<?
  require_once ("../classes/KenexaSearch.class.php");       
  $ks = new KenexaSearch();

  $questionHash = KenexaJobQuestions::getQuestionsHash();

  // Set the desired page number.
  if (isset($_REQUEST['pagenum']) ) $ks->pageNumber = $_REQUEST['pagenum'];
  // Set the search date 
  if (isset($_REQUEST['date_posted']) ) $ks->datePosted = $_REQUEST['date_posted'];
  // Set the sort direction
  if (isset($_REQUEST['sortdir']) ) $ks->sortDir = $_REQUEST['sortdir'];
  // Sort column (division, area_of_interest etc.)
  $limit = (isset($_REQUEST['limit']) ) ? $_REQUEST['limit'] : 2;
  $sortBy = @$_REQUEST['sortby'];
  foreach ($_REQUEST as $key => $value) {	  
    if (isset($questionHash[$key])) {		     
    if ($sortBy && $sortBy == $key) $ks->addQuestion($questionHash[$key], $value, true);
      else $ks->addQuestion($questionHash[$key], $value);
    }
  }
  $ks->hotJobs = (isset($_REQUEST['hotjobs']) ) ? true : false;

  $arr = $ks->search();
  if ($arr->OtherInformation->TotalRecordsFound > 0) {
  //echo "<strong>Total jobs found: " . $arr->OtherInformation->TotalRecordsFound . "</strong><br/>";
  $jobs = $arr->Jobs->Job;
  $jobcounter = 0;
  foreach ($jobs as $job) {
	echo "<a href=\"$job->JobDetailLink\" class=\"careers-job-title\">" . $job->Question[KenexaJobData::JOB_TITLE] . "</a><br/>";
	echo $job->Question[KenexaJobData::DIVISION] . "<br/><br/>";
	if(++$jobcounter>$limit) break;
  }
  echo "<a href=\"../../job-search/\?location=United%20Kingdom%20-%20London\" title=\"London/UK Area\">See All Jobs</a>";
  } else {
  echo "There are currently no open jobs within this area.";
  }
  
?>
