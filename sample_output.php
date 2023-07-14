<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.css" />
<link rel="stylesheet" href="include/bs-datatable.css" type="text/css" />

<?php include_once("analyticstracking.php") ?>

<!-- Includes all the font/styling/js sheets -->
<?php include_once("head.inc") ?>

<style type="text/css">
  td.details-control {
    background: url('include/pictures/details_open.png') no-repeat center center;
    cursor: pointer;
  }

  tr.shown td.details-control {
    background: url('include/pictures/details_close.png') no-repeat center center;
  }

  div.slider {
    display: none;
  }

  table.dataTable tbody td.no-padding {
    padding: 0;

  }

  #metareviewtable_filter {
    float: right;
  }

  #metareviewtable_paginate {
    float: right;
  }

  #first {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    top: 0;
    opacity: 0.8;
    background-color: #000;
    z-index: 9999;
  }

  ul.resultf {
    margin-bottom: 0;
    font-size: 18px;
    list-style-type: none;
  }
</style>

<body class="stretched">

  <?php include_once("headersecondary_resources.inc") ?>

  <?php
  function debug_to_console($data)
  {
    $output = $data;
    if (is_array($output))
      $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }

  function scientificNotation($val)
  {
    $exp = floor(log($val, 10));
    if ($val == 0) {
      return 0;
    } else {
      return sprintf('%.2fE%+03d', $val / pow(10, $exp), $exp);
    }
  }
  //MDF outputs
  $assocation_file = "./sample_outputs/Sample_MDF_corrected_association.txt";
  $mapping_file = "./sample_outputs/Sample_MDF_corrected_mapping.txt";
  $MDF_inputs = "./sample_outputs/Sample_MDF_file_parameter_selection.txt";


  //MSEA outputs
  $results_file = "./sample_outputs/Sample.MSEA_modules_full_result.txt";
  $info_file = "./sample_outputs/Sample.MSEA_merged_modules_full_result.txt";
  $modules_file = "./sample_outputs/Sample.MSEA_merged_modules.txt";
  $overview_file = "./sample_outputs/Sample.MSEA_file_parameter_selection.txt";
  // for MSEA interactive output
  $resultfiledesc = "./sample_outputs/Sample.MSEA_modules_details.txt";
  //$resultfile = "./sample_outputs/Sample.MSEA_modules_pval.txt";
  $joblogfile = "./sample_outputs/Sample_MSEA_joblog.txt";
  //$data = file_get_contents($resultfile); //read the file
  //$convert = explode("\n", $data);
  $fdrval = 1;
  $module_file = "./Data/Pipeline/Resources/pathways/KEGG_Reactome_BioCarta.txt";
  //rewritten for merged modules table

  //Meta-MSEA outputs
  $details_file = "./sample_outputs/Sample_Meta.MSEA_top_modules_details.txt";
  $meta_results_file = "./sample_outputs/Sample_Meta.MSEA_modules_full_result.txt";
  $meta_info_file = "./sample_outputs/Sample_Meta.MSEA_merged_modules_full_result.txt";
  $meta_modules_file = "./sample_outputs/Sample_Meta.MSEA_merged_modules.txt";
  $combinedmeta_file = "./sample_outputs/Sample_Meta.MSEA_meta_combined_result.txt";
  $Individual_files = "./sample_outputs/Sample_Individual_MSEA_Results_From_Meta.zip";
  $meta_overview_file = "./sample_outputs/Sample_Meta.MSEA_file_parameter_selection.txt";
  $overview_file = "./sample_outputs/Sample_Meta.MSEA_file_parameter_selection.txt";
  //$meta_resultfile = "./sample_outputs/Sample_Meta.MSEA_modules_pval.txt";
  $meta_resultfiledesc = "./sample_outputs/Sample_Meta.MSEA_top_modules_details.txt";
  $outfile = "./sample_outputs/Sample_Meta_MSEA_joblog.txt";
  // $meta_data = file_get_contents($meta_resultfile);
  // $meta_convert = explode("\n", $meta_data);

  //KDA outputs
  //$kda_results_file = "./sample_outputs/Sample.wKDA_kd_full_results.txt";
  $kda_overview_file = "./sample_outputs/Sample.wKDA_file_parameter_selection.txt";
  $kda_outfile = "./sample_outputs/Sample.wKDA_joblog.txt";
  $edges_file = "./sample_outputs/Sample.wKDA_cytoscape_edges.txt";
  $nodes_file = "./sample_outputs/Sample.wKDA_cytoscape_nodes.txt";
  $color_file = "./sample_outputs/Sample.wKDA_cytoscape_module_color_mapping.txt";
  //$kda_data = file_get_contents($kda_results_file); //read the file
  //$kda_convert = explode("\n", $kda_data);

  // PharmOmics
  $genefile = "./sample_outputs/Sample.KDA2PHARM_genes.txt";
  //KDA to Pharm App2 outputs
  //$app2_resultfile = "./sample_outputs/Sample.KDA2PHARM_app2result.txt";
  $app2_overview = "./sample_outputs/Sample.KDA2PHARM_overview.txt";
  //KDA to Pharm App3 outputs
  //$app3_resultfile = "./sample_outputs/Sample.KDA2PHARM_app3result.txt";

  //Result zip files
  $MDFres = "./sample_outputs/MDF.zip";
  $MSEAres = "./sample_outputs/MSEA.zip";
  $MetaMSEAres = "./sample_outputs/MetaMSEA.zip";
  $KDAres = "./sample_outputs/KDA.zip";
  $KDAtoPharmRes = "./sample_outputs/KDAtoPharmOmics.zip";



  //$json->data = array();
  // $count = 1;
  // $result = file($app3_resultfile);
  // foreach ($result as $line) {
  //   $line_array = explode("\t", $line);
  //   $database = trim($line_array[0]);
  //   $method = trim($line_array[1]);
  //   $drug = trim($line_array[2]);
  //   $species = trim($line_array[3]);
  //   $tissue = trim($line_array[4]);
  //   $study = trim($line_array[5]);
  //   $dose = trim($line_array[6]);
  //   $time = trim($line_array[7]);
  //   $jaccard = number_format(floatval($line_array[8]), 3, ".", "");
  //   $odds = number_format(floatval($line_array[9]), 3, ".", "");
  //   $pvalue = scientificNotation(trim((float)$line_array[10]));
  //   $rank = number_format(floatval($line_array[11]), 5, ".", "");
  //   if ($count == 1) {
  //     $count++;
  //     continue;
  //   }
  //   $row = array($database, $method, $drug, $species, $tissue, $study, $dose, $time, $jaccard, $odds, $pvalue, $rank);
  //   array_push($json->Data, $row);
  // }
  // $jsonfile = "./Data/kda.json";
  // file_put_contents($jsonfile, json_encode($json));

  //$json->data = array();
  // for ($i = 1; $i < (count($convert) - 1); $i++) {
  //   //echo $convert[$i]; //write value by index
  //   $convert_word = explode("\t", $convert[$i]);
  //   $fdr = $convert_word[2];
  //   $fdrword = explode("%", $fdr);
  //   $fdr = $fdrword[0];
  //   //print($fdr);


  //   $moduleid = $convert_word[0];
  //   //debug_to_console("moduleid:" . $moduleid . " $fdrval:$fdr");
  //   if ($moduleid != "_ctrlA" && $moduleid != "_ctrlB" && $fdrval >= $fdr) {
  //     $l = shell_exec('grep -w ' . $moduleid . ' ' . $resultfiledesc);
  //     $line = explode("\n", $l);
  //     $word = explode("\t", $line[0]);
  //     if ($word[0] == $moduleid && trim($moduleid) != "") {
  //       // prepare data for david
  //       $lw = shell_exec('grep -w ' . $moduleid . ' ' . $module_file);
  //       $linew = explode("\n", $lw);
  //       $dline = "";
  //       for ($k = 0; $k < (count($linew) - 1); $k++) {
  //         $wline = explode("\t", $linew[$k]);
  //         $dline .= $wline[1] . ",";
  //       }
  //       //top gene list;top loci;top score
  //       $genelist = "|";
  //       $locilist = "|";
  //       $scorelist = "|";

  //       for ($k = 0; $k < count($line) && $k < 5; $k++) {
  //         $dataline = explode("\t", $line[$k]);
  //         $genelist .= $dataline[2] . "|";
  //         $locilist .= $dataline[4] . "|";
  //         $scorelist .= $dataline[5] . "|";
  //       }
  //       $row = array();
  //       for ($j = 0; $j < count($convert_word); $j++) {
  //         // print($convert_word[$j]);
  //         array_push($row, $convert_word[$j]);
  //       }
  //       array_push($row, $genelist);
  //       array_push($row, $locilist);
  //       array_push($row, $scorelist);
  //       array_push($json->data, $row);
  //     }
  //   }
  // }

  // for ($i = 1; $i < (count($meta_convert) - 1); $i++) {
  //   //echo $convert[$i]; //write value by index
  //   $convert_word = explode("\t", $meta_convert[$i]);

  //   $fdr = $convert_word[2];
  //   $fdrword = explode("%", $fdr);
  //   $fdr = $fdrword[0];
  //   //print($fdr);

  //   $moduleid = $convert_word[0];
  //   if ($moduleid != "_ctrlA" && $moduleid != "_ctrlB") {
  //     //$l = shell_exec('grep -w ' . $moduleid . ' ' . $results_file);
  //     $l = shell_exec('grep -w ' . $moduleid . ' ' . $meta_resultfiledesc);

  //     $line = explode("\n", $l);
  //     $word = explode("\t", $line[0]);

  //     //debug_to_
  //     if ($word[0] == $moduleid && trim($moduleid) != "") {
  //       // prepare data for david
  //       $lw = shell_exec('grep -w ' . $moduleid . ' ' . $module_file);
  //       $linew = explode("\n", $lw);
  //       $dline = "";
  //       for ($k = 0; $k < (count($linew) - 1); $k++) {
  //         $wline = explode("\t", $linew[$k]);
  //         $dline .= $wline[1] . ",";
  //       }
  //       //top gene list;top loci;top score
  //       $genelist = "|";
  //       $locilist = "|";
  //       $scorelist = "|";
  //       for ($k = 0; $k < count($line) && $k < 5; $k++) {
  //         $dataline = explode("\t", $line[$k]);
  //         $genelist .= $dataline[2] . "|";
  //         $locilist .= $dataline[4] . "|";
  //         $scorelist .= $dataline[5] . "|";
  //       }
  //     } else {
  //       $genelist = "None";
  //       $locilist = "None";
  //       $scorelist = "None";
  //     }

  //     $row = array();
  //     for ($j = 0; $j < count($convert_word); $j++) {
  //       array_push($row, $convert_word[$j]);
  //       //print($convert_word[$j]);
  //     }
  //     array_push($row, $genelist);
  //     array_push($row, $locilist);
  //     array_push($row, $scorelist);
  //     array_push($json->data, $row);
  //     // print($genelist);
  //     // print($locilist);
  //     // print($scorelist);
  //   }
  // }

  // for ($i = 1; $i < (count($kda_convert) - 1); $i++) {
  //   $convert_word = explode("\t", $kda_convert[$i]);
  //   $p_val = ($convert_word[2] - 0);
  //   $fdr = ($convert_word[3] - 0);
  //   $fold = ($convert_word[10] - 0);
  //   $row = array(
  //     $convert_word[0], $convert_word[11], $convert_word[1], scientificNotation($p_val),
  //     scientificNotation($fdr), $convert_word[4], $convert_word[5], $convert_word[6], scientificNotation($fold)
  //   );
  //   array_push($json->data, $row);
  // }

  // $count = 1;
  // $result = file($app2_resultfile);
  // foreach ($result as $line) {
  //   $line_array = explode("\t", $line);
  //   $drugname = trim($line_array[0]);
  //   $species = trim($line_array[1]);
  //   $tissue = trim($line_array[2]);
  //   $zscore = number_format($line_array[5], 3, ".", "");
  //   $rank = number_format($line_array[6], 3, ".", "");
  //   $pvalue = scientificNotation(trim((float)$line_array[7]));
  //   $drug = trim($line_array[11]);

  //   if (file_exists("./sample_outputs/app2_networks/$drug" . "_cytoscape_edges.txt")) {
  //     $link = '<span style="text-align: center;margin:0px;">
  //                             <form style="margin-bottom:0;" action="/sample_outputs/write_sample_cytoscape_app2.php" name="figapp2" target="_blank">
  //                               <input type="hidden" name="drugres" value="' . $drug . '">
  //                               <input type="submit" class="button button-3d" style="padding:0% 5%; font-size 18px;" value="Display Network" />
  //                             </form>
  //                           </span>';
  //     $download = '<a href="./sample_outputs/app2_networks/' . $drug . '_cytoscape_edges.txt" download> Download edges</a><br>
  //                                <a href="./sample_outputs/app2_networks/' . $drug . '_cytoscape_nodes.txt" download> Download nodes</a>';
  //   } else {
  //     $link = "None created";
  //     $download = "None created";
  //   }

  //   if ($count == 1) {
  //     $count++;
  //     continue;
  //   }
  //   $row = array($drugname, $species, $tissue, $zscore, $rank, $pvalue, $link, $download);
  //   array_push($json->data, $row);
  //   //echo "<tr><td>$drugname</td><td>$species</td><td>$tissue</td><td>$zscore</td><td>$rank</td><td>$pvalue</td><td>$link</td><td>$download</td></tr>"; //changed 07312020 JD
  // }
  // $jsonfile = "./sample_outputs/Datatable_sample.KDA2PHARM_app2result.json";
  // file_put_contents($jsonfile, json_encode($json));



  ?>


  <!-- Page title block ---------------------------------------------------------------------------------->
  <section id="page-title">
    <div class="margin_rm" style="margin-left: 0;">
      <div class="container clearfix" style="text-align: center;">
        <h2>Sample Output</h2>
      </div>
    </div>
  </section>

  <!-- <section id="content" style="margin-bottom: 0px;"> -->
  <!--<div class="content-wrap" style="padding-right: 15%;"> -->
  <!--<div class="container clearfix" style="margin-left: 10%;"> -->
  <div class="margin_rm" style="margin-bottom: 200px;">
    <div class="container clearfix" id="myContainer" style="margin-bottom: 40px;padding-left: 0;">
      <p class="instructiontext" style="padding: 2% 0% 0% 0%; margin-bottom: 0;text-align: left;">Download sample output result files</p>
      <div style="font-size:18px">
        Below are links to download sample results for the different modules of the Mergeomics pipeline as well as the input files/parameters overview file (all inputs are available as sample inputs in the pipeline). These results are the same as displayed in the pipeline workflow below.
      </div>
      <ul class="resultf">
        <li><a href=<?php print($MDFres); ?> download>Marker Dependency Filtering (MDF)</a></li>
        <li><a href=<?php print($MSEAres); ?> download>Marker Set Enrichment Analysis (MSEA)</a></li>
        <li><a href=<?php print($MetaMSEAres); ?> download>Meta-MSEA</a></li>
        <li><a href=<?php print($KDAres); ?> download>Weighted Key Driver Analysis (wKDA)</a></li>
        <li><a href=<?php print($KDAtoPharmRes); ?> download>KDA to PharmOmics Drug Repositioning</a></li>
      </ul>

      <p class="instructiontext" style="padding: 1% 0% 0% 0%; margin-bottom: 0;text-align: left;">View pipeline workflow sample results display</p>
      <div style="font-size:18px; margin-bottom: 1%;">
        Below is the pipeline workflow with results for individual GWAS enrichment (meta-MSEA results are also included). For this sample analysis, the top 50% of coronary artery disease GWAS associations were mapped to genes using the 'Distance 50kb' (SNPs mapped to genes based on chromosomal distance) file and corrected with CEU linkage disequilibrium over 70% dependency ('CEU LD70'), and MSEA was run with KEGG, BioCarta, and Reactome marker sets. wKDA was run with an adipose gene regulatory network, and PharmOmics was run with all genes from the wKDA generated subnetwork.
      </div>
      <div id="first">
        <h1 style="color: #fff;padding: 15% 0% 0% 30%;">Please wait a few minutes for data to load<span class='dots'>...</span></h1>
      </div>
      <div class="toggle toggle-border" id="MDFtoggle">
        <div class="togglet toggleta" id="Pharmtogglet"><i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i>
          <div class="capital">Marker Dependency Filtering</div>
        </div>

        <div class="tabs tabs-bb togglec" style="margin-bottom: 0;" style="margin-bottom: 0;">
          <!-- Start tab headers -->

          <ul class="tab-nav clearfix">
            <li><a id="MDF2" href="#MDPruneResult">Results</a></li>
            <li><a id="MDF1" href="#MDPruneParam">Input Files and Parameters</a></li>
          </ul>

          <div class="tab-container">
            <!-- Start TAB container -->

            <div class="togglec" id="MDPruneParam">
              <table class="table table-bordered review" style="text-align: center" ; id="reviewtable">
                <thead>
                  <tr>
                    <!--First row of column headers ------->
                    <th>Type</th>
                    <th>Description</th>
                    <th>Filename/Parameters</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <!--Association data row ------->
                    <td rowspan="3" style="vertical-align: middle;">Files</td>
                    <td>Association Data</td>
                    <td style="font-weight: bold;">
                      <!--Outputs data from the MARKER file ------->
                      CARDIOGRAM_CAD.txt
                    </td>
                  </tr>
                  <tr>
                    <!--Marking Mapping data row ------->
                    <td>Marking Mapping Data</td>
                    <td style="font-weight: bold;">
                      gene2loci.050kb.txt
                    </td>
                  </tr>
                  <tr>
                    <!--Marking Dependency File row ------->
                    <td>Marker Dependency File</td>
                    <td style="font-weight: bold;">
                      ld70.ceu.txt
                    </td>
                  </tr>
                  <tr>
                    <!--Parameters------->
                    <td rowspan="1">Parameters</td>
                    <td>Percentage of Markers</td>
                    <td style="font-weight: bold;">
                      50
                    </td>
                  </tr>
                </tbody>
              </table>
            </div> <!-- Start tab content for LDPrune -->
            <div class="togglec" id="MDPruneResult">
              <table class="table table-bordered review" style="text-align: center;">
                <thead>
                  <tr>
                    <th colspan="3">Download Output Files</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Corrected Marker Associations</td>
                    <td>'MARKER' and 'VALUE' file with marker with lower value in linkage disequilibrium filtered out</td>
                    <td><a tooltip="Optional for possible future use, not necessary for immediate continuation of analysis" style="position: relative;" href=<?php print($assocation_file); ?> download> Download</a></td>
                  </tr>
                  <tr>
                    <td>Corrected Marker Mappings</td>
                    <td>'GENE' and 'MARKER' file with mappings subsetted to those matching markers in the association file</td>
                    <td><a tooltip="Optional for possible future use, not necessary for immediate continuation of analysis" style="position: relative;" href=<?php print($mapping_file); ?> download> Download</a></td>
                  </tr>
                  <tr>
                    <td>MDF input files and parameters</td>
                    <td>File listing chosen files and parameters for this MDF run</td>
                    <td><a tooltip="To keep track of MDF inputs" style="position: relative;" href=<?php print($MDF_inputs); ?> download> Download</a></td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div> <!-- End tab container -->
        </div> <!-- End tab header -->
      </div> <!-- End MDF toggle-->

      <div class="toggle toggle-border" id="SSEAtoggle1">
        <!-- Start second toggle/step in MSEA -->
        <div class="togglet" id="SSEAtogglet1"><i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i>
          <div class="capital">Marker Set Enrichment Analysis</div>
        </div>
        <div class="tabs tabs-bb togglec">
          <!-- Start tab headers -->
          <ul class="tab-nav clearfix">
            <li><a id="SSEAtab2" href="#MSEAResults">Results</a></li>
            <li><a id="SSEAtab1" href="#MSEAInputs">Input Files and Parameters</a></li>
          </ul>
          <div class="tab-container">
            <!-- Start TAB container -->
            <div class="togglec" id="MSEAInputs">
              <!--Start Review table ------->
              <table class="table table-bordered review" style="text-align: center;" id="SSEAreviewtable">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Filename/Parameters</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td rowspan="4" style="vertical-align: middle;">Files</td>
                    <td>Association Data</td>
                    <td style="font-weight: bold;">
                      MDF_corrected_association.txt
                    </td>
                  </tr>
                  <tr>
                    <td>Marking Mapping Data</td>
                    <td style="font-weight: bold;">
                      MDF_corrected_mapping.txt
                    </td>
                  </tr>
                  <tr>
                    <td>Gene Sets</td>
                    <td style="font-weight: bold;">
                      KEGG_Reactome_BioCarta.txt
                    </td>
                  </tr>
                  <tr>
                    <td>Gene Sets Description</td>
                    <td style="font-weight: bold;">
                      KEGG_Reactome_BioCarta_info.txt
                    </td>
                  </tr>
                  <tr>
                    <td rowspan="7" style="vertical-align: middle;">Parameters</td>
                    <td>Permutation Type</td>
                    <td style="font-weight: bold;">
                      gene
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Max Genes in Gene Sets
                    </td>
                    <td style="font-weight: bold;">
                      500
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Min Genes in Gene Sets
                    </td>
                    <!-- Min Genes in Gene Sets column ------->
                    <td style="font-weight: bold;">
                      10
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Max Overlap Allowed for Merging
                    </td>
                    <td style="font-weight: bold;">
                      0.33
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Min Overlap Allowed for Merging
                    </td>
                    <td style="font-weight: bold;">
                      0.33
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Number of Permutations
                    </td>
                    <td style="font-weight: bold;">
                      2000
                    </td>
                  </tr>
                  <tr>
                    <td>
                      MSEA to KDA export FDR cutoff
                    </td>
                    <!--  MSEA FDR Cutoff column ------->
                    <td style="font-weight: bold;">
                      25
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="togglec" id="MSEAResults">
              <table class="table table-bordered review" style="text-align: center;">
                <thead>
                  <tr>
                    <th colspan="3">Download MSEA Result Files</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      Modules Details
                    </td>
                    <td>
                      Lists for each module the genes that contributed to the module's enrichment, the corresponding markers in the association file and their association strengths
                    </td>
                    <td>
                      <a href=<?php print($resultfiledesc); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Modules Results Summary
                    </td>
                    <td>
                      Records for each module the enrichment p-value, FDR, and number of genes and markers contributing to the enrichment
                    </td>
                    <td>
                      <a href=<?php print($results_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      File and Parameter Selection
                    </td>
                    <td>
                      Lists chosen files and parameters for this MSEA run
                    </td>
                    <td>
                      <a href=<?php print($overview_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Runtime job log
                    </td>
                    <td>
                      Runtime outputs and errors (if any) of job
                    </td>
                    <td>
                      <a href=<?php print($joblogfile); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <th colspan="3">Download Merged Modules Result Files</th>
                  </tr>
                  <tr>
                    <td>
                      Merged Modules Results Summary
                    </td>
                    <td>
                      Records MSEA results for merged modules (same data fields as the 'Modules Results Summary File'). Merged modules contain supersets of individual modules that share genes at a ratio above the 'Max Overlap Allowed for Merging' parameter (some modules may remain independent). MSEA is rerun on these merged modules and the results are recorded in this file.
                    </td>
                    <td>
                      <a href=<?php print($info_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Merged Modules Nodes for KDA
                    </td>
                    <td>
                      Lists the genes (nodes) of non-redundant supersets (merged modules) that will automatically be used as input for the next optional step of the analysis, key driver analysis (KDA). These genes are members of modules that passed the user specified FDR cutoff ('MSEA to KDA export FDR cutoff'; default is 25%). If no modules passed this significance, then the top 10 modules are used. Please refer to your results if this was the case and interpret results from KDA accordingly. You may rerun the analysis with a different threshold.
                    </td>
                    <td>
                      <a href=<?php print($modules_file); ?> download> Download</a>
                    </td>
                  </tr>
                </tbody>
              </table>

              <br>

              <div id="tabs">

                <ul class="tab-nav tab-nav2 clearfix" style="display: table; margin: 0 auto;">
                  <li><a href="#tabs-module">Module Results</a></li>
                  <li><a href="#tabs-mergemodule">Merge Module Results</a></li>
                </ul>

                <div class="tab-container">

                  <div class="tab-content clearfix" id="tabs-module">
                    <div class="table-responsive">
                      <table id="module_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th> Module ID</th>
                            <th> MSEA: P-Value</th>
                            <th>MSEA: FDR</th>
                            <th>Description</th>
                            <th>Module Top Gene </th>
                            <th> Module Top Marker </th>
                            <th> Module Top Association Score</th>
                            <!-- <th> Module Details </th> -->
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--End of module tab ------->


                  <div class="tab-content clearfix" id="tabs-mergemodule">
                    <?php

                    $resultfile = "./sample_outputs/Sample.MSEA_merged_modules_full_result.txt";
                    $data = file_get_contents($resultfile); //read the file
                    $convert = explode("\n", $data); //create array separate by new line
                    ?>
                    <!-- Merge module ===================================================== -->
                    <div class="table-responsive">
                      <table id="merge_module" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th>Merge Module ID</th>
                            <th> Merge Module P-Value</th>
                            <th>Frequency</th>
                            <th>Number of Genes</th>
                            <th>Number of Markers </th>
                            <th>Density </th>
                            <th> Overlap</th>
                            <th> Description </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          for ($i = 1; $i < (count($convert) - 1); $i++) {
                            $convert_word = explode("\t", $convert[$i]);
                            if ($convert_word[0] != "_ctrlA" && $convert_word[0] != "_ctrlB") {
                              $p_val = ($convert_word[1] - 0);
                              $freq = ($convert_word[2] - 0);
                              $dense = ($convert_word[5] - 0);
                          ?>
                              <tr>
                                <td> <?php print($convert_word[0]); ?> </td>
                                <td> <?php printf("%.2e", $p_val); ?> </td>
                                <td> <?php printf("%.2e", $freq); ?> </td>
                                <td> <?php print($convert_word[3]); ?> </td>
                                <td> <?php print($convert_word[4]); ?> </td>
                                <td> <?php printf("%.2f", $dense); ?> </td>
                                <td>
                                  <div style="overflow:auto; max-width:200px;display:block"><?php print($convert_word[6]); ?></div>
                                </td>
                                <td>
                                  <div style="overflow:auto; max-width:400px;display:block"><?php print($convert_word[7]); ?></div>
                                </td>
                              </tr>
                          <?php
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>

                  </div>
                  <!--End of merge module tab ----------------->

                </div>
              </div>
            </div>
          </div> <!-- End tab container -->
        </div> <!-- End tab header -->
      </div> <!-- End second toggle/step in MDF -->

      <div class="toggle toggle-border">
        <!-- Start first toggle/step in Meta-MSEA -->
        <div class="togglet"><i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i>
          <div class="capital">Meta-MSEA</div>
        </div>
        <div class="tabs tabs-bb togglec" id="METAtabheader">
          <!-- Start tab headers -->
          <ul class="tab-nav clearfix">
            <li><a id="META2" href="#METAResults">Results</a></li>
            <li><a id="META1" href="#METAInputs">Input Files and Parameters</a>
            </li>
          </ul>
          <div class="tab-container">
            <!-- Start TAB container -->
            <div class="togglec" id="METAInputs">
              <div style="width:100%;">
                <table class="table table-bordered review" id="metareviewtable">
                  <thead>
                    <tr>
                      <th>Click for Parameters</th>
                      <th>Type of Enrichment</th>
                      <th>Association File</th>
                      <th>Mapping File</th>
                      <th>Modules File</th>
                      <th>Descriptions File</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div class="togglec" id="METAResults">
              <table class="table table-bordered review" style="text-align: center" ; id="MSEAresultstable">
                <thead>
                  <tr>
                    <th colspan="3">Download Output Files</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      Meta Modules Details File
                    </td>
                    <td>
                      Lists for each module the genes that contributed to the module's enrichment, the corresponding markers in the association file and their association strengths
                    </td>
                    <td>
                      <a href=<?php print($details_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Meta Modules Results Summary
                    </td>
                    <td>
                      Records for each module the enrichment p-value, FDR, and number of genes and markers contributing to the enrichment
                    </td>
                    <td>
                      <a href=<?php print($meta_results_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Meta Merged Modules Results File
                    </td>
                    <td>
                      Records MSEA results for merged modules (same data fields as the 'Modules Results Summary File'). Merged modules contain supersets of individual modules that share genes at a ratio above the 'Max Overlap Allowed for Merging' parameter (some modules may remain independent). MSEA is rerun on these merged modules and the results are recorded in this file.
                    </td>
                    <td>
                      <a href=<?php print($meta_info_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Meta Merged Modules Nodes for KDA
                    </td>
                    <td>
                      Lists the genes (nodes) of non-redundant supersets (merged modules) that will automatically be used as input for the next optional step of the analysis, key driver analysis (KDA). These genes are members of modules that passed the user specified FDR cutoff ('MSEA to KDA export FDR cutoff'; default is 25%). If no modules passed this significance, then the top 10 modules are used. Please refer to your results if this was the case and interpret results from KDA accordingly. You may rerun the analysis with a different threshold.
                    </td>
                    <td>
                      <a href=<?php print($meta_modules_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Individual Study P and FDR Results File
                    </td>
                    <td>
                      Combined summary file containing the P and FDR values for each individual MSEA run. Refer to the file and parameter selection file below for the individual MSEA run codes
                    </td>
                    <td>
                      <a href=<?php print($combinedmeta_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Individual MSEA Result Files
                    </td>
                    <td>
                      Zip file containing individual MSEA result files. Refer to the file and parameter selection file below for the individual MSEA run codes
                    </td>
                    <td>
                      <a href=<?php print($Individual_files); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Meta MSEA File and Parameter Selection File
                    </td>
                    <td>
                      Lists chosen files and parameters for this Meta MSEA run for each study
                    </td>
                    <td>
                      <a href=<?php print($meta_overview_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Meta MSEA Job log
                    </td>
                    <td>
                      Runtime outputs and errors (if any) of job
                    </td>
                    <td>
                      <a href=<?php print($outfile); ?> download> Download</a>
                    </td>
                  </tr>
                </tbody>
              </table>

              <br>

              <div id="tabs1">
                <ul class="tab-nav tab-nav2 clearfix" style="display: table; margin: 0 auto;">
                  <li><a href="#tabs-module">Module Results</a></li>
                  <li><a href="#tabs-mergemodule">Merge Module Results</a></li>
                  <li><a href="#tabs-combinedmeta">Combined Results</a></li>
                </ul>

                <div class="tab-container">

                  <div class="tab-content clearfix" id="tabs-module">
                    <div class="table-responsive">
                      <table id="meta_module" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th> Module ID</th>
                            <th> Meta P-Value</th>
                            <th>Meta FDR</th>
                            <th>Cochran.Q</th>
                            <th>Cochran.P</th>
                            <th>I2</th>
                            <th> Module Top Gene </th>
                            <th> Module Top Marker </th>
                            <th> Module Top Association Score</th>
                            <!-- <th> Module Details </th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <?php

                          // for ($i = 1; $i < (count($meta_convert) - 1); $i++) {
                          //   //echo $convert[$i]; //write value by index
                          //   $convert_word = explode("\t", $meta_convert[$i]);

                          //   $fdr = $convert_word[2];
                          //   $fdrword = explode("%", $fdr);
                          //   $fdr = $fdrword[0];
                          //   //print($fdr);

                          //   $moduleid = $convert_word[0];
                          //   if ($moduleid != "_ctrlA" && $moduleid != "_ctrlB") {
                          //     //$l = shell_exec('grep -w ' . $moduleid . ' ' . $results_file);
                          //     $l = shell_exec('grep -w ' . $moduleid . ' ' . $meta_resultfiledesc);

                          //     $line = explode("\n", $l);
                          //     $word = explode("\t", $line[0]);

                          //     //debug_to_
                          //     if ($word[0] == $moduleid && trim($moduleid) != "") {
                          //       // prepare data for david
                          //       $lw = shell_exec('grep -w ' . $moduleid . ' ' . $module_file);
                          //       $linew = explode("\n", $lw);
                          //       $dline = "";
                          //       for ($k = 0; $k < (count($linew) - 1); $k++) {
                          //         $wline = explode("\t", $linew[$k]);
                          //         $dline .= $wline[1] . ",";
                          //       }
                          //       //top gene list;top loci;top score
                          //       $genelist = "|";
                          //       $locilist = "|";
                          //       $scorelist = "|";
                          //       for ($k = 0; $k < count($line) && $k < 5; $k++) {
                          //         $dataline = explode("\t", $line[$k]);
                          //         $genelist .= $dataline[2] . "|";
                          //         $locilist .= $dataline[4] . "|";
                          //         $scorelist .= $dataline[5] . "|";
                          //       }
                          //     } else {
                          //       $genelist = "None";
                          //       $locilist = "None";
                          //       $scorelist = "None";
                          //     }
                          ?>
                          <tr>
                            <?php
                            // for ($j = 0; $j < count($convert_word); $j++) {
                            ?>
                            <td>
                              <div style="overflow:auto; max-width:400px;display:block"> <?php //print($convert_word[$j]);
                                                                                          ?> </div>
                            </td>
                            <?php
                            // }

                            ?>
                            <td>
                              <div style="overflow:auto; max-width:200px;display:block"> <?php //print($genelist); 
                                                                                          ?> </div>
                            </td>
                            <td>
                              <div style="overflow:auto; max-width:200px;display:block"> <?php //print($locilist); 
                                                                                          ?> </div>
                            </td>
                            <td>
                              <div style="overflow:auto; max-width:200px;display:block"> <?php //print($scorelist); 
                                                                                          ?> </div>
                            </td>
                          </tr> <?php
                                //   }
                                // }

                                ?> </tbody>
                      </table>
                    </div>
                  </div>
                  <!--End of module tab ------->


                  <div class="tab-content clearfix" id="tabs-mergemodule">

                    <?php

                    $resultfile = "./sample_outputs/Sample_Meta.MSEA_merged_modules_full_result.txt";
                    $data = file_get_contents($resultfile); //read the file
                    $convert = explode("\n", $data); //create array separate by new line

                    ?>



                    <!-- Merge module ===================================================== -->
                    <div class="table-responsive">
                      <table id="meta_merge_module" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th>Merge Module ID</th>
                            <th> Merge Module P-Value</th>
                            <th>Frequency</th>
                            <th>Number of Genes</th>
                            <th>Number of Markers </th>
                            <th>Density </th>
                            <th> Overlap</th>
                            <th> Description </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          for ($i = 1; $i < (count($convert) - 1); $i++) {
                            $convert_word = explode("\t", $convert[$i]);
                            if ($convert_word[0] != "_ctrlA" && $convert_word[0] != "_ctrlB") {
                              $p_val = ($convert_word[1] - 0);
                              $freq = ($convert_word[2] - 0);
                              $dense = ($convert_word[5] - 0);
                          ?>
                              <tr>
                                <td> <?php print($convert_word[0]); ?> </td>
                                <td> <?php printf("%.2e", $p_val); ?> </td>
                                <td> <?php printf("%.2e", $freq); ?> </td>
                                <td> <?php print($convert_word[3]); ?> </td>
                                <td> <?php print($convert_word[4]); ?> </td>
                                <td> <?php printf("%.2f", $dense); ?> </td>
                                <td>
                                  <div style="overflow:auto; max-width:200px;display:block"><?php print($convert_word[6]); ?></div>
                                </td>
                                <td>
                                  <div style="overflow:auto; max-width:400px;display:block"><?php print($convert_word[7]); ?></div>
                                </td>
                              </tr>
                          <?php
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>

                  </div>
                  <!--End of merge module tab ----------------->
                  <div class="tab-content clearfix" id="tabs-combinedmeta">

                    <?php

                    $resultfile = "./sample_outputs/Sample.MSEA_meta_combined_result_site.txt";
                    $data = file_get_contents($resultfile); //read the file
                    $convert = explode("\n", $data); //create array separate by new line

                    ?>

                    <!-- Merge module ===================================================== -->
                    <div class="table-responsive">
                      <table id="combined_meta" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th>Module</th>
                            <th>P.values</th>
                            <th>FDR.values</th>
                            <th>Meta.P</th>
                            <th>Meta.FDR</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          for ($i = 1; $i < (count($convert) - 1); $i++) {
                            $convert_word = explode("\t", $convert[$i]);
                            if ($convert_word[0] != "_ctrlA" && $convert_word[0] != "_ctrlB") {
                              $p_val = ($convert_word[4] - 0);
                              $fdr = ($convert_word[5] - 0);
                          ?>
                              <tr>
                                <td> <?php print($convert_word[0]); ?> </td>
                                <td>
                                  <div style="overflow:auto; max-width:200px;display:block"><?php print($convert_word[2]); ?></div>
                                </td>
                                <td>
                                  <div style="overflow:auto; max-width:200px;display:block"><?php print($convert_word[3]); ?></div>
                                </td>
                                <td> <?php printf("%.2e", $p_val); ?> </td>
                                <td> <?php printf("%.2e", $fdr); ?> </td>
                              </tr>
                          <?php
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>

                  </div>

                </div>
              </div>
            </div>
          </div> <!-- End tab container -->
        </div> <!-- End tab header -->
      </div> <!-- End first toggle/step in Meta-MSEA -->

      <div class="toggle toggle-border" id="wKDAtoggle">
        <!-- Start second toggle/step in MDF -->
        <div class="togglet" id="wKDAtogglet"><i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i>
          <div class="capital">Weighted Key Driver Analysis</div>
        </div>
        <div class="tabs tabs-bb togglec" style="margin-bottom: 0;">
          <!-- Start tab headers -->
          <ul class="tab-nav clearfix">
            <li><a id="wKDA2" href="#wKDAResults">Results</a></li>
            <li><a id="wKDA1" href="#wKDAInputs">Input Files and Parameters</a></li>
          </ul>
          <div class="tab-container">
            <!-- Start TAB container -->
            <div class="togglec" id="wKDAInputs">
              <table class="table table-bordered review" style="text-align: center" ; id="wKDAreviewtable">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Filename/Parameters</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td rowspan="3" style="vertical-align: middle;">Files</td>
                    <td>Gene Sets</td>
                    <td style="font-weight: bold;">
                      Sample.MSEA_merged_modules.txt
                    </td>
                  </tr>
                  <tr>
                    <td>Gene Sets Description</td>
                    <td style="font-weight: bold;">
                      KEGG_Reactome_BioCarta_info.txt
                    </td>
                  </tr>
                  <tr>
                    <td>Network</td>
                    <td style="font-weight: bold;">
                      networks.hs.adipose.txt
                    </td>
                  </tr>
                  <tr>
                    <td rowspan="4">Parameters</td>
                    <td>Search Depth</td>
                    <td style="font-weight: bold;">
                      1
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Edge Type
                    </td>
                    <td style="font-weight: bold;">
                      Incoming and Outgoing
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Min Overlap
                    </td>
                    <td style="font-weight: bold;">
                      0.33
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Edge Factor
                    </td>
                    <td style="font-weight: bold;">
                      0.5
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="togglec" id="wKDAResults">
              <table class="table table-bordered review" style="text-align: center" ; id="wKDAresultstable">
                <thead>
                  <tr>
                    <th colspan="3">
                      Download KDA Output and Cytoscape Visualization Files
                    </th>
                  </tr>
                  <tr>
                    <td>
                      Key Drivers Results
                    </td>
                    <td>
                      Lists for each key driver its P and FDR values, the module of which it is a key driver for, the total number of neighbors ('N.neigh'), the number of neighbors that are members of the module ('N.obsrv'), and the expected number of neighbors that are members of the module as calculated by permutation.
                    </td>
                    <td>
                      <a href=<?php print($kda_results_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Cytoscape Edges
                    </td>
                    <td>
                      Cytoscape-ready edges file describing network connections of the top 5 key drivers from each module and their neighbors
                    </td>
                    <td>
                      <a href=<?php print($edges_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Cytoscape Nodes
                    </td>
                    <td>
                      Cytoscape-ready nodes file listing each node of the edges file and their detailed attributes which can be modified by the user
                    </td>
                    <td>
                      <a href=<?php print($nodes_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Cytoscape Module Color Mapping
                    </td>
                    <td>
                      File describing module colors. A node pie chart image (Google Chart API) will have the color(s) of the module(s) they are members of.
                    </td>
                    <td>
                      <a href=<?php print($color_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      File and Parameter Selection
                    </td>
                    <td>
                      Lists chosen files and parameters for this KDA run
                    </td>
                    <td>
                      <a href=<?php print($kda_overview_file); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Runtime job log
                    </td>
                    <td>
                      Runtime outputs and errors (if any) of job
                    </td>
                    <td>
                      <a href=<?php print($kda_outfile); ?> download> Download</a>
                    </td>
                  </tr>
                </thead>
              </table>
              <div class="table-responsive">
                <table id="wKDA_module" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>Merge Module ID</th>
                      <th>Module Description</th>
                      <th>Key Driver Node</th>
                      <th>P-Value</th>
                      <th>FDR</th>
                      <th>Module Genes</th>
                      <th>KD Subnetwork Genes</th>
                      <th>Module and Subnetwork Overlap</th>
                      <th>Fold Enrichment</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // for ($i = 1; $i < (count($kda_convert) - 1); $i++) {
                    //   $convert_word = explode("\t", $kda_convert[$i]);
                    //   $p_val = ($convert_word[2] - 0);
                    //   $fdr = ($convert_word[3] - 0);
                    //   $fold = ($convert_word[10] - 0);
                    ?>
                    <tr>
                      <td> <?php //print($convert_word[0]); 
                            ?> </td>
                      <td> <?php //print($convert_word[11]); 
                            ?> </td>
                      <td> <?php //print($convert_word[1]); 
                            ?> </td>
                      <td> <?php //printf("%.2e", $p_val); 
                            ?> </td>
                      <td> <?php //printf("%.2e", $fdr); 
                            ?> </td>
                      <td> <?php //print($convert_word[4]); 
                            ?> </td>
                      <td> <?php //print($convert_word[5]); 
                            ?> </td>
                      <td> <?php //print($convert_word[6]); 
                            ?> </td>
                      <td> <?php //printf("%.2f", $fold); 
                            ?> </td>
                    </tr>
                    <?php
                    //}
                    ?>
                  </tbody>
                </table>
              </div>

              <br>

              <span style="text-align: center;">
                <form action="/sample_outputs/cytoscape_network_Sample.php" name="figkda" target="_blank">
                  <input type="submit" class="button button-3d button-large" value="Display KDA Subnetwork" />
                </form>
              </span>
            </div>
          </div> <!-- End tab container -->
        </div> <!-- End tab header -->
      </div> <!-- End third toggle/step in MDF -->

      <div class="toggle toggle-border">
        <!-- Start second toggle/step in MDF -->
        <div class="togglet"><i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i>
          <div class="capital">KDA to PharmOmics Network Based Drug Repositioning</div>
        </div>
        <div class="tabs tabs-bb togglec">
          <!-- Start tab headers -->
          <ul class="tab-nav clearfix">
            <li><a id="pharmOmicstab2" href="#Pharm2Results">Results</a></li>
            <li><a id="pharmOmicstab1" href="#Pharm2Inputs">Genes input and Parameters</a></li>
          </ul>
          <div class="tab-container">
            <!-- Start TAB container -->
            <div class="togglec" id="Pharm2Inputs">
              <table class="table table-bordered review" style="text-align: center;">
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
                      Network Based Drug Repositioning
                    </td>
                    <td>Network Selection
                    <td style="font-weight: bold;">
                      Sample liver network
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Species Selection
                    </td>
                    <td style="font-weight: bold;">
                      Human
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Module and Gene Selection
                    </td>
                    <td style="font-weight: bold;">
                      All genes from the subnetwork
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="togglec" id="Pharm2Results">
              <table class="table table-bordered review" style="text-align: center" ; id="shinyapp2resultstable">
                <thead>
                  <tr>
                    <th colspan="2">
                      Download Output Files
                    </th>
                  </tr>
                  <tr>
                    <td>
                      Network Based Drug Repositioning Analysis File
                    </td>
                    <td>
                      <a href=<?php print($app2_resultfile); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Genes used for repositioning
                    </td>
                    <td>
                      <a href=<?php print($genefile); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Parameters overview
                    </td>
                    <td>
                      <a href=<?php print($app2_overview); ?> download> Download</a>
                    </td>
                  </tr>
                </thead>
              </table>
              <div class="table-responsive">
                <table id="shinyapp2_results" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>
                        Drug
                      </th>
                      <th>
                        Species
                      </th>
                      <th>
                        Tissue
                      </th>
                      <th>
                        Z-Score
                      </th>
                      <th>
                        Rank
                      </th>
                      <th>
                        P-value
                      </th>
                      <th>
                        Visualization Link
                      </th>
                      <th>
                        Network files
                      </th>
                    </tr>
                  </thead>
                  <?php
                  // $count = 1;
                  // $result = file($app2_resultfile);
                  // foreach ($result as $line) {
                  //   $line_array = explode("\t", $line);
                  //   $drugname = trim($line_array[0]);
                  //   $species = trim($line_array[1]);
                  //   $tissue = trim($line_array[2]);
                  //   $zscore = number_format($line_array[5], 3, ".", "");
                  //   $rank = number_format($line_array[6], 3, ".", "");
                  //   $pvalue = scientificNotation(trim((float)$line_array[7]));
                  //   $drug = trim($line_array[11]);

                  //   if (file_exists("./sample_outputs/app2_networks/$drug" . "_cytoscape_edges.txt")) {
                  //     $link = '<span style="text-align: center;margin:0px;">
                  //             <form style="margin-bottom:0;" action="/sample_outputs/write_sample_cytoscape_app2.php" name="figapp2" target="_blank">
                  //               <input type="hidden" name="drugres" value="' . $drug . '">
                  //               <input type="submit" class="button button-3d" style="padding:0% 5%; font-size 18px;" value="Display Network" />
                  //             </form>
                  //           </span>';
                  //     $download = '<a href="./sample_outputs/app2_networks/' . $drug . '_cytoscape_edges.txt" download> Download edges</a><br>
                  //                <a href="./sample_outputs/app2_networks/' . $drug . '_cytoscape_nodes.txt" download> Download nodes</a>';
                  //   } else {
                  //     $link = "None created";
                  //     $download = "None created";
                  //   }

                  //   if ($count == 1) {
                  //     $count++;
                  //     continue;
                  //   }
                  //   echo "<tr><td>$drugname</td><td>$species</td><td>$tissue</td><td>$zscore</td><td>$rank</td><td>$pvalue</td><td>$link</td><td>$download</td></tr>"; //changed 07312020 JD
                  // }

                  ?>
                </table>
              </div>
            </div>
          </div> <!-- End tab container -->
        </div> <!-- End tab header -->
      </div> <!-- End fourth toggle/step in MDF -->

      <div class="toggle toggle-border">
        <!-- Start second toggle/step in MDF -->
        <div class="togglet"><i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i>
          <div class="capital">KDA to PharmOmics Overlap Based Drug Repositioning</div>
        </div>
        <div class="tabs tabs-bb togglec">
          <!-- Start tab headers -->
          <ul class="tab-nav clearfix">
            <li><a id="pharmOmicstab2" href="#Pharm3Results">Results</a></li>
            <li><a id="pharmOmicstab1" href="#Pharm3Inputs">Input genes and modules</a></li>
          </ul>
          <div class="tab-container">
            <!-- Start TAB container -->
            <div class="togglec" id="Pharm3Inputs">
              <table class="table table-bordered review" style="text-align: center" ; id="wKDAreviewtable">
                <thead>
                  <tr>
                    <th>Drug Repositioning Analysis</th>
                    <th>Gene selection</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Overlap Based Drug Repositioning</td>
                    <td style="font-weight: bold;">
                      All genes from the subnetwork
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="togglec" id="Pharm3Results">
              <table class="table table-bordered review" style="text-align: center" ; id="shinyapp3resultstable">
                <thead>
                  <tr>
                    <th colspan="2">
                      Download Output Files
                    </th>
                  </tr>
                  <tr>
                    <td>
                      Overlap Based Drug Repositioning Analysis File
                    </td>
                    <td>
                      <a href=<?php print($app3_resultfile); ?> download> Download</a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Genes used for repositioning
                    </td>
                    <td>
                      <a href=<?php print($genefile); ?> download> Download</a>
                    </td>
                  </tr>
                </thead>
              </table>
              <div class="table-responsive">
                <table id="shinyapp3_results" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>
                        Database
                      </th>
                      <th>
                        Method
                      </th>
                      <th>
                        Drug
                      </th>
                      <th>
                        Species
                      </th>
                      <th>
                        Tissue or Cell Line
                      </th>
                      <th>
                        Study
                      </th>
                      <th>
                        Dose
                      </th>
                      <th>
                        Treatment Duration
                      </th>
                      <th>
                        Jaccard Score
                      </th>
                      <th>
                        Odds Ratio
                      </th>
                      <th>
                        P value
                      </th>
                      <th>
                        Within Species Rank
                      </th>
                    </tr>
                  </thead>
                  <?php
                  // $count = 1;
                  // $result = file($app3_resultfile);
                  // foreach ($result as $line) {
                  //   $line_array = explode("\t", $line);
                  //   $database = trim($line_array[0]);
                  //   $method = trim($line_array[1]);
                  //   $drug = trim($line_array[2]);
                  //   $species = trim($line_array[3]);
                  //   $tissue = trim($line_array[4]);
                  //   $study = trim($line_array[5]);
                  //   $dose = trim($line_array[6]);
                  //   $time = trim($line_array[7]);
                  //   $jaccard = number_format(floatval($line_array[8]), 3, ".", "");
                  //   $odds = number_format(floatval($line_array[9]), 3, ".", "");
                  //   $pvalue = scientificNotation(trim((float)$line_array[10]));
                  //   $rank = number_format(floatval($line_array[11]), 5, ".", "");
                  //   if ($count == 1) {
                  //     $count++;
                  //     continue;
                  //   }

                  //   echo "<tr><td>$database</td><td>$method</td><td>$drug</td><td>$species</td><td>$tissue</td><td>$study</td><td>$dose</td><td>$time</td><td>$jaccard</td><td>$odds</td><td>$pvalue</td><td>$rank</td></tr>";
                  // }

                  ?>
                </table>
              </div>
            </div>
          </div> <!-- End tab container -->
        </div> <!-- End tab header -->
      </div> <!-- End fourth toggle/step in MDF -->
    </div>
  </div>

  <div id="loadIDmodal" class="modal fade bs-example-modal-lg" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" id="sessionIDtitle" style="font-size: 45px;">Sample outputs</h4>

          </div>
          <div class="modal-body" id="loadIDbody" style="text-align: center;">
            <p class='instructiontext' style='font-size: 25px; margin:10px 0 0 0;padding:0px'>Loading data<span class='dots'>...</span><br>This may take a few minutes</p>
          </div>

        </div>
      </div>
    </div>
  </div>


  </section>




</body>

</html>

<script src="include/js/jquery.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.js"></script>
<script src="include/js/bs-datatable.js"></script>

<!-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

<script type="text/javascript">
  //$('#loadIDmodal').modal('toggle'); 

  $(document).ready(function() {

    //$('#content').css('opacity', '100%');
    //$('#loadIDmodal').modal('hide');
    $("#first").fadeOut();

    $("#tabs").tabs();

    $("#tabs1").tabs();

    function format(d) {
      // `d` is the original data object for the row
      return '<div class="slider">' +
        '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
        '<tr>' +
        '<td style="width:50%;">Permutation Type:</td>' +
        '<td style="width:50%;">' + d.perm + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td style="width:50%;">Max Genes in Gene Sets:</td>' +
        '<td style="width:50%;">' + d.maxgenes + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td style="width:50%;">Min Genes in Gene Sets:</td>' +
        '<td style="width:50%;">' + d.mingenes + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td style="width:50%;">Max Overlap Allowed for Merging:</td>' +
        '<td style="width:50%;">' + d.maxoverlap + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td style="width:50%;">Min Overlap Allowed for Merging:</td>' +
        '<td style="width:50%;">' + d.minoverlap + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td style="width:50%;">Number of Permutations:</td>' +
        '<td style="width:50%;">' + d.numperm + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td style="width:50%;">MSEA FDR Cutoff:</td>' +
        '<td style="width:50%;">' + d.fdrcutoff + '</td>' +
        '</tr>' +
        '</table>' +
        '</div>';
    }

    var table;

    table = $('#metareviewtable').DataTable({
      //responsive: true,
      //"autoWidth": false,
      "ajax": "./sample_outputs/Sampledata.json",
      "scrollX": true,
      "columnDefs": [{
        "targets": [2, 3, 4, 5],
        "data": "description",
        "render": function(data, type, row, meta) {
          data = JSON.stringify(data)
            .replace(/^.*[\\\/]/, '') //get File name from full path
            .replace(row["session"], '')
            .replace('"', '');
          data = data.replace('"', '');
          return data; //replace session id from uploaded file
        },
      }, ],
      "columns": [{
          "class": 'details-control',
          "orderable": false,
          "data": null,
          //"width": "5%",
          "target": 0,
          "defaultContent": ''
        },
        {
          "data": "enrichment",
          //"width": "18%",
          "target": 1
        },
        {
          "data": "association",
          //"width": "18%",
          "target": 2
        },
        {
          "data": "marker",
          //"width": "18%",
          "target": 3
        },
        {
          "data": "geneset",
          //"width": "18%",
          "target": 4
        },
        {
          "data": "genedesc",
          //"width": "18%",
          "target": 5
        }
      ],
      "order": [
        [1, 'asc']
      ]
    });

    /*

    $(window).resize(function() {
      $($.fn.dataTable.tables(true)).dataTable()
          .columns.adjust();
    });*/

    // Add event listener for opening and closing details
    $('#metareviewtable tbody').on('click', 'td.details-control', function() {
      var tr = $(this).closest('tr');
      var row = table.row(tr);

      if (row.child.isShown()) {
        // This row is already open - close it
        $('div.slider', row.child()).slideUp(function() {
          row.child.hide();
          tr.removeClass('shown');
        });
      } else {
        // Open this row
        row.child(format(row.data()), 'no-padding').show();
        tr.addClass('shown');

        $('div.slider', row.child()).slideDown();
      }
    });

  });

  $('#module_table').dataTable({
    //"paging": true
    "ajax": 'sample_outputs/Datatable_sample.MSEA_modules_pval.json',
    "order": [
      [1, 'asc']
    ],
    "columnDefs": [{
        render: function(data, type, full, meta) {
          return "<div style=\"overflow:auto; max-width:400px;display:block\">" + data + "</div>";
        },
        targets: [0, 1, 2, 3]
      }, {
        render: function(data, type, full, meta) {
          return "<div style=\"overflow:auto; max-width:200px;display:block\">" + data + "</div>";
        },
        targets: [4, 5, 6]
      }

    ]
  });
  $('#merge_module').dataTable({
    //"paging": true
    "order": [
      [1, 'asc']
    ]
  });

  $('#meta_module').dataTable({
    //"paging": true
    "ajax": 'sample_outputs/META_modules_res.json',
    "order": [
      [1, 'asc']
    ],
    "columnDefs": [{
      render: function(data, type, full, meta) {
        return "<div style=\"overflow:auto; max-width:400px;display:block\">" + data + "</div>";
      },
      targets: [0, 1, 2, 3]
    }, {
      render: function(data, type, full, meta) {
        return "<div style=\"overflow:auto; max-width:200px;display:block\">" + data + "</div>";
      },
      targets: [4, 5, 6]
    }]

  });
  $('#meta_merge_module').dataTable({
    //"paging": true
    "order": [
      [1, 'asc']
    ]
  });

  $('#combined_meta').dataTable({
    //"paging": true
    "order": [
      [4, 'asc']
    ]
  });

  $('#wKDA_module').dataTable({
    //"paging": true
    "ajax": 'sample_outputs/Datatable_sample.wKDA_kd_full_results.json',
    "order": [
      [1, 'asc']
    ]
  });

  $("#shinyapp2_results").dataTable({
    "ajax": 'sample_outputs/Datatable_sample.KDA2PHARM_app2result.json',
    "order": [
      [4, 'desc']
    ],
    autoWidth: false,
    "columnDefs": [{
        "width": "10%"
      },
      {
        "width": "10%"
      },
      {
        "width": "10%"
      },
      {
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "10%"
      },
      {
        "width": "22%"
      },
      {
        "width": "22%"
      },
      {
        render: function(data, type, full, meta) {
          return "<div style='min-width: 8em;'>" + data + "</div>";
        },
        targets: [4, 5, 7]
      }
    ],

  });

  $("#shinyapp3_results").dataTable({
    "ajax": 'sample_outputs/Datatable_sample.KDA2PHARM_app3result.json',
    "order": [
      [8, 'desc']
    ],

  });
</script>

<!-- External JavaScripts IMPORTANT!
============================================= -->
<script src="include/js/plugins.js"></script>
<script src="include/js/bs-filestyle.js"></script>
<!-- Footer Scripts IMPORTANT!
============================================= -->
<script src="include/js/functions.js"></script>