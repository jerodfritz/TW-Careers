<div class="secondary-nav-holder-short">
	<div id="secondary-nav" class="nav-newsroom">
		<ul class="secondary-nav" style="margin-top:43px;">
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/press-releases') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/press-releases/" class="news-updates">Press Releases</a></li>
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/press-contacts') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/press-contacts/" class="press-contacts">Press Contacts</a></li>
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/media-and-identity-materials') !== false && strpos($_SERVER['REQUEST_URI'], 'infocus/quarterly/business/review/') === false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/media-and-identity-materials/" class="media-assets">Media and Identity Materials</a></li>
			<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom/infocus-quarterly-business-review') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/infocus-quarterly-business-review" class="media-assets"><em>in</em>FOCUS Quarterly Business Review</a></li>
		</ul>
	</div>
</div>