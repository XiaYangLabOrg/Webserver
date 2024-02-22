<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['rmchoice'])) {
  $rmchoice = $_GET['rmchoice'];
}




if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}

if (isset($_POST['sessionID'])) {
  $sessionID = $_POST['sessionID'];
}


$fsession = $ROOT_DIR."Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
if (file_exists($fsession)) {
  function replace_a_line($data, $rmchoice)
  {

    if (strpos($data, 'Pharmomics_Path') !== false) {
      $pharmomics_arr = preg_split("/[\t]/", $data);
      $pharmomics_arr2 = explode("|", $pharmomics_arr[1]);
      //$msea2pharmomics = $pharmomics_arr2[0];
      $kda2pharmomics = preg_replace('/\s+/', ' ', trim($pharmomics_arr2[1]));
      if ($rmchoice == 1) {
        return 'Pharmomics_Path:' . "\t" . "SSEAtoPharmomics,1.0|" . $kda2pharmomics . "\n";
      } else if ($rmchoice == 2) {
        return 'Pharmomics_Path:' . "\t" . "MSEAtoPharmomics,1.0|" . $kda2pharmomics . "\n";
      } else {
        return 'Pharmomics_Path:' . "\t" . "METAtoPharmomics,1.0|" . $kda2pharmomics . "\n";
      }
    }
    return $data;
  }
  //$data = file($fsession); // reads an array of lines
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

if (!empty($_POST)) {
  $fp = fopen($fpostOut, "w");
  foreach ($_POST as $key => $value) {
    $postwrite .= $key . "\t" . $value . "\n";
  }
  fwrite($fp, $postwrite);
  fclose($fp);
  chmod($fpostOut, 0774);
}



//OVERVIEW text file
if (isset($_POST['analysistype']) ? $_POST['analysistype'] : null) {
  $pharmtype = $_POST['analysistype'];
  $overview_write = NULL;
  $overview_write .= "Description" . "\t" . "Filename/Parameter" . "\n";


  if ($pharmtype == 1) {
    $fpostOut = $ROOT_DIR."Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_shinyapp2_postdata.txt";
    $overview_fp = $ROOT_DIR."Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_overview.txt";
    $overview_file = fopen($overview_fp, "w");
    $overview_write .= "Pharmomics Analysis Type" . "\t" . "Network Based Drug Positioning" . "\n";
    $net_fp = $_POST['network_select'];
    $species_fp = $_POST['species_select'];

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
    $fpostOut = $ROOT_DIR."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . "_shinyapp3_postdata.txt";
    $overview_fp = $ROOT_DIR."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".SSEA2PHARM_overview.txt";
    $overview_file = fopen($overview_fp, "w");
    $overview_write .= "Pharmomics Analysis Type" . "\t" . "Overlap Based Drug Positioning" . "\n";
  }



  $module_fp = $_POST['modulegroup'];

  if ($module_fp == 1) {
    $sig_fp = $_POST['sig_measure'];
    $thr_fp = $_POST['sig_threshold'];
    $overview_write .= "Modules Selection" . "\t" . "All modules passing P/FDR threshold" . "\n";
    $overview_write .= "Significance Measure" . "\t" . $sig_fp . "\n";
    $overview_write .= "Significane Threshold" . "\t" . $thr_fp . "\n";
  } else {
    $specific_fp = $_POST['moduleselect'];
    $a_fp = implode("|", $specific_fp);
    $overview_write .= "Modules Selection" . "\t" . "Select specific modules" . "\n";
    $overview_write .= "Specific Modules" . "\t" . $a_fp . "\n";
  }

  $genes_fp = $_POST['genegroup'];

  if ($genes_fp == 1)
    $overview_write .= "Genes Selection" . "\t" . "All genes from original gene set" . "\n";
  else
    $overview_write .= "Genes Selection" . "\t" . "Only genes mapped from SNPs" . "\n";

  fwrite($overview_file, $overview_write);
  fclose($overview_file);
  chmod($overview_fp, 0644);
}

if (!empty($_POST)) {
  $fp = fopen($fpostOut, "w");
  foreach ($_POST as $key => $value) {
    $postwrite .= $key . "\t" . $value . "\n";
  }
  fwrite($fp, $postwrite);
  fclose($fp);
  chmod($fpostOut, 0774);
}



if ($rmchoice == 1 || $rmchoice == 2)
  $fullresults = $ROOT_DIR."Data/Pipeline/Results/ssea/$sessionID" . ".MSEA_modules_full_result.txt";
else
  $fullresults = $ROOT_DIR."Data/Pipeline/Results/meta_ssea/" . "$sessionID" . "_meta_result/ssea/" . "$sessionID" . ".MSEA_modules_full_result.txt";

$count = 1;
$json = array();
$detailsarray = file($fullresults);

foreach ($detailsarray as $detail) {
  $count++; // Note that first iteration is $count = 1 not 0 here.
  if ($count <= 2) continue; //skip header


  $line_array = explode("\t", $detail);
  if(trim($line_array[0])=="_ctrlA") continue;
  if(trim($line_array[0])=="_ctrlB") continue;
  //$json['module'] = strtoupper(trim($line_array[0]));
  $json['module'] = trim($line_array[0]);
  $json['pval'] =  scientificNotation(trim((float)$line_array[1]));
  $json['fdr'] =  scientificNotation(trim((float)$line_array[6]));
  $json['numgenes'] = trim($line_array[3]);
  $json['desc'] = trim($line_array[7]);


  $data[] = $json;
}


?>
<style type="text/css">
  .samplefile th,
  .samplefile td {
    padding: 0.25rem !important;
    height: 30px !important;
  }
</style>

<div id="errormsg_ssea2pharm" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_ssea2pharm" style="white-space: pre;"></p>
</div>


<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">

  <!-- Description ===================================================== -->
  <h4 class="instructiontext">
    This part of the pipeline performs either network based drug repositioning or overlap based drug repositioning (PharmOmics) based on genes created by MSEA.
  </h4>


  <!--Start ssea2pharm Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_ssea2pharm"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox' style="display: none;"></div>
  <!--End ssea2pharmTutorial --------------------------------------->



</div>
<!--End of gridcontainer ----->





<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="ssea2pharmomics_parameters.php" name="select" id="ssea2pharmdataform">
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center;" ; id="ssea2pharmmaintable">

      <thead>
        <tr>
          <!--First row of table------------Column Headers------------------------------>
          <th>Drug Repositioning Analysis</th>
          <th>Modules</th>
          <th name="val_ssea2pharm">Genes</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!--Second row of table------------------------------------------>
          <td>
            <h4 class="instructiontext" style="font-size: 18px;">Select network or overlap based <br> drug repositioning analysis</h4>

            <div class="radioholder ssea2pharm">
              <input type="radio" id="analysis_overlap" name="analysistype" value="2" <?php if (isset($_POST['analysistype']) ? $_POST['analysistype'] : null) {
                                                                                        $a = $_POST['analysistype'];
                                                                                        if ($a == 2) {
                                                                                          echo "checked";
                                                                                        }
                                                                                      } else {
                                                                                        echo "";
                                                                                      }  ?>>
              <label for="analysis_overlap">Overlap Based Drug Repositioning</label>
            </div>

            <div class="radioholder ssea2pharm" id="removeclass">
              <input type="radio" id="analysis_network" name="analysistype" value="1" <?php if (isset($_POST['analysistype']) ? $_POST['analysistype'] : null) {
                                                                                        $a = $_POST['analysistype'];
                                                                                        if ($a == 1) {
                                                                                          echo "checked";
                                                                                        }
                                                                                      } else {
                                                                                        echo "";
                                                                                      }  ?>>
              <label for="analysis_network">Network Based Drug Repositioning</label>
            </div>

            <div class="network_analysis" style="display: none;">
              <h4 class="instructiontext" style="font-size: 15px;padding:10px;">Select a network</h4>
              <div class="selectholder ssea2pharm">
                <select class="btn dropdown-toggle btn-light" name="network_select" size="1" id="myNetwork">
                  <option value="0" disabled <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                                echo "";
                                              } else {
                                                echo "selected";
                                              } ?>>Please select option</option>
                  <option value="1" <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                      $a = $_POST['network_select'];
                                      if ($a == 1) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Upload Network</option>
                  <option value="2" <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                      $a = $_POST['network_select'];
                                      if ($a == 2) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Sample liver network</option>
                  <option value="3" <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                      $a = $_POST['network_select'];
                                      if ($a == 3) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Sample kidney network</option>
                  <option value="4" <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                      $a = $_POST['network_select'];
                                      if ($a == 4) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Sample multi-tissue network</option>
                </select>
              </div>

            </div>

            <div id="NetApp2upload" style="display: none;">
              <!-- Start of upload div--->
              <br>
              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file (Max unique nodes: 12500)</div>
              <div class="input-file-container" name="Network for App2" style="width: fit-content;">
                <input class="input-file" id="NetworkApp2uploadInput" name="NetworkApp2uploadedfile" type="file" accept="text/plain" data-show-preview="false">
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
            <div class="alert-app2" id="alert2MSEA"></div>

            <div class="network_analysis" <?php if (isset($_POST['analysistype']) ? $_POST['analysistype'] : null) {
                                            $a = $_POST['analysistype'];
                                            if ($a == 1) {
                                              echo '';
                                            }
                                          } else {
                                            echo 'style="display:none;"';
                                          }  ?>>
              <h4 class="instructiontext" style="font-size: 15px;padding:10px;">Select species</h4>
              <div class="selectholder ssea2pharm">
                <select class="btn dropdown-toggle btn-light" name="species_select" size="1" id="mySpecies">
                  <option value="0" disabled <?php if (isset($_POST['species_select']) ? $_POST['species_select'] : null) {
                                                echo "";
                                              } else {
                                                echo "selected";
                                              } ?>>Please select option</option>
                  <option value="1" <?php if (isset($_POST['species_select']) ? $_POST['species_select'] : null) {
                                      $a = $_POST['species_select'];
                                      if ($a == 1) {
                                        echo "selected";
                                      }
                                    } else {
                                      echo "";
                                    }  ?>>Human</option>
                  <option value="2" <?php if (isset($_POST['species_select']) ? $_POST['species_select'] : null) {
                                      $a = $_POST['species_select'];
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
            <div class="alert alert-warning network_analysis" style="margin: 0 auto; width: 90%;margin-top: 10px;display: none;">
              <i class="icon-warning-sign" style="margin-right: 6px;font-size: 12px;"></i>Currently, for network repositioning in MSEA to PharmOmics, we query only meta signatures and limit uploaded networks to 12500 unique nodes to save computational load. You may run network repositioning using all drug signatures (meta and dose/time segregated) and/or on a larger network from the indepedent <a href="http://mergeomics.research.idre.ucla.edu/runpharmomics.php">PharmOmics pipeline</a> which will require a login. You may download the input genes based on selections made on this form after clicking 'Click to Review'. Also, we are working to include more sample tissue-specific networks.
            </div>



          </td>
          <td>

            <h4 class="instructiontext" style="font-size: 18px;">Select modules based on significance threshold or select specific modules</h4>

            <div class="radioholder ssea2pharm">
              <input type="radio" id="modules_all" name="modulegroup" value="1" <?php if (isset($_POST['modulegroup']) ? $_POST['modulegroup'] : null) {
                                                                                  $a = $_POST['modulegroup'];
                                                                                  if ($a == 1) {
                                                                                    echo "checked";
                                                                                  }
                                                                                } else {
                                                                                  echo "";
                                                                                }  ?>>
              <label for="modules_all">All modules from gene set</label>
            </div>

            <table id="sig_table" <?php if (isset($_POST['modulegroup']) ? $_POST['modulegroup'] : null) {
                                    $a = $_POST['modulegroup'];
                                    if ($a == 1) {
                                      echo "";
                                    } else {
                                      echo 'style="display:none;"';
                                    }
                                  } else {
                                    echo 'style="display:none;"';
                                  }  ?>>
              <div id="rec_message" class="alert alert-warning" <?php if (isset($_POST['modulegroup']) ? $_POST['modulegroup'] : null) {
                                                                  $a = $_POST['modulegroup'];
                                                                  if ($a == 1) {
                                                                    echo "";
                                                                  } else {
                                                                    echo 'style="display:none;"';
                                                                  }
                                                                } else {
                                                                  echo 'style="display:none;"';
                                                                }  ?>>
                <i class="icon-warning-sign"></i><strong>Recommended:</strong> FDR < 0.05 </div> <TD>Significance Measure
          </TD>
          <TD>
            <div class="selectholder ssea2pharm" style="text-align: left;">
              <select class="btn dropdown-toggle btn-light" name="sig_measure" size="1" id="measure">
                <option value="FDR" <?php if (isset($_POST['sig_measure']) ? $_POST['sig_measure'] : null) {
                                      $a = $_POST['sig_measure'];
                                      if (strpos($a, 'FDR') !== false) {
                                        echo 'selected';
                                      }
                                    } else {
                                      echo "";
                                    } ?>>False Discovery Rate</option>
                <option value="Pval" <?php if (isset($_POST['sig_measure']) ? $_POST['sig_measure'] : null) {
                                        $a = $_POST['sig_measure'];
                                        if (strpos($a, 'Pval') !== false) {
                                          echo 'selected';
                                        }
                                      } else {
                                        echo "";
                                      } ?>>P-value</option>
              </select>
            </div>

          </TD>
        <TR>
          <TD>Significance Threshold</TD>
          <TD><input id="threshold" type="text" name="sig_threshold" value="<?php if (isset($_POST['sig_threshold']) ? $_POST['sig_threshold'] : null) {
                                                                              print($_POST['sig_threshold']);
                                                                            } else {
                                                                              print("0.05");
                                                                            } ?>"></TD>
        </TR>
    </table>



    <div class="radioholder ssea2pharm">
      <input type="radio" id="modules_select" name="modulegroup" value="2" <?php if (isset($_POST['modulegroup']) ? $_POST['modulegroup'] : null) {
                                                                              $a = $_POST['modulegroup'];
                                                                              if ($a == 2) {
                                                                                echo "checked";
                                                                              }
                                                                            } else {
                                                                              echo "";
                                                                            }  ?>>
      <label for="modules_select">Select specific modules</label>
    </div>


    </td>
    <td name="val1_ssea2pharm">
      <h4 class="instructiontext" style="font-size: 18px;">Select genes</h4>

      <div class="radioholder ssea2pharm">
        <input id="genes_all" name="genegroup" type="radio" value="1" <?php if (isset($_POST['genegroup']) ? $_POST['genegroup'] : null) {
                                                                        $a = $_POST['genegroup'];
                                                                        if ($a == 1) {
                                                                          echo "checked";
                                                                        }
                                                                      } else {
                                                                        echo "";
                                                                      }  ?>>
        <label for="genes_all" class="radio-style-3-label">All genes from original gene set</label>
      </div>
        <div class="radioholder ssea2pharm">
          <input id="genes_snps" name="genegroup" type="radio" value="2" <?php if (isset($_POST['genegroup']) ? $_POST['genegroup'] : null) {
                                                                            $a = $_POST['genegroup'];
                                                                            if ($a == 2) {
                                                                              echo "checked";
                                                                            }
                                                                          } else {
                                                                            echo "";
                                                                          }  ?>>
          <label for="genes_snps" class="radio-style-3-label">Only genes derived from association data</label>
        </div>

    </td>
    <!--End Second row------------------------------------------>
    </tr>
    </tbody>
    </table>
  </div>

  <div id="moduleselection" class="row" <?php if (isset($_POST['modulegroup']) ? $_POST['modulegroup'] : null) {
                                          $a = $_POST['modulegroup'];
                                          if ($a == 2) {
                                            echo "";
                                          } else {
                                            echo 'style="display: none;"';
                                          }
                                        } else {
                                          echo 'style="display: none;"';
                                        }  ?>>
    <!--Start of module selection ------>

    <div class="table-responsive">
      <div id="rec_message2" style="width: 100%;text-align: center;font-size: 18px;"></div>
      <table id="moduletable" class="table table-striped table-bordered" cellspacing="0" style="text-align: center;">
        <thead>
          <tr>
            <th>Selection<br><input name="select_all" value="1" type="checkbox"></th>
            <th>Module</th>
            <th>P-Value</th>
            <th>FDR</th>
            <th># of Marker Mapped Genes</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <?php



          foreach ($data as $key => $value) {

            $modp = $data[$key]['pval'];
            $modfdr = $data[$key]['fdr'];
            $modnum = $data[$key]['numgenes'];
            $moddesc = $data[$key]['desc'];
            if (isset($_POST['moduleselect'])) {

              $a_arr = $_POST['moduleselect'];
              $a = implode("|", $a_arr);
              $b = explode("|", $a);
              $c = 0;

              foreach ($b as $mod => $item) {

                if ($value['module'] == $item) //check if the sector is in the line
                {
                  echo "<tr><td>" . ' <input class="dt-body-center" type="checkbox" name="moduleselect[]" value= "' . $value['module'] . '" checked></td><td>' . $value['module'] . '</td><td>' . $modp . '</td><td>' . $modfdr . '</td><td>' . $modnum . '</td><td>' . $moddesc . '</td></tr>';
                  unset($b[$mod]);
                  $c = $c + 1;
                }
              }

              if ($c == 0) {
                echo "<tr><td>" . ' <input class="dt-body-center" type="checkbox" name="moduleselect[]" value= "' . $value['module'] . '" ></td><td>' . $value['module'] . '</td><td>' . $modp . '</td><td>' . $modfdr . '</td><td>' . $modnum . '</td><td>' . $moddesc . '</td></tr>';
              } else {
                $c = $c - 1;
              }
            } else {
              echo "<tr><td>" . ' <input class="dt-body-center" type="checkbox" name="moduleselect[]" value= "' . $value['module'] . '" ></td><td>' . $value['module'] . '</td><td>' . $modp . '</td><td>' . $modfdr . '</td><td>' . $modnum . '</td><td>' . $moddesc . '</td></tr>';
            }
          }



          ?>

        </tbody>
      </table>

    </div>
    <!--End of table responsive div ---->
  </div>

