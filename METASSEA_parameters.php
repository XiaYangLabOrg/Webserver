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

1) The overall sessionID ($meta_sessionID) = sessionID that the user will get to return to their META session
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
  //
  $old_meta_session = null;
}

if (isset($_GET['metasessionID'])) {
  $meta_sessionID = $_GET["metasessionID"];
}



//if an older session does not exist and a list_strings file does not exist
//This means that this is the first file
if (!file_exists("./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings")) {
  //then create the list_strings file with the current sessionID (first session)
  $fpath_random = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";
  $fp = fopen($fpath_random, "w");
  $towrite = "$sessionID\n";
  fwrite($fp, $towrite);
  fclose($fp);
} else {
  $fpath_random = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";
  $new_fpath_random = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";
  $current = file_get_contents($fpath_random);
  $current .= "$sessionID\n";
  file_put_contents($new_fpath_random, $current);
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
// $data_MAF = (isset($_POST['formChoice']) ? $_POST['formChoice'] : null);
// $data_MMP = (isset($_POST['formChoice2']) ? $_POST['formChoice2'] : null);
// $data = (isset($_POST['formChoice_SSEA']) ? $_POST['formChoice_SSEA'] : null);
// $data2 = (isset($_POST['formChoice2_SSEA']) ? $_POST['formChoice2_SSEA'] : null);


// if (strlen($data_MAF) < 3) {
//   $gwasformChoice = 0;
//   $locformChoice = 0;
//   $moduleformChoice = 0;
//   $descformChoice = 0;
// } else {
//   $pieces = explode("|", $data_MAF);
//   $gwasformChoice = (int)$pieces[0];
//   $locformChoice = (int)$pieces[1];
//   $moduleformChoice = (int)$pieces[2];
//   $descformChoice = (int)$pieces[3];
//   $sessionID = $pieces[4];
// }


// if (strlen($data_MMP) < 3) {
//   $gwasformChoice2 = 0;
//   $locformChoice2 = 0;
//   $moduleformChoice2 = 0;
//   $descformChoice2 = 0;
// } else {
//   $pieces2 = explode("|", $data_MMP);
//   $gwasformChoice2 = (int)$pieces2[0];
//   $locformChoice2 = (int)$pieces2[1];
//   $moduleformChoice2 = (int)$pieces2[2];
//   $descformChoice2 = (int)$pieces2[3];
//   $sessionID = $pieces2[4];
// }

// if (strlen($data) < 3) {
//   $gwasformChoice3 = 0;
//   $locformChoice3 = 0;
//   $moduleformChoice3 = 0;
//   $descformChoice3 = 0;
// } else {
//   $pieces3 = explode("|", $data);
//   $gwasformChoice3 = (int)$pieces3[0];
//   $locformChoice3 = (int)$pieces3[1];
//   $moduleformChoice3 = (int)$pieces3[2];
//   $descformChoice3 = (int)$pieces3[3];
//   $sessionID = $pieces3[4];
// }


// if (strlen($data2) < 3) {
//   $gwasformChoice4 = 0;
//   $locformChoice4 = 0;
//   $moduleformChoice4 = 0;
//   $descformChoice4 = 0;
// } else {
//   $pieces4 = explode("|", $data2);
//   $gwasformChoice4 = (int)$pieces4[0];
//   $locformChoice4 = (int)$pieces4[1];
//   $moduleformChoice4 = (int)$pieces4[2];
//   $descformChoice4 = (int)$pieces4[3];
//   $sessionID = $pieces4[4];
// }



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

  $sseafdr = $_POST['sseafdr'];

  $fpathparam = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "PARAM_SSEA_FDR";
  $par = "$sseafdr\n";  //SSEA FDR default is 25.0, use 25.0-0
  $fp = fopen($fpathparam, "w");
  fwrite($fp, $par);
  fclose($fp);
  chmod($fpathparam, 0664);


  $txt = "GWAS";
  $fpathloci = $fpath . "ENRICHMENT";
  $myfile = fopen($fpathloci, "w");
  fwrite($myfile, $txt);
  fclose($myfile);
  chmod($fpathloci, 0774);
}
*/



?>
<style>
  input[type="checkbox"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    -ms-appearance: none;
    -o-appearance: none;
    appearance: none;
    position: relative;

    height: 15px;
    width: 15px;
    border-radius: 5px;


    /* background: #cbd1d8; */
    border: none;
    /* color: #fff; */
    cursor: pointer;
    display: inline-block;

    z-index: 1000;
  }

  input[type="checkbox"]:checked::after {

    /* background: #39a9a4; */
    height: 15px;
    width: 15px;
    border-radius: 4px;
    border: 1px solid black;
    content: "\2713";
    font-size: 10px;
    display: block;
    z-index: 100;
    text-align: center;
    /* box-shadow: 2px 1px 6px 0px #555; */
  }
</style>

<div id="errormsg_SSEA" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_SSEA" style="white-space: pre;"></p>
</div>
</div>




<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">

  <div id="METAheader">
    <!-- Description ===================================================== -->
    <h4 style="color: #00004d; text-align: center; padding: 40px;font-size:25px;">
      This part of the pipeline is for merging multiple association studies <br> (GWAS, EWAS, TWAS, PWAS, or MWAS) into a single Meta MSEA.
    </h4>
  </div>
  <!--Start ssea Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_SSEA"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox' style="display: none;"></div>
  <!--End ssea Tutorial --------------------------------------->


