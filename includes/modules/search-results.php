<?php
	$memory_limit = ini_get('memory_limit');
	$max_exec_time = ini_get('max_execution_time');
	ini_set('max_execution_time', '300');
	ini_set('memory_limit', '480M');
	require_once $basePath.'search/Zend/Search/Lucene.php';
	$pageSize = 25; 
	/* change the above number to get more results for a search initiated by a user
	 * the search of all press releases which gives you  the list of all press releases 
	 * is not limited in size. JSM 10/26/2010
	 */
	$generalSearchLimit = 200;
	$INDEX_PATH = $basePath.'search/index';
	$serverName = $_SERVER['SERVER_NAME'];
	$port = $_SERVER['SERVER_PORT'];
	$searchUrl = "http://$serverName:$port";	
	$queryString = isset($_GET['search'])? $_GET['search'] : false;
	$isPR = isset($_GET['isPR'])? $_GET['isPR']:false;
	$company = isset($_GET['company'])? $_GET['company'] : false;
	$pageSought = isset($_GET['page'])? $_GET['page']:false;
	$startmonth = isset($_GET['startmonth'])? padNumber($_GET['startmonth']) : false;
	$startdate = isset($_GET['startdate'])? padNumber($_GET['startdate']):false;
	$startyear = isset($_GET['startyear'])? $_GET['startyear']:false;
	$startYMD = (($startmonth && $startdate && $startyear)? $startyear . $startmonth . $startdate : false); 
	$endmonth = isset($_GET['endmonth'])? padNumber($_GET['endmonth']):false;
	$enddate = isset($_GET['enddate'])? padNumber($_GET['enddate']):false;
	$endyear = isset($_GET['endyear'])? $_GET['endyear']:false;
	$prList = isset($_GET['prList']) ? $_GET['prList'] : false;
	$endYMD = (($endmonth && $enddate && $endyear)? $endyear . $endmonth . $enddate : false);
	$hitPageArray = isset($_SESSION['results']) ? $_SESSION['results'] : false;
	$totalResults = isset($_SESSION['resultcount']) ? $_SESSION['resultcount'] : false; 
	$priorSearch = isset($_SESSION['priorsearch']) ? $_SESSION['priorsearch'] : false;	
	$priorIsPR = isset($_SESSION['priorIsPR']) ? $_SESSION['priorIsPR'] : false;
	$error = false;
	$prFileName = "prList.txt";
	$prStalenessSeconds = 1800;
	/* had to go through some serious hoops in the first if clause below because the session would not work
	 * on the server for this option. JSM 01/10/11
	 */ 
	if (!$queryString & !$isPR & !$pageSought | (strpos($_SERVER['REQUEST_URI'], 'press-releases')>0 & $prList==1)) {
		if ((!file_exists($prFileName) || (time() - filectime($prFileName) >$prStalenessSeconds)) & !$pageSought) {
			$index = Zend_Search_Lucene::open($INDEX_PATH);
			$hits = $index->find('isPR:1^100');
			$today = getdate();
			$endYMD = $today['year'].$today['mon'].$today['mday'];
			$startYMD = ($today['year']-1).$today['mon'].$today['mday'];
			usort($hits, "dateCompareDesc");
			$hitPageArray = paginate($hits);
			$totalResults = count($hits) <= $generalSearchLimit ? $count($hits) : $generalSearchLimit;
			$endDate = $hits[0]->getDocument()->getField('sortdate')->value;
			$startDateIndex = count($hits)>200 ? 199 : count($hits)-1;
			$prFile = fopen($prFileName, 'w');
			fwrite($prFile, serialize($hitPageArray)); 
			fclose($prFile);
		} else {
			$hitPageArray = unserialize(file_get_contents($prFileName));
			$totalResults = 0;
			foreach($hitPageArray as $page) {
				$totalResults = $totalResults + count($page);
			}
		}
		$_SESSION['priorsearch'] = 'Press Releases';
		$_SESSION['priorStartDate'] = false;
		$_SESSION['priorEndDate'] = false; 
		$_SESSION['priorCompany'] = false;
		$queryString = "Press Releases";
		$priorSearch = 'Press Releases';
		$_SESSION['priorIsPR'] = 1;
		$prList = 1;
		$pageSought=!$pageSought? 1 : $pageSought;
		$isPR=1;
	} else if ($isPR == '1') {
		$hitPageArray = false;
		$query = "isPR:1^10"; 
		if ($queryString) {
			$query = $query . " AND " . $queryString;
			$_SESSION['priorsearch'] = $queryString;
		}
		if ($company && strtoupper($company)!='ALL') {
			$query = $query . " AND company:\"" . $company . "\"";
			$_SESSION['priorCompany'] = $company;
		}
		$_SESSION['priorIsPR'] = 1;
		$index = Zend_Search_Lucene::open($INDEX_PATH);
		$hits = $index->find($query);
		
// REMOVE RESULTS OUTSIDE RANGE
		if ($endYMD && $startYMD) {
			$hits = filterHits($hits, $endYMD, $startYMD);
			$_SESSION['priorStartDate'] = $startYMD;
			$_SESSION['priorEndDate'] = $endYMD;
		}
// SORT RESULTS
		usort($hits, "dateCompareDesc");
//COUNT RESULTS; SET TOTAL
		$totalResults = count($hits)> 200 ? 200 : count($hits);
		
// PAGINATE RESULTS -- CHANGE PAGINATE TO STOP AFTER $generalSearchLimit
		if (count($hits)>$pageSize) {
			$hitPageArray = paginate($hits);
			$_SESSION['results'] = $hitPageArray;
			$_SESSION['resultcount'] = $totalResults;
			$pageSought = 1;
		}
	} else if (strlen($queryString)>0) {
		$isPR = false;
		$priorIsPR = false;
		$hitPageArray = false;
		$_SESSION['priorIsPR'] = false;
		$_SESSION['priorCompany'] = false;
		$_SESSION['priorStartDate'] = false;		
		$_SESSION['priorEndDate'] = false;		
		
		$index = Zend_Search_Lucene::open($INDEX_PATH);

		Zend_Search_Lucene::setResultSetLimit($generalSearchLimit);
		$hits = $index->find($queryString);		
		$totalResults = count($hits);

		if ($totalResults>$pageSize) {
			$hitPageArray = paginate($hits);
			$_SESSION['results'] = $hitPageArray;			
			$_SESSION['resultcount'] = $totalResults;
			$_SESSION['priorsearch'] = $queryString;
			$pageSought = 1;
		} else {
			$foundClump = $hits;
			$pageSought = false;
			$_SESSION['results'] = false;			
			$_SESSION['resultcount'] = 0;
			$_SESSION['priorsearch'] = false;
		}
	} else if ($pageSought && (!$hitPageArray || $pageSought > count($hitPageArray))) {
		$error = "You are trying to retrieve a page of results that does not exist.";
	}	
	if (!$pageSought && count($hits)==0 && (count($hitPageArray)==0 || !$hitPageArray)) {
		$error = "No result was found for your search";
		if ($isPR) {
			$error = $error . " for Press Releases ";
			if ($startdate && $enddate) {
				$error = $error . " between $startmonth/$startdate/$startyear and $endmonth/$enddate/$endyear";
			}
			if ($company && $company!="all") {
				$error = $error . " for the company $company";
			}
			if ($queryString)
				$error = $error . " containing the word(s) $queryString";
		} else { 
			$error = $error . " for \"$queryString\"";
		}
		$error = $error . ". If you wish, broaden your search criteria or simplify the word(s) you are searching for and try again. <p style=\"margin-top:150px;\">&nbsp;</p>";
	}
	/* script below sets the search text field in the form on the page which has loaded before this php file runs 11/29/10 JSM */
	if (!$error) {
		if (!$pageSought) {
			$foundClump = $hits;
			$pages = false;
		} else {
			$foundClump = $hitPageArray[$pageSought-1];
			$pages = array_keys($hitPageArray);
		}
		$countOnPage = count($foundClump);
		pageNav($pages, $countOnPage);
		print("<ul class='search-results-news'>");
		if ($pageSought) {
			foreach ($foundClump as $result) {
				print($result);
			}
		} else {
			LIformat($foundClump);
		}
		print("</ul>");
		pageNav($pages, $countOnPage);
	} else {
		print($error);
	}
	ini_set('max_execution_time', $max_exec_time);
	ini_set('memory_limit', $memory_limit);
	session_write_close();

