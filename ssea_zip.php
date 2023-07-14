<?php

$random_string=trim($_GET['My_ses']);

$details_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/"."$random_string".".MSEA_modules_details.txt"; //change this to fix this! (Thien)
$genes_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/"."$random_string".".MSEA_genes_details.txt";
$pvalues_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/$random_string.MSEA_modules_pval.txt";
$results_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/"."$random_string".".MSEA_modules_full_result.txt";
$nodes_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/"."$random_string".".MSEA_genes_top_marker.txt";
$info_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/"."$random_string".".MSEA_merged_modules_full_result.txt";
$modules_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/"."$random_string".".MSEA_merged_modules.txt";    
$overview_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/"."$random_string".".MSEA_file_parameter_selection.txt";

$files = array($nodes_file, $info_file, $modules_file, $details_file, $genes_file, $pvalues_file, $results_file, $overview_file);

$zipname = '/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/'."$random_string".'_MSEA.zip';
$abspath = '/home/www/abhatta3-webserver/Data/Pipeline/Results/ssea/';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($files as $file) {
	if(file_exists($file)){
		$filenameonly = str_replace($abspath,"",$file);
		$zip->addFile($file,$filenameonly);
	}
}
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='."MSEA.zip");
header('Content-Length: ' . filesize($zipname));

// Add these
ob_clean();
flush();

readfile($zipname);
unlink($zipname);

header("Location: ssea_zip.php?My_ses=<?php print($random_string);?>");
file_e
?>