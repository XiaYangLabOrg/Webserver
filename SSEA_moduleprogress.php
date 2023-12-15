 <?php
  //This parameters files is for when the user reviews their SSEA file
  $ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
  function debug_to_console($data)
  {
    $output = $data;
    if (is_array($output))
      $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }
  /* Initialize PHP variables
sessionID = the saved session 

GET = if the user enters the link directly
POST = if PHP enters the link

*/
debug_to_console("line19");
  if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
  }
  if (isset($_GET['marker_association'])) {
    $marker_association = $_GET["marker_association"];
  }

  if (isset($_GET['mapping'])) {
    $mapping = $_GET["mapping"];
  }

  if (isset($_GET['module'])) {
    $module = $_GET["module"];
  }

  if (isset($_GET['module_info'])) {
    $module_info = $_GET["module_info"];
  }
  if (isset($_GET['perm_type'])) {
    $perm_type = $_GET["perm_type"];
  }
  if (isset($_GET['max_gene'])) {
    $max_gene = $_GET["max_gene"];
  }
  if (isset($_GET['min_gene'])) {
    $min_gene = $_GET["min_gene"];
  }
  if (isset($_GET['maxoverlap'])) {
    $maxoverlap = $_GET["maxoverlap"];
  }
  if (isset($_GET['minoverlap'])) {
    $minoverlap = $_GET["minoverlap"];
  }
  if (isset($_GET['sseanperm'])) {
    $sseanperm = $_GET["sseanperm"];
  }
  if (isset($_GET['sseafdr'])) {
    $sseafdr = $_GET["sseafdr"];
  }

  if (isset($_GET['enrichment'])) {
    $enrichment = $_GET["enrichment"];
  }

  if (isset($_GET['GSETConvert'])) {
    $GSETConvert = $_GET["GSETConvert"];
  }

  if (isset($_GET['MMFConvert'])) {
    $MMFConvert = $_GET["MMFConvert"];
  } else{
    $MMFConvert = "none";
  }

  debug_to_console("line74");
  //stored variable for use later
  $fpath = "./Data/Pipeline/Resources/ssea_temp/$sessionID";
  //change the file path if the user has skipped MDF in the GWAS pipeline
  // if (isset($_GET['skippedMDF'])) {
  //   $marker = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_marker";
  //   $mapping = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_mapping";
  //   if (file_exists($marker)) {
  //     $txt = trim(file_get_contents($marker));
  //     $fpathloci = $fpath . "LOCI";
  //     $myfile = fopen($fpathloci, "w");
  //     fwrite($myfile, $ROOT_DIR . "Data/Pipeline/" . $txt);
  //     fclose($myfile);
  //     chmod($fpathloci, 0644);
  //   }
  //   if (file_exists($mapping)) {
  //     $txt = trim(file_get_contents($mapping));
  //     $fpathloci = $fpath . "GWAS_file_list";
  //     $myfile = fopen($fpathloci, "w");
  //     fwrite($myfile, $ROOT_DIR . "Data/Pipeline/" . $txt);
  //     fclose($myfile);
  //     chmod($fpathloci, 0644);
  //   }
  // }


  // $mapping_val = "";
  // $newMappingcontent="GENE\tMARKER\n";
  // function readMappingFile($path) {
  //   $handle = fopen($path, "r");
  //   $content="";
  //   if ($handle) {
  //     while (($line = fgets($handle)) !== false) {
  //       if (strpos($line, 'GENE') !== true) {
  //         $content.=$line;
  //       }
  //     }
  //   fclose($handle);
  //   return $content;
  // }



  // $newMappingfile = "./Data/Pipeline/Resources/ssea_temp/" . $sessionID . ".mappingfile.txt";
  // $fp = fopen($newMappingfile, 'w');
  // fwrite($fp, json_encode($newMappingcontent));
  // fclose($fp);
  /* 
This grabs the email from the email form and stores it in a variable
*/
  if (isset($_GET['SSEAemail'])) {
    $emailid = $_GET['SSEAemail'];
  } else {
    $emailid = "";
  }
  if ($emailid != "") {
    $emailid .= "\n";
  }
  /* 
Sets path to email file and sent_email file
*/
  $femail = "./Data/Pipeline/Results/ssea_email/$sessionID" . "email";
  $email_sent = "./Data/Pipeline/Results/ssea_email/$sessionID" . "sent_email";

  //Doug added this. I don't think it's needed. You will always get the email regardless.
  if ($emailid != "") {
    $parts = explode("@", $emailid);
    $name = $parts[0];
    $domain = $parts[1];
    //change "ucla.edu" to "g.ucla.edu"
    if (trim($domain) == 'ucla.edu') {
      $newid = "$name" . "@g.ucla.edu";
    } else {
      $newid = $emailid;
    }
    $myfile = fopen($femail, "w");
    fwrite($myfile, $newid);
    fclose($myfile);
  }


  debug_to_console("line155");
  /***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we create a txt file with the path information
   ***************************************/

  //paths of the sessionID and POST data
  $fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
  if (file_exists($fsession)) //check if the session.txt file actually exists (it should since this is a moduleprogress page)
  {
    $session = explode("\n", file_get_contents($fsession));
    //Create different array elements based on new line
    $pipe_arr = preg_split("/[\t]/", $session[0]);
    $pipeline = $pipe_arr[1];
    //check if the user has skipped MDF or not.
    if ($pipeline == "GWASskipped") {
      $data = file($fsession); // reads an array of lines
      function replace_a_line($data)
      { //if the user has skipped MDF, then change path from 1 --> 1.25
        if (stristr($data, 'Mergeomics_Path:' . "\t" . "1")) {
          return 'Mergeomics_Path:' . "\t" . "1.25" . "\n";
        }
        return $data;
      }
      $data = array_map('replace_a_line', $data);
      file_put_contents($fsession, implode('', $data));
    } else {
      //if the user has not skipped MDF (1-1.75), then they already went through MDF and now are at SSEA (2)

      $data = file($fsession); // reads an array of lines
      function replace_a_line($data)
      { //if the user has skipped MDF, then change path from 2 --> 2.25
        if (stristr($data, 'Mergeomics_Path:' . "\t" . "2")) {
          return 'Mergeomics_Path:' . "\t" . "2.25" . "\n";
        }
        return $data;
      }
      $data = array_map('replace_a_line', $data);
      file_put_contents($fsession, implode('', $data));
    }
  }
  debug_to_console("line197");
  $fjson = "./Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";

  $data = null;
  //NOt from session load
  if ($marker_association != null) { 
  //if ($module != null) {
    if (file_exists($fjson)) {
      $json = json_decode(file_get_contents($fjson), true)->data[0];
    } else {
      $json = array();
    }
    $json['session'] = $sessionID;
    //$fpathOut = "./Data/Pipeline/Resources/meta_temp/$new_random_string" . "PARAM";

    //$fdr_file = "./Data/Pipeline/Resources/meta_temp/$new_random_string" . "PARAM_SSEA_FDR";
    $json['perm'] = $perm_type;
    $json['maxgenes'] = $max_gene;
    $json['mingenes'] = $min_gene;
    $json['minoverlap'] = $minoverlap;
    $json['maxoverlap'] = $maxoverlap;
    $json['numperm'] = $sseanperm;
    $json['fdrcutoff'] = $sseafdr;
    $json['GSETConvert'] = $GSETConvert;
    $json['MMFConvert'] = $MMFConvert;
    $json['association'] = $marker_association;
    $json['marker'] = $mapping;
    $json['geneset'] =  $module;
    $json['enrichment'] = $enrichment;
    if ($module_info == "no") {
      $module_info = "None Provided";
    }
    $json['genedesc'] = $module_info;

    $data['data'][] = $json;
    // if (empty($data->data)) {
    //   $data['data'][] = $json;
    // } else {
    //   $data->data[] = $json;
    // }
    $fp = fopen($fjson, 'w');
    fwrite($fp, json_encode($data));
    fclose($fp);
    chmod($fjson, 0777);
  } else {
    //From session load
    $json = json_decode(file_get_contents($fjson))->data;
    // $perm_type = $json[0]->perm;
    // $max_gene = $json[0]->maxgenes;
    // $min_gene = $json[0]->mingenes;
    // $minoverlap = $json[0]->minoverlap;
    // $maxoverlap = $json[0]->maxoverlap;
    // $sseanperm = $json[0]->numperm;
    // $sseafdr = $json[0]->fdrcutoff;
    // $marker_association = $json[0]->association;
    // $mapping = $json[0]->marker;
    // $module = $json[0]->geneset;
    // $enrichment = $json[0]->enrichment;
    // $module_info = $json[0]->genedesc;

    $perm_type = $json[0]["perm"];
    $max_gene = $json[0]["maxgenes"];
    $min_gene = $json[0]["mingenes"];
    $minoverlap = $json[0]["minoverlap"];
    $maxoverlap = $json[0]["maxoverlap"];
    $sseanperm = $json[0]["numperm"];
    $sseafdr = $json[0]["fdrcutoff"];
    $marker_association = $json[0]["association"];
    $mapping = $json[0]["marker"];
    $module = $json[0]["geneset"];
    $enrichment = $json[0]["enrichment"];
    $module_info = $json[0]["genedesc"];
  }
  debug_to_console("line256");
  if (count($mapping) > 1) {
    debug_to_console("line272");
    foreach ($mapping as &$value) {
      //$newMappingcontent .= readMappingFile($value);
      $mapping_val .= ", " . basename($value);
    }
    debug_to_console("line277");
    $mapping_val = substr($mapping_val, 2);
  } else {
    debug_to_console("line280");
    if (gettype($mapping) == "array") {
      debug_to_console("line282");
      $mapping_val = basename($mapping[0]);
    } else {
      debug_to_console("line285");
      $mapping_val = basename($mapping);
    }
  }


  ?>

 <!--Instruction text that displays at the top ------->
 <h4 class="instructiontext" id="reviewtext">Please review the files you have selected/uploaded and the parameters you have selected in the overview chart below before executing the MSEA pipeline.</h4>
 <br>

 <!--Start Review table ------->
 <table class="table table-bordered review" style="text-align: center" ; id="SSEAreviewtable">
   <thead>
     <tr>
       <!--First row of column headers ------->
       <th>Type</th>
       <th>Description</th>
       <th>Filename/Parameters</th>

       <?php
        $overview_write = NULL;
        $overview_write .= "Description" . "\t" . "Filename/Parameter" . "\n";
        debug_to_console("line305");
        ?>
     </tr>
   </thead>
   <tbody>
     <tr>
       <!--Association data row ------->
       <td rowspan="4" style="vertical-align: middle;">Files</td>
       <td>Association Data</td>
       <td style="font-weight: bold;">
         <!--Outputs data from the LOCI file ------->
         <?php
          debug_to_console("line317");
          echo basename($marker_association);
          $overview_write .= "Association Data" . "\t" . basename($marker_association) . "\n";
          debug_to_console("line318");
          ?>
       </td>
     </tr>
     <tr>
       <!-- Marking Mapping Data row ------->
       <td>Marking Mapping Data</td>
       <td style="font-weight: bold;">
         <!--Outputs data from the GWAS_file_list file ------->
         <?php

          //echo $mapping_val;
          //echo basename($mapping);
          $overview_write .= "Marker Mapping Data" . "\t" . $mapping_val . "\n";
          ?>
       </td>
     </tr>
     <tr>
       <!-- Gene Set row ------->
       <td>Gene Sets</td>
       <td style="font-weight: bold;">
         <!--Outputs data from the MODULE file ------->
         <?php
          echo basename($module);
          debug_to_console("line342");
          $overview_write .= "Gene Sets" . "\t" . basename($module) . "\n";
          ?>
       </td>
     </tr>
     <tr>
       <!-- Gene Set Description row ------->
       <td>Gene Sets Description</td>
       <td style="font-weight: bold;">
         <!--Outputs data from the DESC file ------->
         <?php
          debug_to_console("line353");
          $fpathmod = $fpath . "DESC";
          if ($module_info == "None Provided") {
            echo "None Selected";
            $overview_write .= "Gene Sets Description" . "\t" . "None Provided" . "\n";
          } else {
            echo basename($module_info);
            $overview_write .= "Gene Sets Description" . "\t" . basename($module_info) . "\n";
          }
          debug_to_console("line362");
          ?>
       </td>
     </tr>
     <!-- Parameters row ------->
     <tr>

       <td rowspan="7" style="vertical-align: middle;">Parameters</td>
       <!-- Permutation column ------->
       <td>Permutation Type</td>
       <td style="font-weight: bold;">
         <?php
         debug_to_console("line374");
          if ($perm_type == "locus") {
            echo "marker";
            $overview_write .= "Permutation Type" . "\tmarker\n";
          } else { //gene
            echo "$perm_type";
            $overview_write .= "Permutation Type" . "\t" . trim("$perm_type") . "\n";
          }
          debug_to_console("line382");
          ?>
       </td>
     </tr>
     <tr>
       <td>
         Max Genes in Gene Sets
       </td>
       <!-- Max Genes in Gene Sets column ------->
       <td style="font-weight: bold;">
         <?php
          echo "$max_gene";
          $overview_write .= "Max Genes in Gene Sets" . "\t" . trim("$max_gene") . "\n";
          debug_to_console("line395");
         ?>
       </td>
     </tr>
     <tr>
       <td>
         Min Genes in Gene Sets
       </td>
       <!-- Min Genes in Gene Sets column ------->
       <td style="font-weight: bold;">
         <?php
          echo "$min_gene";
          $overview_write .= "Min Genes in Gene Sets" . "\t" . trim("$min_gene") . "\n"; 
          debug_to_console("line408");
          ?>
       </td>
     </tr>
     <tr>
       <td>
         Max Overlap Allowed for Merging
       </td>
       <!-- Max Overlap Allowed for Merging column ------->
       <td style="font-weight: bold;">
         <?php
          echo "$maxoverlap";
          $overview_write .= "Max Overlap Allowed for Merging" . "\t" . trim("$maxoverlap") . "\n"; 
          debug_to_console("line421");
          ?>
       </td>
     </tr>
     <tr>
       <td>
         Min Module Overlap Allowed for Merging
       </td>
       <!--  Min Overlap Allowed for Merging column ------->
       <td style="font-weight: bold;">
         <?php
          echo "$minoverlap";
          $overview_write .= "Min Module Overlap Allowed for Merging" . "\t" . trim("$minoverlap") . "\n"; 
          debug_to_console("line434");
          ?>
       </td>
     </tr>
     <tr>
       <td>
         Number of Permutations
       </td>
       <!--  Number of Permutations column ------->
       <td style="font-weight: bold;">
         <?php
          echo "$sseanperm";
          $overview_write .= "Number of Permutations" . "\t" . trim("$sseanperm") . "\n"; ?>
       </td>
     </tr>
     <tr>
       <td>
         MSEA to KDA export FDR cutoff
       </td>
       <!--  MSEA FDR Cutoff column ------->
       <td style="font-weight: bold;">
         <?php
          echo "$sseafdr";
          $overview_write .= "MSEA to KDA export FDR cutoff" . "\t" . trim("$sseafdr") . "\n"; 
          debug_to_console("line458");
          ?>
       </td>
     </tr>
   </tbody>
 </table>


 <?php
  /*This creates a review txt that the user can download if they like*/
  $overview_fp = "./Data/Pipeline/Results/ssea/" . "$sessionID" . "_overview.txt";
  $overview_file = fopen($overview_fp, "w");
  fwrite($overview_file, $overview_write);
  fclose($overview_file);
  chmod($overview_fp, 0777);
  debug_to_console("line473");
  ?>

 <br>
 <br>

 <!------------------------------------------------------------------------------------------
