<?php
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}

if (isset($_GET['rmchoice']) ? $_GET['rmchoice'] : null) {
  $rmchoice = $_GET['rmchoice'];
}
if (isset($_GET['run'])) {
  $run = $_GET['run'];
  $outfile = $ROOT_DIR . "Data/Pipeline/Results/kda/" . $sessionID . ".wKDA_joblog.txt";
  #Delete log file from previous run
  if(file_exists($outfile)){
      unlink($outfile);
  }
}


$resultfile = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.results.txt";

/*
if (!file_exists($resultfile) && !(file_exists($ROOT_DIR . "Data/Pipeline/Results/kda/" . "$sessionID" . ".wKDA_kd_full_results.txt"))) {
  */
if(isset($_GET['run'])){
  if($run=='T'){
    $fpathOut = "./Data/Pipeline/$sessionID" . "analyzekda.R";


    $kdapath = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDA";

    $kdapath = trim(file_get_contents($kdapath));


    $fpathparam = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAPARAM";
    $kdaparam = file_get_contents($fpathparam);

    $dir = substr($kdaparam, 18, 28);
    $depth = substr($kdaparam, 0, 18);

    $pieces = explode("direction <- ", $kdaparam);
    $depth = $pieces[0];
    //echo "$pieces[0]";
    //echo "$pieces[1]";
    //echo "$kdaparam"."<br>";

    if (trim($pieces[1]) == "1") {
      $param = "$depth" . "direction <- 0" . "\n";
      //    echo "$param";
    }

    if (trim($pieces[1]) == "2") {
      $param = "$depth" . "direction <- 1" . "\n";
      //    echo "$param";
    }


    $fpath = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAMODULE";

    $fjson = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "param.json";
    $data = json_decode(file_get_contents($fjson),true)["data"][0];
    $NetConvert = $data["NetConvert"];
    $GSETConvert = $data["GSETConvert"];

    if (!(file_exists($fpath))) {
      //$fpath = "Results/$sessionID" . ".ssea2kda.modules.txt";
      $fpath = $data["geneset"];
    } else {
      $fpath = trim(file_get_contents($fpath));
    }






    // $end="system(\"/home/www/abhatta3-webserver/Data/Pipeline/Himmeli/himmeli_3.4.0/source/himmeli Results/$sessionID.kda2himmeli.config.txt\")";


    // $data="source(\"clenew.r\")\n"."plan <- list()\n"."plan\$label <- \"$sessionID\"\n"."plan\$folder <- \"Results\"\n"."plan\$modfile <- \"$fpath\"\n"."plan\$minsize <- 20\n"."plan\$mindegree <- \"automatic\"\n"."plan\$maxdegree <- \"automatic\"\n"."plan\$maxoverlap <- 0.33\n"."plan\$edgefactor <- 1\n"."plan\$seed <- 1\n";
    // $data="source(\"/home/www/abhatta3-webserver/R_Scripts/sourceDir.R\")\n"."sourceDir(\"/home/www/abhatta3-webserver/R_Scripts/Mergeomics/\" ,trace=FALSE)\n"."job.kda <- list()\n"."job.kda\$label <- \"$sessionID\"\n"."job.kda\$folder <- \"Results\"\n"."job.kda\$modfile <- \"$fpath\"\n"."job.kda\$edgefactor <- 0.0\n";

    $data = "source(\"" . $ROOT_DIR . "R_Scripts/cle.r\")\n"
      . "job.kda <- list()\n"
      . "job.kda\$label <- \"$sessionID\"\n"
      . "job.kda\$folder <- \"Results\"\n"
      . "job.kda\$modfile <- \"$fpath\"\n"
      // ."job.kda\$edgefactor <- 0.5\n"
      // ."job.kda\$maxoverlap <- 0.33\n"
      . "job.kda\$nperm <- 10000\n"
      . "job.kda\$minsize <- 1\n"
      . "job.kda\$mindegree <- \"automatic\"\n"
      . "job.kda\$maxdegree <- \"automatic\"\n"
      . "job.kda\$seed <- 1\n";


    $filenetwork = "job.kda\$netfile <- \"$kdapath\"\n";
    if($NetConvert!=="none"){
      $filenetwork .= "NetConvert <- \"$NetConvert\"\n";
    }
    if($GSETConvert!=="none"){
      $filenetwork .= "GSETConvert <- \"$GSETConvert\"\n";
    }

    $edgefactor = file_get_contents("./Data/Pipeline/Resources/kda_temp/$sessionID" . "edge");
    $maxoverlap = file_get_contents("./Data/Pipeline/Resources/kda_temp/$sessionID" . "overlap");


    $t = file_get_contents("./Data/Pipeline/Resources/part3.txt");


    $fp = fopen($fpathOut, "w");
    fwrite($fp, $data);
    fwrite($fp, $edgefactor);
    fwrite($fp, $maxoverlap);
    fwrite($fp, $param);
    fwrite($fp, $filenetwork);
    $desc_file = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "DESC";

    // JD added
    if ($rmchoice == 1) {
      $jsonfile = "./Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
    } else if ($rmchoice == 2) {
      $jsonfile = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "data.json";
    } else if ($rmchoice == 3) {
      $jsonfile = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "data.json";
    } else {
      //do nothing
    }
    if ($rmchoice == 1 || $rmchoice == 2 || $rmchoice == 3) {
      $datajson = json_decode(file_get_contents($jsonfile))->data;
      $inffile = $datajson[0]->genedesc;
      $inffile_path = "./Data/Pipeline/" . $inffile;
    } else {
      $inffile = "kdadesc";
    }


    if (file_exists($desc_file)) {
      if (strpos(trim(file_get_contents($desc_file)), "None") !== false) {
        //do nothing
      } else {
        $inffile = trim(file_get_contents($desc_file));
        $inffile_write = "job.kda\$inffile <- " . "\"$inffile\"";
        fwrite($fp, $inffile_write);
      }
    } else if (file_exists($inffile_path)) {
      $inffile_write = "job.kda\$inffile <- " . "\"$inffile\"";
      fwrite($fp, $inffile_write);
    } else {
      //do nothing
    }

    fwrite($fp, $t);
    


    // fwrite($fp, $end);
    fclose($fp);


    chmod($fpathOut, 0775);
  }
}


