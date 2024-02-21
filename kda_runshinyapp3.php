<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}

if (isset($_GET['rmchoice'])) {
  $rmchoice = $_GET['rmchoice'];
}

if (isset($_GET['run'])) {
  $run = $_GET['run'];
}

$start = 'cat("0%' . '\n")';
//$start1 = 'cat("10%'.'\n")';
$start1 = 'cat("2%' . '\n")';
//$start2 = 'cat("20%'.'\n")';
//$start3 = 'cat("30%'.'\n")';
$start4 = 'cat("80%' . '\n")';
$start5 = 'cat("100%' . '\n")';

//$fpath1="./Data/Pipeline/Resources/shinyapp3_temp/$sessionID".".KDA2PHARM_genes.txt"; 
$fpath1 = $ROOT_DIR . "Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_genes.txt";

//$fpath2="./Data/Pipeline/Resources/shinyapp3_temp/$sessionID".".KDA2PHARM_down_genes.txt"; 

$fpathOut = $ROOT_DIR ."Data/Pipeline/$sessionID" . "app3.R"; # change to pharmomics folder

//$file1 = json_encode(trim(file_get_contents($fpath1))); # hopefully this just outputs one string?
$file2 = '""';

//$file1 = str_replace("\/","/",$file1);

//$file1 = "Genes_up <- unlist(strsplit(".$file1.",".'"\n|\t|,| "'."))";
$file1 = "Genes_up <- read.delim(\"" . $fpath1 . "\", stringsAsFactors = FALSE, header=FALSE)\n
Genes_up <- unique(Genes_up\$V1)";
$file2 = "Genes_down <- unlist(strsplit(" . $file2 . "," . '"\n|\t|,| "' . "))";


$data = $file1 . "\n" . $file2 . "\n";

//$analysis=file_get_contents("./R_Scripts/app3_analysis");
$analysis = file_get_contents($ROOT_DIR ."Data/Pipeline/Resources/app3_analysis");

#write.table(result, "app3result.txt", row.names=FALSE, quote = FALSE, sep = "\t")

$output = "\nwrite.table(result, " . '"' . $ROOT_DIR ."Data/Pipeline/Results/shinyapp3/$sessionID" . '.KDA2PHARM_app3result.txt", ' . "row.names=FALSE, quote = FALSE, sep =" . '"\t")';


$fp = fopen($fpathOut, "w");
//fwrite($fp, "\n".$start."\n");
fwrite($fp, "\n" . $start1 . "\n");
//fwrite($fp, "\n".$start2."\n");
fwrite($fp, $data);
//fwrite($fp, "\n".$start3."\n");
fwrite($fp, $analysis);
//fwrite($fp, "\n".$start4."\n");
fwrite($fp, $output);
//fwrite($fp, "\n".$start5."\n");
fclose($fp);
chmod($fpathOut, 0777);


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

$fsession = $ROOT_DIR ."Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
if (file_exists($fsession)) {
  function replace_a_line($data, $rmchoice)
  {
    if (strpos($data, 'Pharmomics_Path') !== false) {
      $pharmomics_arr = preg_split("/[\t]/", $data);
      $pharmomics_arr2 = explode("|", $pharmomics_arr[1]);
      $msea2pharmomics = $pharmomics_arr2[0];
      $kda2pharmomics = preg_replace('/\s+/', ' ', trim($pharmomics_arr2[1]));
      if ($rmchoice == 1) {
        return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|SSEAKDAtoPharmomics,1.5" . "\n";
      } else if ($rmchoice == 2) {
        return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|MSEAKDAtoPharmomics,1.5" . "\n";
      } else if ($rmchoice == 3) {
        return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|METAKDAtoPharmomics,1.5" . "\n";
      } else {
        return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|KDAtoPharmomics,1.5" . "\n";
      }
    }
    return $data;
  }
  $handle = fopen($fsession, "r");
  $new_session_contetns = "";
  if ($handle) {
    while (($line = fgets($handle)) !== false) {
      $new_session_contetns .= replace_a_line($line, $rmchoice);
    }
    fclose($handle);
  }
  if (!empty($new_session_contetns)) {
    file_put_contents($fsession, $new_session_contetns);
  }
}


