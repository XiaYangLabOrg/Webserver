<?php
function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
function readMappingFile($path)
{
    $handle = fopen($path, "r");
    $content = "";
    if ($handle) {
        $row = 0;
        while (($line = fgets($handle)) !== false) {
            $row++;
            if ($row > 1) {
                $content .= $line;
            }
        }
        fclose($handle);
        return $content;
    }
}

$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
}

if (isset($_GET['run'])) {
    $run = $_GET['run'];
}

if (isset($_GET['rmchoice']) ? $_GET['rmchoice'] : null) {
    $rmchoice = $_GET['rmchoice'];
}
#Started from MDF
if ($rmchoice == 1) {
    $ssea_json = $ROOT_DIR . "Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
    $data = json_decode(file_get_contents($ssea_json),true)["data"][0];
#Started from TWAS, EWAS ,MWAS
} else {
    $msea_json = $ROOT_DIR . "Data/Pipeline/Resources/msea_temp/$sessionID" . "data.json";
    $data = json_decode(file_get_contents($msea_json),true)["data"][0];
}
$perm_type = $data["perm"];
$max_gene = $data["maxgenes"];
$min_gene = $data["mingenes"];
$minoverlap = $data["minoverlap"];
$maxoverlap = $data["maxoverlap"];
$mseanperm = $data["numperm"];
$fdrval = $data["fdrcutoff"];
$marker_association = $data["association"];
$mapping = $data["marker"];
$mdf = $data["mdf"];
$mdf_ntop = $data["mdf_ntop"];
$module_file = $data["geneset"];
$enrichment = $data["enrichment"];
$module_info = $data["genedesc"];
$GSETConvert = $data["GSETConvert"];

if (is_string($mapping)) {
    $newMappingcontent = "GENE" . "\t" . "MARKER" . "\n";
    foreach ($mapping as &$value) {
        $newMappingcontent .= readMappingFile($ROOT_DIR . "Data/Pipeline/" . $value);
    }
    $mapping = "Resources/ssea_temp/" . $sessionID . ".mappingfile.txt";
    $fp = fopen("./Data/Pipeline/" . $mapping, 'w');
    fwrite($fp, $newMappingcontent);
    fclose($fp);
} else {
    if (is_array($mapping)) {
        $mapping_val = $mapping[0];
    } else {
        $mapping_val = $mapping;
    }
}


$resultfile = $ROOT_DIR . "/Data/Pipeline/Results/ssea/$sessionID.pvalues.txt";
$resultfiledesc = $ROOT_DIR . "/Data/Pipeline/Results/ssea/$sessionID.details.txt";
$fpath = $ROOT_DIR . "/Data/Pipeline/Results/$sessionID.txt";
$results = $ROOT_DIR . "/Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_full_result.txt";


if (isset($_GET['result'])) {
    //do nothing
} else if (isset($_GET['run'])) {
    if ($run == "T") {
        $outfile = $ROOT_DIR . "Data/Pipeline/Results/ssea/" . $sessionID . ".MSEA_joblog.txt";
        //Run Rscript if the result file does not exist or $run is T meaning the page is loaded from a button click
        //debug_to_console($ROOT_DIR . "/Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_pval.txt:" . file_exists($ROOT_DIR . "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_pval.txt"));
        if (!file_exists($ROOT_DIR . "/Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_pval.txt") || $run == "T") {
            //shell_exec('./run_ssea_hdlc.sh ' . $sessionID);
            // debug_to_console('cd ' . $ROOT_DIR . '/Data/Pipeline ;' .
            //     $ROOT_DIR . '/R-3.4.4/bin/Rscript ./' . $sessionID . 'analyze.R');
            // shell_exec('cd ' . $ROOT_DIR . '/Data/Pipeline ;' .
            //     $ROOT_DIR . 'Rscript ./' . $sessionID . 'analyze.R 2>&1 | tee -a ' . $outfile);
            

            
            shell_exec('cd ' . $ROOT_DIR . '/Data/Pipeline ;' .
                'Rscript ./' . $sessionID . 'analyze.R 2>&1 | tee -a ' . $outfile);
        }
    }
} else if (file_exists($results)) {
    //do nothing
} else {
    //do nothing
}




