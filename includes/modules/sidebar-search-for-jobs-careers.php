<script src="<?php echo $baseUrl . 'js/careers/careers.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $baseUrl . 'js/careers/careersParser.js'; ?>" type="text/javascript"></script>
<script src="http://us.js2.yimg.com/us.js.yimg.com/lib/common/utils/2/yahoo_2.0.0-b3.js" type="text/javascript"></script>
<script src="http://us.js2.yimg.com/us.js.yimg.com/lib/common/utils/2/event_2.0.0-b3.js" type="text/javascript"></script>
<script src="http://us.js2.yimg.com/us.js.yimg.com/lib/common/utils/2/dom_2.0.2-b3.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $baseUrl . 'js/careers/ie-dd-widthfix.js'; ?>"></script>

<h4>Search for Jobs</h4>
<ul>
<li><a href="https://careers.timewarner.com/1033/ASP/TG/cim_home.asp?partnerid=391&siteid=36">Search for Jobs in English</a></li>
<li><a href="https://careers.timewarner.com/1036/ASP/TG/cim_home.asp?partnerid=391&siteid=5145">Chercher des emplois en Fran&ccedil;ais</a></li>
<li><a href="https://careers.timewarner.com/1031/ASP/TG/cim_home.asp?partnerid=391&siteid=5144">Suchen nach Stellenangeboten auf Deutsch

 

</a></li>
<li><a href="https://careers.timewarner.com/1040/ASP/TG/cim_home.asp?partnerid=391&siteid=5243">Cercare dei lavori in Italiano</a></li>
<li><a href="https://careers.timewarner.com/3082/ASP/TG/cim_home.asp?partnerid=391&siteid=5244">Buscar ofertas de empleo en Espa&ntilde;ol </a></li>

</ul>
<div class="careers-form">


<form id="search_dd">
<input value="0" id="selectionVar" type="hidden">
<div style="margin:0px; padding:0px; width:308px;">
<ul>
<li>SEARCH BY &nbsp;&nbsp;<select id="selection_main" onChange="javascript:change_dd_selection();" style="width:80px;"><option value="1">Division</option><option value="2">Industry</option><option value="3">Location</option><option value="4">Interest</option></select>
</li>
<li>
<span id="div_selection_1_intro">Use the links below to search for jobs by division.</span><span id="div_selection_2_intro">Use the links below to search for jobs by industry.</span><span id="div_selection_3_intro">Use the links below to search for jobs by hub location.</span><span id="div_selection_4_intro">Use the links below to search for jobs by interest.</span></li>
</ul>
</div>
<div style="clear:both; margin:0 12px 25px; padding-bottom:5px; width:308px;">
<div style="float:left; margin:0;">
<span id="div_selection_1" style="vertical-align:top;"></span><span id="div_selection_2" style="vertical-align:top;"></span><span id="div_selection_3" style="vertical-align:top;"></span><span id="div_selection_4" style="vertical-align:top;"></span></div>
<div style="float:right; margin:0;">
<img class="submit_img" onClick="javascript:selected_dd();" onmouseover="" src="<?php echo $baseUrl . 'imgs/careers/legacy/go_button.gif';?>"</div>
</div>

</form>
</div>
<script type="text/javascript">
<!--
var xmlFile = '<?php echo $baseUrl . 'careers/xml/jobs.xml'; ?>';
var cp = new CareersParser(xmlFile);

-->
</script>

<div style="clear:both;"></div>

<hr class="divider"/>
