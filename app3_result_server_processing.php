<?php
include 'functions.php';
error_reporting(0);
ini_set('display_errors', 'Off');
$draw=$_POST['draw'];
$start=$_POST['start']+2;
$length=$_POST['length'];
$end=$start+$length-1;
$resultfile=$_POST['resultfile'];
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
#echo $resultfile;
$sed_cmd="sed -";
$linecount=shell_exec("wc -l ".$resultfile);
$linecount=explode(" ",$linecount);
$linecount=$linecount[0];

$subsetfilename=$ROOT_DIR."Data/Pipeline/Results/shinyapp3/".generateRandomString() . ".txt";
shell_exec("sed -n " . $start . "," . $end . "p ". $resultfile . " > " . $subsetfilename);

$subsetdata = array("draw"=>$draw, "recordsTotal"=>$linecount, "recordsTotal"=>$linecount,"data"=>array());
$handle = fopen($subsetfilename, "r");
while(!feof($handle)){
    $line = fgets($handle);
    $line_array = explode("\t", $line);
    $database = trim($line_array[0]);
    $method = trim($line_array[1]);
    $drug = trim($line_array[2]);
    $species = trim($line_array[3]);
    $tissue = trim($line_array[4]);
    $study = trim($line_array[5]);
    $dose = trim($line_array[6]);
    $time = trim($line_array[7]);
    $jaccard = number_format(floatval($line_array[8]), 3, ".", "");
    $odds = number_format(floatval($line_array[9]), 3, ".", "");
    $pvalue = scientificNotation(trim((float)$line_array[10]));
    $rank = number_format(floatval($line_array[11]), 5, ".", "");
    $sider = trim($line_array[17]);
    if ($sider!="none") {
        $sider = '<a href=' . $sider . '> SIDER';
    }
    array_push($subsetdata,$database,$method,$drug,$species,$tissue,$study,$dose,$time,$jaccard,$odds,$pvalue,$rank,$sider);
}
fclose($handle);
echo json_encode($subsetdata);
?>