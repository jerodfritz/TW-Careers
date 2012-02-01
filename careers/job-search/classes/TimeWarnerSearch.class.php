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
            
            echo '<table id="results-table" ><tr style="background-color:#ccc">';
            foreach($questions as $question=>$text) {
                $class="";
                if ($question != "req") {
                    $class="hover-effect"; 
                    if($sortBy == $question) $class.= " sort-on-this";
                }
                echo "<th id='$question' class='$class'>$text</th>";
            }
            
            
            echo "<p style='margin-bottom:10px'><strong>Total jobs found: " . $arr->OtherInformation->TotalRecordsFound . "</strong></p>";
            $jobs = $arr->Jobs->Job;
            $odd = 0;
            $class="";
            //$qh = KenexaJobQuestions::getQuestionsHash();
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