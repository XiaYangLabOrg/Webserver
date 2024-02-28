<?php
include 'functions.php';
if (isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {
  $sessionID = $_GET['sessionID'];
  $fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
  if (!file_exists($fsession)) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
      // this is ajax request, do something
      echo "Session ID does not exist!";
      exit;
    } else {
      //this is not an ajax request
      header("Location: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?message=invalid");
      exit;
    }
  }
  // Create an array of the current session file
  $session = explode("\n", file_get_contents($fsession));
  //Create different array elements based on new line
  $pipe_arr = preg_split("/[\t]/", $session[0]);
  $pipeline = preg_replace('/\s+/', ' ', trim($pipe_arr[1]));
  $mergeomics_arr = preg_split("/[\t]/", $session[1]);
  $mergeomics_path = $mergeomics_arr[1];
  $pharmomics_arr = preg_split("/[\t]/", $session[2]);
  $pharmomics_arr2 = explode("|", $pharmomics_arr[1]);
  $ssea2pharmomics = explode(",", $pharmomics_arr2[0]);
  $ssea2pharmomics_pipeline = $ssea2pharmomics[0];
  $ssea2pharmomics_path = $ssea2pharmomics[1];
  $wkda2apharmomics = explode(",", $pharmomics_arr2[1]);
  $wkda2pharmomics_pipeline = $wkda2apharmomics[0];
  $wkda2pharmomics_path = $wkda2apharmomics[1];
  $pharmomics_split = explode("|", $pharmomics_arr[1]);
  $msea2pharm_path = $pharmomics_split[0];
  $kda2pharm_path = $pharmomics_split[1];

  if (substr($pipeline, 0, 10) === "Pharmomics") {
    header("Location: /runpharmomics.php?sessionID=" . $sessionID);
  }


  if ($pipeline == "GWAS") {
    $mdf_arr = preg_split("/[\t]/", $session[3]);
    $mdfskipped = $mdf_arr[1];
    $rmchoice = 1;
  } else if ($pipeline == "GWASskipped") {
    $rmchoice = 1;
  } else if ($pipeline == "MSEA") {
    $rmchoice = 2;
  } else if ($pipeline == "META") {
    $rmchoice = 3;
  }
  $fjson = "./Data/Pipeline/Resources/session/$sessionID" . "_pharmomics.json";
  if (file_exists($fjson)) {
    $json = json_decode(file_get_contents($fjson));
    $rmchoice = $json->rmchoice;
    $network = $json->network_select;
    $species = $json->species_select;
    $module = $json->modulegroup;
    $gene = $json->genegroup;
    $measure = $json->sig_measure;
    $threshold = $json->sig_threshold;
    $analysis = $json->analysis;
    if ($analysis == 1) {
      if ($rmchoice == 1) {
        $ssea2pharm_1_5 = '$("#myssea2pharm_review").load("/ssea_runshinyapp2.php?sessionID=' . $sessionID . '&network=' . $network . '&species=' . $species . '&rmchoice=' . $rmchoice . '");
                          $("#ssea2pharmtab2").html("Network Based Drug Repositioning Results");
                          $("#ssea2pharmtogglet").css("background-color", "#c5ebd4");
                          $("#ssea2pharmtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
        $ssea2phar_1_75 = '$("#myssea2pharm_review").load("/result_shinyapp2.php?sessionID=' . $sessionID . '&type=ssea&signature=1");';
      } else if ($rmchoice == 2) {
        $msea2pharm_1_5 = '$("#mymsea2pharm_review").load("/ssea_runshinyapp2.php?sessionID=' . $sessionID . '&network=' . $network . '&species=' . $species . '&rmchoice=' . $rmchoice . '");
                          $("#msea2pharmtab2").html("Network Based Drug Repositioning Results");
                          $("#msea2pharmtogglet").css("background-color", "#c5ebd4");
                          $("#msea2pharmtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
        $msea2phar_1_75 = '$("#mymsea2pharm_review").load("/result_shinyapp2.php?sessionID=' . $sessionID . '&type=msea&signature=1");';
      } else {
        $meta2pharm_1_5 = '$("#myMETAKDA2PHARM_review").load("/kda_runshinyapp2.php?sessionID=' . $sessionID . '&network=' . $network . '&species=' . $species . '&rmchoice=3");
                           $("#METAKDA2PHARMtab2").html("Network Based Drug Repositioning Results");
                           $("#METAKDA2PHARMtogglet").css("background-color", "#c5ebd4");
                           $("#METAKDA2PHARMtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
      }
    } else {
      if ($rmchoice == 1) {
        $ssea2pharm_1_5 = '$("#myssea2pharm_review").load("/ssea_runshinyapp3.php?sessionID=' . $sessionID . '&rmchoice=' . $rmchoice . '");
                          $("#ssea2pharmtab2").html("Overlap Based Drug Repositioning Results");
                          $("#ssea2pharmtogglet").css("background-color", "#c5ebd4");
                          $("#ssea2pharmtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
        $ssea2phar_1_75 = '$("#myssea2pharm_review").load("/result_shinyapp3.php?sessionID=' . $sessionID . '&type=ssea");';
      } else if ($rmchoice == 2) {
        $msea2pharm_1_5 = '$("#mymsea2pharm_review").load("/ssea_runshinyapp3.php?sessionID=' . $sessionID . '&rmchoice=' . $rmchoice . '");
                          $("#msea2pharmtab2").html("Overlap Based Drug Repositioning Results");
                          $("#msea2pharmtogglet").css("background-color", "#c5ebd4");
                          $("#msea2pharmtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
        $msea2phar_1_75 = '$("#mymsea2pharm_review").load("/result_shinyapp3.php?sessionID=' . $sessionID . '&type=msea");';
      } else {
        $meta2pharm_1_5 = '$("#myMETAKDA2PHARM_review").load("/kda_runshinyapp3.php?sessionID=' . $sessionID . '&rmchoice=3");
                           $("#METAKDA2PHARMtab2").html("Overlap Based Drug Repositioning Results");
                           $("#METAKDA2PHARMtogglet").css("background-color", "#c5ebd4");
                           $("#METAKDA2PHARMtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
      }
    }
  }


  $fjson = "./Data/Pipeline/Resources/session/$sessionID" . "_kda2pharmomics.json";
  if (file_exists($fjson)) {
    $json = json_decode(file_get_contents($fjson));
    $rmchoice = $json->rmchoice;
    $network = $json->network_select;
    $species = $json->species_select;
    $analysis = $json->analysis;
    $choice_kda2pharm = $json->choice_kda2pharm;
    if ($analysis == 1) {
      if ($rmchoice == 1) {
        $ssea2kda2pharm_1_5 = '$("#mypharmOmics_review").load("/kda_runshinyapp2.php?sessionID=' . $sessionID . '&network=' . $network . '&species=' . $species . '&rmchoice=1");
        $("#pharmOmicstab2").html("Network Based Drug Repositioning Results");
        $("#pharmOmicstogglet").css("background-color", "#c5ebd4");
        $("#pharmOmicstogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 3B - PharmOmics</div>");';
        $ssea2kda2pharm_1_75 = '$("#mypharmOmics_review").load("/result_shinyapp2.php?sessionID=' . $sessionID . '&type=wkda&signature=1");';
      } else if ($rmchoice == 2) {
        $msea2kda2pharm_1_5 = '$("#myKDA2PHARM_review").load("/kda_runshinyapp2.php?sessionID=' . $sessionID . '&network=' . $network . '&species=' . $species . '&rmchoice=2");
        $("#KDA2PHARMtab2").html("Network Based Drug Repositioning Results");
        $("#KDA2PHARMtogglet").css("background-color", "#c5ebd4");
        $("#KDA2PHARMtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
        $msea2kda2pharm_1_75 = '$("#myKDA2PHARM_review").load("/result_shinyapp2.php?sessionID=' . $sessionID . '&type=wkda&signature=1")';
      } else if ($rmchoice == 3) {
        $meta2kda2pharm_1_5 = '$("#myKDA2PHARM_review").load("/kda_runshinyapp2.php?sessionID=' . $sessionID . '&network=' . $network . '&species=' . $species . '&rmchoice=3");
        $("#KDA2PHARMtab2").html("Network Based Drug Repositioning Results");
        $("#KDA2PHARMtogglet").css("background-color", "#c5ebd4");
        $("#KDA2PHARMtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
      } else {
        $kda2pharm_1_5 = '$("#myKDA2PHARM_review").load("/kda_runshinyapp2.php?sessionID=' . $sessionID . '&network=' . $network . '&species=' . $species . '&rmchoice=4");
        $("#KDASTART2PHARMtab2").html("Network Based Drug Repositioning Results");
        $("#KDASTART2PHARMtab2").click();
        $("#KDASTART2PHARMtogglet").css("background-color", "#c5ebd4");
        $("#KDASTART2PHARMtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2B - PharmOmics</div>");';
      }
    } else {
      if ($rmchoice == 1) {
        $ssea2kda2pharm_1_5 = '$("#mypharmOmics_review").load("/kda_runshinyapp3.php?sessionID=' . $sessionID . '&rmchoice=1");
        $("#pharmOmicstab2").html("Overlap Based Drug Repositioning Results");
        $("#pharmOmicstogglet").css("background-color", "#c5ebd4");
        $("#pharmOmicstogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 3B - PharmOmics</div>");';
        $ssea2kda2phar_1_75 = '$("#mypharmOmics_review").load("/result_shinyapp3.php?sessionID=" + string + "&type=wkda");';
      } else if ($rmchoice == 2) {
        $msea2kda2pharm_1_5 = '$("#myKDA2PHARM_review").load("/kda_runshinyapp3.php?sessionID=' . $sessionID . '&rmchoice=2");
          $("#KDA2PHARMtab2").html("Overlap Based Drug Repositioning Results");
          $("#KDA2PHARMtogglet").css("background-color", "#c5ebd4");
          $("#KDA2PHARMtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 3B - PharmOmics</div>");';
        $msea2kda2pharm_1_75 = '$("#myKDA2PHARM_review").load("/result_shinyapp3.php?sessionID=' . $sessionID . '&type=wkda");';
      } else if ($rmchoice == 3) {
        $meta2kda2pharm_1_5 = '$("#myKDA2PHARM_review").load("/kda_runshinyapp3.php?sessionID=' . $sessionID . '&rmchoice=3");
          $("#KDA2PHARMtab2").html("Overlap Based Drug Repositioning Results");
          $("#KDA2PHARMtogglet").css("background-color", "#c5ebd4");
          $("#KDA2PHARMtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 3B - PharmOmics</div>");';
      } else {
        $kda2pharm_1_5 = '$("#myKDA2PHARM_review").load("/kda_runshinyapp3.php?sessionID=' . $sessionID . '&rmchoice=4");
          $("#KDASTART2PHARMtab2").html("Overlap Based Drug Repositioning Results");
          $("#KDASTART2PHARMtab2").click();
          $("#KDASTART2PHARMtogglet").css("background-color", "#c5ebd4");
          $("#KDASTART2PHARMtogglet").html("<i class=\"toggle-closed icon-ok-circle\"></i><i class=\"toggle-open icon-ok-circle\"></i><div class=\"capital\">Step 2 - PharmOmics</div>");';
      }
    }
  }



  //javascript controlling session reloading
  $json = json_encode(array(
    "GWAS" => array(
      "1" => '$("#myLDPrune").load("/MDF_parameters.php?sessionID=' . $sessionID . '");
              setTimeout(function(){
                $("#MDFtabheader").show();
              },300);',
      "1.25" => '$("#myLDPrune_review").load("/MDF_moduleprogress.php?sessionID=' . $sessionID . '");
              setTimeout(function(){
                $("#MDFtabheader").show();
                $("#MDFtab2").show();
                $("#MDFtab2").click();       
              }, 300);',
      "1.5" => '$("#myLDPrune_review").load("/run_MDF.php?sessionID=' . $sessionID . '");
              setTimeout(function(){
                $("#MDFtabheader").show();
                $("#MDFtab2").show();
                $("#MDFtab2").click();       
              }, 300);',
      "1.75" => '$("#myLDPrune_review").load("/result_MDF.php?sessionID=' . $sessionID . '");
              $("#MDFtogglet").css("background-color", "#c5ebd4");
		      		$("#MDFtogglet").html(\'<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1 - Marker Dependency Filtering</div>\');
              setTimeout(function(){
                $("#MDFtabheader").show();
                $("#MDFtab2").show();
                $("#MDFtab2").click();       
              }, 300);',
      "2" => '$("#SSEAtoggle").show(); 
              $("#mySSEA").load("/SSEA_parameters.php?sessionID=' . $sessionID . '");
              $("#MDFtogglet").click();
              setTimeout(function(){
                $("#MDFtabheader").hide();
                $("#SSEAtabheader").show();
              },400);',
      "2.25" => '$("#mySSEA_review").load("/SSEA_moduleprogress.php?sessionID=' . $sessionID . '");
            setTimeout(function(){
              $("#MDFtabheader").hide();
              $("#SSEAtabheader").show();
              $("#SSEAtab2").show();
              $("#SSEAtab2").click();         
            }, 400);',
      "2.5" => '$("#mySSEA_review").load("/run_SSEA.php?sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#MDFtabheader").hide();
                  $("#SSEAtabheader").show();
                  $("#SSEAtab2").show();
                  $("#SSEAtab2").click();         
                }, 400);',
      "2.75" => '$("#mySSEA_review").load("/result_SSEA.php?rmchoice=1&sessionID=' . $sessionID . '");
                $("#SSEAtogglet").css("background-color", "#c5ebd4");
                $("#SSEAtogglet").html(\'<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2 - Marker Set Enrichment Analysis</div>\');
                setTimeout(function(){
                  $("#MDFtabheader").hide();
                  $("#SSEAtabheader").show();
                  $("#SSEAtab2").show();
                  $("#SSEAtab2").click();       
                }, 400);',
      "3" => '$("#mywKDA").load("/wKDA_parameters.php?rmchoice=1&sessionID=' . $sessionID . '"); 
              $("#wKDAtoggle").show();
              setTimeout(function(){
                $("#MDFtabheader").hide();
                $("#SSEAtabheader").hide();
                $("#wKDAtabheader").show();           
              }, 500);',
      "3.25" => '$("#mywKDA_review").load("/wKDA_moduleprogress.php?rmchoice=1&sessionID=' . $sessionID . '");
              $("#wKDAtoggle").show();
		    		  setTimeout(function(){
                $("#MDFtabheader").hide();
                $("#SSEAtabheader").hide();
                $("#wKDAtabheader").show();              
                $("#wKDAtab2").show();
                $("#wKDAtab2").click();},
              500);',
      "3.5" => '$("#mywKDA_review").load("/run_wKDA.php?rmchoice=1&sessionID=' . $sessionID . '");
              $("#wKDAtoggle").show();
              setTimeout(function(){
                $("#MDFtabheader").hide();
                $("#SSEAtabheader").hide();
                $("#wKDAtabheader").show();              
                $("#wKDAtab2").show();
                $("#wKDAtab2").click();},
              500);',
      "3.75" => '$("#mywKDA_review").load("/result_wKDA.php?rmchoice=1&sessionID=' . $sessionID . '");
              $("#wKDAtogglet").css("background-color", "#c5ebd4");
              $("#wKDAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 3 - Weighted Key Driver Analysis</div>`);
              $("#wKDAtoggle").show();
              setTimeout(function(){
                $("#MDFtabheader").hide();
                $("#SSEAtabheader").hide();
                $("#wKDAtabheader").show();              
                $("#wKDAtab2").show();
                $("#wKDAtab2").click();},
              500);',
    ),
    "GWASskipped" => array(
      "1" => '$("#MDFtogglet").hide();
              $("#mySSEA").load("/SSEAskipped_parameters.php?sessionID=' . $sessionID . '"); 
              setTimeout(function(){
                $("#SSEAtoggle").show();
                $("#SSEAtabheader").show();
              },400);',
      "1.25" => '$("#mySSEA_review").load("/SSEA_moduleprogress.php?skippedMDF=1&sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#SSEAtabheader").show();
                  $("#SSEAtab2").show();
                  $("#SSEAtab2").click();         
                }, 400);',
      "1.5" => '$("#mySSEA_review").load("/run_SSEA.php?sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#SSEAtabheader").show();
                  $("#SSEAtab2").show();
                  $("#SSEAtab2").click();         
                }, 400);',
      "1.75" => '$("#mySSEA_review").load("/result_SSEA.php?rmchoice=1&sessionID=' . $sessionID . '"); 
                   $("#SSEAtogglet").css("background-color", "#c5ebd4");
                   $("#SSEAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2 - Marker Set Enrichment Analysis</div>`);
                   setTimeout(function(){
                    $("#SSEAtabheader").show();
                    $("#SSEAtab2").show();
                    $("#SSEAtab2").click();         
                  }, 400);
                   ',
      "2" => '$("#wKDAtoggle").show();
                $("#mywKDA").load("/wKDA_parameters.php?rmchoice=1&sessionID=' . $sessionID . '"); 
                setTimeout(function(){
                  $("#SSEAtabheader").hide();
                  $("#wKDAtabheader").show();              
                500);',
      "2.25" => '$("#mywKDA_review").load("/wKDA_moduleprogress.php?rmchoice=1&sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#SSEAtabheader").hide();
                  $("#wKDAtabheader").show();              
                  $("#wKDAtab2").show();
                  $("#wKDAtab2").click();
                }, 500);',
      "2.5" => '$("#mywKDA_review").load("/run_wKDA.php?rmchoice=1&sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#SSEAtabheader").hide();
                  $("#wKDAtabheader").show();              
                  $("#wKDAtab2").show();
                  $("#wKDAtab2").click();
                }, 500);',
      "2.75" => '$("#mywKDA_review").load("/result_wKDA.php?rmchoice=1&sessionID=' . $sessionID . '");
                  $("#wKDAtogglet").css("background-color", "#c5ebd4");
                  $("#wKDAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2 - Weighted Key Driver Analysis</div>`);
                  setTimeout(function(){
                    $("#SSEAtabheader").hide();
                    $("#wKDAtabheader").show();              
                    $("#wKDAtab2").show();
                    $("#wKDAtab2").click();
                  }, 500);',
    ),
    "MSEA" => array(
      "1" => '$("#myMSEA").load("/MSEA_parameters.php?sessionID=' . $sessionID . '"); 
              setTimeout(function(){
                $("#MSEAtabheader").show();
              },400);',
      "1.25" => '$("#myMSEA_review").load("/MSEA_moduleprogress.php?sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#MSEAtabheader").show();
                  $("#MSEAtab2").show();
                  $("#MSEAtab2").click();
                },400);',
      "1.5" => '$("#myMSEA_review").load("/run_MSEA.php?sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#MSEAtabheader").show();
                  $("#MSEAtab2").show();
                  $("#MSEAtab2").click();
                },400);',
      "1.75" => '$("#myMSEA_review").load("/result_SSEA.php?rmchoice=2&sessionID=' . $sessionID . '"); 
                   $("#MSEAtogglet").css("background-color", "#c5ebd4");
                   $("#MSEAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1 - Marker Set Enrichment Analysis</div>`);
                   setTimeout(function(){
                    $("#MSEAtabheader").show();
                    $("#MSEAtab2").show();
                    $("#MSEAtab2").click();
                  },400);',
      "2" => '$("#MSEA2KDAtoggle").show();
              $("#myMSEA2KDA").load("/wKDA_parameters.php?rmchoice=2&sessionID=' . $sessionID . '");
              setTimeout(function(){
                $("#MSEAtabheader").hide();
                $("#MSEA2KDAtabheader").show();
              },400);',
      "2.25" => '$("#myMSEA2KDA_review").load("/wKDA_moduleprogress.php?rmchoice=2&sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#MSEAtabheader").hide();
                  $("#MSEA2KDAtabheader").show();
                  $("#MSEA2KDAtab2").show();
                  $("#MSEA2KDAtab2").click();
                },400);',
      "2.5" => '$("#myMSEA2KDA_review").load("/run_wKDA.php?rmchoice=2&sessionID=' . $sessionID . '");
                setTimeout(function(){
                  $("#MSEAtabheader").hide();
                  $("#MSEA2KDAtabheader").show();
                  $("#MSEA2KDAtab2").show();
                  $("#MSEA2KDAtab2").click();
                },400);',
      "2.75" => '$("#myMSEA2KDA_review").load("/result_wKDA.php?rmchoice=2&sessionID=' . $sessionID . '");
                 $("#MSEA2KDAtogglet").css("background-color", "#c5ebd4");
                 $("#MSEA2KDAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2 - Weighted Key Driver Analysis</div>`);
                 setTimeout(function(){
                  $("#MSEAtabheader").hide();
                  $("#MSEA2KDAtabheader").show();
                  $("#MSEA2KDAtab2").show();
                  $("#MSEA2KDAtab2").click();
                },400);',
    ),
    "META" => array(
      "1" => '$("#myMETA").load("/META_buttons.php?metasessionID=' . $sessionID . '");' . "\n" .
        'setTimeout(function(){$("#METAtogglet").click(); }, 500);' . "\n",
      "1.25" => '$("#myMETA").load("/META_moduleprogress.php?metasessionID=' . $sessionID . '");' . "\n" .
        '$("#METAtab1").html("Review Files");' . "\n",
      //'$("#METAtogglet").click();',

      // '$("#METAtab2").show();' . "\n" .
      // '$("#METAtab2").click();' . "\n",
      "1.5" => '$("#myMETA_review").load("/run_META.php?metasessionID=' . $sessionID . '");' . "\n" .
        '$("#METAtab2").show();' . "\n" .
        '$("#METAtab2").click();' . "\n",
      "1.75" => '$("#myMETA_review").load("/result_META.php?metasessionID=' . $sessionID . '&sessionload=T"); ' . "\n" .
        '$("#METAtab2").show();' . "\n" .
        '$("#METAtab2").click();' . "\n" .
        '$("#METAtogglet").css("background-color", "#c5ebd4");' . "\n" .
        '$("#METAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1 - META-MSEA</div>`);' . "\n",
      "2" => '$("#META2KDAtoggle").show();
                $("#myMETA2KDA").load("/wKDA_parameters.php?rmchoice=3&sessionID=' . $sessionID . '"); 
                $("#METAtogglet").click();
                $("#META2KDAtogglet").click();',
      "2.25" => '$("#myMETA2KDA_review").load("/wKDA_moduleprogress.php?rmchoice=3&sessionID=' . $sessionID . '");
                  $("#META2KDAtab2").show();
                  $("#META2KDAtab2").click();',
      "2.5" => '$("#myMETA2KDA_review").load("/run_wKDA.php?rmchoice=3&sessionID=' . $sessionID . '");
                  $("#META2KDAtab2").show();
                  $("#META2KDAtab2").click();',
      "2.75" => '$("#myMETA2KDA_review").load("/result_wKDA.php?rmchoice=3&sessionID=' . $sessionID . '");
                  $("#META2KDAtab2").show();
                  $("#META2KDAtab2").click();
                  $("#META2KDAtogglet").css("background-color", "#c5ebd4");
                  $("#META2KDAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 2 - Weighted Key Driver Analysis</div>`);',

    ),
    "KDA" => array(
      "1" => '$("#myKDASTART").load("/KDAstart_parameters.php?sessionID=' . $sessionID . '"); 
                $("#KDASTARTtogglet").click();',
      "1.25" => '$("#myKDASTART_review").load("/wKDA_moduleprogress.php?rmchoice=4&sessionID=' . $sessionID . '");
                $("#KDASTARTtab2").show();
                $("#KDASTARTtab2").click();',
      "1.5" => '$("#myKDASTART_review").load("/run_wKDA.php?rmchoice=4&sessionID=' . $sessionID . '");
                $("#KDASTARTtab2").show();
                $("#KDASTARTtab2").click();',
      "1.75" => '$("#myKDASTART_review").load("/result_wKDA.php?rmchoice=4&sessionID=' . $sessionID . '"); 
                   $("#KDASTARTtab2").show();
                   $("#KDASTARTtab2").click();
                   $("#KDASTARTtogglet").css("background-color", "#c5ebd4");
                   $("#KDASTARTtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1 - Weighted Key Driver Analysis</div>`);'
    ),
    "SSEAtoPharmomics" => array(
      "1" => '$("#myssea2pharm").load("/ssea2pharmomics_parameters.php?sessionID=' . $sessionID . '&rmchoice=' . $rmchoice . '");
      setTimeout(function() {
        $("#ssea2pharmtoggle").show();
        $("#SSEAtabheader").hide();
        $("#ssea2pharmtabheader").show();
      }, 500);',
      '1.25' => '$.ajax({
                  url: "ssea2pharmomics_moduleprogress.php",
                  method: "GET",
                  data: {
                    sessionID: "' . $sessionID . '" ,
                  },
                  success: function(data) {
                    $("#myssea2pharm_review").html(data);
                  }
                });
                setTimeout(function(){
                  $("#ssea2pharmtab2").show();
                  $("#ssea2pharmtab2").click();
                  $("#ssea2pharmtab2").html("Review Modules");
                },500);',
      '1.5' => $ssea2pharm_1_5,
      // '1.75' => $ssea2pharm_1_75,
    ),
    "MSEAtoPharmomics" => array(
      "1" => '$("#mymsea2pharm").load("/ssea2pharmomics_parameters.php?sessionID=' . $sessionID . '&rmchoice=' . $rmchoice . '");
      setTimeout(function() {
        $("#msea2pharmtoggle").show();
        $("#MSEAtogglet").click();
        $("#msea2pharmtogglet").click();
      }, 500);',
      '1.25' => '$.ajax({
                  url: "ssea2pharmomics_moduleprogress.php",
                  method: "GET",
                  data: {
                    sessionID: "' . $sessionID . '",
                  },
                  success: function(data) {
                    $("#mymsea2pharm_review").html(data);
                  }
                });
                setTimeout(function(){
                  $("#msea2pharmtab2").show();
                  $("#msea2pharmtab2").click();
                  $("#msea2pharmtab2").html("Review Modules");
                },500);',
      '1.5' => $msea2pharm_1_5,
      // '1.75' => $msea2pharm_1_75,
    ),
    "METAtoPharmomics" => array(
      "1" => '$("#myMETAMSEA2PHARM").load("/ssea2pharmomics_parameters.php?sessionID=' . $sessionID . '&rmchoice=3");
      setTimeout(function() {
        $("#METAMSEA2PHARMtoggle").show();
        $("#METAtabheader").hide();
        $("#METAMSEA2PHARMtabheader").show();
      }, 500);',
      '1.25' => '$.ajax({
                  url: "ssea2pharmomics_moduleprogress.php",
                  method: "GET",
                  data: {
                    sessionID: "' . $sessionID . '",
                  },
                  success: function(data) {
                    $("#myMETAMSEA2PHARM_review").html(data);
                  }
                });
                setTimeout(function(){
                  $("#METAMSEA2PHARMtab2").show();
                  $("#METAMSEA2PHARMtab2").click();
                  $("#METAMSEA2PHARMtab2").html("Review Modules");
                },500);',
      '1.5' => $meta2pharm_1_5,
      // '1.75' => $meta2pharm_1_75,
    ),
    "SSEAKDAtoPharmomics" => array(
      "1" => "$('#mypharmOmics').load('/kda2pharmomics_parameters.php?sessionID=' + string + \"&rmchoice=1\");
              setTimeout(function(){
                $('#pharmOmicstoggle').show();
                $('#pharmOmicstabheader').show();
                $('#wKDAtabheader').hide();
              },500);",
      "1.25" => "$.ajax({
                  url: 'kda2pharmomics_moduleprogress.php',
                  method: 'GET',
                  data: {
                    sessionID: '" . $sessionID . "',
                    rmchoice:'1',
                  },
                  success: function(data) {
                    $('#mypharmOmics_review').html(data);
                  }
                });
                setTimeout(function(){
                  $('#pharmOmicstab2').show();
                  $('#pharmOmicstab2').click()
                  $('#pharmOmicstab2').html('Review Modules');
                },500);
      ",
      "1.5" => $ssea2kda2pharm_1_5,
      // "1.75" => $ssea2kda2pharm_1_75,
    ),
    "MSEAKDAtoPharmomics" => array(
      "1" => "$('#myKDA2PHARM').load('/kda2pharmomics_parameters.php?sessionID=" . $sessionID . "&rmchoice=2');
              setTimeout(function(){
                $('#KDA2PHARMtoggle').show();
                $('#MSEAtabheader').hide();
                $('#MSEA2KDAtabheader').hide();
                $('#KDA2PHARMtabheader').show();
              }, 500);",
      "1.25" => "$.ajax({
                  url: 'kda2pharmomics_moduleprogress.php',
                  method: 'GET',
                  data: {
                    sessionID: '" . $sessionID . "',
                    rmchoice:'2',
                  },
                  success: function(data) {
                    $('#myKDA2PHARM_review').html(data);
                  }
                });
                $('#KDA2PHARMtab2').show();
                $('#KDA2PHARMtab2').click();",
      "1.5" => $msea2kda2pharm_1_5,
      // "1.75" => $msea2kda2pharm_1_75,
    ),
    "METAKDAtoPharmomics" => array(
      "1" => "$('#myMETAKDA2PHARM').load('/kda2pharmomics_parameters.php?sessionID=" . $sessionID . "&rmchoice=3');
              setTimeout(function(){
                $('#META2KDAtabheader').hide();
                $('#METAKDA2PHARMtoggle').show();
                $('#METAKDA2PHARMtogglet').click();
              },500)",
      "1.25" => "$.ajax({
                            url: 'kda2pharmomics_moduleprogress.php',
                            method: 'GET',
                            data: {
                              sessionID: '" . $sessionID . "',
                              rmchoice:'3',
                            },
                            success: function(data) {
                              $('#myMETAKDA2PHARM_review').html(data);
                            }
                          });
                          $('#METAKDA2PHARMtab2').show();
                          $('#METAKDA2PHARMtab2').click();",
      "1.5" => $meta2kda2pharm_1_5,
      // "1.75" => $msea2kda2pharm_1_75,
    ),
    "KDAtoPharmomics" => array(
      "1" => "$('#myKDASTART2PHARM').load('/kda2pharmomics_parameters.php?sessionID=" . $sessionID . "&rmchoice=4');
              setTimeout(function(){
                $('#KDASTARTtabheader').hide();
                $('#KDASTART2PHARMtoggle').show();
                $('#KDASTART2PHARMtogglet').click();
              },500)",
      "1.25" => "$.ajax({
                            url: 'kda2pharmomics_moduleprogress.php',
                            method: 'GET',
                            data: {
                              sessionID: '" . $sessionID . "',
                              rmchoice:'4',
                            },
                            success: function(data) {
                              $('#myKDASTART2PHARM_review').html(data);
                            }
                          });
                          $('#KDASTART2PHARMtab2').show();
                          $('#KDASTART2PHARMtab2').click();",
      "1.5" => $kda2pharm_1_5,
      // "1.75" => $msea2kda2pharm_1_75,
    )
  ));

  $fjsonOut = "./Data/Pipeline/Resources/session/pipeline.json";
  $fp = fopen($fjsonOut, 'w');
  fwrite($fp, $json);
  fclose($fp);
  chmod($fjsonOut, 0777);


  $fjson = "./Data/Pipeline/Resources/session/pipeline.json";
  $url = json_decode(file_get_contents($fjson), true);

  $x = 1;
  $write_url = NULL;

  if ($pipeline == "GWAS" || $pipeline == "GWASskipped")
    $write_url .= "startGWAS();\r\n";
  else if ($pipeline == "MSEA")
    $write_url .= "startTEME();\r\n";
  else if ($pipeline == "META")
    $write_url .= "startMETA();\r\n";
  else
    $write_url .= "startKDA();\r\n";

  while ($x <= $mergeomics_path) {
    if ($mergeomics_path > 1.5 && ($x == 1.25 || $x == 1.5)) {
      $x = $x + 0.25;
      continue;
    }

    if ($mergeomics_path > 2.5 && ($x == 2.25 || $x == 2.5)) {
      $x = $x + 0.25;
      continue;
    }

    if ($mergeomics_path > 3.5 && ($x == 3.25 || $x == 3.5)) {
      $x = $x + 0.25;
      continue;
    }
    $write_url .= $url[$pipeline][strval($x)] . "\r\n";
    $x = $x + 0.25;
  }


  #check for pharmomics
  if (
    $ssea2pharmomics_pipeline == "SSEAtoPharmomics" ||
    $ssea2pharmomics_pipeline == "MSEAtoPharmomics" ||
    $ssea2pharmomics_pipeline == "METAtoPharmomics"
  ) {
    $x = 1.0;
    while ($x <= $ssea2pharmomics_path) {
      $write_url .= $url[$ssea2pharmomics_pipeline][strval($x)] . "\r\n";
      $x = $x + 0.25;
    }
  }
  //METAKDAtoPharmomics
  if (
    $wkda2pharmomics_pipeline == "SSEAKDAtoPharmomics" ||
    $wkda2pharmomics_pipeline == "MSEAKDAtoPharmomics" ||
    $wkda2pharmomics_pipeline == "METAKDAtoPharmomics" ||
    $wkda2pharmomics_pipeline == "KDAtoPharmomics"
  ) {
    $x = 1.0;
    while ($x <= $wkda2pharmomics_path) {
      $write_url .= $url[$wkda2pharmomics_pipeline][strval($x)] . "\r\n";
      $x = $x + 0.25;
    }
  }


  //$write_url .= $url[$pipeline][strval($mergeomics_path)] . "\r\n";
  $furlOut = "./Data/Pipeline/Resources/session/$sessionID" . "mergeomicsurl.js";
  $fp = fopen($furlOut, 'w');
  fwrite($fp, $write_url);
  fclose($fp);
  chmod($furlOut, 0775);
}





