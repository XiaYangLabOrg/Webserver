<?php
//This parameters files is for when the user chooses META - SSEA in mergeomics



/* Initialize PHP variables
sessionID = the saved session 

rmchoice = type of pipeline chouce

GET = if the user enters the link directly
POST = if PHP enters the link

*/


/*

META works by having 3 sessionIDs

1) The overall sessionID ($meta_sessionID) = sessionID that the user will use to return to their META session
    -This does not change
2) The sessionID of current session ($sessionID)
    -Everytime a user clicks "Add file", it will generate a new sessionID
3) The sessionID of old session ($sessionID)
    -This is the sessionID of the previous session (MSEA or SSEA)


There is a "list_strings" file that gets generated during each session
-This gets appended every time someone add another MSEA/SSEA file into META
-We then delete the old list_strings that does not contain the correct information
*/
//This function creates a random session ID string
function generatesessionIDing($length = 10)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $sessionIDing = '';
  for ($i = 0; $i < $length; $i++) {
    $sessionIDing .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $sessionIDing;
}

$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
$FILE_UPLOAD=$ROOT_DIR."Data/Pipeline/Resources/meta_temp/";
if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  $sessionID = $_POST['sessionID'];
} else {
  //generate sessionID if this a new session
  $sessionID = generatesessionIDing(10);
}


if (isset($_GET['oldsession'])) { //check if an old session exists 
  $old_meta_session = $_GET["oldsession"];
} else {
  $old_meta_session = null;
}

if (isset($_GET['metasessionID'])) {
  $meta_sessionID = $_GET["metasessionID"];
}

$session_list_file = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";
if (!file_exists($session_list_file)) {
  //then create the list_strings file with the current sessionID (first session)

  $fp = fopen($session_list_file, "w");
  $towrite = "$sessionID\n";
  fwrite($fp, $towrite);
  fclose($fp);
} else {
  $current = file_get_contents($session_list_file);
  $current .= "$sessionID\n";
  file_put_contents($session_list_file, $current);
}



//this deletes the old session file, so that this session will become the old session
//only if the old session file exist and the list_strings exist
$check_filestring = "./Data/Pipeline/Resources/meta_temp/$old_meta_session" . "list_strings";
$check_filemarker = "./Data/Pipeline/Resources/meta_temp/$old_meta_session" . "MARKER";
if (file_exists($check_filestring) && !file_exists($check_filemarker)) {
  unlink($check_filestring);
}

//variables to store path of files
$fpath = "./Data/Pipeline/Resources/meta_temp/$sessionID";
$fpathloci = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "ENRICHMENT";
$fpath_random = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "list_strings";


/* Initializes form data variables. Imo this is not very efficient, but this is what was here before. So I didn't change it.
There are definitely better ways to do this though...

 */
$data = (isset($_POST['formChoice_MSEA']) ? $_POST['formChoice_MSEA'] : null);
$data1 = (isset($_POST['formChoice_mapping']) ? $_POST['formChoice_mapping'] : null);
//$data2 = (isset($_POST['formChoice2_MSEA']) ? $_POST['formChoice2_MSEA'] : null);
//$data3 = (isset($_POST['formChoice3_MSEA']) ? $_POST['formChoice3_MSEA'] : null);



if (strlen($data??'') < 3) {
  $gwasformChoice = 0;
  $locformChoice = 0;
  $moduleformChoice = 0;
  $descformChoice = 0;
} else {
  $pieces = explode("|", $data);
  $gwasformChoice = (int)$pieces[0];
  $locformChoice = (int)$pieces[1];
  $moduleformChoice = (int)$pieces[2];
  $descformChoice = (int)$pieces[3];
  $sessionID = $pieces[4];
}

if (strlen($data1??'') < 3) {
  $gwasformChoice1 = 0;
  $locformChoice1 = 0;
  $moduleformChoice1 = 0;
  $descformChoice1 = 0;
} else {
  $pieces1 = explode("|", $data1);
  $gwasformChoice1 = (int)$pieces1[0];
  $locformChoice1 = (int)$pieces1[1];
  $moduleformChoice1 = (int)$pieces1[2];
  $descformChoice1 = (int)$pieces1[3];
  $sessionID = $pieces1[4];
}

/*
if (strlen($data2) < 3) {
  $gwasformChoice2 = 0;
  $locformChoice2 = 0;
  $moduleformChoice2 = 0;
  $descformChoice2 = 0;
} else {
  $pieces2 = explode("|", $data2);
  $gwasformChoice2 = (int)$pieces2[0];
  $locformChoice2 = (int)$pieces2[1];
  $moduleformChoice2 = (int)$pieces2[2];
  $descformChoice2 = (int)$pieces2[3];
  $sessionID = $pieces2[4];
}


if (strlen($data3) < 3) {
  $gwasformChoice3 = 0;
  $locformChoice3 = 0;
  $moduleformChoice3 = 0;
  $descformChoice3 = 0;
} else {
  $pieces3 = explode("|", $data3);
  $gwasformChoice3 = (int)$pieces3[0];
  $locformChoice3 = (int)$pieces3[1];
  $moduleformChoice3 = (int)$pieces3[2];
  $descformChoice3 = (int)$pieces3[3];
  $sessionID = $pieces3[4];
}
*/


$pv = "";


//create the parameter files when user submits form
/*
if (isset($_POST['permuttype']) ? $_POST['permuttype'] : null) {
  $fpathparam = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "PARAM";
  $pv = $_POST['permuttype'];
  $par = "job.msea\$permtype <- \"$pv\"\n";    //#permutation type, use "locus" for locus permutation
  $mx = $_POST['maxgene'];
  $par .= "job.msea\$maxgenes <- $mx\n";     //#max genes in geneset, default is 500
  $min = $_POST['mingene'];
  $par .= "job.msea\$mingenes <- $min\n";        // #min genes in geneset, default is 10
  $ovp = $_POST['overlap'];
  $g_ovp = $_POST['gene_overlap'];
  // $par.="job.msea\$maxoverlap <- $ovp\n";  //#max overlap ratio allowed for merging, (default is 0.2, use 1.0 to skip merging)
  $par .= "rmax <- $ovp\n";  //#max overlap ratio allowed for merging, (default is 0.2, use 1.0 to skip merging)
  $par .= "job.msea\$maxoverlap <- $g_ovp\n";
  $per = $_POST['permu'];
  $par .= "job.msea\$nperm <- $per\n";     //#number of permutations, default is 2000 
  $fp = fopen($fpathparam, "w");
  fwrite($fp, $par);
  fclose($fp);
  chmod($fpathparam, 0664);

  $mseafdr = $_POST['mseafdr'];

  $fpathparam = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "PARAM_SSEA_FDR";
  $par = "$mseafdr\n";  //MSEA FDR default is 25.0, use 25.0-0
  $fp = fopen($fpathparam, "w");
  fwrite($fp, $par);
  fclose($fp);
  chmod($fpathparam, 0664);


  $txt = "EWAS/TWAS/MWAS";
  $fpathloci = $fpath . "ENRICHMENT";
  $myfile = fopen($fpathloci, "w");
  fwrite($myfile, $txt);
  fclose($myfile);
  chmod($fpathloci, 0774);
}
*/