</form>
<!--End of ssea2pharm form -------------------------------------->


<!---------------------------Start of gene display -------
                        <div id="genesection" class="row" <?php if (isset($_POST['modselect']) ? $_POST['modselect'] : null) {
                                                            echo '';
                                                          } else {
                                                            echo 'style="display: none;"';
                                                          }  ?>> 
                            <div class="col-lg-5 col-sm-5 col-xs-12" style="margin: 40px auto;">
                                <div class="genetitle">Genes</div>
                                <select style="border: 2px; text-align:center;" name="genefrom" id="geneselect" class="form-control" size="8" multiple="multiple" disabled> 
                                </select>
                            </div>
  
                        </div> --->



<!----------------------------------------End of shinyapp3 maintable ----------------------------------------------->

<!-------------------------------------------------Start Review button ----------------------------------------------------->
<div id="Validatediv_ssea2pharm" style="text-align: center;">
  <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_ssea2pharm">Click to Review</button>
  <div id="preload_ssea2pharm"></div>
</div>
<!-------------------------------------------------End Review button ----------------------------------------------------->

<!--if selectedmodules + SNPs --->
<?php
function getGenesFromAllModules($fselectpathOut, $fgenespathOut, $data, $genearray, $genearr, $measure, $threshold)
{
  $sig = strtolower($measure);
  $fpselect = fopen($fselectpathOut, "w");
  $fpgenes = fopen($fgenespathOut, "w");
  //$genearr[] = "GENE";
  foreach ($data as $array) {



    if ($array[$sig] < $threshold) //filter modules by significance measure and threshold ("FDR < 0.05")
    {
      foreach ($genearray as $line) //get genes for the modules
      {
        $line_array = explode("\t", $line);
        // set first element to the new array
        //$modulecheck = strtoupper(trim($line_array[0]));
        $modulecheck = trim($line_array[0]);
        if (strpos($modulecheck, $array['module']) !== false) //check if module name is in the same line
        {
          $genearr[] = trim($line_array[1]); //pass in all the genes for that module
        }
      }

      $genesalone = implode("\n", $genearr);
      fwrite($fpgenes, $genesalone . "\n");

      $modgenes = implode("|", $genearr);
      $modn = $array['module'];
      if($sig=='fdr'){
        $modfdr = $array['fdr'];
      } else{
        $modfdr = $array['pval'];
      }
      $moddesc = $array['desc'];
      $write = "$modn\t$modfdr\t$modgenes\t$moddesc\t";
      fwrite($fpselect, $write . "\n");
      unset($genearr);
      $genearr = array();
    }
  }
  fclose($fpgenes);
  chmod($fgenespathOut, 0777);

  $lines = file($fgenespathOut);
  $lines = array_unique($lines);
  file_put_contents($fgenespathOut, implode($lines));
  fclose($fpselect);
  chmod($fselectpathOut, 0777);
}