// RENAME FILES

// RENAME PVALUE FILE
if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.pvalues.txt")) {
    rename("./Data/Pipeline/Results/ssea/$sessionID.pvalues.txt", "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_pval.txt");
}

// RENAME DETAILS FILE
if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.details.txt")) {
    rename("./Data/Pipeline/Results/ssea/$sessionID.details.txt", "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_details.txt");
}

// RENAME GENES FILE
if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.genes.txt")) {
    rename("./Data/Pipeline/Results/ssea/$sessionID.genes.txt", "./Data/Pipeline/Results/ssea/$sessionID.MSEA_genes_details.txt");
}
// RENAME RESULTS FILE
if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.results.txt")) {
    rename("./Data/Pipeline/Results/ssea/$sessionID.results.txt", "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_full_result.txt");
}
// RENAME NODES FILE
if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.nodes.txt")) {
    rename("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.nodes.txt", "./Data/Pipeline/Results/ssea/$sessionID.MSEA_genes_top_marker.txt");
}
// RENAME INFO FILE
if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.info.txt")) {
    rename("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.info.txt", "./Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules_full_result.txt");
}
// RENAME MODULES FILE
if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.modules.txt")) {
    rename("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.modules.txt", "./Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules.txt");
}
// RENAME MODULES FILE
if (file_exists("./Data/Pipeline/Results/ssea/" . "$sessionID" . "_overview.txt")) {
    rename("./Data/Pipeline/Results/ssea/" . "$sessionID" . "_overview.txt", "./Data/Pipeline/Results/ssea/$sessionID.MSEA_file_parameter_selection.txt");
}


$resultfile = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_pval.txt";

if($GSETConvert!=="none"){
    $genesetconverted =  "./Data/Pipeline/Resources/ssea_temp/Converted_" . basename($module_file);
}

/*
if ((!(file_exists($results_sent)))) {
    if(file_exists($email)) {
        ?>
        <script>
            window.open("sendemail.php?My_key=<?php print($sessionID);?>","_self",false);
        </script>
    <?php
    }
}

*/


$data = file_get_contents($resultfile); //read the file

$convert = explode("\n", $data); //create array separate by new line

$resultdownload = "./Data/Pipeline/Results/ssea/$sessionID.details.txt";








// else
//   $fpathparam = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "MODULE";








$resultfiledesc = "./Data/Pipeline/Results/ssea/" . "$sessionID" . ".MSEA_modules_details.txt";
$genes_file = "./Data/Pipeline/Results/ssea/" . "$sessionID" . ".MSEA_genes_details.txt";
$pvalues_file = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_pval.txt";
$results_file = "./Data/Pipeline/Results/ssea/" . "$sessionID" . ".MSEA_modules_full_result.txt";
$nodes_file = "./Data/Pipeline/Results/ssea/" . "$sessionID" . ".MSEA_genes_top_marker.txt";
$info_file = "./Data/Pipeline/Results/ssea/" . "$sessionID" . ".MSEA_merged_modules_full_result.txt";
$modules_file = "./Data/Pipeline/Results/ssea/" . "$sessionID" . ".MSEA_merged_modules.txt";
$overview_file = "./Data/Pipeline/Results/ssea/" . "$sessionID" . ".MSEA_file_parameter_selection.txt";
$joblogfile = "./Data/Pipeline/Results/ssea/" . "$sessionID" . ".MSEA_joblog.txt";


$fpathOut = "./Data/Pipeline/$sessionID" . "newgeneset.R";


//$function = file_get_contents($ROOT_DIR . "R_Scripts/makeNewGeneSetFileOnlySNPMappedGenes.R");
$geneset = $module_file;
$module_file = "./Data/Pipeline/" . $module_file; //for the results table JD changed
// ended up commenting out that part anyway


