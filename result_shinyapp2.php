<?php
include "functions.php";

$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";

if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
  $fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
  if (isset($_GET['signature'])) { // coming from run_app2
    $signature = $_GET['signature'];
    if (isset($_GET['type']) ? $_GET['type'] : null) {
      //$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
      $type = $_GET['type'];
      if ($type == 'ssea' || $type == 'msea') {
        $genefile = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_genes.txt";
        $overview = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_overview.txt";
      }
      if ($type == 'wkda') {
        $genefile = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_genes.txt";
        $overview = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_overview.txt";
      }
      if (file_exists($fsession)) {
        $data = file($fsession); // reads an array of lines
        function replace_a_line($data)
        {
          if (stristr($data, 'Pharmomics_Path:' . "\t" . "4.25")) {
            return 'Pharmomics_Path:' . "\t" . "4.5" . "\n";
          }
          if (stristr($data, 'Pharmomics_Path:' . "\t" . "1.0")) {
            return 'Pharmomics_Path:' . "\t" . "1.25" . "\n";
          }
          return $data;
        }
        $data = array_map('replace_a_line', $data);
        if (strpos($data[2], 'signature') !== false) {
          //do nothing
        } else {
          array_push($data, 'signature:' . "\t$signature" . "\n", 'type:' . "\t$type" . "\n");
        }
        file_put_contents($fsession, implode('', $data));

        $session_write = NULL;
      }
    }
  } else if (file_exists($fsession)) { // signature not set, coming from session reload
    $session = explode("\n", file_get_contents($fsession));
    //Create different array elements based on new line
    $sig_arr = preg_split("/[\t]/", $session[2]);
    $signature = $sig_arr[1];
    $type_arr = preg_split("/[\t]/", $session[3]);
    $type = $type_arr[1];
    if ($type == 'ssea' || $type == 'msea') {
      $genefile = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_genes.txt";
      $overview = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_overview.txt";
    }
    if ($type == 'wkda') {
      $genefile = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_genes.txt";
      $overview = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_overview.txt";
    }
  } else {
    $signature = 1;
  }
}

$resultfile = "./Data/Pipeline/Results/shinyapp2/$sessionID" . "_app2result.txt";
$resultfilehepatotox = "./Data/Pipeline/Results/shinyapp2/$sessionID" . "_app2result_hepatotox.txt";

if (isset($_GET['run'])) {
  $run = $_GET['run'];
}