function getGenesFromSelectModules($fselectpathOut, $fgenespathOut, $filename, $data)
{
  $fpselect = fopen($fselectpathOut, "w");
  $fpgenes = fopen($fgenespathOut, "w");
  $a_arr = $_POST['moduleselect'];
  $a = implode("|", $a_arr);
  $b = explode("|", $a);
  $hold_array = array();
  $json2 = array();
  $array = file($filename);

  for ($i = 0; $i < count($b); $i++) {
    // loop through array continuously based on amount of modules selected
    foreach ($array as $line) {
      // explode the line on tab. Note double quotes around \t are mandatory
      $line_array = explode("\t", $line);
      // set first element to the new array
      $modulecheck = trim($line_array[0]);
      if (strpos($modulecheck, $b[$i]) !== false) //check if the sector is in the line
      {
        //if found, pass the node into an array; this will create an array of genes that corresponds to the module
        $new_gene = trim($line_array[1]);
        $json2['module'] = $modulecheck;
        $json2['gene'] = $new_gene;
        $data2[] = $json2;
      }
    }
  }

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

    $genesalone = implode("\n", $hold_array);
    fwrite($fpgenes, $genesalone . "\n");

    $geneselected = implode("|", $hold_array);
    $writedesc = "$moddesc";
    fwrite($fpselect, $write);
    fwrite($fpselect, $geneselected . "\t");
    fwrite($fpselect, $writedesc . "\n");
    unset($hold_array); // $foo is gone
    $hold_array = array(); // $foo is here again
    // query to delete where item = $item
  }
  fclose($fpgenes);
  chmod($fgenespathOut, 0777);
  $lines = file($fgenespathOut);
  $lines = array_unique($lines);
  file_put_contents($fgenespathOut, implode($lines));
  fclose($fpselect);
  chmod($fselectpathOut, 0777);
}

