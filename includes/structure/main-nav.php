 <div id="omniture" style="display:none">
             <script type="text/javascript">
             var s_account="devtimewarner"; // change for the production site
             </script>
             <script language="JavaScript" type="text/javascript" src="<?php echo('http://'.$_SERVER['HTTP_HOST'].'/js/omniture/timewarner.js'); ?>"></script>
             <script language="JavaScript" type="text/javascript">
             s_time.pageName="<?php function curPageName() { $pathInfo=$_SERVER['REQUEST_URI']; $pathTrim=substr($pathInfo,1,strlen($pathInfo)-2); if (strlen($pathTrim)<=1) $pathPipes="homepage"; else $pathPipes=str_replace("/","|",$pathTrim); return "timewarner|" . $pathPipes;} echo curPageName();?>"
             s_time.channel="corp" 
             s_time.prop16="<?php $sectionArray = explode("/",$_SERVER["REQUEST_URI"]); if ($sectionArray[1]=="index.php") echo "homepage"; else echo $sectionArray[1];?>"	
             s_time.prop17="<?php function curPageURL() { $pageURL = "http://"; $pageURL .= $_SERVER["SERVER_NAME"].substr($_SERVER["SCRIPT_NAME"],0,-9); return $pageURL;} echo curPageURL();?>"
			 <?php $sectionArray = explode("/",$_SERVER["SCRIPT_NAME"]); $levelsCount=count($sectionArray); if ($levelsCount>3) echo $prop11Output="s_time.prop11=\"".$sectionArray[2]."\"";?>
	           </script> 
	           <script type="text/javascript">
	             var s_code=s_time.t();
	             if (s_code) document.write(s_code);
	           </script>
</div>
<div id="dropdown-holder-bg" class="<?php if (
	strpos($_SERVER['REQUEST_URI'], 'our-content') === false && 
	strpos($_SERVER['REQUEST_URI'], 'our-innovations') === false &&
	strpos($_SERVER['REQUEST_URI'], 'our-company') === false) { echo ' hidden '; } ?>"><div class="subnav-bg"></div></div>
