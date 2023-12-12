 <?php

  //This parameters files is for when the user reviews their MDF file


  /* Initialize PHP variables
sessionID = the saved session 

GET = if the user enters the link directly
POST = if PHP enters the link

*/
  if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
  }
  if (isset($_GET['marker_association'])) {
    $marker_association = $_GET["marker_association"];
  }

  if (isset($_GET['mapping'])) {
    $mapping = $_GET["mapping"];
  }
  if (isset($_GET['enrichment'])) {
    $enrichment = $_GET["enrichment"];
  }

  if (isset($_GET['mdf'])) {
    $mdf = $_GET["mdf"];
  }

  if (isset($_GET['mdf_ntop'])) {
    $mdf_ntop = $_GET['mdf_ntop'];
  }

  if (isset($_GET['MMFConvert'])) {
    $MMFConvert = $_GET['MMFConvert'];
  }

  //stored variable for later use
  $fpath = "./Data/Pipeline/Resources/ldprune_temp/$sessionID";

  /* 
This grabs the email from the email form and stores it in a variable
*/
  if (isset($_GET['email'])) {
    $emailid = $_GET['email'];
  } else {
    $emailid = "";
  }

  if ($emailid != "") {
    $emailid .= "\n";
  }

  /* 
Sets path to email file and sent_email file
*/
  $femail = "./Data/Pipeline/Results/ld_prune_email/$sessionID" . "email";
  $email_sent = "./Data/Pipeline/Results/ld_prune_email/$sessionID" . "sent_email";

  //Doug added this. I don't think it's needed. You will always get the email regardless.
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


  /***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we create a txt file with the path information
   ***************************************/

  //paths of the sessionID and POST data
  $fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
  $fpostOut = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_MDF_postdata.txt";
  if (file_exists($fsession)) {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "1")) { //changes path from 1 --> 1.25
        return 'Mergeomics_Path:' . "\t" . "1.25" . "\n";
      }
      return $data;
    }
    //replace the data in the file with the 1.25
    $data = array_map('replace_a_line', $data);
    file_put_contents($fsession, implode('', $data));
  }

  $data = null;
  $fjson = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "data.json";
  if ($marker_association != null) {

    $json = array();

    //$fpath_random = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";

    //$num_iterations = file($fpath_random);
    //for ($i = 0; $i < (count($num_iterations)); $i++) {

    $json['session'] = $sessionID;

    if ($mdf != "0") {
      $json['mdf'] = $mdf;
      $json['mdf_ntop'] = $mdf_ntop;
    }
    $json['association'] = $marker_association;
    if ($mapping == "0") {
      $mapping = "None Provided";
    }
    $json['marker'] = $mapping;
    $json['enrichment'] = $enrichment;
    $json['MMFConvert'] = $MMFConvert;

    if (empty($data->data)) {
      $data['data'][] = $json;
    } else {
      $data->data[] = $json;
    }
    $fp = fopen($fjson, 'w');
    fwrite($fp, json_encode($data));
    fclose($fp);
    chmod($fjson, 0777);
  } else {
    $json = json_decode(file_get_contents($fjson))->data;
    $mdf = $json[0]->mdf;
    $mdf_ntop = $json[0]->mdf_ntop;
    $marker_association = $json[0]->association;
    $mapping = $json[0]->marker;
  }
  $mapping_val ="";
  foreach ($mapping as &$value) {
    //$newMappingcontent .= readMappingFile($value);
    $mapping_val .= ", " . basename($value);
  }
  $mapping_val = substr($mapping_val, 2);
  ?>

 <!--Instruction text that displays at the top ------->
 <h4 class="instructiontext" id="reviewtext">Please review the files you have selected/uploaded and the parameters you have selected in the overview chart below before executing the MDF pipeline.</h4>
 <br>


 <!--Start Review table ------->
 <table class="table table-bordered review" style="text-align: center" ; id="reviewtable">
   <thead>
     <tr>
       <!--First row of column headers ------->
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
       <!--Association data row ------->
       <td rowspan="3" style="vertical-align: middle;">Files</td>
       <td>Association Data</td>
       <td style="font-weight: bold;">
         <!--Outputs data from the MARKER file ------->
         <?php
          echo basename($marker_association);
          $overview_write .= "Association Data" . "\t" . basename($marker_association) . "\n";
          ?>
       </td>
     </tr>
     <tr>
       <!--Marking Mapping data row ------->
       <td>Marking Mapping Data</td>
       <td style="font-weight: bold;">
         <!--Outputs data from the MAPPING file ------->
         <?php
          echo $mapping_val;
          $overview_write .= "Marker Mapping Data" . "\t" . $mapping_val . "\n";
          ?>
       </td>
     </tr>
     <tr>
       <!--Marking Dependency File row ------->
       <td>Marker Dependency File</td>
       <td style="font-weight: bold;">
         <!--Outputs data from the LINKAGE file ------->
         <?php
          echo basename($mdf);
          $overview_write .= "Marker Dependency File" . "\t" . basename($mdf) . "\n";
          ?>
       </td>
     </tr>
     <tr>
       <!--Parameters------->
       <td rowspan="1">Parameters</td>
       <td>Percentage of Markers</td>
       <td style="font-weight: bold;">
         <?php
          echo "$mdf_ntop";
          $overview_write .= "Percentage of Markers" . "\t" . "$mdf_ntop" . "\n";
          ?>

       </td>
     </tr>
   </tbody>
 </table>

 <?php
  /*This creates a review txt that the user can download if they like*/
  $overview_fp = "./Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_overview.txt";
  $overview_file = fopen($overview_fp, "w");
  fwrite($overview_file, $overview_write);
  fclose($overview_file);
  chmod($overview_fp, 0777);
  ?>

 <br>
 <br>
 <!------------------------------------------------------------------------------------------
