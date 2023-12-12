<?php include_once("analyticstracking.php") ?>
<?php

$random_string=trim($_GET['My_ses']);

$details_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/"."$random_string"."_meta_result/ssea/"."$random_string".".MSEA_top_modules_details.txt";
$pvalues_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/"."$random_string"."_meta_result/ssea/"."$random_string".".MSEA_modules_pval.txt";
$results_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/"."$random_string"."_meta_result/ssea/"."$random_string".".MSEA_modules_full_result.txt";
$nodes_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/"."$random_string"."_meta_result/ssea/"."$random_string".".MSEA_genes_top_marker.txt";
$info_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/"."$random_string"."_meta_result/ssea/"."$random_string".".MSEA_merged_modules_full_result.txt";
$modules_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/"."$random_string"."_meta_result/ssea/"."$random_string".".MSEA_merged_modules.txt";    
$overview_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/"."$random_string"."_meta_result/ssea/"."$random_string".".MSEA_file_parameter_selection.txt";
$combinedmeta_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/" . "$random_string" . "_meta_result/ssea/" . "$random_string" . "_META.combined.results.txt";
$individual_files = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/" . "$random_string" . ".meta.inter.results/Individual_MSEA_Results.zip";
$joblog = "/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/" . "$random_string" . "_joblog.txt";

$files = array($nodes_file, $info_file, $modules_file, $details_file, $pvalues_file, $results_file, $overview_file, $combinedmeta_file, $individual_files, $joblog);

$zipname = '/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/'."$random_string".'_meta_result/'.'MD_Prune.zip';
$abspath = '/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/'."$random_string".'_meta_result/ssea/';
$abspath1 = '/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/'."$random_string".'_meta_result/';
$abspath2 = '/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/'."$random_string".'.meta.inter.results/';
$abspath3 = '/home/www/abhatta3-webserver/Data/Pipeline/Results/meta_ssea/';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($files as $file) {
	if(file_exists($file)){
		if(strpos($file, $abspath)!==false){
			$filenameonly = str_replace($abspath,"",$file);
		}
		//else if(strpos($file, $abspath1)!==false){
		else if(strpos($file, "meta_result")!==false){
			$filenameonly = str_replace($abspath1,"",$file);
		}
		else if(strpos($file, $abspath2)!==false){
			$filenameonly = str_replace($abspath2,"",$file);
		}
		else{
			$filenameonly = str_replace($abspath3,"",$file);
		}
		//$filenameonly = str_replace($abspath,"",$file);
		/*
		if(strpos("combined", $file)){
			$filenameonly = str_replace($abspath1,"",$file);
		}
		else if(strpos("meta.inter.results", $file)){
			$filenameonly = str_replace($abspath2,"",$file);
		}
		else{
			$filenameonly = str_replace($abspath,"",$file);
		}*/
		$zip->addFile($file,$filenameonly);
	}
}
$zip->addFile($overview_file,"$random_string".".MSEA_file_parameter_selection.txt");
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='."Meta_MSEA.zip");
header('Content-Length: ' . filesize($zipname));

// Add these
ob_clean();
flush();

readfile($zipname);
unlink($zipname);

header("Location: meta_zip.php?My_ses=<?php print($random_string);?>");
file_e
?>