if (isset($_POST['modulegroup']) ? $_POST['modulegroup'] : null) {
  $m = $_POST['modulegroup'];
  $g = $_POST['genegroup'];
  $a = $_POST['analysistype'];
  if ($a == 1) {
    $fselectpathOut = $ROOT_DIR."Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_selectedmodules.txt";
    $fgenespathOut = $ROOT_DIR."Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . ".SSEA2PHARM_genes.txt";
  } else {
    $fselectpathOut = $ROOT_DIR."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".SSEA2PHARM_selectedmodules.txt";
    $fgenespathOut = $ROOT_DIR."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".SSEA2PHARM_genes.txt";
  }

  if ($m == 1 && $g == 1) //All modules from gene sets + All genes
  {
    if ($rmchoice == 1)
      //$geneset_file = "./Data/Pipeline/Resources/ssea_temp/$sessionID" . "MODULE";
      $geneset_file = $ROOT_DIR."Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
    else if ($rmchoice == 2)
      //$geneset_file = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "MODULE";
      $geneset_file =$ROOT_DIR."Data/Pipeline/Resources/msea_temp/$sessionID" . "data.json";
    else
      $geneset_file = $ROOT_DIR."Data/Pipeline/Resources/meta_temp/$sessionID" . "data.json"; // will need to work on this

    //$geneset = trim(file_get_contents($geneset_file)); edited by JD Dec
    $datajson = json_decode(file_get_contents($geneset_file))->data;
    $genesets = $datajson[0]->geneset;
    $fgeneset = $ROOT_DIR."Data/Pipeline/" . $genesets;
    $genearray = file($fgeneset);
    $genearr = array();

    $s = $_POST['sig_measure'];
    $t = $_POST['sig_threshold'];

    getGenesFromAllModules($fselectpathOut, $fgenespathOut, $data, $genearray, $genearr, $s, $t);
  } elseif ($m == 1 && $g == 2) //All modules from gene set + Only genes mapped from SNPs
  {
    if ($rmchoice == 1 || $rmchoice == 2)
      $geneset_file = $ROOT_DIR."Data/Pipeline/Results/ssea/$sessionID" . ".GeneSets_SNP_mapped.txt";
    else
      $geneset_file = $ROOT_DIR."Data/Pipeline/Results/meta_ssea/$sessionID" . ".GeneSets_SNP_mapped.txt";

    $genearray = file($geneset_file);
    $genearr = array();

    $s = $_POST['sig_measure'];
    $t = $_POST['sig_threshold'];

    getGenesFromAllModules($fselectpathOut, $fgenespathOut, $data, $genearray, $genearr, $s, $t);
  } elseif ($m == 2 && $g == 1) //Select specific modules + All genes
  {
    if ($rmchoice == 1)
      //$geneset_file = "./Data/Pipeline/Resources/ssea_temp/$sessionID" . "MODULE";
      $geneset_file = $ROOT_DIR."Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
    else if ($rmchoice == 2)
      //$geneset_file = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "MODULE";
      $geneset_file = $ROOT_DIR."Data/Pipeline/Resources/msea_temp/$sessionID" . "data.json";
    else
      //$geneset_file = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "MODULE";
      $geneset_file = $ROOT_DIR."Data/Pipeline/Resources/meta_temp/$sessionID" . "data.json";

    $datajson = json_decode(file_get_contents($geneset_file))->data;
    $genesets = $datajson[0]->geneset;
    $filename = $ROOT_DIR."Data/Pipeline/" . $genesets;
    //$geneset = trim(file_get_contents($geneset_file));
    //$filename = "./Data/Pipeline/" . $geneset;

    getGenesFromSelectModules($fselectpathOut, $fgenespathOut, $filename, $data);
  } else //Select specific modules + Only genes mapped from SNPs
  {
    if ($rmchoice == 1 || $rmchoice == 2)
      $filename = $ROOT_DIR."Data/Pipeline/Results/ssea/$sessionID" . ".GeneSets_SNP_mapped.txt";
    else
      $filename = $ROOT_DIR."Data/Pipeline/Results/meta_ssea/$sessionID" . ".GeneSets_SNP_mapped.txt";

    getGenesFromSelectModules($fselectpathOut, $fgenespathOut, $filename, $data);
  }
}