// $sessionID=$_POST["My_ses"];

?>

<div id="errormsg_MSEA" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_MSEA" style="white-space: pre;"></p>
</div>
</div>



<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">

  <div id="METAheader">
    <!-- Description ===================================================== -->
    <h4 style="color: #00004d; text-align: center; padding: 40px;font-size:25px;">
      This part of the pipeline is for merging multiple association studies <br> (GWAS, EWAS, TWAS, or MWAS) into a single Meta MSEA.
    </h4>
  </div>

  <!--Start MSEA Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_MSEA"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox' style="display: none;"></div>
  <!--End MSEA Tutorial --------------------------------------->


</div>
<!--End of grid container --->

<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="METAMSEA_parameters.php" name="select2" id="MSEAdataform">
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center" ; id="MSEAmaintable">
      <thead>
        <tr>
          <!--First row of table------------Column Headers------------------------------>
          <th>Type of File</th>
          <th class="uploadwidth">Upload/Select File</th>
          <th name="val_MSEA">Sample File Format</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!--Second row of table------------------------------------------>
          <td data-column="File type &#xa;">Marker Association File <br>

            <div class="informationtext" data-toggle="modal" data-target="#MAFinfomodal" href="#MAFinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>


          </td>
          <!--Second row|first column of table------------------------------------------>
          <td data-column="Upload/Select File &#xa;">
            <!--Second row|second column of table------------------------------------------>

            <!--Start MDF Upload ----------------------------->
            <div id="Selectupload" class="selectholder MSEA" align="center">
              <!--Start MDF Upload FORM---------------------------------->


              <select class="MSEA" name="formChoice_MSEA" size="1" id="marker_association">
                <option value="0">Please select option</option>
                <option value="private_data">Upload your association data</option>
                <option value="Resources/sample_EWAS/Sample_EWAS.txt">Sample Human EWAS</option>
                <option value="Resources/sample_TWAS/Sample_TWAS.txt">Sample Human TWAS</option>
                <option value="Resources/sample_PWAS/Sample_PWAS.txt">Sample Human PWAS</option>
                <option value="Resources/sample_EWAS/EWAS_GSE31835.txt">Psoriasis EWAS GSE31835</option>
                <option value="Resources/sample_EWAS/EWAS_GSE63315.txt">Psoriasis EWAS GSE63315</option>
                <option value="Resources/sample_EWAS/Birthweight_EWAS.txt">Birthweight EWAS</option>
                <option value="Resources/sample_EWAS/Maternal_Anxiety_EWAS.txt">Maternal Anxiety EWAS</option>
                <option value="Resources/sample_EWAS/Mental_Health_EWAS.txt">Mental Health EWAS</option>
              </select>


              <!--End MDF upload FORM------------------------------------->
              <br>

              <!-- Mapping Association File Upload div --->

            </div> <!-- End Select upload div---->

            <div id="MAFupload" style="display: none;">

              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

              <div class="input-file-container" name="Marker Association File" style="width: fit-content;">
                <input class="input-file" id="MAFuploadInput" name="MAFuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="MAFlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="MAFprogressbar" class="progress active" style='display: none;'>
                  <div id="MAFprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="MAFprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="MAFfilereturn" class="file-return"></p>
                <span id='MAF_uploaded_file'></span>
              </div>
               <table>
                <td style="vertical-align: middle">
                  Gene Identifier Conversion
                </td>
                <td>              
                <select class="btn dropdown-toggle btn-light" name="MAFConvert" size="1" id="MAFConvert" style="font-size: 18px;">
                  <option value="none" selected>None</option>
                  <option value="entrez">Entrez to gene symbol</option>
                  <option value="ensembl">Ensembl to gene symbol</option>
               </select>
            </td>
              </table>
            </div> <!-- End of upload div--->

            <div class="" id="alert_MAF" style="display:none;">
              <div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Success!</strong></div>
              <br>
              <div id="MMF_question" style="line-height:30px;">Would you like to use a mapping file to map your markers in your desired marker set?
                <a style="color:#5f5e58;" data-toggle="modal" data-target="#addMappingmodal" href="#addMappingmodal"><i class="icon-info-sign i-addmap"></i></a>
                <br>
                <button type="button" class="button button-3d button-small nomargin" id="MMF_Yes">Yes</button>
                <button type="button" class="button button-3d button-small nomargin" id="MMF_No">No</button>
              </div>
            </div>
            <!--Div to alert user of certain comment (i.e. success) -->




          </td>
          <td data-column="Sample Format &#xa;" name="val1">
            <!--Second row|third column of table------------------------------------------>
            <!--Start MDF Sample File Format -------------------->
            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="SNP, gene, protein, or metabolite">MARKER</a></th>
                    <th><a href="#" tooltip="Association strength which can be -log10(p-value), effect size, absolute value of the log fold change, etc. Larger values signify higher association strength.">VALUE</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="MARKER(Header): ">IDH2</td>
                    <td data-column="VALUE(Header): ">0.1452</td>

                  </tr>
                  <tr>
                    <td data-column="MARKER(Header): ">CALM3</td>
                    <td data-column="VALUE(Header): ">0.1108</td>

                  </tr>
                  <tr>
                    <td data-column="MARKER(Header): ">PTPRC</td>
                    <td data-column="VALUE(Header): ">1.3979</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that contains marker to trait associations. UTF-8/ASCII encoded files recommended. Sample files for all inputs can be found <a href="samplefiles.php">here</a>.</p>
            </div>





          </td>
          <!--End MDF Sample File Format -->
        </tr>

        <tr id="marker_mapping" style="display:none;">
          <!--Second row of table (show only if button is pressed)------------------------------------------>
          <td data-column="File type &#xa;">Marker Mapping File

            <div>
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>Gene-marker mapping file that links genomic markers to genes. For GWAS, the most commonly used mapping is based on genomic distance (e.g., 10 kb, 20 kb, 50 kb), which is provided on the web server. A data-driven function-based mapping is more preferred if available.
            </div>

          </td>
          <!--Third row|first column of table------------------------------------------>
          <td data-column="Upload/Select File &#xa;">
            <!--Third row|second column of table------------------------------------------>


            <!--Start MMF Upload ----------------------------->
            <div id="Selectupload2" class="selectholder MSEA" align="center">


              <!--Start MMF Upload FORM--------------------------------
                        <form enctype="multipart/form-data" action="LD_prune.php" name="select" id="Markerdataform2" > -->

              <select class="MSEA" name="formChoice_mapping" id="mapping_file" size="1">
                <option value="0">Please select option</option>
                <option value="private_data">Upload your mapping file</option>
                <option value="Resources/mappings/Sample_EWAS_Mapping.txt">Human EWAS Mapping</option>

              </select>
              <br>
              <!-- Marker Mapping File Upload div --->
            </div> <!-- End Selectupload2 div---->


            <div id="MMFupload" style="display: none;">

              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

              <div class="input-file-container" name="Marker Mapping File" style="width: fit-content;">
                <input class="input-file" id="MMFuploadInput" name="MMFuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="MMFlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i>Select a file... </label>
                <!--Progress bar ------------------------------>
                <div id="MMFprogressbar" class="progress active" style='display: none;'>
                  <div id="MMFprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="MMFprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="MMFfilereturn" class="file-return"></p>
                <span id='MMF_uploaded_file'></span>
              </div>
              <table>
                <td style="vertical-align: middle">
                  Gene Identifier Conversion
                </td>
                <td>              
                <select class="btn dropdown-toggle btn-light" name="MMFConvert" size="1" id="MMFConvert" style="font-size: 18px;">
                  <option value="none" selected>None</option>
                  <option value="entrez">Entrez to gene symbol</option>
                  <option value="ensembl">Ensembl to gene symbol</option>
               </select>
            </td>
              </table>
            </div> <!-- End of upload div--->

            <div class="alert" id="alertMMF" style="display:none;">
              <div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Success!</strong></div>
              <br>
              <div id="ask_MDF" style="line-height:30px;">Would you like to run Marker dependency filtering?
                This will take disease/phenotype association files and corrects for marker dependency.
                (optional)
                <a style="color:#5f5e58;" data-toggle="modal" data-target="#addMappingmodal" href="#addMappingmodal"></a>
                <br>
                <button type="button" class="button button-3d button-small nomargin" id="MDF_Yes">Yes</button>
                <button type="button" class="button button-3d button-small nomargin" id="MDF_No">No</button>
              </div>

            </div>
            <!--Div to alert user of certain comment (i.e. success) -->


          </td>
          <!--End of MMF Upload column -------------------->
          <td data-column="Sample Format &#xa;" name="val2_MMF">
            <!--Third row|Third column (Sample file format) -------------------->


            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#">GENE</a></th>
                    <th><a href="#" tooltip="SNP, gene, protein, or metabolite">MARKER</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="GENE(Header): ">CDK6</td>
                    <td data-column="MARKER(Header): ">rs10</td>

                  </tr>
                  <tr>
                    <td data-column="GENE(Header): ">AGER</td>
                    <td data-column="MARKER(Header): ">rs1000</td>

                  </tr>
                  <tr>
                    <td data-column="GENE(Header): ">N4BP2</td>
                    <td data-column="MARKER(Header): ">rs1000000</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that provides marker to gene mapping. UTF-8/ASCII encoded files recommended. </p>
            </div>





          </td>
          <!--End MMF Sample File Format -->
        </tr>
        <tr id="mdf_row" style="display:none;">
          <td data-column="File Type &#xa;">
            Marker Dependency File
            (i.e. HapMap3 LD File)
            <div>
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
              Marker Dependency File <br> (i.e. HapMap3 LD File)
              To better annotate the gene sets in MSEA output, a description file for the gene sets is needed to specify the source of the gene set and a detailed description of the functional information used to group genes.
              <br>The sample methylation disequilibrium file provided here was obtained from <a href="https://academic.oup.com/bioinformatics/article/34/15/2657/4939328" target="_blank">EWAS software 2.0</a>.
            </div>

          </td>
          <!--Fourth row|first column (Type of File)-------------------->
          <td data-column="Upload/Select File &#xa;">
            <!--Fourth row|second column (Upload/Select File) -------------------->
            <div class="selectholder MSEA" align="center">
              <select class="MSEA" name="formChoice3" size="1" id="mdf">
                <option value="0">Please select option</option>
                <option value="private_data">Upload your Correlation File</option>
                <option value="Resources/LD_files/md_example_50.txt">Example MD for EWAS</option>
              </select>
              <br>
            </div>
            <!--End of selectupload3 div --->

            <!-- Marker Dependency File Upload div --->
            <div id="MDFupload" style="display: none;">

              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

              <div class="input-file-container" name="Marker Dependency File" style="width: fit-content;">
                <input class="input-file" id="MDFuploadInput" name="MDFuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="MDFlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="MDFprogressbar" class="progress active" style='display: none;'>
                  <div id="MDFprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="MDFprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="MDFfilereturn" class="file-return"></p>
                <span id='MDF_uploaded_file'></span>
              </div>
            </div> <!-- End of upload div--->
            <div id="PercentageMDF">
              <div class="datagrid">
                <p class="nobottommargin">Percentage of Markers:</p>
                <input type="text" id="percent_markers" name="percentage" value="50">
                <p>Between 1-100. Default is 50.</p>
              </div>
            </div>
            <div class="alert_MDF" id="alert_MDF" style="display:none;">
              <div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Success!</strong></div>
            </div>

            <!--Div to alert user of certain comment (i.e. success) -->

          </td> <!-- End of MDF upload row -------------->
          <td data-column="Sample Format &#xa;" name="val3">
            <!--Fourth row|Third column (Marker Dependency Sample File Format)---->

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="SNP, gene, protein, or metabolite">MARKERa</a></th>
                    <th><a href="#" tooltip="SNP, gene, protein, or metabolite">MARKERb</a></th>
                    <th><a href="#" tooltip="Correlation value between MARKERa and MARKERb &#013; (0-100)">WEIGHT</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="Markera:">rs12565</td>
                    <td data-column="Markerb:">rs29776</td>
                    <td data-column="Weight:">0.611</td>

                  </tr>
                  <tr>
                    <td data-column="Markera:">rs11804</td>
                    <td data-column="Markerb:">rs29776</td>
                    <td data-column="Weight:">1</td>

                  </tr>
                  <tr>
                    <td data-column="Markera:">rs12138</td>
                    <td data-column="Markerb:">rs12562</td>
                    <td data-column="Weight:">0.575</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that provides the two markers and their correlation. UTF-8/ASCII encoded files recommended. </p>
            </div>
          </td>
        </tr>

      </tbody>
    </table>
    <!--End of MSEA maintable -->
  </div>
  <!--End of responsive div for MSEA maintable --->

  <!-------------------------------------------------Start of MSEA Parameters table ----------------------------------------------------->
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center;" id="MSEAparameterstable">
      <thead>
        <tr>
          <th colspan='3' style="border-bottom: aliceblue;">Parameters for MSEA</th>
        </tr>
        <tr>
          <th>Parameter type</th>
          <th name="val">Input</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Permutation type:</td>

          <td name="val1">
            <select class="btn dropdown-toggle btn-light" name="permuttype" id="permuttype" size="1">
              <option value="marker" selected>Marker</option>
              <option value="gene" disabled>Gene</option>
            </select>
          </td>



        </tr>

        <tr name="Max Overlap for Merging Gene Mapping">

          <td>Max Overlap for Merging Gene Mapping:</td>

          <td name="val4"> <input class='MSEAparameter' id="maxoverlap" type="text" name="gene_overlap" id="maxoverlap" value="1" readonly="readonly">
          </td>


        </tr>

        <tr name="Number of Permutations">

          <td>Number of Permutations:</td>

          <td name="val6"><input class='MSEAparameter' type="text" name="permu" id="mseanperm" value="2000"></td>

        </tr>

        <tr name="MSEA FDR cutoff">

          <td>
            MSEA to KDA export FDR cutoff:
            <div class="alert alert-warning" style="margin: 0 auto; width: 90%;margin-top: 10px;">
              <i class="icon-warning-sign" style="margin-right: 6px;font-size: 12px;"></i>This is the cutoff (in percentage) used to export meta results (significant modules) to KDA <b>for this specific study</b>. The module will also need to pass the selected meta FDR on the 'Review Files' tab to be included in the KDA analysis (to be included in the KDA analysis, the module has to pass both this FDR cutoff value and the meta FDR which is set on the 'Review Files' tab).
            </div>
          </td>

          <td name="val7"><input class='MSEAparameter' type="text" name="mseafdr" id="mseafdr" value="50"></td>


        </tr>

      </tbody>
    </table>
  </div>
  <!--End of responsive div for parameters table -->
  <br>
  <!-------------------------------------------------End of MSEA Parameters table ----------------------------------------------------->
  <!-------------------------------------------------Start Review button ----------------------------------------------------->
  <div id="Validatediv_MSEA" style="text-align: center;">
    <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_MSEA">Finish and Review</button>
    <div style="text-align: center;" id="preload"></div>