<div id="main-nav" style="position:relative; z-index: 100">
    <div id="branding"><h1><a href="<?php echo $baseUrl; ?>" title="Time Warner Logo">Time Warner</a></h1></div>
    <ul id="utility-nav">
		<li<?php if (strpos($_SERVER['REQUEST_URI'], 'investor-relations') !== false) echo ' class="thispage"'; ?>><a href="http://ir.timewarner.com/phoenix.zhtml?c=70972&p=irol-IRHome" class="investor-relations">Investor Relations</a></li>
		<li<?php if (strpos($_SERVER['REQUEST_URI'], 'newsroom') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>newsroom/" class="newsroom" >Newsroom</a></li>
		<li<?php if (strpos($_SERVER['REQUEST_URI'], 'careers') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>careers/" class="careers" >Careers</a></li>

    </ul>
	<div style="clear: both;">
    <div id="search">
        <form id="main-search" method="GET" action="<?php echo $baseUrl; ?>search/">
            <img style="float:left;" src="<?php echo $baseUrl; ?>imgs/global/search/search_lft.png" /><input type="text" value="Search" name="search" onfocus="if (this.value=='Search') { this.value=''; }"/>
            <button type="submit" class="btn btn-search" title="Keyword Search">Search</button>
        </form>
    </div>
    <ul class="main-content">
		<li><a class="main-link brands<?php if (strpos($_SERVER['REQUEST_URI'], 'our-content') !== false) echo ' current thispage'; ?>" 
			href="<?php echo $baseUrl; ?>our-content/" >OUR CONTENT</a>
			<div class="dropdown-holder<?php if (strpos($_SERVER['REQUEST_URI'], 'our-content') !== false) { echo ' current'; } else { echo ' hidden'; } ?>">
				<img src="<?php echo $baseUrl; ?>imgs/global/nav/a_global_leader_in_television_film_and_journalism.gif" />
				<ul>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-content/turner-broadcasting-system') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-content/turner-broadcasting-system" >Turner Broadcasting System</a></li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-content/warner-bros-entertainment') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-content/warner-bros-entertainment" >Warner Bros. Entertainment</a></li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-content/home-box-office') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-content/home-box-office" >Home Box Office</a></li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-content/time-inc') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-content/time-inc" >Time Inc.</a></li>
				</ul>
			</div>
		</li>
		
		<li class="company"><a class="main-link company<?php if (strpos($_SERVER['REQUEST_URI'], 'our-company') !== false) echo ' current thispage'; ?>" 
			href="<?php echo $baseUrl; ?>our-company/" >OUR COMPANY</a>
			<div class="dropdown-holder<?php if (strpos($_SERVER['REQUEST_URI'], 'our-company') !== false) { echo ' current'; } else { echo ' hidden'; } ?>">
				<img src="<?php echo $baseUrl; ?>imgs/global/nav/delivering_high-quality_brands_and_content.gif" alt="A Leader in Innovation for More Than 100 Years." />
				<ul class="secondary-nav">
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-company/about-us') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-company/about-us" title="About Us">About Us</a></li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-company/management') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-company/management" title="Management">Management</a>
						<div class="dropdown-holder hidden"><div class="tertiary-nav">
							<img src="<?php echo $baseUrl; ?>imgs/global/nav/dropdown_top.gif" class="" alt="" border="0" />
							<ul class="ancilary-nav">
								<li><a href="<?php echo $baseUrl; ?>our-company/management/board-of-directors" >Board of Directors</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/management/senior-corporate-executives" >Senior Corporate Executives</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/management/executives-by-business" >Executives by Business</a></li>
							</ul>
							<img src="<?php echo $baseUrl; ?>imgs/global/nav/dropdown_btm.gif" class="btm" alt="" border="0" />
						</div></div>
					</li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-company/corporate-governance') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-company/corporate-governance/" >Corporate Governance</a>
						<div class="dropdown-holder hidden"><div class="tertiary-nav">
							<img src="<?php echo $baseUrl; ?>imgs/global/nav/dropdown_top.gif" class="" alt="" border="0" />
							<ul class="ancilary-nav">
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-governance/board-of-directors" >Board of Directors</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-governance/board-leadership-and-committee-structure" >Board Leadership and Committee Structure</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-governance/committee-charters-policies-and-reports" >Committee Charters, Policies and Reports</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-governance/by-laws" >By-Laws</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-governance/governance-policy" >Governance Policy</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-governance/codes-of-conduct" >Codes of Conduct</a></li>
							</ul>
							<img src="<?php echo $baseUrl; ?>imgs/global/nav/dropdown_btm.gif" class="btm" alt="" border="0" />
						</div></div>
					</li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-company/corporate-responsibility') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-company/corporate-responsibility" >Corporate Responsibility</a>
						<div class="dropdown-holder hidden"><div class="tertiary-nav">
							<img src="<?php echo $baseUrl; ?>imgs/global/nav/dropdown_top.gif" class="" alt="" border="0" />
							<ul class="ancilary-nav">
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-responsibility/diversity" >Diversity</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-responsibility/content-responsibility" >Content Responsibility</a></li>

								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-responsibility/ethics" >Ethics</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-responsibility/political-activities" >Political Activities</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-responsibility/global-supply-chain" >Global Supply Chain</a></li>
                                <li><a href="<?php echo $baseUrl; ?>our-company/corporate-responsibility/sustainability" >Sustainability</a></li>
								<li><a href="<?php echo $baseUrl; ?>our-company/corporate-responsibility/in-the-community" >In The Community</a></li>
                                
							</ul>
							<img src="<?php echo $baseUrl; ?>imgs/global/nav/dropdown_btm.gif" class="btm" alt="" border="0" />
						</div></div>
					</li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-company/global-media') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-company/global-media" >Global Media Group</a></li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-company/tw-investments') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-company/tw-investments" >TW Investments</a></li>
				</ul>
			</div>
		</li>		

        <li class="innovations"><a class="main-link innovations<?php if (strpos($_SERVER['REQUEST_URI'], 'our-innovations') !== false) echo ' current thispage'; ?>" 
			href="<?php echo $baseUrl; ?>our-innovations/" >OUR INNOVATIONS</a>
			<div class="dropdown-holder<?php if (strpos($_SERVER['REQUEST_URI'], 'our-innovations') !== false) { echo ' current'; } else { echo ' hidden'; } ?>">
				<img src="<?php echo $baseUrl; ?>imgs/global/nav/driving_consumer_choice_and_experiences.gif" />
				<ul>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-innovations/content-everywhere') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-innovations/content-everywhere" >Content Everywhere</a></li>
					<li<?php if (strpos($_SERVER['REQUEST_URI'], 'our-innovations/shaping-trends') !== false) echo ' class="thispage"'; ?>><a href="<?php echo $baseUrl; ?>our-innovations/shaping-trends" >Shaping Trends</a></li>
				</ul>
			</div>
		</li>

	</ul>
	</div>
</div><?php //end nav ?>