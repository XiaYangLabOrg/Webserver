<?php
function debug_to_console($data)
{
  $output = $data;
  if (is_array($output))
    $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['rmchoice'])) {
  $rmchoice = $_GET['rmchoice'];
}

if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}
debug_to_console($sessionID);
if (isset($_GET['run'])) {
  $run = $_GET['run'];
}

if (isset($_GET['network']) and isset($_GET['species'])) {
  $network = $_GET['network'];
  $species = $_GET['species'];
  if ($network == 1) {
    $network_str = $ROOT_DIR . "Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_network.txt";
    if ($species == 1) { // custom upload and human
      $file2 = "network_file <- \"" . $network_str . "\"" . "\n" .
        "species <- \"Human\"";
    } else { // custom upload and mouse
      $file2 = "network_file <- \"" . $network_str . "\"" . "\n" .
        "species <- \"Mouse\"";
    }
  } else {
    if ($network == 2) {
      $network_str = "tissue <- \"liver\"\n";
    } else if ($network == 3) {
      $network_str = "tissue <- \"kidney\"\n";
    } else {
      $network_str = "tissue <- \"combined\"\n";
    }

    if ($species == 1) {
      $file2 = $network_str . "species <- \"Human\"";
    } else {
      $file2 = $network_str . "species <- \"Mouse\"";
    }
  }
}

$fpath1 = "/home/www/abhatta3-webserver/Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_genes.txt";
//$fpath2 = $network; //change to user input


$fpathOut = "./Data/Pipeline/$sessionID" . "app2.R"; # change to pharmomics folder


//$file1 = json_encode(trim(file_get_contents($fpath1))); # 
//$file2 = json_encode($fpath2); # 

$file1 = "Genes <- read.delim(\"" . $fpath1 . "\", stringsAsFactors = FALSE,header=FALSE)
colnames(Genes) <- \"GENE\"
sessionID <- \"$sessionID" . "\"";
//$file2 = "network <- " . $file2;

$data = $file1 . "\n" . $file2 . "\n";
$analysis = file_get_contents("./Data/Pipeline/Resources/app2_analysis_meta");
//$output = "\nwrite.table(tableresult, " . '"' . $ROOT_DIR . "Data/Pipeline/Results/shinyapp2/$sessionID" . '.KDA2PHARM_app2result.txt", ' . "row.names=FALSE, quote = FALSE, sep =" . '"\t")';

$fp = fopen($fpathOut, "w");
fwrite($fp, $data);
fwrite($fp, $analysis);
//fwrite($fp, $output);
fclose($fp);
chmod($fpathOut, 0777);


$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
if (file_exists($fsession)) {
  function replace_a_line($data, $rmchoice)
  {
    if (strpos($data, 'Pharmomics_Path') !== false) {
      $pharmomics_arr = preg_split("/[\t]/", $data);
      $pharmomics_arr2 = explode("|", $pharmomics_arr[1]);
      $msea2pharmomics = $pharmomics_arr2[0];
      //$kda2pharmomics = preg_replace('/\s+/', ' ', trim($pharmomics_arr2[1]));
      debug_to_console("rmchoice:" . $rmchoice);
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
  function kda2networkAjax() {
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
          text = text.replace(/\s/g, '');
          if (text.indexOf("100%") == -1) {
            setTimeout(function() {
              $self();
            }, 50);

          }



          $('#kda2networkprogresswidth').width(text);
          $('#kda2networkprogresspercent').html(text);




        }
      };
      $http.open('GET', 'pharmomics_loadbar.php' + '?sessionID=' + string + "&date=" + new Date().getTime(), true);
      $http.send(null);

    }

  }
</script>

<script type="text/javascript">
  setTimeout(function() {
    kda2networkAjax();
  }, 50);
</script>

<!-- Description ===================================================== -->
<table class="table table-bordered" style="text-align: center;">
  <thead>
    <tr>
      <th>Network Based Drug Repositioning Job is running</th>
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

        <div id="kda2networkprogressbar" class="progress active">
          <div id="kda2networkprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            <span id="kda2networkprogresspercent"></span>
          </div>
        </div>

        <div style="text-align: center;">
          <h4 class="instructiontext">
            PharmOmics is running on data. You can wait to get the results in about 30 minutes to 2 hours. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=<?php print($sessionID); ?>
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

$email_sent = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "sent_email";
$email = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "email";



if ((!(file_exists($email_sent)))) {
  if (file_exists($email)) {
    require('./PHPMailer-master/class.phpmailer.php');



    $mail = new PHPMailer();

    $mail->Body = 'Your network based drug repositioning job is running. We will send you a notification with a link to your results after completion.';
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


    $mail->SetFrom('darneson@g.ucla.edu', 'Daniel Ha');

    $mail->Subject    = "Network Based Drug Repositioning Execution Started";

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
        $('#mypharmOmics_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
      </script>
    <?php
    } else if ($rmchoice == 2) {
    ?>
      <script type="text/javascript">
        $('#myKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
      </script>

    <?php
    } else if ($rmchoice == 3) {
    ?>
      <script type="text/javascript">
        $('#myMETAKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
      </script>
    <?php
    } else {
    ?>
      <script type="text/javascript">
        $('#myKDASTART2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
      </script>
    <?php
    }
  } else {

    if ($rmchoice == 1) {
    ?>
      <script type="text/javascript">
        $('#mypharmOmics_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
      </script>
    <?php
    } else if ($rmchoice == 2) {
    ?>
      <script type="text/javascript">
        $('#myKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
      </script>

    <?php
    } else if ($rmchoice == 3) {
    ?>
      <script type="text/javascript">
        $('#myMETAKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
      </script>
    <?php
    } else {
    ?>
      <script type="text/javascript">
        $('#myKDASTART2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
      </script>
    <?php
    }
  }
} else {
  if ($rmchoice == 1) {
    ?>
    <script type="text/javascript">
      $('#mypharmOmics_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
    </script>
  <?php
  } else if ($rmchoice == 2) {
  ?>
    <script type="text/javascript">
      $('#myKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
    </script>

  <?php
  } else if ($rmchoice == 3) {
  ?>
    <script type="text/javascript">
      $('#myMETAKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
    </script>
  <?php
  } else {
  ?>
    <script type="text/javascript">
      $('#myKDASTART2PHARM_review').load("/result_shinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&type=wkda&signature=1&run=<?php echo $run ?>");
    </script>
<?php
  }
}