if ($rmchoice == 1) {
?>
  <script type="text/javascript">
    function ssea2pharmreview() //This function gets the review table for wKDA
    {



      var choice = $('select[name=network_select] option').filter(':selected').val(),
        species = $('select[name=species_select] option').filter(':selected').val(),
        moduletype = $("input[name='modulegroup']:checked").val(),
        genetype = $("input[name='genegroup']:checked").val(),
        analysis = $("input[name='analysistype']:checked").val(),
        rm = 1;

      if (moduletype == 1) {
        var sigmeasure = $('#measure option:selected').val();
        var sigthreshold = $('#threshold').val();
        $.ajax({
          url: "ssea2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            network_select: choice,
            species_select: species,
            modulegroup: moduletype,
            genegroup: genetype,
            analysistype: analysis,
            sig_measure: sigmeasure,
            sig_threshold: sigthreshold,
            rmchoice: rm
          },
          success: function(data) {
            $('#myssea2pharm_review').html(data);
          }
        });
      } else {
        $.ajax({
          url: "ssea2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            network_select: choice,
            species_select: species,
            modulegroup: moduletype,
            genegroup: genetype,
            analysistype: analysis,
            rmchoice: rm
          },
          success: function(data) {
            $('#myssea2pharm_review').html(data);
          }
        });
      }


      $('#ssea2pharmtab2').show();
      $('#ssea2pharmtab2').click();
      $('#ssea2pharmtab2').html('Review Modules');




    }

    $('#ssea2pharmdataform').submit(function(e) {

      e.preventDefault();
      var string = "<?php echo $sessionID; ?>";
      var form_data = new FormData(document.getElementById('ssea2pharmdataform'));
      form_data.append("sessionID", string);




      $.ajax({
        'url': 'ssea2pharmomics_parameters.php?rmchoice=1',
        'type': 'POST',
        'data': form_data,
        processData: false,
        contentType: false,
        'success': function(data) {
          $("#myssea2pharm").html(data);
          ssea2pharmreview()
        }
      });



    });
  </script>