function paginate($hits) {
	global $generalSearchLimit;
	$hitArray = array();
	$hitPageArray = array();
	$counter = 0;
	$totalCounter = 0;
	global $pageSize;
	if (count($hits)>0) {
	foreach ($hits as $hit) {
		if ($totalCounter>$generalSearchLimit) {
			break;
		}
		$title = $hit->title;
		$url = $hit->url;
		$isPR = $hit->isPR;		
		if ($isPR == '0') {
			$hitArray[] = "<li><a href=\"$url\">$title</a></li>";
		} else {
			$releaseDate = $hit->releaseDate;
			$hitArray[] = <<<EOS
			<li><p class="date">$releaseDate</p>
			<a href="$url">$title</a></li>
EOS;
		}
		if ($counter++ == $pageSize-1) {
			$hitPageArray[] = $hitArray;
			$hitArray = array();
			$counter = 0;
		}
		$totalCounter++;
	}
		/* handle the last page */
		if (count($hitArray)>0 && $totalCounter <= $generalSearchLimit) {
			$hitPageArray[] = $hitArray;
		}	
	} else { 
		$hitPageArray = false;
	}
	return $hitPageArray;
}

function LIformat($hits) {
	foreach($hits as $hit) {
		$title = $hit->title;
		$url = $hit->url;
		$isPR = $hit->isPR;
		if ($isPR == '0') {
			print( <<<EOS
			<li><a href="$url">$title</a></li>
EOS
			);
		} else {
			$releaseDate = $hit->releaseDate;
			print(<<<EOS
			<li><p class="date">$releaseDate</p>
			<a href="$url">$title</a></li>
EOS
			);
		}
	}
}

