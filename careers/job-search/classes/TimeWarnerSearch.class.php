<?php

require_once('KenexaSearch.class.php');

class TimeWarnerSearch { 

  /**
  * KenexaSearch Wrapper
  * @ks KenexaSearch
  */
  public $ks = null;

  public function __construct() {
    $this->ks = new KenexaSearch();
    $questionHash = KenexaJobQuestions::getQuestionsHash();
    foreach ($_REQUEST as $key => $value) {
	  if (isset($questionHash[$key])) {		     
         $this->ks->addQuestion($questionHash[$key], $value);
	  }
    }
  } 


  function printInputs() {
    $inputs = array();
    echo "<form id='searchForm' action = './' method='POST'>";
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
        switch ($type) {
            case 'radio':
            case 'multi-select':
            case 'select':
                // Handle Location differently
                if($key == KenexaJobQuestions::LOCATION) {
                    $col .= "<input type=\"hidden\" class='kenexa-question' id=\"$keyId\"  name='$keyId'></input>";
                }else {
                    $col.= "<select class='kenexa-question' id=\"$keyId\"  name='$keyId' >";
                    $col .= "<option value=''>Any</option>";
                    foreach ($field['options'] as $option) {
                        $col.= "<option>{$option['Code']}</option>";
                    }
                    $col.= "</select>";
                }
                break;
            case 'text':
                $col .= "<input class='kenexa-question' id=\"$keyId\" name='$keyId'></input>";
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
    echo    "<div class=\"skin-inputs-third\">".
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
		echo '<table id="results-table" ><tr style="background-color:#ccc"><th>Posting Job Title</th><th>Location</th><th>Division</th><th>Area of Interest</th><th>Type</th><th>Req #</th><th>Date</th></tr>';
		echo "<p style='margin-bottom:10px'><strong>Total jobs found: " . $arr->OtherInformation->TotalRecordsFound . "</strong></p>";
		$jobs = $arr->Jobs->Job;
		$odd = 0;
		$class="";
		
		foreach ($jobs as $job) {			
			if($odd) $class="odd";
			else $class="";
			echo "<tr>";
			echo "<td class='$class'>". "<a href='{$job->JobDetailLink}'>".$job->Question[KenexaJobData::JOB_TITLE] . "</a></td>";
			echo "<td class='$class'>". $job->Question[KenexaJobData::LOCATION] . "</td>";
			echo "<td class='$class'>". $job->Question[KenexaJobData::DIVISION] . "</td>";
			echo "<td class='$class'>". $job->Question[KenexaJobData::INDUSTRY] . "</td>";
			echo "<td class='$class'>". $job->Question[KenexaJobData::POSITION_TYPE] . "</td>";
			echo "<td class='$class'>". $job->Question[KenexaJobData::REQUISITION_NO] . "</td>";
			echo "<td class='$class last'>". $job->LastUpdated . "</td>";
			echo "<tr/>";
			$odd ^= 1;
		}
		echo "</table>";
	}
  }
}