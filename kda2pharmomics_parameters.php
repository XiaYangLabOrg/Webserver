<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";


if (isset($_GET['rmchoice'])) {
  $rmchoice = $_GET['rmchoice'];
}

if (isset($_POST['rmchoice'])) {
  $rmchoice = $_POST['rmchoice'];
}

if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}

if (isset($_POST['sessionID'])) {
  $sessionID = $_POST['sessionID'];
}
$sessionID2 = $sessionID;
$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";

if (file_exists($fsession)) {
  function replace_a_line($data, $rmchoice)
  {

    if (strpos($data, 'Pharmomics_Path') !== false) {
      $pharmomics_arr = preg_split("/[\t]/", $data);
      $pharmomics_arr2 = explode("|", $pharmomics_arr[1]);
      $msea2pharmomics = $pharmomics_arr2[0];
      //$kda2pharmomics = preg_replace('/\s+/', ' ', trim($pharmomics_arr2[1]));
      if ($rmchoice == 1) {
        return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|SSEAKDAtoPharmomics,1.0" . "\n";
      } else if ($rmchoice == 2) {
        return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|MSEAKDAtoPharmomics,1.0" . "\n";
      } else {
        return 'Pharmomics_Path:' . "\t" . $msea2pharmomics . "|METAKDAtoPharmomics,1.0" . "\n";
      }
    }
    return $data;
  }
  //$data = file($fsession); // reads an array of lines
  $handle = fopen($fsession, "r");
  $new_session_contetns = "";
  while (($line = fgets($handle)) !== false) {
    $new_session_contetns .= replace_a_line($line, $rmchoice);
  }
  fclose($handle);
  if (!empty($new_session_contetns)) {
    file_put_contents($fsession, $new_session_contetns);
  }
}

$file = "./Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_up_genes.txt";
$filename = "./Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_module_genes.txt";
$genefileOut = "./Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_genes.txt";
$kd_file = "./Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_KD_genes.txt";

if ($rmchoice == 1)
  $fullresults = "./Data/Pipeline/Results/ssea/$sessionID" . ".MSEA_merged_modules_full_result.txt";
else if ($rmchoice == 2)
  $fullresults = "./Data/Pipeline/Results/ssea/$sessionID" . ".MSEA_merged_modules_full_result.txt"; #JD change
else if ($rmchoice == 3)
  $fullresults = "./Data/Pipeline/Results/meta_ssea/$sessionID" . "_meta_result/ssea/$sessionID" . ".MSEA_merged_modules_full_result.txt";
else
  $fullresults = "./Data/Pipeline/Results/kda/$sessionID" . ".wKDA_kd_full_results.txt";



if (isset($_POST['kda_analysistype']) ? $_POST['kda_analysistype'] : null) {
  $kda_type = $_POST['kda_analysistype'];
  $overview_write = NULL;
  $overview_write .= "Description" . "\t" . "Filename/Parameter" . "\n";


  if ($kda_type == 1) {
    copy($file, "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_up_genes.txt"); //move files to shinyapp2_temp folder
    copy($filename, "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_module_genes.txt"); //move files to shinyapp2_temp folder
    $file = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_up_genes.txt"; //change path to shinyapp2
    $filename = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_module_genes.txt"; //change path to shinyapp2
    $kd_file = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_KD_genes.txt";
    $genefileOut = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_genes.txt";  //change path to shinyapp2

    $overview_fp = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_overview.txt";
    $overview_file = fopen($overview_fp, "w");
    $overview_write .= "Pharmomics Analysis Type" . "\t" . "Network Based Drug Positioning" . "\n";
    $net_fp = $_POST['kda_network_select'];
    $species_fp = $_POST['kda_species_select'];

    if ($net_fp == 1)
      $overview_write .= "Network Type" . "\t" . "User custom" . "\n";
    else if ($net_fp == 2)
      $overview_write .= "Network Type" . "\t" . "Liver" . "\n";
    else if ($net_fp == 3)
      $overview_write .= "Network Type" . "\t" . "Kidney" . "\n";
    else
      $overview_write .= "Network Type" . "\t" . "Multi-tissue" . "\n";

    if ($species_fp == 1)
      $overview_write .= "Species" . "\t" . "Human" . "\n";
    else
      $overview_write .= "Species" . "\t" . "Mouse" . "\n";
  } else {
    $overview_fp = "./Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_overview.txt";
    $overview_file = fopen($overview_fp, "w");
    $overview_write .= "Pharmomics Analysis Type" . "\t" . "Overlap Based Drug Positioning" . "\n";
  }


  if (isset($_POST['radiogroup']) ? $_POST['radiogroup'] : null) {
    $option = $_POST['radiogroup'];

    if ($option == 1) {
      copy($file, $genefileOut);
      if (!copy($file, $genefileOut))
        echo "failed to copy";


      $overview_write .= "Genes and Modules" . "\t" . "All genes from the subnetwork" . "\n";
    }
    if ($option == 2) {

      $overview_write .= "Genes and Modules" . "\t" . "All genes from input modules in the subnetwork" . "\n";

      $array = file($filename);
      $new_array = array();
      //$new_array[] = "GENE";

      // loop through array
      foreach ($array as $line) {

        // explode the line on tab. Note double quotes around \t are mandatory
        $line_array = explode("\t", $line);
        // set first element to the new array
        $new_array[] = trim($line_array[1]);
      }
      $finished = implode("\n", $new_array);

      $myfile = fopen($genefileOut, "w");
      fwrite($myfile, $finished);
      fclose($myfile);
      chmod($genefileOut, 0644);
    }
    if ($option == 3) {
      if (isset($_POST['modselect']) ? $_POST['modselect'] : null) {
        $a_arr = $_POST['modselect'];
        $modules = implode("|", $a_arr);
        $overview_write .= "Genes and Modules" . "\t" . "Genes from specific modules in the subnetwork" . "\n";
        $overview_write .= "Specific modules" . "\t" . $modules . "\n";
      }

      $json2 = array();

      $myfile = fopen($genefileOut, "w");

      $modulesArray = explode("|", $modules);

      $array = file($filename);

      for ($i = 0; $i < count($modulesArray); $i++) {

        // loop through array continuously based on amount of modules selected
        foreach ($array as $line) {


          // explode the line on tab. Note double quotes around \t are mandatory
          $line_array = explode("\t", $line);
          // set first element to the new array
          $modulecheck = trim($line_array[0]);


          if (strpos($modulecheck, $modulesArray[$i]) !== false) //check if the sector is in the line
          {
            //if found, pass the node into an array; this will create an array of genes that corresponds to the module
            $new_gene = trim($line_array[1]);
            $json2['module'] = $modulecheck;
            $json2['gene'] = $new_gene;
            $write = "$new_gene\n";
            fwrite($myfile, $write);
            $data2[] = $json2;
          }
        }
      }

      fclose($myfile);

      chmod($genefileOut, 0644);

      //print json_encode($data2);

    }
    if ($option == 4) {

      copy($kd_file, $genefileOut);


      $overview_write .= "Genes and Modules" . "\t" . "Significant (FDR<0.05) key drivers" . "\n";
    }
  }

  fwrite($overview_file, $overview_write);
  fclose($overview_file);
  chmod($overview_fp, 0644);
}




