<?php
$pageTitle = 'Careers - Search Jobs - Time Warner';

$metaDescription = 'Search information technology and Internet job openings. Locate job fairs and career events. Post resumes to high tech employers.';

$metaKeywords = 'brassring, job search, job fairs, tech job, it, information technology, employment, resume, monster, headhunter, computer, listing, opening, description, bank, career, internship, opportunity, employment opportunities, classified ads, engineering, search engine, telecommunication, free agent, internet, dice.com, full time, freelance, executive, graphic design, vacancy, executive, part time, classified ads, technology, programmer, post resume, professional, computer work, technical recruiter, project management, research companies, web developer, board, database, Westech, networking, manager, computer, technical writing, engineer, professionals, application, posting, hightech, hiring, interview, expos, employer, qa, consulting, semiconductor, consultant,  computing, hardware, help wanted, technologies, analyst, administrative, administrator, industry';

$dir = '/careers/job-search/';

$stylesheets = array(
    $dir . 'css/jquery.multiselect.css',
    $dir . 'css/custom-theme/jquery-ui-1.8.17.custom.css',
    $dir . 'css/job-search.css'
);

$javascripts = array(
    'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', 
    'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', 
    $dir.'js/jquery.tools.min.js',
    $dir.'js/jquery.multiselect.min.js',
    $dir.'js/job-search.js'
  );

require_once ('../../includes/careers-head.php');
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
                <?php include $basePath . 'includes/structure/main-nav.php'; ?>
                <?php include $basePath . 'includes/structure/careers-nav.php'; ?>
            </div>
        </div>
        <div id="content">
            <div class="content-width">
                <?php  include $basePath . 'includes/structure/breadcrumb.php';  ?>
                <div id="careers-advanced-search">
                    <h1>Search for Jobs</h1>
                    <?php $inputs = $tw->getInputs(); ?>
                    <form id='searchForm' action = './' method='GET'>
                        <div id="inputs-wrap">
                            <div class="inputs-third first">
    		                    <?php print $inputs[KenexaJobQuestions::DIVISION] ?>
	    	                    <?php print $inputs[KenexaJobQuestions::AREA_OF_INTEREST] ?>
	    	                    <label for='keyword' class='infield'>Keyword / Requisition #</label>
		                        <?php print $inputs[KenexaJobQuestions::KEYWORD] ?><a href="keyword-help/" rel="#overlay" target="_new" title="Help with keyword searching" class='keyword-help-button'><span></span></a>
                                <div id="date-options-wrap">
                                
                                    <div id="date-radios-wrap">
                                
										<div class="date-radio-wrap">
											<input class="date-radio" type="radio" id ="date-select-option1" name="date-select-option" value="all-dates" />
											<label for="date-select-option1" class="date-label" >All Posting Dates</label>
										</div>
										<div class="date-radio-wrap">
											<input class="date-radio" type="radio" id ="date-select-option2" name="date-select-option" value="posted-after" />
											<label for="date-select-option2" class="date-label">Include All Jobs Updated After</label>
											<input name ='date-input' id ="date-input"  type='text'  >
											<input type="hidden" id="date-value">
										</div>
                                    </div>  
                                </div>
                                        
                                </div>
                            
                            <div class="inputs-third second">
                                <?php print $inputs[KenexaJobQuestions::LOCATION] ?>
                                <div id="form-action-buttons">
                                  <div id="clear-button">Clear</div>
                                  <input id ='ajaxSubmit' type='button' value='SUBMIT'>
                                </div>
                            </div>
                            <div class="inputs-third third">
                                <div class="inputs-wrap">
                                    <?php print $inputs[KenexaJobQuestions::INDUSTRY] ?>
                                    <?php print $inputs[KenexaJobQuestions::POSITION_TYPE] ?>
                                    <ul id="search-diff-language-links">
                                        <li><a href="https://careers.timewarner.com/1036/ASP/TG/cim_home.asp?partnerid=391&siteid=5145">Chercher des emplois en Fran&ccedil;ais</a></li>
                                        <li><a href="https://careers.timewarner.com/1031/ASP/TG/cim_home.asp?partnerid=391&siteid=5144">Suchen nach Stellenangeboten auf Deutsch</a></li>
                                        <li><a href="https://careers.timewarner.com/1040/ASP/TG/cim_home.asp?partnerid=391&siteid=5243">Cercare dei lavori in Italiano</a></li>
                                        <li><a href="https://careers.timewarner.com/3082/ASP/TG/cim_home.asp?partnerid=391&siteid=5244">Buscar ofertas de empleo en Espa&ntilde;ol </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="ajax-loader"><img src="<?php print $dir; ?>images/ajax-loader.gif"/></div>
                    <div id='results'>
                    <?php $tw->displaySearchResults(); ?>
                    </div>
                    <div id="search-results-footer">
                        <div class="left">
                            If you are experiencing any technical issues with our career site, please contact: <a href="mailto:RecruitAdmin@timewarner.com">RecruitAdmin@timewarner.com</a> .<br/>
                            Please be sure to provide a detailed explanation of the issue you are encountering.
                        </div>
                        <div class="right">
                            <a href="#" alt="Career Site Help" title="Career Site Help">Career Site Help</a><br/>
                            <a href="#" alt="Website Accessibility" title="Website Accessibility">Website Accessibility</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include $basePath . 'includes/structure/sticky-footer-careers.php'; ?>
    </div>
    <div id="overlay" class="overlay"><div class="content-wrap"></div></div>
    <?php include $basePath . 'includes/careers-scripts.php'; ?>
</body>
</html>