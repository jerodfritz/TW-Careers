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
  
    $options = simplexml_load_string(file_get_contents($optionsXML));
    $json = json_encode($options);
    $options = json_decode($json,TRUE);
    $fields = array();
    foreach($options as $name => $option){
  	  $obj = array();
  	  $obj['Name'] = $name;
	  $obj['Type'] = $option['@attributes']['type'];
	  $obj['options'] = array();
	  if(isset($option['Options'])){
	  	foreach ($option['Options'] as $o) {
		    $opt = array();		   
		    $val = split("\|",$o);
		    $desc = split('=',$val[1]);		    
		    $opt['Description'] = $desc[0];
		    $val = split("\|",$o);
		    $code = split('=',$val[3]);		    
		    $opt['Code'] = $code[0];
		    $obj['options'][]  = $opt;
		}
  	  }
	  $fields[$option['@attributes']['questionNumber']] = $obj;
    }
  
    $this->ks = new KenexaSearch($fields);
    $questionHash = KenexaJobQuestions::getQuestionsHash();
	
	$this->request = $_REQUEST;
	
	// Set the desired page number.
	if (isset($_REQUEST['pagenum']) ) $this->ks->pageNumber = $_REQUEST['pagenum'];
	
	// Set the search date 
	if (isset($_REQUEST['date_posted']) ) $this->ks->datePosted = $_REQUEST['date_posted'];
	
	// Sort column (division, area_of_interest etc.)
	$sortBy = @$_REQUEST['sortby'];
    
	foreach ($_REQUEST as $key => $value) {	  
	  if (isset($questionHash[$key])) {		     
         if ($sortBy && $sortBy == $key) $this->ks->addQuestion($questionHash[$key], $value, true);
		 else $this->ks->addQuestion($questionHash[$key], $value);
	  }
    }
  } 


  function printInputs() {
    $inputs = array();
    
    echo "<form id='searchForm' action = './' method='GET'>";
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
                    $col.= "<select title='$title' class='kenexa-question multi-select'  name='$keyId' multiple='multiple' >";
                    //$col .= "<option value=''>Any</option>";
                    foreach ($field['options'] as $option) {
                        $selected = '';
                        if(isset($this->request[$keyId])){
                          $params = split(",",$this->request[$keyId]);
                          foreach($params as $param){
                            if($option['Code'] == $param){
                              $selected = "selected";
                              break;
                            }
                          }
                        }
                        $col.= "<option $selected>{$option['Code']}</option>";
                    }
                    $col.= "</select>";
                }
                break;
            case 'text':
                $value = '';
                if(isset($this->request[$keyId])){
                  $value = "value='".$this->request[$keyId]."'";
                }
                if($keyId == 'keyword'){
                  $col .=  "<div class='infieldwrap'>";
                  $col .=  "<label for='keyword' class='infield'>Keyword / Requisition #</label>";
                  $col .= "<input class='kenexa-question' id=\"$keyId\" placeholder='Keyword / Requisition #' name='$keyId' $value></input>";
                  $col .= "<a href='#' class='keyword-help-button'><span></span></a>";
                  $col .=  "</div>";
                } else {
                  $col .= "<input class='kenexa-question' id=\"$keyId\" name='$keyId' $value></input>";
                }
                break;
            case 'textarea':
                $col .= "<textarea class='kenexa-question' id=\"$keyId\" name='$keyId'></textarea>";
                break;
            default:
                $col .= "<input class='kenexa-question' id=\"$keyId\" name='$keyId'></input>";
        }
        $inputs[$key] = $col;
    }
    echo "<div id=\"skin-inputs-wrap\">";
    echo    "<div class=\"skin-inputs-third first\">".
            $inputs[KenexaJobQuestions::DIVISION].
            $inputs[KenexaJobQuestions::AREA_OF_INTEREST].
            $inputs[KenexaJobQuestions::KEYWORD].
            "</div>";
    echo    "<div class=\"skin-inputs-third\">".
                $inputs[KenexaJobQuestions::LOCATION].
                "<input id ='ajaxSubmit' type='button' value='SUBMIT'>".
            "</div>";
    
    echo    "<div class=\"skin-inputs-third\">".
                "<div class=\"inputs-wrap\">".
                    $inputs[KenexaJobQuestions::INDUSTRY].
                    $inputs[KenexaJobQuestions::POSITION_TYPE].
                "</div>".
            "</div>";
    echo "</div>";
    echo "</form>";
//    echo "<div id='search-history'><strong>Previous searches:</strong><br/></div>";
 
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
                  echo sprintf( '<div class="results-count">Showing %s - %s of %s Results</div>', $from,  $to, $total);				} else {
                  echo '<div class="results-count">Your Search returned 0 Results</div>';
                }

				// If more than one page, show a link for each page.
				// JS searches for these and adds events to do a paginated search.
				if($maxPages > 1) {
					echo "<div class='page-info'>";
					echo "<strong>Pages: </strong>";
					for($i=1;$i<=$maxPages;$i++) {
						$class="";
						if($i == $this->ks->pageNumber ) $class = " page-link-current";	// additional class for current page.
						echo "<div class='page-link$class'>[$i] </div>";
					}
					echo "</div>";		
				}
			echo "</div>";
			
            echo '<table id="results-table" style="clear:both" ><tr class="header">';
            foreach($questions as $question=>$text) {
                $class="";
                if ($question != "req") {
                    $class="hover-effect"; 
                    if($sortBy == $question) $class.= " sort-on-this";
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
                echo "<td class='$class title'>". "<a href='{$job->JobDetailLink}'>".$job->Question[KenexaJobData::JOB_TITLE] . "</a></td>";
                echo "<td class='$class location'>". $job->Question[KenexaJobData::LOCATION] . "</td>";
                echo "<td class='$class division'>". $job->Question[KenexaJobData::DIVISION] . "</td>";
                echo "<td class='$class industry'>". $job->Question[KenexaJobData::INDUSTRY] . "</td>";
                echo "<td class='$class type'>". $job->Question[KenexaJobData::POSITION_TYPE] . "</td>";
                echo "<td class='$class req'>". $job->Question[KenexaJobData::REQUISITION_NO] . "</td>";
                echo "<td class='$class updated last'>". $job->LastUpdated . "</td>";
                echo "<tr/>";
                $odd ^= 1;
                $num ++;
            }
            echo "</table>";
        }
  }
}