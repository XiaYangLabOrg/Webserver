<?php
function debug_to_console($data)
{
  $output = $data;
  if (is_array($output))
    $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
if (isset($_POST['sessionID'])) {
  $sessionID = $_POST['sessionID'];
} else if (isset($_GET['sessionID'])) {
  $sessionID = $_GET["sessionID"];
}

if (isset($_POST['upregulatedgenes'])) {
  $upregulated = $_POST['upregulatedgenes'];
  //$upregulated = str_replace("\r\n",'\n', $upregulated);
}

if (isset($_POST['downregulatedgenes'])) {
  $downregulated = $_POST['downregulatedgenes'];
  //$downregulated = str_replace("\r\n",'\n', $downregulated);
}
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";


$resultfile="./Data/Pipeline/Results/shinyapp3/$sessionID"."_app3result.txt";  
$frunning_status = "./Data/Pipeline/$sessionID" . "app3_is_running"; # change to pharmomics folder

if (!file_exists($frunning_status) & !file_exists($resultfile)) {

  $start = 'cat("0%' . '\n")';
  //$start1 = 'cat("10%' . '\n")';
  $start1 = 'cat("2%' . '\n")';
  $start2 = 'cat("20%' . '\n")';
  $start3 = 'cat("30%' . '\n")';
  $start4 = 'cat("80%' . '\n")';
  $start5 = 'cat("100%' . '\n")';


  //$fpath2="./Data/Pipeline/Resources/shinyapp3_temp/$sessionID".".KDA2PHARM_down_genes.txt"; 

  $fpathOut = $ROOT_DIR . "Data/Pipeline/$sessionID" . "app3.R"; # change to pharmomics folder
  debug_to_console($fpathOut);
  $array_up = explode("\n", $upregulated);
  $array_down = explode("\n", $downregulated);

  $final = "GENE" . "\r\n";

  foreach ($array_up as $line) {
    $final .= "\n" . "$line";
  }

  $filename = $ROOT_DIR . "Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . "_up_genes.txt";
  debug_to_console($filename);
  $f = fopen($filename, 'w');
  fwrite($f, $final);
  fclose($f);

  $final = "GENE" . "\r\n";


  foreach ($array_down as $line) {
    $final .=  "\n" . "$line";
  }
  // Write the whole thing to the file
  $filename = $ROOT_DIR . "Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . "_down_genes.txt";
  $f = fopen($filename, 'w');
  fwrite($f, $final);
  fclose($f);

  //$file1 = "Genes_up <- unlist(strsplit(".'"'.$upregulated.'"'.",".'"\n|\t|,| "'."))";
  //$file2 = "Genes_down <- unlist(strsplit(".'"'.$downregulated.'"'.",".'"\n|\t|,| "'."))";

  $file1 = "Genes_up <- read.delim(\"" . $ROOT_DIR . "Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . "_up_genes.txt\", stringsAsFactors = FALSE)\n
Genes_up <- Genes_up\$GENE";
  $file2 = "Genes_down <- read.delim(\"" . $ROOT_DIR . "Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . "_down_genes.txt\", stringsAsFactors = FALSE)\n
Genes_down <- Genes_down\$GENE\n
sessionID <- \"$sessionID" . "\"";

  $data = $file1 . "\n" . $file2 . "\n";

  $analysis =file_get_contents($ROOT_DIR . "Data/Pipeline/Resources/app3_analysis");

  $fp = fopen($fpathOut, "w");
  fwrite($fp, "\n" . $start1 . "\n");
  fwrite($fp, $data);
  fwrite($fp, $analysis);
  fclose($fp);
  chmod($fpathOut, 0777);
}


/* echo "<pre>";
if( ($fp = popen("sh run_app3.sh $sessionID | tee -a ./Data/Pipeline/Results/shinyapp3/out.txt", 'r')) ) {
    while( !feof($fp) ){
        echo fread($fp, 1024);
        ob_flush();
        flush(); // you have to flush buffer
    }
    fclose($fp);
}
echo "</pre>"; */

?>


<script type="text/javascript">
  function app3Ajax() {
    var
      $http,
      text,
      $self = arguments.callee;

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
          text = text.replace(/\s/g, '');
          if (text.indexOf("100%") == -1) {
            setTimeout(function() {
              $self();
            }, 1500);

          }
          if (text == "100%") {
            setTimeout(function() {
              var string = "<?php echo $sessionID; ?>";
              $('#myAPP3_run').load("/result_shinyapp3.php?sessionID=" + string + "&type=pharm");
            }, 20000)

          }


          $('#app3progresswidth').width(text);
          $('#app3progresspercent').html(text);




        }
      };
      $http.open('GET', 'pharmomics_loadbar.php' + '?sessionID=' + string + "&date=" + new Date().getTime(), true);
      $http.send(null);

    }

  }
</script>

<script type="text/javascript">
  setTimeout(function() {
    app3Ajax();
  }, 50);
</script>


<!-- Description ===================================================== -->
<table class="table table-bordered" style="text-align: center" ;>
  <thead>
    <tr>
      <th>Overlap Based Drug Repositioning Job is running</th>
    </tr>
  </thead>
  <tbody>


    <tr>
      <td>
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

        <div id="app3progressbar" class="progress active">
          <div id="app3progresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            <span id="app3progresspercent"></span>
          </div>
        </div>

        <div style="text-align: center;">
          <h4 class="instructiontext">
            PharmOmics is running on data. You can wait to get the results in about 5 minutes. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runpharmomics.php?sessionID=<?php print($sessionID); ?>
          </h4>
        </div>
      </td>

    </tr>

  </tbody>
</table>


<script type="text/javascript">
  var string = "<?php echo $sessionID ?>";
</script>

<?php

$email_sent = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "sent_email";
$email = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "email";



if ((!(file_exists($email_sent)))) {
  if (file_exists($email)) {
    require('./PHPMailer-master/class.phpmailer.php');



    $mail = new PHPMailer();

    $mail->Body = 'Your Overlap Based Drug Repositioning job is running. We will send you a notification with a link to your results after completion.';
    $mail->Body .= "\n";
    $mail->Body .= 'If you close your browser, you can get your results from: http://mergeomics.research.idre.ucla.edu/runpharmomics.php?sessionID=';
    $mail->Body .= "$sessionID";
    $mail->Body .= ' when the pipeline is complete';

    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "smha118@g.ucla.edu";  // GMAIL username
    $mail->Password   = "mergeomics729@";            // GMAIL password


    $mail->SetFrom('smha118@g.ucla.edu', 'Daniel Ha');

    $mail->Subject    = "Overlap Based Drug Repositioning Execution Started";

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

?>




<script type="text/javascript">
  <?php
  if (!file_exists($frunning_status)) {
  ?>
    var string = "<?php echo $sessionID; ?>";
    $('#myAPP3_run').load("/result_shinyapp3.php?sessionID=" + string + "&type=pharm");
  <?php
  }
  ?>
</script>