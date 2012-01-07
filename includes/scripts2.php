<? // jQuery Library: linking through GOOGLE APIs ?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/jquery/jquery-1.4.2.min.js"></script>

<? /* PLUGINS:
	- Accessible RIA Table: for sorting tables
	- Custom Tooltip: for a universal tooltip to be applied to any element with class of tooltip and title attribute
	- Star Rating: for pretty star image rating
	- Any other plugins comment here and link to below 
*/ ?>
<!--<script type="text/javascript" src="<?php echo $baseUrl; ?>js/plugins/jquery.ba-clickoutside.js"></script>-->
<? // CUFON ?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/cufon/cufon-yui.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/cufon/Uni_Reg_400.font.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/cufon/Uni_Bold_600.font.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/cufon/Uni_Sans_Regular_Italic_italic_400.font.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/cufon/UniSansHeavyItalic_italic_900.font.js"></script>
<script type="text/javascript">
    Cufon.replace('ul.social span', { fontFamily: 'Uni Bold' });
/*	Cufon.replace('#content-main h1', { fontFamily: 'UniSansHeavyItalic' }); 
	Cufon.replace('#content-main h2.tagline', { fontFamily: 'Uni Reg' });
	Cufon.replace('#content-main h2.tagline-italic', { fontFamily: 'Uni Sans Regular Italic' });
	Cufon.replace('#content-main h2.infocus-italic', { fontFamily: 'Uni Sans Regular Italic' });
*/	Cufon.replace('#sticky-footer #explore-content li h3', { fontFamily: 'Uni Bold' });
	Cufon.replace('#sticky-footer #explore-content li h4', { fontFamily: 'Uni Sans Regular Italic' });
</script>
<? // Global JS: initializes and sets global functionality ?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/global.js"></script>
<script type="text/javascript">
    baseUrl = '<?php echo $baseUrl; ?>';
</script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>js/jquery/jquery.carousel.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('div.viewport').carousel({ 		
		autoSlide: true,
		loop: true,
		autoSlideInterval: 5000,
        delayAutoSlide: 2000,	
		prevBtn: false,
		nextBtn: false,
		direction: "vertical",
		effect : "slide"						
		 } ); 		
		});
               
       	
</script>



