 <?php
    //This parameters files is for when the user reviews their MSEA file


    /* Initialize PHP variables
sessionID = the saved session 

GET = if the user enters the link directly
POST = if PHP enters the link

*/

    if (isset($_GET['sessionID'])) {
        $sessionID = $_GET["sessionID"];
    }
    if (isset($_GET['marker_association'])) {
        $marker_association = $_GET["marker_association"];
    }
    if (isset($_GET['mapping'])) {
        $mapping = $_GET["mapping"];
    } else {
        $mapping = "None provided";
    }

    if (isset($_GET['module'])) {
        $module = $_GET["module"];
    }
    if (isset($_GET['module_info'])) {
        $module_info = $_GET["module_info"];
    } else {
        $module_info = "None provided";
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
    if (isset($_GET['mseanperm'])) {
        $mseanperm = $_GET["mseanperm"];
    }
    if (isset($_GET['mseatrim'])) {
        $mseatrim = $_GET["mseatrim"];
    }
    if (isset($_GET['mseafdr'])) {
        $mseafdr = $_GET["mseafdr"];
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

    if (isset($_GET['MAFConvert'])) {
        $MAFConvert = $_GET["MAFConvert"];
    }

    if (isset($_GET['MMFConvert'])) {
        $MMFConvert = $_GET["MMFConvert"];
    }

    if (isset($_GET['GSETConvert'])) {
        $GSETConvert = $_GET['GSETConvert'];
    }

    if (isset($_GET['rerun'])) {
        $rerun = $_GET['rerun'];
    }

    /* 
This grabs the email from the email form and stores it in a variable
*/
    if (isset($_GET['MSEAemail'])) {
        $emailid = $_GET['MSEAemail'];
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
        if (trim($domain) == 'ucla.edu') {
            $newid = "$name" . "@g.ucla.edu";
        } else {
            $newid = $emailid;
        }
        $myfile = fopen($femail, "w");
        fwrite($myfile, $newid);
        fclose($myfile);
    }


    //fpath variable that will be used later
    $fpath = "./Data/Pipeline/Resources/msea_temp/$sessionID";

    /***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we create a txt file with the path information
     ***************************************/
    if ($sessionID != null) {
        if ($rerun == "T") {
            $fjson = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "data.json";
            $json = array();

            //$fpath_random = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";

            //$num_iterations = file($fpath_random);
            //for ($i = 0; $i < (count($num_iterations)); $i++) {

            $json['session'] = $sessionID;
            //$fpathOut = "./Data/Pipeline/Resources/meta_temp/$new_random_string" . "PARAM";

            //$fdr_file = "./Data/Pipeline/Resources/meta_temp/$new_random_string" . "PARAM_SSEA_FDR";
            $json['perm'] = $perm_type;
            $json['maxgenes'] = $max_gene;
            $json['mingenes'] = $min_gene;
            $json['minoverlap'] = $minoverlap;
            $json['maxoverlap'] = $maxoverlap;
            $json['numperm'] = $mseanperm;
            $json['trim'] = $mseatrim;
            $json['fdrcutoff'] = $mseafdr;

            if ($mdf != "0") {
                $json['mdf'] = $mdf;
                $json['mdf_ntop'] = $mdf_ntop;
            }


            $json['association'] = $marker_association;


            $json['marker'] = $mapping;


            $json['geneset'] =  $module;

            $json['enrichment'] = $enrichment;

            if($module_info == "no"){
                $module_info = "None Provided";
            }
            $json['genedesc'] = $module_info;

            $json['MAFConvert'] = $MAFConvert;
            $json['MMFConvert'] = $MMFConvert;
            $json['GSETConvert'] = $GSETConvert;


            if (empty($data->data)) {
                $data['data'][] = $json;
            } else {
                $data->data[] = $json;
            }

            $fp = fopen($fjson, 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
            chmod($fjson, 0777);
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
        } else {
            //page is loaded from session load
            $fjson = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "data.json";
            if (file_exists($fjson)) {
                $data = json_decode(file_get_contents($fjson))->data;
                $perm_type = $data[0]->perm;
                $max_gene = $data[0]->maxgenes;
                $min_gene = $data[0]->mingenes;
                $minoverlap = $data[0]->minoverlap;
                $maxoverlap = $data[0]->maxoverlap;
                $mseanperm = $data[0]->numperm;
                $mseatrim = $data[0]->trim;
                $mseafdr = $data[0]->fdrcutoff;
                $marker_association = $data[0]->association;
                $mapping = $data[0]->marker;
                $mdf = $data[0]->mdf;
                $mdf_ntop = $data[0]->mdf_ntop;
                $module = $data[0]->geneset;
                $enrichment = $data[0]->enrichment;
                $module_info = $data[0]->genedesc;
                $MAFConvert = $data[0]->MAFConvert;
                $MMFConvert = $data[0]->MMFConvert;
                $GSETConvert = $data[0]->GSETConvert;
            }
        }
    }
    if ($mdf == "0" || empty($mdf)) {
        $mdf = "None provided";
    }
    if ($mapping == "0" || empty($mapping)) {
        $mapping = "None provided";
    }

    ?>





 <!--Instruction text that displays at the top ------->
 <h4 class="instructiontext" id="reviewtext">Please review the files you have selected/uploaded and the parameters you have selected in the overview chart below before executing the MSEA pipeline.</h4>
 <br>


 <!--Start Review table ------->
 <table class="table table-bordered review" style="text-align: center" ; id="MSEAreviewtable">
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
             <td rowspan=5 style="vertical-align: middle;">Files</td>
             <td>Association Data</td>
             <td style="font-weight: bold;">
                <?php 
                    echo (str_replace($sessionID, "", basename($marker_association))); 
                    $overview_write .= "Association Data" . "\t" . basename($marker_association) . "\n";
                ?>    
             </td>
         </tr>
         <!---------------------------------------------------------------------------------
              If the user has a MAPPING file (they will not if they said "No" to a mapping file) 
              then create a row for their mapping file choice
                -------------------------------------------------------------------------------->

         <tr>
             <td>Mapping File</td>
             <td style='font-weight: bold;'>
                <?php 
                    echo (str_replace($sessionID, "", basename($mapping))); 
                    $overview_write .= "Marker Mapping Data" . "\t" . basename($mapping) . "\n";
                ?>
            </td>
         </tr>
         <tr>
             <td>MDF File</td>
             <td style='font-weight:bold;'>
                <?php 
                    echo (str_replace($sessionID, "", basename($mdf))); 
                    $overview_write .= "Dependency Data" . "\t" . basename($mdf) . "\n";

                ?>
                 
             </td>
         </tr>
         <tr>
             <td>Gene Sets</td>
             <td style='font-weight:bold;'>
                <?php 
                    echo (str_replace($sessionID, "", basename($module)));
                    $overview_write .= "Gene Sets" . "\t" . basename($module) . "\n"; 
                ?>
                    
            </td>
         </tr>
         <tr>
             <td>Gene Sets Description</td>
             <td style='font-weight:bold;'>
                <?php 
                    echo (str_replace($sessionID, "", basename($module_info))); 
                    $overview_write .= "Gene Sets Description" . "\t" . basename($module_info) . "\n";
                    
                ?>
                    
            </td>
         </tr>
         <tr>
             <td rowspan="7" style="vertical-align: middle;">Parameters</td>
             <!-- Permutation column ------->
             <td>Permutation Type</td>
             <td style="font-weight: bold;">
                <?php 
                    if($perm_type=="locus"){
                        echo "marker";
                        $overview_write .= "Permutation Type" . "\tmarker\n";
                    }
                    else{
                        echo "$perm_type"; 
                        $overview_write .= "Permutation Type" . "\t" . trim("$perm_type") . "\n";
                    }
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
                    echo "$mseanperm"; 
                    $overview_write .= "Number of Permutations" . "\t" . trim("$mseanperm") . "\n";
                ?>
            </td>
         </tr>
         <tr>
             <td>
                 Trim extremes
             </td>
             <!--  Number of Permutations column ------->
             <td style="font-weight: bold;"> 
                <?php 
                    echo "$mseatrim"; 
                    $overview_write .= "Trim extremes" . "\t" . trim("$mseatrim") . "\n";
                ?>
            </td>
         </tr>
         <tr>
             <td>
                 MSEA to KDA export FDR cutoff
             </td>
             <!--  MSEA FDR Cutoff column ------->
             <td style="font-weight: bold;">
                <?php 
                    echo "$mseafdr";
                    $overview_write .= "MSEA to KDA Export FDR Cutoff" . "\t" . trim("$mseafdr") . "\n"; 
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
        if (isset($_GET['MSEAemail']) ? $_GET['MSEAemail'] : null) {
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

         <input type="text" name="MSEAemail" id="yourEmail_MSEA">

         <button type="button" class="button button-3d button-small nomargin" id="MSEAemailSubmit">Send email</button>
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
     <button type="button" class="button button-3d button-large nomargin" id="RunMSEAPipeline">Run MSEA Pipeline</button>
 </div>
 <!-- These divs are needed to enter some preloading information ---->
 <div id="emailconfirm_MSEA"></div>
 <div id="MSEAloading"></div>


 <script type="text/javascript">
     /**********************************************************************************************
Javascript functions/scripts (These are inlined because it was easier to do)
You can technically extract it and just call it externally if you want to keep the php page cleaner, but not needed
***********************************************************************************************/
     var string = "<?php echo $sessionID; ?>"; //get sessionID and store to javascript variable
     var run = "<?php echo $rerun ?>";
     $('html,body').animate({
         scrollTop: $("#MSEAtoggle").offset().top
     }); //scroll to the bottom


     /*This is the email submit event listener. Will reload the page with the email*/
     $("#MSEAemailSubmit").on('click', function(e) {
         var email = $("input[name=MSEAemail]").val();
         $('#myMSEA_review').empty();
         $('#myMSEA_review').load("/MSEA_moduleprogress.php?sessionID=" + string + "&MSEAemail=" + email);
         e.preventDefault();
         return false; //stops page from refreshing

     });

     /*This is the submit event listener. Will load run_SSEA */
     $("#RunMSEAPipeline").on('click', function() {
         $('#myMSEA_review').load("/run_MSEA.php?sessionID=" + string + "&run=T");
         $('#MSEAtab2').html('Results');
         $("#MSEAtab2").click();
         $("#MSEAtogglet").css("background-color", "#c5ebd4");
         $("#MSEAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1 - Marker Set Enrichment Analysis</div>`);

         return false;
     });
 </script>