?>

<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.css" />
<?php include_once("analyticstracking.php") ?>

<!-- Includes all the font/styling/js sheets -->
<?php include_once("head.php") ?>

<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>

<style>
  .btn:focus {
    outline: none !important;
    box-shadow: none !important;
  }

  #sidebar.active .custom-menu {
    margin-right: -30px !important;
  }

  .pipelineBubble {
  	border: 4px solid black;
	border-radius: 25px;
	margin: auto;
	width: 75%;
	text-align: center;
	font-size: 22px;
  }


  .activePipe{
    opacity: 1 !important;
  }

  .activeArrow{
    opacity: 1 !important;
  }

  .activePipe:hover {
	border: 4px solid #DC461D;
  }

  a.pipelineNav {
	color:black;
  }


</style>
<!-- START body of pipeline ----------------------------------------------------------------------------->

<body class="stretched">

  <!-- Include the Run Mergeomics header ------------------------------------------------------------------>
  <?php include_once("headersecondary.inc") ?>



  <!-- Page title block ---------------------------------------------------------------------------------->
  <section id="page-title">

    <div class="margin_rm">
      <div class="container clearfix" style="text-align: center;">
        <h2>Mergeomics Pipeline</h2>

      </div>
    </div>

  </section>




  <!---------------- Pipeline starting points: 3 animated buttons ------------------------------------------------------------>
  <section id="content" style="margin-bottom: 0px;">


    <div class="content-wrap" style="padding: 20px 0 0 0;">

      <nav id="sidebar">
        <div class="custom-menu">
          <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i id="sidebar_icon" class="icon-bars"></i>
            <span class="sr-only">Toggle Menu</span>
          </button>
        </div>
        <h1><a href="#" id="session_id" class="session" onclick="copy(this)">New Job</a></h1>
        <!-- <script type="text/javascript">
          function copy(that) {
            var inp = document.createElement('input');
            document.body.appendChild(inp)
            inp.value = that.textContent.split(" ").pop();
            inp.select();
            document.execCommand('copy', false);
            inp.remove();

            alert("Copied " + inp.value + " to Clipboard!");
          }
        </script> -->
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="home.php"><span class="fa fa-home mr-3"></span> Homepage</a>
          </li>
          <li>
            <a data-toggle="modal" data-target="#sessionIDmodal" href="#sessionIDmodal" tooltip="Enter existing session"><span class="fa fa-user mr-3"></span> Input Session ID <i class="icon-line2-question"></i></a>
            <!--<div class="collapse" id="session_enter" data-parent="#sidebar">
                    <a href="#" class="list-group-item" data-parent="#session_enter" style="background-color: #587d90;">Test</a>
                </div>-->
          </li>
          <li style="margin-bottom: 15%;">
            <a href="/runmergeomics.php"><span class="fa fa-sticky-note mr-3"></span> Run New Job <i class="icon-line2-question"></i></a>
          </li>
          <!-- <li>
            <a href="#" tooltip="Download files of current session"><span class="fa fa-sticky-note mr-3"></span> Download files <i class="icon-line2-question"></i></a>
          </li>

          <li>
            <a data-toggle="modal" data-target="#PIPELINEmap" href="#PIPELINEmap" tooltip="Pipeline map of session"><span class="fa fa-sticky-note mr-3"></span> Pipeline Map <i class="icon-line2-question"></i></a>
          </li> -->
         	<div class ="pipelineBubble activePipe" id="MDFflowChart" style="background: #FFF2CC;display: none;"><a href="#MDFtoggle" class="pipelineNav">MDF</a></div>
         	<div style="text-align: center;font-size: 25px;display: none;"><span>↓</span></div>
         	<div class ="pipelineBubble" id="MSEAflowChart" style="background: #F8CECC;display: none;">MSEA</div>
         	<div style="text-align: center;font-size: 25px;display: none;"><span>↓</span></div>
         	<div class ="pipelineBubble activePipe" id="METAflowChart" style="background: #E1D5E7;display: none;"><a href="#METAtoggle" class="pipelineNav">Meta-MSEA</a></div>
         	<div style="text-align: center;font-size: 25px;display: none;"><span>↓</span></div>
         	<div class ="pipelineBubble" id="MSEAtoPharmflowChart" style="background: #ACDED5;display: none;">MSEA to PharmOmics</div>
         	<div style="text-align: center;font-size: 25px;display: none;"><span>↓</span></div>
         	<div class ="pipelineBubble" id="KDAflowChart" style="background: #D4E1F5;display: none;">KDA</div>
         	<div style="text-align: center;font-size: 25px;display: none;"><span>↓</span></div>
         	<div class ="pipelineBubble" id="KDAtoPharmflowChart" style="background: #ACDED5;display: none;">KDA to PharmOmics</div>
         	<!--
              <img style="height: auto;padding: 10% 0% 0% 10%;" id="flowchart" src="include/pictures/GWAS_MDF_MSEA_KDA_Pharm.png" alt="Overview">
              <a class="draw-border" href="#MDFtoggle"></a>
              -->

          <p style="margin-top: 5%;">Please save your session ID so you can load your session at a later time (valid 48 hours after start of session) or optionally submit your email when prompted to receive session details and results. </p>

          <!--
          <p style="margin-top: 5%;">Mergeomics is being actively developed by the Yang Lab in the Department of Integrative Biology and Physiology at UCLA. </p>-->

        </ul>

      </nav>

      <div class="margin_rm">

        <div class="container clearfix" id="myContainer">

          <div class="row clearfix">

            <!--------------------- GWAS buttons ------------------------------------------------------------>

            <div class="col-lg-3 center bottommargin" name="GWAS" id="GWAScontainer">

              <div class="button-wrapper">
                <div id="GWASoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
                  <a href="#" class="runm button-inner" id="GWASbutton"> Individual GWAS <br>Enrichment</a>
                </div>
                <a href="#" class="button button-rounded button-reveal button-large button-teal" id="GWASstart" style="display: none;"><i class="icon-play"></i><span>Run Pipeline</span></a>
              </div>
              <div class="toggle toggle-border" style="display:none;" id="MDFtoggle">
                <div class="togglet" id="MDFtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 1 - Marker Dependency Filtering</div>
                </div>

                <div class="tabs tabs-bb togglec" id="MDFtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="MDFtab1" href="#myLDPrune">Input Files and Parameters</a></li>
                    <li><a id="MDFtab2" href="#myLDPrune_review" style="display: none;">Review Files</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myLDPrune"></div> <!-- Start tab content for LDPrune -->

                    <div class="togglec" id="myLDPrune_review"></div>

                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End MDF toggle-->

              <div class="toggle toggle-border" id="SSEAtoggle" style="display: none;">
                <!-- Start second toggle/step in MDF -->
                <div class="togglet" id="SSEAtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 2: Marker Set Enrichment Analysis</div>
                </div>


                <div class="tabs tabs-bb togglec" id="SSEAtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="SSEAtab1" href="#mySSEA">Input Files and Parameters</a></li>
                    <li><a id="SSEAtab2" href="#mySSEA_review" style="display: none;">Review Files</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="mySSEA"></div>

                    <div class="togglec" id="mySSEA_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End second toggle/step in MDF -->

              <div class="toggle toggle-border" id="ssea2pharmtoggle" style="display: none;">
                <!-- Start fifth toggle/step in MDF -->
                <div class="togglet" id="ssea2pharmtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 2B - PharmOmics</div>
                </div>


                <div class="tabs tabs-bb togglec" id="ssea2pharmtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="ssea2pharmtab1" href="#myssea2pharm">Input Modules</a></li>
                    <li><a id="ssea2pharmtab2" href="#myssea2pharm_review" style="display: none;">Review Modules</a></li>


                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myssea2pharm"></div>

                    <div class="togglec" id="myssea2pharm_review"></div>



                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End fifth toggle/step in MDF -->


              <div class="toggle toggle-border" id="wKDAtoggle" style="display: none;">
                <!-- Start second toggle/step in MDF -->
                <div class="togglet" id="wKDAtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 3 - Weighted Key Driver Analysis</div>
                </div>


                <div class="tabs tabs-bb togglec" id="wKDAtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="wKDAtab1" href="#mywKDA">Input Files and Parameters</a></li>
                    <li><a id="wKDAtab2" href="#mywKDA_review" style="display: none;">Review Files</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="mywKDA"></div>

                    <div class="togglec" id="mywKDA_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End third toggle/step in MDF -->

              <div class="toggle toggle-border" id="pharmOmicstoggle" style="display: none;">
                <!-- Start second toggle/step in MDF -->
                <div class="togglet" id="pharmOmicstogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 3B - PharmOmics</div>
                </div>


                <div class="tabs tabs-bb togglec" id="pharmOmicstabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="pharmOmicstab1" href="#mypharmOmics">Input Modules</a></li>
                    <li><a id="pharmOmicstab2" href="#mypharmOmics_review" style="display: none;">Review Modules</a></li>

                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="mypharmOmics"></div>

                    <div class="togglec" id="mypharmOmics_review"></div>




                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End fourth toggle/step in MDF -->




            </div> <!-- End GWAS container -->





            <!-----------------Transcriptome, Epigenome, or Metabolome Enrichment (TEME) button ------------------------------------------------------------>

            <div class="col-lg-3 center bottommargin" name="MSEA" id="TEMEcontainer">
              <div class="button-wrapper">
                <div id="TEMEoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
                  <a href="#" class="runm button-inner" id="TEMEbutton">Individual EWAS, TWAS, PWAS, or MWAS <br> Enrichment</a>
                </div>
                <a href="#" class="button button-rounded button-reveal button-large button-teal" id="TEMEstart" style="display: none;"><i class="icon-play"></i><span>Run Pipeline</span></a>
              </div>


              <div class="toggle toggle-border" id="MSEAtoggle" style="display: none;">
                <!-- Start first toggle/step in MSEA -->
                <div class="togglet" id="MSEAtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 1: Marker Set Enrichment Analysis</div>
                </div>


                <div class="tabs tabs-bb togglec" id="MSEAtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="MSEAtab1" href="#myMSEA">Input Files and Parameters</a></li>
                    <li><a id="MSEAtab2" href="#myMSEA_review" style="display: none;">Review Files</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myMSEA"></div>

                    <div class="togglec" id="myMSEA_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End first toggle/step in MSEA -->

              <div class="toggle toggle-border" id="msea2pharmtoggle" style="display: none;">
                <!-- Start second toggle/step in MSEA -->
                <div class="togglet" id="msea2pharmtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 1B - PharmOmics</div>
                </div>


                <div class="tabs tabs-bb togglec" id="msea2pharmtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="msea2pharmtab1" href="#mymsea2pharm">Input Modules</a></li>
                    <li><a id="msea2pharmtab2" href="#mymsea2pharm_review" style="display: none;">Review Modules</a></li>


                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="mymsea2pharm"></div>

                    <div class="togglec" id="mymsea2pharm_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End second toggle/step in MSEA -->

              <div class="toggle toggle-border" id="MSEA2KDAtoggle" style="display: none;">
                <!-- Start thirdtoggle/step in MSEA -->
                <div class="togglet" id="MSEA2KDAtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 2 - Weighted Key Driver Analysis</div>
                </div>


                <div class="tabs tabs-bb togglec" id="MSEA2KDAtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="MSEA2KDAtab1" href="#myMSEA2KDA">Input Files and Parameters</a></li>
                    <li><a id="MSEA2KDAtab2" href="#myMSEA2KDA_review" style="display: none;">Review Files</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myMSEA2KDA"></div>

                    <div class="togglec" id="myMSEA2KDA_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End third toggle/step in MSEA -->


              <div class="toggle toggle-border" id="KDA2PHARMtoggle" style="display: none;">
                <!-- Start fourth toggle/step in MSEA -->
                <div class="togglet" id="KDA2PHARMtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 2B - PharmOmics</div>
                </div>


                <div class="tabs tabs-bb togglec" id="KDA2PHARMtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="KDA2PHARMtab1" href="#myKDA2PHARM">Input Modules</a></li>
                    <li><a id="KDA2PHARMtab2" href="#myKDA2PHARM_review" style="display: none;">Review Modules</a></li>

                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myKDA2PHARM"></div>

                    <div class="togglec" id="myKDA2PHARM_review"></div>




                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End fourth toggle/step in MSEA -->


            </div>
            <!-----------------End of Transcriptome, Epigenome, or Metabolome Enrichment (TEME) button ------------------------------------------------------------>


            <!----------------Start of Meta-MSEA button ------------------------------------------------------------>


            <div class="col-lg-3 center bottommargin" name="META" id="METAcontainer">

              <div class="button-wrapper">
                <div id="METAoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
                  <a href="#" class="runm button-inner" id="METAbutton">Multiple Omics Datasets <br>(GWAS, EWAS, TWAS, PWAS, MWAS) <br> Enrichment</a>
                </div>
                <a href="#" class="button button-rounded button-reveal button-large button-teal" id="METAstart" style="display: none;"><i class="icon-play"></i><span>Run Pipeline</span></a>
              </div>

              <div class="toggle toggle-border" id="METAtoggle" style="display: none;">
                <!-- Start first toggle/step in Meta-MSEA -->
                <div class="togglet" id="METAtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 1 - Meta-MSEA</div>
                </div>


                <div class="tabs tabs-bb togglec" id="METAtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="METAtab1" href="#myMETA">Type of Enrichment</a></li>
                    <li><a id="METAtab2" href="#myMETA_review" style="display: none;">Results</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->


                    <div class="togglec" id="myMETA"></div>
                    <div class="togglec" id="myMETA_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End first toggle/step in Meta-MSEA -->


              <div class="toggle toggle-border" id="METAMSEA2PHARMtoggle" style="display: none;">
                <!-- Start second toggle/step in META-MSEA -->
                <div class="togglet" id="METAMSEA2PHARMtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 1B - PharmOmics</div>
                </div>


                <div class="tabs tabs-bb togglec" id="METAMSEA2PHARMtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="METAMSEA2PHARMtab1" href="#myMETAMSEA2PHARM">Input Modules</a></li>
                    <li><a id="METAMSEA2PHARMtab2" href="#myMETAMSEA2PHARM_review" style="display: none;">Review Modules</a></li>


                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myMETAMSEA2PHARM"></div>

                    <div class="togglec" id="myMETAMSEA2PHARM_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End second toggle/step in META-MSEA -->

              <div class="toggle toggle-border" id="META2KDAtoggle" style="display: none;">
                <!-- Start third toggle/step in META-MSEA -->
                <div class="togglet" id="META2KDAtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 2 - Weighted Key Driver Analysis</div>
                </div>


                <div class="tabs tabs-bb togglec" id="META2KDAtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="META2KDAtab1" href="#myMETA2KDA">Input Files and Parameters</a></li>
                    <li><a id="META2KDAtab2" href="#myMETA2KDA_review" style="display: none;">Review Files</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myMETA2KDA"></div>

                    <div class="togglec" id="myMETA2KDA_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End third toggle/step in META-MSEA -->

              <div class="toggle toggle-border" id="METAKDA2PHARMtoggle" style="display: none;">
                <!-- Start fourth toggle/step in MSEA -->
                <div class="togglet" id="METAKDA2PHARMtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 2B - PharmOmics</div>
                </div>


                <div class="tabs tabs-bb togglec" id="KDA2PHARMtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="METAKDA2PHARMtab1" href="#myMETAKDA2PHARM">Input Modules</a></li>
                    <li><a id="METAKDA2PHARMtab2" href="#myMETAKDA2PHARM_review" style="display: none;">Review Modules</a></li>

                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myMETAKDA2PHARM"></div>

                    <div class="togglec" id="myMETAKDA2PHARM_review"></div>



                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End fourth toggle/step in MSEA -->

            </div>
            <!-------------------------------------------KDA button ------------------------------------------------------------>
            <div class="col-lg-3 center bottommargin" name="KDA" id="KDASTARTcontainer">

              <div class="button-wrapper">
                <div id="KDASTARToutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
                  <a href="#" class="runm button-inner" id="KDASTARTbutton">Weighted Key Driver <br> Analysis</a>
                </div>
                <a href="#" class="button button-rounded button-reveal button-large button-teal" id="KDAstart" style="display: none;"><i class="icon-play"></i><span>Run Pipeline</span></a>
              </div>

              <div class="toggle toggle-border" id="KDASTARTtoggle" style="display: none;">
                <!-- Start first toggle/step in wKDA -->
                <div class="togglet" id="KDASTARTtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 1 - Weighted Key Driver Analysis</div>
                </div>


                <div class="tabs tabs-bb togglec" id="KDASTARTtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="KDASTARTtab1" href="#myKDASTART">Input Files and Parameters</a></li>
                    <li><a id="KDASTARTtab2" href="#myKDASTART_review" style="display: none;">Review Files</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myKDASTART"></div>

                    <div class="togglec" id="myKDASTART_review"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End first toggle/step in wKDA -->

              <div class="toggle toggle-border" id="KDASTART2PHARMtoggle" style="display: none;">
                <!-- Start second toggle/step in wKDA -->
                <div class="togglet" id="KDASTART2PHARMtogglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Step 2 - PharmOmics</div>
                </div>


                <div class="tabs tabs-bb togglec" id="KDASTART2PHARMtabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="KDASTART2PHARMtab1" href="#myKDASTART2PHARM">Input Modules</a></li>
                    <li><a id="KDASTART2PHARMtab2" href="#myKDASTART2PHARM_review" style="display: none;">Review Modules</a></li>

                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myKDASTART2PHARM"></div>

                    <div class="togglec" id="myKDASTART2PHARM_review"></div>




                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End fourth toggle/step in MSEA -->


            </div>
            <!-----------------End of KDA button button ------------------------------------------------------------>


          </div> <!-- End row clearfix -->

          <div id="flowchart_div" class="row clearfix">

            <div class="col-full center bottommargin" data-animate="pulse">
              <img style="width: 80%;height: auto;" id="flowchart" src="include/pictures/OVERVIEW_2.png" alt="Overview">
              <a id="MDFbubble" class="draw-border" data-toggle="modal" data-target="#MDFmodal" href="#MDFmodal"></a>
              <a id="MSEAbubble" class="draw-border" data-toggle="modal" data-target="#MSEAmodal" href="#MSEAmodal"></a>
              <a id="METAbubble" class="draw-border" data-toggle="modal" data-target="#METAmodal" href="#METAmodal"></a>
              <a id="KDAbubble" class="draw-border" data-toggle="modal" data-target="#KDAmodal" href="#KDAmodal"></a>
              <a id="DRUGbubble" class="draw-border" data-toggle="modal" data-target="#DRUGmodal" href="#DRUGmodal"></a>
              <a id="NETWORKbubble" class="draw-border" data-toggle="modal" data-target="#NETWORKmodal" href="#NETWORKmodal"></a>
              <a id="JACCARDbubble" class="draw-border" data-toggle="modal" data-target="#JACCARDmodal" href="#JACCARDmodal"></a>




            </div>


          </div>


        </div> <!-- End container clearfix -->
      </div>
      <!--Margin left div--->
    </div><!-- End content-wrap -->


  </section> <!-- End button section -->

  <!----------------------------------MODAL ------------------------------------------>
  <div id="MDFmodal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">Marker Dependency Filtering</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pictures/MDF_modal.png">
            <br>
            <div class="divider divider-center"></div>
            <h4 class="instructiontext">MDF prepares input files for MSEA by correcting for dependency between omics markers (e.g. linkage disequilibrium between SNPs in GWAS).</h4>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="MSEAmodal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">Marker Set Enrichment Analysis</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pictures/MSEA_modal.png">
            <br>
            <div class="divider divider-center"></div>
            <h4 class="instructiontext">MSEA detects pathways and networks affected by multidimensional molecular markers (e.g., SNPs, differential methylation sites) associated with a pathological condition. The pipeline can be concluded after MSEA is run, or the results can be used directly in wKDA.</h4>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="METAmodal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">META Marker Set Enrichment Analysis</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pictures/MSEA_modal.png">
            <br>
            <div class="divider divider-center"></div>
            <!--<h4 class="instructiontext">Information will be added soon!</h4>-->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="KDAmodal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">Weighted Key Driver Analysis</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pictures/KDA_modal.png">
            <br>
            <div class="divider divider-center"></div>
            <h4 class="instructiontext">wKDA identifies essential regulators of disease-associated pathways and networks and produces the corresponding interactive network visualization. wKDA can be run as a follow-up to MSEA or Meta MSEA; or it can be run as an independent module.</h4>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="DRUGmodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">PharmOmics Pipeline</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pictures/Pharmomics_overview.png">
            <br>
            <div class="divider divider-center"></div>
            <h4 class="instructiontext">PharmOmics is a comprehensive drug knowledgebase comprised of genomic footprints derived from meta-analysis of microarray and RNA sequencing data relevant to drugs from tissues and cells derived from human, mouse, and rat samples in GEO, ArrayExpress, TG-GATEs and drugMatrix data repositories. </h4>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="NETWORKmodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">Network Based Drug Repositioning</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pharmomics/App2.png">
            <br>
            <div class="divider divider-center"></div>
            <h4 class="instructiontext">PharmOmics is a comprehensive drug knowledgebase comprised of genomic footprints derived from meta-analysis of microarray and RNA sequencing data relevant to drugs from tissues and cells derived from human, mouse, and rat samples in GEO, ArrayExpress, TG-GATEs and drugMatrix data repositories. </h4>
            <p style="padding: 0 5%; font-size: 20px;">This tool ranks drugs based on the connectivity of drug signatures to input genes as defined by a gene network model. distance(I,D) is a network proximity measurement between drug (D) and input genes (I).</p>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="JACCARDmodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">Jaccard/Overlap Based Drug Repositioning</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pictures/App3.png">
            <br>
            <div class="divider divider-center"></div>
            <h4 class="instructiontext">PharmOmics is a comprehensive drug knowledgebase comprised of genomic footprints derived from meta-analysis of microarray and RNA sequencing data relevant to drugs from tissues and cells derived from human, mouse, and rat samples in GEO, ArrayExpress, TG-GATEs and drugMatrix data repositories. </h4>
            <p style="padding: 0 5%; font-size: 20px;">The Jaccard score is used as the direct overlap measurement between input genes and disease genes (unsigned for single list of genes, signed for up- and downregulated genes). The gene overlap fold enrichment, odds ratio, Fisher's exact test p-value, and within-species rank is also calculated.</p>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="PIPELINEmap" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">Pipeline Map</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img style="height: auto; width: 60%;" src="include/pictures/OVERVIEW_GWAS_2.png">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="sessionIDmodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 45px;">Enter Session ID</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body" style="text-align: center;">
            <!-- <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
                        <div class="alert alert-warning" style="margin: 0 auto; width: 80%;">
                          <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i><strong>Note:</strong> SessionIDs/Progress are under maintenance. We apologize for any inconvenience this may cause. For now, please use the email option to have your results emailed to you if you need to be away from your computer.
                        </div>
                      </div> --->
            <p class="instructiontext" style="margin: 0;">Session IDs are valid for 48 hours. <br>If 24 hours has already passed, please start a new job.</p>
            <br>
            <form action="runmergeomics.php" name="sessionform" id="mySessionform">
              <input type="text" name="sessionID" id="mySessionID" />

              <div id="myIDpreload"></div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" onclick="submitID()">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="loadIDmodal" class="modal fade bs-example-modal-lg" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" id="sessionIDtitle" style="font-size: 45px;">Session ID</h4>

          </div>
          <div class="modal-body" id="loadIDbody" style="text-align: center;">
          </div>

        </div>
      </div>
    </div>
  </div>



  <div id="skipMDFmodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 45px;">Skip MDF?</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body" style="font-size: 25px;text-align: center;">
            <p> Are you sure you want to skip Marker Dependency Filtering? <br> If dependency among markers are not corrected, spurious results may be generated!</p>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="skipMDF()">Yes, skip MDF</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No, go back</button>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- <script src="include/js/functions.js?20200803"></script> -->
    <!--<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.js"></script>-->
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
  <script>
    /*Sidebar Jquery Event handlers *

1) Change the container width and sidebar active state based on window side

*/

    $(document).ready(function() {
      if ($(window).width() < 992) {
        $('.container').addClass('no_sidebar');
        $('.margin_rm').addClass('no_margin');
      }

      $(".pipelineNav").on('click', function(e){
      var href = $(this).attr('href');
      console.log(href);
      //var classList = $(href).children(0).attr("class").split(/\s+/);
      //console.log(classList);
      //if (classList.length === 2) {
      if ($(href).children('.togglec').css('display') == 'none') {
          $(href).children(0).click();
      }

      //var container = $('#GWAScontainer');
      //var scrollTo = $(href);
      var val = $(href).offset().top - $(window).scrollTop() - 65;
      if (val<=0 || ($(window).scrollTop()!=0 && $(window).scrollTop() < $(href).offset().top)){ 
        // below item or scrolled down but not below item
        var val = $(href).offset().top - 65;
      } 

      $(window).scrollTop(
        //$(href).offset().top - $(window).offset().top + $(window).scrollTop() - 60
        //$(href).offset().top - $(window).scrollTop() - 60
        val
      );

      return false;
    });
    });

    $(window).on('resize', function() {
      if ($(window).width() > 992 && $("#sidebar.active")[0]) {
        $('.container').addClass('no_sidebar');
        $('.margin_rm').addClass('no_margin');
      } else if ($(window).width() < 992 && $("#sidebar.active")[0]) {
        $('.container').removeClass('no_sidebar');
        $('.margin_rm').removeClass('no_margin');
      } else if ($(window).width() < 992 && $("#sidebar")[0]) {
        $('.container').addClass('no_sidebar');
        $('.margin_rm').addClass('no_margin');
      } else {
        $('.container').removeClass('no_sidebar');
        $('.margin_rm').removeClass('no_margin');
      }

    });

    $('#sidebarCollapse').on('click', function() {

      if ($("#sidebar.active")[0]) {
        $('#sidebar').toggleClass('active');
        $('.container').toggleClass('no_sidebar');
        $('.margin_rm').toggleClass('no_margin');
      } else {
        $('#sidebar').toggleClass('active');
        $('.container').toggleClass('no_sidebar');
        $('.margin_rm').toggleClass('no_margin');
      }


    });

    $(document).scroll(function() {
      if ($(window).scrollTop() > 100 && $(window).width() < 992) {

        $("#sidebar").css("margin-top", "-206px");

      } else if ($(window).scrollTop() < 100 && $(window).width() < 992) {

        $("#sidebar").css("margin-top", "-106px");

      }
    });

    

	


    /*End Sidebar Jquery Event Handler*/

    function submitID() {
      $("#myIDpreload").html(`<p class='instructiontext' style='font-size: 15px; margin:10px 0 0 0;padding:0px'>Loading session<span class = "dots">...</span><br>This may take a few seconds</p>`);
      $("#mySessionform").on('submit', function(event) {
        var session = $('#mySessionID').val();
        $.ajax({
          url: 'runmergeomics.php?sessionID=' + session,
          type: 'GET'
        });
      });

      return false;
    }
    //Needs to combine all 4 functions into 1....
    function startGWAS() {
      //Remove animation from container---------------------------------------------------------->
      $("#GWAScontainer").removeAttr("data-animate");
      $("#GWAScontainer").removeClass("pulse animated");

      //Fade out and up the other two buttons------------------------------------------------------->
      $("#TEMEcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#KDASTARTcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#METAcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#GWASstart").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#flowchart").animate({
        height: 0,
        opacity: 0
      }, 'slow');

      //Wait about until other buttons fade out and then change size of GWAS button------------------------------------------------------->
      window.setTimeout(function() {
        $("#GWAScontainer").addClass("col_full").removeClass("col-lg-3 center bottommargin");
        $("#GWASoutline").removeClass();
        //$("p").css({"background-color": "yellow", "font-size": "200%"});
        $("#GWASoutline").css({
          "height": "100px",
          "margin": "10px 0"
        });
        $("#GWASbutton:first-child").removeClass();
        $("#GWASbutton:first-child").addClass("button button-3d button-rounded button runm runm_pipeline noHover");
        $("#GWASbutton").unbind("click").click(function() {});

        $("#GWAScontainer").hide().show("slide", {
          direction: "up"
        }, 500);
        $("#MDFtoggle").show();
        //Remove unwanted divs from DOM---------------------------------------------------------------------------------------------->
        $("#TEMEcontainer").remove();
        $("#KDASTARTcontainer").remove();
        $("#METAcontainer").remove();
        $("#METAstart").remove();
        $("#MDFbubble, #MSEAbubble, #METAbubble, #KDAbubble, #DRUGbubble, #NETWORKbubble, #JACCARDbubble").hide();

        //sidebar flowchart
        $("#MDFflowChart").show();
        $("#MDFflowChart").next().css('opacity','0.5').show();
        $("#MSEAflowChart").css('opacity','0.5').show();
        $("#MSEAflowChart").next().css('opacity','0.5').show();
        $("#MSEAtoPharmflowChart").css('opacity','0.5').show();
        $("#MSEAtoPharmflowChart").next().css('opacity','0.5').show();
        $("#KDAflowChart").css('opacity','0.5').show();
        $("#KDAflowChart").next().css('opacity','0.5').show();
        $("#KDAtoPharmflowChart").css('opacity','0.5').show();

      }, 800); //End of setTimeout function
    }


    function startTEME() {
      //Remove animation from container---------------------------------------------------------->
      $("#TEMEcontainer").removeAttr("data-animate");
      $("#TEMEcontainer").removeClass("pulse animated");

      //Fade out and up the other two buttons------------------------------------------------------->
      $("#GWAScontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#KDASTARTcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#METAcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#TEMEstart").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#flowchart").animate({
        height: 0,
        opacity: 0
      }, 'slow');


      //Wait about until other buttons fade out and then change size of GWAS button------------------------------------------------------->
      window.setTimeout(function() {
        $("#TEMEcontainer").addClass("col_full").removeClass("col-lg-3 center bottommargin");
        $("#TEMEoutline").removeClass();
        $("#TEMEoutline").css({
          "height": "100px",
          "margin": "10px 0"
        });

        $("#TEMEbutton:first-child").removeClass();
        $("#TEMEbutton:first-child").addClass("button button-3d button-rounded button runm runm_pipeline noHover");

        $("#TEMEcontainer").hide().show("slide", {
          direction: "up"
        }, 500);
        $("#MSEAtoggle").show();

        //Remove unwanted divs from DOM---------------------------------------------------------------------------------------------->
        $("#GWAScontainer").remove();
        $("#KDASTARTcontainer").remove();
        $("#METAcontainer").remove();
        $("#TEMEstart").remove();
        $("#MDFbubble, #MSEAbubble, #METAbubble, #KDAbubble, #DRUGbubble, #NETWORKbubble, #JACCARDbubble").hide();

      }, 800); //End of setTimeout function

      // sidebar flowchart
      $("#MSEAflowChart").addClass("activePipe").html('<a href="#MSEAtoggle" id="MSEAtoggleNav" class="pipelineNav">MSEA</a>').show();
      $("#MSEAflowChart").next().css('opacity','0.5').show();
      $("#MSEAtoPharmflowChart").css('opacity','0.5').show();
      $("#MSEAtoPharmflowChart").next().css('opacity','0.5').show();
      $("#KDAflowChart").css('opacity','0.5').show();
      $("#KDAflowChart").next().css('opacity','0.5').show();
      $("#KDAtoPharmflowChart").css('opacity','0.5').show();

    }


    function startMETA() {
      //Remove animation from container---------------------------------------------------------->
      $("#METAcontainer").removeAttr("data-animate");
      $("#METAcontainer").removeClass("pulse animated");

      //Fade out and up the other two buttons------------------------------------------------------->
      $("#GWAScontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#KDASTARTcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#TEMEcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#METAstart").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#flowchart").animate({
        height: 0,
        opacity: 0
      }, 'slow');

      //Wait about until other buttons fade out and then change size of GWAS button------------------------------------------------------->
      window.setTimeout(function() {
        $("#METAcontainer").addClass("col_full").removeClass("col-lg-3 center bottommargin");
        $("#METAoutline").removeClass();
        $("#METAoutline").css({
          "height": "100px",
          "margin": "10px 0"
        });

        $("#METAbutton:first-child").removeClass();
        $("#METAbutton:first-child").addClass("button button-3d button-rounded button runm runm_pipeline noHover");

        $("#METAcontainer").hide().show("slide", {
          direction: "up"
        }, 500);
        $("#METAtoggle").show();

        //Remove unwanted divs from DOM---------------------------------------------------------------------------------------------->
        $("#GWAScontainer").remove();
        $("#KDASTARTcontainer").remove();
        $("#TEMEcontainer").remove();
        $("#METAstart").remove();
        $("#MDFbubble, #MSEAbubble, #METAbubble, #KDAbubble, #DRUGbubble, #NETWORKbubble, #JACCARDbubble").hide();

      }, 800); //End of setTimeout function

      // sidebar flowchart
      $("#METAflowChart").show();
      $("#METAflowChart").next().css('opacity','0.5').show();
      $("#MSEAtoPharmflowChart").css('opacity','0.5').show();
      $("#MSEAtoPharmflowChart").next().css('opacity','0.5').show();
      $("#KDAflowChart").css('opacity','0.5').show();
      $("#KDAflowChart").next().css('opacity','0.5').show();
      $("#KDAtoPharmflowChart").css('opacity','0.5').show();
    }


    function startKDA() {
      //Remove animation from container---------------------------------------------------------->
      $("#KDASTARTcontainer").removeAttr("data-animate");
      $("#KDASTARTcontainer").removeClass("pulse animated");

      //Fade out and up the other two buttons------------------------------------------------------->
      $("#GWAScontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#METAcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#TEMEcontainer").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#KDAstart").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#flowchart").animate({
        height: 0,
        opacity: 0
      }, 'slow');

      //Wait about until other buttons fade out and then change size of GWAS button------------------------------------------------------->
      window.setTimeout(function() {
        $("#KDASTARTcontainer").addClass("col_full").removeClass("col-lg-3 center bottommargin");
        $("#KDASTARToutline").removeClass();
        $("#KDASTARToutline").css({
          "height": "100px",
          "margin": "10px 0"
        });

        $("#KDASTARTbutton:first-child").removeClass();
        $("#KDASTARTbutton:first-child").addClass("button button-3d button-rounded button runm runm_pipeline noHover");


        $("#KDASTARTcontainer").hide().show("slide", {
          direction: "up"
        }, 500);
        $("#KDASTARTtoggle").show();

        //Remove unwanted divs from DOM---------------------------------------------------------------------------------------------->
        $("#GWAScontainer").remove();
        $("#METAcontainer").remove();
        $("#TEMEcontainer").remove();
        $("#KDAstart").remove();
        $("#MDFbubble, #MSEAbubble, #METAbubble, #KDAbubble, #DRUGbubble, #NETWORKbubble, #JACCARDbubble").hide();

      }, 800); //End of setTimeout function

      // sidebar flowchart
      $("#KDAflowChart").addClass("activePipe").html('<a href="#KDASTARTtoggle" id="KDASTARTtoggleNav" class="pipelineNav">KDA</a>').show();
      $("#KDAflowChart").next().css('opacity','0.5').show();
      $("#KDAtoPharmflowChart").css('opacity','0.5').show();
    }

    //Click the GWAS Enrichment program [Will only run once]------------------------------------------------------------------------>
    $("#GWASstart").one("click", function() {
      startGWAS();
      //Wait until GWAS button appears and then load MDF pipeline----------------------------------------------------->
      window.setTimeout(function() {
        $("#myLDPrune").load('/MDF_parameters.php');
        $('#MDFtogglet').click();
      }, 400);
    });

    $("#TEMEstart").one("click", function() {
      startTEME();
      //Wait until GWAS button appears and then load MDF pipeline----------------------------------------------------->
      window.setTimeout(function() {
        $("#myMSEA").load('/MSEA_parameters.php');
        $('#MSEAtogglet').click();

      }, 400);

    });

    $("#METAstart").one("click", function() {
      startMETA();
      //Wait until GWAS button appears and then load MDF pipeline----------------------------------------------------->
      window.setTimeout(function() {
        $("#myMETA").load('/META_buttons.php');
        $('#METAtogglet').click();
      }, 400);

    });
    $("#KDAstart").one("click", function() {
      startKDA();
      //Wait until GWAS button appears and then load MDF pipeline----------------------------------------------------->
      window.setTimeout(function() {
        $("#myKDASTART").load('/KDAstart_parameters.php');
        $('#KDASTARTtogglet').click();
      }, 400);



    });

    function preload(arrayOfImages) {
      $(arrayOfImages).each(function() {
        $('<img />').attr('src', this).appendTo('body').css('display', 'none');
      });
    }

    // Usage:

    preload([
      'include/pictures/OVERVIEW_2.png',
      'include/pictures/OVERVIEW_GWAS_2.png',
      'include/pictures/OVERVIEW_MSEA_2.png',
      'include/pictures/OVERVIEW_META_2.png',
      'include/pictures/OVERVIEW_KDA_2.png',
    ]);

    var img0 = new Image();
    img0.src = "include/pictures/OVERVIEW_2.png";
    var gwas0 = new Image();
    gwas0.src = "include/pictures/OVERVIEW_GWAS_2.png";
    var msea0 = new Image();
    msea0.src = "include/pictures/OVERVIEW_MSEA_2.png";
    var meta0 = new Image();
    meta0.src = "include/pictures/OVERVIEW_META_2.png";
    var kda0 = new Image();
    kda0.src = "include/pictures/OVERVIEW_KDA_2.png";




    $(".button-wrapper").click(function() {
      var $this = $(this).find('.button-inner');
      var name_type = $this.closest(".col-lg-3.center.bottommargin").attr('name');

      if ($this.hasClass("runm_active"))
      //Keep track if button is clicked-------------------------------------------------------------->
      {
        $this.data('clicked', true);
      } else {
        $this.data('clicked', false);
      }
      $('.runm.button-inner').removeClass('runm_active');
      $('.button.button-rounded.button-reveal.button-large.button-teal').hide();


      if ($this.data('clicked')) //if it's already been clicked, then do this
      {
        $this.parent().nextAll('.button.button-rounded.button-reveal.button-large.button-teal').eq(0).hide();
        $this.removeClass('runm_active');
        $("#flowchart").attr("src", img0.src);
        $this.data('clicked', false);

      } else //if it hasn't been clicked, then do this
      {
        $this.parent().nextAll('.button.button-rounded.button-reveal.button-large.button-teal').eq(0).show();
        $this.addClass('runm_active');
        if (name_type == "GWAS")
          $("#flowchart").attr("src", gwas0.src);
        else if (name_type == "MSEA")
          $("#flowchart").attr("src", msea0.src);
        else if (name_type == "META")
          $("#flowchart").attr("src", meta0.src);
        else
          $("#flowchart").attr("src", kda0.src);

        $this.data('clicked', true);

      }


    });


    function hasTouch() {
      return 'ontouchstart' in document.documentElement ||
        navigator.maxTouchPoints > 0 ||
        navigator.msMaxTouchPoints > 0;
    }

    if (hasTouch()) { // remove all the :hover stylesheets
      try { // prevent exception on browsers not supporting DOM styleSheets properly
        for (var si in document.styleSheets) {
          var styleSheet = document.styleSheets[si];
          if (!styleSheet.rules) continue;

          for (var ri = styleSheet.rules.length - 1; ri >= 0; ri--) {
            if (!styleSheet.rules[ri].selectorText) continue;

            if (styleSheet.rules[ri].selectorText.match(':hover')) {
              styleSheet.deleteRule(ri);
            }
          }
        }
      } catch (ex) {}
    }
  </script>

  <!-- Go To Top button
  ============================================= -->
  <div id="gotoTop" class="icon-angle-up"></div>

  <!-- External JavaScripts IMPORTANT!
  ============================================= -->
  <script src="include/js/plugins.js"></script>
  <script src="include/js/bs-filestyle.js"></script>
  <!-- <script src="include/js/bs-select.js"></script> -->
  <!-- Footer Scripts IMPORTANT!
  ============================================= -->
  <script src="include/js/functions.js"></script>







  <?php


  if (!empty($_GET['sessionID'])) {


  ?>

    <script>
      var string = "<?php echo $sessionID; ?>";
      var url = "/Data/Pipeline/Resources/session/" + string + "mergeomicsurl.js";

      $('#content').css('opacity', '0%');
      $('.modal-backdrop.fade').css({
        "opacity": "100%",
        "background-color": "white"
      });
      $('#loadIDmodal').modal('toggle');
      $("#sessionIDtitle").html("Session ID: " + string);
      $("#loadIDbody").html("<p class='instructiontext' style='font-size: 25px; margin:10px 0 0 0;padding:0px'>Loading session<span class = 'dots'>...</span><br>This may take a few seconds</p>");


      //Wait until GWAS button appears and then load MDF pipeline-----------------------------------------------------

      $.getScript(url, function() {
        $(document).ajaxStop(function() {
          setTimeout(function() {
            $('.modal-backdrop.fade').css('opacity', '0%');
            $('#content').css('opacity', '100%');
            $('#loadIDmodal').modal('hide');
          }, 1000);
        });

      });

    </script>


<?php
}else{
?>
<script type="text/javascript">
    var n = localStorage.getItem('on_load_session');
    console.log(n);
    //add data we are interested in tracking to an array
    var values = new Array();
    var oneday = new Date();
    oneday.setHours(oneday.getHours() + 24); //one day from now
    values.push(n);
    values.push(oneday);
    try {
      localStorage.setItem(0, values.join(";"));
    } catch (e) {}

    //check if past expiration date
    var values = localStorage.getItem(0).split(";");

    if (values[1] < new Date()) {
      localStorage.clear();
    }

    if (n === null) {
      //do nothing
    } else {
      $(window).on('load', function() {
        // Run code
        var result = confirm("Would you like to resume where you left off? \nSession ID: " + n + "\n(Note: Your session is available for 48 hrs)");

        if (result) {
          $(location).attr('href', '/runmergeomics.php?sessionID=' + n);
          localStorage.clear();
        } else {
          localStorage.clear();
        }
      });


    }
</script>
<?php  
}

if (isset($_GET['message']) ? $_GET['message'] : null) {
?>
    <script type="text/javascript">
      console.log("msg");
      alert("Session ID does not exist!");
    </script>
<?php
  } 
?>



</body>

</html>