// Added this R code to part2.txt (used to build MSEA script in run_MSEA.php JD
/*
$str2 = substr($resultfiledesc, 2);
$output = "source(\"" . $ROOT_DIR . "/R_Scripts/cle.r\")\n" . 'makeNewGeneSetFileOnlySNPMappedGenes(geneset_file = "' . $ROOT_DIR . '/Data/Pipeline/' . $geneset . '", details_file = "' . $ROOT_DIR . '/' . $str2 . '")';

$fp = fopen($fpathOut, "w");
fwrite($fp, $function);
fwrite($fp, $output);
fclose($fp);
chmod($fpathOut, 0777);

$newgeneset = "./Data/Pipeline/Results/ssea/New_GeneSets_SNP_mapped.txt";
if($rmchoice==1){ // JD changed
    if ((!(file_exists($newgeneset)) && !(file_exists("./Data/Pipeline/Results/ssea/$sessionID.GeneSets_SNP_mapped.txt"))) || $run == "T") {
        //shell_exec('./run_geneset.sh ' . $sessionID);
        // debug_to_console('cd ' . $ROOT_DIR . '/Data/Pipeline ;' .
        //     $ROOT_DIR . '/R-3.4.4/bin/Rscript ./' . $sessionID . 'newgeneset.R');
        shell_exec('cd ' . $ROOT_DIR . '/Data/Pipeline ;' .
            $ROOT_DIR . '/R-3.4.4/bin/Rscript ./' . $sessionID . 'newgeneset.R');
        chmod($newgeneset, 0777);
        rename("./Data/Pipeline/Results/ssea/New_GeneSets_SNP_mapped.txt", "./Data/Pipeline/Results/ssea/$sessionID.GeneSets_SNP_mapped.txt");
    } else {
        if (file_exists($newgeneset)) {
            rename("./Data/Pipeline/Results/ssea/New_GeneSets_SNP_mapped.txt", "./Data/Pipeline/Results/ssea/$sessionID.GeneSets_SNP_mapped.txt");
        }
    }
}
*/

/***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we have a txt file with the path information
 ***************************************/

$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
//$fpostOut = "./Data/Pipeline/Resources/ssea_temp/$sessionID" . "_SSEA_postdata.txt";
if (file_exists($fsession)) {
    $session = explode("\n", file_get_contents($fsession));
    //Create different array elements based on new line
    $pipe_arr = preg_split("/[\t]/", $session[0]);
    $pipeline = $pipe_arr[1];

    if ($pipeline == "GWASskipped") {
        $data = file($fsession); // reads an array of lines
        function replace_a_line($data)
        {
            if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.5")) {
                return 'Mergeomics_Path:' . "\t" . "1.75" . "\n";
            }
            return $data;
        }
        $data = array_map('replace_a_line', $data);
        // if (!empty($data))
        //     file_put_contents($fsession, implode('', $data));
    } else if ($pipeline == "GWAS") {
        $data = file($fsession); // reads an array of lines
        function replace_a_line($data)
        {
            if (stristr($data, 'Mergeomics_Path:' . "\t" . "2.5")) {
                return 'Mergeomics_Path:' . "\t" . "2.75" . "\n";
            }
            return $data;
        }
    } else if ($pipeline == "MSEA") {
        $data = file($fsession); // reads an array of lines
        function replace_a_line($data)
        {
            if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.5")) {
                return 'Mergeomics_Path:' . "\t" . "1.75" . "\n";
            }
            return $data;
        }
    }
    $data = array_map('replace_a_line', $data);
    if (!empty($data))
        file_put_contents($fsession, implode('', $data));
}


?>