function pageNav($pages, $resultEnd) {
	
	global $pageSought;
	global $totalResults;
	global $pageSize;
	global $queryString;
	global $priorSearch;
	global $isPR;
	global $generalSearchLimit;
	global $priorIsPR;
	global $prList;
	if ($isPR || $priorIsPR) {
		$targetPage = "/newsroom/press-releases/search-results.php";
	} else {
		$targetPage = "/search/index.php";
	}	
	if ($pageSought) {
		$resultStart = $pageSought == 1 ? 1: (($pageSought-1) * $pageSize)+1;
		$resultLast = $resultStart + $resultEnd-1;
	} else {
		$resultStart = 1;
		$resultLast = $resultEnd;
	}
	$resultString = "<div class=\"pagination-info\">Results $resultStart-$resultLast of $totalResults";
	if (strlen($queryString)>0) {
		$resultString = $resultString . " for \"" . htmlentities($queryString) . "\"</div>";
	} else if (strlen($priorSearch)>0) {
		$resultString = $resultString . " for \"" . htmlentities($priorSearch) . "\"</div>";
	} else if ($prList) {
		$resultString = $resultString . " for \"Press Releases\"</div>";
	} else {
		$resultString = $resultString . "</div>";
	}
	$resultString = $resultString . "<div style=\"font-weight:normal; font-style:italic; font-size:10pt;\">Please note: search returns a maximum of $generalSearchLimit results. Adjust your criteria to see different groups of results.  Search hints: To search for a specific phrase, please use quotation marks. </div>";
	
	print($resultString);
	if ($pages) {
		print("<div class=\"pagination-holder\">");
		print("    <ul class=\"pagination\">");
		if ($pageSought-1 >= 1) {
			$prevPage = 'page='. ($pageSought-1);
			if ($prList) {
				$prevPage= "$prevPage&prList=1";
			}
			print("			<li class=\"page-prev\"><a href=\"$targetPage?$prevPage\"><img src=\"/imgs/global/btn_arrow_prev.gif\" alt=\"Previous page\" border=\"0\" height=\"11\" width=\"11\"></a> &nbsp;<a href=\"$targetPage?$prevPage\">Prev</a></li>");
		}
		if ($pageSought + 1 <= count($pages)) {
			$nextPage = 'page=' . ($pageSought+1);
			if ($prList) {
				$nextPage = "$nextPage&prList=1";
			}
			print("		    <li class=\"page-next\"><a href=\"$targetPage?$nextPage\">Next</a> &nbsp;<a href=\"$targetPage?$nextPage\"><img src=\"/imgs/global/btn_arrow_next.gif\" alt=\"Next page.\" border=\"0\" height=\"11\" width=\"11\"></a></li>");
			
		}
		foreach ($pages as $page) {
			$dispPage = $page +1;
			if ($page == $pageSought-1) {
				print("<li class=\"thispage\">$dispPage</li>");
			} else {
				if ($prList) {
					print("<li><a href='$targetPage?page=$dispPage&prList=1'>$dispPage&nbsp;</a></li>");
				} else {
					print("<li><a href='$targetPage?page=$dispPage'>$dispPage&nbsp;</a></li>");
				}
			}
		}
		print("</ul>");
		print("</div>");
	}
}

function padNumber($number) {
	return strlen($number)== 2 ? $number : "0" . $number; 
}

function filterHits($hits, $enddate, $startdate) {
	$startdateInt = intval($startdate);
	$enddateInt = intval($enddate);
	$newHits = array();
	foreach ($hits as $hit) {
			try { //search sometimes returns non-PR results. Exclude them. JSM 11/15/2010
				$hitDate = intval($hit->getDocument()->getField('sortdate')->value);
				if (($hitDate <= $enddateInt) && ($hitDate >= $startdateInt)) {
					$newHits[] = $hit;
				}
			} catch (Exception $e) {}
	}
	return $newHits;
}
	
function dateCompareDesc($a, $b) {
	$aDate = intval($a->getDocument()->getField('sortdate')->value);
	$bDate = intval($b->getDocument()->getField('sortdate')->value);
	if ($aDate == $bDate) {
			return 0;
		} else {
			return ($aDate > $bDate) ? -1 : 1; //deliberately backwards to get descending search 
		}
}
?>