</form>
<!--End of MSEA form (This combines the two inputs together) ---->
</div>
<!-------------------------------------------------End Review button ----------------------------------------------------->



<?php
/*
if (isset($_POST['permuttype']) ? $_POST['permuttype'] : null) {
  $txt = "EWAS/TWAS/MWAS";
  $fpathloci = $fpath . "ENRICHMENT";
  $myfile = fopen($fpathloci, "w");
  fwrite($myfile, $txt);
  fclose($myfile);
  $ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
  $fpathloci = $fpath . "MAPPING";
  if (!file_exists($fpathloci)) //if a user mapping file does not exist, then create a fake one, otherwise do nothing
  {
    $txt = "Resources/meta_temp/$sessionID" . "genfile_for_geneEnrichment.txt";
    $myfile = fopen($fpathloci, "w");
    fwrite($myfile, $txt);
    fclose($myfile);

    $fmappingOut = "./Data/Pipeline/$sessionID" . "enrichment.R";
    $fpath1 = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "MARKER";
    $enrichment = trim(file_get_contents($fpath1));
    $associationfile = $ROOT_DIR . "/Data/Pipeline/" . $enrichment; //Association file
    $mappingfile = $ROOT_DIR . "/Data/Pipeline/Resources/meta_temp/" . $sessionID . "genfile_for_geneEnrichment.txt"; //fake mapping file
    $source = 'source("' + $ROOT_DIR . 'R_Scripts/cle.r")';
    $add = 'marker_associations <- read.delim("' . $associationfile . '", stringsAsFactors = FALSE)';
    $add2 = 'genfile = data.frame("GENE"=marker_associations$MARKER, "MARKER" = marker_associations$MARKER, stringsAsFactors = FALSE)';
    $add3 = 'write.table(genfile, ' + $ROOT_DIR . '"Data/Pipeline/Resources/meta_temp/' . $sessionID . 'genfile_for_geneEnrichment.txt", row.names = FALSE, quote = FALSE, sep = "\t")';
    $add4 = 'system("chmod +x ' + $ROOT_DIR . 'Data/Pipeline/Resources/meta_temp/' . $sessionID . 'genfile_for_geneEnrichment.txt")';
    $fp = fopen($fmappingOut, "w");
    fwrite($fp, $source . "\n" . $add . "\n" . $add2 . "\n" . $add3 . "\n" . $add4 . "\n");
    fclose($fp);
    chmod($fmappingOut, 0755);
    shell_exec('./run_meta_enrichment.sh ' . $sessionID);
  }
}
*/