Email div block
Users can enter their email. It will refresh the page with a GET if they click enter email.
--------------------------------------------------------------------------------------------->

 <h5 style="color: #00004d;">Enter your e-mail id for job completion notification (Recommended)
   <?php
    /*This checks if the email exists or not. If it does, then give a success notifcation  */
    if (isset($_GET['email']) ? $_GET['email'] : null) {
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

     <input type="text" name="email" id="yourEmail">

     <button type="button" class="button button-3d button-small nomargin" id="emailSubmit">Send email</button>
   <?php
    }

    ?>
 </h5>

 <br>

 <!------------------------------------------------------------------------------------------
Submit div block
Users submits to run their job
--------------------------------------------------------------------------------------------->
 <div style="text-align:center;">
   <button type="button" class="button button-3d button-large nomargin" id="RunMDFPipeline">Run MDF Pipeline</button>
 </div>
 <!-- These divs are needed to enter some preloading information ---->
 <div id="emailconfirm"></div>
 <div id="MDFloading"></div>




 <script type="text/javascript">
   var session_id = "<?php echo $sessionID ?>"
   $("#Validatebutton").html('Click to Review');
   /**********************************************************************************************
Javascript functions/scripts (These are inlined because it was easier to do)
You can technically extract it and just call it externally if you want to keep the php page cleaner, but not needed
***********************************************************************************************/

   /*This is the email submit event listener. Will reload the page with the email*/
   //  $("#MSEAemailSubmit").on('click', function() {
   $("#emailSubmit").on('click', function(e) {
     var email = $("input[name=email]").val();
     $('#myLDPrune_review').empty();
     $('#myLDPrune_review').load("/MDF_moduleprogress.php?sessionID=" + session_id + "&email=" + email);
     e.preventDefault();
     return false;

   });

   /*This is the submit event listener. Will load run_MDF */
   $("#RunMDFPipeline").on('click', function() {
     $('#myLDPrune_review').load("/run_MDF.php?sessionID=" + session_id + "&run=T");
     $('#MDFtab2').html('Results');
     $("#MDFtogglet").css("background-color", "#c5ebd4");
     $("#MDFtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1 - Marker Dependency Filtering<div>`);
     return false;
   });
   //})
 </script>