</div>
<!--End of grid container --->

<!-- Description ============Start table========================================= -->
<div class="table-responsive" style="overflow: visible;">
  <!--Make table responsive--->
  <table class="table table-bordered" style="text-align: center" ; id="SSEAmaintable">
    <thead>
      <tr>
        <!--First row of table------------Column Headers------------------------------>
        <th>Type of File</th>
        <th class="uploadwidth">Upload/Select File</th>
        <th name="val_SSEA">Sample File Format</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <!--Second row of table------------------------------------------>
        <td data-column="File type &#xa;">Marker Association File <br>

          <div>
            <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>Marker-disease association summary results including Marker IDs and –log10 transformed association p-values. Marker types can be SNPs, methylation loci, transcripts, proteins, metabolites, etc. (sample file format to your right).
          </div>


        </td>
        <!--Second row|first column of table------------------------------------------>
        <td data-column="Upload/Select File &#xa;">
          <!--Second row|second column of table------------------------------------------>

          <!--Start MDF Upload ----------------------------->
          <div id="Selectupload" class="selectholder SSEA" align="center">
            <!--Start MDF Upload FORM---------------------------------->


            <select class="SSEA" name="formChoice" size="1" id="marker_association">
              <option value="0">Please select option</option>
              <option value="private_data">Upload your association data</option>
              <option value="Resources/sample_GWAS/Sample_human_HDL_cholesterol_GWAS.txt">Sample Human GWAS</option>
              <option value="Resources/sample_GWAS/AD_IGAP.txt">Alzheimer's disease GWAS</option>
              <option value="Resources/sample_GWAS/EAGLE_ADHD.txt">ADHD GWAS</option>
              <option value="Resources/sample_GWAS/SAGE_AlcoholDependence.txt">Alcohol Dependence GWAS</option>
              <option value="Resources/sample_GWAS/GIANT_BMIall.txt">Body mass index GWAS</option>
              <option value="Resources/sample_GWAS/RashkinS_BreastCancer.txt">Breast Cancer GWAS</option>
              <option value="Resources/sample_GWAS/CARDIOGRAM_CAD.txt">Coronary Artery Disease GWAS</option>
              <option value="Resources/sample_GWAS/MAGIC.fastingglucose.txt">Fasting Glucose GWAS</option>
              <option value="Resources/sample_GWAS/ShahS_HeartFailure.txt">Heart Failure GWAS</option>
              <option value="Resources/sample_GWAS/glgc.hdl.txt">GLGC HDL GWAS</option>
              <option value="Resources/sample_GWAS/glgc.ldl.txt">GLGC LDL GWAS</option>
              <option value="Resources/sample_GWAS/ColemanJ_MDD.txt">Major Depressive Disorder GWAS</option>
              <option value="Resources/sample_GWAS/TimmersP_Lifespan.txt">Parental Lifespan GWAS</option>
              <option value="Resources/sample_GWAS/IPDGC_PD.txt">Parkinson's Disease GWAS</option>
              <option value="Resources/sample_GWAS/GWAS_Psoriasis.txt">Psoriasis GWAS</option>
              <option value="Resources/sample_GWAS/Pairo-CastineiraE_COVID.txt">Severe illness in COVID GWAS</option>
              <option value="Resources/sample_GWAS/PGC_Schizophrenia.txt">Schizophrenia GWAS</option>
              <option value="Resources/sample_GWAS/MalikR_Stroke.txt">Stroke GWAS</option>
              <option value="Resources/sample_GWAS/WangY_SLE.txt">Systemic Lupus Erythematosus GWAS</option>
              <option value="Resources/sample_GWAS/DIAGRAM_T2D.txt">Type 2 Diabetes GWAS</option>
              <option value="Resources/sample_GWAS/glgc.tc.txt">GLGC Total Cholesterol GWAS</option>
              <option value="Resources/sample_GWAS/glgc.tg.txt">GLGC Triglycerides GWAS</option>
              <option value="Resources/sample_GWAS/Mouse_Sample_GWAS.txt">Sample Mouse GWAS</option>
            </select>
            <br>
            <?php $fpathloci = $fpath . "MARKER";
            if (file_exists($fpathloci)) {

              $marker_file = file_get_contents($fpathloci);
              $split_file = explode("/", $marker_file);
              $uploaded_association = $split_file[2];
            } else {
              $uploaded_association = 0;
            }
            ?>

            <!-- Mapping Association File Upload div --->

          </div> <!-- End Select upload div---->



          <div id="MAFupload" style="display: none;">

            <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

            <div class="input-file-container" name="Marker Association File" style="width: fit-content;">
              <input class="input-file" id="MAFskippeduploadInput" name="MAFuploadedfile" type="file" accept="text/plain" data-show-preview="false">
              <label id="MAFlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file ...</label>
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
          </div> <!-- End of upload div--->

          <div class="alert-SSEA" id="alert1"></div>
          <!--Div to alert user of certain comment (i.e. success) -->


        </td>
        <td data-column="Sample Format &#xa;" name="val1_MAF">
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
                  <td data-column="MARKER(Header): ">rs4747841</td>
                  <td data-column="VALUE(Header): ">0.1452</td>

                </tr>
                <tr>
                  <td data-column="MARKER(Header): ">rs4749917</td>
                  <td data-column="VALUE(Header): ">0.1108</td>

                </tr>
                <tr>
                  <td data-column="MARKER(Header): ">rs737656</td>
                  <td data-column="VALUE(Header): ">1.3979</td>

                </tr>
              </tbody>
            </table>
            <p>A <strong>TAB</strong> deliminated text file that contains marker to trait associations. UTF-8/ASCII encoded files recommended. Sample files for all inputs can be found <a href="samplefiles.php">here</a>.</p>
          </div>





        </td>
        <!--End MDF Sample File Format -->



      </tr>
      <!--End MDF Row -->
      <tr>
        <!--Start Mapping File Row (3rd column) --------------------------------------->


        <td data-column="File type &#xa;">Marker Mapping File

          <div>
            <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>Gene-marker mapping file that links genomic markers to genes. For GWAS, the most commonly used mapping is based on genomic distance (e.g., 10 kb, 20 kb, 50 kb), which is provided on the web server. A data-driven function-based mapping is more preferred if available.
          </div>

        </td>
        <!--Third row|first column of table------------------------------------------>
        <td data-column="Upload/Select File &#xa;">
          <!--Third row|second column of table------------------------------------------>
          <!--Start MMF Upload ----------------------------->
          <div id="Selectupload2">
            <!--Start MMF select FORM------------------------------ -->

            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#example-modal-modal" id="mapping_btn">Please select option</button>
            <div class="modal fade" id="example-modal-modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Please select mapping file(s)</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  </div>
                  <div class="modal-body">
                    <select class="SSEA" name="formChoice2" size="1" id="mapping_file" multiple="multiple">
                      <optgroup class="only_one_mapping" label="Only One Mapping file can be selected">
                        <option value="Resources/mappings/gene2loci.010kb.txt">Chromosomal distance 10Kb</option>
                        <option value="Resources/mappings/gene2loci.020kb.txt">Chromosomal distance 20Kb</option>
                        <option value="Resources/mappings/gene2loci.050kb.txt">Chromosomal distance 50Kb</option>
                        <option value="Resources/GTEx_v8_eQTL/combined_49esnps.txt">GTEx 49 Combined eQTLs</option>
                        <option value="Resources/GTEx_v8_sQTL/combined_49ssnps.txt">GTEx 49 Combined sQTLs</option>
                        <option value="Resources/mappings/combined_eqtl_sqtl_pqtl_distance.txt">Combined eQTLs, sQTLs, and pQTLs</option>
                      </optgroup>
                      <optgroup class="multiple_mapping" label="Multiple (up to 5) Mapping file(s) can be selected">
                        <option value="Resources/mappings/gene2loci.regulome.txt">Regulome mapping</option>
                        <option value="Resources/mappings/Mouse_Sample_Locus_Mapping.txt">Mouse GWAS Mapping</option>
                        <option value="Resources/GTEx_v8_eQTL/Adipose_Subcutaneous.txt">GTEx Adipose Subcutaneous eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Adipose_Visceral_Omentum.txt">GTEx Adipose Visceral Omentum eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Adrenal_Gland.txt">GTEx Adrenal Gland eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Artery_Aorta.txt">GTEx Artery Aorta eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Artery_Coronary.txt">GTEx Artery Coronary eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Artery_Tibial.txt">GTEx Artery Tibial eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Amygdala.txt">GTEx Brain Amygdala eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Anterior_cingulate_cortex_BA24.txt">GTEx Brain Anterior cingulate cortex BA24 eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Caudate_basal_ganglia.txt">GTEx Brain Caudate basal ganglia eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Cerebellar_Hemisphere.txt">GTEx Brain Cerebellar Hemisphere eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Cerebellum.txt">GTEx Brain Cerebellum eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Cortex.txt">GTEx Brain Cortex eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Frontal_Cortex_BA9.txt">GTEx Brain Frontal Cortex BA9 eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Hippocampus.txt">GTEx Brain Hippocampus eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Hypothalamus.txt">GTEx Brain Hypothalamus eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Nucleus_accumbens_basal_ganglia.txt">GTEx Brain Nucleus accumbens basal ganglia eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Putamen_basal_ganglia.txt">GTEx Brain Putamen basal ganglia eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Spinal_cord_cervical_c-1.txt">GTEx Brain Spinal cord cervical c-1 eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Brain_Substantia_nigra.txt">GTEx Brain Substantia nigra eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Breast_Mammary_Tissue.txt">GTEx Breast Mammary Tissue eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Cells_Cultured_fibroblasts.txt">GTEx Cells Cultured fibroblasts eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Cells_EBV-transformed_lymphocytes.txt">GTEx Cells EBV-transformed lymphocytes eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Colon_Sigmoid.txt">GTEx Colon Sigmoid eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Colon_Transverse.txt">GTEx Colon Transverse eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Esophagus_Gastroesophageal_Junction.txt">GTEx Esophagus Gastroesophageal Junction eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Esophagus_Mucosa.txt">GTEx Esophagus Mucosa eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Esophagus_Muscularis.txt">GTEx Esophagus Muscularis eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Heart_Atrial_Appendage.txt">GTEx Heart Atrial Appendage eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Heart_Left_Ventricle.txt">GTEx Heart Left Ventricle eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Kidney_Cortex.txt">GTEx Kidney Cortex eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Liver.txt">GTEx Liver eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Lung.txt">GTEx Lung eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Minor_Salivary_Gland.txt">GTEx Minor Salivary Gland eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Muscle_Skeletal.txt">GTEx Muscle Skeletal eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Nerve_Tibial.txt">GTEx Nerve Tibial eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Ovary.txt">GTEx Ovary eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Pancreas.txt">GTEx Pancreas eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Pituitary.txt">GTEx Pituitary eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Prostate.txt">GTEx Prostate eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Skin_Not_Sun_Exposed_Suprapubic.txt">GTEx Skin Not Sun Exposed Suprapubic eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Skin_Sun_Exposed_Lower_leg.txt">GTEx Skin Sun Exposed Lower leg eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Small_Intestine_Terminal_Ileum.txt">GTEx Small Intestine Terminal Ileum eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Spleen.txt">GTEx Spleen eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Stomach.txt">GTEx Stomach eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Testis.txt">GTEx Testis eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Thyroid.txt">GTEx Thyroid eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Uterus.txt">GTEx Uterus eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Vagina.txt">GTEx Vagina eQTL</option>
                        <option value="Resources/GTEx_v8_eQTL/Whole_Blood.txt">GTEx Whole Blood eQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Adipose_Subcutaneous.txt">GTEx Adipose Subcutaneous sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Adipose_Visceral_Omentum.txt">GTEx Adipose Visceral Omentum sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Adrenal_Gland.txt">GTEx Adrenal Gland sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Artery_Aorta.txt">GTEx Artery Aorta sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Artery_Coronary.txt">GTEx Artery Coronary sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Artery_Tibial.txt">GTEx Artery Tibial sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Amygdala.txt">GTEx Brain Amygdala sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Anterior_cingulate_cortex_BA24.txt">GTEx Brain Anterior cingulate cortex BA24 sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Caudate_basal_ganglia.txt">GTEx Brain Caudate basal ganglia sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Cerebellar_Hemisphere.txt">GTEx Brain Cerebellar Hemisphere sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Cerebellum.txt">GTEx Brain Cerebellum sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Cortex.txt">GTEx Brain Cortex sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Frontal_Cortex_BA9.txt">GTEx Brain Frontal Cortex BA9 sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Hippocampus.txt">GTEx Brain Hippocampus sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Hypothalamus.txt">GTEx Brain Hypothalamus sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Nucleus_accumbens_basal_ganglia.txt">GTEx Brain Nucleus accumbens basal ganglia sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Putamen_basal_ganglia.txt">GTEx Brain Putamen basal ganglia sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Spinal_cord_cervical_c-1.txt">GTEx Brain Spinal cord cervical c-1 sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Brain_Substantia_nigra.txt">GTEx Brain Substantia nigra sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Breast_Mammary_Tissue.txt">GTEx Breast Mammary Tissue sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Cells_Cultured_fibroblasts.txt">GTEx Cells Cultured fibroblasts sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Cells_EBV-transformed_lymphocytes.txt">GTEx Cells EBV-transformed lymphocytes sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Colon_Sigmoid.txt">GTEx Colon Sigmoid sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Colon_Transverse.txt">GTEx Colon Transverse sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Esophagus_Gastroesophageal_Junction.txt">GTEx Esophagus Gastroesophageal Junction sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Esophagus_Mucosa.txt">GTEx Esophagus Mucosa sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Esophagus_Muscularis.txt">GTEx Esophagus Muscularis sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Heart_Atrial_Appendage.txt">GTEx Heart Atrial Appendage sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Heart_Left_Ventricle.txt">GTEx Heart Left Ventricle sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Kidney_Cortex.txt">GTEx Kidney Cortex sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Liver.txt">GTEx Liver sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Lung.txt">GTEx Lung sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Minor_Salivary_Gland.txt">GTEx Minor Salivary Gland sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Muscle_Skeletal.txt">GTEx Muscle Skeletal sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Nerve_Tibial.txt">GTEx Nerve Tibial sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Ovary.txt">GTEx Ovary sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Pancreas.txt">GTEx Pancreas sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Pituitary.txt">GTEx Pituitary sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Prostate.txt">GTEx Prostate sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Skin_Not_Sun_Exposed_Suprapubic.txt">GTEx Skin Not Sun Exposed Suprapubic sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Skin_Sun_Exposed_Lower_leg.txt">GTEx Skin Sun Exposed Lower leg sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Small_Intestine_Terminal_Ileum.txt">GTEx Small Intestine Terminal Ileum sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Spleen.txt">GTEx Spleen sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Stomach.txt">GTEx Stomach sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Testis.txt">GTEx Testis sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Thyroid.txt">GTEx Thyroid sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Uterus.txt">GTEx Uterus sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Vagina.txt">GTEx Vagina sQTL</option>
                        <option value="Resources/GTEx_v8_sQTL/Whole_Blood.txt">GTEx Whole Blood sQTL</option>
                      </optgroup>


                    </select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <!-- Marker Mapping File Upload div --->
          </div> <!-- End Selectupload2 div---->
          <div id="MMFupload1">
            <div style="color: black;">or Upload your mapping file</div>
            <div style="color: black;">
              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>
              <div class="input-file-container" name="Marker Mapping File" style="width: fit-content;">
                <input class="input-file" id="MMFskippeduploadInput" name="MMFuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="MMFlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="MMFprogressbar" class="progress active" style='display: none;'>
                  <div id="MMFprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="MMFprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="MMFfilereturn" class="file-return"><?php if ($uploaded_mapping !== 0) {
                                                            print($uploaded_mapping);
                                                          } ?></p>
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
          </div>
          <!--Div to alert user of certain comment (i.e. success) -->
          <br>
          <!-- <div id="MMFupload2" style="display:none;">
            <div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Successful!</strong></div>
          </div> -->
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
          </div>

        </td>
        <!--Fourth row|first column (Type of File)-------------------->
        <td data-column="Upload/Select File &#xa;">
          <!--Fourth row|second column (Upload/Select File) -------------------->
          <div class="selectholder SSEA" align="center">
            <select class="SSEA" name="formChoice3" size="1" id="mdf">
              <option value="0">Please select option</option>
              <option value="private_data">Upload your Correlation File</option>
              <option value="Resources/LD_files/ld70.ceu.txt">CEU LD70</option>
              <option value="Resources/LD_files/ld50.ceu.txt">CEU LD50</option>
              <option value="Resources/LD_files/ld30.ceu.txt">CEU LD30</option>
              <option value="Resources/LD_files/ld20.ceu.txt">CEU LD20</option>
              <option value="Resources/LD_files/ld10.ceu.txt">CEU LD10</option>
              <option value="Resources/LD_files/ld70.acb.txt">ACB LD70</option>
              <option value="Resources/LD_files/ld50.acb.txt">ACB LD50</option>
              <option value="Resources/LD_files/ld70.asw.txt">ASW LD70</option>
              <option value="Resources/LD_files/ld50.asw.txt">ASW LD50</option>
              <option value="Resources/LD_files/ld70.cdx.txt">CDX LD70</option>
              <option value="Resources/LD_files/ld50.cdx.txt">CDX LD50</option>
              <option value="Resources/LD_files/ld70.chb.txt">CHB LD70</option>
              <option value="Resources/LD_files/ld50.chb.txt">CHB LD50</option>
              <option value="Resources/LD_files/ld70.chs.txt">CHS LD70</option>
              <option value="Resources/LD_files/ld50.chs.txt">CHS LD50</option>
              <option value="Resources/LD_files/ld70.clm.txt">CLM LD70</option>
              <option value="Resources/LD_files/ld50.clm.txt">CLM LD50</option>
              <option value="Resources/LD_files/ld70.esn.txt">ESN LD70</option>
              <option value="Resources/LD_files/ld50.esn.txt">ESN LD50</option>
              <option value="Resources/LD_files/ld70.fin.txt">FIN LD70</option>
              <option value="Resources/LD_files/ld50.fin.txt">FIN LD50</option>
              <option value="Resources/LD_files/ld70.gbr.txt">GBR LD70</option>
              <option value="Resources/LD_files/ld50.gbr.txt">GBR LD50</option>
              <option value="Resources/LD_files/ld70.gih.txt">GIH LD70</option>
              <option value="Resources/LD_files/ld50.gih.txt">GIH LD50</option>
              <option value="Resources/LD_files/ld70.gwd.txt">GWD LD70</option>
              <option value="Resources/LD_files/ld50.gwd.txt">GWD LD50</option>
              <option value="Resources/LD_files/ld70.ibs.txt">IBS LD70</option>
              <option value="Resources/LD_files/ld50.ibs.txt">IBS LD50</option>
              <option value="Resources/LD_files/ld70.itu.txt">ITU LD70</option>
              <option value="Resources/LD_files/ld50.itu.txt">ITU LD50</option>
              <option value="Resources/LD_files/ld70.jpt.txt">JPT LD70</option>
              <option value="Resources/LD_files/ld50.jpt.txt">JPT LD50</option>
              <option value="Resources/LD_files/ld70.khv.txt">KHV LD70</option>
              <option value="Resources/LD_files/ld50.khv.txt">KHV LD50</option>
              <option value="Resources/LD_files/ld70.lwk.txt">LWK LD70</option>
              <option value="Resources/LD_files/ld50.lwk.txt">LWK LD50</option>
              <option value="Resources/LD_files/ld70.msl.txt">MSL LD70</option>
              <option value="Resources/LD_files/ld50.msl.txt">MSL LD50</option>
              <option value="Resources/LD_files/ld70.mxl.txt">MXL LD70</option>
              <option value="Resources/LD_files/ld50.mxl.txt">MXL LD50</option>
              <option value="Resources/LD_files/ld70.pel.txt">PEL LD70</option>
              <option value="Resources/LD_files/ld50.pel.txt">PEL LD50</option>
              <option value="Resources/LD_files/ld70.pjl.txt">PJL LD70</option>
              <option value="Resources/LD_files/ld50.pjl.txt">PJL LD50</option>
              <option value="Resources/LD_files/ld70.pur.txt">PUR LD70</option>
              <option value="Resources/LD_files/ld50.pur.txt">PUR LD50</option>
              <option value="Resources/LD_files/ld70.stu.txt">STU LD70</option>
              <option value="Resources/LD_files/ld50.stu.txt">STU LD50</option>
              <option value="Resources/LD_files/ld70.tsi.txt">TSI LD70</option>
              <option value="Resources/LD_files/ld50.tsi.txt">TSI LD50</option>
              <option value="Resources/LD_files/ld70.yri.txt">YRI LD70</option>
              <option value="Resources/LD_files/ld50.yri.txt">YRI LD50</option>
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
          <div class="alert-SSEA" id="alert3"></div>

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
      <!--End Marker Mapping File Row -->
    </tbody>
  </table>
  <!--End of SSEA maintable -->
