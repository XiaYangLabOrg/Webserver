<?php


 if(isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {
        $sessionID=$_GET['sessionID'];
        $fsession = "./Data/Pipeline/Resources/session/$sessionID"."_session.txt";
        // Create an array of the current session file
        $session = explode("\n", file_get_contents($fsession));
        //Create different array elements based on new line
        $pipe_arr = preg_split("/[\t]/", $session[0]);
        $pipeline = $pipe_arr[1];
        $mergeomics_arr = preg_split("/[\t]/", $session[1]);
        $mergeomics_path = $mergeomics_arr[1];
        $pharmomics_arr = preg_split("/[\t]/", $session[2]);
        $pharmomics_split = explode("|", $pharmomics_arr[1]);
        $msea2pharm_path = $pharmomics_split[0];
        $kda2pharm_path = $pharmomics_split[1];   

         if($pipeline == "GWAS")
         {
           $mdf_arr = preg_split("/[\t]/", $session[3]);
           $mdfskipped = $mdf_arr[1];
         } 


    $json = json_encode(array(
     "GWAS" => array(
        "1" => "$('#myLDPrune').load('/MDF_parameters.php?sessionID=$sessionID');$('#MDFtogglet').click();",
        "1.25" => "$('#myLDPrune_review').load('/MDF_moduleprogress.php?sessionID=$sessionID');$('#MDFtab2').show();$('#MDFtab2').click();",
        "1.5" => "$('#myLDPrune_review').load('/run_MDF.php?sessionID=$sessionID');$('#MDFtab2').show();$('#MDFtab2').click();",
        "1.75" => "$('#myLDPrune_review').load('/result_MDF.php?sessionID=$sessionID');$('#MDFtab2').show();$('#MDFtab2').click();",
        "2" => "$('#SSEAtoggle').show(); $('#mySSEA').load('/SSEA_parameters.php?sessionID=$sessionID');$('#SSEAtogglet').click();",
        "2.25" => "$('#mySSEA_review').load('/SSEA_moduleprogress.php?sessionID=$sessionID');$('#SSEAtab2').show();$('#SSEAtab2').click();",
        "2.5" => "$('#mySSEA_review').load('/run_SSEA.php?sessionID=$sessionID');$('#SSEAtab2').show();$('#SSEAtab2').click();",
        "2.75" => "$('#mySSEA_review').load('/result_SSEA.php?rmchoice=1&sessionID=$sessionID');$('#SSEAtab2').show();$('#SSEAtab2').click();",
        "3" => "$('#wKDAtoggle').show();$('#mywKDA').load('/wKDA_parameters.php?rmchoice=1&sessionID=$sessionID');$('#wKDAtogglet').click();",
        "3.25" => "$('#mywKDA_review').load('/wKDA_moduleprogress.php?rmchoice=1&sessionID=$sessionID');$('#wKDAtab2').show();$('#wKDAtab2').click();",
        "3.5" => "$('#mywKDA_review').load('/run_wKDA.php?rmchoice=1&sessionID=$sessionID');$('#wKDAtab2').show();$('#wKDAtab2').click();",
        "3.75" => "$('#mywKDA_review').load('/result_wKDA.php?rmchoice=1&sessionID=$sessionID');$('#wKDAtab2').show();$('#wKDAtab2').click();"
     ),
     "GWASskipped" => array(
        "1" => "/SSEAskipped_parameters.php",
        "1.25" => "/SSEA_moduleprogress.php?skippedMDF=1",
        "1.5" => "/run_SSEA.php",
        "1.75" => "/result_SSEA.php",
        "2" => "/wKDA_parameters.php?rmchoice=1",
        "2.25" => "/wKDA_moduleprogress.php?rmchoice=1",
        "2.5" => "/run_wKDA.php?rmchoice=1",
        "2.75" => "/result_wKDA.php?rmchoice=1"
     ),
     "MSEA" => array(
        "1" => "/MSEA_parameters.php",
        "1.25" => "/MSEA_moduleprogress.php",
        "1.5" => "/run_MSEA.php",
        "1.75" => "/result_SSEA.php?rmchoice=2",
        "2" => "/wKDA_parameters.php?rmchoice=2",
        "2.25" => "/wKDA_moduleprogress.php?rmchoice=2",
        "2.5" => "/run_wKDA.php?rmchoice=2",
        "2.75" => "/result_wKDA.php?rmchoice=2"
     ),
     "META" => array(
        "1" => "/MDF_parameters.php",
        "1.25" => "/MDF_moduleprogress.php",
        "1.5" => "/run_MDF.php",
        "1.75" => "/result_MDF.php",
        "2" => "/SSEA_parameters.php",
        "2.25" => "/SSEA_moduleprogress.php",
        "2.5" => "/run_SSEA.php",
        "2.75" => "/result_SSEA.php",
        "3" => "/wKDA_parameters.php?rmchoice=1",
        "3.25" => "/wKDA_moduleprogress.php?rmchoice=1",
        "3.5" => "/run_wKDA.php?rmchoice=1",
        "3.75" => "/result_wKDA.php?rmchoice=1"
     ),
     "KDA" => array(
        "1" => "/wKDA_parameters.php?rmchoice=4",
        "1.25" => "/wKDA_moduleprogress.php?rmchoice=4",
        "1.5" => "/run_wKDA.php?rmchoice=4",
        "1.75" => "/result_wKDA.php?rmchoice=4"
     ),
     "SSEAtoPharmomics" => array(
        "1" => "/MDF_parameters.php",
        "1.25" => "/MDF_moduleprogress.php",
        "1.5" => "/run_MDF.php",
        "1.75" => "/result_MDF.php",
        "2" => "/SSEA_parameters.php",
        "2.25" => "/SSEA_moduleprogress.php",
        "2.5" => "/run_SSEA.php",
        "2.75" => "/result_SSEA.php",
        "3" => "/wKDA_parameters.php?rmchoice=1",
        "3.25" => "/wKDA_moduleprogress.php?rmchoice=1",
        "3.5" => "/run_wKDA.php?rmchoice=1",
        "3.75" => "/result_wKDA.php?rmchoice=1"
     ),
     "MSEAtoPharmomics" => array(
        "1" => "/MDF_parameters.php",
        "1.25" => "/MDF_moduleprogress.php",
        "1.5" => "/run_MDF.php",
        "1.75" => "/result_MDF.php",
        "2" => "/SSEA_parameters.php",
        "2.25" => "/SSEA_moduleprogress.php",
        "2.5" => "/run_SSEA.php",
        "2.75" => "/result_SSEA.php",
        "3" => "/wKDA_parameters.php?rmchoice=1",
        "3.25" => "/wKDA_moduleprogress.php?rmchoice=1",
        "3.5" => "/run_wKDA.php?rmchoice=1",
        "3.75" => "/result_wKDA.php?rmchoice=1"
     ),
     "KDAtoPharmomics" => array(
        "1" => "/MDF_parameters.php",
        "1.25" => "/MDF_moduleprogress.php",
        "1.5" => "/run_MDF.php",
        "1.75" => "/result_MDF.php",
        "2" => "/SSEA_parameters.php",
        "2.25" => "/SSEA_moduleprogress.php",
        "2.5" => "/run_SSEA.php",
        "2.75" => "/result_SSEA.php",
        "3" => "/wKDA_parameters.php?rmchoice=1",
        "3.25" => "/wKDA_moduleprogress.php?rmchoice=1",
        "3.5" => "/run_wKDA.php?rmchoice=1",
        "3.75" => "/result_wKDA.php?rmchoice=1"
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

      while ($x <= $mergeomics_path)
      {
       
        if($mergeomics_path > 1.5 && ($x == 1.25 || $x==1.5))
        {
          $x = $x + 0.25;
          continue; 
        }

        if($mergeomics_path > 2.5 && ($x == 2.25 || $x==2.5))
         {
            $x = $x + 0.25;
            continue; 
        }

        if($mergeomics_path > 3.5 && ($x == 3.25 || $x== 3.5))
         {
          $x = $x + 0.25;
          continue; 
        }

        $write_url .= $url[$pipeline][strval($x)]."\n";
        $x = $x + 0.25;
      }
      $furlOut = "./Data/Pipeline/Resources/session/$sessionID"."mergeomicsurl.js";
      $fp = fopen($furlOut, 'w');
      fwrite($fp, $write_url);
      fclose($fp);
      chmod($furlOut, 0775);


}





?>