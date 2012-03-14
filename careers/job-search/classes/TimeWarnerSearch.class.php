<?php

require_once('KenexaSearch.class.php');

class TimeWarnerSearch { 

  /**
  * KenexaSearch Wrapper
  * @ks KenexaSearch
  */
  public $ks = null;
  private $request = null;
  
  public function __construct($optionsXML) {

    $this->ks = new KenexaSearch($optionsXML);
    $questionHash = KenexaJobQuestions::getQuestionsHash();
	
	$this->request = $_REQUEST;
	
	// Set the desired page number.
	if (isset($_REQUEST['pagenum']) ) $this->ks->pageNumber = $_REQUEST['pagenum'];
	
	// Set the search date 
	if (isset($_REQUEST['date_posted']) ) $this->ks->datePosted = $_REQUEST['date_posted'];
	
	// Set the sort direction
	if (isset($_REQUEST['sortdir']) ) $this->ks->sortDir = $_REQUEST['sortdir'];
	
	// Sort column (division, area_of_interest etc.)
	$sortBy = @$_REQUEST['sortby'];
    
	foreach ($_REQUEST as $key => $value) {	  
            if (isset($questionHash[$key])) {		     
            if ($sortBy && $sortBy == $key) $this->ks->addQuestion($questionHash[$key], $value, true);
		 else $this->ks->addQuestion($questionHash[$key], $value);
	  }
    }
  } 


  function getInputs() {
    $inputs = array();
    
    $fields = $this->ks->getFields();
    foreach ($fields as $key => $field) {
        $col="";
        $keyId = "";
        foreach (KenexaJobQuestions::getQuestionsHash() as $qName => $qId) {
            if ($qId == $key) {
                $keyId = $qName;
                break;
            }                      
        }
        
        $type = strtolower($field['Type']);
        
        // These are the titles displayed in the multi-selects (not location).
        $titles = array("division"=>"Division",
                        "area_of_interest"=>"Area of Interest",
                        "industry"=>"Industry",
                        "position"=>"Position Type"
            );
        switch ($type) {
            case 'radio':
            case 'multi-select':
            case 'select':
                // Handle Location differently
                if($key == KenexaJobQuestions::LOCATION) {
                    $col .= "<input type=\"hidden\" class='kenexa-question' id=\"$keyId\"  name='$keyId'></input>";
                }else {
                    $title=$titles[$keyId];
                    $col.= "<select title='$title' class='kenexa-question multi-select'  id=\"$keyId\" name='$keyId' multiple='multiple' >";
                    //$col .= "<option value=''>Any</option>";
                    foreach ($field['options'] as $option) {
                        $selected = '';
                        if(isset($this->request[$keyId])){
                          $params = split(",",$this->request[$keyId]);
                          foreach($params as $param){
                            if(trim($option['Code']) == trim($param) || trim($option['Description']) == trim($param)){
                              $selected = "selected";
                              break;
                            }
                          }
                        }
                        $col.= "<option $selected>{$option['Description']}</option>";
                    }
                    $col.= "</select>";
                }
                break;
            case 'text':
                $value = '';
                if(isset($this->request[$keyId])){
                  $value = "value='".$this->request[$keyId]."'";
                }
                $col .= "<input class='kenexa-question' id=\"$keyId\" name='$keyId' $value></input>";
                break;
            case 'textarea':
                $col .= "<textarea class='kenexa-question' id=\"$keyId\" name='$keyId'></textarea>";
                break;
            default:
                $col .= "<input class='kenexa-question' id=\"$keyId\" name='$keyId'></input>";
        }
        $inputs[$key] = $col;
    }
    if(isset($_GET['history'])){
      echo "<div id='search-history'><strong>Previous searches:</strong><br/></div>";
    }
    return $inputs; 
}