<?php
//added conditional 8.11.2020
if (!(file_exists($resultfiledesc))) { ?>
    <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
        <div class="alert alert-danger" style="margin: 0 auto; width: 50%;">
            <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i><strong>No enriched modules were found.</strong> You may not have had many associations or the markers in your association file did not match many markers in your mapping file. Make sure the markers in your association file match those of the marker sets you are testing. If they do not, make sure to use a mapping file to map your markers to those of the marker set. You may also check the 'Runtime job log' to see any errors.
        </div>
    </div>
<?php }
?>


<link rel="stylesheet" href="include/bs-datatable.css" type="text/css" />
<!-- Description ===================================================== -->

<br>
<br>

<table class="table table-bordered review" style="text-align: center" ; id="MSEAresultstable">

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
        <!-- Deemed not of interest
    <?php
    //if ($rmchoice == 1) { 
    ?>
    <tr>
      <td>
        Genes Details File
      </td>
      <td>
        <a href=<?php //print($genes_file); 
                ?> download> Download</a>
      </td>
    </tr>
    <?php //}
    ?>
    <tr> 
      <td>
        MSEA Modules P Values File
      </td>
      <td>
        <a href=<?php //print($pvalues_file); 
                ?> download> Download</a>
      </td>
    </tr>
    -->
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
        <!-- Deemed not of interest
    <?php
    //if ($rmchoice == 1) { 
    ?>
    <tr>
      <td>
        MSEA Genes Top Markers File
      </td>
      <td>
        <a href=<?php //print($nodes_file); 
                ?> download> Download</a>
      </td>
    </tr>
    <?php //}
    ?>
    -->
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
    <?php
    if ($GSETConvert !== "none") { 
      if($GSETConvert == "entrez"){
        $convertedfile = "Entrez to gene symbols converted gene set file";
      } else {
        $convertedfile = "Ensembl to gene symbols converted gene set file";
      }
      /*
      if($run=="F"){
        $genesetconverted = $genesetconverted[0];
      }
      */
    ?>
    <tr>
      <td>
        <?php echo $convertedfile; ?> 
      </td>
      <td>
        Original gene set file with gene identifers converted to gene symbols. Gene identifiers not matching any gene symbols were removed.
      </td>
      <td>

        <a href=<?php print($genesetconverted); ?> download> Download</a>
      </td>
    </tr>
    <?php }
    ?>
    </tbody>
</table>




<br>
<div style="text-align: center;">
    <input type="button" class="button button-3d button-small nomargin" value="Click to Download All Files in Zip Folder" onclick="window.open('ssea_zip.php?My_ses=<?php print($sessionID); ?>','_self','resizable=yes')" />
</div>

