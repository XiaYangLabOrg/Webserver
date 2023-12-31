<?php
function debug_to_console($data)
{
  $output = $data;
  if (is_array($output))
    $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}
if (isset($_GET['rmchoice']) ? $_GET['rmchoice'] : null) {
  $rmchoice = $_GET['rmchoice'];
}
if (isset($_GET['run'])) {
  $run = $_GET['run'];
}


$resultfile = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.results.txt";
$resultfiledownload = "http://mergeomics.research.idre.ucla.edu/Data/Pipeline/Results/kda/$sessionID.results.txt";


$fpath = "./Data/Pipeline/Results/$sessionID.txt";

$outfile = $ROOT_DIR . "Data/Pipeline/Results/kda/" . $sessionID . ".wKDA_joblog.txt";


if (isset($_GET['run'])) {
  if($run == 'T'){
    if(file_exists($ROOT_DIR . "Data/Pipeline/Results/kda/" . "$sessionID" . ".wKDA_kd_full_results.txt")){
      $rerun = "T";
    } else{
      $rerun = "F";
    }

    //$info = shell_exec('./run_kda.sh ' . $sessionID . ' 2>&1');
    //debug_to_console('cd ' . $ROOT_DIR . 'Data/Pipeline;' . $ROOT_DIR . 'R-3.4.4/bin/Rscript ./' . $sessionID . 'analyzekda.R');
    $info = shell_exec('Rscript ./' . $sessionID . 'analyzekda.R 2>&1 | tee -a ' . $outfile);
    //var_dump($info);
    //$outs = file($outfile);
    //$last_line = $outs[count($outs)-1];
    //$outs = escapeshellarg($outfile); 
    //$last_line = `tail -n 2 $outs`;
    $last_line = shell_exec("tail -1 ".$outfile . " | head -1");
    //var_dump($last_line);
    //debug_to_console($last_line);
    //echo $last_line;
    //debug_to_console(gettype($last_line));
    //$dump = var_export($last_line, true);
    //debug_to_console($varinfo['string']);
    /*
    ob_start();
    var_dump($last_line);
    $varinfo = ob_get_clean();
    */
    //eval("\$varinfo=$dump;");
    //debug_to_console($varinfo);
    //debug_to_console($varinfo['string']);
    //debug_to_console(gettype($varinfo));
    //debug_to_console(unvar_dump($dump));
    //echo $varinfo;
    
    /*
    foreach ($varinfo as &$value) {
      debug_to_console($value);
    }
    */
    
    //if($last_line=="Execution halted" && $rerun = "T"){
    if(strpos($last_line, "Execution halted") !== false){
    //if($last_line=="Execution halted " && $rerun == "T"){
      $failedon2ndrun = "T";
    }
    else{
      $failedon2ndrun = "F";
    }
    debug_to_console($failedon2ndrun);
  }
} else if (file_exists($resultfile) && file_exists($ROOT_DIR . "Data/Pipeline/Results/kda/" . "$sessionID" . ".wKDA_kd_full_results.txt")) {
  // do nothing
} else {
  // debug_to_console("wKDA session loaded : shell_exec did not run");
}


// RENAME FILES

// RENAME HUBS FILE
$hubs_file = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.hubs.txt";
$hubs_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_hubs_structure.txt";
if (file_exists($hubs_file)) {
  rename($hubs_file, $hubs_file_renamed);
}

// RENAME PVALUES FILE
$pvalues_file =  $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.pvalues.txt";
$pvalues_file_renamed =  $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_kd_pval.txt";
if (file_exists($pvalues_file)) {
  rename($pvalues_file, $pvalues_file_renamed);
}

// RENAME RESULTS FILE
$results_file = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.results.txt";
$results_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_kd_full_results.txt";
if (file_exists($results_file)) {
  rename($results_file, $results_file_renamed);
}
// RENAME TOPHITS FILE
$top_hits_file = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.tophits.txt";
$top_hits_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_kd_tophits.txt";
if (file_exists($top_hits_file)) {
  rename($top_hits_file, $top_hits_file_renamed);
}