  function displaySearchResults() {
	$arr = $this->ks->search();
	if ($arr) {
		$questions = array( "title"=>"Posting Job Title",
		        "location"=>"Location",
		        "division"=>"Division",
		        "area_of_interest"=>"Area of Interest",
		        "position"=>"Type",
		        "req"=>"Req #",
		        "date"=>"Date");
		$sortBy = "date";
		if (isset($_REQUEST['sortby'])) $sortBy = $_REQUEST['sortby'];
		
		$maxPages = intval($arr->OtherInformation->MaxPages);
		
		echo "<div id='search-stats'>";
		
		// Show the search results stats.
		
		$resultsPerPage = 50;
		$page = intval($arr->OtherInformation->PageNumber) ;
		$total = intval($arr->OtherInformation->TotalRecordsFound);
		$to = $page * $resultsPerPage;
		$to = ($to > $total)? $total : $to ;
		$from = (($page-1) * $resultsPerPage ) + 1;
		
		if($total > 0) {
			echo sprintf( '<div class="results-count">Showing %s - %s of %s Results</div>', $from,  $to, $total);
			
			// If more than one page, show a link for each page.
			// JS searches for these and adds events to do a paginated search.
			if($maxPages > 1) {
				echo "<div class='page-info'>";
				if($this->ks->pageNumber > 1) {
					$num = $this->ks->pageNumber - 1;
					echo "<div class='page-link$class' num='$num'>Previous</div>";
				}
				for($i=1;$i<=$maxPages;$i++) {
					$class="";
					if($i == $this->ks->pageNumber ) $class = " page-link-current";
						// additional class for current page.
						echo "<div class='page-link$class' num='$i'>$i</div>";
					}
					if($this->ks->pageNumber < $maxPages) {
						$num = $this->ks->pageNumber + 1;
						echo "<div class='page-link$class' num='$num'>Next</div>";
					}
					echo "</div>";		
				}
			echo "</div>";
			echo '<table id="results-table" style="clear:both" ><tr class="header">';
			foreach($questions as $question=>$text) {
				$class="";
				if ($question != "req"){ // && $question != "title") {
					$class="hover-effect"; 
					if ($sortBy == $question) {
						$class.= " sort-on-this";
						if ($this->ks->sortDir == "ASC") $class.= " sort-asc";
						else $class.= " sort-desc";
					}
				}
				echo "<th id='$question' class='$class'>$text<span class='sort-arrow'></span></th>";
			}
			
			$jobs = $arr->Jobs->Job;
			$odd = 0;
			$class="";
			//$qh = KenexaJobQuestions::getQuestionsHash();
			$num = 1;
			foreach ($jobs as $job) {			
				if($odd) $class="odd";
				else $class="";
				echo "<tr id='result-$num'>";
				echo "<td class='$class title'>". "<span onclick='showDetails(this);return false;' class='arrow'></span><a href='{$job->JobDetailLink}' onclick='showDetails(this);return false;' class='details-link'>".$job->Question[KenexaJobData::JOB_TITLE] . "</a></td>";
				echo "<td class='$class location'>". $job->Question[KenexaJobData::LOCATION] . "</td>";
				echo "<td class='$class division'>". $job->Question[KenexaJobData::DIVISION] . "</td>";
				echo "<td class='$class industry' onMouseOver='showTruncated(this);' onMouseOut='hideTruncated(this);' >". $this->Truncate($job->Question[KenexaJobData::INDUSTRY],45,true) . "</td>";
				echo "<td class='$class type'>". $job->Question[KenexaJobData::POSITION_TYPE] . "</td>";
				echo "<td class='$class req'>". $job->Question[KenexaJobData::REQUISITION_NO] . "</td>";
				echo "<td class='$class updated last'>". $job->LastUpdated . "</td>";
				echo "</tr>";
				echo "<tr id='result-$num-details' class='details'>";
				echo "<td colspan='7' class='details-cell'>";
				//echo $this->Truncate($job->JobDescription,400, false);
				echo $this->TruncateHtml($job->JobDescription, 400, ' <span class="ellipses">...</span>', false, true);
				echo "<div class='view-full'><a href='{$job->JobDetailLink}' target='_blank' title='View Full Description'>View Full Description</a></div>";
				echo "</td>";
				echo "</tr>";
				$odd ^= 1;
				$num ++;
			}
			echo "</table>";
		} else {
			echo '<div class="no-results"><div class="results-count">Your Search returned 0 Results</div></div>'; // Hack to show an extra row to prevent IE from resizing columns
		}
	}else {
	// If we get here, there's either no questions or maybe an error in the search.
	}
  }
  
  function Truncate($string, $length, $output_truncated) {
    $ret = $string;
    if (strlen($string) > $length) {
		$visible_string =  $this->TokenTruncate($string,$length);
		$ret = $this->CloseTags($visible_string);
		$ret .= '<span class="ellipses">...</span>';
		if( $output_truncated ) {
  		  $ret .= '<span class="truncated">';
		  $ret .= substr($string,strlen($visible_string),strlen($string));
		  $ret .= '</span>';
		}
    }
    return $ret;
  }
  
  function TokenTruncate($string, $your_desired_width) {
    $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
    $parts_count = count($parts);

    $length = 0;
    $last_part = 0;
    for (; $last_part < $parts_count; ++$last_part) {
    $length += strlen($parts[$last_part]);
      if ($length > $your_desired_width) { break; }
    }

    return implode(array_slice($parts, 0, $last_part));
  }

  function CloseTags($html) {
    preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];   #put all closed tags into an array
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
      return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i=0; $i < $len_opened; $i++) {
      if (!in_array($openedtags[$i], $closedtags)){
        $html .= '</'.$openedtags[$i].'>';
      } else {
        unset($closedtags[array_search($openedtags[$i], $closedtags)]);    }
    }  return $html;
  } 
  
  /**
   * TruncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
   *
   * @param string $text String to truncate.
   * @param integer $length Length of returned string, including ellipsis.
   * @param string $ending Ending to be appended to the trimmed string.
   * @param boolean $exact If false, $text will not be cut mid-word
   * @param boolean $considerHtml If true, HTML tags would be handled correctly
   *
   * @return string Trimmed string.
   */
  function TruncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                    unset($open_tags[$pos]);
                    }
                // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length+$content_length> $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if($total_length>= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
  }  
  
  
}