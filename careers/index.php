<?php 
$pageTitle = 'Careers - Working With Us - Time Warner';

$metaDescription = 'At Time Warner, we offer employment in innumerable areas, both disciplinary and geographic, within the major arms of the media.';

$metaKeywords = 'Time Warner Careers, Jobs, Employment, Resume, Turner Broadcasting System Jobs, TBS Jobs, Home Box Office jobs, HBO jobs, Warner Bros. Entertainment jobs, WB jobs, Warner Bros. jobs, Time Inc. jobs, Open positions, Candidates';

$dir = '/careers/';
$stylesheets = array (
  $dir . 'carousel/carousel.css',
  $dir . 'css/style.css',
);

$javascripts = array (
  'http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js',
  '/js/global.js',
  $dir . 'js/jquery.infieldlabel.min.js',
  $dir . 'carousel/carousel.js',
  $dir . 'js/script.js',
);

include '../includes/careers-head.php'; 
?>
<body>
    <div id="wrapper">
        <div id="container">
            <div id="header">
                 <?php include $basePath . 'includes/structure/main-nav.php'; ?>
                 <?php include $basePath . 'includes/structure/careers-nav.php'; ?>
            </div>
        </div>
        <div id="content">
            <div class="content-holder">
				<?php include $basePath . 'includes/structure/breadcrumb.php'; ?>
                  <script type="text/javascript">
                  $(function(){
                    Caro.fromXML("<?php print $dir ?>carousel/slides.xml",$('#carousel-wrap'));
                  });
                  </script>
                  <div id="carousel-bg" class="carousel">
                    <div id="carousel-wrap">
                      <div class="wheel"></div>
                    </div>
                  </div>
                  <h1>Careers</h1>
                 <div class="content-width">
                   <div class="search-and-login">
                     <div class="search">
                       <div class="inner">
                         <form class="search-form" action="job-search/" id="keyword-search-form" method="get">
                            <label for="search-keywords">Keywords</label>
                            <input type="text" name="keyword" value="" id="search-keywords">
                            <button value="submit" class="black" onclick='$("#keyword-search-form").submit()'><span>Submit</span></button>
                         </form>
                         <button value="Advanced Search" href="job-search" class="grey"><span>Advanced Search</span></button>
                       </div>
                     </div>
                     <div class="login">
                       <div class="inner">
                         <button value="Login" class="blue" href="https://careers.timewarner.com/1033/asp/tg/submitnow.asp?partnerid=391&siteid=36&codes=XSNW"><span><a href="https://careers.timewarner.com/1033/asp/tg/submitnow.asp?partnerid=391&siteid=36&codes=XSNW" title="Login">Login</a></span></button>
                         <a href="https://careers.timewarner.com/1033/asp/tg/submitnow.asp?partnerid=391&siteid=36&codes=XSNW">Login</a> to access your profile<br />or <a href="https://careers.timewarner.com/1033/asp/tg/submitnow.asp?partnerid=391&siteid=36&codes=XSNW">create a new account</a>
                       </div>
                     </div>
                   </div>
                 </div>
                 <div class="content-width"><div class="bottom-boxes"> <!-- begin boxes -->
                   <button id="third-width-box-first" class="third-width-box third-width-box-first" href="working-with-us/why-time-warner/">
                     <div class="wrapper"><div class="inner">
                       <div class="blue-highlight">Content. Company. Innovations.</div>
                       Learn more about why Time Warner is an<br/>
                       employer of choice.
                     </div></div>
                   </button>
                   <button id="third-width-box-center" class="third-width-box third-width-box-center" href="areas-of-operation/job-spotlight/">
                     <div class="wrapper"><div class="inner">
                       Stay up to date on the latest recruitment<br/>
                       activities and featured job opportunities.
                     </div></div>
                   </button>
                   <div id="third-width-box-last" class="third-width-box third-width-box-last">
                     <div class="wrapper"><div class="inner">
                       <a href="working-with-us/stay-connected/" target="_blank" title="Stay Connected" ><img src="<?php print $dir ?>images/buttons/arrow.png" class="connect-button" title="Stay Connected Bullet Arrow" alt="Stay Connected Bullet Arrow" /></a>
                       <a href="http://www.facebook.com/TimeWarner" target="_blank" title="Connect Facebook" ><img src="<?php print $dir ?>images/buttons/facebook.png" class="connect-button" title="Connect Facebook" alt="Connect Facebook" /></a>
                       <a href="http://www.youtube.com/user/TimeWarnerCable" target="_blank" title="Connect YouTube" ><img src="<?php print $dir ?>images/buttons/youtube.png" class="connect-button" title="Connect YouTube" alt="Connect YouTube" /></a>
                       <a href="https://twitter.com/twxcorp" target="_blank" title="Connect Twitter" ><img src="<?php print $dir ?>images/buttons/twitter.png" class="connect-button" title="Connect Twitter" alt="Connect Twitter" /></a>
                       <a href="http://www.linkedin.com/company/time-warner-inc." target="_blank" title="Connect LinkedIn" ><img src="<?php print $dir ?>images/buttons/linkedin.png" class="connect-button" title="Connect LinkedIn" alt="Connect LinkedIn" /></a>
                     </div></div>
                   </div>
                 </div>
            </div></div> <!-- end boxes -->
        </div>
         <?php include $basePath . 'includes/structure/sticky-footer-careers.php'; ?>
    </div>
<?php include $basePath . 'includes/careers-scripts.php'; ?>
</body>
</html>
