<?php
function debug_to_console($data)
{
  $output = $data;
  if (is_array($output))
    $output = implode(',', $output);
  echo "<script type=\"text/javascript\">console.log('Debug Objects: " . $output . "' );</script>";
}
function readMappingFile($path)
{
  //$arr = array();
  $handle = fopen($path, "r");
  $content = "";
  if ($handle) {
    $row = 0;
    while (($line = fgets($handle)) !== false) {
      $row++;
      if ($row > 1) {
        $content .= $line;
        //$arr[] = $line;
      }
    }
    fclose($handle);
    return $content;
    //return $arr;
  }
}
//This result file is for MDF
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";


/* Initialize PHP variables
sessionID = the saved session 

GET = if the user enters the link directly
POST = if PHP enters the link

*/


if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}


if (isset($_GET['run'])) {
  $run = $_GET['run'];
} else {
  $run = "F";
}

//$ssea_json = $ROOT_DIR . "Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
$ssea_json = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/$sessionID" . "data.json";
$data = json_decode(file_get_contents($ssea_json))->data;
$mapping = $data[0]->marker;
$MMFConvert =  $data[0]->MMFConvert;
if($run == "T"){
  if (count($mapping) > 1) {
    //$newMappingcontent = array();
    $newMappingcontent = "GENE" . "\t" . "MARKER" . "\n";
    //$count = 1;
    foreach ($mapping as &$value) {
      /*
      if($count==1){
        $newMappingcontent = readMappingFile($ROOT_DIR . "Data/Pipeline/" . $value);
        $count++;
      }
      else{
        $newMappingcontent = array_push($newMappingcontent, readMappingFile($ROOT_DIR . "Data/Pipeline/" . $value));
      }
      */
      $newMappingcontent .= readMappingFile($ROOT_DIR . "Data/Pipeline/" . $value);
    }
    //$newMappingcontent = array_unique($newMappingcontent);
    $mapping = "Resources/ssea_temp/" . $sessionID . ".mappingfile.txt";
    $fp = fopen("./Data/Pipeline/" . $mapping, 'w');
    fwrite($fp, $newMappingcontent);
    fclose($fp);
  } else {
    $mapping = $mapping[0];
    if($MMFConvert!=="none"){
      shell_exec($ROOT_DIR . 'R-3.4.4/bin/Rscript ./Data/Pipeline/geneConversion.R '. $sessionID . " " . $MMFConvert . " " . $mapping);
    }
  }
}


//filepath to output folder
$resultfile = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_output/";

/*********************************************************************************** 
Run shell script to run R file
 *******************************************************************************/
/*********************************************************************************** 
Get the path of the new renamed output files
 *******************************************************************************/
$assocation_file = "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/MDF_corrected_association.txt";
$mapping_file = "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/MDF_corrected_mapping.txt";
$overview_file = "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_MDF_file_parameter_selection.txt";
//echo ('cd ' . $ROOT_DIR . 'Data/Pipeline; ' . $ROOT_DIR . 'run_ld_prune.sh ' . $sessionID);
if (!file_exists($assocation_file) || $run == "T") {
  $outfile = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/" . $sessionID . ".MDF_joblog.txt";
  shell_exec('cd ' . $ROOT_DIR . 'Data/Pipeline; bash ' . $sessionID . 'preprocess.bash ' . '2>&1 | tee -a ' . $outfile);

}


//there are no checks so that it will run a job each time
//even if they already ran it


/*********************************************************************************** 
Send out result email if user has entered an email
 *******************************************************************************/
$results_sent = "./Data/Pipeline/Results/ld_prune_email/$sessionID" . "sent_email";
$results_notified = "./Data/Pipeline/Results/ld_prune_email/$sessionID" . "sent_email_notified";

if ((!(file_exists($results_notified)))) {
  if ((file_exists($results_sent))) {
    require_once('./PHPMailer-master/class.phpmailer.php');
    $emailid = "./Data/Pipeline/Results/ld_prune_email/$sessionID" . "email";

    $mail = new PHPMailer();

    $mail->Body = 'Congratulations! You have successfully executed our pipeline. Please download your results.';
    $mail->Body .= "\n";
    $mail->Body .= 'Your results are available at: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=';
    $mail->Body .= "$sessionID";

    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "smha118@g.ucla.edu";  // GMAIL username
    $mail->Password   = "mergeomics729@";            // GMAIL password


    $mail->SetFrom('smha118@g.ucla.edu', 'Daniel Ha');

    $mail->Subject    = "Mergeomics MDF Execution Complete!";

    $address = trim(file_get_contents($emailid));
    $mail->AddAddress($address);
    if (!$mail->Send()) {
      //echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
      $myfile = fopen("./Data/Pipeline/Results/ld_prune_email/$sessionID" . "sent_email_notified", "w");
      fwrite($myfile, $address);
      fclose($myfile);
    }
  }
}



/*********************************************************************************** 
Get the path of the output files from the R script
 *******************************************************************************/
// $assocation_file = "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/marker.txt";
// $mapping_file = "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/genes.txt";
// $overview_file = "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_overview.txt";

// RENAME ASSOCIATION FILE
if (file_exists("./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/marker.txt")) {
  rename("./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/marker.txt", "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/MDF_corrected_association.txt");
}

// RENAME MAPPING FILE
if (file_exists("./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/genes.txt")) {
  rename("./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/genes.txt", "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/MDF_corrected_mapping.txt");
}

// RENAME OVERVIEW FILE
if (file_exists("./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_overview.txt")) {
  rename("./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_overview.txt", "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_MDF_file_parameter_selection.txt");
}