<br>
<br>
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

                            <?php
                            if($module_info!=="None Provided"){
                            ?>
                            <th>Description</th>
                            <?php
                            }
                            ?>
                            <th>Module Top Gene </th>
                            <th> Module Top Marker </th>
                            <th> Module Top Association Score</th>
                            <!-- <th> Module Details </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 1; $i < (count($convert) - 1); $i++) {
                            //echo $convert[$i]; //write value by index
                            $convert_word = explode("\t", $convert[$i]);
                            $fdr = $convert_word[2];
                            $fdrword = explode("%", $fdr);
                            $fdr = $fdrword[0];
                            if($module_info=="None Provided"){
                                $convert_word = array_slice($convert_word, 0, -1);
                            }
                            //print($fdr);
                        ?>
                            <?php
                            $moduleid = $convert_word[0];
                            if ($moduleid != "_ctrlA" && $moduleid != "_ctrlB" && $fdrval >= $fdr) {
                                $l = shell_exec('grep -w ' . $moduleid . ' ' . $resultfiledesc);
                                $line = explode("\n", $l);
                                $word = explode("\t", $line[0]);
                                if ($word[0] == $moduleid && trim($moduleid) != "") {
                                    // prepare data for david
                                    /*
                                    $lw = shell_exec('grep -w ' . $moduleid . ' ' . $module_file);
                                    debug_to_console('grep -w ' . $moduleid . ' ' . $module_file);
                                    $linew = explode("\n", $lw);
                                    /$dline = "";
                                    for ($k = 0; $k < (count($linew) - 1); $k++) {
                                        $wline = explode("\t", $linew[$k]);
                                        $dline .= $wline[1] . ",";
                                    }
                                    */
                                    //top gene list;top loci;top score
                                    $genelist = "|";
                                    $locilist = "|";
                                    $scorelist = "|";
                                    for ($k = 0; $k < count($line) && $k < 5; $k++) {
                                        $dataline = explode("\t", $line[$k]);
                                        $genelist .= $dataline[2] . "|";
                                        $locilist .= $dataline[4] . "|";
                                        $scorelist .= $dataline[5] . "|";
                                    }
                            ?>
                                    <tr>
                                        <?php
                                        for ($j = 0; $j < count($convert_word); $j++) {
                                        ?>
                                            <td>
                                                <div style="overflow:auto; max-width:400px;display:block"> <?php print($convert_word[$j]); ?> </div>
                                            </td>
                                        <?php
                                        }

                                        ?>
                                        <td>
                                            <div style="overflow:auto; max-width:200px;display:block"> <?php print($genelist); ?> </div>
                                        </td>
                                        <td>
                                            <div style="overflow:auto; max-width:200px;display:block"> <?php print($locilist); ?> </div>
                                        </td>
                                        <td>
                                            <div style="overflow:auto; max-width:200px;display:block"> <?php print($scorelist); ?> </div>
                                        </td>
                                        <!-- <td> <a href=http://david.abcc.ncifcrf.gov/api.jsp?type=GENE_SYMBOL&ids=<?php //print($dline); 
                                                                                                                        ?>&tool=summary> David </a> </td>  -->
                                    </tr> <?php
                                        }
                                    }
                                }
                                            ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--End of module tab ------->


        <div class="tab-content clearfix" id="tabs-mergemodule">
            <?php

            $resultfile = $ROOT_DIR . "/Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules_full_result.txt";


            $data = file_get_contents($resultfile); //read the file
            $convert = explode("\n", $data); //create array separate by new line

            $resultdownload2 = "/Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules.txt";


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
                            <?php
                            if($module_info!=="None Provided"){
                            ?>
                            <th> Description </th>
                            <?php
                            }
                            ?>
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
                                    <?php
                                        if($module_info!=="None Provided"){
                                    ?>
                                    <td>
                                        <div style="overflow:auto; max-width:400px;display:block"><?php print($convert_word[7]); ?></div>
                                    </td>
                                    <?php
                                    }
                                    ?>
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


<br>
<br>
<br>


<?php
// added conditional 8.11.2020
if (file_exists($resultfiledesc)) { ?>
    <h4 class="instructiontext">To continue directly to wKDA or PharmOmics (Drug Repositioning) using the MSEA Results click below:
        <br>
        <button type="button" class="button button-3d button-large pipeline" id="RunwKDA">Run wKDA Pipeline</button>
        <button type="button" class="button button-3d button-large pipeline" id="Runpharmomics">Run PharmOmics Pipeline</button>
    </h4>
    <div id="preload"></div>
<?php }
?>

<?php
// added conditional 8.11.2020
if (!(file_exists($resultfiledesc))) { ?>
    <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
        <div class="alert alert-danger" style="margin: 0 auto; width: 50%;">
            <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i>Unable to continue to KDA or Pharmomics without results from MSEA.
        </div>
    </div>
<?php }
?>


<?php

$resultfile = $ROOT_DIR . "Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules_full_result.txt";


$data = file_get_contents($resultfile); //read the file

$convert = explode("\n", $data); //create array separate by new line

$resultdownload2 = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules.txt";


$results_sent = "./Data/Pipeline/Results/ssea_email/$sessionID" . "sent_email";
$results_sent_notified = "./Data/Pipeline/Results/ssea_email/$sessionID" . "sent_email_notified";

