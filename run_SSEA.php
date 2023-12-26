<?php
//This run file is for when the user runs SSEA (from MDF).
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
function debug_to_console($data)
{
  $output = $data;
  if (is_array($output))
    $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
debug_to_console("11");
function readMappingFile($path)
{
  $handle = fopen($path, "r");
  $content = "";
  if ($handle) {
    $row = 0;
    while (($line = fgets($handle)) !== false) {
      $row++;
      if ($row > 1) {
        $content .= $line;
      }
    }
    fclose($handle);
    return $content;
  }
}
debug_to_console("29");
/* Initialize PHP variables
sessionID = the saved session 

GET = if the user enters the link directly
POST = if PHP enters the link

*/

debug_to_console("38");
if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}
debug_to_console("42");
if (isset($_GET['run'])) {
  $run = $_GET['run'];
}

?>

<!-- MSEA Job table ===================================================== -->
<table class="table table-bordered" style="text-align: center" ;>
  <thead>
    <tr>
      <th>Marker Set Enrichment Analysis Job is running</th>
    </tr>
  </thead>
  <tbody>


    <tr>
      <td>
        <!-- Loading car animation ===================================================== -->
        <div class="loading-window">
          <div class="DNA_cont">
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
          </div>

          <div class="text">
            <span>Running</span><span class="dots">...</span>
          </div>
        </div>
        </div>

        <div style="text-align: center;">
          <h4 class="instructiontext">
            MSEA is running on data. You can wait to get the results in about 30 minutes. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=<?php print($sessionID); ?>
          </h4>
        </div>
      </td>

    </tr>

  </tbody>
</table>


<?php

/* This gets the path of all the parameter files that were created in _parameters.php
    This is where you can change/optimize the code if needed 
*/

// $fpath1 = $ROOT_DIR . "/Data/Pipeline/Resources/ssea_temp/$sessionID" . "GWAS_file_list";
// $fpath2 = $ROOT_DIR . "/Data/Pipeline/Resources/ssea_temp/$sessionID" . "LOCI";
// $fpath3 = $ROOT_DIR . "/Data/Pipeline/Resources/ssea_temp/$sessionID" . "MODULE";
// $fpath4 = $ROOT_DIR . "/Data/Pipeline/Resources/ssea_temp/$sessionID" . "DESC";
// $fpathparam = $ROOT_DIR . "/Data/Pipeline/Resources/ssea_temp/$sessionID" . "PARAM";
// $fpath5 = $ROOT_DIR . "/Data/Pipeline/Resources/ssea_temp/$sessionID" . "PARAM_SSEA_FDR";
debug_to_console("107");
$fjson = $ROOT_DIR . "Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
$json = json_decode(file_get_contents($fjson))["data"];
$perm_type = $json[0]["perm"];
$max_gene = $json[0]["maxgenes"];
$min_gene = $json[0]["mingenes"];
$minoverlap = $json[0]["minoverlap"];
$maxoverlap = $json[0]["maxoverlap"];
$sseanperm = $json[0]["numperm"];
$sseafdr = $json[0]["fdrcutoff"];
$marker_association = $json[0]["association"];
$mapping = $json[0]["marker"];
$module =  $json[0]["geneset"];
$enrichment = $json[0]["enrichment"];
$module_info =  $json[0]["genedesc"];
$GSETConvert = $json[0]["GSETConvert"];
$MMFConvert = $json[0]["MMFConvert"];
// debug_to_console($marker_association);
// debug_to_console($mapping);
// debug_to_console($module);
// debug_to_console($module_info);
debug_to_console("128");
if (is_string($mapping)) {
  //File will be generated in result_SSEA.php
  $mapping = "Resources/ssea_temp/" . $sessionID . ".mappingfile.txt";
} else {
  if (is_array($mapping)) {
    $mapping_val = $mapping[0];
    // Jess added
    $mapping = $mapping_val;
  } else {
    $mapping_val = $mapping;
    // Jess added
    $mapping = $mapping_val;
  }
}
//debug_to_console("Mapping:" . $mapping);
debug_to_console("144");
//Path of where the R code/file is created
$fpathOut = $ROOT_DIR . "/Data/Pipeline/$sessionID" . "analyze.R";