// RENAME EDGES FILE
$cytoscape_edges_file = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.kda2cytoscape.edges.txt";
$cytoscape_edges_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_edges.txt";
if (file_exists($cytoscape_edges_file)) {
  rename($cytoscape_edges_file, $cytoscape_edges_file_renamed);
}

// RENAME NODES FILE
$cytoscape_nodes_file = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.kda2cytoscape.nodes.txt";
$cytoscape_nodes_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_nodes.txt";
if (file_exists($cytoscape_nodes_file)) {
  rename($cytoscape_nodes_file, $cytoscape_nodes_file_renamed);
}

//RENAME TOP KDS FILE
$cytoscape_top_kds_file = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.kda2cytoscape.top.kds.txt";
$cytoscape_top_kds_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_top_kds.txt";
if (file_exists($cytoscape_top_kds_file)) {
  rename($cytoscape_top_kds_file, $cytoscape_top_kds_file_renamed);
}

//RENAME COLOR MAPPIGN FILE
$color_mapping_file = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.module.color.mapping.txt";
$color_mapping_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_module_color_mapping.txt";
if (file_exists($color_mapping_file)) {
  rename($color_mapping_file, $color_mapping_file_renamed);
}

//RENAME OVERVIEW FILE
$overveiw_file = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID" . "_overview.txt";
$overview_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_file_parameter_selection.txt";
if (file_exists($overveiw_file)) {
  rename($overveiw_file, $overview_file_renamed);
}

$resultfile = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_kd_full_results.txt";





$data = file_get_contents($resultfile); //read the file

$convert = explode("\n", $data); //create array separate by new line

$hubs_file = "./Data/Pipeline/Results/kda/$sessionID.wKDA_hubs_structure.txt";
$pvalues_file = "./Data/Pipeline/Results/kda/$sessionID.wKDA_kd_pval.txt";
$results_file = "./Data/Pipeline/Results/kda/$sessionID.wKDA_kd_full_results.txt";
$tophits_file = "./Data/Pipeline/Results/kda/$sessionID.wKDA_kd_tophits.txt";
$overview_file = "./Data/Pipeline/Results/kda/$sessionID.wKDA_file_parameter_selection.txt";

$edges_file = "./Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_edges.txt";
$nodes_file = "./Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_nodes.txt";
$topkds_file = "./Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_top_kds.txt";
$color_file = "./Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_module_color_mapping.txt";
$outfile = "./Data/Pipeline/Results/kda/" . $sessionID . ".wKDA_joblog.txt";



$num_cols = count(explode("\t", $convert[0]));

//Create node files for upload to shinyapp3
if (file_exists($nodes_file)) {
  $counter = 0;

  $array = file($nodes_file);
  $new_array = array();
  //$new_array[] = "GENE";

  // loop through array
  foreach ($array as $line) {
    // Skip header.
    if ($counter++ == 0) continue;
    // explode the line on tab. Note double quotes around \t are mandatory
    $line_array = explode("\t", $line);
    // set first element to the new array
    $new_array[] = $line_array[0];
  }

  $finished = implode("\n", $new_array);

  $genefileOut = $ROOT_DIR . "Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_up_genes.txt";
  $myfile = fopen($genefileOut, "w");
  fwrite($myfile, $finished);
  fclose($myfile);
  chmod($genefileOut, 0775);
}

$fjson = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "param.json";
$data = json_decode(file_get_contents($fjson))->data;
$NetConvert = $data[0]->NetConvert;
$GSETConvert = $data[0]->GSETConvert;
if($NetConvert!=="none"){
  $kdapath = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDA";
  $kdapath = trim(file_get_contents($kdapath));
  $netconvertedfile = "./Data/Pipeline/Resources/kda_temp/Converted_" . basename($kdapath);
}
if($GSETConvert!=="none"){
  $fpath = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAMODULE";
  if(file_exists($fpath)){
    $fpath = trim(file_get_contents($fpath));
  } else {
    $fpath = $data[0]->geneset;
  }
  $gsetconvertedfile = "./Data/Pipeline/Resources/kda_temp/Converted_" . basename($fpath);
}


