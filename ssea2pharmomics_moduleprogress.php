 <?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
  if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
  }

  if (isset($_GET['rmchoice'])) {
    $rmchoice = $_GET['rmchoice'];
  }

  if (isset($_GET['network_select'])) {
    $network = $_GET['network_select'];
  }

  if (isset($_GET['species_select'])) {
    $species = $_GET['species_select'];
  }

  if (isset($_GET['modulegroup'])) {
    $module = $_GET['modulegroup'];
  }

  if (isset($_GET['genegroup'])) {
    $gene = $_GET['genegroup'];
  }

  if (isset($_GET['sig_measure'])) {
    $measure = $_GET['sig_measure'];
    if ($measure == 'FDR') {
      $measure = "False Discovery Rate";
    } else {
      $measure = "P-Value";
    }
  }

  if (isset($_GET['sig_threshold'])) {
    $threshold = $_GET['sig_threshold'];
  }
  $fjson = $ROOT_DIR."Data/Pipeline/Resources/session/$sessionID" . "_pharmomics.json";
  if (isset($_GET['analysistype'])) {
    $analysis = $_GET['analysistype'];
    $json = array();
    $json['session'] = $sessionID;
    $json['rmchoice'] = $rmchoice;
    $json['network_select'] = $network;
    $json['species_select'] = $species;
    $json['modulegroup'] = $module;
    $json['genegroup'] = $gene;
    $json['sig_measure'] = $measure;
    $json['sig_threshold'] = $threshold;
    $json['analysis'] = $analysis;
    $fp = fopen($fjson, 'w');
    fwrite($fp, json_encode($json));
    fclose($fp);
    chmod($fjson, 0777);
  } else {
    #Assuming it is a session loading if $analysistype is empty 
    $json = json_decode(file_get_contents($fjson));
    $sessionID = $json->session;
    $rmchoice = $json->rmchoice;
    $network = $json->network_select;
    $species = $json->species_select;
    $module = $json->modulegroup;
    $gene = $json->genegroup;
    $measure = $json->sig_measure;
    $threshold = $json->sig_threshold;
    $analysis = $json->analysis;
  }
  if ($analysis == 1) {
    $genefile = $ROOT_DIR."Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_selectedmodules.txt";
    $geneinputfile = $ROOT_DIR."Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_genes.txt";
    $femail = $ROOT_DIR."Data/Pipeline/Results/shinyapp2_email/$sessionID" . "email";
    $email_sent = $ROOT_DIR."Data/Pipeline/Results/shinyapp2_email/$sessionID" . "sent_email";
  } else {
    $genefile = $ROOT_DIR."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".SSEA2PHARM_selectedmodules.txt";
    $geneinputfile = $ROOT_DIR."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".SSEA2PHARM_genes.txt";
    $femail = $ROOT_DIR."Data/Pipeline/Results/shinyapp3_email/$sessionID" . "email";
    $email_sent = $ROOT_DIR."Data/Pipeline/Results/shinyapp3_email/$sessionID" . "sent_email";
  }

  $geneinputfilesize = filesize($geneinputfile);
  


  if (isset($_GET['ssea2pharmemail'])) {
    $emailid = $_GET['ssea2pharmemail'];
  } else {
    $emailid = "";
  }

  if ($emailid != "") {
    $emailid .= "\n";
  }

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

  $fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
  if (file_exists($fsession)) {
    function replace_a_line($data, $rmchoice)
    {
      if (strpos($data, 'Pharmomics_Path') !== false) {
        $pharmomics_arr = preg_split("/[\t]/", $data);
        $pharmomics_arr2 = explode("|", $pharmomics_arr[1]);
        //$msea2pharmomics = $pharmomics_arr2[0];
        $kda2pharmomics = preg_replace('/\s+/', ' ', trim($pharmomics_arr2[1]));

        if ($rmchoice == 1) {
          return 'Pharmomics_Path:' . "\t" . "SSEAtoPharmomics,1.25|" . $kda2pharmomics . "\n";
        } else {
          return 'Pharmomics_Path:' . "\t" . "MSEAtoPharmomics,1.25|" . $kda2pharmomics . "\n";
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

 <style type="text/css">
   #ssea2pharm_overview {
     table-layout: fixed;
     text-align: center;
   }

   #ssea2pharm_overview td {
     word-wrap: break-word;
     vertical-align: top;
   }
 </style>

 <?php

  if ($geneinputfilesize == 0) {
  ?>
    <div class="alert alert-warning" style="margin: 0 auto; width: 50%;margin-top: 10px;font-size: 16px;text-align: center;">
    <i class="icon-warning-sign" style="margin-right: 6px;font-size: 16px;"></i>No genes for repositioning! You may not have any modules passing the chosen statistical threshold. Make threshold more lenient to capture modules.
  </div>
   <?php
  } 
  ?>

 <h4 class="instructiontext" id="reviewtext">Please review the modules/genes you have selected in the overview chart below before executing the overlap based drug repositioning pipeline.</h4>

 <br>
 <?php

  if ($analysis == 1) {
  ?>
   <table class="table table-bordered review" style="text-align: center" ; id="ssea2pharmtable">
     <thead>
       <tr>
         <th>Drug Repositioning Analysis</th>
         <th>Description</th>
         <th>Parameters</th>
       </tr>
     </thead>
     <tbody>
       <tr>
         <td rowspan='5' style="vertical-align: middle;">
           Network Based Drug Repositioning
         </td>
         <td>Network Selection</td>
         <td style="font-weight: bold;">
           <?php
            if ($network == 1)
              echo "User Custom Network";
            else if ($network == 2)
              echo "Sample liver network";
            else if ($network == 3)
              echo "Sample kidney network";
            else
              echo "Sample multi-tissue network";
            ?>
         </td>
       </tr>
       <tr>
         <td>Species Selection</td>
         <td style="font-weight: bold;">
           <?php
            if ($species == 1)
              echo "Human";
            else
              echo "Mouse";
            ?>
         </td>
       </tr>
       <tr>
         <td>
           Module Selection
         </td>
         <td style="font-weight: bold;">
           <?php
            if ($module == 1)
              echo nl2br("All modules from gene set \n ($measure < $threshold)");
            else
              echo "Selected specific modules";
            ?>
         </td>
       </tr>
       <tr>
         <td>
           Gene Selection
         </td>
         <td style="font-weight: bold;">
           <?php
            if ($gene == 1)
              echo "All genes from original gene set";
            else
              echo "Only genes mapped from SNPs";
            ?>
         </td>
       </tr>
       <tr>
         <td>
           Genes used for repositioning
         </td>
         <td>
           <a href=<?php print($geneinputfile); ?> download> Download</a>
         </td>
       </tr>
     </tbody>
   </table>
 <?php

  } else {
  ?>
   <table class="table table-bordered review" style="text-align: center" ; id="ssea2pharmtable">
     <thead>
       <tr>
         <th>Drug Repositioning Analysis</th>
         <th>Description</th>
         <th>Parameters</th>
       </tr>
     </thead>
     <tbody>
       <tr>
         <td rowspan='3' style="vertical-align: middle;">
           Overlap Based Drug Repositioning
         </td>
         <td>
           Module Selection
         </td>
         <td style="font-weight: bold;">
           <?php
            if ($module == 1)
              echo nl2br("All modules from gene set \n ($measure < $threshold)");
            else
              echo "Selected specific modules";
            ?>
         </td>
       </tr>
       <tr>
         <td>
           Gene Selection
         </td>
         <td style="font-weight: bold;">
           <?php
            if ($gene == 1)
              echo "All genes from original gene set";
            else
              echo "Only genes mapped from SNPs";
            ?>
         </td>
       </tr>
       <tr>
         <td>
           Genes used for repositioning
         </td>
         <td>
           <a href=<?php print($geneinputfile); ?> download> Download</a>
         </td>
       </tr>
     </tbody>
   </table>
 <?php
  }



  ?>

 <br>

 <br>

 <?php
  if ($module == 1) {
    if ($gene == 1 && $measure == "False Discovery Rate") {
  ?>

     <div class="table-responsive">
       <table id="ssea2pharm_overview" class="table table-striped table-bordered" cellspacing="0" width="100%">
         <thead>
           <tr>
             <th>Selected Modules</th>
             <th>FDR</th>
             <th>Genes from Modules</th>
             <th>Description</th>

           </tr>
         </thead>
         <tbody>
           <?php

            $array = file($genefile, FILE_SKIP_EMPTY_LINES);

            ?>

           <?php foreach ($array as $line) {
              $line_array = explode("\t", $line);
              $moduleres = trim($line_array[0]);
              if ($moduleres == "_ctrlA" or $moduleres == "_ctrlB") {
                continue;
              }
              $fdrres = trim($line_array[1]);
              $generes = trim($line_array[2]); // no content
              $descres = trim($line_array[3]);
              echo "<tr><td>$moduleres</td><td>$fdrres</td><td>$generes</td><td>$descres</td></tr>";
            }
            ?>



         </tbody>
       </table>
     </div>

   <?php

    } else {
    ?>
     <div class="table-responsive">
       <table id="ssea2pharm_overview" class="table table-striped table-bordered" cellspacing="0" width="100%">
         <thead>
           <tr>
             <th>Selected Modules</th>
             <th>P-Value</th>
             <th>Genes from Modules</th>
             <th>Description</th>

           </tr>
         </thead>
         <tbody>
           <?php

            $array = file($genefile, FILE_SKIP_EMPTY_LINES);

            ?>

           <?php foreach ($array as $line) {
              $line_array = explode("\t", $line);
              $moduleres = trim($line_array[0]);
              $pvalres = trim($line_array[1]);
              $generes = trim($line_array[2]);
              $descres = trim($line_array[3]);
              echo "<tr><td>$moduleres</td><td>$pvalres</td><td>$generes</td><td>$descres</td></tr>";
            }
            ?>



         </tbody>
       </table>
     </div>

   <?php
    }
  } else {
    ?>
   <div class="table-responsive">
     <table id="ssea2pharm_overview" class="table table-striped table-bordered" cellspacing="0" width="100%">
       <thead>
         <tr>
           <th>Selected Modules</th>
           <th>P-Value</th>
           <th>FDR</th>
           <th>Genes from Modules</th>
           <th>Description</th>

         </tr>
       </thead>
       <tbody>
         <?php

          $array = file($genefile, FILE_SKIP_EMPTY_LINES);

          ?>

         <?php foreach ($array as $line) {
            $line_array = explode("\t", $line);
            $moduleres = trim($line_array[0]);
            if ($moduleres == "_ctrlA" or $moduleres == "_ctrlB") {
              continue;
            }
            $pvalres = trim($line_array[1]);
            $fdrres = trim($line_array[2]);
            $generes = trim($line_array[3]);
            $descres = trim($line_array[4]);
            echo "<tr><td>$moduleres</td><td>$pvalres</td><td>$fdrres</td><td>$generes</td><td>$descres</td></tr>";
          }
          ?>



       </tbody>
     </table>
   </div>
 <?php
  }

  ?>





 <br>
 <br>

 <?php

  if ($geneinputfilesize == 0) {
  ?>
    <div class="alert alert-warning" style="margin: 0 auto; width: 50%;margin-top: 10px;font-size: 16px;text-align: center;">
    <i class="icon-warning-sign" style="margin-right: 6px;font-size: 16px;"></i>Cannot continue to drug repositioning without genes to input.
  </div>
   <?php
  } else {
  ?>

 <h5 style="color: #00004d;">Enter your e-mail id for job completion notification (Optional)
   <?php if (isset($_GET['ssea2pharmemail']) ? $_GET['ssea2pharmemail'] : null) {
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

     <input type="text" name="ssea2pharmemail" id="yourEmail_ssea2pharm">

     <button type="button" class="button button-3d button-small nomargin" id="ssea2pharmemailSubmit">Send email</button>
   <?php
    }

    ?>
 </h5>

 <br>


 <div style="text-align:center;">
   <?php
    if ($analysis == 1) {
    ?>
     <button type="button" class="button button-3d button-large pipeline" id="runshinyapp2pipeline_ssea">Run Network Based Drug Repositioning</button>

   <?php
    } else {
    ?>

     <button type="button" class="button button-3d button-large pipeline" id="runshinyapp3pipeline_ssea">Run Overlap Based Drug Repositioning</button>

   <?php
    }
    ?>

 </div>
 <div id="emailconfirm_ssea2pharm"></div>
 <div id="ssea2pharmloading"></div>

    <?php
  } 
  ?>

 <?php
  if ($module == 1) {


  ?>
   <script type="text/javascript">
     $('#ssea2pharm_overview').dataTable({
       "lengthMenu": [
         [5, 10, 25, -1],
         [5, 10, 25, "All"]
       ],
       autoWidth: false,
       columns: [{
           "width": "10%",
           "targets": 0
         },
         {
           "width": "10%",
           "targets": 1
         },
         {
           "width": "60%",
           "targets": 2
         },
         {
           "width": "20%",
           "targets": 3
         }

       ]
     });
   </script>

 <?php

  } else {
  ?>

   <script type="text/javascript">
     $('#ssea2pharm_overview').dataTable({
       "lengthMenu": [
         [5, 10, 25, -1],
         [5, 10, 25, "All"]
       ],
       autoWidth: false,
       columns: [{
           "width": "10%",
           "targets": 0
         },
         {
           "width": "10%",
           "targets": 1
         },
         {
           "width": "10%",
           "targets": 2
         },
         {
           "width": "50%",
           "targets": 3
         },
         {
           "width": "20%",
           "targets": 4
         }

       ]
     });
   </script>

 <?php
  }

  ?>






 <script type="text/javascript">
   var string = "<?php echo $sessionID; ?>";
 </script>

 <?php
  if ($rmchoice == 1) {
  ?>
   <script type="text/javascript">
     $("#ssea2pharmemailSubmit").on('click', function() {
       var email = $("input[name=ssea2pharmemail]").val();
       $('#myssea2pharm_review').empty();
       $('#myssea2pharm_review').load("/ssea2pharmomics_moduleprogress.php?sessionID=" + string + "&ssea2pharmemail=" + email + "&rmchoice=1");
       return false;

     });


     $("#runshinyapp2pipeline_ssea").on('click', function() {
       var network = <?php echo $network; ?>;
       var species = <?php echo $species; ?>;
       $('#myssea2pharm_review').load("/ssea_runshinyapp2.php?sessionID=" + string + "&network=" + network + "&species=" + species + "&rmchoice=1&run=T");
       $('#ssea2pharmtab2').html('Network Based Drug Repositioning Results');
       $("#ssea2pharmtogglet").css("background-color", "#c5ebd4");
       $("#ssea2pharmtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2B - PharmOmics</div>`);


       return false;

     });

     $("#runshinyapp3pipeline_ssea").on('click', function() {
       $('#myssea2pharm_review').load("/ssea_runshinyapp3.php?sessionID=" + string + "&rmchoice=1&run=T");
       $('#ssea2pharmtab2').html('Overlap Based Drug Repositioning Results');
       $("#ssea2pharmtogglet").css("background-color", "#c5ebd4");
       $("#ssea2pharmtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2B - PharmOmics</div>`);


       return false;

     });
   </script>

 <?php
  } else if ($rmchoice == 2) {
  ?>
   <script type="text/javascript">
     $("#ssea2pharmemailSubmit").on('click', function() {
       var email = $("input[name=ssea2pharmemail]").val();
       $('#mymsea2pharm_review').empty();
       $('#mymsea2pharm_review').load("/ssea2pharmomics_moduleprogress.php?sessionID=" + string + "&ssea2pharmemail=" + email + "&rmchoice=2");
       return false;

     });


     $("#runshinyapp2pipeline_ssea").on('click', function() {
       var network = <?php echo $network; ?>;
       var species = <?php echo $species; ?>;
       $('#mymsea2pharm_review').load("/ssea_runshinyapp2.php?sessionID=" + string + "&network=" + network + "&species=" + species + "&rmchoice=2&run=T");
       $('#msea2pharmtab2').html('Network Based Drug Repositioning Results');
       $("#msea2pharmtogglet").css("background-color", "#c5ebd4");
       $("#msea2pharmtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1B - PharmOmics</div>`);


       return false;

     });

     $("#runshinyapp3pipeline_ssea").on('click', function() {
       $('#mymsea2pharm_review').load("/ssea_runshinyapp3.php?sessionID=" + string + "&rmchoice=2&run=T");
       $('#msea2pharmtab2').html('Overlap Based Drug Repositioning Results');
       $("#msea2pharmtogglet").css("background-color", "#c5ebd4");
       $("#msea2pharmtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1B - PharmOmics</div>`);


       return false;

     });
   </script>


 <?php
  } else {
  ?>
   <script type="text/javascript">
     $("#ssea2pharmemailSubmit").on('click', function() {
       var email = $("input[name=ssea2pharmemail]").val();
       $('#myMETAMSEA2PHARM_review').empty();
       $('#myMETAMSEA2PHARM_review').load("/ssea2pharmomics_moduleprogress.php?sessionID=" + string + "&ssea2pharmemail=" + email + "&rmchoice=3");
       return false;

     });


     $("#runshinyapp2pipeline_ssea").on('click', function() {
       var network = <?php echo $network; ?>;
       var species = <?php echo $species; ?>;
       $('#myMETAMSEA2PHARM_review').load("/ssea_runshinyapp2.php?sessionID=" + string + "&network=" + network + "&species=" + species + "&rmchoice=3&run=T");
       $('#METAMSEA2PHARMtab2').html('Network Based Drug Repositioning Results');
       $("#METAMSEA2PHARMtogglet").css("background-color", "#c5ebd4");
       $("#METAMSEA2PHARMtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1B - PharmOmics</div>`);


       return false;

     });

     $("#runshinyapp3pipeline_ssea").on('click', function() {
       $('#myMETAMSEA2PHARM_review').load("/ssea_runshinyapp3.php?sessionID=" + string + "&rmchoice=3&run=T");
       $('#METAMSEA2PHARMtab2').html('Overlap Based Drug Repositioning Results');
       $("#METAMSEA2PHARMtogglet").css("background-color", "#c5ebd4");
       $("#METAMSEA2PHARMtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1B - PharmOmics</div>`);


       return false;

     });
   </script>

 <?php
  }

  ?>