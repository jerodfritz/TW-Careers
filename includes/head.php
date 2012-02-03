<?php include 'baseurl.php'; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php	if (isset($pageTitle)) {
		if (preg_match('/<.+?>/', $pageTitle)) {
			$pageTitle = preg_replace('/<.+?>/', '', $pageTitle);
		}
	}
?>	
	<title><?php print (isset($pageTitle)) ? $pageTitle: 'Time Warner'; ?></title>
	<meta name="description" content="<?php echo (isset($metaDescription)) ? $metaDescription : 'Time Warner'; ?>" />
	<meta name="keywords" content="<?php echo (isset($metaKeywords)) ? $metaKeywords : 'Time Warner'; ?>" />
<?php 
	if (isset($metaList)) {
		foreach($metaList as $name=>$value) {
			$metatag = <<<EOS
	<meta name="$name" content="$value" />

EOS;
			print($metatag);
		}
	}
?>
	<link rel="stylesheet" href="<?php echo $baseUrl; ?>css/screen.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo $baseUrl; ?>css/print.css" type="text/css" media="print" />
	<?php 
	if (isset($stylesheets)) {
	  foreach($stylesheets as $sheet){
        $css = <<<EOS
	<link rel="stylesheet" href="$sheet" type="text/css" media="screen" />

EOS;
        print($css);
	  }  
	} 
	?>
	<!--[if IE]>
		<link rel="stylesheet" href="<?php echo $baseUrl; ?>css/ie.css" type="text/css" media="screen" />
	<![endif]-->
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo $baseUrl; ?>css/ie7.css" type="text/css" media="screen" />
	<![endif]-->
	<!--[if IE 6]>
		<link rel="stylesheet" href="<?php echo $baseUrl; ?>css/ie6.css" type="text/css" media="screen" />
	<![endif]-->
	<!--[if LTE IE 7]>
		<script type="text/javascript" src="<?php echo $baseUrl; ?>js/pngfix/iepngfix_tilebg.js"></script>
		<style type="text/css">
		div#header, #main-nav ul.main-content li a.main-link, #main-nav ul.ancilary-nav li a.main-link, 
		#slider-code .prev, #slider-code .next, #main-nav img, #main-search button { 
			behavior: url("<?php echo $baseUrl; ?>js/pngfix/iepngfix.htc")
		}
		</style>
	<![endif]-->
	
	<script type="text/javascript" src="<?php echo $baseUrl; ?>js/swfobject.js"></script>
	<?php 
	if (isset($javascripts)) {
	  foreach($javascripts as $script){
        $js = <<<EOS
	<script type="text/javascript" src="$script"></script>

EOS;
        print($js);
	  }  
	} 	
	?>	
</head>