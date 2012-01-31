<?php

$isAjax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $isAjax = true;
}
if(!$isAjax) {
    $pageTitle = 'Careers - Search Jobs - Time Warner';

    $metaDescription = 'Search information technology and Internet job openings. Locate job fairs and career events. Post resumes to high tech employers.';

    $metaKeywords = 'brassring, job search, job fairs, tech job, it, information technology, employment, resume, monster, headhunter, computer, listing, opening, description, bank, career, internship, opportunity, employment opportunities, classified ads, engineering, search engine, telecommunication, free agent, internet, dice.com, full time, freelance, executive, graphic design, vacancy, executive, part time, classified ads, technology, programmer, post resume, professional, computer work, technical recruiter, project management, research companies, web developer, board, database, Westech, networking, manager, computer, technical writing, engineer, professionals, application, posting, hightech, hiring, interview, expos, employer, qa, consulting, semiconductor, consultant,  computing, hardware, help wanted, technologies, analyst, administrative, administrator, industry';

    $dir = '/careers/job-search/';

    $stylesheets = array(
    $dir.'css/jquery.multiselect.css', 
    'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css',
    $dir.'css/job-search.css', );

    $javascripts = array(
    'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', 
    'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', 
    $dir.'js/jquery.multiselect.min.js',
    $dir.'js/job-search.js');

    require_once ('../../includes/head.php');
}
require_once ("classes/KenexaSearch.class.php");
$ks = new KenexaSearch();

$questionHash = array(
    "division" => KenexaJobQuestions::DIVISION,
    "description" => KenexaJobQuestions::DESCRIPTION,
    "location" => KenexaJobQuestions::LOCATION,
    "title" => KenexaJobQuestions::TITLE,
    "area_of_interest" =>  KenexaJobQuestions::AREA_OF_INTEREST,
    "industry" => KenexaJobQuestions::INDUSTRY,
    "position" => KenexaJobQuestions::POSITION_TYPE,
    "keyword" => KenexaJobQuestions::KEYWORD,
    "business_unit" => KenexaJobQuestions::BUSINESS_UNIT
);
   
function basicInputs($ks,$questionHash) {    
    
    // Create a bunch of inputs based on the big XML file.
    echo "<form id='searchForm' action = 'index.php' method='POST'>";
    $fields = $ks->getFields();
    foreach ($fields as $key => $field) {
        $keyId = "";
        foreach ($questionHash as $qName => $qId) {
            if ($qId == $key) {
                $keyId = $qName;
                break;
            }                      
        }
        // echo " $qId " ;             
        //$keyId = 'kq' . '_' . $key;
        $label = "<label class='kenexa-label' for='$keyId'>" . $field['Name'] . "</label>";
        $type = strtolower($field['Type']);
        switch ($type) {
            case 'radio':
            case 'multi-select':
            case 'select':
                // If it is the location input, put a hidden one in there,
                // as this will be change via the special location widget.
                if($key == KenexaJobQuestions::LOCATION) {
                    echo "<input type=\"hidden\" class='kenexa-question' id=\"$keyId\"  name='$keyId'></input><br/>";
                }else {
                    echo "$label<select class='kenexa-question' id=\"$keyId\"  name='$keyId' >";
                    echo "<option value=''>Any</option>";
                    foreach ($field['options'] as $option) {
                        echo "<option>{$option['Code']}</option>";
                    }
                    echo "</select><br/>";
                }
                break;
            case 'text':
                echo "$label<input class='kenexa-question' id=\"$keyId\" name='$keyId'></input><br/>";
                break;
            case 'textarea':
                echo "$label<textarea class='kenexa-question' id=\"$keyId\" name='$keyId'></textarea><br/>";
                break;
            default:
                echo "$label<input class='kenexa-question' id=\"$keyId\" name='$keyId'></input><br/>";
        }
    }
    /* 		
      // Create a form and bunch of inputs based on the scraper data.
      $questions = $ks->getOptions();
      echo "<p><strong>Enter your search criteria...</strong></p>";
      echo "<form id='searchForm' action = 'index.php' method='POST'>";
      foreach($questions as $key => $questionId) {
      $keyId = $key .'_'.$questionId;
      echo    "<label for='$keyId'>$key</label>".
      "<input type='text' class='kenexa-question' name='questions[$keyId]' id = '$keyId'><br/>";
      }
     */
    echo "<input id ='ajaxSubmit' type='button' value='SUBMIT'/></form>";
//	echo "<div id='search-history'><strong>Previous searches:</strong><br/></div>";
    echo "<div id='results'>";
 

}

