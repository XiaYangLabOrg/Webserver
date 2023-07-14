 <?php
  if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
  }

  if (isset($_POST['sessionID'])) { // added by JD ??
    $sessionID = $_POST['sessionID'];
  }

  if (isset($_GET['rmchoice'])) {
    $rmchoice = $_GET['rmchoice'];
  }

  if (isset($_GET['kda_network_select'])) {
    $network = $_GET['kda_network_select'];
  } else {
    $network = '0';
  }

  if (isset($_GET['kda_species_select'])) {
    $species = $_GET['kda_species_select'];
  } else {
    $species = '0';
  }
  if (isset($_GET['radiogroup'])) {
    $choice_kda2pharm = $_GET['radiogroup'];
  }

  $fjson = "./Data/Pipeline/Resources/session/$sessionID" . "_kda2pharmomics.json";
  if (isset($_GET['kda_analysistype'])) {
    $analysis = $_GET['kda_analysistype'];
    if ($analysis == 1) {
      $femail = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "email";
      $email_sent = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "sent_email";
    } else {
      $femail = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "email";
      $email_sent = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "sent_email";
    }
    $json = array();
    $json['session'] = $sessionID;
    $json['rmchoice'] = $rmchoice;
    $json['network_select'] = $network;
    $json['species_select'] = $species;
    $json['choice_kda2pharm'] = $choice_kda2pharm;
    $json['analysis'] = $analysis;
    $fp = fopen($fjson, 'w');
    fwrite($fp, json_encode($json));
    fclose($fp);
    chmod($fjson, 0777);
  } else {
    #Assuming it is a session loading if $analysistype is empty 
    $json = json_decode(file_get_contents($fjson));
    $sessionID = $json->session; // added by JD ??
    $network = $json->network_select;
    $species = $json->species_select;
    $choice_kda2pharm = $json->choice_kda2pharm;
    $analysis = $json->analysis;
  }

  if ($analysis == 1) {
    $geneinputfile = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_genes.txt";
  } else{
    $geneinputfile = "./Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_genes.txt";
  }

  $geneinputfilesize = filesize($geneinputfile);

  if (isset($_GET['kda2pharmemail'])) {
    $emailid = $_GET['kda2pharmemail'];
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
        $msea2pharmomics = $pharmomics_arr2[0];
        $kda2pharmomics = preg_replace('/\s+/', ' ', trim($pharmomics_arr2[1]));

        if ($rmchoice == 1) {
          return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|SSEAKDAtoPharmomics,1.25" . "\n";
        } else if ($rmchoice == 2) {
          return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|MSEAKDAtoPharmomics,1.25" . "\n";
        } else if ($rmchoice == 3) {
          return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|METAKDAtoPharmomics,1.25" . "\n";
        } else {
          return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|KDAtoPharmomics,1.25" . "\n";
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
   #kda2pharm_genes {
     table-layout: fixed;
     text-align: center;
   }

   #kda2pharm_genes td {
     word-wrap: break-word;
     vertical-align: top;
   }
 </style>

 <script type="text/javascript">
   var string = localStorage.getItem("on_load_session");
   console.log("kda2pharmomics_modul:" + string);
   var network = "<?php echo $network; ?>";
   var species = "<?php echo $species; ?>";
   var choice = "<?php echo $choice_kda2pharm; ?>";
   var analysis = "<?php echo $analysis; ?>";
 </script>

  <?php

  if ($geneinputfilesize == 0) {
  ?>
    <div class="alert alert-warning" style="margin: 0 auto; width: 50%;margin-top: 10px;font-size: 16px;text-align: center;">
    <i class="icon-warning-sign" style="margin-right: 6px;font-size: 16px;"></i>No genes for repositioning! If you chose the 'Significant (FDR<0.05) key drivers' options, you may not have any KDs that pass that threshold.
  </div>
   <?php
  } 
  ?>

 <h4 class="instructiontext" id="reviewtext">Please review the modules/genes you have selected in the overview chart below before executing the overlap based drug repositioning pipeline.</h4>

 <br>

 <?php

  if ($analysis == 1) {
  ?>
   <table class="table table-bordered review" style="text-align: center" ; id="kda2pharmtable">
     <thead>
       <tr>
         <th>Drug Repositioning Analysis</th>
         <th>Description</th>
         <th>Parameters</th>
       </tr>
     </thead>
     <tbody>
       <tr>
         <td rowspan='4' style="vertical-align: middle;">
           Network Based Drug Repositioning
         </td>
         <td>Network Selection
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
         <td>
           Species Selection
         </td>
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
           Module and Gene Selection
         </td>
         <td style="font-weight: bold;">
           <?php
            if ($choice_kda2pharm == 1)
              echo "All genes from the subnetwork";
            else if ($choice_kda2pharm == 2)
              echo "All genes from input modules in the subnetwork";
            else if ($choice_kda2pharm == 3)
              echo "Genes from specific modules in the subnetwork";
            else
              echo "Significant (FDR<0.05) key drivers";
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
   <table class="table table-bordered review" style="text-align: center;" id="kda2pharmtable">
     <thead>
       <tr>
         <th>Drug Repositioning Analysis</th>
         <th>Description</th>
         <th>Parameters</th>
       </tr>
     </thead>
     <tbody>
       <tr>
         <td rowspan='2' style="vertical-align: middle;">
           Overlap Based Drug Repositioning
         </td>
         <td>
           Module Selection
         </td>
         <td style="font-weight: bold;">
           <?php
            if ($choice_kda2pharm == 1)
              echo "All genes from the subnetwork";
            else if ($choice_kda2pharm == 2)
              echo "All genes from input modules in the subnetwork";
            else if ($choice_kda2pharm == 3)
              echo "Genes from specific modules in the subnetwork";
            else
              echo "Significant (FDR<0.05) key drivers";
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


 <div class="table-responsive" <?php if (isset($_GET['radiogroup']) ? $_GET['radiogroup'] : null) {
                                  $a = $_GET['radiogroup'];
                                  if ($a == 3) {
                                    echo "";
                                  } else {
                                    echo 'style="display:none;"';
                                  }
                                } else {
                                  echo 'style="display:none;"';
                                }  ?>>
   <table id="kda2pharm_genes" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
        if ($analysis == 1)
          $genefile = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_selectedmodules.txt";
        else
          $genefile = "./Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_selectedmodules.txt";


        $array = file($genefile, FILE_SKIP_EMPTY_LINES);

        ?>

       <?php foreach ($array as $line) {
          $line_array = explode("\t", $line);
          $moduleres = trim($line_array[0]);
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
   <?php if (isset($_GET['kda2pharmemail']) ? $_GET['kda2pharmemail'] : null) {
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

     <input type="text" name="kda2pharmemail" id="yourEmail_kda2pharm">

     <button type="button" class="button button-3d button-small nomargin" id="kda2pharmemailSubmit">Send email</button>
   <?php
    }

    ?>
 </h5>

 <br>



 <div style="text-align:center;">
   <?php
    if ($rmchoice == 1) {
      if ($analysis == 1) {
    ?>
       <button type="button" class="button button-3d button-large pipeline" id="runshinyapp2pipeline_kda">Run Network Based Drug Repositioning</button>
       <script type="text/javascript">
         $("#runshinyapp2pipeline_kda").on('click', function() {
           var network = <?php echo $network; ?>;
           var species = <?php echo $species; ?>;
           // $('#pharmOmicstab3').show();
           //$('#pharmOmicstab3').click();

           $('#mypharmOmics_review').load("/kda_runshinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&network=" + network + "&species=" + species + "&rmchoice=1&run=T");
           $('#pharmOmicstab2').html('Network Based Drug Repositioning Results');
           $("#pharmOmicstogglet").css("background-color", "#c5ebd4");
           $("#pharmOmicstogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 3B - PharmOmics</div>`);


           return false;

         });
       </script>

     <?php
      } else {
      ?>
       <button type="button" class="button button-3d button-large pipeline" id="runshinyapp3pipeline_kda">Run Overlap Based Drug Repositioning</button>
       <script type="text/javascript">
         $("#runshinyapp3pipeline_kda").on('click', function() {
           $('#mypharmOmics_review').load("/kda_runshinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&rmchoice=1&run=T");
           $('#pharmOmicstab2').html('Overlap Based Drug Repositioning Results');
           $("#pharmOmicstogglet").css("background-color", "#c5ebd4");
           $("#pharmOmicstogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 3B - PharmOmics</div>`);


           return false;

         });
       </script>

     <?php
      }
    } else if ($rmchoice == 2) {
      if ($analysis == 1) {
      ?>

       <button type="button" class="button button-3d button-large pipeline" id="runshinyapp2pipeline_kda">Run Network Based Drug Repositioning</button>
       <script type="text/javascript">
         $("#runshinyapp2pipeline_kda").on('click', function() {
           var network = <?php echo $network; ?>;
           var species = <?php echo $species; ?>;
           // $('#pharmOmicstab3').show();
           //$('#pharmOmicstab3').click();
           $('#myKDA2PHARM_review').load("/kda_runshinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&network=" + network + "&species=" + species + "&rmchoice=2&run=T");
           $('#KDA2PHARMtab2').html('Network Based Drug Repositioning Results');
           $("#KDA2PHARMtogglet").css("background-color", "#c5ebd4");
           $("#KDA2PHARMtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2B - PharmOmics</div>`);


           return false;

         });
       </script>

     <?php
      } else {
      ?>
       <button type="button" class="button button-3d button-large pipeline" id="runshinyapp3pipeline_kda">Run Overlap Based Drug Repositioning</button>
       <script type="text/javascript">
         $("#runshinyapp3pipeline_kda").on('click', function() {
           $('#myKDA2PHARM_review').load("/kda_runshinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&rmchoice=2&run=T");
           $('#KDA2PHARMtab2').html('Overlap Based Drug Repositioning Results');
           $("#KDA2PHARMtogglet").css("background-color", "#c5ebd4");
           $("#KDA2PHARMtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2B - PharmOmics</div>`);


           return false;

         });
       </script>

     <?php
      }
    } else if ($rmchoice == 3) {
      if ($analysis == 1) {
      ?>
       <button type="button" class="button button-3d button-large pipeline" id="runshinyapp2pipeline_kda">Run Network Based Drug Repositioning</button>
       <script type="text/javascript">
         $("#runshinyapp2pipeline_kda").on('click', function() {
           var network = <?php echo $network; ?>;
           // $('#pharmOmicstab3').show();
           //$('#pharmOmicstab3').click();
           $('#myMETAKDA2PHARM_review').load("/kda_runshinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&network=" + network + "&species=" + species + "&rmchoice=3&run=T");
           $('#METAKDA2PHARMtab2').html('Network Based Drug Repositioning Results');
           $("#METAKDA2PHARMtogglet").css("background-color", "#c5ebd4");
           $("#METAKDA2PHARMtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2B - PharmOmics</div>`);


           return false;

         });
       </script>

     <?php
      } else {
      ?>
       <button type="button" class="button button-3d button-large pipeline" id="runshinyapp3pipeline_kda">Run Overlap Based Drug Repositioning</button>
       <script type="text/javascript">
         $("#runshinyapp3pipeline_kda").on('click', function() {

           $('#myMETAKDA2PHARM_review').load("/kda_runshinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&rmchoice=3&run=T");
           $('#METAKDA2PHARMtab2').html('Overlap Based Drug Repositioning Results');
           $("#METAKDA2PHARMtogglet").css("background-color", "#c5ebd4");
           $("#METAKDA2PHARMtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2B - PharmOmics</div>`);
           return false;

         });
       </script>

     <?php
      }
    } else {
      if ($analysis == 1) {
      ?>

       <button type="button" class="button button-3d button-large pipeline" id="runshinyapp2pipeline_kda">Run Network Based Drug Repositioning</button>
       <script type="text/javascript">
         $("#runshinyapp2pipeline_kda").on('click', function() {
           var network = <?php echo $network; ?>;
           // $('#pharmOmicstab3').show();
           //$('#pharmOmicstab3').click();
           $('#myKDASTART2PHARM_review').load("/kda_runshinyapp2.php?sessionID=" + localStorage.getItem("on_load_session") + "&network=" + network + "&species=" + species + "&rmchoice=4&run=T");
           $('#KDASTART2PHARMtab2').html('Network Based Drug Repositioning Results');
           $("#KDASTART2PHARMtogglet").css("background-color", "#c5ebd4");
           $("#KDASTART2PHARMtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1B - PharmOmics</div>`);


           return false;

         });
       </script>

     <?php
      } else {
      ?>
       <button type="button" class="button button-3d button-large pipeline" id="runshinyapp3pipeline_kda">Run Overlap Based Drug Repositioning</button>
       <script type="text/javascript">
         $("#runshinyapp3pipeline_kda").on('click', function() {
           $('#myKDASTART2PHARM_review').load("/kda_runshinyapp3.php?sessionID=" + localStorage.getItem("on_load_session") + "&rmchoice=4&run=T");
           $('#KDASTART2PHARMtab2').html('Overlap Based Drug Repositioning Results');
           $("#KDASTART2PHARMtogglet").css("background-color", "#c5ebd4");
           $("#KDASTART2PHARMtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1B - PharmOmics</div>`);


           return false;

         });
       </script>

   <?php
      }
    }

    ?>

 </div>
 <div id="emailconfirm_kda2pharm"></div>
 <div id="kda2pharmloading"></div>

  <?php
  } 
  ?>


 <script type="text/javascript">
   $('#kda2pharm_genes').dataTable({
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
  if ($rmchoice == 1) {
  ?>
   <script type="text/javascript">
     $("#kda2pharmemailSubmit").on('click', function() {
       var email = $("input[name=kda2pharmemail]").val();
       if (analysis == 1) {
         $('#mypharmOmics_review').empty();
         $('#mypharmOmics_review').load("/kda2pharmomics_moduleprogress.php?sessionID=" + string + "&kda2pharmemail=" + email + "&rmchoice=1" + "&kda_analysistype=1" + "&kda_network_select=" + network + "&radiogroup=" + choice);
       } else {

         $('#mypharmOmics_review').empty();
         $('#mypharmOmics_review').load("/kda2pharmomics_moduleprogress.php?sessionID=" + string + "&kda2pharmemail=" + email + "&rmchoice=1" + "&kda_analysistype=2" + "&radiogroup=" + choice);
       }

       return false;

     });
   </script>

 <?php
  } else if ($rmchoice == 2) {
  ?>
   <script type="text/javascript">
     $("#kda2pharmemailSubmit").on('click', function() {
       var email = $("input[name=kda2pharmemail]").val();
       if (analysis == 1) {
         $('#myKDA2PHARM_review').empty();
         $('#myKDA2PHARM_review').load("/kda2pharmomics_moduleprogress.php?sessionID=" + string + "&kda2pharmemail=" + email + "&rmchoice=2" + "&kda_analysistype=1" + "&kda_network_select=" + network + "&kda_species_select=" + species + "&radiogroup=" + choice);
       } else {
         $('#myKDA2PHARM_review').empty();
         $('#myKDA2PHARM_review').load("/kda2pharmomics_moduleprogress.php?sessionID=" + string + "&kda2pharmemail=" + email + "&rmchoice=2" + "&kda_analysistype=2" + "&radiogroup=" + choice);
       }

       return false;

     });
   </script>


 <?php
  } else if ($rmchoice == 3) {
  ?>
   <script type="text/javascript">
     $("#kda2pharmemailSubmit").on('click', function() {
       var email = $("input[name=kda2pharmemail]").val();
       if (analysis == 1) {
         console.log("SessionID" + string);
         $('#myMETAKDA2PHARM_review').empty();
         $('#myMETAKDA2PHARM_review').load("/kda2pharmomics_moduleprogress.php?sessionID=" + string + "&kda2pharmemail=" + email + "&rmchoice=3" + "&kda_analysistype=1" + "&kda_network_select=" + network + "&kda_species_select=" + species + "&radiogroup=" + choice);
       } else {
         console.log("SessionID" + string);
         $('#myMETAKDA2PHARM_review').empty();
         $('#myMETAKDA2PHARM_review').load("/kda2pharmomics_moduleprogress.php?sessionID=" + string + "&kda2pharmemail=" + email + "&rmchoice=3" + "&kda_analysistype=1" + "&radiogroup=" + choice); //analysistype should be 1?
       }

       return false;

     });
   </script>

 <?php
  } else {
  ?>
   <script type="text/javascript">
     $("#kda2pharmemailSubmit").on('click', function() {
       var email = $("input[name=kda2pharmemail]").val();
       if (analysis == 1) {
         $('#myKDASTART2PHARM_review').empty();
         $('#myKDASTART2PHARM_review').load("/kda2pharmomics_moduleprogress.php?sessionID=" + string + "&kda2pharmemail=" + email + "&rmchoice=4" + "&kda_analysistype=1" + "&kda_network_select=" + network + "&kda_species_select=" + species + "&radiogroup=" + choice);
       } else {
         $('#myKDASTART2PHARM_review').empty();
         $('#myKDASTART2PHARM_review').load("/kda2pharmomics_moduleprogress.php?sessionID=" + string + "&kda2pharmemail=" + email + "&rmchoice=4" + "&kda_analysistype=1" + "&radiogroup=" + choice);
       }

       return false;

     });
   </script>

 <?php
  }


  ?>