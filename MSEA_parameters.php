 <?php

/* Initialize PHP variables
sessionID = the saved session 

rmchoice = type of pipeline chouce

GET = if the user enters the link directly
POST = if PHP enters the link

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
$FILE_UPLOAD=$ROOT_DIR."Data/Pipeline/Resources/msea_temp/";

//Check if the sessionID exists and the user has returned to the form
if (isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {
  //if it does exist, then initialize the sessionID with specified one
  $sessionID = $_GET['sessionID'];
} else {
  //if it does not exist, then create a new random string
  $sessionID = generatesessionIDing(10);
}

//check if form has been submitted and then initialize it
if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  $sessionID = $_POST['sessionID'];
}

$fpostOut = "./Data/Pipeline/Resources/mesa_temp/$sessionID" . "_MSEA_postdata.txt";
$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$session_write = NULL;

//if the sessionID does not exist
if (!file_exists($fsession)) {
  //create the session txt file
  $sessionfile = fopen($fsession, "w");
  $session_write .= "Pipeline:" . "\t" . "MSEA" . "\n";
  $session_write .= "Mergeomics_Path:" . "\t" . "1" . "\n";
  $session_write .= "Pharmomics_Path:" . "\t" . "0|0" . "\n";
  fwrite($sessionfile, $session_write);
  fclose($sessionfile);
  chmod($fsession, 0775);
}

//if the POST data doesn't exist yet, create it and store on server
if (!empty($_POST)) {
  $fp = fopen($fpostOut, "w");
  foreach ($_POST as $key => $value) {
    $postwrite .= $key . "\t" . $value . "\n";
  }
  fwrite($fp, $postwrite);
  fclose($fp);
  chmod($fpostOut, 0774);
}


/* Initializes form data variables. Imo this is not very efficient, but this is what was here before. So I didn't change it.
There are definitely better ways to do this though...

 */
$data = (isset($_POST['formChoice_MSEA']) ? $_POST['formChoice_MSEA'] : null);
$data1 = (isset($_POST['formChoice_mapping']) ? $_POST['formChoice_mapping'] : null);
$data2 = (isset($_POST['formChoice2_MSEA']) ? $_POST['formChoice2_MSEA'] : null);
$data3 = (isset($_POST['formChoice3_MSEA']) ? $_POST['formChoice3_MSEA'] : null);