<?php
} else if ($rmchoice == 2) {
?>
  <script type="text/javascript">
    function msea2pharmreview() //This function gets the review table for wKDA
    {
      var choice = $('select[name=network_select] option').filter(':selected').val(),
        species = $('select[name=species_select] option').filter(':selected').val(),
        moduletype = $("input[name='modulegroup']:checked").val(),
        genetype = $("input[name='genegroup']:checked").val(),
        analysis = $("input[name='analysistype']:checked").val(),
        rm = 2;

      if (moduletype == 1) {
        var sigmeasure = $('#measure option:selected').val();
        var sigthreshold = $('#threshold').val();
        $.ajax({
          url: "ssea2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            network_select: choice,
            species_select: species,
            modulegroup: moduletype,
            genegroup: genetype,
            analysistype: analysis,
            sig_measure: sigmeasure,
            sig_threshold: sigthreshold,
            rmchoice: rm
          },
          success: function(data) {
            $('#mymsea2pharm_review').html(data);
          }
        });
      } else {
        $.ajax({
          url: "ssea2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            network_select: choice,
            species_select: species,
            modulegroup: moduletype,
            genegroup: genetype,
            analysistype: analysis,
            rmchoice: rm
          },
          success: function(data) {
            $('#mymsea2pharm_review').html(data);
          }
        });
      }
      $('#msea2pharmtab2').show();
      $('#msea2pharmtab2').click();
      $('#msea2pharmtab2').html('Review Modules');
    }

    $('#ssea2pharmdataform').submit(function(e) {
      e.preventDefault();
      var string = "<?php echo $sessionID; ?>";
      var form_data = new FormData(document.getElementById('ssea2pharmdataform'));
      form_data.append("sessionID", string);

      $.ajax({
        'url': 'ssea2pharmomics_parameters.php?rmchoice=2',
        'type': 'POST',
        'data': form_data,
        processData: false,
        contentType: false,
        'success': function(data) {
          $("#mymsea2pharm").html(data);
          msea2pharmreview()
        }
      });

      e.preventDefault();
    });
  </script>

