				<?php
				$def = 'index';					//default web page for directories on your server.
				$ignore = array();				//folders to ignore
				$ignore = array('1997','1998','1999','2000','2001','2002','2003','2004','2005','2006','2007','2008',
				'2009','2010','2011','2012','2013','2014','2015','2016','2017','2018','2019','2020','01','02','03',
				'04','05','06','07','08','09','10','11','12');				//folders to ignore
				$dPath = $_SERVER['PHP_SELF'];	//Get the script path, relative to web root.
				$dCount = 1;
				//echo '<!-- baseUrl: '.$baseUrl.' -->';
				if ($baseUrl != '/') { $dPath = str_replace($baseUrl,"",$dPath,$dCount); } else { $dPath = substr($dPath,1); }

				//strip anything after index.php, if it exists.
				$idx = strpos(strtolower($dPath),"/index.php");
				if ($idx!==false){}
					$dPath = substr($dPath, 0, $idx);
				$dChunks = explode("/", $dPath);   						//Separate out folder and file names by looking for slashes.
				?><div id="breadcrumb">
					<a href="<?php echo $baseUrl; ?>">Home</a>
					<?php
					for($i=0; $i<count($dChunks); $i++){
						//echo "\n<!-- chunk: $i, which is {$dChunks[$i]}. -->\n";
						if (!in_array($dChunks[$i],$ignore)) {
							$bcDivider = ' <span class="breadcrumb-divider">&gt;</span>';
							$breadcrumb = " <a href=\"$baseUrl";
							for($j=0; $j<=$i; $j++){ // build the link
								$breadcrumb .= $dChunks[$j] . '/';
								//echo "<!-- j=$j and breadcrumb is: $breadcrumb -->\n";
								if($j!=count($dChunks)-1) $breadcrumb .= ("");
							}
							$breadcrumb .= "\">";
							$prChunks = array();
							//echo "\n<!-- breadcrumb is now: $breadcrumb -->\n";
							if($i==count($dChunks)-2 && strpos($dChunks[$i+1],$def)!==false){
								$breadcrumb = ' ';
								$prChunks = explode(".", $dChunks[$i]);	
							} else if($i==count($dChunks)-1){
								$prChunks = explode(".", $dChunks[$i]);		//take out the file extension...
								if ($prChunks[0] == $def && !in_array($dChunks[$i-1],$ignore)) {
									$prChunks[0] = ''; 					//don't display the filename if it's index or whatever default you specified.
									$breadcrumb = '';
									$bcDivider = '';
								} else if ($prChunks[0] == $def) {
									$prChunks[0] = $buildName . '</a>';
								} else { $breadcrumb = ' '; $prChunks[0] .= ''; }
							} else {
								$prChunks[0] = $dChunks[$i].'</a>';
							}
							$prChunks[0] = str_replace(array('_','-') , " " , $prChunks[0]); 	//Finish writing the link, replacing underscores with spaces for the end user.
							$prChunks[0] = str_replace('  ' , '-' , $prChunks[0]);
							$prChunks[0] = str_replace('by laws' , 'By-Laws' , $prChunks[0]);
							$prChunks[0] = str_replace('faqs' , 'FAQs' , $prChunks[0]);
							$prChunks[0] = str_replace('tw' , 'TW' , $prChunks[0]);
							$prChunks[0] = str_replace('tv' , 'TV' , $prChunks[0]);
							$prChunks[0] = str_replace('infocus' , 'inFOCUS' , $prChunks[0]);
							$prChunks[0] = str_replace('hbo' , 'Home Box Office' , $prChunks[0]);
							$prChunks[0] = str_replace('global media' , 'Global Media Group' , $prChunks[0]);
							echo $bcDivider.$breadcrumb.$prChunks[0];
						} else {
							$buildName .= $dChunks[$i].' ';
						}
					}
					?>
				</div>