Email div block
Users can enter their email. It will refresh the page with a GET if they click enter email.
--------------------------------------------------------------------------------------------->
 <h5 style="color: #00004d;">Enter your e-mail id for job completion notification (Recommended)
   <?php
   debug_to_console("line485");
    /*This checks if the email exists or not. If it does, then give a success notifcation  */
    if (isset($_GET['SSEAemail']) ? $_GET['SSEAemail'] : null) {
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

     <input type="text" name="SSEAemail" id="yourEmail_SSEA">

     <button type="button" class="button button-3d button-small nomargin" id="SSEAemailSubmit">Send email</button>
   <?php
    }
    debug_to_console("line505");
    ?>
 </h5>

 <br>
 <!------------------------------------------------------------------------------------------
Submit div block
Users submits to run their job
--------------------------------------------------------------------------------------------->

 <div style="text-align:center;">
   <button type="button" class="button button-3d button-large nomargin" id="RunSSEAPipeline">Run MSEA Pipeline</button>
 </div>
 <!-- These divs are needed to enter some preloading information ---->
 <div id="emailconfirm_SSEA"></div>
 <div id="SSEAloading"></div>


 <script type="text/javascript">
   /**********************************************************************************************
Javascript functions/scripts (These are inlined because it was easier to do)
You can technically extract it and just call it externally if you want to keep the php page cleaner, but not needed
***********************************************************************************************/
   var string = "<?php echo $sessionID; ?>"; //get sessionID and store to javascript variable

  $("#Validatebutton_SSEA").html('Click to Review');

   $('html,body').animate({
     scrollTop: $("#SSEAtoggle").offset().top
   }); //scroll to the bottom

   /*This is the email submit event listener. Will reload the page with the email*/
   $("#SSEAemailSubmit").on('click', function(e) {
     var email = $("input[name=SSEAemail]").val();
     $('#mySSEA_review').empty();
     $('#mySSEA_review').load("/SSEA_moduleprogress.php?sessionID=" + string + "&SSEAemail=" + email);
     e.preventDefault();
     return false; //stops page from refreshing

   });

   /*This is the submit event listener. Will load run_SSEA */
   $("#RunSSEAPipeline").on('click', function() {
     $('#mySSEA_review').load("/run_SSEA.php?sessionID=" + string + "&run=T"); //load run_SSEA with the sessionID
     $('#SSEAtab2').html('Results'); //Write "Result" to the second tab 
     $("#SSEAtogglet").css("background-color", "#c5ebd4"); //Change toggle from to Green
     $("#SSEAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2 - Marker Set Enrichment Analysis</div>`); //change toggle to the "ok circle icon"                
     return false; //stops page from refreshing

   });
 </script>