//Get all the information from the parameters files and store into variables
$file1 = trim($ROOT_DIR . "Data/Pipeline/" . $mapping);
$file2 = trim($ROOT_DIR . "Data/Pipeline/" . $marker_association);
$file3 = trim($ROOT_DIR . "Data/Pipeline/" . $module);
$file4 = trim($ROOT_DIR . "Data/Pipeline/" . $module_info);
$file5 = trim($sseafdr);
debug_to_console("154");
$par = "job.msea\$permtype<-\"$perm_type\"" . "\n" .
  "job.msea\$maxgenes<-$max_gene" . "\n" .
  "job.msea\$mingenes<-$min_gene" . "\n" .
  "rmax<-$minoverlap" . "\n" .
  "job.msea\$maxoverlap<-$maxoverlap" . "\n" .
  "job.msea\$nperm<-$sseanperm" . "\n" .
  "job.msea\$label<-\"$sessionID\"" . "\n";


//store some R variables with the file information 
$out = "job.msea\$label <- \"$sessionID\"";   //label
$file1 = "job.msea\$genfile <- \"$file1\""; //genfile (mapping file/path_to_cat_GWAS)
$file2 = "job.msea\$marfile <- \"$file2\""; //marfile (Associationfile)
$file3 = "job.msea\$modfile <- \"$file3\""; //modfile (MODULE)
debug_to_console("169");
//if the user did not select an information file
if (strpos($module_info, "None Provided") !== false) {
  //then set as empty 
  $file4 = "";
} else {
  //otherwise set the R variable to file4

  $file4 = "job.msea\$inffile <- \"$file4\"";
}

debug_to_console("180");
$file5 = "FDR_filter <- (" . "$file5" . "/100)" . "\n";
if($GSETConvert!=="none"){
    debug_to_console("GSETConvert:" . $GSETConvert);
    $file5 .= "GSETConvert <- \"$GSETConvert\"" . "\n"; //label
}
if($MMFConvert!=="none"){
    $file5 .= "MMFConvert <- \"$MMFConvert\"" . "\n"; //label
}

debug_to_console("190");
//Some extra R code that is added into the final R file
//Could also probably take it out and put it on this page, if you'd like

$h = "# Import library scripts.
source(\"" . $ROOT_DIR . "/R_Scripts/cle.r\")
# SNP set enrichment analysis.
job.msea <- list()
job.msea\$folder <- \"Results\"\n";
$m = file_get_contents($ROOT_DIR . "/Data/Pipeline/Resources/part2.txt");
//$t=file_get_contents("./Data/Pipeline/Resources/part3.txt");

debug_to_console("202");
//combine the variables into 1
$data = $out . "\n" . $file1 . "\n" . $file2 . "\n" . $file3 . "\n" . $file4 . "\n" . $file5 . "\n";

//create and write the R file

$fp = fopen($fpathOut, "w");
fwrite($fp, $h);
fwrite($fp, $par);
fwrite($fp, $data);
fwrite($fp, $m);


fclose($fp);

chmod($fpathOut, 0777); //change permissions to 777. I think 777 is too much. Probably could change it to 644, but check if it executes

debug_to_console("219");
/***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we have a txt file with the path information
 ***************************************/


$fsession = $ROOT_DIR . "/Data/Pipeline/Resources/session/$sessionID" . "_session.txt";

if (file_exists($fsession)) {
  debug_to_console("230");
  $session = explode("\n", file_get_contents($fsession));
  //Create different array elements based on new line
  $pipe_arr = preg_split("/[\t]/", $session[0]);
  $pipeline = $pipe_arr[1];

  if ($pipeline == "GWASskipped") //check if the user skipped MDF
  {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.25")) { //change from 1.25 --> 1.5
        return 'Mergeomics_Path:' . "\t" . "1.5" . "\n";
      }
      return $data;
    }
    $data = array_map('replace_a_line', $data);
    file_put_contents($fsession, implode('', $data));
  } else {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "2.25")) { //change from 2.25 --> 2.5
        return 'Mergeomics_Path:' . "\t" . "2.5" . "\n";
      }
      return $data;
    }
    $data = array_map('replace_a_line', $data);
    file_put_contents($fsession, implode('', $data));
  }
}