/***************************************
Session ID
Since we don't have a database, we have to update the txt file with the path information
 ***************************************/
$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";

if (file_exists($fsession)) {

  $session = explode("\n", file_get_contents($fsession));
  //Create different array elements based on new line
  $pipe_arr = preg_split("/[\t]/", $session[0]);
  $pipeline = $pipe_arr[1];

  if ($pipeline == "GWASskipped") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "2.25")) {
        return 'Mergeomics_Path:' . "\t" . "2.5" . "\n";
      }
      return $data;
    }
  } else if ($pipeline == "GWAS") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "3.25")) {
        return 'Mergeomics_Path:' . "\t" . "3.5" . "\n";
      }
      return $data;
    }
  } else if ($pipeline == "MSEA" || $pipeline == "META") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "2.25")) { //change from 3.25 --> 3.5
        return 'Mergeomics_Path:' . "\t" . "2.5" . "\n";
      }
      return $data;
    }
  } else if ($pipeline == "KDA") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.25")) { //change from 1.25 --> 1.5
        return 'Mergeomics_Path:' . "\t" . "1.5" . "\n";
      }
      return $data;
    }
  }

  $data = array_map('replace_a_line', $data);
  file_put_contents($fsession, implode('', $data));
}



?>

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
          
          timeOutVar=null;
          if (!text.includes("WKDA COMPLETE")) {
            timeOutVar=setTimeout(function() {
                        $self();
                      }, 10000);
          }else{
            clearTimeout(timeOutVar);
            $('#mywKDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=<?php echo $rmchoice ?>")
          }
          $('#kdaruntime').html(text);
        }
      };
      $http.open('GET', 'runtime.php' + '?sessionID=' + string + "&pipeline=kda&date=" + new Date().getTime(), true);
      $http.send(null);

    }

  }