if ((file_exists($results_sent))) {
    if (!(file_exists($results_sent_notified))) {
        if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.pvalues.txt")) {
            $resultfile = "./Data/Pipeline/Results/ssea/$sessionID.pvalues.txt";
        } else {
            $resultfile = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_pval.txt";
        }
        if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.details.txt")) {
            $resultfiledesc = "./Data/Pipeline/Results/ssea/$sessionID.details.txt";
        } else {
            $resultfiledesc = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_details.txt";
        }
        if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.genes.txt")) {
            $resultgenes = "./Data/Pipeline/Results/ssea/$sessionID.genes.txt";
        } else {
            $resultgenes = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_genes_details.txt";
        }
        if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.results.txt")) {
            $results = "./Data/Pipeline/Results/ssea/$sessionID.results.txt";
        } else {
            $results = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_full_result.txt";
        }
        if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.nodes.txt")) {
            $nodes = "./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.nodes.txt";
        } else {
            $nodes = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_genes_top_marker.txt";
        }
        if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.info.txt")) {
            $info = "./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.info.txt";
        } else {
            $info = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules_full_result.txt";
        }
        if (file_exists("./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.modules.txt")) {
            $mergemodules = "./Data/Pipeline/Results/ssea/$sessionID.ssea2kda.modules.txt";
        } else {
            $mergemodules = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules.txt";
        }
        if (file_exists("./Data/Pipeline/Results/ssea/" . "$sessionID" . "_overview.txt")) {
            $overview = "./Data/Pipeline/Results/ssea/" . "$sessionID" . "_overview.txt";
        } else {
            $overview = "./Data/Pipeline/Results/ssea/$sessionID.MSEA_file_parameter_selection.txt";
        }
        #require_once('./PHPMailer-master/class.phpmailer.php');

        #PHPMailer has been updated to the most recent version (https://github.com/PHPMailer/PHPMailer)
        #Mail function is written at sendEmail in functions.php - Jan.3.2024 Dan
        $emailid = "./Data/Pipeline/Results/ssea_email/$sessionID" . "email";
        include_once("functions.php");
        $recipient = trim(file_get_contents($emailid));
        $title = "Mergeomics - Marker Set Enrichment Analysis (MSEA) Execution Complete!";
        $body  = "Congratulations! You have successfully executed our pipeline. Please download your results.\n";
        $body .= "Your results are available at: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=";
        $body .= "$sessionID";
        $body .= "\nNote: Your results will be deleted from the server after 24 hours";
        sendEmail($recipient,$title,$body,$results_sent_notified);
    }
}



?>


<script src="include/js/bs-datatable.js"></script>


<script type="text/javascript">
    $('#module_table').dataTable({
        //"paging": true
        "order": [
            [1, 'asc']
        ]
    });
    $('#merge_module').dataTable({
        //"paging": true
        "order": [
            [1, 'asc']
        ]
    });
    $("#tabs").tabs();

    var function_for_display_animation = function() {
        $("#preload").html(`<h4 style="padding: 10px" class='instructiontext'>Transferring data to PharmOmics....<br><img src='include/pictures/ajax-loader.gif' /></h4>`);
    }
    var function_for_remove_animation = function() {
        $("#preload").html('');
    }
    var string = "<?php echo $sessionID; ?>";

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
      if(val<=0){
        var val = $(href).offset().top - 65;
      }

      $(window).scrollTop(
        //$(href).offset().top - $(window).offset().top + $(window).scrollTop() - 60
        //$(href).offset().top - $(window).scrollTop() - 60
        val
      );

      return false;
    });
</script>


