<?php
error_reporting(E_ERROR | E_PARSE);
if (isset($_GET['sessionID'])) {
	$sessionID = $_GET['sessionID'];
}

if (isset($_GET['pipeline'])) {
	$pipeline = $_GET['pipeline'];
}

if($pipeline=="msea"){
	$outfile = "./Data/Pipeline/Results/ssea/" . $sessionID . "_joblog.txt";
}
else if($pipeline=="meta"){
	$outfile = "./Data/Pipeline/Results/meta_ssea/" . $sessionID . "_joblog.txt";
}
else if($pipeline=="kda"){
	$outfile = "./Data/Pipeline/Results/kda/" . $sessionID . "_joblog.txt";
}

//$outfile = "./Data/Pipeline/Results/shinyapp2/" . $sessionID . "out.txt";
if (file_exists($outfile)) {
	$file = file_get_contents($outfile);
	print nl2br($file);
}
	
?>