?>

<!---------------------------------------Modal information for addMapping -------------------------------------------------------->
<div id="addMappingmodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 45px;">Mapping Files</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="style-msg infomsg" style="margin: 0 auto; width: 80%;padding:30px;font-size: 18px;"> Mapping files can be used to map your markers to markers of the chosen marker set. <br>
              For example, to enrich EWAS data for gene sets, CpG sites have to be mapped to genes. <br>
              The mapping file would have the CpG sites in the ‘MARKER’ column and genes in the ‘GENE’ column.
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-------------------End modal -------------------------->

<!---------------------------------------Modal information for MDF info -------------------------------------------------------->
<div id="MAFinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 35px;">Marker Association File</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;"> Marker-disease association summary results including Marker IDs and –log10 transformed association p-values. <br><br> Make sure your uploaded markers match the markers of the chosen marker sets. <br><br> Currently we have a sample EWAS mapping file that maps cgIDs to genes which can be used if you would like to enrich your EWAS data for gene sets. <br><br> We are in the process of making metabolite-gene mappings.
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-------------------End modal -------------------------->

<!---------------------------------------Modal information for MMF info -------------------------------------------------------->
<div id="MMFinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 35px;">Marker Mapping Files</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;"> Gene-marker mapping file that links genomic markers to genes. For GWAS, the most commonly used mapping is based on genomic distance (e.g., 10 kb, 20 kb, 50 kb), which is provided on the web server. A data-driven function-based mapping is more preferred if available.
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-------------------End modal -------------------------->