<?php
} else {
?>

  <script type="text/javascript">
    function meta2pharmreview() //This function gets the review table for wKDA
    {

      var choice = $('select[name=network_select] option').filter(':selected').val();
      var species = $('select[name=species_select] option').filter(':selected').val();
      var moduletype = $("input[name='modulegroup']:checked").val();
      var genetype = $("input[name='genegroup']:checked").val();
      var analysis = $("input[name='analysistype']:checked").val();
      var rm = 3;
      console.log("haha");
      console.log(analysis);
      if (moduletype == 1) {
        var sigmeasure = $('#measure option:selected').val();
        var sigthreshold = $('#threshold').val();
        $.ajax({
          url: "ssea2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            network_select: choice,
            species_select: species,
            modulegroup: moduletype,
            genegroup: genetype,
            analysistype: analysis,
            sig_measure: sigmeasure,
            sig_threshold: sigthreshold,
            rmchoice: rm
          },
          success: function(data) {
            $('#myMETAMSEA2PHARM_review').html(data);
          }
        });
      } else {
        $.ajax({
          url: "ssea2pharmomics_moduleprogress.php",
          method: "GET",
          data: {
            sessionID: string,
            network_select: choice,
            species_select: species,
            modulegroup: moduletype,
            genegroup: genetype,
            analysistype: analysis,
            rmchoice: rm
          },
          success: function(data) {
            $('#myMETAMSEA2PHARM_review').html(data);
          }
        });
      }


      $('#METAMSEA2PHARMtab2').show();
      $('#METAMSEA2PHARMtab2').click();
      $('#METAMSEA2PHARMtab2').html('Review Modules');




    }

    $('#ssea2pharmdataform').submit(function(e) {

      e.preventDefault();
      var string = "<?php echo $sessionID; ?>";
      var form_data = new FormData(document.getElementById('ssea2pharmdataform'));
      form_data.append("sessionID", string);




      $.ajax({
        'url': 'ssea2pharmomics_parameters.php?rmchoice=3',
        'type': 'POST',
        'data': form_data,
        processData: false,
        contentType: false,
        'success': function(data) {
          $("#myMETAMSEA2PHARM").html(data);
          meta2pharmreview()
        }
      });

      e.preventDefault();


    });
  </script>

<?php
}

?>


