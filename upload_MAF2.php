<?php
/*


define ('UTF32_BIG_ENDIAN_BOM'   , chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
define ('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
define ('UTF16_BIG_ENDIAN_BOM'   , chr(0xFE) . chr(0xFF));
define ('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
define ('UTF8_BOM'               , chr(0xEF) . chr(0xBB) . chr(0xBF));

function detect_utf_encoding($text) {

    $first2 = substr($text, 0, 2);
    $first3 = substr($text, 0, 3);
    $first4 = substr($text, 0, 3);

    if ($first3 == UTF8_BOM) return 'UTF-8';
    elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
    elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
    elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
    elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE'; //this is what the utf file was
    else return 'not found';
}

*/

//If input from Excel using tab delimited text file, file encoding is us-ascii.
// ms dos is also us-ascii
// utf is utf-16le
// R output is us-ascii
// textEdit output for UTF-8 is us-ascii
// testEdit output for UTF-16 is utf-16le
error_reporting(0);
ini_set('display_errors', 'Off');
ini_set('memory_limit', -1);
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
function _detectFileEncoding($filepath) {
    // VALIDATE $filepath !!!
    $output = array();
    exec('file -i ' . $filepath, $output);
    if (isset($output[0])){
        $ex = explode('charset=', $output[0]);
        return isset($ex[1]) ? $ex[1] : null;
    }
    return null;
}

/**
* Decode UTF-16 encoded strings.
* 
* Can handle both BOM'ed data and un-BOM'ed data. 
* Assumes Big-Endian byte order if no BOM is available.
* 
* @param   string  $str  UTF-16 encoded data to decode.
* @return  string  UTF-8 / ISO encoded data.
* @access  public
* @version 0.1 / 2005-01-19
* @author  Rasmus Andersson {@link http://rasmusandersson.se/}
* @package Groupies
*/
function utf16_decode($str, &$be=null) {
    if (strlen($str) < 2) {
        return $str;
    }
    $c0 = ord($str[0]);
    $c1 = ord($str[1]);
    $start = 0;
    if ($c0 == 0xFE && $c1 == 0xFF) {
        $be = true;
        $start = 2;
    } else if ($c0 == 0xFF && $c1 == 0xFE) {
        $start = 2;
        $be = false;
    }
    if ($be === null) {
        $be = true;
    }
    $len = strlen($str);
    $newstr = '';
    for ($i = $start; $i < $len; $i += 2) {
        if ($be) {
            $val = ord($str[$i])   << 4;
            $val += ord($str[$i+1]);
        } else {
            $val = ord($str[$i+1]) << 4;
            $val += ord($str[$i]);
        }
        $newstr .= ($val == 0x228) ? "\n" : chr($val);
    }
    return $newstr;
}

$fileName = $_FILES['afile']['name'];
$fileType = $_FILES['afile']['type'];
$session_id = $_POST["session_id"];
#$target_path = $ROOT_DIR . $_POST['path'] . $session_id . basename($fileName);
$target_path = $_POST['path'] . $session_id . basename($fileName);
$data_type = $_POST['data_type'];
//$fileContent = file_get_contents($_FILES['afile']['tmp_name']);
//$dataUrl = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
$fh = fopen($_FILES['afile']['tmp_name'], 'r');
$index = 0;
$msg = "";
$staus = 0;

$incompatible_encoding=FALSE;