function scientificNotation($val)
{
  $exp = floor(log($val, 10));
  if ($val == 0) {
    return 0;
  } else {
    return sprintf('%.3fE%+03d', $val / pow(10, $exp), $exp);
  }
}
$frunning_status = "./Data/Pipeline/$sessionID" . "app2_is_running";
if ($signature == 1) { //meta 
  if (isset($_GET['run'])) {
    if ($run == 'T' && !file_exists($frunning_status)) {
      $outfile = $ROOT_DIR . "Data/Pipeline/Results/shinyapp2/" . $sessionID . "out.txt";
      shell_exec("touch " . $frunning_status);
      debug_to_console($ROOT_DIR . "R-3.4.4/bin/Rscript " . $ROOT_DIR . "Data/Pipeline/" . $sessionID . "app2.R | tee " . $outfile);
      shell_exec("Rscript " . $ROOT_DIR . "Data/Pipeline/" . $sessionID . "app2.R | tee " . $outfile);
      #shell_exec("sh run_app2.sh $sessionID | tee " . $outfile);
      sleep(1);
    }
  } else if ($type == 'pharm') {
    if (!file_exists($resultfile) && !file_exists($frunning_status)) {
      $outfile = $ROOT_DIR . "Data/Pipeline/Results/shinyapp2/" . $sessionID . "out.txt";
      shell_exec("touch " . $frunning_status);
      shell_exec("Rscript " . $ROOT_DIR . "Data/Pipeline/" . $sessionID . "app2.R | tee " . $outfile);
      #shell_exec("sh run_app2.sh $sessionID | tee " . $outfile);
      sleep(1);
    }
  } else {
    //do nothing
  }

  if (file_exists($outfile)) {
    unlink($frunning_status);
    unlink($outfile);
    if ($type == 'pharm') {
      $resultfile = "./Data/Pipeline/Results/shinyapp2/$sessionID" . "_app2result.txt";
    }
  }
  chmod($resultfile, 0777);

  if (file_exists($resultfile)) {
    $results_sent = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "sent_results";
    $email = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "email";
    if ((!(file_exists($results_sent)))) {
      if (file_exists($email)) {
        $recipient = trim(file_get_contents($email));
        $title = "Network Based Drug Repositioning Execution Complete!";
        $body  = "Congratulations! You have successfully executed our pipeline. Please download your results.\n";
        $body .= "Your results are available at: http://".$_SERVER["HTTP_HOST"]."/runpharmomics.php?sessionID=";
        $body .= "$sessionID";
        $body .= "\n";
        $body .= "Note: Your results will be deleted from the server after 24 hours";
        sendEmail($recipient,$title,$body,$email_sent);
      }
    }
  }
} else {
  include('config.php');
  //include('mysql_conn.php');
  $login_button = '';
  $scriptUri = "http://" . $_SERVER["HTTP_HOST"] . "/runpharmomics.php?fromapp2=true";
  $google_client->setRedirectUri($scriptUri);

  //This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
  if (isset($_GET["code"])) {
    //It will Attempt to exchange a code for an valid authentication token.
    #    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
    $google_client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $google_client->getAccessToken();

    //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
    if (!isset($token['error'])) {
      //Set the access token used for requests
      $google_client->setAccessToken($token['access_token']);

      //Store "access_token" value in $_SESSION variable for future use.
      $_SESSION['access_token'] = $token['access_token'];

      //Create Object of Google Service OAuth 2 class
      $google_service = new Google_Service_Oauth2($google_client);

      //Get user profile data from google
      $data = $google_service->userinfo->get();
      //debug_to_console($data);
      //Below you can find Get profile data and store into $_SESSION variable
      if (!empty($data['given_name'])) {
        $_SESSION['user_first_name'] = $data['given_name'];
      }

      if (!empty($data['family_name'])) {
        $_SESSION['user_last_name'] = $data['family_name'];
      }

      if (!empty($data['email'])) {
        $_SESSION['user_email_address'] = $data['email'];
      }

      if (!empty($data['gender'])) {
        $_SESSION['user_gender'] = $data['gender'];
      }

      if (!empty($data['picture'])) {
        $_SESSION['user_image'] = $data['picture'];
      }
    }
  }
  #$user_name = $_SESSION['user_first_name'] . " " . $_SESSION['user_last_name'];
  #$user_email = $_SESSION['user_email_address'];
  // run commands on hoffman2
  if (!file_exists($resultfile) && !file_exists($frunning_status)) {
    // $cmds1 = "sshpass -p \"mergeomics729@\" ssh smha118@192.154.2.201 ";
    // $cmds2 = "'source /etc/profile;module load R;cd /u/scratch/s/smha118/app2seg;qsub -cwd -V -m bea -l h_data=4G,h_rt=12:00:00,highp run_pharm_dose_seg.sh " . $sessionID . "'";
    $cmds1 = "sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" ssh ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"]." ";
    $cmds2 = "'source /etc/profile; module load R/4.2.2; cd /u/scratch/m/mergeome/app2seg; qsub -cwd -V -m bea -l h_data=4G,h_rt=12:00:00,highp /u/home/m/mergeome/PharmOmics_resource/run_pharm_dose_seg.sh " . $sessionID . "'";
    shell_exec("touch " . $frunning_status);
    shell_exec($cmds1 . $cmds2);
#    $user_usage_count = $ROOT_DIR . "/User_usage/" . $user_email . "." . date("Y.m.d") . ".json";
    // $json = array();
    // $json['session'] = $sessionID;
    // $json['user_name'] = $user_name;
    // $json['user_email'] = $user_email;
    // $json['submit_time'] = date("Y-m-d h:i:sa");
    // $json['cmds'] = $cmds2;
    // if (!file_exists($user_usage_count)) {
    //   $jsondata[] = $json;
    // } else {
    //   $jsondata = json_decode(file_get_contents($user_usage_count));
    //   array_push($jsondata, $json);
    // }
    // $fp = fopen($user_usage_count, 'w');
    // fwrite($fp, json_encode($jsondata));
    // fclose($fp);
    // $cmds = "qsub -cwd -V -m bea -l h_data=4G,h_rt=12:00:00 run_pharm_dose_seg.sh " . $sessionID;
    // $sql = 'INSERT INTO hoffman2_logs (sessionID, user_email, user_name, cmds) 
    //     VALUES ("' . $sessionID . '", "' . $user_email . '", "' . $user_name . '","' . $cmds . '"); ';
    // mysqli_query($conn, $sql);
    // mysqli_close($conn);
  }
  //set_time_limit(0);

  //echo "Script began: " . date("d-m-Y h:i:s") . "<br>";
  if (file_exists("./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_is_done")) {
    //echo "The file was found: " . date("d-m-Y h:i:s") . "<br>";
    unlink($frunning_status);
    $results_sent = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "sent_results";
    $email = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "email";
    if ((!(file_exists($results_sent)))) {
      if (file_exists($email)) {
        $recipient = trim(file_get_contents($email));
        $title = "Network Based Drug Repositioning Execution Complete!";
        $body  = "Congratulations! You have successfully executed our pipeline. Please download your results.\n";
        $body .= "Your results are available at: http://".$_SERVER["HTTP_HOST"]."/runpharmomics.php?sessionID=";
        $body .= "$sessionID";
        $body .= "\n";
        $body .= "Note: Your results will be deleted from the server after 24 hours";
        sendEmail($recipient,$title,$body,$email_sent);
      }
    }

    //break;
  } else {
    echo "Not ready!!";
  }



  // do {
  //   //if (file_exists($resultfile)) {
  //   if (file_exists("./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_is_done")) {
  //     echo "The file was found: " . date("d-m-Y h:i:s") . "<br>";
  //     break;
  //   }
  // } while (true);
}