</div>
<!--End of responsive div for SSEA maintable --->

<!-------------------------------------------------Start of SSEA Parameters table ----------------------------------------------------->
<div class="table-responsive" style="overflow: visible;">
  <!--Make table responsive--->
  <table class="table table-bordered" style="text-align: center;" id="SSEAparameterstable">
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
          <div class="selectholder SSEA"> <select class="btn dropdown-toggle btn-light" name="permuttype" id="permuttype" size="1">
              <option value="gene" selected>Gene</option>
              <option value="marker">Marker</option>
            </select></div>
        </td>



      </tr>


      <tr name="Max Overlap for Merging Gene Mapping">

        <td>Max Overlap for Merging Gene Mapping:</td>

        <td name="val4"> <input class='sseaparameter' id="maxoverlap" type="text" name="gene_overlap" value="0.33">
        </td>


      </tr>

      <tr name="Number of Permutations">

        <td>Number of Permutations:</td>

        <td name="val6"><input class='sseaparameter' id="sseanperm" type="text" name="permu" value="2000"></td>

      </tr>

      <tr name="MSEA FDR cutoff">

        <td>
          MSEA to KDA export FDR cutoff:
          <div class="alert alert-warning" style="margin: 0 auto; width: 90%;margin-top: 10px;">
            <i class="icon-warning-sign" style="margin-right: 6px;font-size: 12px;"></i>This is the cutoff (in percentage) used to export individual MSEA results (significant modules) to KDA <b>(for this specific study)</b>. The module will also need to pass the selected meta FDR on the 'Review Files' tab to be included in the KDA analysis (to be included in the KDA analysis, the module has to pass both this FDR cutoff value and the meta FDR which is set on the 'Review Files' tab).
          </div>
        </td>

        <td name="val7"><input class='MSEAparameter' type="text" name="sseafdr" id="sseafdr" value="50"></td>


      </tr>

    </tbody>
  </table>
