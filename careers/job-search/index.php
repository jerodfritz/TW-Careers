<?php
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


foreach ($_REQUEST as $key => $value) {
	if (isset($questionHash[$key])) {		     
         $ks->addQuestion($questionHash[$key], $value);
	}
}
$isAjax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $isAjax = true;
}

ob_start();

if (!$isAjax) {
    // Create a bunch of inputs based on the big XML file.
    echo "<form id='searchForm' action = 'index.php' method='POST'>";
    echo '<div id="location-selects">'.
            '<p>Select desired location:</p>'.
            '<select id="country-select" ></select>'.
            '<select id="state-select" class="loc-select" size="6" multiple="multiple"></select>'.
            '<select id="city-select" class="loc-select" size="6" multiple="multiple"></select>'.
        '</div>';    
    $fields = $ks->getFields();
    foreach ($fields as $key => $field) {
        $keyId = 'kq' . '_' . $key;
        $label = "<label class='kenexa-label' for='$keyId'>" . $field['Name'] . "</label>";
        $type = strtolower($field['Type']);
        switch ($type) {
            case 'radio':
            case 'multi-select':
            case 'select':
                // If it is the location input, put a hidden one in there,
                // as this will be change via the special location widget.
                if($key == KenexaJobQuestions::LOCATION) {
                    echo "<input type=\"hidden\" class='kenexa-question' id=\"$keyId\"  name='questions[$keyId]'></input><br/>";
                }else {
                    echo "$label<select class='kenexa-question' id=\"$keyId\"  name='questions[$keyId]' >";
                    echo "<option value=''>Any</option>";
                    foreach ($field['options'] as $option) {
                        echo "<option>{$option['Code']}</option>";
                    }
                    echo "</select><br/>";
                }
                break;
            case 'text':
                echo "$label<input class='kenexa-question' id=\"$keyId\" name='questions[$keyId]'></input><br/>";
                break;
            case 'textarea':
                echo "$label<textarea class='kenexa-question' id=\"$keyId\" name='questions[$keyId]'></textarea><br/>";
                break;
            default:
                echo "$label<input class='kenexa-question' id=\"$keyId\" name='questions[$keyId]'></input><br/>";
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
    echo "<input id ='ajaxSubmit' type='button' value='Search Jobs'></form>";
	echo "<div id='search-history'><strong>Previous searches:</strong><br/></div>";
    echo "<div id='results'>";
}

// Display search results.
echo "<hr/>";



$arr = $ks->search();

if ($arr) {
    echo "<strong>Total jobs found: " . $arr->OtherInformation->TotalRecordsFound . "</strong><br/>";
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
</body>
</html> 