if (strlen($data) < 3) {
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


if (strlen($data1) < 3) {
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





$fpath = "./Data/Pipeline/Resources/msea_temp/$sessionID";

$pv = "";


//create the parameter files when user submits form
if (isset($_POST['permuttype']) ? $_POST['permuttype'] : null) {
  $fpathparam = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "PARAM";
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

  $mseafdr = $_POST['mseafdr'];

  $fpathparam = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "PARAM_MSEA_FDR";
  $par = "$mseafdr\n";  //MSEA FDR default is 25.0, use 25.0-0
  $fp = fopen($fpathparam, "w");
  fwrite($fp, $par);
  fclose($fp);
}

/* if the POST data already exists (i.e. user comes back), then initialize their choices to the form */
if (file_exists($fpostOut)) {
  /*  
        Example:
        formChoice_wKDA Jr3tZK12Qj|2|0|0  --> We want to get the 2 and initialize to formChoice_wKDA inpit
        kdaparam_depth  Jr3tZK12Qj|0|0|1|0 --> We want to get the 1 and initialize to kdaparam_depth input
        kdaparam_direct Jr3tZK12Qj|0|0|1 --> We want to get the 1 and initialize to kdaparam_depth input
      */
  //create an array from each line of POST data

  $postdata = explode("\n", file_get_contents($fpostOut));
  //create another array to store first/second/etc line of POST data (separated by tab)
  $splitformChoice = preg_split("/[\t]/", $postdata[0]);
  //create another array to store info from second column [i.e. array(Jr3tZK12Qj, 0, 0, 1, 0)] (separated by "|")
  $explodeformChoice = explode("|", $splitformChoice[1]);
  $locformChoice = $explodeformChoice[1]; // initialize the data to form choice ($kdaformChoice = 1)


  //repeat above for the other form choices
  $splitformChoice1 = preg_split("/[\t]/", $postdata[1]);
  //check if the user had uploaded their own mapping file
  if ($splitformChoice1[0] == "formChoice_mapping") {
    //if they did, set the parameters with the mapping file
    $explodeformChoice1 = explode("|", $splitformChoice1[1]);
    $locformChoice1 = $explodeformChoice1[1];

    $splitformChoice2 = preg_split("/[\t]/", $postdata[2]);
    $explodeformChoice2 = explode("|", $splitformChoice2[1]);
    $moduleformChoice2 = $explodeformChoice2[2];

    $splitformChoice3 = preg_split("/[\t]/", $postdata[3]);
    $explodeformChoice3 = explode("|", $splitformChoice3[1]);
    $descformChoice3 =  $explodeformChoice3[3];
  } else {
    //if they did not, set the parameters without the mapping file
    $explodeformChoice1 = explode("|", $splitformChoice1[1]);
    $moduleformChoice2 = $explodeformChoice1[2];

    $splitformChoice2 = preg_split("/[\t]/", $postdata[2]);
    $explodeformChoice2 = explode("|", $splitformChoice2[1]);
    $descformChoice3 = $explodeformChoice2[3];
  }
}


?>
<!-- Error message div for MSEA ===================================================== -->
<div id="errormsg_MSEA" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_MSEA" style="white-space: pre;"></p>
</div>
</div>




<!-- Grid container for MSEA ===================================================== -->
<div class="gridcontainer">

  <!-- Description based on pipeline choice ===================================================== -->

  <?php
  if (isset($_GET['rmchoice'])) {
    $rmchoice = $_GET['rmchoice'];
    if ($rmchoice != 3) {
  ?>
      <h4 class="instructiontext">
        This part of the pipeline starts from MSEA and then it gives the option for performing wKDA on MSEA results.
      </h4>

  <?php
    }
  }

  ?>


  <!--Start MSEA Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_MSEA"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox' style="display: none;"></div>
  <!--End MSEA Tutorial --------------------------------------->


</div>
<!--End of grid container --->

<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="MSEA_parameters.php" name="select2" id="MSEAdataform">
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
              <div id="MMF_question" style="line-height:30px;">Would you like to use a mapping file (ex. epigenetic markers to genes)?
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
                    <th><a href="#" tooltip="Epigenetic probe, gene, protein, or metabolite" style="position: relative;">MARKER</a></th>
                    <th><a href="#" tooltip="Association strength which can be -log10(p-value), effect size, absolute value of the log fold change, etc. Larger values signify higher association strength." style="position: relative;">VALUE</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="MARKER(Header): ">C1QA</td>
                    <td data-column="VALUE(Header): ">5.348</td>

                  </tr>
                  <tr>
                    <td data-column="MARKER(Header): ">GFAP</td>
                    <td data-column="VALUE(Header): ">1.907</td>

                  </tr>
                  <tr>
                    <td data-column="MARKER(Header): ">JUNB</td>
                    <td data-column="VALUE(Header): ">0.425</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that contains marker to trait associations. UTF-8/ASCII encoded files recommended. Sample files for all inputs can be found <a href="samplefiles.php">here</a>.</p>
            </div>
          </td>
          <!--End MDF Sample File Format -->
        </tr>

        <!--------------------------Second row of table (will show only if "yes" button is pressed)------------------------------------------>
        <tr id="marker_mapping" style="display:none;">
          <!--Second row of table (show only if button is pressed)------------------------------------------>
          <td data-column="File type &#xa;">Marker Mapping File

            <div>
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>File that links markers to those of the marker sets to be enriched (e.g. gene sets). An example CgID to gene mapping file is provided (EWAS).
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
                <option value="Resources/mappings/Sample_EWAS_Mapping.txt">Sample Human EWAS Mapping</option>
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
                    <th><a href="#" tooltip="Epigenetic probe, gene, protein, or metabolite" style="position: relative;">MARKER</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="GENE(Header): ">A4GALT</td>
                    <td data-column="MARKER(Header): ">cg07393322</td>

                  </tr>
                  <tr>
                    <td data-column="GENE(Header): ">GBL</td>
                    <td data-column="MARKER(Header): ">cg04490516</td>

                  </tr>
                  <tr>
                    <td data-column="GENE(Header): ">NXPH3</td>
                    <td data-column="MARKER(Header): ">cg06405206</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that provides marker to gene mapping. UTF-8/ASCII encoded files recommended.</p>
            </div>
          </td>
          <!--End MMF Sample File Format -->
        </tr>
        <tr id="mdf_row" style="display:none;">
          <td data-column="File Type &#xa;">
            Marker Dependency File
            <div>
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
              File to filter out dependencies between markers with correlation between 0 to 1 in the 'WEIGHT' column. Provide the correlated markers for which you would like to filter on (ex. only upload >70% correlated markers for >70% filtering).
              <br>The sample methylation disequilibrium file provided here was obtained from <a href="https://academic.oup.com/bioinformatics/article/34/15/2657/4939328" target="_blank">EWAS software 2.0</a>.
            </div>

          </td>
          <!--Fourth row|first column (Type of File)-------------------->
          <td data-column="Upload/Select File &#xa;">
            <!--Fourth row|second column (Upload/Select File) -------------------->
            <div class="selectholder MSEA" align="center">
              <select class="MSEA" name="formChoice_mdf" size="1" id="mdf">
                <option value="0">Please select option</option>
                <option value="private_data">Upload your Correlation File</option>
                <option value="Resources/LD_files/md_example_50.txt">Example marker dependency for EWAS</option>
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
              <table style="display: none;">
              	<td style="vertical-align: middle">
              		Gene Identifier Conversion
              	</td>
              	<td>              
	              <select class="btn dropdown-toggle btn-light" name="MDFConvert" size="1" id="MDFConvert" style="font-size: 18px;">
		              <option value="none" selected>None</option>
		              <option value="entrez">Entrez to gene symbol</option>
		              <option value="ensembl">Ensembl to gene symbol</option>
	             </select>
         		</td>
              </table>
            </div> <!-- End of upload div--->
            <div id="PercentageMDF">
              <div class="datagrid">
                <p class="nobottommargin">Percentage of Markers:</p>
                <input type="text" id="percent_markers" name="percentage" value="100">
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
                    <th><a href="#" tooltip="Epigenetic probe, etc." style="position: relative;">MARKERa</a></th>
                    <th><a href="#" tooltip="Epigenetic probe, etc." style="position: relative;">MARKERb</a></th>
                    <th><a href="#" tooltip="Correlation value between MARKERa and MARKERb &#013; (0-100)" style="position: relative;">WEIGHT</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="Markera:">cg14008030</td>
                    <td data-column="Markerb:">cg21870274</td>
                    <td data-column="Weight:">1.000</td>

                  </tr>
                  <tr>
                    <td data-column="Markera:">cg21870274</td>
                    <td data-column="Markerb:">cg23100540</td>
                    <td data-column="Weight:">0.866</td>

                  </tr>
                  <tr>
                    <td data-column="Markera:">cg21870274</td>
                    <td data-column="Markerb:">cg01097950</td>
                    <td data-column="Weight:">0.736</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that provides the two markers and their correlation. UTF-8/ASCII encoded files recommended. </p>
            </div>
          </td>
        </tr>
        <!---------------------------------------End MMF---- ---------------------------------------------------->
        <tr>
          <!--Second row of table------------------------------------------>
          <td data-column="File type &#xa;">Marker Sets

            <div class="informationtext" data-toggle="modal" data-target="#GSETinfomodal" href="#GSETinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>

          </td>
          <!--Second row|first column of table------------------------------------------>
          <td data-column="Upload/Select File &#xa;">
            <!--Start MDF Upload ----------------------------->
            <div id="" class="selectholder MSEA" align="center">
              <select class="MSEA" name="formChoice2_MSEA" size="1" id="module">
                <option value="0">Please select option</option>
                <option value="private_data">Upload Gene Sets</option>
                <option value="Resources/pathways/KEGG_Reactome_BioCarta.txt">KEGG, Reactome, and BioCarta pathways</option>
                <option value="Resources/pathways/KEGG.txt">KEGG pathways</option>
                <option value="Resources/pathways/Reactome.txt">Reactome pathways</option>
                <option value="Resources/pathways/BioCarta.txt">BioCarta pathways</option>
                <option value="Resources/pathways/WikiPathways2021.txt">WikiPathways</option>
                <option value="Resources/pathways/BioPlanet.txt">BioPlanet Pathways</option>
                <option value="Resources/pathways/GWAS_Catalog_2021.txt">GWAS Catalog Trait Associations</option>
                <option value="Resources/pathways/MSigDB_Hallmark.txt">MSigDB Hallmark</option>
                <option value="Resources/pathways/MSigDB_canonical_pathways.txt">MSigDB Canonical Pathways</option>
                <option value="Resources/pathways/MSigDB_regulatory_target.txt">MSigDB Regulatory Target (TFs and miRNA)</option>
                <option value="Resources/pathways/MSigDB_cell_type_signatures.txt">MSigDB Cell Type Signatures</option>
                <option value="Resources/pathways/MSigDB_chemical_genetic_perturbations.txt">MSigDB chemical and genetic perturbations</option>
                <option value="Resources/pathways/MSigDB_Oncogenic_Signatures.txt">MSigDB Oncogenic Signatures</option>
                <option value="Resources/pathways/MSigDB_Immunologic_Signatures.txt">MSigDB Immunologic Signatures</option>
                <option value="Resources/pathways/MSigDB_Computational_Gene_Sets_Cancer.txt">MSigDB Computational Gene Sets (Cancer)</option>
                <option value="Resources/pathways/GO_biological_process.txt">GO Biological Process</option>
                <option value="Resources/pathways/GO_Cellular_Component.txt">GO Cellular Component</option>
                <option value="Resources/pathways/GO_Molecular_Function.txt">GO Molecular Function</option>
                <option value="Resources/pathways/Adipose_Subcutaneous_Coexp.txt">Adipose Subcutaneous Coexpression</option>
                <option value="Resources/pathways/Adipose_Visceral_Omentum_Coexp.txt">Adipose Visceral Omentum Coexpression</option>
                <option value="Resources/pathways/Adrenal_Gland_Coexp.txt">Adrenal_Gland Coexpression</option>
                <option value="Resources/pathways/Artery_Aorta_Coexp.txt">Artery Aorta Coexpression</option>
                <option value="Resources/pathways/Artery_Tibial_Coexp.txt">Artery Tibial Coexpression</option>
                <option value="Resources/pathways/Brain_Cerebellar_Hemisphere_Coexp.txt">Brain Cerebellar Hemisphere Coexpression</option>
                <option value="Resources/pathways/Brain_Cerebellum_Coexp.txt">Brain Cerebellum Coexpression</option>
                <option value="Resources/pathways/Brain_Cortex_Coexp.txt">Brain Cortex Coexpression Modules</option>
                <option value="Resources/pathways/Brain_Frontal_Cortex_BA9_Coexp.txt">Brain Frontal Cortex BA9 Coexpression</option>
                <option value="Resources/pathways/Brain_Hippocampus_Coexp.txt">Brain Hippocampus Coexpression</option>
                <option value="Resources/pathways/Brain_Hypothalamus_Coexp.txt">Brain Hypothalamus Coexpression</option>
                <option value="Resources/pathways/Colon_Sigmoid_Coexp.txt">Colon Sigmoid Coexpression</option>
                <option value="Resources/pathways/Esophagus_Mucosa_Coexp.txt">Esophagus Mucosa Coexpression</option>
                <option value="Resources/pathways/Esophagus_Muscularis_Coexp.txt">Esophagus Muscularis Coexpression</option>
                <option value="Resources/pathways/Heart_Left_Ventricle_Coexp.txt">Heart Left Ventricle Coexpression</option>
                <option value="Resources/pathways/Liver_Coexp.txt">Liver Coexpression</option>
                <option value="Resources/pathways/Muscle_Skeletal_Coexp.txt">Muscle Skeletal Hypothalamus Coexpression</option>
                <option value="Resources/pathways/Nerve_Tibial_Coexp.txt">Nerve Tibial Coexpression</option>
                <option value="Resources/pathways/Pancreas_Coexp.txt">Pancreas Coexpression</option>
                <option value="Resources/pathways/Pituitary_Coexp.txt">Pituitary Coexpression</option>
                <option value="Resources/pathways/Spleen_Coexp.txt">Spleen Coexpression</option>
                <option value="Resources/pathways/Stomach_Coexp.txt">Stomach Coexpression</option>
                <option value="Resources/pathways/Thyroid_Coexp.txt">Thyroid Coexpression</option>
                <option value="Resources/pathways/Whole_Blood_Coexp.txt">Whole Blood Coexpression</option>
              </select>
              <br>
              <!-- Mapping Association File Upload div --->
            </div>
            <!--End selectupload_Gset div-->

            <div id="GSETupload" style="display: none;">

              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

              <div class="input-file-container" name="Gene Sets File" style="width: fit-content;">
                <input class="input-file" id="GSETuploadInput" name="GSETuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="GSETlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="GSETprogressbar" class="progress active" style='display: none;'>
                  <div id="GSETprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="GSETprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="GSETfilereturn" class="file-return"></p>
                <span id='GSET_uploaded_file'></span>
              </div>
               <table>
              	<td style="vertical-align: middle">
              		Gene Identifier Conversion
              	</td>
              	<td>              
	              <select class="btn dropdown-toggle btn-light" name="GSETConvert" size="1" id="GSETConvert" style="font-size: 18px;">
		              <option value="none" selected>None</option>
		              <option value="entrez">Entrez to gene symbol</option>
		              <option value="ensembl">Ensembl to gene symbol</option>
	             </select>
         		</td>
              </table>
            </div> <!-- End of upload div--->

            <div class="alert-MSEA" id="alert_GSET"></div>
            <!--Div to alert user of certain comment (i.e. success) -->


          </td>
          <!--Second row|second column of table------------------------------------------>
          <td data-column="Sample Format &#xa;" name="val1_MSEA">
            <!--Start Second row|third column of table------------------------------------------>

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="Name of marker set (i.e. canonical pathway or co-expression module)" style="position: relative;">MODULE</a></th>
                    <th><a href="#">GENE</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="MODULE(Header): ">Cell cycle</td>
                    <td data-column="GENE(Header): ">CDC16</td>

                  </tr>
                  <tr>
                    <td data-column="MODULE(Header): ">Cell cycle</td>
                    <td data-column="GENE(Header): ">ANAPC1</td>

                  </tr>
                  <tr>
                    <td data-column="MODULE(Header): ">WGCNA Brown</td>
                    <td data-column="GENE(Header): ">XRCC5</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that contains collections of pre-defined sets of genes that are functionally related. UTF-8/ASCII encoded files recommended. </p>
            </div>


          </td>
          <!--End Second row|third column of table------------------------------------------>

        </tr>
        <tr id="gsetd_row" style="display:none;">
          <td data-column="File type &#xa;">Marker Sets Description<br>(Optional)

            <div class="informationtext" data-toggle="modal" data-target="#GSETDinfomodal" href="#GSETDinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>

          </td>
          <td data-column="Upload/Select File &#xa;">
            <!--Start Gene Set Description Input ----------------------------->
            <div id="Selectupload_GSETD" class="selectholder MSEA" align="center">
              <select class="MSEA" name="formChoice3_MSEA" size="1" id="module_info">
                <option value="0">Please select option</option>
                <option value="private_data">Upload Gene Sets descriptions</option>
                <option value="no" selected>No Gene Sets Description</option>
              </select>
            </div> <!-- End Gene Set Description input -->
            <!-- Gene Set Description File Upload div --->
            <div id="GSETDupload" style="display: none;">

              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

              <div class="input-file-container" name="Gene Sets Description File" style="width: fit-content;">
                <input class="input-file" id="GSETDuploadInput" name="GSETDuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="GSETDlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="GSETDprogressbar" class="progress active" style='display: none;'>
                  <div id="GSETDprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="GSETDprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="GSETDfilereturn" class="file-return"></p>
                <span id='GSETD_uploaded_file'></span>
              </div>
            </div> <!-- End of upload div--->


            <div class="alert-MSEA" id="alert_GSETD"></div>
            <!--Div to alert user of certain comment (i.e. success) -->


          </td>
          <td data-column="Sample Format &#xa;" name="val2_MSEA">
            <!--Third row|Third column (Gene Set Description sample format) -------------------->

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="Name of marker set (I.e. canonical pathway or co-expression module)" style="position: relative;">MODULE</a></th>
                    <th><a href="#" tooltip="Source of marker set" style="position: relative;">SOURCE</a></th>
                    <th><a href="#" tooltip="Description of marker set" style="position: relative;">DESCR</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="MODULE(Header): ">Cell cycle</td>
                    <td data-column="SOURCE(Header): ">KEGG</td>
                    <td data-column="DESCR(Header): ">Mitotic cell cycle progression is accomplished through a reproducible sequence of events - S, M, G1, and G2 phases.</td>

                  </tr>
                  <tr>
                    <td data-column="MODULE(Header): ">WGCNA Brown</td>
                    <td data-column="SOURCE(Header): ">WGCNA Liver Coexpression Module</td>
                    <td data-column="DESCR(Header): ">Immune function</td>
                  </tr>
                  <tr>
                    <td data-column="MODULE(Header): ">Proteasome Pathway</td>
                    <td data-column="SOURCE(Header): ">BioCarta</td>
                    <td data-column="DESCR(Header): ">https://www.gsea-msigdb.org/gsea/msigdb/cards/ BIOCARTA_PROTEASOME_PATHWAY</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that contains detailed descriptions of the gene sets (i.e. full name of a biological pathway). UTF-8/ASCII encoded files recommended. </p>
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
          <!---------------Start of Permutation input ------------->
          <td>Permutation type:</td>

          <td name="val1"> <select class="btn dropdown-toggle btn-light" name="permuttype" size="1" id="permuttype">
              <option value="marker" selected>Marker</option>
              <option value="gene" disabled>Gene</option>
            </select>
          </td>



        </tr>
        <!---------------Start of Max Genes in Gene Sets input ------------->
        <tr name="Max Genes in Gene Sets">

          <td>Max Genes in Gene Sets:</td>

          <td name="val2"><input class='MSEAparameter' type="text" name="maxgene" id="maxgene" value="<?php if (isset($_POST['maxgene']) ? $_POST['maxgene'] : null) {
                                                                                                        print($_POST['maxgene']);
                                                                                                      } else {
                                                                                                        print("500");
                                                                                                      } ?>">
          </td>


        </tr>
        <!---------------Start of Min Genes in Gene Sets input ------------->
        <tr name="Min Genes in Gene Sets">

          <td>Min Genes in Gene Sets:</td>

          <td name="val3"><input class='MSEAparameter' type="text" name="mingene" id="mingene" value=" <?php if (isset($_POST['mingene']) ? $_POST['mingene'] : null) {
                                                                                                          print($_POST['mingene']);
                                                                                                        } else {
                                                                                                          print("10");
                                                                                                        } ?>">
          </td>

        </tr>
        <!---------------Start of Max Overlap for Merging Gene Mapping input ------------->
        <tr name="Max Overlap for Merging Gene Mapping">

          <td>Max Overlap for Merging Gene Mapping:</td>

          <td name="val4"> <input class='MSEAparameter' id="maxoverlap" type="text" name="gene_overlap" value="<?php if (isset($_POST['gene_overlap']) ? $_POST['gene_overlap'] : null) {
                                                                                                                  print($_POST['gene_overlap']);
                                                                                                                } else {
                                                                                                                  print("1");
                                                                                                                } ?>" readonly="readonly">
          </td>


        </tr>
        <!---------------Start of Min Overlap Allowed for Merging input ------------->
        <tr name="Min Overlap Allowed for Merging">

          <td>Min Module Overlap Allowed for Merging:</td>

          <td name="val5"><input class='MSEAparameter' id="minoverlap" type="text" name="overlap" value="<?php if (isset($_POST['overlap']) ? $_POST['overlap'] : null) {
                                                                                                            print($_POST['overlap']);
                                                                                                          } else {
                                                                                                            print("0.33");
                                                                                                          } ?>">
          </td>


        </tr>
        <!---------------Start of Number of Permutations input ------------->
        <tr name="Number of Permutations">

          <td>Number of Permutations:</td>

          <td name="val6"><input class='MSEAparameter' type="text" name="permu" id="mseanperm" value="<?php if (isset($_POST['permu']) ? $_POST['permu'] : null) {
                                                                                                        print($_POST['permu']);
                                                                                                      } else {
                                                                                                        print("2000");
                                                                                                      } ?>"></td>

        </tr>
        <tr name="Trim">

          <td>Trim extremes:
            <div class="alert alert-warning" style="margin: 0 auto; width: 90%;margin-top: 10px;">
              <i class="icon-warning-sign" style="margin-right: 6px;font-size: 12px;"></i>Percentile of markers taken from beginning and end of trait associations to avoid signal inflation of null background in gene permutation
            </div>  

          </td>  

          <td name="val8"><input class='MSEAparameter' type="text" name="trim" id="mseatrim" value="<?php if (isset($_POST['trim']) ? $_POST['trim'] : null) {
                                                                                                        print($_POST['trim']);
                                                                                                      } else {
                                                                                                        print("0.002");
                                                                                                      } ?>"></td>

        </tr>
        <!---------------Start of MSEA FDR cutoff input ------------->
        <tr name="MSEA FDR cutoff">

          <td>MSEA to KDA export FDR cutoff:<br>
            <div class="alert alert-warning" style="margin: 0 auto; width: 90%;margin-top: 10px;">
            <i class="icon-warning-sign" style="margin-right: 6px;font-size: 12px;"></i>This parameter is used for exporting results to KDA. If no modules pass this significance level, the top 10 pathways will be exported to KDA. Make note if this is the case and interpret downstream results cautiously.
            </div>
          </td>

          <td name="val7"><input class='MSEAparameter' id="mseafdr" type="text" name="mseafdr" value="<?php if (isset($_POST['mseafdr']) ? $_POST['mseafdr'] : null) {
                                                                                                        print($_POST['mseafdr']);
                                                                                                      } else {
                                                                                                        print("5");
                                                                                                      } ?>"></td>






        </tr>

      </tbody>
    </table>
  </div>
  <!--End of responsive div for parameters table -->
  <br>
  <!-------------------------------------------------End of MSEA Parameters table ----------------------------------------------------->
  <!-------------------------------------------------Start Review button ----------------------------------------------------->
  <div id="Validatediv_MSEA" style="text-align: center;">
    <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_MSEA">Click to Review</button>

