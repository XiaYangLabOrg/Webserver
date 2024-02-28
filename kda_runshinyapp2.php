<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['rmchoice'])) {
  $rmchoice = $_GET['rmchoice'];
}

if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}

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

$fpath1 = $ROOT_DIR . "Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_genes.txt";
//$fpath2 = $network; //change to user input


$fpathOut = $ROOT_DIR ."Data/Pipeline/$sessionID" . "app2.R"; # change to pharmomics folder


//$file1 = json_encode(trim(file_get_contents($fpath1))); # 
//$file2 = json_encode($fpath2); # 

$file1 = "Genes <- read.delim(\"" . $fpath1 . "\", stringsAsFactors = FALSE,header=FALSE)
colnames(Genes) <- \"GENE\"
sessionID <- \"$sessionID" . "\"";
//$file2 = "network <- " . $file2;

$data = $file1 . "\n" . $file2 . "\n";
$analysis = file_get_contents($ROOT_DIR ."Data/Pipeline/Resources/app2_analysis_meta");
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
  var sessionID = "<?php echo $sessionID;?>";
  var rmchoice = "<?php echo $rmchoice;?>";
  
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
          if (!text.includes("100%")) {
            timeOutVar=setTimeout(function() {
                        $self();
                        }, 10000);
          }else{
            clearTimeout(timeOutVar);
            if(rmchoice==1){
              $('#mypharmOmics_review').load("/result_shinyapp2.php?sessionID=" + sessionID + "&type=wkda&signature=1");
            }else if(rmchoice==2){
              $('#myKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + sessionID + "&type=wkda&signature=1");
            }else if(rmchoice==3){
              $('#myMETAKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + sessionID + "&type=wkda&signature=1");
            }else{
              $('#myKDASTART2PHARM_review').load("/result_shinyapp2.php?sessionID=" + sessionID + "&type=wkda&signature=1");
            }
          }
          $('#kda2networkprogresswidth').width(text);
          $('#kda2networkprogresspercent').html(text);
        }
      };
      $http.open('GET', 'pharmomics_loadbar.php' + '?sessionID=' + sessionID + "&date=" + new Date().getTime(), true);
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
    $recipient = trim(file_get_contents($email));
    $title = "Network Based Drug Repositioning Execution Started";
    $body  = "Your Network Based Drug Repositioning job is running. We will send you a notification with a link to your results after completion.\n";
    $body .= "If you close your browser, you can get your results from: http://".$_SERVER["HTTP_HOST"]."/runpharmomics.php?sessionID=";
    $body .= "$sessionID";
    $body .= " when the pipeline is complete";
    sendEmail($recipient,$title,$body,$email_sent);  
  }
}  


?>
<script type="text/javascript">
  var sessionID = "<?php echo $sessionID;?>";
  var rmchoice = "<?php echo $rmchoice;?>";
  var run ="<?php echo $run;?>";
  if(rmchoice==1){
    $('#mypharmOmics_review').load("/result_shinyapp2.php?sessionID=" + sessionID + "&type=wkda&signature=1&run="+run);
  }else if(rmchoice==2){
    $('#myKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + sessionID + "&type=wkda&signature=1&run="+run);
  }else if(rmchoice==3){
    $('#myMETAKDA2PHARM_review').load("/result_shinyapp2.php?sessionID=" + sessionID + "&type=wkda&signature=1&run="+run);
  }else{
    $('#myKDASTART2PHARM_review').load("/result_shinyapp2.php?sessionID=" + sessionID + "&type=wkda&signature=1&run="+run);
  }
</script>