?>

<table class="table table-bordered review" style="text-align: center" ; id="shinyapp2resultstable">
  <thead>
    <tr>
      <th colspan="2">
        Download Output Files
      </th>
    </tr>
    <tr>
      <td>
        Network Based Drug Repositioning Analysis File
      </td>
      <td>
        <a href=<?php print($resultfile); ?> download> Download</a>
      </td>
    </tr>
    <tr>
      <td>
        Hepatotoxicity ADR Scoring
      </td>
      <td>
        <a href=<?php print($resultfilehepatotox); ?> download> Download</a>
      </td>
    </tr>
    <?php
    if ($type == 'ssea' || $type == 'msea' || $type == 'wkda') { ?>
      <tr>
        <td>
          Genes used for repositioning
        </td>
        <td>
          <a href=<?php print($genefile); ?> download> Download</a>
        </td>
      </tr>
      <tr>
        <td>
          Parameters overview
        </td>
        <td>
          <a href=<?php print($overview); ?> download> Download</a>
        </td>
      </tr>
    <?php }
    ?>

  </thead>
</table>

<?php
if ($signature == 1 && file_exists($resultfile) && $type == 'wkda') { ?>
  <!--Meta table version----------->
  <div class="table-responsive">
    <table id="shinyapp2_resultskda" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>
            Drug
          </th>
          <th>
            Species
          </th>
          <th>
            Tissue
          </th>
          <th>
            Z-Score
          </th>
          <th>
            Rank
          </th>
          <th>
            P-value
          </th>
          <th>
            Visualization Link
          </th>
          <th>
            Network Cytoscape files
          </th>
        </tr>
      </thead>
      <?php
      $count = 1;
      $result = file($resultfile);
      foreach ($result as $line) {
        $line_array = explode("\t", $line);
        $drugname = trim($line_array[0]);
        $species = trim($line_array[1]);
        $tissue = trim($line_array[2]);
        $zscore = number_format($line_array[5], 3, ".", "");
        $rank = number_format($line_array[6], 3, ".", "");
        $pvalue = scientificNotation(trim((float)$line_array[7]));
        $drug = trim($line_array[11]);

        if (file_exists("./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_drug_networks/$drug" . "_cytoscape_edges.txt")) {
          $link = '<span style="text-align: center;margin:0px;">
                <form style="margin-bottom:0;" action="/cyto_visualize/write_cytoscape_app2.php" name="figapp2" target="_blank">
                  <input type="hidden" name="sessionID" value="' . $sessionID . '">
                  <input type="hidden" name="drugres" value="' . $drug . '">
                  <input type="submit" class="button button-3d" style="padding:0% 5%; font-size 18px;background-color: #DC461D;margin:0 20px 0 0;" value="Display Network" />
                </form>
              </span>';
          $download = '<a href="./Data/Pipeline/Resources/shinyapp2_temp/' . $sessionID . '_drug_networks/' . $drug . '_cytoscape_edges.txt" download> Download edges</a><br>
                   <a href="./Data/Pipeline/Resources/shinyapp2_temp/' . $sessionID . '_drug_networks/' . $drug . '_cytoscape_nodes.txt" download> Download nodes</a>';
        } else {
          $link = "None created";
          $download = "None created";
        }

        if ($count == 1) {
          $count++;
          continue;
        }
        echo "<tr><td>$drugname</td><td>$species</td><td>$tissue</td><td>$zscore</td><td>$rank</td><td>$pvalue</td><td>$link</td><td>$download</td></tr>"; //changed 07312020 JD
      }

      ?>
    </table>
  </div>
<?php
} else if ($signature == 1 && file_exists($resultfile)) {
?>
  <!--Meta table version----------->
  <div style="margin-bottom: 10%; padding-bottom: 1%;" class="table-responsive">
    <table id="shinyapp2_results" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>
            Drug
          </th>
          <th>
            Species
          </th>
          <th>
            Tissue
          </th>
          <th>
              <a href="#" tooltip="Z score of network proximity measurement against 1000 sets of permuted drug and input genes" style="position: relative; z-index: 1001;">Z score</a>
          </th>
          <th>
             <a href="#" tooltip="Percentile rank of network z scores across drug signatures" style="position: relative; z-index: 1001;">Z score rank</a>
          </th>
          <th>
            <a href="#" tooltip="Probability of a network z score to be less (greater connectivity) than or equal to the observed z score based on z scores of all drug signatures" style="position: relative; z-index: 1001;">P value</a>
          </th>
          <th>
            Visualization Link
          </th>
          <th>
            Network Cytoscape files
          </th>
          <th>
            SIDER link
          </th>
        </tr>
      </thead>
      <?php
      $count = 1;
      $result = file($resultfile);
      foreach ($result as $line) {
        $line_array = explode("\t", $line);
        $drugname = trim($line_array[0]);
        $species = trim($line_array[1]);
        $tissue = trim($line_array[2]);
        $zscore = number_format($line_array[9], 3, ".", "");
        $rank = number_format($line_array[10], 3, ".", "");
        $pvalue = scientificNotation(trim((float)$line_array[11]));
        $drug = trim($line_array[16]);
        $sider = trim($line_array[8]);

        if (file_exists("./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_drug_networks/$drug" . "_cytoscape_edges.txt")) {
          $link = '<span style="text-align: center;margin:0px;">
                <form style="margin-bottom:0;" action="/cyto_visualize/write_cytoscape_app2.php" name="figapp2" target="_blank">
                  <input type="hidden" name="sessionID" value="' . $sessionID . '">
                  <input type="hidden" name="drugres" value="' . $drug . '">
                  <input type="submit" class="button button-3d" style="padding:0% 5%; font-size 18px;background-color: #DC461D;margin:0 20px 0 0;" value="Display Network" />
                </form>
              </span>';
          $download = '<a href="./Data/Pipeline/Resources/shinyapp2_temp/' . $sessionID . '_drug_networks/' . $drug . '_cytoscape_edges.txt" download> Download edges</a><br>
                   <a href="./Data/Pipeline/Resources/shinyapp2_temp/' . $sessionID . '_drug_networks/' . $drug . '_cytoscape_nodes.txt" download> Download nodes</a>';
        } else {
          $link = "None created";
          $download = "None created";
        }

        if ($sider!="none") {
          $sider = '<a href=' . $sider . '> SIDER';
        }

        if ($count == 1) {
          $count++;
          continue;
        }
        echo "<tr><td>$drugname</td><td>$species</td><td>$tissue</td><td>$zscore</td><td>$rank</td><td>$pvalue</td><td>$link</td><td>$download</td><td>$sider</td></tr>"; //changed 07312020 JD
      }

      ?>
    </table>
  </div>

  <!--Hepatotoxicity table----------->
  <h3 style="margin-bottom: 1%;">Hepatotoxicity ADR scoring of input genes</h3>
  <p style="text-align: left;margin-bottom: 1%; font-size: 1rem;">
  Overlap score for user input genes against Comparative Toxicogenomics Database chemical induced liver injury signature. Jaccard score rank and p value are calculated against 4201 rat liver drug signatures in the database.
  </p>
  <div class="table-responsive">
    <table id="shinyapp2_resultstox" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>
            Adverse drug reaction
          </th>
          <th>
            Jaccard score
          </th>
          <th>
            Jaccard score rank
          </th>
          <th>
            Jaccard p value
          </th>
          <th>
            ADR genes and input genes overlap
          </th>
        </tr>
      </thead>
      <?php

      $count = 1;
      $result = file($resultfilehepatotox);
      foreach ($result as $line) {
        $line_array = explode("\t", $line);
        $submod = trim($line_array[0]);

        $jaccard = number_format((float)$line_array[1], 3, ".", "");
        $jrank = number_format((float)$line_array[2], 3, ".", "");
        $jpvalue = scientificNotation(trim((float)$line_array[3]));

        $overlap = trim($line_array[4]);

        if ($count == 1) {
          $count++;
          continue;
        }

        echo "<tr><td>$submod</td><td>$jaccard</td><td>$jrank</td><td>$jpvalue</td><td>$overlap</td></tr>";
      }

      ?>
    </table>
  </div>

<?php
} else if (file_exists($resultfile)) { // dose seg version
?>
  <!--Dose seg table version----------->
  <div style="margin-bottom: 10%; padding-bottom: 1%;" class="table-responsive">
    <table id="shinyapp2_results" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <caption>The method used for drug DEG signatures was Limma. Network visualization subsets to drug genes with a first neighbor connection to an input gene and all input genes. In the file available for download below 'Download Output files', additional columns include the genes that are both a drug gene and input gene ('Drug_gene_input_gene_overlap_in_network'), drug genes that are directly (first neighbor) connected to input genes ('Drug_genes_directly_connected_to_input_gene'), and input genes that are directly connected to drug genes ('Input_genes_directly_connected_to_drug_gene'). Second, third, and so on neighbors are considered in the network score calculation, but we show first drug neighbor gene results and network visualizations. </caption>
      <thead>
        <tr>
          <th>
            Database
          </th>
          <th>
            Drug
          </th>
          <th>
            Species
          </th>
          <th>
            Tissue
          </th>
          <th>
            Study
          </th>
          <th>
            Time
          </th>
          <th>
            Dose
          </th>
          <th>
              <a href="#" tooltip="Z score of network proximity measurement against 1000 sets of permuted drug and input genes" style="position: relative; z-index: 1001;">Z score</a>
          </th>
          <th>
             <a href="#" tooltip="Percentile rank of network z scores across drug signatures" style="position: relative; z-index: 1001;">Z score rank</a>
          </th>
          <th>
            <a href="#" tooltip="Probability of a network z score to be less (greater connectivity) than or equal to the observed z score based on z scores of all drug signatures" style="position: relative; z-index: 1001;">P value</a>
          </th>
          <th>
            Visualization Link
          </th>
          <th>
            Network Cytoscape files
          </th>
          <th>
            Hepatotoxicity Rank
          </th>
          <th>
            SIDER link
          </th>
        </tr>
      </thead>
      <?php
      /*
    set_time_limit(0);
  do {
  if (file_exists($resultfile)) {
      break;
  }
  } while(true);*/
      $count = 1;
      $result = file($resultfile);
      foreach ($result as $line) {
        $line_array = explode("\t", $line);
        $database = trim($line_array[8]);
        $drugname = trim($line_array[0]);
        $species = trim($line_array[1]);
        $tissue = trim($line_array[2]);
        $study = trim($line_array[3]);
        $time = trim($line_array[4]);
        $dose = trim($line_array[5]);
        //$jaccard = number_format((float)$line_array[10], 3, ".", "");
        $zscore = number_format((float)$line_array[10], 3, ".", "");
        $rank = number_format((float)$line_array[15], 3, ".", "");
        $pvalue = scientificNotation(trim((float)$line_array[16]));
        $drug = trim($line_array[17]);
        $tox = trim($line_array[81]);
        $sider = trim($line_array[82]);

        if (file_exists("./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_drug_networks/$drug" . "_cytoscape_edges.txt")) {
          $link = '<span style="text-align: center;margin:0px;">
                <form style="margin-bottom:0;" action="/cyto_visualize/write_cytoscape_app2.php" name="figapp2" target="_blank">
                  <input type="hidden" name="sessionID" value="' . $sessionID . '">
                  <input type="hidden" name="drugres" value="' . $drug . '">
                  <input type="submit" class="button button-3d" style="padding:0% 5%; font-size 18px;background-color: #DC461D;margin:0 20px 0 0;" value="Display Network" />
                </form>
              </span>';
          $download = '<a href="./Data/Pipeline/Resources/shinyapp2_temp/' . $sessionID . '_drug_networks/' . $drug . '_cytoscape_edges.txt" download> Download edges</a><br>
                   <a href="./Data/Pipeline/Resources/shinyapp2_temp/' . $sessionID . '_drug_networks/' . $drug . '_cytoscape_nodes.txt" download> Download nodes</a>';
        } else {
          $link = "None created";
          $download = "None created";
        }

        if ($sider!="none") {
          $sider = '<a href=' . $sider . '> SIDER';
        }

        if ($count == 1) {
          $count++;
          continue;
        }

        echo "<tr><td>$database</td><td>$drugname</td><td>$species</td><td>$tissue</td><td>$study</td><td>$time</td><td>$dose</td><td>$zscore</td><td>$rank</td><td>$pvalue</td><td>$link</td><td>$download</td><td>$tox</td><td>$sider</td></tr>";
      }

      ?>
    </table>
  </div>


    <!--Hepatotoxicity table----------->
  <h3 style="margin-bottom: 1%;">Hepatotoxicity ADR scoring of input genes</h3>
  <p style="text-align: left;margin-bottom: 1%; font-size: 1rem;">
  Network scores for user input genes against Comparative Toxicogenomics Database chemical induced liver injury signature and hepatotoxicity liver network submodules from Chen et al. 2022. Network score rank and p value are calculated against 4201 rat liver drug signatures in the database.
</p>
  <div class="table-responsive">
    <table id="shinyapp2_resultstox" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>
            Hepatotoxicity Submodule
          </th>
          <th>
            Z score
          </th>
          <th>
            Z score rank
          </th>
          <th>
            P value
          </th>
          <th>
            Hepatotoxicity genes and input genes overlap
          </th>
        </tr>
      </thead>
      <?php

      $count = 1;
      $result = file($resultfilehepatotox);
      foreach ($result as $line) {
        $line_array = explode("\t", $line);
        $submod = trim($line_array[0]);

        $zscore = number_format((float)$line_array[1], 3, ".", "");
        $zrank = number_format((float)$line_array[2], 3, ".", "");
        $zpvalue = scientificNotation(trim((float)$line_array[3]));

        /*
        $jaccard = number_format((float)$line_array[4], 3, ".", "");
        $jrank = trim($line_array[5]);
        $jpvalue = trim($line_array[6]);
        */

        $overlap = trim($line_array[4]);

        if ($count == 1) {
          $count++;
          continue;
        }

        echo "<tr><td>$submod</td><td>$zscore</td><td>$zrank</td><td>$zpvalue</td><td>$overlap</td></tr>";
      }

      ?>
    </table>
  </div>
<?php

} else {
  //do nothing
}
?>