if (file_exists($nodes_file) && file_exists($color_file)) {
  $counter = 0;
  $counter1 = 0;

  $modulefileOut = $ROOT_DIR . "Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_module_genes.txt";
  $module_file = fopen($modulefileOut, "w");

  $array_color = file($color_file);
  $array_nodes = file($nodes_file);
  // loop through module color array
  foreach ($array_color as $line2) {
    // Skip header.
    if ($counter++ == 0) continue;
    // explode the line on tab. Note double quotes around \t are mandatory
    $line_color = explode("\t", $line2);
    // set module variable to the module ID
    //$module = trim(strtr($line_color[0], array('.' => '', ',' => ''))); 
    $module = trim($line_color[1]); //JD changed
    // set sector variable to the sector ID
    $sector = "1:" . trim(substr($line_color[2], 1));
    //loop through nodes file to find each gene that is part of the module


    foreach ($array_nodes as $line3) {

      // Skip header.
      if ($counter1++ == 0) continue;

      // explode the line on tab. Note double quotes around \t are mandatory
      $line_node = explode("\t", $line3);
      $sectorcheck = trim($line_node[5]);

      if (strpos($sectorcheck, $sector) !== false) //check if the sector is in the line
      {
        //if found, pass the node into an array; this will create an array of genes that corresponds to the module
        $new_gene = trim($line_node[0]);

        $write = "$module\t$new_gene\n";
        fwrite($module_file, $write);
      }
    }

    $counter1 = 0; //reset the secondloop
  }

  fclose($module_file);
  chmod($modulefileOut, 0775);
}




/***************************************
Session ID
Since we don't have a database, we have to update the txt file with the path information
 ***************************************/
$fsession = $ROOT_DIR . "Data/Pipeline/Resources/session/$sessionID" . "_session.txt";

if (file_exists($fsession)) {

  $session = explode("\n", file_get_contents($fsession));
  //Create different array elements based on new line
  $pipe_arr = preg_split("/[\t]/", $session[0]);
  $pipeline = $pipe_arr[1];

  if ($pipeline == "GWASskipped") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "2.5")) {
        return 'Mergeomics_Path:' . "\t" . "2.75" . "\n";
      }
      return $data;
    }
  } else if ($pipeline == "GWAS") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "3.5")) {
        return 'Mergeomics_Path:' . "\t" . "3.75" . "\n";
      }
      return $data;
    }
  } else if ($pipeline == "MSEA" || $pipeline == "META") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "2.5")) { //change from 3.5 --> 3.75
        return 'Mergeomics_Path:' . "\t" . "2.75" . "\n";
      }
      return $data;
    }
  } else if ($pipeline == "KDA") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.5")) { //change from 3.5 --> 3.75
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
//added conditional 10.09.2020
if ($failedon2ndrun=="T") { ?>
  <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
    <div class="alert alert-danger" style="margin: 0 auto; width: 50%;">
      <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i><strong>KDA failed on job rerun.</strong> Looks like you had a successful run with your initial parameters, but the job failed or produced no results on the next run. Your old results are retained here. Please check the runtime job log for the specific error.
    </div>
  </div>
  <br>
<?php }
?>

<?php
//added conditional 10.09.2020
if (!(file_exists($resultfile))) { ?>
  <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
    <div class="alert alert-danger" style="margin: 0 auto; width: 50%;">
      <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i><strong>No key drivers found.</strong> You may not have had many markers (i.e. genes) that matched those in the network. Consider using a more dense network or increase the number of markers/marker sets (i.e. gene sets) to query the network.
    </div>
  </div>
  <br>
<?php }
?>

