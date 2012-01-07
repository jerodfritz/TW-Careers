
<div id="sticky-footer"><!--
<div id="footer-holder-bg" class="hidden"></div>-->
    <?php if ($_SERVER['REQUEST_URI'] == $baseUrl || $_SERVER['REQUEST_URI'] == $baseUrl.'index.php') {
    	include_once('includes/structure/explore-footer.php');
    } ?>
	<br /><div class="footer-content">
		<!--<ul class="social">
			<li><span>FOLLOW US</span></li>
			<li><a href="#" title="You Tube" class="youtube">You Tube</a></li>
			<li><a href="#" title="Facebook" class="facebook">Facebook</a></li>
			<li><a href="#" title="Twitter" class="twitter">Twitter</a></li>
		</ul>
		<ul class="brands">
			<li><a href="#">Explore Our Brands</a></li>
		</ul>
		<div class="hr">
			<hr />
		</div>-->
		<ul class="links">
        <li><a href="<?php echo $baseUrl; ?>index.php" title="Home">Home</a></li>
			<li><a href="<?php echo $baseUrl; ?>legal-and-privacy/" title="Legal and Privacy">Legal &amp; Privacy</a></li>
			<li><a href="<?php echo $baseUrl; ?>caution-concerning-forward-looking-statements/" title="Caution Concerning Forward-Looking Statements">Caution Concerning Forward-Looking Statements</a></li>
			<li><a href="<?php echo $baseUrl; ?>sitemap/" title="Sitemap">Sitemap</a></li>
			<li><a href="<?php echo $baseUrl; ?>contact-us/" title="Contact Us">Contact Us</a></li>
			<li><a href="<?php echo $baseUrl; ?>shop-and-subscribe/" title="Shop &amp; Subscribe">Shop &amp; Subscribe</a></li>
		</ul>
		<ul class="copyright">
			<li>&copy; Time Warner 2011. All Rights Reserved.</li>
		</ul>
	</div>
</div><?php //end footer ?>