<?php

if ($type == 'pharm') {
?>
  <script type="text/javascript">
    $("#APP2togglet").css("background-color", "#c5ebd4");
    $("#APP2togglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">App 2 - Network Based Drug Repositioning</div>`);
  </script>
<?php
}
?>
<script type="text/javascript">
  var signature = "<?php echo $signature; ?>";
  var type = "<?php echo $type; ?>";
  /*
  if(signature==1){
  	var column = 4;
  }
  else{
  	var column = 9;
  }*/

  if (signature == 1 && type == "wkda") {
    $("#shinyapp2_resultskda").dataTable({

      "order": [
        [4, 'desc']
      ],
      "dom": '<"top"Bf<"clear">>rt<"bottom"ilp<"clear">>',
      "buttons": [
        'excelHtml5',
      ],
      autoWidth: false,
      "columnDefs": [{
          "width": "10%"
        },
        {
          "width": "10%"
        },
        {
          "width": "10%"
        },
        {
          "width": "8%"
        },
        {
          "width": "8%"
        },
        {
          "width": "10%"
        },
        {
          "width": "22%"
        },
        {
          "width": "22%"
        },
        {
          render: function(data, type, full, meta) {
            return "<div style='min-width: 8em;'>" + data + "</div>";
          },
          targets: [4, 5, 7]
        }
      ],
      "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "All"]
      ]
    });
  } else if (signature == 1) {
    $("#shinyapp2_results").dataTable({

      "order": [
        [4, 'desc']
      ],
      "dom": "Bfrtlip",
      "buttons": [
        'excelHtml5',
      ],
      autoWidth: false,
      "columnDefs": [{
          "width": "10%"
        },
        {
          "width": "10%"
        },
        {
          "width": "10%"
        },
        {
          "width": "8%"
        },
        {
          "width": "8%"
        },
        {
          "width": "10%"
        },
        {
          "width": "22%"
        },
        {
          "width": "22%"
        },
        {
          render: function(data, type, full, meta) {
            return "<div style='min-width: 8em;'>" + data + "</div>";
          },
          targets: [4, 5, 7]
        }
      ],
      "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "All"]
      ]
    });

    $("#shinyapp2_resultstox").dataTable({

        "dom": '<"top"Bf<"clear">>rt<"bottom"ilp<"clear">>',

        autoWidth: false,
        "columnDefs": [{
            "width": "15%"
          },
          {
            "width": "15%"
          },
          {
            "width": "15%"
          },
          {
            "width": "15%"
          },
          {
            "width": "40%"
          },
          {
            render: function(data, type, full, meta) {
              return "<div style='max-height: 50px; overflow-y: auto; text-align: left; min-width: 10em;'>" + data + "</div>";
            },
            targets: [4]
          }
        ]
    });

    
  } else {
    $("#shinyapp2_results").dataTable({

      "order": [
        [8, 'desc']
      ],
      "dom": "Bfrtlip",
      "buttons": [
        'excelHtml5',
      ]
    });

    $("#shinyapp2_resultstox").dataTable({

      

      "order": [
        [1, 'asc']
      ],

      "dom": '<"top"Bf<"clear">>rt<"bottom"ilp<"clear">>',
      "buttons": [
        'excelHtml5',
      ],
      autoWidth: false,
      "columnDefs": [{
          "width": "15%"
        },
        {
          "width": "15%"
        },
        {
          "width": "15%"
        },
        {
          "width": "15%"
        },
        {
          "width": "40%"
        },
        {
          render: function(data, type, full, meta) {
            return "<div style='max-height: 50px; overflow-y: auto; text-align: left;'>" + data + "</div>";
          },
          targets: [4]
        }
      ],

      "lengthMenu": [
        [15, 30, -1],
        [15, 30, "All"]
      ]
    });

  }
</script>