</div>
<!--End of responsive div for parameters table -->
<br>
<!-------------------------------------------------End of SSEA Parameters table ----------------------------------------------------->
<!-------------------------------------------------Start Review button ----------------------------------------------------->
<div id="Validatediv_SSEA" style="text-align: center;">
  <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_SSEA">Finish and Review</button>
  <div style="text-align: center;" id="preload"></div>
  <!--End of SSEA form (This combines the two inputs together) ---->
</div>
<!-------------------------------------------------End Review button ----------------------------------------------------->



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
            <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;"> Marker-disease association summary results including Marker IDs and –log10 transformed association p-values. Marker types can be SNPs, methylation loci, transcripts, proteins, metabolites, etc. (sample file format to your right).
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
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-------------------End modal -------------------------->

  <!---------------------------------------Modal information for MDF -------------------------------------------------------->
  <div id="GSETinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 35px;">Gene Sets File</h4>
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


  <!---------------------------------------Modal information for MDF -------------------------------------------------------->
  <div id="GSETDinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 35px;">Gene Sets Descriptions File</h4>
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



  <script type="text/javascript" src="include/multiselect/docs/js/bootstrap.bundle-4.5.2.min.js"></script>
  <script type="text/javascript" src="include/multiselect/docs/js/prettify.min.js"></script>
  <script type="text/javascript" src="include/multiselect/dist/js/bootstrap-multiselect.js"></script>
  <script type="text/javascript">
    /**********************************************************************************************
Javascript functions/scripts (These are inlined because it was easier to do)
You can technically extract it and just call it externally if you want to keep the php page cleaner, but not needed
***********************************************************************************************/

    var session_id = "<?php echo $sessionID; ?>";
    var meta_session_id = "<?php echo $meta_sessionID; ?>";
    var file
    var marker_association_file = null;
    var mapping_file = [];
    //var module_set_file = null;
    //var module_info_file = null;
    var permtype = null;
    //var maxgene = null;
    //var mingene = null;
    var maxoverlap = null;
    //var minoverlap = null;
    var sseanperm = null;
    var sseafdr = null;
    var mdffile = null;
    var mdf_ntop = null;
    var MMFConvert = null;
    var file_upload_target_path="<?php echo $FILE_UPLOAD;?>";
    function basename(path) {
      return path.split('/').reverse()[0];
    }

    function SSEAdone() //This function gets the review table for MSEA
    {
      $.ajax({
        url: "META_moduleprogress.php",
        method: "GET",
        data: {
          sessionID: session_id,
          metasessionID: meta_session_id,
          marker_association: marker_association_file,
          mapping: mapping_file,
          //module: module_set_file,
          //module_info: module_info_file,
          perm_type: permtype,
          //max_gene: maxgene,
          //min_gene: mingene,
          maxoverlap: maxoverlap,
          //minoverlap: minoverlap,
          sseanperm: sseanperm,
          sseafdr: sseafdr,
          mdf: mdffile,
          MMFConvert: MMFConvert,
          enrichment: "GWAS",
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

    // $('#SSEAdataform').submit(function(e) {

    //   e.preventDefault();
    //   var form_data = new FormData(document.getElementById('SSEAdataform'));
    //   form_data.append("sessionID", string);

    //   $.ajax({
    //     'url': 'METASSEA_parameters.php',
    //     'type': 'POST',
    //     'data': form_data,
    //     processData: false,
    //     contentType: false,
    //     'success': function(data) {

    //     }
    //   });


    // });
    /////////////////////////////////////////////End submit function for SSEA form//////////////////////////////////////////////////////

    var function_for_display_animation = function() {
      $("#preload").html(`<img src='include/pictures/ajax-loader.gif' />`);
    }


    var idarray = ['MAFskippeduploadInput'];
    var errorlist = [];

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

    if (mapping_file.length == 0) {
      errorlist.push('Marker Mapping File is not selected!');
    }

    ///////////////Start Validation/REVIEW button -- Function for clicking 'Click to review button'///////////////////////////////////
    $("#Validatebutton_SSEA").on('click', function() {
      // var select = $("select[name='formChoice_SSEA'] option:selected").index()
      //var select2 = $("select[name='formChoice2_SSEA'] option:selected").index()
      var select3 = $("select[name='formChoice'] option:selected").index() // association
      //var select4 = $("select[name='formChoice2'] option:selected").index() // mapping
      //var selectarray = [select3, select4, select, select2];
      var selectarray = [select3];

      var errorlist = [];
      selectarray.forEach(myFunction);

      $('.SSEAparameter').each(function() {
        if ($(this).val() == "") {
          errorlist.push($(this).parent().parent().attr('name') + ' is empty!');

        }
      });
      if (errorlist.length === 0) {
        $(this).html('Please wait ...')
          .attr('disabled', 'disabled');
        //$("#SSEAdataform").submit();
        function_for_display_animation();

        setTimeout(function() {
          MMFConvert = $("#MMFConvert").val();

          if ($("#marker_association").val() != "private_data") {
            marker_association_file = $("#marker_association").val();
          }
          /*
          if ($("#mapping_file").val() != "private_data") {
            mapping_file = $("#mapping_file").val();
          }
          */

          permtype = $("#permuttype").val();
          //maxgene = $("#maxgene").val();
          //mingene = $("#mingene").val();
          maxoverlap = $("#maxoverlap").val();
          //minoverlap = $("#minoverlap").val();
          sseanperm = $("#sseanperm").val();
          sseafdr = $("#sseafdr").val();
          mdffile = $("#mdf").val();
          mdf_ntop = $("#percent_markers").val();
          $('#myMETA_body').empty();
          SSEAdone();
        }, 500);



      } else {
        var result = errorlist.join("\n");
        //alert(result);
        $('#errorp_SSEA').html(result);
        $("#errormsg_SSEA").fadeTo(2000, 500).slideUp(500, function() {
          $("#errormsg_SSEA").slideUp(500);
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

    $("#minoverlap, #maxoverlap").inputFilter(function(value) {
      return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
    });
    $("#sseafdr").inputFilter(function(value) {
      return /^\d*$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 100));
    });

    ///////////////////////////////////////////////End Validation/REVIEW button/////////////////////////////////////////////////////////////

    // set up select boxes
    $('.selectholder.SSEA').each(function() {
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
          $('.activeselectholder.SSEA').each(function() {
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
    $('.selectholder.SSEA .selectdropdown span').click(function() {
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
      var container = $(".selectholder.SSEA");

      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        $('.activeselectholder.SSEA').each(function() {
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

    var myTutButton_SSEA = document.getElementById("myTutButton_SSEA");
    var val_SSEA = 0;

    //begin function for when button is clicked-------------------------------------------------------------->
    myTutButton_SSEA.addEventListener("click", function() {

      //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
      var $this_SSEA = $(this);

      //If tutorial is already opened yet, then do this-------------------------------------------------------------->
      if ($this_SSEA.data('clicked')) {


        $('.tutorialbox').hide();

        $('#SSEAparameterstable').find('tr').each(function() {
          $(this).find('td[name="tut"]').eq(-1).remove();
          $(this).find('th[name="tut"]').eq(-1).remove();
        });


        $this_SSEA.data('clicked', false);
        val_SSEA = val_SSEA - 1;
        $("#myTutButton_SSEA").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

      }

      //If tutorial is not opened yet, then do this-------------------------------------------------------------->
      else {
        $this_SSEA.data('clicked', true);
        val_SSEA = val_SSEA + 1; //val counter to not duplicate prepend function


        if (val_SSEA == 1) //Only prepend the tutorial once
        {


          $('#SSEAparameterstable').find('td[name="val1"]').eq(-1).after(`
                                <td name="tut">
                                <strong>Permutation type</strong>: Gene or marker based, to estimate statistical significance p-values. <br>
                                    <strong>Default value</strong>: "Gene" is the recommended for GWAS but users can choose "Marker" which generally leads to more significant results but may be biased towards genes with many markers (SNPs).
                                 </td>

                                `);

          $('#SSEAparameterstable').find('td[name="val4"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Max Overlap in merging Gene Mapping</strong>:
                                    Gene merging is to reduce artefacts from shared markers.<br>
                                    <strong>Default Value</strong>: 0.33. Genes sharing markers over this ratio will be merged.
                                </td>`);


          $('#SSEAparameterstable').find('td[name="val6"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Number of Permutations</strong>: the number of gene or marker permutations conudcted in the MSEA analysis <br>
                                   <strong>Options</strong>: 1000 to 20,000 (for publication, recommend >= 10,000) <br>
                                    <strong>Default value</strong>: 2000
                                </td>`);

          $('#SSEAparameterstable').find('td[name="val7"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>MSEA to KDA export FDR cutoff</strong>: FDR should be within the specified FDR cutoff. <br>
                                    <strong>Options</strong>: Between 0 to 100 (100 is 100%) <br>
                                    <strong>Default value</strong>: 50. For inclusion in KDA, the module has to pass this FDR cutoff for this dataset, the other FDR cutoffs for other datasets, and the Meta-MSEA Meta FDR cutoff. To include all modules, choose 100.
                                </td>`);


          $('#SSEAparameterstable').find('th[name="val"]').eq(-1).after('<th name="tut">Comments</th>');

          $('.tutorialbox').show();
          $('.tutorialbox').html('The purpose of the pipeline is to take an association dataset for a given disease or phenotype from the user as input and integrate the association data with functional genomics information, pathways, and gene networks to derive pathways, gene networks and key regulatory genes for the disease or phenotype.');





        }
        $("#myTutButton_SSEA").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
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

    $('select.SSEA').on('change', function() {
      var select = $(this).find('option:selected').index();
      if (select != 1)
        $(this).parent().next().hide();

      if (select == 1)
        $(this).parent().next().show();

      if (select > 1)
        $(this).parent().nextAll(".alert-SSEA").eq(0).html(successalert).hide().fadeIn(300);
      else if (select == 1)
        $(this).parent().nextAll(".alert-SSEA").eq(0).html(uploadalert).hide().fadeIn(300);
      else
        $(this).parent().nextAll(".alert-SSEA").eq(0).empty();
    });

    $('select.SSEA').each(function() {
      $(this).trigger('change');
    });

    $("#Selectupload2").on("change", function() {
      var select = $(this).find('option:selected').index();
        $("#alertMMF").show();
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
    //MARKER ASSOCIATION FILE UPLOAD EVENT HANDLER
    $("#MAFskippeduploadInput").on("change", function() {
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
              marker_association_file = "Resources/meta_temp/"+basename(fullPath);
              var filename = fullPath.replace("/^.*[\\\/]/", "").replace(session_id, "");
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


    //MAPPING UPLOAD EVENT HANDLER
    $("#MMFskippeduploadInput").on("change", function(event) {
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
              //mapping_file = fullPath.replace("./Data/Pipeline/", "");
              mapping_file.push("Resources/meta_temp/"+basename(fullPath));
              var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
              $('#MMFfilereturn').html(filename);
              //$('#MMF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
              $('#alertMMF').show();
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


    $("#GSETDlabelname").on("keydown", function(event) {
      if (event.keyCode == 13 || event.keyCode == 32) {
        $("#GSETDuploadInput").focus();
      }
    });
    $("#GSETDlabelname").on("click", function(event) {
      $("#GSETDuploadInput").focus();
    });

    //MDF upload event handler
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
              mdffile = "Resources/meta_temp/"+basename(fullPath);
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


    
    $(document).ready(function() {
      // $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + session_id);
      // $('#session_id').css("padding", "17px 30px");
      window.prettyPrint() && prettyPrint();
      $('#mapping_file').multiselect({
        enableCaseInsensitiveFiltering: true,
        maxHeight: 600,
        onChange: function(option, checked) {
          // Get selected options.
          console.log(option.val());
          if (option.parent().attr('class') == "only_one_mapping") {
            $('#mapping_file').multiselect('deselectAll', false);
            $("#mapping_file").multiselect('select', option.val());

          } else {
            $('#mapping_file').multiselect('deselect', "Resources/mappings/gene2loci.010kb.txt");
            $('#mapping_file').multiselect('deselect', "Resources/mappings/gene2loci.020kb.txt");
            $('#mapping_file').multiselect('deselect', "Resources/mappings/gene2loci.050kb.txt");
            $('#mapping_file').multiselect('deselect', "Resources/GTEx_v8_eQTL/combined_49esnps.txt");
            $('#mapping_file').multiselect('deselect', "Resources/GTEx_v8_sQTL/combined_49ssnps.txt");
          }

          var selectedOptions = $('#mapping_file option:selected');
          mapping_file = [];
          $(selectedOptions).each(function(index, selected) {
            mapping_file.push($(this).val());
          });
          if (selectedOptions.length == 0) {
            $("#MMFupload1").show();
            $("#alertMMF").hide();
            $("#mapping_btn").html("Please select option");
          } else {
            $("#MMFupload1").hide();
            $("#alertMMF").show();
            $("#mapping_btn").html(selectedOptions.length + " selected");
          }
          if (selectedOptions.length >= 5) {
            // Disable all other checkboxes.
            var nonSelectedOptions = $('#mapping_file option').filter(function() {
              return !$(this).is(':selected');
            });

            nonSelectedOptions.each(function() {
              if ($(this).parent().attr('class') != "only_one_mapping") {
                var input = $('input[value="' + $(this).val() + '"]');
                input.parent().parent().prop('disabled', true);
                input.parent('.multiselect-option').addClass('disabled');
              }
            });
          } else {
            // Enable all checkboxes.
            $('#mapping_file option').each(function() {
              var input = $('input[value="' + $(this).val() + '"]');
              input.parent().parent().prop('disabled', false);
              input.parent('.multiselect-option').addClass('disabled');
            });
          }
        },
        buttonText: function(options) {
          if (options.length == 0) {
            return 'None selected';
          } else {
            var selected = 0;
            options.each(function() {
              selected += 1;
            });
            return selected + ' Selected';
          }
        }

      });
    });
  </script>