<div class="secondary-nav-holder-short">
	<div id="secondary-nav" class="nav-newsroom">
		<ul class="secondary-nav" style="margin-top:43px;">
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/press-releases') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/press-releases/" class="news-updates">Reports &amp; SEC Filings</a></li>
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/press-contacts') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/press-contacts/" class="press-contacts">Stock &amp; Debt Securities Information</a></li>
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/media-and-identity-materials') !== false && strpos($_SERVER['REQUEST_URI'], 'infocus/quarterly/business/review/') === false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/media-and-identity-materials/" class="media-assets">Events &amp; Presentations</a></li>
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/media-and-identity-materials/infocus/quarterly/business/review') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/media-and-identity-materials/infocus/quarterly/business/review" class="media-assets">Shareholder Services</a></li>
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/media-and-identity-materials/infocus/quarterly/business/review') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/media-and-identity-materials/infocus/quarterly/business/review" class="media-assets">Corporate Actions</a></li>
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/media-and-identity-materials/infocus/quarterly/business/review') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/media-and-identity-materials/infocus/quarterly/business/review" class="media-assets">Corporate Governance</a></li>
		</ul>
	</div>
</div>