$counter = 0;
$json = array();
$detailsarray = file($fullresults);

foreach ($detailsarray as $detail) {
  // Skip header.
  if ($counter++ == 0) continue;
  if ($rmchoice == 1 || $rmchoice == 2 || $rmchoice == 3) {
    $line_array = explode("\t", $detail);
    //$json['module'] = strtoupper(trim($line_array[0]));
    $json['module'] = trim($line_array[0]); # JD change
    $json['pval'] =  scientificNotation(trim((float)$line_array[1]));
    $json['fdr'] =  scientificNotation(trim((float)$line_array[2]));
    $json['desc'] = trim($line_array[7]);
  } else {
    $line_array = explode("\t", $detail);
    //$json['module'] = strtoupper(trim($line_array[0]));
    $json['module'] = trim($line_array[0]);
    $json['pval'] =  scientificNotation(trim((float)$line_array[2]));
    $json['fdr'] =  scientificNotation(trim((float)$line_array[3]));
    $json['desc'] = trim($line_array[11]);
  }

  $data[] = $json;
}


?>

<style type="text/css">
  .genetitle {
    text-align: center;
    border: 2px solid #346084;

    background: #4682b4;
    color: white;
    font-size: 24px;

    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }

  select[disabled]>option {

    border-bottom: 1px solid #4949491a;
  }


  div.dataTables_wrapper div.dataTables_filter label {
    text-align: center !important;
  }

  .samplefile th,
  .samplefile td {
    padding: 0.25rem !important;
    height: 30px !important;
  }
</style>

<div id="errormsg_kda2pharm" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_kda2pharm" style="white-space: pre;"></p>
</div>


<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">

  <!-- Description ===================================================== -->
  <h4 class="instructiontext">
    This part of the pipeline performs overlap based drug repositioning based on genes from the subnetwork created by wKDA
  </h4>


  <!--Start kda2pharm Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_kda2pharm"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox_kda2pharm' style="display: none;"></div>
  <!--End kda2pharm Tutorial --------------------------------------->



</div>
<!--End of gridcontainer ----->



