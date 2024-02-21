<?php include_once("analyticstracking.php") ?>
<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
$random_string=trim($_GET['My_ses']);

$assocation_file = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/"."$random_string"."_output/MDF_corrected_association.txt";
$mapping_file = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/"."$random_string"."_output/MDF_corrected_mapping.txt";
$overview_file =$ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/"."$random_string"."_MDF_file_parameter_selection.txt";
$out_file = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/" . $random_string . ".MDF_joblog.txt";
$ssea_json = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/$random_string" . "data.json";
$data = json_decode(file_get_contents($ssea_json))->data;
$mapping = $data[0]->marker;
$geneconvertedfile = str_replace("Resources/ssea_temp/", $ROOT_DIR . "Data/Pipeline/Resources/ssea_temp/Converted_", $mapping);

$files = array($assocation_file, $mapping_file, $out_file, $geneconvertedfile[0]);

$zipname = $ROOT_DIR . 'Data/Pipeline/Resources/ldprune_temp/'."$random_string".'_output/'.'MD_Prune.zip';
$abspath = $ROOT_DIR . 'Data/Pipeline/Resources/ldprune_temp/'."$random_string".'_output/';
$abspath1 = $ROOT_DIR . 'Data/Pipeline/Resources/ldprune_temp/';
$abspath2 = $ROOT_DIR . 'Data/Pipeline/Resources/ssea_temp/';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($files as $file) {
	if(file_exists($file)){
		if(strpos($file, $abspath)!==false){
			$filenameonly = str_replace($abspath,"",$file);
		}
		else if(strpos($file, $abspath1)!==false){
			$filenameonly = str_replace($abspath1,"",$file);
		}
		else if(strpos($file, $abspath2)!==false){
			$filenameonly = str_replace($abspath2,"",$file);
		}
		$zip->addFile($file,$filenameonly);
	}
}
$zip->addFile($overview_file,"MDF_file_parameter_selection.txt");
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='."MD_Prune.zip");
header('Content-Length: ' . filesize($zipname));

// Add these
ob_clean();
flush();

readfile($zipname);
unlink($zipname);

header("Location: ld_prune_zip.php?My_ses=<?php print($random_string);?>");

?>