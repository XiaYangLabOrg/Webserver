<?php
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  $sessionID = $_POST['sessionID'];
}
if (isset($_POST['genelist']) ? $_POST['genelist'] : null) {
  $genelist = $_POST['genelist'];
}
$array = explode("\n", trim($genelist));
$final = "MODULE\tNODE\n";
foreach ($array as $line) {
  $final .= "Input_GeneList\t$line" . "\n";
}
$filename = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "_nodes_file.txt";

$f = fopen($ROOT_DIR . $filename, 'w');
fwrite($f, $final);
fclose($f);

echo $filename;