<?php
if ($rmchoice == 1) {
?>
    <script type="text/javascript">
        $('#RunwKDA').on('click', function() {
            $('#SSEAtogglet').click();
            $('#wKDAtoggle').show();
            $('#mywKDA').load('/wKDA_parameters.php?sessionID=' + string + "&rmchoice=1");

            $('#wKDAtogglet').click();

            //sidebar
            $("#MSEAflowChart").next().css('opacity','1');
            $("#MSEAtoPharmflowChart").next().css('opacity','1');
            $("#KDAflowChart").addClass('activePipe').html('<a href="#wKDAtoggle" class="pipelineNav">KDA</a>').css('opacity','1');

            return false;
        });

        $('#Runpharmomics').on('click', function() {
            function_for_display_animation();
            $('#myssea2pharm').load('/ssea2pharmomics_parameters.php?sessionID=' + string + "&rmchoice=1", function_for_remove_animation);
            setTimeout(function() {
                $('#ssea2pharmtoggle').show();
                $('#SSEAtogglet').click();
                $('#ssea2pharmtogglet').click();

            }, 500);

            //sidebar
            $("#MSEAflowChart").next().css('opacity','1');
            $("#MSEAtoPharmflowChart").addClass('activePipe').html('<a href="#pharmOmicstoggle" class="pipelineNav">MSEA to PharmOmics</a>').css('opacity','1');

            return false;
        });
    </script>

<?php
} else if ($rmchoice == 2) {
?>
    <script type="text/javascript">
        $('#RunwKDA').on('click', function() {
            $('#MSEAtogglet').click();
            $('#MSEA2KDAtoggle').show();
            $('#myMSEA2KDA').load('/wKDA_parameters.php?sessionID=' + string + "&rmchoice=2");

            $('#MSEA2KDAtogglet').click();

            //sidebar
            $("#MSEAflowChart").next().css('opacity','1');
            $("#MSEAtoPharmflowChart").next().css('opacity','1');
            $("#KDAflowChart").addClass('activePipe').html('<a href="#MSEA2KDAtoggle" class="pipelineNav">KDA</a>').css('opacity','1');

            return false;
        });

        $('#Runpharmomics').on('click', function() {
            function_for_display_animation();
            $('#mymsea2pharm').load('/ssea2pharmomics_parameters.php?sessionID=' + string + "&rmchoice=2", function_for_remove_animation);
            setTimeout(function() {
                $('#msea2pharmtoggle').show();
                $('#MSEAtogglet').click();
                $('#msea2pharmtogglet').click();

            }, 500);

            //sidebar
            $("#MSEAflowChart").next().css('opacity','1');
            $("#MSEAtoPharmflowChart").addClass('activePipe').html('<a href="#msea2pharmtoggle" class="pipelineNav">MSEA to PharmOmics</a>').css('opacity','1');

            return false;
        });
    </script>


<?php
} else {
?>

    <script type="text/javascript">
        $('#RunwKDA').on('click', function() {
            $('#METAtogglet').click();
            $('#METAKDAtoggle').show();
            $('#myMETAKDA').load('/wKDA_parameters.php?sessionID=' + string + "&rmchoice=3");
            $('#METAKDAtogglet').click();

            //sidebar
            $("#METAflowChart").next().css('opacity','1');
            $("#MSEAtoPharmflowChart").next().css('opacity','1');
            $("#KDAflowChart").addClass('activePipe').html('<a href="#META2KDAtoggle" class="pipelineNav">KDA</a>').css('opacity','1');

            return false;
        });

        $('#Runpharmomics').on('click', function() {
            function_for_display_animation();
            $('#mymsea2pharm').load('/ssea2pharmomics_parameters.php?sessionID=' + string + "&rmchoice=3", function_for_remove_animation);
            setTimeout(function() {
                $('#msea2pharmtoggle').show();
                $('#MSEAtogglet').click();
                $('#msea2pharmtogglet').click();

            }, 500);

            //sidebar
            $("#METAflowChart").next().css('opacity','1');
            $("#MSEAtoPharmflowChart").addClass('activePipe').html('<a href="#METAMSEA2PHARMtoggle" class="pipelineNav">MSEA to PharmOmics</a>').css('opacity','1');

            return false;
        });
    </script>


<?php
}

?>