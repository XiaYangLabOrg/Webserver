<?php include_once("analyticstracking.php") ?>
<?php

$random_string=trim($_GET['My_ses']);

$hubs_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/kda/$random_string.wKDA_hubs_structure.txt";
$pvalues_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/kda/$random_string.wKDA_kd_pval.txt";
$results_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/kda/$random_string.wKDA_kd_full_results.txt";
$tophits_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/kda/$random_string.wKDA_kd_tophits.txt";
$overview_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/kda/$random_string.wKDA_file_parameter_selection.txt";
$outfile = "/home/www/abhatta3-webserver/Data/Pipeline/Results/kda/$random_string.wKDA_joblog.txt";

$edges_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/cytoscape/$random_string.wKDA_cytoscape_edges.txt";
$nodes_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/cytoscape/$random_string.wKDA_cytoscape_nodes.txt";
$topkds_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/cytoscape/$random_string.wKDA_cytoscape_top_kds.txt";
$color_file = "/home/www/abhatta3-webserver/Data/Pipeline/Results/cytoscape/$random_string.wKDA_cytoscape_module_color_mapping.txt";

//$files1 = array($hubs_file, $pvalues_file, $results_file, $tophits_file, $overview_file);
$files1 = array($results_file, $overview_file, $outfile);
$files2 = array($edges_file, $nodes_file, $topkds_file, $color_file);

$zipname = '/home/www/abhatta3-webserver/Data/Pipeline/Results/kda/'."$random_string".'_wKDA.zip';
$abspath1 = '/home/www/abhatta3-webserver/Data/Pipeline/Results/kda/';
$abspath2 = '/home/www/abhatta3-webserver/Data/Pipeline/Results/cytoscape/';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($files1 as $file) {
	if(file_exists($file)){
		$filenameonly = str_replace($abspath1,"",$file);
		$zip->addFile($file,$filenameonly);
	}
}
foreach ($files2 as $file) {
	if(file_exists($file)){
		$filenameonly = str_replace($abspath2,"",$file);
		$zip->addFile($file,$filenameonly);
	}
}
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='."wKDA.zip");
header('Content-Length: ' . filesize($zipname));

// Add these
ob_clean();
flush();

readfile($zipname);
unlink($zipname);

header("Location: kda_zip.php?My_ses=<?php print($random_string);?>");
file_e
?>