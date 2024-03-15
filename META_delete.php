<?php
if (isset($_GET['metasessionID'])) {
    $meta_sessionID = $_GET["metasessionID"];
    $fjson = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "data.json";
}

if (isset($_GET['sessionID'])) {
    $sessionID = $_GET["sessionID"];
    $fpath_list = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";
    $num_iterations = count(file($fpath_list));
}

if (isset($_GET['sessiondelete'])) {
    $sessiondelete = $_GET["sessiondelete"];
    $fpath_delete = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";
}


$fullPath = "./Data/Pipeline/Resources/meta_temp/";

$fdelete = glob($fullPath . "$sessiondelete" . "*");


$check = '';

foreach ($fdelete as $file) {
    if ($file == $fpath_delete) {
        continue;
    }


    unlink($file);
}



$lines = file($fpath_list);
$result = NULL;

foreach ($lines as $line) {
    if (strpos($line, $sessiondelete) !== false) {
        $result .= '';
    } else {
        $result .= $line;
    }
}

file_put_contents($fpath_list, $result);

if ($sessionID !== $sessiondelete) {
    unlink($fpath_delete);
}



$json = json_decode(file_get_contents($fjson));

$fpath_random = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";

$i = 0;
$list_strings = "";
$newjson = array();
foreach ($json->data as $element) {
    if ($element->session != $sessiondelete) {
        array_push($newjson, $element);
    } else {
        $list_strings .= $element->session . "\n";
    }
    $i++;
}

if (count($newjson) == 0) {
    if (file_exists($fpath_list)) {
        unlink($fpath_list);
    }
    if (file_exists($fjson)) {
        unlink($fjson);
    }
} else {
    if (empty($json->data)) {
        $json['data'] = $newjson;
    } else {
        $json->data = $newjson;
    }
    //$data["data"] = $json->data;
    $fp_list_strings = fopen($fpath_random, "w");
    fwrite($fp_list_strings, $list_strings);
    fclose($fp);
    chmod($fp_list_strings, 0775);
    $fjson = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "data.json";
    $fp = fopen($fjson, 'w');
    fwrite($fp, json_encode($json));
    fclose($fp);
    chmod($fjson, 0775);
}


 // Encoding array in JSON format
?> 


