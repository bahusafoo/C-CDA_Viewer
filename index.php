<?php
	$SearchFilter2 = $_GET['SearchFilter2'];
	//$SearchFilter2 = $_REQUEST["SearchFilter2"];
	?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Intelligent Software and Systems">
		<title>Legacy EMR Data Viewer</title>
		<!-- Code from HL7 C-CDA Viewer (Intelsoft) -->
		<script src="js/jquery-1.12.0.min.js"></script>	
		<!--
			<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
			<script>window.jQuery || document.write('&lt;script src="js/jquery-1.12.0.min.js">\x3C/script>')</script>
			-->
		<script src="js/core.js"></script>
		<!--<link rel="stylesheet" type="text/css" media="all" href="css/font-awesome.css" />-->
		<link rel="stylesheet" type="text/css" media="all" href="css/cda.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/pure-min.css" />
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.css">
		<link rel="stylesheet" href="css/pureextension.css">
		<!--[if lte IE 8]>
		<link rel="stylesheet" href="css/marketing-old-ie.css">
		<![endif]-->
		<!--[if gt IE 8]><!-->
		<link rel="stylesheet" href="css/marketing.css">
		<!--<![endif]-->
		<script src="js/packery.pkgd.min.js"></script>
		<script src="js/draggabilly.pkgd.min.js"></script>
		<!--
			<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js"></script>
			<script>window.jQuery || document.write('<script src="js/masonry.pkgd.min.js">\x3C/script>')</script>
			-->
		<script src="js/xslt/xslt.js"></script>
		<script src="js/core.js"></script>
		<link rel="shortcut icon" href="./.favicon.ico">
		<link rel="stylesheet" href="./.style.css">
		<script src="./.sorttable.js"></script>
	</head>
	<body>
		<!--[if lte IE 8]>
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-old-ie-min.css">
		<![endif]-->
		<!--[if gt IE 8]><!-->
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
		<!--<![endif]-->
		<div class="custom-wrapper pure-g home-menu pure-menu pure-menu-fixed" style="width:100%" id="men">
			<div class="pure-u-1">
				<span class="pure-button viewbtn" id="inputcdabtn" id_target="inputcda" style="float:right;display:block"><i class="fa fa-lg fa-file-code-o"></i> Back to Home</span>
				<a href="home.php" class="" style="float:left;font-weight:bold;font-size:1.75em;;padding-top:0.2em;padding-bottom:0.5em;text-decoration:none">Legacy EMR Data Viewer</a>
			</div>
		</div>
		<div class="content-wrapper" id="content-wrapper" style="top:auto">
		<div class="pure-g"  style="margin-top:3em">
		<div class="pure-u-1">
		<div class=" cdaview"  id="inputcda" style="padding:1em;">
			<p></p>
			<!-- START TABLE VIEW -->
			<div class="table-wrapper-wrapper">
				<div align="right">
					Filter Search: <input type="text" id="filterText" value="<?php echo $SearchFilter2 ?>"> <button type="button" id="filterSearch" class="pure-button" onclick="filterResults()">Search</button> <button type="button" class="pure-button" onclick="clearFilter()">Clear Filter</button>
				</div>
				<b align="left">To begin, search for a patient and/or select a document listed below:</b>
				<div class="table-wrapper">
					<table class="sortable" width="90%">
						<thead>
							<tr>
								<th>Filename</th>
								<th>Type</th>
								<!-- <th>Actions</th> -->
							</tr>
						</thead>
						<tbody><?php
							//$SearchFilter2 = $_GET['SearchFilter2'];
							// Adds pretty filesizes
							function pretty_filesize($file) {
								$size=filesize($file);
								if($size<1024){$size=$size." Bytes";}
								elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
								elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
								else{$size=round($size/1073741824, 1)." GB";}
								return $size;
							}
							
							// wildcard string matching function
							function stringMatchWithWildcard($source,$pattern) {
							   $pattern = preg_quote($pattern,'/');        
							   $pattern = str_replace( '\*' , '.*', $pattern);   
							   return preg_match( '/^' . $pattern . '$/i' , $source );
							}
							
								// Checks to see if veiwing hidden files is enabled
							if($_SERVER['QUERY_STRING']=="hidden")
							{$hide="";
							 $ahref="./";
							 $atext="Hide";}
							else
							{$hide=".";
							 $ahref="./?hidden";
							 $atext="Show";}
							
							 // Opens directory
							 $myDirectory=opendir("./CCDAs");
							
							// Gets each entry
							while($entryName=readdir($myDirectory)) {
							   $dirArray[]=$entryName;
							}
							
							// Closes directory
							closedir($myDirectory);
							
							// Counts elements in array
							$indexCount=count($dirArray);
							
							// Sorts files
							sort($dirArray);
							
							// Loops through the array of files
							for($index=0; $index < $indexCount; $index++) {
							
							// Decides if hidden files should be displayed, based on query above.
							    if(substr("$dirArray[$index]", 0, 1)!=$hide) {
							
							// Resets Variables
								$favicon="";
								$class="file";
							
							// Gets File Names
								$name=$dirArray[$index];
								$namehref=$dirArray[$index];
							
							// Gets Date Modified
								$modtime=date("M j Y g:i A", filemtime($dirArray[$index]));
								$timekey=date("YmdHis", filemtime($dirArray[$index]));
							
							
							// Separates directories, and performs operations on those directories
								if(is_dir($dirArray[$index]))
								{
										$extn="&lt;Directory&gt;";
										$size="&lt;Directory&gt;";
										$sizekey="0";
										$class="dir";
							
									// Gets favicon.ico, and displays it, only if it exists.
										if(file_exists("$namehref/favicon.ico"))
											{
												$favicon=" style='background-image:url($namehref/favicon.ico);'";
												$extn="&lt;Website&gt;";
											}
							
									// Cleans up . and .. directories
										if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;"; $favicon=" style='background-image:url($namehref/.favicon.ico);'";}
										if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
								}
							
							// File-only operations
								else{
									// Gets file extension
									$extn=pathinfo($dirArray[$index], PATHINFO_EXTENSION);
							
									// Prettifies file type
									switch ($extn){
										case "xml": $extn="CCDA Document"; break;
										case "png": $extn="PNG Image"; break;
										case "jpg": $extn="JPEG Image"; break;
										case "jpeg": $extn="JPEG Image"; break;
										case "svg": $extn="SVG Image"; break;
										case "gif": $extn="GIF Image"; break;
										case "ico": $extn="Windows Icon"; break;
							
										case "txt": $extn="Text File"; break;
										case "log": $extn="Log File"; break;
										case "htm": $extn="HTML File"; break;
										case "html": $extn="HTML File"; break;
										case "xhtml": $extn="HTML File"; break;
										case "shtml": $extn="HTML File"; break;
										case "php": $extn="PHP Script"; break;
										case "js": $extn="Javascript File"; break;
										case "css": $extn="Stylesheet"; break;
							
										case "pdf": $extn="PDF Document"; break;
										case "xls": $extn="Spreadsheet"; break;
										case "xlsx": $extn="Spreadsheet"; break;
										case "doc": $extn="Microsoft Word Document"; break;
										case "docx": $extn="Microsoft Word Document"; break;
							
										case "zip": $extn="ZIP Archive"; break;
										case "htaccess": $extn="Apache Config File"; break;
										case "exe": $extn="Windows Executable"; break;
							
										default: if($extn!=""){$extn=strtoupper($extn)." File";} else{$extn="Unknown";} break;
									}
							
									// Gets and cleans up file size
										$size=pretty_filesize($dirArray[$index]);
										$sizekey=filesize($dirArray[$index]);
							                        
										$FileContents = file_get_contents("../CCDAs/$name");
								}
							
							// Output
							
							// Filter matches, display result row
							$pattern = "*$SearchFilter2*";
							if (stringMatchWithWildcard($name,$pattern) == 1) {
							  echo("
								 <tr class='$class'>
									 <td><span style='width:75%;text-align:left' file=\"./CCDAs/$name\" class='pure-button transform'>$name</span></td>
									 <td>$extn</td>
									 <!-- <td> <span onclick='loadtextarea(\"./CCDAs/$name\")' class='pure-button'>Show XML</span></td> -->
								 </tr>");
								 }
							    }
							 }
							 ?>
						</tbody>
					</table>
				</div>
			</div>
			<div align="center">
			</div>
			<!-- END TABLE VIEW -->
			<!--	<div aling="right">
				<br />
				<p>Raw XML:</p>
				</div>
				<textarea id="cdaxml" style="width:95%;height:20em;padding:0.25em;"
				placeholder="Your C-CDA document goes here:
				<ClinicalDocument xmlns=&quot;urn:hl7-org:v3&quot; xmlns:xsi=&quot;http://www.w3.org/2001/XMLSchema-instance&quot; xmlns:sdtc=&quot;urn:hl7-org:sdtc&quot; xmlns:cda=&quot;urn:hl7-org:v3&quot;>"
				></textarea>
				<div style="margin:0.5em" aling="right">
				<p id="transform" class="pure-button transform">Load this XML in the viewer<i class="fa fa-angle-double-right"></i></p>
				</div>-->
		</div>
		<div class=" cdaview"  id="viewcda">
			<p></p>
		</div>
		<div class="footer">
  			<p>Viewer site put together by <a href="http://visuafusion.com"><b>visuaFUSION Systems Solutions</b></a>, based upon the original HL7 C-CDA Viewer by <b>IntelSoft</b>.</p>
		</div>
		<script src="js/purejs.js"></script>
		<script>
			function filterResults() {
			    var filterString = document.getElementById("filterText").value;
			    window.location.replace('index.php?SearchFilter2=' + filterString);
			}
			function clearFilter() {
			    window.location.replace('index.php?SearchFilter2=');
			}

var input = document.getElementById("filterText");
input.addEventListener('keypress', function(event) {
  if (event.key === "Enter") {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    document.getElementById('filterSearch').click();
  }
});
		</script>
	</body>
</html>