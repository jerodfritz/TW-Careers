<?php

$pageTitle = 'Careers - Search Jobs - Time Warner';

$metaDescription = 'Search information technology and Internet job openings. Locate job fairs and career events. Post resumes to high tech employers.';

$metaKeywords = 'brassring, job search, job fairs, tech job, it, information technology, employment, resume, monster, headhunter, computer, listing, opening, description, bank, career, internship, opportunity, employment opportunities, classified ads, engineering, search engine, telecommunication, free agent, internet, dice.com, full time, freelance, executive, graphic design, vacancy, executive, part time, classified ads, technology, programmer, post resume, professional, computer work, technical recruiter, project management, research companies, web developer, board, database, Westech, networking, manager, computer, technical writing, engineer, professionals, application, posting, hightech, hiring, interview, expos, employer, qa, consulting, semiconductor, consultant,  computing, hardware, help wanted, technologies, analyst, administrative, administrator, industry';

$dir = '/careers/job-search/';

$stylesheets = array(
    $dir.'css/jquery.multiselect.css', 
    $dir.'css/custom-theme/jquery-ui-1.8.17.custom.css',
    $dir.'css/job-search.css' 
  );

$javascripts = array(
    'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', 
    'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', 
    $dir . '../js/jquery.infieldlabel.min.js',
    $dir.'js/jquery.multiselect.min.js',
    $dir.'js/job-search.js'
  );

require_once ('../../includes/head.php');
require_once ("classes/krumo/class.krumo.php"); 
require_once ("classes/TimeWarnerSearch.class.php");


$tw = new TimeWarnerSearch(dirname(__FILE__) . '/options/options.xml');
?>

<script type="text/javascript">
  var locData = <?php print json_encode($tw->ks->getXRefLocationData()) ?>;
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
				<?php /*include $basePath . 'includes/structure/breadcrumb.php';*/ ?>
				<div id="careers-advanced-search">
				    <h1>Search for Jobs</h1>
					<?php print $tw->printInputs(); ?>
                    <div id='results'>
                      <div id="ajax-loader"><img src="<?php print $dir; ?>images/ajax-loader.gif"/></div>
                      <?php print $tw->displaySearchResults(); ?>
                    </div>
				</div>
			</div>
        </div>
         <?php include $basePath . 'includes/structure/sticky-footer-careers.php'; ?>
    </div>
<?php include $basePath . 'includes/careers-scripts.php'; ?>
</body>
</html>