if ($fh) //check if the file was opened correctly
{
    $fileEncode = _detectFileEncoding($_FILES['afile']['tmp_name']);
    if($fileEncode=="utf-16le"){
    // write new file in utf-8 format
    	/*
        $write = NULL;
        $nLines = count(file($_FILES['afile']['tmp_name'])); 

        while ($index++ < $nLines)
        {
            $line = fgets($fh);
            //$write .= str_replace("\n","",str_replace("ÿþ","",utf8_encode($line))); // put byte order mark in front of header ÿþ
            //$write .= mb_convert_encoding($line, 'UTF-16LE', 'UTF-8');
            $write .= iconv($in_charset ='UTF-16LE',$out_charset = 'UTF-8', $line);
        }
        $newFileName = "./Data/Pipeline/tmpFileEncoding/" . "$session_id" . $fileName;
        $newFile = fopen($newFileName, "w");
  		fwrite($newFile, $write);
  		fclose($newFile);
  		*/
  		$newFileName = $ROOT_DIR."Data/Pipeline/tmpFileEncoding/" . "$session_id" . $fileName;
  		$utf8Contents = utf16_decode(file_get_contents($_FILES['afile']['tmp_name']));
  		$newFile = fopen($newFileName, 'w');
    	fwrite($newFile, pack("CCC",0xef,0xbb,0xbf));
    	fwrite($newFile, $utf8Contents);
    	fclose($newFile);

        // if ETPMWAS, create genfile
        if(strpos($target_path, "msea_temp") !== false){
            $nLines = count(file($newFileName));
            $convertedfile = fopen($newFileName, 'r');
            $index = 0;
            $write = NULL;
            $write .= "GENE" . "\t" . "MARKER" . "\n";
            while ($index++ < $nLines)
            {
                $line = fgets($convertedfile);
                $line_arr = explode("\t", $line);
                $marker = $line_arr[0];
                $write .= $marker . "\t" . $marker . "\n";
            }
            $genfile = $_POST['path'] . $session_id . "genfile_for_geneEnrichment.txt";
            $genfile_file = fopen($genfile, "w");
            fwrite($genfile_file, $write);
            fclose($overview_file);
            chmod($genfile, 0777);
        }

        $incompatible_encoding=TRUE;
        fclose($fh);
    }
    
}

if($incompatible_encoding){
	$fh = fopen($ROOT_DIR."Data/Pipeline/tmpFileEncoding/" . "$session_id" . $fileName, 'r');
}

//antimalware run
exec('clamscan --infected --remove --quiet ' . escapeshellarg($ROOT_DIR."Data/Pipeline/tmpFileEncoding/" . "$session_id" . $fileName), $ROOT_DIR."Data/Pipeline/tmpFileEncoding/". "$session_id".$fileName.".clam.out", $return);
if ($return == 0) {
    // No virus found
    $index = 0;

    if ($fh) //check if the file was opened correctly
    {
        while ($index++ < 2) //run the loop twice
        {
            $line = fgets($fh); //read each line individually
            if ($data_type == "marker_association") {
                $check = "MARKER\tVALUE";
            } else if ($data_type == "mapping") {
                $check = "GENE\tMARKER";
            } else if ($data_type == "gene_set") {
                $check = "MODULE\tGENE";
            } else if ($data_type == "gene_set_desc") {
                $check = "MODULE\tSOURCE\tDESCR";
            } else if ($data_type == "mdf") {
                $check = "MARKERa\tMARKERb\tWEIGHT";
            }
            /*
            if($test == 'UTF-8'){
                $msg = "utf 8";
                break;
            }
            */
            if(!(preg_match("/\t/", $line))){
                $msg = "File not tab delimited. Please use tabs as the file separators.";
                break;
            }
            else if ($line == true && $index == 1) {
                if (strstr($line, $check)) {
                    $msg = "Header is correct";
                } else {
                    $msg = "Column headers are incorrect! <br> Please refer to the sample file format and reupload!";
                    break;
                }
            } else if ($line == false && $index == 2) {
                $msg = "No data or empty file";
            } else if ($line == true && $index == 2) {
                if (preg_match('/\S/', $line)) {
                    $msg = "Header is correct and secondline does have data" . $fileEncode;
                    if($incompatible_encoding){
                        copy($newFileName, $target_path);
                        $status = 1;
                    }
                    else{
                        $status = move_uploaded_file($_FILES['afile']['tmp_name'], $target_path);
                    }
                } else {
                    $msg = "Data not detected! <br> Please refer to the sample file format and reupload!";
                }
            } else {
                $msg = "No data or empty file: " . $fileName;
            }
        }
    } else {
        // error opening the file.
        $msg = "Could not open file: " . $fileName . $fileEncode;
    }
    fclose($fh);
} else {
    fclose($fh);
    unlink($fh);
    $msg = "malicious file detected: " . $fileName;
    // Virus found   
}




$json = json_encode(array(
    'name' => $fileName,
    'type' => $fileType,
    'msg' => $msg,
    'status' => $status,
    'targetPath' => $target_path,
));

echo $json;
