<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['sessionID'])) {
	$sessionID = $_GET['sessionID'];
}

if (isset($_GET['pipeline'])) {
	$pipeline = $_GET['pipeline'];
}

if($pipeline=="msea"){
	$outfile = "./Data/Pipeline/Results/ssea/" . $sessionID . ".MSEA_joblog.txt";
}
else if($pipeline=="meta"){
	$outfile = "./Data/Pipeline/Results/meta_ssea/" . $sessionID . "_joblog.txt";
}
else if($pipeline=="kda"){
	$outfile = "./Data/Pipeline/Results/kda/" . $sessionID . ".wKDA_joblog.txt";
}
else if($pipeline=="mdf"){
	$outfile = "./Data/Pipeline/Resources/ldprune_temp/" . $sessionID . ".MDF_joblog.txt";
}

//$outfile = "./Data/Pipeline/Results/shinyapp2/" . $sessionID . "out.txt";
if (file_exists($outfile)) {
	$file = file_get_contents($outfile);
	// $file = str_replace("/home/www/abhatta3-webserver/Data/Pipeline/Resources/ldprune_temp/", "", $file);
	// $file = str_replace("/home/www/abhatta3-webserver/Data/Pipeline/Resources/LD_files/", "", $file);
	$file = str_replace($ROOT_DIR."Data/Pipeline/Resources/ldprune_temp/", "", $file);
	$file = str_replace($ROOT_DIR."Data/Pipeline/Resources/LD_files/", "", $file);
	print nl2br($file);
}
	
?>