<script type="text/javascript">
  (function($) {
    $.fn.inputFilter = function(inputFilter) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          this.value = "";
        }
      });
    };
  }(jQuery));
  var string = "<?php echo $sessionID; ?>";
  $(document).ready(function() {
    $("#analysis_overlap").click();
    $("#modules_all").click();
    $("#genes_all").click();
    $("#removeclass").removeClass("nobottommargin");
  });

  $("html, body").animate({
    scrollTop: $(document).height()
  }, "slow");
  $("#threshold").inputFilter(function(value) {
    return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });

  $("#MSEAflowChart").next().addClass("activeArrow");
  var rmchoice = "<?php echo $rmchoice; ?>";
  if (rmchoice == 1) { // SSEA
    var link = "#ssea2pharmtoggle";
    var descrip = "MSEA to Pharmomics";
  }
  else if (rmchoice == 2){ // ETPM
    var link = "#msea2pharmtoggle";
    var descrip = "MSEA to Pharmomics";
  }
  else { // meta
    var link = "#METAMSEA2PHARMtoggle";
    var descrip = "Meta-MSEA to Pharmomics";
  }
  $("#MSEAtoPharmflowChart").addClass("activePipe").html('<a href="' + link +'" class="pipelineNav" id="MSEAtoPharmNav">' + descrip + '</a>').css("opacity","1");

  $("#MSEAtoPharmNav").on('click', function(e){
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

  //NETWORK FILE UPLOAD EVENT HANDLER
  $("#NetworkApp2uploadInput").on("change", function() {
    $("#NetworkApp2labelname").html("Select another file?");
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 2500000) {
      alert("File Size is too big");
      var control = $("#NetworkApp2uploadInput"); //get the id
      control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", "./Data/Pipeline/Resources/shinyapp2_temp/");
      fd.append("data_type", "network_app2");
      fd.append("session_id", session_id);
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
            var control = $("#NetworkApp2uploadInput"); //get the id
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
      $("#NetworkApp2uploadInput").focus();
    }
  });
  $("#NetworkApp2labelname").on("click", function(event) {
    $("#NetworkApp2uploadInput").focus();
    return false;
  });

  $("#Validatebutton_ssea2pharm").on('click', function() {
    var analysis = $("input[name='analysistype']:checked").val();
    var networktype = $("#myNetwork").prop('selectedIndex'),
      speciestype = $("#mySpecies").prop('selectedIndex'),
      moduletype = $("input[name='modulegroup']:checked").val(),
      arr = [],
      errorlist = [];
    if ($("input:radio[name='analysistype']").is(':checked')) {
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

    if ($("input:radio[name='modulegroup']").is(':checked')) {
      if (moduletype == 1) {

        if (!$.trim($("#threshold").val())) {
          // sig threshold is empty or contains only white-space
          errorlist.push('A significance threshold has not been entered!');
        }
      } else {

        $("input[name='moduleselect[]']:checked").each(function() {
          arr.push(this.value);
        });
        if (arr.length === 0) {
          errorlist.push('No specific modules have been selected!');
        }

      }
    } else {
      errorlist.push('Modules have not been selected!');
    }

    if ($("input:radio[name='genegroup']").is(':checked')) {
      //do nothing
    } else {
      errorlist.push('Genes have not been selected!');
    }

    if (errorlist.length === 0) {
      $(this).html('Please wait ...')
        .attr('disabled', 'disabled');
      $("#preload_ssea2pharm").html(`<h4 style="padding: 10px" class='instructiontext'>Loading genes....<br>This may take a few seconds <br> <img src='include/pictures/ajax-loader.gif' /></h4>`);

      if (moduletype == 1) {
        $('#moduleselection').remove();
      } else {
        $('#measure').remove();
        $('#threshold').remove();

      }

      $("#ssea2pharmdataform").submit();
    } else {
      var result = errorlist.join("\n");
      //alert(result);
      $('#errorp_ssea2pharm').html(result);
      $("#errormsg_ssea2pharm").fadeTo(2000, 500).slideUp(500, function() {
        $("#errormsg_ssea2pharm").slideUp(500);
      });

    }


    return false;


  });





  var rows_selected = [];

  var table = $('#moduletable').dataTable({
    "columnDefs": [{
        "width": "6%",
        "orderable": false,
        "targets": 0
      },
      {
        "width": "20%",
        "targets": 1
      },
      {
        "width": "10%",
        "targets": 2
      },
      {
        "width": "10%",
        "targets": 3
      },
      {
        "width": "10%",
        "targets": 4
      },
      {
        "width": "44%",
        "targets": 5
      }
    ],
    order: [
      [3, 'asc']
    ]

    /*
     'rowCallback': function(row, data, dataIndex){
       // Get row ID
       var rowId = data[0];

       // If row ID is in the list of selected row IDs
       if($.inArray(rowId, rows_selected) !== -1){
          $(row).find('input[type="checkbox"]').prop('checked', true);
          $(row).addClass('selected');
       }
    } */


  });




  // Handle click on table cells with checkboxes
  $('#moduletable').on('click', 'tbody td, thead th:first-child, input', function(e) {
    $(this).parent().find('input[type="checkbox"]').trigger('click');
    if ($(this).parent().find('input[type="checkbox"]').is(":checked")) { //If the checkbox is checked
      $(this).parent().find('input[type="checkbox"]').closest('tr').addClass("highlight_row");
      //Add class on checkbox checked
    } else {
      $(this).parent().find('input[type="checkbox"]').closest('tr').removeClass("highlight_row");
      //Remove class on checkbox uncheck
    }
  });





  // Handle click on "Select all" control
  $('thead input[name="select_all"]').on('click', function(e) {
    if (this.checked) {
      $('#moduletable tbody input[type="checkbox"]:not(:checked)').trigger('click');
    } else {
      $('#moduletable tbody input[type="checkbox"]:checked').trigger('click');
    }

    // Prevent click event from propagating to parent
    e.stopPropagation();
  });

  /*

   $('#moduletable tbody').on('click', 'input[type="checkbox"]', function(e){

      var $row = $(this).closest('tr');

      // Get row data
      var data = table.row($row).data();

      // Get row ID
      var rowId = data[0];

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         rows_selected.splice(index, 1);
      }

       if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }

      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table);

      // Prevent click event from propagating to parent
      e.stopPropagation();
   }); 
   // Handle table draw event
   table.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table);
   });

  function updateDataTableSelectAllCtrl(table){
   var $table             = table.table().node();
   var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
   var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
   var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

   alert(chkbox_select_all);
   alert($chkbox_checked);

   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
}

    */




  $("input[name='analysistype']").change(function() {

    var select_analysis = $("input[name='analysistype']:checked").val();
    if (select_analysis == 1) {
      $(".network_analysis").show();

    } else {
      $(".network_analysis").hide();
    }


  });


  // set up radio boxes
  $('.radioholder.ssea2pharm').each(function() {
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
  $('.radioholder.ssea2pharm :input').change(function() {
    $('.radioholder.ssea2pharm :input').each(function() {
      if ($(this).prop('checked') == true) {
        $(this).parent().addClass('activeradioholder');
      } else $(this).parent().removeClass('activeradioholder');
    });
  });
  // manually fire radio box change event on page load
  $('.radioholder.ssea2pharm :input').change();



  // set up select boxes
  $('.selectholder.ssea2pharm').each(function() {
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
        $('.activeselectholder.ssea2pharm').each(function() {
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
  $('.selectholder.ssea2pharm .selectdropdown span').click(function() {

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
    var select = $("#myNetwork").find('option:selected').index();
    if (select != 1)
      //$("#myNetwork_kda").parent().next().hide();
      $("#NetApp2upload").hide();
    if (select == 1)
      //$("#myNetwork_kda").parent().next().show();
      $("#NetApp2upload").show();
    /*if (select > 1)
      $("#myNetwork_kda").parent().nextAll(".alert-app2").eq(0).html(successalert).hide().fadeIn(300);*/
    if (select == 1)
      //$("#myNetwork_kda").parent().nextAll(".alert-app2").eq(0).html(uploadalert).hide().fadeIn(300);
      $("#alert2MSEA").eq(0).html(uploadalert).hide().fadeIn(300);
    else
      //$("#myNetwork_kda").parent().nextAll(".alert-app2").eq(0).empty();
      $("#alert2MSEA").eq(0).empty();
  });

  var uploadalert = `<div style="padding:0% 25%;">
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



  $("input[name=modulegroup]").change(function() {
    var select_module = $("input[name='modulegroup']:checked").val();
    if (select_module == 2) {
      $("#sig_table").animate({
        opacity: 'hide',
        height: 'hide'
      }, 'slow');
      $("#rec_message").animate({
        opacity: 'hide',
        height: 'hide'
      }, 'slow');
      $("#rec_message2").html(`<div class="alert alert-warning"><i class="icon-warning-sign" style="margin-right:3%;"></i><strong>Recommended:</strong> FDR < 0.05</div>`).hide().fadeIn();
      $("#moduleselection").animate({
        opacity: 'show',
        height: 'show'
      }, 'slow');
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
      $('html,body').animate({
        scrollTop: $("#moduleselection").offset().top - 90
      });
    } else {
      $("#moduleselection").animate({
        opacity: 'hide',
        height: 'hide'
      }, 'slow');
      $("#rec_message").animate({
        opacity: 'show',
        height: 'show'
      }, 'slow');
      $("#sig_table").animate({
        opacity: 'show',
        height: 'show'
      }, 'slow');
    }


  });
</script>