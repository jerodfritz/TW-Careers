<?php
	$companyArray = array("Global Media Group", "Home Box Office", "Time Inc.", "Time Warner Corporate", 
	"Time Warner Investments", "Turner Broadcasting System", "Warner Bros. Entertainment");

	$priorIsPR = isset($_SESSION['priorIsPR']) ? htmlentities($_SESSION['priorIsPR']) : false;
	$isPR = isset($_GET['isPR']) ? htmlentities($_GET['isPR']) : false;
	if($priorIsPR | $isPR ) {
		$priorSearch = isset($_GET['search'])? htmlentities($_GET['search']) : (isset($_SESSION['priorsearch']) & $_SESSION['priorsearch'] != 'Press Releases' ? htmlentities($_SESSION['priorsearch']) : false);
	} else {
		$priorSearch = false;
		$_SESSION['priorsearch'] = false;
	}
	if (!empty($_GET)) {
		$priorCompany = isset($_GET['company']) ? htmlentities($_GET['company']): (isset($_SESSION['priorCompany']) ? $_SESSION['priorCompany'] : false);	
		$priorStartDate = isset($_SESSION['priorStartDate']) ? htmlentities($_SESSION['priorStartDate']) : false;
		$priorEndDate = isset($_SESSION['priorEndDate']) ? htmlentities($_SESSION['priorEndDate']) : false;
	}
	$today = getdate(); 
	if (isset($_GET['startdate']) & isset($_GET['startmonth']) & isset($_GET['startyear'])) {
		$startYear = intval(htmlentities($_GET['startyear']));
		$startMonth = intval(htmlentities($_GET['startmonth']));
		$startDay = intval(htmlentities($_GET['startdate']));
	} else if ($priorStartDate) {
		$startYear = substr($priorStartDate, 0, 4);
		$startMonth = intval(substr($priorStartDate, 4, 2));
		$startDay = intval(substr($priorStartDate, 6));
	} else {
		$startMonth = $today['mon']; 
		$startDay = $today['mday'];
		$startYear = $today['year']-1;
	}
	if (isset($_GET['enddate']) & isset($_GET['endmonth']) & isset($_GET['endyear'])) {
		$endYear = intval(htmlentities($_GET['endyear']));
		$endMonth = intval(htmlentities($_GET['endmonth']));
		$endDay = intval(htmlentities($_GET['enddate']));
	} else if ($priorEndDate) {
		$endYear = substr($priorEndDate, 0, 4);
		$endMonth = intval(substr($priorEndDate, 4, 2));
		$endDay = intval(substr($priorEndDate, 6));
	} else {
		$endMonth = $today['mon'];
		$endDay = $today['mday'];
		$endYear = $today['year'];
	}
	
						$action = $baseUrl."newsroom/press-releases/search-results.php";
						$monArray = array(' ', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
						$hedChunk = <<<EOS
					    <div class="search-news">
						<h2>Search for Press Releases</h2>
						<form action="$action" method="GET">
							<div style="float: left;">
								<label for="startmonth" class="news-search-label">Start Date:</label>
								<input type="hidden" name="isPR" value="1"/>
								<select name="startmonth" class="custom-select">
EOS;
						$startDateSel = <<<EOS
								</select>
								<select name="startdate" class="custom-select">
EOS;
						$startYearSel = <<<EOS
								</select>
								<select name="startyear" class="custom-select">
EOS;
						$endMoSel = <<<EOS
								</select>
								<br/><br/>
								<label for="endmonth" class="news-search-label">End Date:</label>
								<select name="endmonth" class="custom-select">
EOS;
						$endDateSel = <<<EOS
								</select>
								<select name="enddate" class="custom-select">
EOS;
						$endYearSel = <<<EOS
								</select>
								<select name="endyear" class="custom-select">
EOS;
						print($hedChunk); 
						
						for ($i=1; $i<13; $i++) {
							if ($i == $startMonth) {
							print("<option value=\"$i\" selected=\"selected\">$monArray[$i]</option>");
							} else {
							print("<option value=\"$i\">$monArray[$i]</option>");
							}
						}
						print($startDateSel);
						for ($i=1; $i<32; $i++) {
							if ($i == $startDay) {
								echo "<option value=\"$i\" selected=\"selected\">$i</option>";
							} else {
								echo "<option value=\"$i\">$i</option>";
							}
						}
						print($startYearSel);
						for ($i=1998; $i<=$today['year']; $i++) {
							if ($i==($startYear)	) {
								echo '<option value="'.$i.'" selected>'.$i.'</option>';
							} else {
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						}
						print($endMoSel); 
						for ($i=1; $i<13; $i++) {
							if ($i == $endMonth) {
							print("<option value=\"$i\" selected=\"selected\">$monArray[$i]</option>");
							} else {
							print("<option value=\"$i\">$monArray[$i]</option>");
							}
						}
						print($endDateSel);
						for ($i=1; $i<32; $i++) {
							if ($i == $endDay) {
								echo "<option value=\"$i\" selected=\"selected\">$i</option>";
							} else {
								echo "<option value=\"$i\">$i</option>";
							}
						}
						print($endYearSel);
						for ($i=1998; $i<=$today['year']; $i++) {
							if ($i==$endYear) {
								echo '<option value="'.$i.'" selected>'.$i.'</option>';
							} else {
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						}
?>								</select>
							</div>	
							<div style="float: right;">
								<label for="company" class="news-search-label">Company:</label>
								<select name="company" class="custom-select" id="company-dropdown">
<?php 								if ($priorCompany) { ?>
									<option value="all">All Companies</option>
<?php 								} else { ?>									
									<option value="all" selected="selected">All Companies</option>
<?php
									}
	 								foreach ($companyArray as $company) {
	 									$selected = $company == $priorCompany ? " selected =\"selected\"" : "";	
	 									print("<option value=\"$company\"$selected>$company</option>" . chr(10));
	 								}
?>
								</select>
								<br /><br />
								<label for="keywords" class="news-search-label">Keywords:</label>
								<input type="text" id="searchField" name="search" value="<?php echo $priorSearch?>" size="25" style="margin-top:0;padding:3px 3px 3px 5px;height:18px;width:220px;" />
							</div>
							<br clear="all" />
							<div style="clear:all; float: right;">
								<!--<input type="reset" class="reset" />-->
								<input type="submit" class="submit" value="Submit" />
							</div>
						</form>
					</div>
					<br clear="all" />