?>

<script type="text/javascript">
  function kda2jaccardAjax() {
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
            }, 50);

          }



          $('#kda2jaccardprogresswidth').width(text);
          $('#kda2jaccardprogresspercent').html(text);




        }
      };
      $http.open('GET', 'pharmomics_loadbar.php' + '?sessionID=' + string + "&date=" + new Date().getTime(), true);
      $http.send(null);

    }

  }
</script>

<script type="text/javascript">
  setTimeout(function() {
    kda2jaccardAjax();
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

        <div id="kda2jaccardprogressbar" class="progress active">
          <div id="kda2jaccardprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            <span id="kda2jaccardprogresspercent"></span>
          </div>
        </div>

        <div style="text-align: center;">
          <h4 class="instructiontext">
            PharmOmics is running on data. You can wait to get the results in about 5 minutes. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=<?php print($sessionID); ?>
          </h4>
        </div>
      </td>

    </tr>

  </tbody>
</table>


<script type="text/javascript">
  var string = "<?php echo $sessionID ?>";
  //var run = "<?php //echo $run 
                ?>";
</script>

<?php

$email_sent = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "sent_email";
$email = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "email";



if ((!(file_exists($email_sent)))) {
  if (file_exists($email)) {
    require('./PHPMailer-master/class.phpmailer.php');



    $mail = new PHPMailer();

    $mail->Body = 'Your overlap based drug repositioning job is running. We will send you a notification with a link to your results after completion.';
    $mail->Body .= "\n";
    $mail->Body .= 'If you close your browser, you can get your results from: http://mergeomics.research.idre.ucla.edu/resultshinyapp3.php?My_key=';
    $mail->Body .= "$sessionID";
    $mail->Body .= ' when the pipeline is complete';

    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "darneson@g.ucla.edu";  // GMAIL username
    $mail->Password   = "friday180";            // GMAIL password


    $mail->SetFrom('darneson@g.ucla.edu', 'Doug Arneson');

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
    if ($rmchoice == 1) {
?>
      <script type="text/javascript">
        $('#mypharmOmics_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
      </script>
    <?php
    } else if ($rmchoice == 2) {
    ?>
      <script type="text/javascript">
        $('#myKDA2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
      </script>

    <?php
    } else if ($rmchoice == 3) {
    ?>
      <script type="text/javascript">
        $('#myMETAKDA2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
      </script>
    <?php
    } else {
    ?>
      <script type="text/javascript">
        $('#myKDASTART2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
      </script>
    <?php
    }
  } else {

    if ($rmchoice == 1) {
    ?>
      <script type="text/javascript">
        $('#mypharmOmics_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
      </script>
    <?php
    } else if ($rmchoice == 2) {
    ?>
      <script type="text/javascript">
        $('#myKDA2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
      </script>

    <?php
    } else if ($rmchoice == 3) {
    ?>
      <script type="text/javascript">
        $('#myMETAKDA2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
      </script>
    <?php
    } else {
    ?>
      <script type="text/javascript">
        $('#myKDASTART2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
      </script>
    <?php
    }
  }
} else {
  if ($rmchoice == 1) {
    ?>
    <script type="text/javascript">
      $('#mypharmOmics_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
    </script>
  <?php
  } else if ($rmchoice == 2) {
  ?>
    <script type="text/javascript">
      $('#myKDA2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
    </script>

  <?php
  } else if ($rmchoice == 3) {
  ?>
    <script type="text/javascript">
      $('#myMETAKDA2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
    </script>
  <?php
  } else {
  ?>
    <script type="text/javascript">
      $('#myKDASTART2PHARM_review').load("/result_shinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&run=<?php echo $run ?>");
    </script>
<?php
  }
}