<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="kda2pharmomics_parameters.php" name="select" id="kda2pharmdataform">
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center" ; id="kda2pharmmaintable">

      <thead>
        <tr>
          <!--First row of table------------Column Headers------------------------------>
          <th>Drug Repositioning Analysis</th>
          <th name="val_kda2pharm">Genes & Modules</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!--Second row of table------------------------------------------>
          <td>
            <h4 class="instructiontext" style="font-size: 15px;">Select network or overlap based <br> drug repositioning analysis</h4>

            <div class="radioholder kda2pharm">
              <!-- Jess deleted nobottommargin class------------------->
              <input type="radio" id="kda_analysis_overlap" name="kda_analysistype" value="2" <?php if (isset($_POST['kda_analysistype']) ? $_POST['kda_analysistype'] : null) {
                                                                                                $a = $_POST['kda_analysistype'];
                                                                                                if ($a == 2) {
                                                                                                  echo "checked";
                                                                                                }
                                                                                              } else {
                                                                                                echo "";
                                                                                              }  ?>>
              <label for="kda_analysis_overlap">Overlap Based Drug Repositioning</label>
            </div>
            <div class="radioholder kda2pharm">
              <input type="radio" id="kda_analysis_network" name="kda_analysistype" value="1" <?php if (isset($_POST['kda_analysistype']) ? $_POST['kda_analysistype'] : null) {
                                                                                                $a = $_POST['kda_analysistype'];
                                                                                                if ($a == 1) {
                                                                                                  echo "checked";
                                                                                                }
                                                                                              } else {
                                                                                                echo "";
                                                                                              }  ?>>
              <label for="kda_analysis_network">Network Based Drug Repositioning</label>
            </div>

            <div class="kda_network_analysis" <?php if (isset($_POST['kda_analysistype']) ? $_POST['kda_analysistype'] : null) {
                                                $a = $_POST['kda_analysistype'];
                                                if ($a == 1) {
                                                  echo '';
                                                }
                                              } else {
                                                echo 'style="display:none;"';
                                              }  ?>>
              <h4 class="instructiontext" style="font-size: 15px;padding:10px;">Select a network</h4>
              <div class="selectholder kda2pharm">
                <select class="btn dropdown-toggle btn-light" name="kda_network_select" size="1" id="myNetwork_kda">
                  <option value="0" disabled <?php if (isset($_POST['kda_network_select']) ? $_POST['kda_network_select'] : null) {
                                                echo "";
                                              } else {
                                                echo "selected";
                                              } ?>>Please select option</option>
                  <option value="1" <?php if (isset($_POST['kda_network_select']) ? $_POST['kda_network_select'] : null) {
                                      $a = $_POST['kda_network_select'];
                                      if ($a == 1) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Upload network file</option>
                  <option value="2" <?php if (isset($_POST['kda_network_select']) ? $_POST['kda_network_select'] : null) {
                                      $a = $_POST['kda_network_select'];
                                      if ($a == 2) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Sample liver network</option>
                  <option value="3" <?php if (isset($_POST['kda_network_select']) ? $_POST['kda_network_select'] : null) {
                                      $a = $_POST['kda_network_select'];
                                      if ($a == 3) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Sample kidney network</option>
                  <option value="4" <?php if (isset($_POST['kda_network_select']) ? $_POST['kda_network_select'] : null) {
                                      $a = $_POST['kda_network_select'];
                                      if ($a == 4) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Sample multi-tissue network</option>
                </select>
              </div>

            </div>
            <div id="NetApp2KDAupload" style="display: none;">
              <!-- Start of upload div--->
              <br>
              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file (Max unique nodes: 12500)</div>
              <div class="input-file-container" name="Network for App2" style="width: fit-content;">
                <input class="input-file" id="NetworkApp2uploadInputkda" name="NetworkApp2uploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="NetworkApp2labelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file ...</label>
                <!--Progress bar ------------------------------>
                <div id="NetworkApp2progressbar" class="progress active" style='display: none;'>
                  <div id="NetworkApp2progresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="NetworkApp2progresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="NetworkApp2filereturn" class="file-return"></p>
                <span id='NetworkApp2_uploaded_file'></span>
              </div>
            </div> <!-- End of upload div--->
            <div class="alert-app2" id="alert2KDA"></div>
            <div class="kda_network_analysis" <?php if (isset($_POST['kda_analysistype']) ? $_POST['kda_analysistype'] : null) {
                                                $a = $_POST['kda_analysistype'];
                                                if ($a == 1) {
                                                  echo '';
                                                }
                                              } else {
                                                echo 'style="display:none;"';
                                              }  ?>>
              <h4 class="instructiontext" style="font-size: 15px;padding:10px;">Select species</h4>
              <div class="selectholder kda2pharm">
                <select class="btn dropdown-toggle btn-light" name="kda_species_select" size="1" id="mySpecies_kda">
                  <option value="0" disabled <?php if (isset($_POST['kda_species_select']) ? $_POST['kda_species_select'] : null) {
                                                echo "";
                                              } else {
                                                echo "selected";
                                              } ?>>Please select option</option>
                  <option value="1" <?php if (isset($_POST['kda_species_select']) ? $_POST['kda_species_select'] : null) {
                                      $a = $_POST['kda_species_select'];
                                      if ($a == 1) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Human</option>
                  <option value="2" <?php if (isset($_POST['kda_species_select']) ? $_POST['kda_species_select'] : null) {
                                      $a = $_POST['kda_species_select'];
                                      if ($a == 2) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Mouse</option>
                </select>
              </div>

            </div>

            <!-- Add Nov 2020 ----------------->
            <div class="alert alert-warning kda_network_analysis" style="margin: 0 auto; width: 90%;margin-top: 10px;display: none;">
              <i class="icon-warning-sign" style="margin-right: 6px;font-size: 12px;"></i>Currently, for network repositioning in KDA to PharmOmics, we query only meta signatures and limit uploaded networks to 12500 unique nodes to save computational load. You may run network repositioning using all drug signatures (meta and dose/time segregated) and/or on a larger network from the indepedent <a href="http://mergeomics.research.idre.ucla.edu/runpharmomics.php">PharmOmics pipeline</a> which will require a login. You may download the input genes based on selections made on this form after clicking 'Click to Review'. Also, we are working to include more sample tissue-specific networks.
            </div>

          </td>
          <td name="val1_kda2pharm">


            <h4 class="instructiontext" style="font-size: 15px;">Select genes and modules from the subnetwork to run <br> drug repositioning analysis</h4>



            <div class="radioholder kda2pharm">
              <input id="radio-1" name="radiogroup" type="radio" value="1" <?php if (isset($_POST['radiogroup']) ? $_POST['radiogroup'] : null) {
                                                                              $a = $_POST['radiogroup'];
                                                                              if ($a == 1) {
                                                                                echo "checked";
                                                                              }
                                                                            } else {
                                                                              echo "";
                                                                            }  ?>>
              <label for="radio-1" class="radio-style-3-label">All genes from the subnetwork</label>
            </div>

            <div class="radioholder kda2pharm">
              <input id="radio-2" name="radiogroup" type="radio" value="2" <?php if (isset($_POST['radiogroup']) ? $_POST['radiogroup'] : null) {
                                                                              $a = $_POST['radiogroup'];
                                                                              if ($a == 2) {
                                                                                echo "checked";
                                                                              }
                                                                            } else {
                                                                              echo "";
                                                                            }  ?>>
              <label for="radio-2" class="radio-style-3-label">All genes from input modules in the subnetwork</label>
            </div>

            <div class="radioholder kda2pharm">
              <input id="radio-3" name="radiogroup" type="radio" value="3" <?php if (isset($_POST['radiogroup']) ? $_POST['radiogroup'] : null) {
                                                                              $a = $_POST['radiogroup'];
                                                                              if ($a == 3) {
                                                                                echo "checked";
                                                                              }
                                                                            } else {
                                                                              echo "";
                                                                            }  ?>>
              <label for="radio-3" class="radio-style-3-label">Genes from specific modules in the subnetwork</label>
            </div>
            <div class="radioholder kda2pharm">
              <input id="radio-4" name="radiogroup" type="radio" value="4" <?php if (isset($_POST['radiogroup']) ? $_POST['radiogroup'] : null) {
                                                                              $a = $_POST['radiogroup'];
                                                                              if ($a == 4) {
                                                                                echo "checked";
                                                                              }
                                                                            } else {
                                                                              echo "";
                                                                            }  ?>>
              <label for="radio-3" class="radio-style-3-label">Significant (FDR<0.05) key drivers</label>
            </div>

            <!-- <div id="kda2pharm_description"></div> -->




          </td>
          <!--End Second row------------------------------------------>
        </tr>
      </tbody>
    </table>
  </div>


  <div id="modulesection" class="row" <?php if (isset($_POST['modselect']) ? $_POST['modselect'] : null) {
                                        echo '';
                                      } else {
                                        echo 'style="display: none;"';
                                      }  ?>>
    <!--Start of module selection ------>

    <div class="table-responsive">
      <div id="rec_message_kda2pharm" style="width: 100%;text-align: center;font-size: 18px;"></div>
      <table id="moduleselecttable" class="table table-striped table-bordered" cellspacing="0" style="width: 100%;text-align: center;">
        <caption>Showing modules with KDs having FDR < 0.05.</caption>
        <thead>
          <tr>
            <th>Selection<br><input name="select_all" value="1" type="checkbox"></th>
            <th>Module</th>
            <?php
            if ($rmchoice == 4) { ?>
              <th>Top KD P-Value</th>
              <th>Top KD FDR</th>
            <?php
            } else { ?>
              <th>MSEA P-Value</th>
              <th>MSEA FDR</th>
            <?php
            }
            ?>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <?php

          if ((file_exists($filename))) {
            $eachlines = file($filename);
            $new_array = array();
            foreach ($eachlines as $line) { //add php code here


              $line_array = explode("\t", $line);
              $new_array[] = $line_array[0];
            }
            $new_array = array_unique($new_array);

            foreach ($new_array as $value) {
              $key = array_search($value, array_map(function ($v) {
                return $v['module'];
              }, $data));
              $modp = $data[$key]['pval'];
              $modfdr = $data[$key]['fdr'];
              $moddesc = $data[$key]['desc'];
              if (isset($_POST['modselect']) ? $_POST['modselect'] : null) {
                $a_arr = $_POST['modselect'];
                $a = implode("|", $a_arr);
                $b = explode("|", $a);
                $c = 0;
                foreach ($b as $key => $item) {
                  if ($value == $item) //check if the sector is in the line
                  {
                    echo "<tr><td>" . ' <input type="checkbox" name="modselect[]" value= "' . $value . '" checked></td><td>' . $value . '</td><td>' . $modp . '</td><td>' . $modfdr . '</td><td>' . $moddesc . '</td></tr>';
                    unset($b[$key]);
                    $c = $c + 1;
                  }
                }

                if ($c == 0) {
                  echo "<tr><td>" . ' <input type="checkbox" name="modselect[]" value= "' . $value . '" ></td><td>' . $value . '</td><td>' . $modp . '</td><td>' . $modfdr . '</td><td>' . $moddesc . '</td></tr>';
                } else {
                  $c = $c - 1;
                }
              } else {
                echo "<tr><td>" . ' <input type="checkbox" name="modselect[]" value= "' . $value . '" ></td><td>' . $value . '</td><td>' . $modp . '</td><td>' . $modfdr . '</td><td>' . $moddesc . '</td></tr>';
              }
            }
          }


          ?>

        </tbody>
      </table>

    </div>
    <!--End of table responsive div ---->




  </div>
  <!--End of module selection --->


  <!---------------------------Start of gene display  (needed?)----------------------------------------->
  <!--       <div id="genesection" class="row" <?php if (isset($_POST['modselect']) ? $_POST['modselect'] : null) {
                                                  echo '';
                                                } else {
                                                  echo 'style="display: none;"';
                                                }  ?>> 
                            <div class="col-lg-5 col-sm-5 col-xs-12" style="margin: 40px auto;">
                                <div class="genetitle">Genes</div>
                                <select style="border: 2px; text-align:center;" name="genefrom" id="geneselect" class="form-control" size="8" multiple="multiple" disabled> 
                                </select>
                            </div>
  
                        </div> -->
  <!--End of gene display--->

</form>
<!--End of kda2pharm form -------------------------------------->

<!----------------------------------------End of kda2pharm maintable ----------------------------------------------->

<!-------------------------------------------------Start Review button ----------------------------------------------------->
<div id="Validatediv_kda2pharm" style="text-align: center;">
  <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_kda2pharm">Click to Review</button>
  <div id="preload_kda2pharm"></div>
</div>
<!-------------------------------------------------End Review button ----------------------------------------------------->

<?php
if (isset($_POST['modselect']) ? $_POST['modselect'] : null) {
  if ($kda_type == 1)
    $fselectpathOut = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".KDA2PHARM_selectedmodules.txt";
  else
    $fselectpathOut = "./Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_selectedmodules.txt";



  $fpselect = fopen($fselectpathOut, "w");
  $a_arr = $_POST['modselect'];
  $a = implode("|", $a_arr);
  $b = explode("|", $a);

  $hold_array = array();



  foreach ($b as $item) {
    $key = array_search($item, array_map(function ($v) {
      return $v['module'];
    }, $data));
    $modp = $data[$key]['pval'];
    $modfdr = $data[$key]['fdr'];
    $moddesc = $data[$key]['desc'];
    $write = "$item\t$modp\t$modfdr\t";
    foreach ($data2 as $value) {
      if (strpos($item, $value['module']) !== false) //check if the sector is in the line
      {
        $hold_array[] = $value['gene']; // epIJp9
      }
    }
    $geneselected = implode("|", $hold_array);
    $writedesc = "$moddesc";
    fwrite($fpselect, $write);
    fwrite($fpselect, $geneselected . "\t");
    fwrite($fpselect, $writedesc . "\n");
    unset($hold_array); // $foo is gone
    $hold_array = array(); // $foo is here again
    // query to delete where item = $item
  }


  fclose($fpselect);
  chmod($fselectpathOut, 0777);
}

if ($rmchoice == 1) {
?>
  <script type="text/javascript">
    $('html,body').animate({
      scrollTop: $("#wKDAtoggle").offset().top
    });

    function kda2pharmreview() //This function gets the review table for wKDA
    {
      var choice = $('input[name="radiogroup"]:checked').val(),
        networktype = $("select[name='kda_network_select'] option").filter(':selected').val(),
        analysistype = $("input[name='kda_analysistype']:checked").val(),
        speciestype = $("select[name='kda_species_select'] option").filter(':selected').val(),
        rm = 1;

      if (analysistype == 1) {
        $.ajax({
          url: "kda2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            radiogroup: choice,
            kda_network_select: networktype,
            kda_analysistype: analysistype,
            kda_species_select: speciestype,
            rmchoice: rm
          },
          success: function(data) {
            $('#mypharmOmics_review').html(data);
          }
        });
      } else {
        $.ajax({
          url: "kda2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            radiogroup: choice,
            kda_analysistype: analysistype,
            rmchoice: rm
          },
          success: function(data) {
            $('#mypharmOmics_review').html(data);
          }
        });
      }
      $('#pharmOmicstab2').show();
      $('#pharmOmicstab2').click();

    }

    ///////////////Start Submit Function (wKDA form) -- Function for clicking 'Click to review button'///////////////////////////////////

    $('#kda2pharmdataform').submit(function(e) {

      e.preventDefault();
      var form_data = new FormData(document.getElementById('kda2pharmdataform'));
      form_data.append("sessionID", string);


      //kda2pharmreview()
      $.ajax({
        'url': 'kda2pharmomics_parameters.php?rmchoice=<?php echo $rmchoice ?>',
        'type': 'POST',
        'data': form_data,
        processData: false,
        contentType: false,
        'success': function(data) {
          $("#mypharmOmics").html(data);
          kda2pharmreview()
        }
      });

    });
    /////////////////////////////////////////////End submit function for SSEA form//////////////////////////////////////////////////////
  </script>

<?php
} else if ($rmchoice == 2) {
?>
  <script type="text/javascript">
    // $('html,body').animate({
    //   scrollTop: $("#MSEA2KDAtoggle").offset().top
    // });

    function kda2pharmreview() //This function gets the review table for wKDA
    {
      var choice = $('input[name="radiogroup"]:checked').val(),
        networktype = $("select[name='kda_network_select'] option").filter(':selected').val(),
        analysistype = $("input[name='kda_analysistype']:checked").val(),
        speciestype = $("select[name='kda_species_select'] option").filter(':selected').val(),
        rm = 2;

      if (analysistype == 1) {

        $.ajax({
          url: "kda2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            radiogroup: choice,
            kda_network_select: networktype,
            kda_analysistype: analysistype,
            kda_species_select: speciestype,
            rmchoice: rm
          },
          success: function(data) {
            $('#myKDA2PHARM_review').html(data);
          }
        });
      } else {
        $.ajax({
          url: "kda2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            radiogroup: choice,
            kda_analysistype: analysistype,
            rmchoice: rm
          },
          success: function(data) {
            $('#myKDA2PHARM_review').html(data);
          }
        });
      }
      $('#KDA2PHARMtab2').show();
      $('#KDA2PHARMtab2').click();

    }

    ///////////////Start Submit Function (wKDA form) -- Function for clicking 'Click to review button'///////////////////////////////////

    $('#kda2pharmdataform').submit(function(e) {

      e.preventDefault();
      var form_data = new FormData(document.getElementById('kda2pharmdataform'));
      form_data.append("sessionID", string);

      $.ajax({
        'url': 'kda2pharmomics_parameters.php?rmchoice=2',
        'type': 'POST',
        'data': form_data,
        processData: false,
        contentType: false,
        'success': function(data) {
          $("#myKDA2PHARM").html(data);
          kda2pharmreview()
        }
      });

    });
    /////////////////////////////////////////////End submit function for SSEA form//////////////////////////////////////////////////////
  </script>

<?php
} else if ($rmchoice == 3) {
?>
  <script type="text/javascript">
    $('html,body').animate({
      scrollTop: $("#META2KDAtoggle").offset().top
    });

    function kda2pharmreview() //This function gets the review table for wKDA
    {
      var choice = $('input[name="radiogroup"]:checked').val(),
        networktype = $("select[name='kda_network_select'] option").filter(':selected').val(),
        analysistype = $("input[name='kda_analysistype']:checked").val(),
        speciestype = $("select[name='kda_species_select'] option").filter(':selected').val(),
        rm = 3;

      if (analysistype == 1) {

        $.ajax({
          url: "kda2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            radiogroup: choice,
            kda_network_select: networktype,
            kda_analysistype: analysistype,
            kda_species_select: speciestype,
            rmchoice: rm
          },
          success: function(data) {
            $('#myMETAKDA2PHARM_review').html(data);
          }
        });
      } else {
        $.ajax({
          url: "kda2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            radiogroup: choice,
            kda_analysistype: analysistype,
            rmchoice: rm
          },
          success: function(data) {
            $('#myMETAKDA2PHARM_review').html(data);
          }
        });
      }
      $('#METAKDA2PHARMtab2').show();
      $('#METAKDA2PHARMtab2').click();

    }

    ///////////////Start Submit Function (wKDA form) -- Function for clicking 'Click to review button'///////////////////////////////////

    $('#kda2pharmdataform').submit(function(e) {
      //kda2pharmreview()
      e.preventDefault();

      var form_data = new FormData(document.getElementById('kda2pharmdataform'));
      form_data.append("sessionID", string);

      $.ajax({
        'url': 'kda2pharmomics_parameters.php?rmchoice=3',
        'type': 'POST',
        'data': form_data,
        processData: false,
        contentType: false,
        'success': function(data) {
          $("#myMETAKDA2PHARM").html(data);
          kda2pharmreview()
        }
      });

    });
    /////////////////////////////////////////////End submit function for SSEA form//////////////////////////////////////////////////////
  </script>

<?php
} else {
?>
  <script type="text/javascript">
    // $('html,body').animate({
    //   scrollTop: $("#KDASTARTtoggle").offset().top
    // });

    function kda2pharmreview() //This function gets the review table for wKDA
    {
      var choice = $('input[name="radiogroup"]:checked').val(),
        networktype = $("select[name='kda_network_select'] option").filter(':selected').val(),
        analysistype = $("input[name='kda_analysistype']:checked").val(),
        speciestype = $("select[name='kda_species_select'] option").filter(':selected').val(),
        rm = 4;

      if (analysistype == 1) {

        $.ajax({
          url: "kda2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            radiogroup: choice,
            kda_network_select: networktype,
            kda_analysistype: analysistype,
            kda_species_select: speciestype,
            rmchoice: rm
          },
          success: function(data) {
            $('#myKDASTART2PHARM_review').html(data);
          }
        });
      } else {
        $.ajax({
          url: "kda2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            radiogroup: choice,
            kda_analysistype: analysistype,
            rmchoice: rm
          },
          success: function(data) {
            $('#myKDASTART2PHARM_review').html(data);
          }
        });
      }
      $('#KDASTART2PHARMtab2').show();
      $('#KDASTART2PHARMtab2').click();

    }

    ///////////////Start Submit Function (wKDA form) -- Function for clicking 'Click to review button'///////////////////////////////////

    $('#kda2pharmdataform').submit(function(e) {
      e.preventDefault();

      var form_data = new FormData(document.getElementById('kda2pharmdataform'));
      form_data.append("sessionID", string);
      $.ajax({
        'url': 'kda2pharmomics_parameters.php?rmchoice=4',
        'type': 'POST',
        'data': form_data,
        processData: false,
        contentType: false,
        'success': function(data) {
          $("#myKDASTART2PHARM").html(data);
          kda2pharmreview()
        }
      });

    });
    /////////////////////////////////////////////End submit function for SSEA form//////////////////////////////////////////////////////
  </script>