<!---------------------------------------Modal information for GSET -------------------------------------------------------->
<div id="GSETinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 35px;">Marker Sets File</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;"> Functionally related gene sets such as co-regulation, shared response, co-localization on chromosomes, or participants of specific biological processes. Typical sources of gene sets includes canonical pathways such as Reactome and KEGG, or coexpression modules constructed using algorithms like weighted coexpression gene networks analysis (WGCNA).
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-------------------End modal -------------------------->

<!---------------------------------------Modal information for GSET -------------------------------------------------------->
<div id="GSETDinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 35px;">Marker Sets Description File</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;"> To better annotate the gene sets in MSEA output, a description file for the gene sets is needed to specify the source of the gene set and a detailed description of the functional information used to group genes.
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-------------------End modal -------------------------->

<script type="text/javascript">
  var session_id = "<?php echo $sessionID; ?>";
  var meta_session_id = "<?php echo $meta_sessionID; ?>";
  var marker_association_file = null;
  var mapping_file = [];
  var permtype = null;
  var maxoverlap = null;
  var mseanperm = null;
  var mseafdr = null;
  var mdffile = null;
  var mdf_ntop = null;
  var MAFConvert = null;
  var MMFConvert = null;
  var file_upload_target_path="<?php echo $FILE_UPLOAD;?>";
  function MSEAdone() //This function gets the review table for MSEA
  {
    $.ajax({
      url: "META_moduleprogress.php",
      method: "GET",
      data: {
        sessionID: session_id,
        metasessionID: meta_session_id,
        marker_association: marker_association_file,
        mapping: mapping_file,
        perm_type: permtype,
        maxoverlap: maxoverlap,
        sseanperm: mseanperm,
        sseafdr: mseafdr,
        MAFConvert: MAFConvert,
        MMFConvert: MMFConvert,
        enrichment: "EWAS/TWAS/PWAS/MWAS",
        mdf: mdffile,
        mdf_ntop: mdf_ntop,
      },
      success: function(data) {
        $('#myMETA').html(data);
      }
    });
    $("#METAtogglet").html("<i class='toggle-closed icon-remove-circle'></i><i class='toggle-open icon-remove-circle'></i><div class='capital'>Step 1: Meta-MSEA</div>");
    $('#METAtab1').html("Review Files");
  }


  ///////////////Start Submit Function (SSEA form) -- Function for clicking 'Click to review button'///////////////////////////////////

  $('#MSEAdataform').submit(function(e) {

    e.preventDefault();
    var form_data = new FormData(document.getElementById('MSEAdataform'));
    form_data.append("sessionID", string);

    $.ajax({
      'url': 'METAMSEA_parameters.php',
      'type': 'POST',
      'data': form_data,
      processData: false,
      contentType: false,
      'success': function(data) {

      }
    });


  });
  /////////////////////////////////////////////End submit function for SSEA form//////////////////////////////////////////////////////
  var function_for_display_animation = function() {
    $("#preload").html(`<img src='include/pictures/ajax-loader.gif' />`);
  }


  ///////////////Start Validation/REVIEW button -- Function for clicking 'Click to review button'///////////////////////////////////
  $("#Validatebutton_MSEA").on('click', function() {

    if ($(".yes_map")[0]) {
      // Do something if yes_map class exists
      var select = $("select[name='formChoice_MSEA'] option:selected").index(),
        select_map = $("select[name='formChoice_mapping'] option:selected").index();
      //select1 = $("select[name='formChoice2_MSEA'] option:selected").index(),
      //select2 = $("select[name='formChoice3_MSEA'] option:selected").index();


      //var selectarray = [select, select_map, select1, select2];
      var selectarray = [select, select_map];
      var idarray = ['MAFuploadInput', 'MMFuploadInput'];



    } else if ($(".yes_map")[1]) {
      var select = $("select[name='formChoice_MSEA'] option:selected").index(),
        select_map = $("select[name='formChoice_mapping'] option:selected").index(),
        select_mdf = $("select[name='formChoice_mdf'] option:selected").index();
      //select1 = $("select[name='formChoice2_MSEA'] option:selected").index(),
      //select2 = $("select[name='formChoice3_MSEA'] option:selected").index();

      //initialize arrays to check against formchoice w/ mapping file input
      //var selectarray = [select, select_map, select_mdf, select1, select2];
      var selectarray = [select, select_map, select_mdf];
      var selectarray = [select, select_map, select_mdf];
      var idarray = ['MAFuploadInput', 'MMFuploadInput', 'MDFuploadInput'];
    } else {
      // Do something if yes_map class does not exist
      var select = $("select[name='formChoice_MSEA'] option:selected").index();
      //select1 = $("select[name='formChoice2_MSEA'] option:selected").index(),
      //select2 = $("select[name='formChoice3_MSEA'] option:selected").index();


      //var selectarray = [select, select1, select2];
      var selectarray = [select];
      var idarray = ['MAFuploadInput'];

    }

    var errorlist = [];
    selectarray.forEach(myFunction);

    function myFunction(item, index) {

      if (item === 0) {
        errorlist.push($('#' + idarray[index].toString()).parent().attr('name') + ' is not selected!');
      } else if (item === 1) {
        if ($('#' + idarray[index].toString()).nextAll('.file-return').eq(0).text() == '') {
          errorlist.push($('#' + idarray[index].toString()).parent().attr('name') + ' is not selected!');
        }
      } else {
        //do nothing
      }

    }

    $('.MSEAparameter').each(function() {
      if ($(this).val() == "") {
        errorlist.push($(this).parent().parent().attr('name') + ' is empty!');

      }
    });

    if ($('#MMF_question').length == 0) {
      //if the question doesn't exist, no check
    } else if ($('#MMF_question').is(':empty')) {
      //if the question does exist and it's empty, no check

    } else {
      //if the question exists and it's not empty, hard check
      //errorlist.push("Please select Yes/No to include your mapping file!")
    }

    if (errorlist.length === 0) {
      $(this).html('Please wait ...')
        .attr('disabled', 'disabled');
      if ($(".yes_map")[0]) {
        //do nothing
      } else {
        //remove the mapping form
        //$("#marker_mapping").remove();
      }
      //$("#MSEAdataform").submit();
      function_for_display_animation();

      setTimeout(function() {
        $('#myMETA_body').empty();

        MAFConvert = $("#MAFConvert").val();
        MMFConvert = $("#MMFConvert").val();

        if ($("#marker_association").val() != "private_data") {
          marker_association_file = $("#marker_association").val();
          MAFConvert = "none";
        }
        if ($("#mapping_file").val() == 0) {
          mapping_file.push("None Provided");
        } else if ($("#mapping_file").val() != "private_data") {
          mapping_file.push($("#mapping_file").val());
          MMFConvert = "none";
        }

        /*
        if ($("#module").val() != "private_data") {
          module_set_file = $("#module").val();
        }
        if ($("#module_info").val() != "private_data") {
          module_info_file = $("#module_info").val();
        }
        */
        permtype = $("#permuttype").val();
        //maxgene = $("#maxgene").val();
        //mingene = $("#mingene").val();
        maxoverlap = $("#maxoverlap").val();
        //minoverlap = $("#minoverlap").val();
        mseanperm = $("#mseanperm").val();
        mseafdr = $("#mseafdr").val();
        mdf_ntop = $("#percent_markers").val();

        MSEAdone();

      }, 500);
    } else {
      var result = errorlist.join("\n");
      //alert(result);
      $('#errorp_MSEA').html(result);
      $("#errormsg_MSEA").fadeTo(2000, 500).slideUp(500, function() {
        $("#errormsg_MSEA").slideUp(500);
      });
    }
  });



  //Function to filter percentage (no decimals and number between 1-100)

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

  /*
  $("#minoverlap, #maxoverlap").inputFilter(function(value) {
    return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });
  */

  $("#maxoverlap").inputFilter(function(value) {
    return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });

  $("#mseafdr").inputFilter(function(value) {
    return /^\d*$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 100));
  });





  ///////////////////////////////////////////////End Validation/REVIEW button/////////////////////////////////////////////////////////////

  // set up select boxes
  $('.selectholder.MSEA').each(function() {
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
        $('.activeselectholder.MSEA').each(function() {
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
  $('.selectholder.MSEA .selectdropdown span').click(function() {

    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    var value = $(this).text();
    $(this).parent().siblings('select').val(value);
    $(this).parent().siblings('.desc').fadeOut(100, function() {
      $(this).text(value);
      $(this).fadeIn(100);
    });
    $(this).parent().siblings('select').children('option:contains("' + value + '")').prop('selected', 'selected');

    $(this).parent().siblings('select').trigger("change");


  });


  $(document).mouseup(function(e) {
    var container = $(".selectholder.MSEA");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      $('.activeselectholder.MSEA').each(function() {
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
    }
  });

  ///////////////////////////////////////////////Start Tutorial Button script'///////////////////////////////////

  var myTutButton_MSEA = document.getElementById("myTutButton_MSEA");
  var val_MSEA = 0;

  //begin function for when button is clicked-------------------------------------------------------------->
  myTutButton_MSEA.addEventListener("click", function() {

    //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
    var $this_MSEA = $(this);

    //If tutorial is already opened yet, then do this-------------------------------------------------------------->
    if ($this_MSEA.data('clicked')) {


      $('.tutorialbox').hide();

      $('#MSEAparameterstable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });


      $this_MSEA.data('clicked', false);
      val_MSEA = val_MSEA - 1;
      $("#myTutButton_MSEA").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

    }

    //If tutorial is not opened yet, then do this-------------------------------------------------------------->
    else {
      $this_MSEA.data('clicked', true);
      val_MSEA = val_MSEA + 1; //val counter to not duplicate prepend function


      if (val_MSEA == 1) //Only prepend the tutorial once
      {


        $('#MSEAparameterstable').find('td[name="val1"]').eq(-1).after(`
                                <td name="tut">
                                <strong>Permutation type</strong>: Marker-based permutation to estimate statistical significance p-values. <br>
                                    <strong>Default value</strong>: "Marker" is the only option for MSEA user.
                                 </td>
                                `);

        $('#MSEAparameterstable').find('td[name="val4"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Max Overlap in merging Gene Mapping</strong>: 1:1 mapping, e.g. RNAseq<br>
                                    <strong>Default Value</strong>: MSEA user can only select "1" to skip merging
                                </td>`);


        $('#MSEAparameterstable').find('td[name="val6"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Number of Permutations</strong>: the number of gene or marker permutations conudcted in the MSEA analysis <br>
                                   <strong>Options</strong>: 1000 to 20,000 (for publication, recommend >= 10,000) <br>
                                    <strong>Default value</strong>: 2000
                                </td>`);

        $('#MSEAparameterstable').find('td[name="val7"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>MSEA FDR cutoff</strong>: FDR should be within the specified FDR cutoff. <br>
                                    <strong>Options</strong>: Between 0 to 100 (100 is 100%) <br>
                                    <strong>Default value</strong>: 50
                                </td>`);

        $('#MSEAparameterstable').find('th[name="val"]').eq(-1).after('<th name="tut">Comments</th>');

        $('.tutorialbox').show();
        $('.tutorialbox').html('The purpose of the pipeline is to take an association dataset for a given disease or phenotype from the user as input and integrate the association data with functional genomics information, pathways, and gene networks to derive pathways, gene networks and key regulatory genes for the disease or phenotype.');





      }
      $("#myTutButton_MSEA").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
    }



  });

  ///////////////////////////////////////////////Start upload/alert functions -- functions for when you select an input file (sample or uploaded)/////////////////////////////////////////////////////////////

  var successalert = '<div class="alert alert-success" style="margin: 20px 10px 0 10px;"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;margin:0px;"></i><strong>Success!</strong> </div>';
  var uploadalert = `<div class="alert alert-warning">
                                                    <div class="sb-msg">
                                                        <i class="icon-warning-sign"></i> 
                                                        <strong>Maximum File Size:</strong> 400Mb</div>
                                                    <div class="sb-msg">
                                                        <i class="icon-warning-sign"></i>    
                                                        <strong>Accepted file type:</strong> *.txt</div>
                                                    </div>`;



  ///////////////////////////////////////////////First input form (Marker Assocication file) function/////////////////////////////////////////////////////////////

  $('select.MSEA').on('change', function() {
    var select = $(this).find('option:selected').index();
    if (select != 1)
      $(this).parent().next().hide();

    if (select == 1)
      $(this).parent().next().show();

    if (select > 1)
      $(this).parent().nextAll(".alert-MSEA").eq(0).html(successalert).hide().fadeIn(300);
    else if (select == 1)
      $(this).parent().nextAll(".alert-MSEA").eq(0).html(uploadalert).hide().fadeIn(300);
    else
      $(this).parent().nextAll(".alert-MSEA").eq(0).empty();
  });

  $('#marker_association').on('change', function() {
    var select = $(this).find('option:selected').index();
    if (select == 5 || select == 6 || select == 7 || select == 8 || select == 9) {
      $('#alert_MAF').show();
    } else if(select == 2){
      $("#MMF_Yes").css({
      'background-color': 'internal-light-dark( rgb(74, 74, 74));',
      'border': 'rgb(74, 74, 74);',
      'box-shadow': 'inset 0 1px 0 rgb(74, 74, 74), inset 0 -1px 0 rgb(74, 74, 74), inset 0 0 0 1px rgb(74, 74, 74), 0 2px 4px rgba(0, 0, 0, 0.2)'
      });
      $("#MMF_No").css({
        'background-color': '',
        'border': '',
        'box-shadow': ''
      });
      $('#marker_mapping').addClass("yes_map");
      $('#marker_mapping').fadeIn('slow');
    }else {
      $('#alert_MAF').hide();
    }
  });
  $('#mdf').on('change', function() {

    var select = $(this).find('option:selected').index();
    if (select > 1) {
      $('#alert_MDF').show();
    } else {
      $('#alert_MDF').hide();
    }
  });


  $('select.MSEA').each(function() {
    $(this).trigger('change');
  });

  // $("#MMF_Yes").on('click', function() {
  //   $('#marker_mapping').addClass("yes_map");
  //   $('#marker_mapping').fadeIn('slow');
  //   $('#MMF_question').empty();
  // });

  // $("#MMF_No").on('click', function() {
  //   if ($(".yes_map")[0]) {
  //     $('#marker_mapping').removeClass("yes_map");
  //     $('#marker_mapping').fadeOut('slow');
  //   }
  //   $('#MMF_question').empty();
  // });
  $("#MMF_Yes").on("click", function() {
    $("#MMF_Yes").css({
      'background-color': 'internal-light-dark( rgb(74, 74, 74));',
      'border': 'rgb(74, 74, 74);',
      'box-shadow': 'inset 0 1px 0 rgb(74, 74, 74), inset 0 -1px 0 rgb(74, 74, 74), inset 0 0 0 1px rgb(74, 74, 74), 0 2px 4px rgba(0, 0, 0, 0.2)'
    });
    $("#MMF_No").css({
      'background-color': '',
      'border': '',
      'box-shadow': ''
    });
    $('#marker_mapping').addClass("yes_map");
    $('#marker_mapping').fadeIn('slow');

  });
  $("#MMF_No").on("click", function() {
    $("#MDF_No").click();
    $("#MMF_No").css({
      'background-color': 'internal-light-dark( rgb(74, 74, 74));',
      'border': 'rgb(74, 74, 74);',
      'box-shadow': 'inset 0 1px 0 rgb(74, 74, 74), inset 0 -1px 0 rgb(74, 74, 74), inset 0 0 0 1px rgb(74, 74, 74), 0 2px 4px rgba(0, 0, 0, 0.2)'
    });
    $("#MMF_Yes").css({
      'background-color': '',
      'border': '',
      'box-shadow': ''
    });
    $('#marker_mapping').removeClass("yes_map");
    $('#marker_mapping').fadeOut('slow');
    //set mdf select val to 0
    $("#mapping_file").val("0");
  });

  $("#Selectupload2").on("change", function() {
    var select = $(this).find('option:selected').index();
    if (select > 1) {
      $("#alertMMF").show();
    } else {
      $("#alertMMF").hide();
    }
  });
  $("#MDF_Yes").on("click", function() {
    $("#MDF_Yes").css({
      'background-color': 'internal-light-dark( rgb(74, 74, 74));',
      'border': 'rgb(74, 74, 74);',
      'box-shadow': 'inset 0 1px 0 rgb(74, 74, 74), inset 0 -1px 0 rgb(74, 74, 74), inset 0 0 0 1px rgb(74, 74, 74), 0 2px 4px rgba(0, 0, 0, 0.2)'
    });
    $("#MDF_No").css({
      'background-color': '',
      'border': '',
      'box-shadow': ''
    });
    $('#mdf_row').fadeIn('slow');

  });
  $("#MDF_No").on("click", function() {
    $("#MDF_No").css({
      'background-color': 'internal-light-dark( rgb(74, 74, 74));',
      'border': 'rgb(74, 74, 74);',
      'box-shadow': 'inset 0 1px 0 rgb(74, 74, 74), inset 0 -1px 0 rgb(74, 74, 74), inset 0 0 0 1px rgb(74, 74, 74), 0 2px 4px rgba(0, 0, 0, 0.2)'
    });
    $("#MDF_Yes").css({
      'background-color': '',
      'border': '',
      'box-shadow': ''
    });
    $('#mdf_row').fadeOut('slow');
    //set mdf select val to 0
    $("#mdf").val("0");
  });



  $("#MAFuploadInput").on('change', function() {
    $("#MAFlabelname").html("Select another file?");
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#MAFskippeduploadInput"); //get the id
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", file_upload_target_path);
      fd.append("data_type", "marker_association");
      fd.append("session_id", session_id);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload_MAF2.php', true);

      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          $('#MAFprogressbar').show();
          var percentComplete = (e.loaded / e.total) * 100;
          $('#MAFprogresswidth').width(percentComplete.toFixed(2) + '%');
          $('#MAFprogresspercent').html(percentComplete.toFixed(2) + '%');
        }
      };

      xhr.onload = function() {
        if (this.status == 200) {
          var resp = JSON.parse(this.response);
          $('#MAFprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#MAFprogressbar').hide();
          if (resp.status == 1) {
            var fullPath = resp.targetPath;
            $('#alert_MAF').show();
            marker_association_file = fullPath.replace("/var/www/mergeomics/html/./Data/Pipeline/", "");
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#MAFfilereturn').html(filename);
            //$('#MAF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
          } else {
            $('#MAF_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong>' + resp.msg + '</div>');
            var control = $("#MAFskippeduploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#MAFfilereturn").empty();
          }
        };
      };
      xhr.send(fd);
    }
  });
  $("#MAFlabelname").on("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      $("#MAFskippeduploadInput").focus();
    }
  });
  $("#MAFlabelname").on("click", function(event) {
    $("#MAFskippeduploadInput").focus();
    return false;
  });

  $("#MMFuploadInput").on('change', function() {
    $("#MMFlabelname").html("Select another file?");
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#MMFskippeduploadInput"); //get the id
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", file_upload_target_path);
      fd.append("data_type", "mapping");
      fd.append("session_id", session_id);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload_MAF2.php', true);
      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          $('#MMFprogressbar').show();
          var percentComplete = (e.loaded / e.total) * 100;
          $('#MMFprogresswidth').width(percentComplete.toFixed(2) + '%');
          $('#MMFprogresspercent').html(percentComplete.toFixed(2) + '%');
        }
      };

      xhr.onload = function() {
        if (this.status == 200) {
          var resp = JSON.parse(this.response);
          $('#MMFprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#MMFprogressbar').hide();
          if (resp.status == 1) {
            var fullPath = resp.targetPath;
            mapping_file.push(fullPath.replace("/var/www/mergeomics/html/./Data/Pipeline/", ""));
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#MMFfilereturn').html(filename);
            $('#MMF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
          } else {
            $('#MMF_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong>' + resp.msg + '</div>');
            var control = $("#MAFskippeduploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#MMFfilereturn").empty();
          }
        };
      };
      xhr.send(fd);
    }
  });
  $("#MMFlabelname").on("keydown", function(evetnt) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      $("#MMFskippeduploadInput").focus();
    }
  });
  $("#MMFlabelname").on("click", function(evetnt) {
    $("#MMFskippeduploadInput").focus();
    return false;
  });
  $("#MDFuploadInput").on("change", function() {
    $("#MDFlabelname").html("Select another file?")
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#MDFuploadInput"); //get the id
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", file_upload_target_path);
      fd.append("data_type", "mdf");
      fd.append("session_id", session_id);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload_MAF2.php', true);
      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          $('#MDFprogressbar').show();
          var percentComplete = (e.loaded / e.total) * 100;
          $('#MDFprogresswidth').width(percentComplete.toFixed(2) + '%');
          $('#MDFprogresspercent').html(percentComplete.toFixed(2) + '%');
        }
      };

      xhr.onload = function() {
        if (this.status == 200) {
          var resp = JSON.parse(this.response);
          $('#MDFprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#MDFprogressbar').hide();
          if (resp.status == 1) {
            var fullPath = resp.targetPath;
            mdffile = fullPath.replace("/var/www/mergeomics/html/./Data/Pipeline/", "");
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#MDFfilereturn').html(filename);
            $('#MDF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
          } else {
            $('#MDF_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong><br>' + resp.msg + '</div>');
            $("#MDFfilereturn").empty();
          }
        };
      };
      xhr.send(fd);
    }
  });

  $("#MDFlabelname").on("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      $("#MDFuploadInput").focus();
    }
  });
  $("#MDFlabelname").on("click", function(event) {
    $("#MDFuploadInput").focus();
  });

  

  /*
  $("#GSETuploadInput").on('change', function() {
    $("#GSETlabelname").html("Select another file?");
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#GSETuploadInput"); //get the id
      control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", "./Data/Pipeline/Resources/meta_temp/");
      fd.append("data_type", "gene_set");
      fd.append("session_id", session_id);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload_MAF2.php', true);
      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          $('#GSETprogressbar').show();
          var percentComplete = (e.loaded / e.total) * 100;
          $('#GSETprogresswidth').width(percentComplete.toFixed(2) + '%');
          $('#GSETprogresspercent').html(percentComplete.toFixed(2) + '%');
        }
      };

      xhr.onload = function() {
        if (this.status == 200) {
          var resp = JSON.parse(this.response);
          $('#GSETprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#GSETprogressbar').hide();
          if (resp.status == 1) {
            var fullPath = resp.targetPath;
            console.log("Fullpath:"+fullPath);
            module_set_file = fullPath.replace("/var/www/mergeomics/html/./Data/Pipeline/","");
            console.log("Fullpath:"+module_set_file);
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#GSETfilereturn').html(filename);
            $('#GSET_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
          } else {
            $('#GSET_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong><br>' + resp.msg + '</div>');
            // var control = $("#GSETuploadInput"); //get the id
            // control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#GSETfilereturn").empty();
          }
        };
      };
      xhr.send(fd);
    }


  });
  $("#GSETlabelname").on("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      $("#GSETuploadInput").focus();
    }
  });
  $("#GSETlabelname").on("click", function(event) {
    $("#GSETuploadInput").focus();
    return false;
  });


  $("#GSETDuploadInput").on('change', function() {
    $("#GSETDlabelname").html("Select another file?")
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#GSETDuploadInput"); //get the id
      control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", "./Data/Pipeline/Resources/meta_temp/");
      fd.append("data_type", "gene_set_desc");
      fd.append("session_id", session_id);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload_MAF2.php', true);
      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          $('#GSETDprogressbar').show();
          var percentComplete = (e.loaded / e.total) * 100;
          $('#GSETDprogresswidth').width(percentComplete.toFixed(2) + '%');
          $('#GSETDprogresspercent').html(percentComplete.toFixed(2) + '%');
        }
      };

      xhr.onload = function() {
        if (this.status == 200) {
          var resp = JSON.parse(this.response);
          $('#GSETDprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#GSETDprogressbar').hide();
          if (resp.status == 1) {
            var fullPath = resp.targetPath;
            module_info_file = fullPath.replace("/var/www/mergeomics/html/./Data/Pipeline/", "");
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#GSETDfilereturn').html(filename);
            $('#GSETD_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
          } else {
            $('#GSETD_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong><br>' + resp.msg + '</div>');
            $("#GSETDfilereturn").empty();
          }
        };
      };
      xhr.send(fd);
    }
  });
  $("#GSETDlabelname").on("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      $("#GSETDuploadInput").focus();
    }
  });
  $("#GSETDlabelname").on("click", function(event) {
    $("#GSETDuploadInput").focus();
  });
*/
</script>