</script>

<script type="text/javascript">
  kda2networkAjaxtest();
</script>


<!-- Description ===================================================== -->
<table class="table table-bordered" style="text-align: center" ;>
  <thead>
    <tr>
      <th>Weighted Key Driver Analysis Job is running</th>
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

        <div style="text-align: center;">
          <h4 class="instructiontext">
            wKDA is running on data. You can wait to get the results in about 30 minutes. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=<?php print($sessionID); ?>
          </h4>
        </div>
      </td>

    </tr>

  </tbody>
</table>

<div class="toggle toggle-border" id="KDARtToggle">
  <div class="togglet toggleta"><i class="icon-plus-square1" id="runtimeiconkda"></i>
    <div class="capital">Click to see runtime log</div>
  </div>
  <div class="togglec" id="KDArt" style="display:none;font-size: 16px;padding-top: 1%;">
    <div id="kdaruntime"></div>
  </div>
</div>

<script>
  var string = "<?php echo $sessionID; ?>";

  $("#KDARtToggle").on('click', function(e) {
    var x = document.getElementById("KDArt");
    if (x.style.display === "none") {
        x.style.display = "block";
        $("#runtimeiconkda").removeClass("icon-plus-square1");
        $("#runtimeiconkda").addClass("icon-minus-square");
    }
    else {
        x.style.display = "none";
        $("#runtimeiconkda").removeClass("icon-minus-square");
        $("#runtimeiconkda").addClass("icon-plus-square1");
    }

    e.preventDefault();
  });
</script>

<?php

$email_sent = "./Data/Pipeline/Results/kda_email/$sessionID" . "sent_email";
$email = "./Data/Pipeline/Results/kda_email/$sessionID" . "email";



if ((!(file_exists($email_sent)))) {
  if (file_exists($email)) {
    require('./PHPMailer-master/class.phpmailer.php');



    $mail = new PHPMailer();

    $mail->Body = 'Your Weighted Key Driver Analysis job is running. We will send you a notification with a link to your results after completion.';
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

    $mail->Subject    = "wKDA Execution Started";

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
      <script>
        $('#mywKDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=1&run= <?php echo $run ?>");
      </script>

    <?php
    } else if ($rmchoice == 2) {
    ?>

      <script>
        $('#myMSEA2KDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=2&run=<?php echo $run ?>");
      </script>

    <?php
    } else if ($rmchoice == 3) {
    ?>

      <script>
        $('#myMETA2KDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=3&run= <?php echo $run ?>");
      </script>

    <?php
    } else {
    ?>
      <script>
        $('#myKDASTART_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=4&run=<?php echo $run ?>");
      </script>

    <?php
    }
  } else {

    if ($rmchoice == 1) {
    ?>

      <script>
        $('#mywKDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=1&run=<?php echo $run ?>");
      </script>


    <?php
    } else if ($rmchoice == 2) {
    ?>

      <script>
        $('#myMSEA2KDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=2&run=<?php echo $run ?>");
      </script>

    <?php
    } else if ($rmchoice == 3) {
    ?>

      <script>
        $('#myMETA2KDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=3&run=<?php echo $run ?>");
      </script>

    <?php
    } else {
    ?>
      <script>
        $('#myKDASTART_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=4&run=<?php echo $run ?>");
      </script>

    <?php
    }
  }
} else {

  if ($rmchoice == 1) {
    ?>

    <script>
      $('#mywKDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=1&run=<?php echo $run ?>");
    </script>


  <?php
  } else if ($rmchoice == 2) {
  ?>

    <script>
      $('#myMSEA2KDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=2&run=<?php echo $run ?>");
    </script>

  <?php
  } else if ($rmchoice == 3) {
  ?>

    <script>
      $('#myMETA2KDA_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=3&run=<?php echo $run ?>");
    </script>

  <?php
  } else {
  ?>
    <script>
      $('#myKDASTART_review').load("/result_wKDA.php?sessionID=" + string + "&rmchoice=4&run=<?php echo $run ?>");
    </script>

<?php
  }
}
?>