?>

<div class="toggle toggle-border" id="SSEARtToggle">
  <div class="togglet toggleta"><i class="icon-plus-square1" id="runtimeiconmsea"></i>
    <div class="capital">Click to see runtime log</div>
  </div>
  <div class="togglec" id="SSEArt" style="display:none;font-size: 16px;padding-top: 1%;">
    <div id="ssearuntime"></div>
  </div>
</div>
<script type="text/javascript">
  function kda2networkAjaxtest() {
    var
      $http,
      text,
      $self = arguments.callee;
    var string = "<?php echo $sessionID; ?>";
    if (window.XMLHttpRequest) {
      $http = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
      try {
        $http = new ActiveXObject('Msxml2.XMLHTTP');
      } catch (e) {
        $http = new ActiveXObject('Microsoft.XMLHTTP');
      }
    }

    if ($http) {
      $http.onreadystatechange = function() {
        if (/4|^complete$/.test($http.readyState)) {
          text = $http.responseText;
          //text = text.replace(/\s/g, '');
          if (text.indexOf("100%") == -1) {
            setTimeout(function() {
              $self();
            }, 50);
          }
          $('#ssearuntime').html(text);
        }
      };
      $http.open('GET', 'runtime.php' + '?sessionID=' + string + "&pipeline=msea&date=" + new Date().getTime(), true);
      $http.send(null);

    }

  }
  setTimeout(function() {
    kda2networkAjaxtest();
  }, 50);
</script>
<script type="text/javascript">
  var string = "<?php echo $sessionID; ?>";

  $("#SSEARtToggle").on('click', function(e) {
    var x = document.getElementById("SSEArt");
    if (x.style.display === "none") {
        x.style.display = "block";
        $("#runtimeiconmsea").removeClass("icon-plus-square1");
        $("#runtimeiconmsea").addClass("icon-minus-square");
    }
    else {
        x.style.display = "none";
        $("#runtimeiconmsea").removeClass("icon-minus-square");
        $("#runtimeiconmsea").addClass("icon-plus-square1");
    }

    e.preventDefault();
  });
</script>

<?php
/*
Email php block'

*/
debug_to_console("337");
$email_sent = $ROOT_DIR . "/Data/Pipeline/Results/ssea_email/$sessionID" . "sent_email";
$email = $ROOT_DIR . "/Data/Pipeline/Results/ssea_email/$sessionID" . "email";


//check if the email has been sent
if ((!(file_exists($email_sent)))) {
  //check if the email exists
  if (file_exists($email)) {
    require($ROOT_DIR . '/PHPMailer-master/class.phpmailer.php');


    debug_to_console("349");
    $mail = new PHPMailer();

    $mail->Body = 'Your Marker Set Enrichment Analysis job is running. We will send you a notification with a link to your results after completion.';
    $mail->Body .= "\n";
    $mail->Body .= 'If you close your browser, you can get your results from: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=';
    $mail->Body .= "$sessionID";
    $mail->Body .= ' when the pipeline is complete';

    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "smha118@g.ucla.edu";  // GMAIL username
    #$mail->Password   = "mergeomics729@";            // GMAIL password


    $mail->SetFrom('smha118@g.ucla.edu', 'Daniel Ha');

    $mail->Subject    = "MSEA Execution Started";

    $address = trim(file_get_contents($email));
    $mail->AddAddress($address);

    if (!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    } else {

      $myfile = fopen($email_sent, "w");
      fwrite($myfile, $address);
      fclose($myfile);
    }
  }
}
debug_to_console("383");
?>
<script type="text/javascript">
  //once the email has been sent, go to result page
  $('#mySSEA_review').load("/result_SSEA.php?sessionID=" + string + "&rmchoice=1&run=<?php print($run); ?>"); //go to the result SSEA w/ choice 1 (SSEA)
</script>