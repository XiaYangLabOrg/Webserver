 <?php
  include "functions.php";
  $ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
  if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
  }


  if (isset($_GET['rmchoice']) ? $_GET['rmchoice'] : null) {
    $rmchoice = $_GET['rmchoice'];
  }

  if (isset($_GET['geneset']) ? $_GET['geneset'] : null) {
    $geneset = $_GET['geneset'];
  }

  if (isset($_GET['genesetd']) ? $_GET['genesetd'] : null) {
    $genesetd = $_GET['genesetd'];
  }

  if (isset($_GET['network']) ? $_GET['network'] : null) {
    $network = $_GET['network'];
  }

  if (isset($_GET['kdadepth']) ? $_GET['kdadepth'] : null) {
    $kdadepth = $_GET['kdadepth'];
  }

  if (isset($_GET['kdadirect']) ? $_GET['kdadirect'] : null) {
    $kdadirect = $_GET['kdadirect'];
  }
  if (isset($_GET['minKDA']) ? $_GET['minKDA'] : null) {
    $minKDA = $_GET['minKDA'];
  }
  if (isset($_GET['edgewKDA']) ? $_GET['edgewKDA'] : null) {
    $edgewKDA = $_GET['edgewKDA'];
  }

  if (isset($_GET['NetConvert'])) {
    $NetConvert = $_GET['NetConvert'];
  }

  if (isset($_GET['GSETConvert'])) {
    $GSETConvert = $_GET['GSETConvert'];
  }

  if (isset($_GET['rerun'])) {
    $rerun = $_GET['rerun'];
  }
  $fjson = $ROOT_DIR . "Data/Pipeline/Resources/kda_temp/$sessionID" . "param.json";
  if ($rerun == "T") {
    $json = array();
    //$fpath_random = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";
    //$num_iterations = file($fpath_random);
    //for ($i = 0; $i < (count($num_iterations)); $i++) {
    $json['session'] = $sessionID;
    //$fpathOut = "./Data/Pipeline/Resources/meta_temp/$new_random_string" . "PARAM";
    //$fdr_file = "./Data/Pipeline/Resources/meta_temp/$new_random_string" . "PARAM_SSEA_FDR";
    $json['geneset'] = $geneset;
    $json['GSETConvert'] = $GSETConvert;
    $json['genesetd'] = $genesetd;
    $json['network'] = $network;
    $json['NetConvert'] = $NetConvert;
    $json['kdadepth'] = $kdadepth;
    $json['kdadirect'] = $kdadirect;
    $json['minKDA'] = $minKDA;
    $json['edgewKDA'] = $edgewKDA;
    if (empty($data->data)) {
      $data['data'][] = $json;
    } else {
      $data->data[] = $json;
    }
    debug_to_console($fjson);
    $fp = fopen($fjson, 'w');
    fwrite($fp, json_encode($data));
    fclose($fp);
    chmod($fjson, 0777);
  } else {
    if (file_exists($fjson)) {
      $data = json_decode(file_get_contents($fjson),true)["data"][0];
      $geneset = $data["geneset"];
      $genesetd = $data["genesetd"];
      $network = $data["network"];
      $kdadepth = $data["kdadepth"];
      $kdadirect = $data["kdadirect"];
      $minKDA = $data["minKDA"];
      $edgewKDA = $data["edgewKDA"];
    }
  }

  if (!file_exists("./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAPARAM") || $rerun == "T") {
    //Generate input files needed for run_wKDA.php
    if ($geneset == 2) {
      $geneset = "Resources/kda_temp/" . $sessionID . "_nodes_file.txt";
      $fp = fopen("./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAMODULE", "w");
      fwrite($fp, $geneset);
      fclose($fp);
    }


    $fp = fopen("./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDA", "w");
    fwrite($fp, $network);
    fclose($fp);

    $fp = fopen("./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAPARAM", "w");
    fwrite($fp, "job.kda\$depth <- " . $kdadepth . "\n");
    fwrite($fp, "job.kda\$direction <- " . $kdadirect . "\n");
    fclose($fp);

    if ($kdadirect == 1) {
      $kdadirect = "Undirected";
    } else {
      $kdadirect = "Directed";
    }

    $fp = fopen("./Data/Pipeline/Resources/kda_temp/$sessionID" . "DESC", "w");
    fwrite($fp, $genesetd);
    fclose($fp);
    if ($genesetd == 2) {
      unlink("./Data/Pipeline/Resources/kda_temp/$sessionID" . "DESC");
      $genesetd = "No Gene Sets Description";
    }

    $fp = fopen("./Data/Pipeline/Resources/kda_temp/$sessionID" . "edge", "w");
    fwrite($fp, "job.kda\$edgefactor <-" . $edgewKDA . "\n");
    fclose($fp);

    $fp = fopen("./Data/Pipeline/Resources/kda_temp/$sessionID" . "overlap", "w");
    fwrite($fp, "job.kda\$maxoverlap <-" . $minKDA . "\n");
    fclose($fp);
  }

  //paths of the sessionID and POST data
  $fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
  $fpostOut = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "_MSEA_postdata.txt";
  if (file_exists($fsession)) //check if the session.txt file actually exists (it should since this is a moduleprogress page)
  {
    $session = explode("\n", file_get_contents($fsession));
    //Create different array elements based on new line
    $pipe_arr = preg_split("/[\t]/", $session[0]);
    $pipeline = $pipe_arr[1];

    if ($pipeline == "MSEA") //check if the pipeline is MSEA. Probably not needed for this pipeline
    {
      // read file and store lines into an array
      $data = file($fsession);
      //function to change the path from 1 --> 1.25 
      function replace_a_line($data)
      {
        if (stristr($data, 'Mergeomics_Path:' . "\t" . "1")) {
          return 'Mergeomics_Path:' . "\t" . "1.25" . "\n";
        }
        return $data;
      }
      //replace the data in the file with the 1.25
      $data = array_map('replace_a_line', $data);
      file_put_contents($fsession, implode('', $data));
    }
  }


  if (isset($_GET['wKDAemail'])) {
    $emailid = $_GET['wKDAemail'];
  } else {
    $emailid = "";
  }

  if ($emailid != "") {
    $emailid .= "\n";
  }

  $femail = "./Data/Pipeline/Results/kda_email/$sessionID" . "email";
  $email_sent = "./Data/Pipeline/Results/kda_email/$sessionID" . "sent_email";


  if ($emailid != "") {
    $parts = explode("@", $emailid);
    $name = $parts[0];
    $domain = $parts[1];
    if (trim($domain) == 'ucla.edu') {
      $newid = "$name" . "@g.ucla.edu";
    } else {
      $newid = $emailid;
    }
    $myfile = fopen($femail, "w");
    fwrite($myfile, $newid);
    fclose($myfile);
  }

  if ((!(file_exists($email_sent)))) {
    if (file_exists($femail)) {

      $sendemail = 'Yes';
    } else {

      $sendemail = 'No';
    }
  }


  $fpath = "./Data/Pipeline/Resources/$sessionID";


  $kdapath = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDA";



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
        if (stristr($data, 'Mergeomics_Path:' . "\t" . "2")) {
          return 'Mergeomics_Path:' . "\t" . "2.25" . "\n";
        }
        return $data;
      }
    } else if ($pipeline == "GWAS") {
      $data = file($fsession); // reads an array of lines
      function replace_a_line($data)
      {
        if (stristr($data, 'Mergeomics_Path:' . "\t" . "3")) {
          return 'Mergeomics_Path:' . "\t" . "3.25" . "\n";
        }
        return $data;
      }
    } else if ($pipeline == "MSEA" || $pipeline == "META") {
      $data = file($fsession); // reads an array of lines
      function replace_a_line2($data)
      {
        if (stristr($data, 'Mergeomics_Path:' . "\t" . "2")) { //change from 2 --> 2.25
          return 'Mergeomics_Path:' . "\t" . "2.25" . "\n";
        }
        return $data;
      }
    } else if ($pipeline == "KDA") {
      $data = file($fsession); // reads an array of lines
      function replace_a_line($data)
      {
        if (stristr($data, 'Mergeomics_Path:' . "\t" . "1")) { //change from 1 --> 1.25
          return 'Mergeomics_Path:' . "\t" . "1.25" . "\n";
        }
        return $data;
      }
    }

    if ($pipeline == "MSEA"|| $pipeline == "META") {
      $data = array_map('replace_a_line2', $data);
    } else {
      $data = array_map('replace_a_line', $data);
    }
    if (!empty($data)) {
      file_put_contents($fsession, implode('', $data));
    }
  }





  ?>

 <h4 class="instructiontext" id="reviewtext">Please review the files you have selected/uploaded and the parameters you have selected in the overview chart below before executing the wKDA pipeline.</h4>

 <br>

 <table class="table table-bordered review" style="text-align: center;" id="wKDAreviewtable">
   <thead>
     <tr>
       <th>Type</th>
       <th>Description</th>
       <th>Filename/Parameters</th>

       <?php
        $overview_write = NULL;
        $overview_write .= "Description" . "\t" . "Filename/Parameter" . "\n";
        ?>
     </tr>
   </thead>
   <tbody>
     <tr>
       <td rowspan="3" style="vertical-align: middle;">Files</td>
       <td>Gene Sets</td>
       <td style="font-weight: bold;">
         <?php
         /*
         if ($geneset == 2) {
           echo basename($geneset);
           $overview_write .= "Gene Sets" . "\t" . "$geneset" . "\n";
         }
         else{
          */
          $fpathmod = $ROOT_DIR . "Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAMODULE";
          chmod($fpathmod, 0777);
          if(file_exists($fpathmod)){
            $mod = trim(file_get_contents($fpathmod));
            echo basename($mod);
            $overview_write .= "Gene Sets" . "\t" . "$mod" . "\n";
          }
          else{
            echo basename($geneset);
            $overview_write .= "Nodes or Gene Sets" . "\t" . "$geneset" . "\n";
          }

         //}
          ?>
       </td>
     </tr>
     <tr>
       <td>Gene Sets Description</td>
       <td style="font-weight: bold;">
         <?php



          echo basename($genesetd);
          $overview_write .= "Gene Sets Description" . "\t" . "$genesetd" . "\n";

          ?>
       </td>
     </tr>
     <tr>
       <td>Network</td>
       <td style="font-weight: bold;">
         <?php
          echo basename($network);
          $overview_write .= "Network" . "\t" . "$network" . "\n";
          ?>
       </td>
     </tr>
     <tr>
       <td rowspan="4">Parameters</td>
       <td>Search Depth</td>
       <td style="font-weight: bold;">
         <?php

          if ($rmchoice !== 4) {
            $fpathparam = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAPARAM";
            $max_genes = null;
            if (file_exists($fpathparam)) {
              $gwas_file = file($fpathparam);
              $max_genes = explode("<- ", $gwas_file[0]);
              $max_genes = trim($max_genes[1]);
            }
            echo $max_genes;
            $overview_write .= "Search Depth" . "\t" . "$max_genes" . "\n";
          } else {

            echo $kdadepth;
            $overview_write .= "Search Depth" . "\t" . "$kdadepth" . "\n";
          }
          ?>
       </td>
     </tr>
     <tr>
       <td>
         Edge Type
       </td>
       <td style="font-weight: bold;">
         <?php

          if ($rmchoice !== 4) {
            $fpathparam = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAPARAM";
            $edge_type = null;
            if (file_exists($fpathparam)) {
              $gwas_file = file($fpathparam);
              $edge_type = explode("<- ", $gwas_file[1]);
              $edge_type = trim($edge_type[1]);
            }
            if ($edge_type == "1") {
              echo "Undirected";
              $overview_write .= "Edge Type" . "\t" . "Undirected" . "\n";
            } elseif ($edge_type == "2") {
              echo "Directed";
              $overview_write .= "Edge Type" . "\t" . "Directed" . "\n";
            }
            // echo $edge_type;
          } else {

            echo $kdadirect;
            $overview_write .= "Edge Type" . "\t" . "$kdadirect" . "\n";
          }
          ?>
       </td>
     </tr>
     <tr>
       <td>
         Min Overlap
       </td>
       <td style="font-weight: bold;">
         <?php

          echo $minKDA;
          $overview_write .= "Min Overlap" . "\t" . "$minKDA" . "\n";

          ?>
       </td>
     </tr>
     <tr>
       <td>
         Edge Factor
       </td>
       <td style="font-weight: bold;">
         <?php



          echo $edgewKDA;
          $overview_write .= "Min Overlap" . "\t" . "$edgewKDA" . "\n";


          ?>
       </td>
     </tr>

   </tbody>
 </table>

 <?php
  $overview_fp = "./Data/Pipeline/Results/kda/" . "$sessionID" . "_overview.txt";
  $overview_file = fopen($overview_fp, "w");
  fwrite($overview_file, $overview_write);
  fclose($overview_file);
  chmod($overview_fp, 0777);
  ?>

 <br>
 <br>


 <h5 style="color: #00004d;">Enter your e-mail id for job completion notification (Recommended)
   <?php if (isset($_GET['wKDAemail']) ? $_GET['wKDAemail'] : null) {
    ?>
     <div class="alert alert-success" style="display: inline-flex; padding: 5px;">
       <i class="i-rounded i-small icon-check" style="background-color: #2ea92e;"></i><strong style="margin-top: 5px;">
         <?php
          print($newid);
          ?>
       </strong>
     </div>
   <?php
    } else {
    ?>

     <input type="text" name="wKDAemail" id="yourEmail_wKDA">

     <button type="button" class="button button-3d button-small nomargin" id="wKDAemailSubmit">Send email</button>
   <?php
    }

    ?>
 </h5>

 <br>


 <div style="text-align:center;">
   <button type="button" class="button button-3d button-large nomargin" id="RunwKDAPipeline">Run wKDA Pipeline</button>
 </div>
 <div id="emailconfirm_wKDA"></div>
 <div id="wKDAloading"></div>

 <script type="text/javascript">
   var string = "<?php echo $sessionID; ?>";
 </script>

 <?php
  if ($rmchoice == 1) {
  ?>
   <script type="text/javascript">
     $("#wKDAemailSubmit").on('click', function(e) {
       var email = $("input[name=wKDAemail]").val();
       $('#mywKDA_review').empty();
       $('#mywKDA_review').load("/wKDA_moduleprogress.php?sessionID=" + string + "&wKDAemail=" + email + "&rmchoice=1");
       e.preventDefault();
       return false;

     });


     $("#RunwKDAPipeline").on('click', function() {
       //var emailcheck = "<?php echo $sendemail; ?>";
       $('#mywKDA_review').load("/run_wKDA.php?sessionID=" + string + "&rmchoice=1&run=T");
       $('#wKDAtab2').html('Results');
       $("#wKDAtogglet").css("background-color", "#c5ebd4");
       $("#wKDAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 3 - Weighted Key Driver Analysis</div>`);

       return false;

     });
   </script>

 <?php
  } else if ($rmchoice == 2) {
  ?>
   <script type="text/javascript">
     $("#wKDAemailSubmit").on('click', function(e) {
       var email = $("input[name=wKDAemail]").val();
       $('#myMSEA2KDA_review').empty();
       $('#myMSEA2KDA_review').load("/wKDA_moduleprogress.php?sessionID=" + string + "&wKDAemail=" + email + "&rmchoice=2");
       e.preventDefault();
       return false;

     });


     $("#RunwKDAPipeline").on('click', function() {
       //var emailcheck = "<?php echo $sendemail; ?>";
       $('#myMSEA2KDA_review').load("/run_wKDA.php?sessionID=" + string + "&rmchoice=2&run=T");
       $('#MSEA2KDAtab2').html('Results');
       $("#MSEA2KDAtogglet").css("background-color", "#c5ebd4");
       $("#MSEA2KDAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2 - Weighted Key Driver Analysis</div>`);

       return false;

     });
   </script>

 <?php
  } else if ($rmchoice == 3) {
  ?>
   <script type="text/javascript">
     $("#wKDAemailSubmit").on('click', function(e) {
       var email = $("input[name=wKDAemail]").val();
       $('#myMETA2KDA_review').empty();
       $('#myMETA2KDA_review').load("/wKDA_moduleprogress.php?sessionID=" + string + "&wKDAemail=" + email + "&rmchoice=3");
       e.preventDefault();
       return false;

     });


     $("#RunwKDAPipeline").on('click', function() {
       //var emailcheck = "<?php echo $sendemail; ?>";
       $('#myMETA2KDA_review').load("/run_wKDA.php?sessionID=" + string + "&rmchoice=3&run=T");
       $('#META2KDAtab2').html('Results');
       $("#META2KDAtogglet").css("background-color", "#c5ebd4");
       $("#META2KDAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2 - Weighted Key Driver Analysis</div>`);

       return false;

     });
   </script>

 <?php
  } else {

  ?>

   <script type="text/javascript">
     $("#wKDAemailSubmit").on('click', function(e) {
       var email = $("input[name=wKDAemail]").val();
       $('#myKDASTART_review').empty();
       $('#myKDASTART_review').load("/wKDA_moduleprogress.php?sessionID=" + string + "&wKDAemail=" + email + "&rmchoice=4");
       e.preventDefault();
       return false;

     });


     $("#RunwKDAPipeline").on('click', function() {
       //var emailcheck = "<?php echo $sendemail; ?>";
       $('#myKDASTART_review').load("/run_wKDA.php?sessionID=" + string + "&rmchoice=4&run=T");
       $('#KDASTARTtab2').html('Results');
       $("#KDASTARTtogglet").css("background-color", "#c5ebd4");
       $("#KDASTARTtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1 - Weighted Key Driver Analysis</div>`);

       return false;

     });
   </script>

 <?php

  }


  ?>