$outfile = "Data/Pipeline/Resources/ldprune_temp/" . $sessionID . ".MDF_joblog.txt";
// basename doesn't work on session reload...
$geneconvertedfile = str_replace("Resources/ssea_temp/", "Data/Pipeline/Resources/ssea_temp/Converted_", $mapping);
//$geneconvertedfile = "Data/Pipeline/Resources/ssea_temp/Converted_" . basename($mapping);
//$pieces = explode("/",$mapping);
//$mappingname = end($pieces);

//$mappingname = pathinfo(strval($mapping), PATHINFO_FILENAME);
//$geneconvertedfile = "Data/Pipeline/Resources/ssea_temp/Converted_" . $mappingname;




/*********************************************************************************** 
Update the session
 *******************************************************************************/
$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$fpostOut = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_MDF_postdata.txt";
if (file_exists($fsession)) {
  $data = file($fsession); // reads an array of lines
  function replace_a_line($data)
  {
    if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.5")) { //1.5 --> 1.75
      return 'Mergeomics_Path:' . "\t" . "1.75" . "\n";
    }
    return $data;
  }
  $data = array_map('replace_a_line', $data);
  file_put_contents($fsession, implode('', $data));
}
$fjson = "./Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
$json = json_decode(file_get_contents($fjson))->data;
$marker_association = "Resources/ldprune_temp/" . $sessionID . "_output/MDF_corrected_association.txt";
$mapping = "Resources/ldprune_temp/" . $sessionID . "_output/MDF_corrected_mapping.txt";
$json[0]->association = $marker_association;
$json[0]->marker = $mapping;
$data = null;
if (empty($data->data)) {
  $data['data'][] = $json[0];
} else {
  $data->data[] = $json[0];
}
$fp = fopen($fjson, 'w');
fwrite($fp, json_encode($data));
fclose($fp);
chmod($fjson, 0777);




?>







<br>
<br>

<!-- Result/Download table ===================================================== -->
<table class="table table-bordered review" style="text-align: center" ; id="MDFresultstable">

  <thead>
    <tr>
      <th colspan="3">Download Output Files</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Corrected Marker Associations</td>
      <td>'MARKER' and 'VALUE' file with marker with lower value in linkage disequilibrium filtered out</td>
      <td><a tooltip="Optional for possible future use, not necessary for immediate continuation of analysis" style="position: relative;" href=<?php print($assocation_file); ?> download> Download</a></td>
    </tr>
    <tr>
      <td>Corrected Marker Mappings</td>
      <td>'GENE' and 'MARKER' file with mappings subsetted to those matching markers in the association file</td>
      <td><a tooltip="Optional for possible future use, not necessary for immediate continuation of analysis" style="position: relative;" href=<?php print($mapping_file); ?> download> Download</a></td>
    </tr>
    <tr>
      <td>MDF input files and parameters</td>
      <td>File listing chosen files and parameters for this MDF run</td>
      <td><a tooltip="To keep track of MDF inputs" style="position: relative;" href=<?php print($overview_file); ?> download> Download</a></td>
    </tr>
    <tr>
      <td>Runtime job log</td>
      <td>Runtime output and errors (if any) of job</td>
      <td><a style="position: relative;" href=<?php print($outfile); ?> download> Download</a></td>
    </tr>
    <?php
    if ($MMFConvert !== "none") { 
      if($MMFConvert == "entrez"){
        $convertedfile = "Entrez to gene symbols converted mapping file";
      } else {
        $convertedfile = "Ensembl to gene symbols converted mapping file";
      }
      if($run=="F"){
        $geneconvertedfile = $geneconvertedfile[0];
      }
    ?>
    <tr>
      <td>
        <?php echo $convertedfile; ?> 
      </td>
      <td>
        Original marker mapping file with gene identifers converted to gene symbols. Gene identifiers not matching any gene symbols were removed.
      </td>
      <td>

        <a href=<?php print($geneconvertedfile); ?> download> Download</a>
      </td>
    </tr>
    <?php }
    ?>
  </tbody>
</table>


<br>
<div style="text-align: center;">
  <!-- Download zip file button ===================================================== -->
  <input type="button" class="button button-3d button-small nomargin" value="Click to Download All Files in Zip Folder" onclick="window.open('ld_prune_zip.php?My_ses=<?php print($sessionID); ?>','_self','resizable=yes')" />
</div>
<br>

<input type="hidden" name='My_kda' value="<?php $send = "$sessionID";
                                          print($send); ?>">
<h4 class="instructiontext">To continue directly to MSEA, click below:
  <br>
  <!-- Run MSEA button ===================================================== -->
  <button type="button" class="button button-3d button-large pipeline" id="RunSSEA">Run MSEA Pipeline</button>
</h4>

<?php

//error if the files aren't created.
//There should be a better error message. Maybe outputting the error of the R code instead.
//You can look at echoing shell_exec( ****.sh 2>&1);
if (!file_exists("./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/MDF_corrected_association.txt")) { ?>
  <div class="warning-message"> ERROR! There was an error making the corrected association file. Make sure all inputs are in the correct format. They should be tab delimited text files with headers 'MARKER' 'VALUE' for the association file, 'GENE' 'MARKER' for the mapping file, and 'MARKERa' 'MARKERb' 'WEIGHT' for the LD file.</div>
<?php }
?>



<script type="text/javascript">
  var session_id = "<?php echo $sessionID ?>";
  /*********************************************************************************** 
Run SSEA script
*******************************************************************************/
  console.log("line337")
  $('#RunSSEA').on('click', function() {
    $('#MDFtogglet').click();
    $('#SSEAtoggle').show();
    $('#mySSEA').load('/SSEA_parameters.php?sessionID=' + session_id);

    $('#SSEAtogglet').click();

    //sidebar
    /*
    $("#MDFflowChart").next().css('opacity','1');
    $("#MSEAflowChart").addClass('activePipe');
    */

    return false;


  });
</script>