<?php
}


?>



<script type="text/javascript">
  var string = localStorage.getItem("on_load_session");
  console.log("kda2pharparam:" + string);
  $(document).ready(function() {
    $("#kda_analysis_overlap").click();
    $("#radio-1").click();
  });

  $("#KDAflowChart").next().addClass("activeArrow");
  var rmchoice = "<?php echo $rmchoice; ?>";
  if (rmchoice == 1) { // SSEA
    var link = "#pharmOmicstoggle";
  }
  else if (rmchoice == 2){ // ETPM
    var link = "#KDA2PHARMtoggle";
  }
  else if (rmchoice == 3){ // meta
    var link = "#METAKDA2PHARMtoggle";
  }
  else{ // KDA start
    var link = "#KDASTART2PHARMtoggle";
  }
  $("#KDAtoPharmflowChart").addClass("activePipe").html('<a href="' + link +'" class="pipelineNav" id="KDAtoPharmNav">KDA to Pharmomics</a>').css("opacity","1");

  $("#KDAtoPharmNav").on('click', function(e){
    var href = $(this).attr('href');
    console.log(href);
    if ($(href).children('.togglec').css('display') == 'none') {
        $(href).children(0).click();
    }
    var val = $(href).offset().top - $(window).scrollTop() - 65;
    if (val<=0 || ($(window).scrollTop()!=0 && $(window).scrollTop() < $(href).offset().top)){ 
      // below item or scrolled down but not below item
      var val = $(href).offset().top - 65;
    } 

    $(window).scrollTop(
      val
    );

    return false;
  });


  //////////////////////////////////////////////Start Tutorial Button script'///////////////////////////////////

  //var myTutButton_kda2pharm = document.getElementById("myTutButton_kda2pharm");
  var val_kda2pharm = 0;


  //begin function for when button is clicked-------------------------------------------------------------->
  /*
  myTutButton_kda2pharm.addEventListener("click", function() {

    //If tutorial is already opened yet, then do this-------------------------------------------------------------->
    if (val_kda2pharm == 1) {


      $('.tutorialbox_kda2pharm').hide();
      //$("#kda2pharm_description").html('');

      $('#kda2pharmmaintable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });


      $("#myTutButton_kda2pharm").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'
      val_kda2pharm = val_kda2pharm - 1;

    }

    //If tutorial is not opened yet, then do this-------------------------------------------------------------->
    else {

      $('#kda2pharmmaintable').find('th[name="val_kda2pharm"]').eq(-1).after('<th name="tut">Tutorial</th>');




      if ($('#radio-1').is(":checked")) {

        $('#kda2pharmmaintable').find('td[name="val1_kda2pharm"]').eq(-1).after(`
                                              <td name="tut" style="font-size: 1.5rem;">
                                          All the genes that are part of the subnetwork created by wKDA. <br> This includes genes that are not part of any of the input modules but are part of the input network and connected to a gene from an input module to some degree based on the search depth.
                                           </td>

                                          `);

        // $("#kda2pharm_description").html(`<div><div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div> All the genes that are part of the subnetwork created by wKDA. This includes genes that are not part of any of the input modules but are part of the input network and connected to a gene from an input module to some degree based on the search depth. </div>`); 
      } else if ($('#radio-2').is(":checked")) {


        $('#kda2pharmmaintable').find('td[name="val1_kda2pharm"]').eq(-1).after(`
                                          <td name="tut" style="font-size: 1.5rem;">
                                           Only genes that were orginally members of the input modules and part of the subnetwork. (Not all genes in the input modules are included. They must be in the subnetwork)
                                           </td>

                                          `);

        // $("#kda2pharm_description").html(`<div><div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>  Only genes that were orginally members of the input modules and part of the subnetwork. (Not all genes in the input modules are included. They must be in the subnetwork) </div>`);

      } else {


        $('#kda2pharmmaintable').find('td[name="val1_kda2pharm"]').eq(-1).after(`
                                               <td name="tut" style="font-size: 1.5rem;">
                                         User can choose specific modules that contains the genes that were part of the subnetwork. 
                                           </td>

                                          `);

        // $("#kda2pharm_description").html(`<div><div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div> User can choose specific modules that contains the genes that were part of the subnetwork. </div>`);

      }

      $('.tutorialbox_kda2pharm').show();
      $('.tutorialbox_kda2pharm').html('This step performs Jaccard score-based drug repositioning that calculates the similarity of drug signatures with network genes. This is part of our PharmOmics tool.');


      $("#myTutButton_kda2pharm").html("Close Tutorial"); //Change name of button to 'Close Tutorial'

      val_kda2pharm = val_kda2pharm + 1;

    }


  });
  */

  $("input[name='radiogroup']").change(function() {
    if (val_kda2pharm == 1 && $('#radio-1').is(":checked")) {
      $("td[name='tut']").html('All the genes that are part of the subnetwork created by wKDA. <br> This includes genes that are not part of any of the input modules but are part of the input network and connected to a gene from an input module to some degree based on the search depth.');
    }

    if (val_kda2pharm == 1 && $('#radio-2').is(":checked")) {
      $("td[name='tut']").html(' Only genes that were orginally members of the input modules and part of the subnetwork. (Not all genes in the input modules are included. They must be in the subnetwork)');
    }

    if (val_kda2pharm == 1 && $('#radio-3').is(":checked")) {
      $("td[name='tut']").html(' User can choose specific modules that contains the genes that were part of the subnetwork. ');
    }

    if ($('#radio-3').is(":checked")) {

      $("#modulesection, #genesection").animate({
        opacity: 'show',
        height: 'show'
      }, 'slow');
      $("#rec_message_kda2pharm").html(`<div class="alert alert-warning"><i class="icon-warning-sign"></i><strong>Recommended:</strong> FDR < 0.05</div>`).hide().fadeIn();
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
      $('html,body').animate({
        scrollTop: $("#modulesection").offset().top - 90
      });

    } else {
      $("#modulesection, #genesection").animate({
        opacity: 'hide',
        height: 'hide'
      }, 'slow');
    }
  })

  //NETWORK FILE UPLOAD EVENT HANDLER
  $("#NetworkApp2uploadInputkda").on("change", function() {
    $("#NetworkApp2labelname").html("Select another file?");
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 2500000) {
      alert("File Size is too big");
      var control = $("#NetworkApp2uploadInputkda"); //get the id
      control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", "./Data/Pipeline/Resources/shinyapp2_temp/");
      fd.append("data_type", "network_app2");
      fd.append("session_id", string);
      console.log(session_id);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload_app2Network.php', true);

      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          $('#NetworkApp2progressbar').show();
          var percentComplete = (e.loaded / e.total) * 100;
          $('#NetworkApp2progresswidth').width(percentComplete.toFixed(2) + '%');
          $('#NetworkApp2progresspercent').html(percentComplete.toFixed(2) + '%');
        }
      };

      xhr.onload = function() {
        if (this.status == 200) {
          var resp = JSON.parse(this.response);
          $('#NetworkApp2progresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#NetworkApp2progressbar').hide();
          console.log(resp.targetPath);
          if (resp.status == 1) {
            //var fullPath = resp.targetPath;
            //network_file = fullPath.replace("./Data/Pipeline/", "");
            //var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#NetworkApp2filereturn').html(name);
            $('#NetworkApp2_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;padding: 0% 0% 0% 0.3%;"></i><strong>Upload successful!</strong></div>`);
          } else {
            $('#NetworkApp2_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong>' + resp.msg + '</div>');
            var control = $("#NetworkApp2uploadInputkda"); //get the id
            control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#NetworkApp2filereturn").empty();
          }
        };
      };
      xhr.send(fd);
    }
  });
  $("#NetworkApp2labelname").on("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      $("#NetworkApp2uploadInputkda").focus();
    }
  });
  $("#NetworkApp2labelname").on("click", function(event) {
    $("#NetworkApp2uploadInputkda").focus();
    return false;
  });

  //////////////////////////////Populate Genes from Specific modules/////////////////////////////////////////(Right now it only works with 1 selection)
  /* $("#search").change(function(){

  $('#geneselect').empty();
  var selectedValues = $(":checkbox:checked").toArray().map(item => item.value).join();
  alert(selectedValues);
  var geneArray = [];
   $.get(file, function (data) 
   {
      var stimuliArray = data.split('\n').map(function(ln){
      return ln.split('\t');
    });
      //alert(stimuliArray[0][1]);
   
    var lines = data.split("\n");
    $.each(lines, function (n, elem) {

        if(elem.indexOf(selectedValues) != -1)
        {   
            geneArray.push(stimuliArray[n][1]);
        }

    });


      var option = '';
      for (var i=0;i<geneArray.length;i++){
         option += '<option value="'+ i + '">' + geneArray[i] + '</option>';
      }
      $('#geneselect').append(option);
          //var genes = geneArray.join("\n");

}, 'text');

}); */

  //////////////////////////////////////////////End Tutorial Button script'///////////////////////////////////






  ///////////////Start Validation/REVIEW button -- Function for clicking 'Click to review button'///////////////////////////////////
  $("#Validatebutton_kda2pharm").on('click', function() {


    var analysis = $("input[name='kda_analysistype']:checked").val(),
      networktype = $("#myNetwork_kda").prop('selectedIndex'),
      speciestype = $("#mySpecies_kda").prop('selectedIndex'),
      genemodulechoice = $("input[name='radiogroup']:checked").val(),
      arr = [],
      errorlist = [];

    if ($("input:radio[name='kda_analysistype']").is(':checked')) {
      if (analysis == 1) {
        if (networktype == 0) {
          errorlist.push('A network has not been selected!');
        }
        if (speciestype == 0) {
          errorlist.push('A species has not been selected!');
        }
      }

    } else {
      errorlist.push('A drug repositioning analysis has not been selected!');
    }

    if ($("input:radio[name='radiogroup']").is(':checked')) {
      if (genemodulechoice == 3) {
        $("input[name='modselect[]']:checked").each(function() {
          arr.push(this.value);
        });
        if (arr.length === 0) {
          errorlist.push('No specific modules have been selected!');
        }

      }

    } else {
      errorlist.push('Modules have not been selected!');
    }

    if (errorlist.length === 0) {
      $(this).html('Please wait ...')
        .attr('disabled', 'disabled');
      $("#preload_kda2pharm").html(`<h4 style="padding: 5px" class='instructiontext'>Loading genes....<br>This may take a few seconds <br> <img src='include/pictures/ajax-loader.gif' /></h4>`);

      if (genemodulechoice == 1 || genemodulechoice == 2) {
        $("#modulesection").remove();
      }

      $("#kda2pharmdataform").submit();
    } else {
      var result = errorlist.join("\n");
      //alert(result);
      $('#errorp_kda2pharm').html(result);
      $("#errormsg_kda2pharm").fadeTo(2000, 500).slideUp(500, function() {
        $("#errormsg_kda2pharm").slideUp(500);
      });

    }


    return false;









  });