function skinInputs($ks,$questionHash) {
    
    $inputs = array();
       // Create a bunch of inputs based on the big XML file.
    echo "<form id='searchForm' action = 'index.php' method='POST'>";
    $fields = $ks->getFields();
    
    foreach ($fields as $key => $field) {
        $col="";
        $keyId = "";
        foreach ($questionHash as $qName => $qId) {
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
                // If it is the location input, put a hidden one in there,
                // as this will be change via the special location widget.
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
    
    ////////////////////////////////////////
    echo "</form>";
//    echo "<div id='search-history'><strong>Previous searches:</strong><br/></div>";
    echo "<div id='results'>";
 
}


function displayJobs($ks) {
	$arr = $ks->search();
	// Display search results.
	echo "<hr/>";
	if ($arr) {
		echo "<p><strong>Jobs Found: " . $arr->OtherInformation->TotalRecordsFound . "</strong></p>";
		$jobs = $arr->Jobs->Job;
		foreach ($jobs as $job) {
			echo "<h3>" . $job->Question[KenexaJobData::JOB_TITLE] . "</h3>";
			echo "Location: " . $job->Question[KenexaJobData::LOCATION] . "<br/>";
			//echo $job->HotJob."<br/>";
			echo "Last updated: " . $job->LastUpdated . "<br/>";
			echo "<p>" . nl2br($job->JobDescription) . "</p>";
			echo "<a href=\"$job->JobDetailLink\">Click here for more details</a>";
		}
	}
}


function displaySkinJobs($ks) {
	$arr = $ks->search();
	
	if ($arr) {
		?>
		<table id="results-table" ><tr style="background-color:#ccc"><th>Posting Job Title</th><th>Location</th><th>Division</th><th>Area of Interest</th><th>Type</th><th>Req #</th><th>Date</th></tr>
<?
		
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
			//echo $job->HotJob."<br/>";
			//echo "Last updated: " . $job->LastUpdated . "<br/>";
			//echo "<p>" . nl2br($job->JobDescription) . "</p>";
			//echo "<a href=\"$job->JobDetailLink\">Click here for more details</a>";
		}
		echo "</table>";
	}
}

foreach ($_REQUEST as $key => $value) {
	if (isset($questionHash[$key])) {		     
         $ks->addQuestion($questionHash[$key], $value);
	}
}

/*
// Do we have any question inputs?
if (isset($_REQUEST['questions'])) {
    // Add each one to the search.
    foreach ($_REQUEST['questions'] as $key => $value) {
        if ($value !== "" ) {	// Ignore empty fields (should not have received any).
            $qId = substr($key, strpos($key, '_') + 1);    // Get the number part of the input name (the questionId).            
            $ks->addQuestion($qId, $value);
        }
    }
}
 */


ob_start();


if (!$isAjax) {
    skinInputs($ks,$questionHash);
}



displaySkinJobs($ks);

if ($isAjax) {
    // If ajax, just return the html of the search results.
    ob_end_flush();
    exit();
}
// Close the results div.
echo "</div>";


//   ["HotJob"]=>string(2) "No"
//      ["LastUpdated"]=>
//      string(11) "22-Dec-2011"
//      ["JobDescription"]=>

/*
 * ["OtherInformation"]=>
  object(SimpleXMLElement)#8 (4) {
  ["TotalRecordsFound"]=>
  string(2) "32"
  ["MaxPages"]=>
  string(1) "1"
  ["StartDoc"]=>
  string(1) "1"
  ["PageNumber"]=>
  string(1) "1"
  }
 */
$content = ob_get_contents();
ob_end_clean();
?>

<script type="text/javascript">
  var locData = <?php print json_encode($ks -> getXRefLocationData()) ?>;
</script>


<body>
	<div id="wrapper">
		<div id="container">
			<div id="header">
				<?php  include $basePath . 'includes/structure/main-nav.php'; ?>
				<?php  include $basePath . 'includes/structure/careers-nav.php'; ?>
			</div>
		</div>
		<div id="content">
			<div class="content-width">
				<?php include $basePath . 'includes/structure/breadcrumb.php'; ?>
                <h1>Careers</h1>				
				<div id="careers-advanced-search">
					<div id="ajax-loader"><img src="<?php print $dir; ?>images/ajax-loader.gif"/></div>
					<?php echo $content; ?>		
				</div>
			</div>
        </div>
         <?php include $basePath . 'includes/structure/sticky-footer-careers.php'; ?>
    </div>
<?php include $basePath . 'includes/careers-scripts.php'; ?>
</body>
</html>
