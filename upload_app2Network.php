<?php
$fileName = $_FILES['afile']['name'];
$fileType = $_FILES['afile']['type'];
$session_id = $_POST["session_id"];
$target_path = $_POST['path'] . $session_id . "_network.txt";
$data_type = $_POST['data_type'];
//$fileContent = file_get_contents($_FILES['afile']['tmp_name']);
//$dataUrl = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
$fh = fopen($_FILES['afile']['tmp_name'], 'r');
$index = 0;
$msg = "";
$status = 0;
if ($fh) //check if the file was opened correctly
{
    while ($index++ < 2) //run the loop twice (just look at first two lines)
    {
        $line = fgets($fh); //read each line individually

        if ($line == false && $index == 1) {
            $msg = "No data or empty file";
        } else if ($line == false && $index == 2) {
            $msg = "No data or empty file";
        } else if ($line == true && $index == 2) {
            $msg = "File contains data";
            $status = move_uploaded_file($_FILES['afile']['tmp_name'], $target_path);
        }

         else {
            $msg = "No data or empty file: " . $fileName;
        }
    }
} else {
    // error opening the file.
    $msg = "Could not open file: " . $fileName;
}
fclose($fh);

$json = json_encode(array(
    'name' => $fileName,
    'type' => $fileType,
    'msg' => $msg,
    'status' => $status,
    'targetPath' => $target_path,
));

echo $json;
