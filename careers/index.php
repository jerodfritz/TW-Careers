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
  $dir . 'js/jquery.infieldlabel.min.js',
  $dir . 'carousel/carousel.js',
  $dir . 'js/script.js',
);

include '../includes/head.php'; 
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
                         <form class="search-form" action="job-search/" method="get">
                            <label for="search-keywords">Keywords</label>
                            <input type="text" name="questions[kq_1158]" value="" id="search-keywords">
                            <button value="submit" class="black"><span>Submit</span></button>
                         </form>
                         <button value="Advanced Search" class="grey"><span>Advanced Search</span></button>
                       </div>
                     </div>
                     <div class="login">
                       <div class="inner">
                         <button value="Login" class="blue"><span><a href="https://careers.timewarner.com/1033/ASP/TG/cim_home.asp?partnerid=391&siteid=36" title="Login">Login</a></span></button>
                         <a href="#">Login</a> to access your profile<br />or <a href="#">create a new account</a>
                       </div>
                     </div>
                   </div>
                 </div>
                 <div class="content-width">
                   <button class="third-width-box third-width-box-first">
                     <div class="inner">
                       <div class="blue-highlight">Content. Company. Innovations.</div>
                       Learn more about why Time Warner is an<br/>
                       employer of choice.
                     </div>
                   </button>
                   <button class="third-width-box third-width-box-center">
                     <div class="inner">
                       Stay up to date on the latest recruitment<br/>
                       activitied and featured job opportunities.
                     </div>
                   </button>
                   <button class="third-width-box third-width-box-last">
                     <div class="inner">
                       <img src="<?php print $dir ?>images/buttons/arrow.png" class="connect-button" title="Stay Connected Bullet Arrow" alt="Stay Connected Bullet Arrow" />
                       <a href="http://www.facebook.com/TimeWarner" target="_blank" title="Connect Facebook" ><img src="<?php print $dir ?>images/buttons/facebook.png" class="connect-button" title="Connect Facebook" alt="Connect Facebook" /></a>
                       <a href="http://www.youtube.com/user/TimeWarnerCable" target="_blank" title="Connect YouTube" ><img src="<?php print $dir ?>images/buttons/youtube.png" class="connect-button" title="Connect YouTube" alt="Connect YouTube" /></a>
                       <a href="https://twitter.com/twxcorp" target="_blank" title="Connect Twitter" ><img src="<?php print $dir ?>images/buttons/twitter.png" class="connect-button" title="Connect Twitter" alt="Connect Twitter" /></a>
                       <a href="http://www.linkedin.com/company/time-warner-inc." target="_blank" title="Connect LinkedIn" ><img src="<?php print $dir ?>images/buttons/linkedin.png" class="connect-button" title="Connect LinkedIn" alt="Connect LinkedIn" /></a>
                     </div>
                   </button>
                 </div>
            </div>
        </div>
         <?php include $basePath . 'includes/structure/sticky-footer-careers.php'; ?>
    </div>
</body>
</html>
