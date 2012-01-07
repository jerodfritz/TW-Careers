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
                            <input type="text" name="search-keywords" value="" id="search-keywords">
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
                   <div class="third-width-box third-width-box-first">
                   </div>
                   <div class="third-width-box">
                   </div>
                   <div class="third-width-box third-width-box-last">
                   </div>
                 </div>
            </div>
        </div>
         <?php include $basePath . 'includes/structure/sticky-footer-careers.php'; ?>
    </div>
</body>
</html>