</script>

<script src="include/js/multiselect.min.js"></script>

<script type="text/javascript">
  // set up radio boxes
  $('.radioholder.kda2pharm').each(function() {
    $(this).children().hide();
    var description = $(this).children('label').html();
    $(this).append('<span class="desc">' + description + '</span>');
    $(this).prepend('<span class="tick"></span>');
    // on click, update radio boxes accordingly
    $(this).click(function() {
      $(this).children('input').prop('checked', true);
      $(this).children('input').trigger('change');
    });
  });
  // update radio holder classes when a radio element changes
  $('.radioholder.kda2pharm :input').change(function() {
    $('.radioholder.kda2pharm :input').each(function() {
      if ($(this).prop('checked') == true) {
        $(this).parent().addClass('activeradioholder');
      } else $(this).parent().removeClass('activeradioholder');
    });
  });
  // manually fire radio box change event on page load
  $('.radioholder.kda2pharm :input').change();



  // set up select boxes
  $('.selectholder.kda2pharm').each(function() {
    $(this).children().hide();
    var description = $(this).children('select').find(":selected").text();
    $(this).append('<span class="desc">' + description + '</span>');
    $(this).append('<span class="pulldown"></span>');
    // set up dropdown element
    $(this).append('<div class="selectdropdown"></div>');
    $(this).children('select').children('option').each(function() {
      if ($(this).attr('value') != '0') {
        $drop = $(this).parent().siblings('.selectdropdown');
        var name = $(this).text();
        $drop.append('<span>' + name + '</span>');
      }
    });
    // on click, show dropdown
    $(this).click(function() {
      if ($(this).hasClass('activeselectholder')) {
        // roll up roll up
        $(this).children('.selectdropdown').slideUp(200);
        $(this).removeClass('activeselectholder');
        // change span back to selected option text
        if ($(this).children('select').val() != '0') {
          $(this).children('.desc').fadeOut(100, function() {
            $(this).text($(this).siblings("select").find(":selected").text());
            $(this).fadeIn(100);
          });
        }
      } else {
        // if there are any other open dropdowns, close 'em
        $('.activeselectholder.kda2pharm').each(function() {
          $(this).children('.selectdropdown').slideUp(200);
          // change span back to selected option text
          if ($(this).children('select').val() != '0') {
            $(this).children('.desc').fadeOut(100, function() {
              $(this).text($(this).siblings("select").find(":selected").text());
              $(this).fadeIn(100);
            });
          }
          $(this).removeClass('activeselectholder');
        });
        // roll down
        $(this).children('.selectdropdown').slideDown(200);
        $(this).addClass('activeselectholder');
        // change span to show select box title while open
        if ($(this).children('select').val() != '0') {
          $(this).children('.desc').fadeOut(100, function() {
            $(this).text($(this).siblings("select").children("option[value=0]").text());
            $(this).fadeIn(100);
          });
        }
      }
    });
  });
  // select dropdown click action
  $('.selectholder.kda2pharm .selectdropdown span').click(function() {

    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    var value = $(this).text();
    $(this).parent().siblings('select').val(value);
    $(this).parent().siblings('.desc').fadeOut(100, function() {
      $(this).text(value);
      $(this).fadeIn(100);
    });
    $(this).parent().siblings('select').children('option:contains("' + value + '")').prop('selected', 'selected');

    //Show select file box when option 1 is selected
    var select = $("#myNetwork_kda").find('option:selected').index();
    if (select != 1)
      //$("#myNetwork_kda").parent().next().hide();
      $("#NetApp2KDAupload").hide();
    if (select == 1)
      //$("#myNetwork_kda").parent().next().show();
      $("#NetApp2KDAupload").show();
    /*if (select > 1)
      $("#myNetwork_kda").parent().nextAll(".alert-app2").eq(0).html(successalert).hide().fadeIn(300);*/
    if (select == 1)
      //$("#myNetwork_kda").parent().nextAll(".alert-app2").eq(0).html(uploadalert).hide().fadeIn(300);
      $("#alert2KDA").eq(0).html(uploadalertpharm).hide().fadeIn(300);

    else
      //$("#myNetwork_kda").parent().nextAll(".alert-app2").eq(0).empty();
      $("#alert2KDA").eq(0).empty();
  });

  var uploadalertpharm = `<div style="padding:0% 25%;">
              <p style="margin: 0;"><b>File format</b></p>
          <table class="samplefile">
            <thead>
            <tr>
              <th style="font-size: 16px;">HEAD</th>
              <th style="font-size: 16px;">TAIL</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td data-column="MARKER(Header): ">A1BG</td>
              <td data-column="VALUE(Header): ">SNHG6</td>
            </tr>
            <tr>
              <td data-column="MARKER(Header): ">A1BG</td>
              <td data-column="VALUE(Header): ">UNC84A</td>
            </tr>
            <tr>
              <td data-column="MARKER(Header): ">A1CF</td>
              <td data-column="VALUE(Header): ">KIAA1958</td>
            </tr>
            </tbody>
          </table>
              </div>`;

  $("#myNetwork_kda").on("change", function() {
    var select = $(this).find('option:selected').index();
    if (select != 1)
      $(this).parent().next().hide();

    if (select == 1)
      $(this).parent().next().show();

    /*if (select > 1)
      $(this).parent().nextAll(".alert-app2").eq(0).html(successalert).hide().fadeIn(300);*/
    if (select == 1)
      $(this).parent().nextAll(".alert-app2").eq(0).html(uploadalertpharm).hide().fadeIn(300);
    else
      $(this).parent().nextAll(".alert-app2").eq(0).empty();
  });

  $("input[name='kda_analysistype']").change(function() {

    var select_analysis = $("input[name='kda_analysistype']:checked").val();
    if (select_analysis == 1) {
      $(".kda_network_analysis").show();

    } else {
      $(".kda_network_analysis").hide();
    }


  });


  $('#moduleselecttable').dataTable({
    "columnDefs": [{
        "width": "10%",
        "targets": [0, 2, 3]
      },
      {
        "width": "50%",
        "targets": 4
      },
      {
        "width": "20%",
        "targets": 1
      }

    ],
    "order": [
      [3, "asc"]
    ]

  });




  // Handle click on table cells with checkboxes
  $('#moduleselecttable').on('click', 'tbody td, thead th:first-child', function(e) {
    $(this).parent().find('input[type="checkbox"]').trigger('click');
    if ($(this).parent().find('input[type="checkbox"]').is(":checked")) { //If the checkbox is checked
      $(this).parent().find('input[type="checkbox"]').closest('tr').addClass("highlight_row");
      //Add class on checkbox checked
    } else {
      $(this).parent().find('input[type="checkbox"]').closest('tr').removeClass("highlight_row");
      //Remov.phpe class on checkbox uncheck
    }
  });





  // Handle click on "Select all" control
  $('#moduleselecttable thead input[name="select_all"]').on('click', function(e) {
    if (this.checked) {
      $('#moduleselecttable tbody input[type="checkbox"]:not(:checked)').trigger('click');
    } else {
      $('#moduleselecttable tbody input[type="checkbox"]:checked').trigger('click');
    }

    // Prevent click event from propagating to parent
    e.stopPropagation();
  });




  /*--------------------Gene box display jquery -------------------------------------
var file = "/Data/Pipeline/Resources/shinyapp3_temp/" + '<?php echo $sessionID; ?>' + 'MODULE_GENES';
   $("input[type='checkbox']").change(function (e) {
    

  $('#geneselect').empty();
  var selectedValues = $(":checkbox:checked").toArray().map(item => item.value);

  var geneArray = [];
   $.get(file, function (data) 
   {
      var stimuliArray = data.split('\n').map(function(ln){
      return ln.split('\t');
    });
      var lines = data.split("\n");

     selectedValues.forEach(function(item, index) {
       $.each(lines, function (n, elem) {

        if(elem.indexOf(selectedValues[index]) != -1)
        {   
            geneArray.push(stimuliArray[n][1]);
        }

    });

});
   

      var option = '';
      for (var i=0;i<geneArray.length;i++){
         option += '<option value="'+ i + '">' + geneArray[i] + '</option>';
      }
      $('#geneselect').append(option);
          //var genes = geneArray.join("\n");

}, 'text');

});

*/

  $('#search').multiselect({
    keepRenderingSort: true
  });
</script>