<?php
//added conditional 10.09.2020
if (file_exists($resultfile) and !(file_exists($edges_file))) { ?>
  <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
    <div class="alert alert-danger" style="margin: 0 auto; width: 50%;">
      <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i><strong>No significant (FDR<0.05) key drivers found.</strong> Subnetwork Cytoscape files not generated. </div> </div> <br>
        <?php }
        ?>


        <table class="table table-bordered review" style="text-align: center" ; id="wKDAresultstable">
          <thead>
            <tr>
              <th colspan="3">
                Download KDA Output and Cytoscape Visualization Files
              </th>
            </tr>
            <!-- Not necessary
    <tr>
      <td>
        Hubs Structure File
      </td>
      <td>
        <a href=<?php //print($hubs_file); 
                ?> download> Download</a>
      </td>
    </tr>
    -->
            <!-- Not necessary
    <tr>
      <td>
        Key Drivers P Values File
      </td>
      <td>
        <a href=<?php //print($pvalues_file); 
                ?> download> Download</a>
      </td>
    </tr>
    -->
            <tr>
              <td>
                Key Drivers Results
              </td>
              <td>
                Lists for each key driver its P and FDR values, the module of which it is a key driver for, the total number of neighbors ('N.neigh'), the number of neighbors that are members of the module ('N.obsrv'), and the expected number of neighbors that are members of the module as calculated by permutation ('N.expct').
              </td>
              <td>
                <a href=<?php print($results_file); ?> download> Download</a>
              </td>
            </tr>
            <!-- Not necessary
    <tr>
      <td>
        Key Drivers Top Hits File
      </td>
      <td>
        <a href=<?php //print($tophits_file); 
                ?> download> Download</a>
      </td>
    </tr>
    -->
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
            <!-- Not necessary
    <tr>
      <td>
        Cytoscape Top Key Drivers
      </td>
      <td>
        <a href=<?php //print($topkds_file); 
                ?> download> Download</a>
      </td>
    </tr>
    -->
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
                <a href=<?php print($outfile); ?> download> Download</a>
              </td>
            </tr>
    <?php
    if ($NetConvert !== "none") { 
      if($NetConvert == "entrez"){
        $convertedfile = "Entrez to gene symbols converted network file";
      } else {
        $convertedfile = "Ensembl to gene symbols converted network file";
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
        Original network file with gene identifers converted to gene symbols. Gene identifiers not matching any gene symbols were removed.
      </td>
      <td>

        <a href=<?php print($netconvertedfile); ?> download> Download</a>
      </td>
    </tr>
    <?php }
    ?>
    <?php
    if ($GSETConvert !== "none") { 
      if($GSETConvert == "entrez"){
        $convertedfilegset = "Entrez to gene symbols converted nodes file";
      } else {
        $convertedfilegset = "Ensembl to gene symbols converted nodes file";
      }
      /*
      if($run=="F"){
        $genesetconverted = $genesetconverted[0];
      }
      */
    ?>
    <tr>
      <td>
        <?php echo $convertedfilegset; ?> 
      </td>
      <td>
        Original nodes file with gene identifers converted to gene symbols. Gene identifiers not matching any gene symbols were removed.
      </td>
      <td>
        <a href=<?php print($gsetconvertedfile); ?> download> Download</a>
      </td>
    </tr>
    <?php }
    ?>
          </thead>
        </table>

        <br>
        <br>

        <div style="text-align: center;">
          <input type="button" class="button button-3d button-small nomargin" value="Click to Download All Files in Zip Folder" onclick="window.open('kda_zip.php?My_ses=<?php print($sessionID); ?>','_self','resizable=yes')" />
        </div>

        <br>
        <br>
        <br>
        <div class="table-responsive">
          <table id="wKDA_module" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Merge Module ID</th>
                <th>Key Driver Node</th>
                <th>P-Value</th>
                <th>FDR</th>
                <th>Module Genes</th>
                <th>KD Subnetwork Genes</th>
                <th>Module and Subnetwork Overlap</th>
                <th>Fold Enrichment</th>
                <?php
                if ($num_cols == 12) {
                ?>
                  <th>Module Description</th>
                <?php
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              for ($i = 1; $i < (count($convert) - 1); $i++) {
                $convert_word = explode("\t", $convert[$i]);
                $p_val = ($convert_word[2] - 0);
                $fdr = ($convert_word[3] - 0);
                $fold = ($convert_word[10] - 0);
              ?>
                <tr>
                  <td> <?php print($convert_word[0]); ?> </td>
                  <td> <?php print($convert_word[1]); ?> </td>
                  <td> <?php printf("%.2e", $p_val); ?> </td>
                  <td> <?php printf("%.2e", $fdr); ?> </td>
                  <td> <?php print($convert_word[4]); ?> </td>
                  <td> <?php print($convert_word[5]); ?> </td>
                  <td> <?php print($convert_word[6]); ?> </td>
                  <td> <?php printf("%.2f", $fold); ?> </td>
                  <?php
                  if ($num_cols == 12) {
                  ?>
                    <td> <?php print($convert_word[11]); ?> </td>
                  <?php
                  }
                  ?>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>


        <?php
        // added conditional 10.9.2020
        if (file_exists($edges_file)) { ?>
          <input type="hidden" name='sessionID' value="<?php $send = "$sessionID";
                                                        print($send); ?>">
          <h4 class="instructiontext">To continue directly to overlap based drug repositioning (PharmOmics) using the wKDA subnetwork results click below:
            <br>
            <button type="button" class="button button-3d button-large pipeline" id="RunPharmOmics">Run PharmOmics Pipeline</button> </h4>

          <span style="text-align: center;">
            <form action="/cyto_visualize/write_cytoscape.php" name="figkda" target="_blank">
              <input type="hidden" name='sessionID' value="<?php print($sessionID); ?>">
              <input type="submit" class="button button-3d button-large" value="Display KDA Subnetwork" />
            </form>
          </span>
        <?php }
        ?>


        <?php
        // add network/gene overlap figure later
        if (!(file_exists($edges_file))) { ?>
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="alert alert-danger" style="margin: 0 auto; width: 50%;">
              No significant key drivers found. To view module gene overlap with network, if any, click below. This may take several minutes for large networks. Key drivers may be difficult to pinpoint if module genes are sparsely distributed and connected in the network.
            </div>
          </div>
          <span style="text-align: center;">
            <form action="/cyto_visualize/write_cytoscape_subnet.php" name="networkfig" target="_blank">
              <input type="hidden" name='sessionID' value="<?php print($sessionID); ?>">
              <input type="submit" class="button button-3d button-large" value="Display Network Graph" />
            </form>
          </span>
        <?php }

        ?>

        <?php
        // added conditional 10.9.2020
        if (!(file_exists($edges_file))) { ?>
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="alert alert-danger" style="margin: 0 auto; width: 50%;">
              <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i>Unable to continue to Pharmomics without significant results from KDA.
            </div>
          </div>
        <?php }
        ?>


        <?php

        $results_sent = "./Data/Pipeline/Results/kda_email/$sessionID" . "sent_results";
        $email = "./Data/Pipeline/Results/kda_email/$sessionID" . "email";
        if ((!(file_exists($results_sent)))) {
          if (file_exists($email)) {
            require('./PHPMailer-master/class.phpmailer.php');
            $hubs = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_hubs_structure.txt";
            $pvals = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_kd_pval.txt";
            $resultfile = $ROOT_DIR .  "Data/Pipeline/Results/kda/$sessionID.wKDA_kd_full_results.txt";
            $tophits = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_kd_tophits.txt";
            $overview_file = $ROOT_DIR . "Data/Pipeline/Results/kda/$sessionID.wKDA_file_parameter_selection.txt";

            $edges = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_edges.txt";
            $nodes = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_nodes.txt";
            $topkds = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_top_kds.txt";
            $colormapping = $ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_module_color_mapping.txt";

            $all_files = array();
            foreach (glob($ROOT_DIR . "Data/Pipeline/Results/himmeli/$sessionID*.svg") as $a_file) {
              $all_files[] = $a_file;
            }
            $emailid = $ROOT_DIR . "Data/Pipeline/Results/kda_email/$sessionID" . "email";
            $mail = new PHPMailer();

            $mail->Body = 'Congratulations! You have successfully executed our pipeline. Please download your results.';
            $mail->Body .= "\n";
            $mail->Body .= 'Your results are available at: http://mergeomics.research.idre.ucla.edu/result_wKDA.php?sessionID=';
            $mail->Body .= "$sessionID";
            $mail->Body .= "\n";
            $mail->Body .= 'Note: Your results will be deleted from the server after 24 hours';

            //$mail->IsSMTP(); // telling the class to use SMTP

            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
            $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
            $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
            $mail->Username   = "smha118@g.ucla.edu";  // GMAIL username
            $mail->Password   = "mergeomics729@";            // GMAIL password

            $mail->SetFrom('smha118@g.ucla.edu', 'Daniel Ha');

            $mail->Subject    = "Mergeomics wKDA Execution Complete!";

            $file_to_attach = "$resultfile";
            $file_to_attach2 = "$hubs";
            $file_to_attach3 = "$pvals";
            $file_to_attach4 = "$tophits";
            $file_to_attach6 = "$edges";
            $file_to_attach7 = "$nodes";
            $file_to_attach8 = "$topkds";
            $file_to_attach9 = "$colormapping";
            $file_to_attach10 = "$overview_file";
            $file_to_attach11 = "$outfile";

            $mail->addAttachment($file_to_attach, 'wKDA_kd_full_results.txt');
            //$mail->addAttachment($file_to_attach2, 'wKDA_hubs_structure.txt');
            //$mail->addAttachment($file_to_attach3, 'wKDA_kd_pval.txt');
            //$mail->addAttachment($file_to_attach4, 'wKDA_kd_tophits.txt');
            $mail->addAttachment($file_to_attach6, 'wKDA_cytoscape_edges.txt');
            $mail->addAttachment($file_to_attach7, 'wKDA_cytoscape_nodes.txt');
            //$mail->addAttachment($file_to_attach8, 'wKDA_cytoscape_top_kds.txt');
            $mail->addAttachment($file_to_attach9, 'wKDA_cytoscape_module_color_mapping.txt');
            $mail->addAttachment($file_to_attach10, 'wKDA_file_parameter_selection.txt');
            $mail->addAttachment($file_to_attach11, 'wKDA_runtime_joblog.txt');

            foreach ($all_files as $attach) {
              $mail->addAttachment($attach);
            }

            //$address = "dougvarneson@gmail.com";
            $address = trim(file_get_contents($emailid));
            $mail->AddAddress($address);

            if (!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
            } else {

              $myfile = fopen("./Data/Pipeline/Results/kda_email/$sessionID" . "sent_results", "w");
              fwrite($myfile, $address);
              fclose($myfile);
            }
          }
        }


        ?>

        <script src="include/js/bs-datatable.js"></script>
        <script type="text/javascript">
          var string = "<?php echo $sessionID; ?>";
          $('#wKDA_module').dataTable({
            "order": [
              [4, 'asc']
            ]
          });
        </script>

        <?php
        if ($rmchoice == 1) {
        ?>
          <script type="text/javascript">
            $('#RunPharmOmics').on('click', function() {
              $('#wKDAtogglet').click();
              $('#pharmOmicstoggle').show();
              $('#mypharmOmics').load('/kda2pharmomics_parameters.php?sessionID=' + string + "&rmchoice=1");

              $('#pharmOmicstogglet').click();
              return false;


            });
          </script>

        <?php

        } else if ($rmchoice == 2) {
        ?>

          <script type="text/javascript">
            $('#RunPharmOmics').on('click', function() {
              $('#MSEA2KDAtogglet').click();
              $('#KDA2PHARMtoggle').show();
              $('#myKDA2PHARM').load('/kda2pharmomics_parameters.php?sessionID=' + string + "&rmchoice=2");

              $('#KDA2PHARMtogglet').click();
              return false;


            });
          </script>


        <?php
        } else if ($rmchoice == 3) {
        ?>

          <script type="text/javascript">
            $('#RunPharmOmics').on('click', function() {
              $('#META2KDAtogglet').click();
              $('#METAKDA2PHARMtoggle').show();
              $('#myMETAKDA2PHARM').load("/kda2pharmomics_parameters.php?sessionID=<?php echo $sessionID ?>&rmchoice=3");
              $('#METAKDA2PHARMtogglet').click();
              return false;
            });
          </script>


        <?php
        } else {
        ?>

          <script type="text/javascript">
            $('#RunPharmOmics').on('click', function() {
              $('#KDASTARTtogglet').click();
              $('#KDASTART2PHARMtoggle').show();
              $('#myKDASTART2PHARM').load('/kda2pharmomics_parameters.php?sessionID=' + string + "&rmchoice=4");

              $('#KDASTART2PHARMtogglet').click();
              return false;


            });
          </script>


        <?php
        }

        ?>