</form>
<!--End of MSEA form (This combines the two inputs together) ---->
</div>
<!-------------------------------------------------End Review button ----------------------------------------------------->


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
            <div class="style-msg infomsg" style="margin: 0 auto; width: 80%;padding:30px;font-size: 18px;text-align: left;"> Mapping files can be used to map your markers to markers of the chosen marker sets. For example, to enrich EWAS data for gene sets, CpG sites have to be mapped to genes. The mapping file would have the CpG sites in the ‘MARKER’ column and genes in the ‘GENE’ column.
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
            <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;text-align: left;"> Marker-disease association summary results including marker IDs and association strengths (larger values indicate higher association, i.e. use –log10 p-values, effect size, etc.). Markers should match the markers of the chosen marker sets. If they do not, use a mapping file. Currently, we have a sample EWAS mapping file that maps CG methylation probe IDs to genes which can be used if you would like to enrich your EWAS data for gene sets. We are working to expand our resources.
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
  /**********************************************************************************************
Javascript functions/scripts (These are inlined because it was easier to do)
You can technically extract it and just call it externally if you want to keep the php page cleaner, but not needed
***********************************************************************************************/
  var session_id = "<?php echo $sessionID; ?>";
  var target_path="<?php echo $FILE_UPLOAD;?>";
  var marker_association_file = null;
  var mapping_file = null;
  var mdffile = null;
  var mdf_ntop = null;
  var module_set_file = null;
  var module_info_file = null;
  var permtype = null;
  var maxgene = null;
  var mingene = null;
  var maxoverlap = null;
  var minoverlap = null;
  var mseanperm = null;
  var mseafdr = null;
  var MAFConvert = null;
  var MMFConvert = null;
  var GSETConvert = null;

  var n = localStorage.getItem('on_load_session');
  localStorage.setItem("on_load_session", session_id);
  $(document).ready(function() {
    //change the sessionID to current on sidebar
    $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + session_id).attr('tooltip','Save your session ID! Click to copy.');
    $('#session_id').css("padding", "17px 30px");
  });

  $("#MSEAtoggleNav").on('click', function(e){
    var href = $(this).attr('href');
    if ($(href).children('.togglec').css('display') == 'none') {
        $(href).children(0).click();
    }
    console.log($(href).offset().top);
    console.log($(window).scrollTop());

    var val = $(href).offset().top - $(window).scrollTop() - 65; // at top

    if (val<=0 || ($(window).scrollTop()!=0 && $(window).scrollTop() < $(href).offset().top)){ 
      // below item or scrolled down but not below item
      var val = $(href).offset().top - 65;
    } 

    $(window).scrollTop(
      val
    );

    return false;
  });

  function MSEAdone() //This function gets the review table for MSEA
  {
    $.ajax({
      url: "MSEA_moduleprogress.php",
      method: "GET",
      data: {
        sessionID: session_id,
        marker_association: marker_association_file,
        mapping: mapping_file,
        mdf: mdffile,
        mdf_ntop: mdf_ntop,
        module: module_set_file,
        module_info: module_info_file,
        perm_type: permtype,
        max_gene: maxgene,
        min_gene: mingene,
        maxoverlap: maxoverlap,
        minoverlap: minoverlap,
        mseanperm: mseanperm,
        mseatrim: mseatrim,
        mseafdr: mseafdr,
        enrichment: "EWAS/TWAS/MWAS",
        MAFConvert: MAFConvert,
        MMFConvert: MMFConvert,
        GSETConvert: GSETConvert,
        rerun: "T",
      },
      success: function(data) {
        $('#myMSEA_review').html(data);
        $('#MSEAtab2').show();
        $('#MSEAtab2').click();
      }
    });
    $("#Validatebutton_MSEA").html("Click to Review");
    $("#Validatebutton_MSEA").removeAttr("disabled");
    $("#MSEAtogglet").html("<i class='toggle-closed icon-remove-circle'></i><i class='toggle-open icon-remove-circle'></i><div class='capital'>Step 1: Marker Set Enrichment Analysis</div>");
    //$('#MSEAtab1').html("Review Files");
  }

  /**********************************************************************************************
  Set up Select slide down js function
  ***********************************************************************************************/
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
  //if user clicks somewhere else, it will close the dropdown box.
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



  /**********************************************************************************************
  Review and submit functions
  ***********************************************************************************************/

  function MSEAreview() //This function gets the review table for MSEA
  {


    $.ajax({
      url: "MSEA_moduleprogress.php",
      method: "GET",
      data: {
        sessionID: string
      },
      success: function(data) {
        $('#myMSEA_review').html(data);
      }
    });
    $('#MSEAtab2').show();
    $('#MSEAtab2').click();


  }

  ///////////////Start Submit Function (MSEA form) -- Function for clicking 'Click to review button'///////////////////////////////////

  $('#MSEAdataform').submit(function(e) {

    e.preventDefault();
    var form_data = new FormData(document.getElementById('MSEAdataform'));
    form_data.append("sessionID", string);

    $.ajax({
      'url': 'MSEA_parameters.php',
      'type': 'POST',
      'data': form_data,
      processData: false,
      contentType: false,
      'success': function(data) {
        $("#myMSEA").html(data);
        MSEAreview()
      }
    });


  });
  /////////////////////////////////////////////End submit function for MSEA form//////////////////////////////////////////////////////


  /**********************************************************************************************
  Validation/REVIEW button -- Function for clicking 'Click to review button
  Will create an error message at the top if user forgets or does not have all data entered into form
  ***********************************************************************************************/
  $("#Validatebutton_MSEA").on('click', function() {
    //check if user has clicked yes to upload mapping file
    if ($(".yes_map")[0]) {
      // Do something if yes_map class exists
      var select = $("select[name='formChoice_MSEA'] option:selected").index(),
        select_map = $("select[name='formChoice_mapping'] option:selected").index(),
        select1 = $("select[name='formChoice2_MSEA'] option:selected").index(),
        select2 = $("select[name='formChoice3_MSEA'] option:selected").index();

      //initialize arrays to check against formchoice w/ mapping file input
      var selectarray = [select, select_map, select1, select2];
      var idarray = ['MAFuploadInput', 'MMFuploadInput', 'GSETuploadInput', 'GSETDuploadInput'];



    } else if ($(".yes_map")[1]) {
      var select = $("select[name='formChoice_MSEA'] option:selected").index(),
        select_map = $("select[name='formChoice_mapping'] option:selected").index(),
        select_mdf = $("select[name='formChoice_mdf'] option:selected").index(),
        select1 = $("select[name='formChoice2_MSEA'] option:selected").index(),
        select2 = $("select[name='formChoice3_MSEA'] option:selected").index();

      //initialize arrays to check against formchoice w/ mapping file input
      var selectarray = [select, select_map, select_mdf, select1, select2];
      var idarray = ['MAFuploadInput', 'MMFuploadInput', 'MDFuploadInput', 'GSETuploadInput', 'GSETDuploadInput'];
    } else {
      // Do something if yes_map class does not exist
      var select = $("select[name='formChoice_MSEA'] option:selected").index(),
        select1 = $("select[name='formChoice2_MSEA'] option:selected").index(),
        select2 = $("select[name='formChoice3_MSEA'] option:selected").index();

      //initialize arrays to check against formchoice w/o mapping file input
      var selectarray = [select, select1, select2];
      var idarray = ['MAFuploadInput', 'GSETuploadInput', 'GSETDuploadInput'];

    }
    //This is the error array. If there is something in here, then that means there is an error
    //We use ".push" to add things into array
    var errorlist = [];
    selectarray.forEach(myFunction); //for loop with the created function


    var function_for_display_animation = function() {
      $("#preload").html(`<img src='include/pictures/ajax-loader.gif' />`);
    }

    function myFunction(item, index) //create a function to check array to variables
    {

      if (item === 0) //If user submits "Please choose an option"
      {
        errorlist.push($('#' + idarray[index].toString()).parent().attr('name') + ' is not selected!');
      } else if (item === 1) //If user submits an upload form but did not upload anything
      {
        if ($('#' + idarray[index].toString()).nextAll('.file-return').eq(0).text() == '') {
          errorlist.push($('#' + idarray[index].toString()).parent().attr('name') + ' is not selected!');
        }
      } else {
        //do nothing
      }


    }
    //check each MSEA parameters
    $('.MSEAparameter').each(function() {
      if ($(this).val() == "") {
        errorlist.push($(this).parent().parent().attr('name') + ' is empty!');

      }
    });

    if ($('#MMF_question').length < 0) {
      //if the question doesn't exist, no check
    } else if ($('#MMF_question').is(':empty')) {
      //if the question does exist and it's empty, no check

    } else {
      //if the question exists and it's not empty, hard check
      //errorlist.push("Please select Yes/No to include your mapping file!")
    }
    //check if the errorlist array is empty
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
        $('#myMSEA_body').empty();

        MAFConvert = $("#MAFConvert").val();
        MMFConvert = $("#MMFConvert").val();
        GSETConvert = $("#GSETConvert").val();

        if ($("#marker_association").val() != "private_data") {
          marker_association_file = $("#marker_association").val();
          MAFConvert = "none";
        }
        if ($("#mapping_file").val() != "private_data") {
          mapping_file = $("#mapping_file").val();
          MMFConvert = "none";
        }
        if ($("#module").val() != "private_data") {
          module_set_file = $("#module").val();
          GSETConvert = "none";
        }
        if ($("#module_info").val() != "private_data") { // user did not upload module info
          //module_info_file = $("#module_info").val();
          if ($("#module").val() != "private_data") { // user chose sample
          	module_info_file = module_set_file.replace(".txt","_info.txt");
      	  }
      	  else{ // user chose to upload module sets but left option as "No Gene Sets Description"
      	  	module_info_file = $("#module_info").val();
      	  }
        }

        if ($("#mdf").val() != "private_data") {
          mdffile = $("#mdf").val();
        }

        permtype = $("#permuttype").val();
        maxgene = $("#maxgene").val();
        mingene = $("#mingene").val();
        maxoverlap = $("#maxoverlap").val();
        minoverlap = $("#minoverlap").val();
        mseanperm = $("#mseanperm").val();
        mseatrim = $("#mseatrim").val();
        mseafdr = $("#mseafdr").val();
        mdf_ntop = $("#percent_markers").val();
        MSEAdone();

      }, 500);

    } else {
      //if errorlist array is not empty, then slidedown error message
      var result = errorlist.join("\n");
      //alert(result);
      $('#errorp_MSEA').html(result);
      $("#errormsg_MSEA").fadeTo(2000, 500).slideUp(500, function() {
        $("#errormsg_MSEA").slideUp(500);
      });


    }





  });

  /**********************************************************************************************
  Filter percentage function -- Creates a filter (i.e. no decimals and number between 1-100) so that user doesn't submit inaccurate data
  Applies to the text forms
  ***********************************************************************************************/
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

  //Can only input values from 0-1. If they try to type "2", it won't appear
  $("#minoverlap, #maxoverlap").inputFilter(function(value) {
    return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });
  //Can only input values from 0-25. If they try to type "50", it won't appear
  $("#mseafdr").inputFilter(function(value) {
    return /^\d*$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 25));
  });







  /**********************************************************************************************
  Tutorial Button Script -- Append the tutorial to the form
  ***********************************************************************************************/

  var myTutButton_MSEA = document.getElementById("myTutButton_MSEA");
  var val_MSEA = 0; //We only want to append once, even if the user clicks on the tutorial button multiple times

  //begin function for when button is clicked-------------------------------------------------------------->
  myTutButton_MSEA.addEventListener("click", function() {

    //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
    var $this_MSEA = $(this);

    //If tutorial is already opened yet, then do this-------------------------------------------------------------->
    if ($this_MSEA.data('clicked')) {

      //hide the tutorial box
      $('.tutorialbox').hide();

      //remove the tutorial from the wKDA parameters table
      $('#MSEAparameterstable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });

      //remove the tutorial from the wKDA main table
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
                                    <strong>Default value</strong>: "Marker" is the only option for EWAS/TWAS/PWAS/MWAS user.
                                 </td>

                                `);

        $('#MSEAparameterstable').find('td[name="val2"]').eq(-1).after(`

                                <td name="tut">
                                <strong>Max Genes in Gene Sets</strong>: defines the maximum gene number that a gene set can have. <br>
                                        <strong>Options</strong>: Number between 2 and 10,000; suggested between 200-800 <br>
                                        <strong>Default value</strong>: 500
                                 </td>

                                `);
        $('#MSEAparameterstable').find('td[name="val3"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Min Genes in Gene Sets</strong>: defines the minimal gene number that a gene set can have. <br>
                                        <strong>Options</strong>: Number between 2 and less than Max Genes in Gene Sets <br>
                                        <strong>Default value</strong>: 10
                                </td>`);

        $('#MSEAparameterstable').find('td[name="val4"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Max Overlap in merging Gene Mapping</strong>: Overlap ratio threshold for merging genes with shared markers (SNPs). Over this overlap ratio, the genes will be merged. Applies only to genomic data.<br>
                                    <strong>Default Value</strong>: EWAS/TWAS/PWAS/MWAS user can only select "1" to skip merging
                                </td>`);

        $('#MSEAparameterstable').find('td[name="val5"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Min Module Overlap Allowed for Merging</strong>: Minimum gene overlap ratio between modules (gene sets) that will have them merged (to merge redundant modules). For instance, for the default value of 0.33, the modules need to have an overlap ratio of 0.33 or greater to be merged. <br>
                                   <strong>Options</strong>: 0 to 1 (Use 1 to skip merging) <br>
                                    <strong>Default value</strong>: 0.33 (33% overlap)
                                </td>`);


        $('#MSEAparameterstable').find('td[name="val6"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Number of Permutations</strong>: the number of gene or marker permutations conudcted in the MSEA analysis <br>
                                   <strong>Options</strong>: 1000 to 20,000 (for publication, recommend >= 10,000) <br>
                                    <strong>Default value</strong>: 2000
                                </td>`);

        $('#MSEAparameterstable').find('td[name="val7"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>MSEA to KDA export FDR cutoff</strong>: Gene sets must pass this FDR threshold to be exported to KDA. We recommend 5 (5%) for formal analysis. If no gene sets pass, the top 10 will be used in KDA. <br>
                                    <strong>Options</strong>: Between 0 to 25 (25 is 25%) <br>
                                    <strong>Default value</strong>: 5
                                </td>`);

        $('#MSEAparameterstable').find('th[name="val"]').eq(-1).after('<th name="tut">Comments</th>');

        $('.tutorialbox').show();
        $('.tutorialbox').html('The purpose of the pipeline is to take an association dataset for a given disease or phenotype from the user as input and integrate the association data with functional genomics information, pathways, and gene networks to derive pathways, gene networks and key regulatory genes for the disease or phenotype.');





      }
      $("#myTutButton_MSEA").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
    }



  });

  /**********************************************************************************************
upload/alert variables & success alerts -- functions for when you select an input file (sample or uploaded)
***********************************************************************************************/

  var successalert = '<div class="alert alert-success" style="margin: 20px 10px 0 10px;"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;margin:0px;"></i><strong>Success!</strong> </div>';
  var uploadalert = `<div class="alert alert-warning">
                                    <div class="sb-msg">
                                        <i class="icon-warning-sign"></i> 
                                        <strong>Maximum File Size:</strong> 400Mb</div>
                                    <div class="sb-msg">
                                        <i class="icon-warning-sign"></i>    
                                        <strong>Accepted file type:</strong> *.txt</div>
                                    </div>`;



  //Whenever the select option is changed, do some functions

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
    } else {
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

  $('#module').on('change', function(){
  	var select = $(this).find('option:selected').index();
  	if (select == 1) {
  		$('#gsetd_row').show();
  	} else {
  		$('#gsetd_row').hide();
  	}
  });

  // $('#marker_association').on('change', function() {
  //   var select = $(this).find('option:selected').index();
  //   if (select == 2) {

  //     $('#alert_MAF').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Success!</strong></div>
  //                       <br><div id="MMF_question" style="line-height:30px;">Would you like to use a mapping file to map your markers in your desired marker set? <a style="color:#5f5e58;" data-toggle="modal" data-target="#addMappingmodal" href="#addMappingmodal"><i class="icon-info-sign i-addmap"></i></a><br><button type="button" class="button button-3d button-small nomargin" id="MMF_Yes">Yes</button> <button type="button" class="button button-3d button-small nomargin" id="MMF_No">No</button></div> 
  //                       `);
  //   }

  // });



  //trigger the change at start of page
  //Helpful if a user comes back with the sessionID
  $('select.MSEA').each(function() {
    $(this).trigger('change');

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
    $('#mdf_row').addClass("yes_map");
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
    $('#mdf_row').removeClass("yes_map");
    $('#mdf_row').fadeOut('slow');
    //set mdf select val to 0
    $("#mdf").val("0");
  });




  /**********************************************************************************************
  Mapping File functions --- event listeners to Yes and No for mapping file upload
  ***********************************************************************************************/
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
    $("#mapping_file").val([]);
    $("#mapping_file").val("0");
  });

  // $(document).on('click', '#MMF_Yes', function() {
  //   $('#marker_mapping').addClass("yes_map");
  //   $('#marker_mapping').fadeIn('slow');
  //   $('#MMF_question').empty();



  // });

  // $(document).on('click', '#MMF_No', function() {

  //   if ($(".yes_map")[0]) {
  //     $('#marker_mapping').removeClass("yes_map");
  //     $('#marker_mapping').fadeOut('slow');
  //   }

  //   $('#MMF_question').empty();

  // });

  /**********************************************************************************************
  Upload functions -- uses AJAX to send data to a PHP file and then upload the file onto the server if conditions are correct
  ***********************************************************************************************/

  //Marker Associative File
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
      fd.append("path", target_path);
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
            marker_association_file = fullPath;
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#MAFfilereturn').html(filename);
            $('#MAF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
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

  //Mapping File Upload function

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
      fd.append("path", target_path);
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
            mapping_file = fullPath;
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#MMFfilereturn').html(filename);
            $("#alertMMF").show();
            // $('#MMF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
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
      fd.append("path", target_path);
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
            mdffile = fullPath;
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#MDFfilereturn').html(filename);
            //$("#alertMMF").show();
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


  //Gene Set Upload function   
  $("#GSETuploadInput").on('change', function() {
    $("#GSETlabelname").html("Select another file?");
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#GSETuploadInput"); //get the id
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", target_path);
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
            module_set_file = fullPath;
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
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      var fd = new FormData();
      fd.append("afile", file);
      fd.append("path", target_path);
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
            module_info_file = fullPath;
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

  /*These events are for when the upload form is clicked */
  // var fileInput3 = document.getElementById("MAFuploadInput"),
  //   button3 = document.getElementById("MAFlabelname"),
  //   the_return3 = document.getElementById("MAFfilereturn"),
  //   fileInput4 = document.getElementById("GSETuploadInput"),
  //   button4 = document.getElementById("GSETlabelname"),
  //   the_return4 = document.getElementById("GSETfilereturn"),
  //   fileInput5 = document.getElementById("GSETDuploadInput"),
  //   button5 = document.getElementById("GSETDlabelname"),
  //   the_return5 = document.getElementById("GSETDfilereturn");


  // button3.addEventListener("keydown", function(event) {
  //   if (event.keyCode == 13 || event.keyCode == 32) {
  //     fileInput3.focus();
  //   }
  // });
  // button3.addEventListener("click", function(event) {
  //   fileInput3.focus();
  //   return false;
  // });

  // button4.addEventListener("keydown", function(event) {
  //   if (event.keyCode == 13 || event.keyCode == 32) {
  //     fileInput4.focus();
  //   }
  // });
  // button4.addEventListener("click", function(event) {
  //   fileInput4.focus();
  //   return false;
  // });

  // button5.addEventListener("keydown", function(event) {
  //   if (event.keyCode == 13 || event.keyCode == 32) {
  //     fileInput5.focus();
  //   }
  // });
  // button5.addEventListener("click", function(event) {
  //   fileInput5.focus();
  //   return false;
  // });

  // fileInput3.addEventListener("change", function(event) {

  //   button3.innerHTML = "Select another file?";
  // });


  // fileInput4.addEventListener("change", function(event) {

  //   button4.innerHTML = "Select another file?";
  // });

  // fileInput5.addEventListener("change", function(event) {

  //   button5.innerHTML = "Select another file?";
  // });
</script>