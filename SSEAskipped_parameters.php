<?php
//This parameters files is for when the user skips MDF


/* Initialize PHP variables
sessionID = the saved session 

GET = if the user enters the link directly
POST = if PHP enters the link

*/


if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}

if (isset($_POST['sessionID'])) {
  $sessionID = $_POST['sessionID'];
}

//get the paths of stored sessionID information
$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$fpostOut = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_MDFskipped_postdata.txt";

$FILE_UPLOAD=$ROOT_DIR."Resources/ssea_temp/";
/***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we create a txt file with the path information
 ***************************************/
//this variable will write into the .txt file
$session_write = NULL;

//if the sessionID does exist
if (file_exists($fsession)) {
  //then look for "Pipeline: GWAS" and change  to "Pipeline: GWASskipped"
  //then set the Mergeomics_path to 1 

  $lines = file($fsession);
  $result = NULL;

  foreach ($lines as $line) {
    if (substr($line, 0, 3) == 'Pip') {
      $result .= 'Pipeline:' . "\t" . "GWASskipped" . "\n";
    } else if (substr($line, 0, 3) == 'Mer') {
      $result .= 'Mergeomics_Path:' . "\t" . "1" . "\n";
    } else {
      $result .= $line;
    }
  }
  //put the contents back into the sessionID.txt
  file_put_contents($fsession, $result);
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



////Store some file path variables to be used later
$fpath = "./Data/Pipeline/Resources/ldprune_temp/$sessionID";

$pv = "";







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
<!-- Error message box (slides up and down) ===================================================== -->
<div id="errormsg_SSEA" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_SSEA" style="white-space: pre;"></p>
</div>
</div>




<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">

  <!--Start ssea Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_SSEA"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox' style="display: none;"></div>
  <!--End ssea Tutorial --------------------------------------->


</div>
<!--End of grid container --->

<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="SSEAskipped_parameters.php" name="select2" id="SSEAdataform">
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
          <td>Marker Association File <br>

            <div>
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>Marker-disease association summary results including Marker IDs and –log10 transformed association p-values. Marker types can be SNPs, methylation loci, transcripts, proteins, metabolites, etc. (sample file format to your right).
            </div>


          </td>
          <!--Second row|first column of table------------------------------------------>
          <td>
            <!--Second row|second column of table------------------------------------------>

            <!--Start MAF ----------------------------->
            <div id="Selectupload" class="selectholder SSEA" align="center">
              <!--Start MAF Select FORM---------------------------------->


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
            </div> <!-- End MAF Select div---->
            <!-- MAF File Upload div --->
            <div id="MAFupload" style="display: none;">
              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>
              <div class="input-file-container" name="Marker Association File" style="width: fit-content;">
                <input class="input-file" id="MAFskippeduploadInput" name="MAFuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="MAFlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="MAFprogressbar" class="progress active" style='display: none;'>
                  <div id="MAFprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="MAFprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="MAFfilereturn" class="file-return"><?php if ($uploaded_association !== 0) {
                                                            print($uploaded_association);
                                                          } ?></p>
                <span id='MAF_uploaded_file'><?php if ($uploaded_association !== 0) {
                                                echo nl2br('<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;margin:0px;"></i><strong>Success!</strong> </div>');
                                              } else {
                                                print("");
                                              } ?></span>
              </div>
            </div> <!-- End of upload div--->

            <div class="alert-SSEA" id="alert1"><?php if ($uploaded_association !== 0) {
                                                  echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                } else {
                                                  print("");
                                                } ?></div>
            <!--Div to alert user of certain comment (i.e. success) -->








          </td>
          <td name="val1_MAF">
            <!--Second row|third column of table------------------------------------------>
            <!--Start MAF Sample File Format -------------------->
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
                    <td>rs4747841</td>
                    <td>0.1452</td>

                  </tr>
                  <tr>
                    <td>rs4749917</td>
                    <td>0.1108</td>

                  </tr>
                  <tr>
                    <td>rs737656</td>
                    <td>1.3979</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that contains marker to trait associations. UTF-8/ASCII encoded files recommended. Sample files for all inputs can be found <a href="samplefiles.php">here</a>.</p>
            </div>





          </td>
          <!--End MAF Sample File Format -->



        </tr>
        <!--End MAF Row -->
        <!---------------------------Start Mapping File Row (3rd row) --------------------------------------->
        <tr>
          <!--Column 1: Title --->


          <td>Marker Mapping File

            <div>
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>Gene-marker mapping file that links genomic markers to genes. For GWAS, the most commonly used mapping is based on genomic distance (e.g., 10 kb, 20 kb, 50 kb), which is provided on the web server. A data-driven function-based mapping is more preferred if available.
            </div>

          </td>
          <!--Third row|first column of table------------------------------------------>
          <td>
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
                        <optgroup class="multiple_mapping" label="Multiple (up to 10) Mapping file(s) can be selected">
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


              <div class="alert-SSEA" id="alert2"><?php if ($uploaded_mapping !== 0) {
                                                    echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                  } else {
                                                    print("");
                                                  } ?></div>
            </div>
            <!--Div to alert user of certain comment (i.e. success) -->
            <br>
            <div id="MMFupload2" style="display:none;">
              <div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Successful!</strong></div>
            </div>

          </td>
          <!--End of MMF Upload column -------------------->
          <td name="val2_MMF">
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
                    <td>CDK6</td>
                    <td>rs10</td>

                  </tr>
                  <tr>
                    <td>AGER</td>
                    <td>rs1000</td>

                  </tr>
                  <tr>
                    <td>N4BP2</td>
                    <td>rs1000000</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that provides marker to gene mapping. UTF-8/ASCII encoded files recommended.</p>
            </div>
          </td>
          <!--End MMF Sample File Format -->
        </tr>
        <!--End Marker Mapping File Row -->
        <tr>
          <!--Third row of table------------------------------------------>
          <td>Gene Sets
            <div>
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>Functionally related gene sets such as co-regulation, shared response, co-localization on chromosomes, or participants of specific biological processes. Typical sources of gene sets includes canonical pathways such as Reactome and KEGG, or coexpression modules constructed using algorithms like weighted coexpression gene networks analysis (WGCNA).
            </div>

          </td>
          <!--Third row|first column of table------------------------------------------>
          <td>
            <!--Start Gene Set select FORM ----------------------------->
            <div id="Selectupload_GSET" class="selectholder SSEA" align="center">
              <select class="SSEA" name="formChoice_SSEA" size="1" id="module">
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
            </div>
            <!--End selectupload_Gset div-->
            <!--Start Gene Set upload div ------------->
            <div id="GSETupload" style="display: none;">
              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>
              <div class="input-file-container" name="Gene Sets File" style="width: fit-content;">
                <input class="input-file" id="GSETuploadInput" name="GSETuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="GSETlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> <?php if ($uploaded_module !== 0) {
                                                                                                                      print("Select another file?");
                                                                                                                    } else {
                                                                                                                      print("Select a file...");
                                                                                                                    } ?></label>
                <!--Progress bar ------------------------------>
                <div id="GSETprogressbar" class="progress active" style='display: none;'>
                  <div id="GSETprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="GSETprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="GSETfilereturn" class="file-return"><?php if ($uploaded_module !== 0) {
                                                              print($uploaded_module);
                                                            } ?></p>
                <span id='GSET_uploaded_file'><?php if ($uploaded_module !== 0) {
                                                echo nl2br('<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;margin:0px;"></i><strong>Success!</strong> </div>');
                                              } else {
                                                print("");
                                              } ?></span>
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
            </div> <!-- End of Gene SET upload div--->

            <!--Div to alert user of certain comment (i.e. success) -->
            <div class="alert-SSEA" id="alert_GSET"><?php if ($uploaded_module !== 0) {
                                                      echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                    } else {
                                                      print("");
                                                    } ?></div>


          </td>
          <!--Third row|second column of table------------------------------------------>
          <td name="val1_SSEA">
            <!--Start Third row|third column of table------------------------------------------>

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="Name of marker set (I.e. canonical pathway or co-expression module)">MODULE</a></th>
                    <th><a href="#">GENE</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Cell cycle</td>
                    <td>CDC16</td>

                  </tr>
                  <tr>
                    <td>Cell cycle</td>
                    <td>ANAPC1</td>

                  </tr>
                  <tr>
                    <td>WGCNA Brown</td>
                    <td>XRCC5</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that contains collections of pre-defined sets of genes that are functionally related. UTF-8/ASCII encoded files recommended.</p>
            </div>


          </td>
          <!--End Third row|third column of table------------------------------------------>

        </tr>
        <tr id="gsetd_row" style="display:none;">
          <td>Gene Sets Description
            <div>
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>To better annotate the gene sets in MSEA output, a description file for the gene sets is needed to specify the source of the gene set and a detailed description of the functional information used to group genes.
            </div>
          </td>
          <td>
            <!--Start Gene Set Description Select Input ----------------------------->
            <div id="Selectupload_GSETD" class="selectholder SSEA" align="center">
              <select class="SSEA" name="formChoice2_SSEA" size="1" id="module_info">
                <option value="0">Please select option</option>
                <option value="private_data">Upload Gene Sets descriptions</option>
                <option value="no" selected>No Gene Sets Description</option>
              </select>
            </div> <!-- End Gene Set Description input -->
            <!-- Start Gene Set Description UPLOAD div -->
            <div id="GSETDupload" style="display: none;">
              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>
              <div class="input-file-container" name="Gene Sets Description File" style="width: fit-content;">
                <input class="input-file" id="GSETDuploadInput" name="GSETDuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="GSETDlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i>Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="GSETDprogressbar" class="progress active" style='display: none;'>
                  <div id="GSETDprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="GSETDprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="GSETDfilereturn" class="file-return"><?php if ($uploaded_description !== 0) {
                                                              print($uploaded_description);
                                                            } ?></p>
                <span id='GSETD_uploaded_file'><?php if ($uploaded_description !== 0) {
                                                  echo nl2br('<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;margin:0px;"></i><strong>Success!</strong> </div>');
                                                } else {
                                                  print("");
                                                } ?></span>
              </div>
            </div> <!-- End of GEne SET description upload div--->

            <!--Div to alert user of certain comment (i.e. success) -->
            <div class="alert-SSEA" id="alert_GSETD"><?php if ($uploaded_description !== 0) {
                                                        echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                      } else {
                                                        print("");
                                                      } ?></div>


          </td>
          <td name="val2_SSEA">
            <!--Fourth row|Third column (Gene Set Description sample format) -------------------->

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="Name of marker set (I.e. canonical pathway or co-expression module)">MODULE</a></th>
                    <th><a href="#" tooltip="Source of marker set">SOURCE</a></th>
                    <th><a href="#" tooltip="Description of marker set">DESCR</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Cell cycle</td>
                    <td>KEGG</td>
                    <td>Mitotic cell cycle progression is accomplished through a reproducible sequence of events - S, M, G1, and G2 phases.</td>

                  </tr>
                  <tr>
                    <td>WGCNA Brown</td>
                    <td>WGCNA Liver Coexpression Module</td>
                    <td>Immune function</td>
                  </tr>
                  <tr>
                    <td>Proteasome Pathway</td>
                    <td>BioCarta</td>
                    <td>https://www.gsea-msigdb.org/gsea/msigdb/cards/ BIOCARTA_PROTEASOME_PATHWAY</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that contains detailed descriptions of the gene sets (i.e. full name of a biological pathway). UTF-8/ASCII encoded files recommended.</p>
            </div>


          </td>
        </tr>
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
        <!---------------Start of Permutation type input ------------->
        <tr>
          <td>Permutation type:</td>

          <td name="val1">
            <div class="selectholder SSEA">
              <select class="btn dropdown-toggle btn-light" id="permuttype" name="permuttype" size="1">
                <option value="locus">Marker</option>
                <option value="gene" selected>Gene</option>
              </select>
            </div>
          </td>



        </tr>
        <!---------------Start of Max Genes in Gene Sets input ------------->
        <tr name="Max Genes in Gene Sets">

          <td>Max Genes in Gene Sets:</td>

          <td name="val2">
            <input class='sseaparameter' type="text" id="maxgene" name="maxgene" value="500">

          </td>
        </tr>
        <!---------------Start of Min Genes in Gene Sets input ------------->
        <tr name="Min Genes in Gene Sets">
          <td>Min Genes in Gene Sets:</td>
          <td name="val3"><input class='sseaparameter' id="mingene" type="text" name="mingene" value="10">

          </td>

        </tr>
        <!---------------Start of Max Overlap for Merging Gene Mapping input ------------->
        <tr name="Max Overlap for Merging Gene Mapping">
          <td>Max Overlap for Merging Gene Mapping:</td>
          <td name="val4"> <input class='sseaparameter' id="maxoverlap" type="text" name="gene_overlap" value="0.33">

          </td>


        </tr>
        <!---------------Start of Min Overlap for Merging input ------------->
        <tr name="Min Overlap Allowed for Merging">

          <td>Min Overlap Allowed for Merging:</td>

          <td name="val5">
            <input class='sseaparameter' id="minoverlap" type="text" name="overlap" value="0.33">

          </td>


        </tr>
        <!---------------Start of Number of Permutations input ------------->
        <tr name=" Number of Permutations">

          <td>Number of Permutations:</td>

          <td name="val6"><input class='sseaparameter' id="sseanperm" type="text" name="permu" value="2000">
          </td>

        </tr>
        <!---------------Start of MSEA FDR cutoff input ------------->
        <tr name="MSEA FDR cutoff">

          <td>MSEA FDR cutoff:</td>

          <td name="val7">
            <input class='sseaparameter' id="sseafdr" type="text" name="sseafdr" value="25">

          </td>





        </tr>

      </tbody>
    </table>
  </div>
  <!--End of responsive div for parameters table -->
  <br>
  <!-------------------------------------------------End of SSEA Parameters table ----------------------------------------------------->
  <!-------------------------------------------------Start Review button ----------------------------------------------------->
  <div id="Validatediv_SSEA" style="text-align: center;">
    <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_SSEA">Click to Review</button>

</form>
<!--End of SSEA form (This combines the two inputs together) ---->
</div>
<!-------------------------------------------------End Review button ----------------------------------------------------->




<script type="text/javascript" src="include/multiselect/docs/js/bootstrap.bundle-4.5.2.min.js"></script>
<script type="text/javascript" src="include/multiselect/docs/js/prettify.min.js"></script>
<script type="text/javascript" src="include/multiselect/dist/js/bootstrap-multiselect.js"></script>

<script type="text/javascript">
  var session_id = "<?php echo $sessionID; ?>";
  var marker_association_file = null;
  var mapping_file = [];
  var module_set_file = null;
  var module_info_file = null;
  var permtype = null;
  var maxgene = null;
  var mingene = null;
  var maxoverlap = null;
  var minoverlap = null;
  var sseanperm = null;
  var sseafdr = null;
  var MMFConvert = null;
  var GSETConvert = null;
  var target_path="<?php echo $FILE_UPLOAD;?>";

  $("#MDFflowChart").hide();
  $("#MDFflowChart").next().hide();
  $("#MSEAflowChart").addClass("activePipe").html('<a href="#SSEAtoggle" class="pipelineNav" id="SSEAtoggleNav">MSEA</a>').css("opacity","1");

  $("#SSEAtoggleNav").on('click', function(e){
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
    if (val<=0 || ($(window).scrollTop()!=0 && $(window).scrollTop() < $(href).offset().top)){ 
      // below item or scrolled down but not below item
      var val = $(href).offset().top - 65;
    } 

    $(window).scrollTop(
      //$(href).offset().top - $(window).offset().top + $(window).scrollTop() - 60
      //$(href).offset().top - $(window).scrollTop() - 60
      val
    );

    return false;
  });


  /**********************************************************************************************
Set up Select slide down js function
***********************************************************************************************/
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


  function SSEAreview() //This function gets the review table for SSEA
  {

    var skipped = 1;

    $.ajax({
      url: "SSEA_moduleprogress.php",
      method: "GET",
      data: {
        sessionID: session_id,
        skippedMDF: skipped,
        marker_association: marker_association_file,
        mapping: mapping_file,
        module: module_set_file,
        module_info: module_info_file,
        perm_type: permtype,
        max_gene: maxgene,
        min_gene: mingene,
        maxoverlap: maxoverlap,
        minoverlap: minoverlap,
        sseanperm: sseanperm,
        sseafdr: sseafdr,
        MMFConvert: MMFConvert,
        GSETConvert: GSETConvert,
        enrichment: "GWAS",
      },
      success: function(data) {
        $('#mySSEA_review').html(data);
      }
    });
    $('#SSEAtab2').show();
    $('#SSEAtab2').click();




  }

  ///////////////Start Submit Function (SSEA form) -- Function for clicking 'Click to review button'///////////////////////////////////

  $('#SSEAdataform').submit(function(e) {

    e.preventDefault();


    // $.ajax({
    //   'url': 'SSEAskipped_parameters.php',
    //   'type': 'POST',
    //   'data': form_data,
    //   processData: false,
    //   contentType: false,
    //   'success': function(data) {
    //     $("#mySSEA").html(data);
    //     SSEAreview()
    //   }
    // });


  });
  /////////////////////////////////////////////End submit function for SSEA form//////////////////////////////////////////////////////

  /**********************************************************************************************
  Validation/REVIEW button -- Function for clicking 'Click to review button
  Will create an error message at the top if user forgets or does not have all data entered into form
  ***********************************************************************************************/
  $("#Validatebutton_SSEA").on('click', function() {

    var select = $("select[name='formChoice_SSEA'] option:selected").index(),
      select2 = $("select[name='formChoice2_SSEA'] option:selected").index(),
      select3 = $("select[name='formChoice'] option:selected").index();
    //select4 = $("select[name='formChoice2'] option:selected").index();


    //var selectarray = [select3, select4, select, select2];
    var selectarray = [select3, select, select2];
    // var idarray = ['MAFskippeduploadInput', 'MMFskippeduploadInput', 'GSETuploadInput', 'GSETDuploadInput'];
    var idarray = ['MAFskippeduploadInput', 'GSETuploadInput', 'GSETDuploadInput'];
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

      }
    }
    if (mapping_file.length == 0) {
      errorlist.push('Marker Mapping File is not selected!');
    }


    $('.sseaparameter').each(function() {
      if ($(this).val() == "") {
        errorlist.push($(this).parent().parent().attr('name') + ' is empty!');

      }
    });

    if (errorlist.length === 0) {
      $(this).html('Please wait ...');
        //.attr('disabled', 'disabled');
      setTimeout(function() {
        MMFConvert = $("#MMFConvert").val();
        GSETConvert = $("#GSETConvert").val();

        if ($("#marker_association").val() != "private_data") {
          marker_association_file = $("#marker_association").val();
        }
        if ($("#mapping_file").val() != "private_data") {
           MMFConvert = "none";
        }
        if ($("#module").val() != "private_data") {
          module_set_file = $("#module").val();
          GSETConvert = "none";
        }
        if ($("#module_info").val() != "private_data") {
          if ($("#module").val() != "private_data") { // user chose sample
            module_info_file = module_set_file.replace(".txt","_info.txt");
          }
          else{ // user chose to upload module sets but left option as "No Gene Sets Description"
            module_info_file = $("#module_info").val();
          }
        }
        permtype = $("#permuttype").val();
        maxgene = $("#maxgene").val();
        mingene = $("#mingene").val();
        maxoverlap = $("#maxoverlap").val();
        minoverlap = $("#minoverlap").val();
        sseanperm = $("#sseanperm").val();
        sseafdr = $("#sseafdr").val();
        SSEAreview();
        $(this).html('Click to Review');
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
  ////Can only input values from 0-1. If they try to type "2", it won't appear
  $("#minoverlap, #maxoverlap").inputFilter(function(value) {
    return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });
  ////Can only input values from 0-25. If they try to type "50", it won't appear
  $("#sseafdr").inputFilter(function(value) {
    return /^\d*$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 25));
  });

  ///////////////////////////////////////////////End Validation/REVIEW button/////////////////////////////////////////////////////////////



  /**********************************************************************************************
  Tutorial Button Script -- Append the tutorial to the form
  ***********************************************************************************************/

  var myTutButton_SSEA = document.getElementById("myTutButton_SSEA");
  var val_SSEA = 0; //We only want to append once, even if the user clicks on the tutorial button multiple times

  //begin function for when button is clicked-------------------------------------------------------------->
  myTutButton_SSEA.addEventListener("click", function() {

    //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
    var $this_SSEA = $(this);

    //If tutorial is already opened yet, then do this-------------------------------------------------------------->
    if ($this_SSEA.data('clicked')) {

      //hide the tutorial box
      $('.tutorialbox').hide();

      //remove the tutorial from the wKDA parameters table
      $('#SSEAparameterstable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });

      //change tutorial clicked to false. So next time, we add the tutorial. 
      $this_SSEA.data('clicked', false);
      val_SSEA = val_SSEA - 1;
      //Change name of button to 'Click for Tutorial'
      $("#myTutButton_SSEA").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

    }

    //If tutorial is not opened yet, then do this-------------------------------------------------------------->
    else {
      $this_SSEA.data('clicked', true);
      val_SSEA = val_SSEA + 1; //val counter to not duplicate prepend function
      if (val_SSEA == 1) //Only prepend the tutorial once
      {
        //Find the last column and then append a new column.
        //Since each row has different information, we have to indiviualize the tutorial cell
        $('#SSEAparameterstable').find('td[name="val1"]').eq(-1).after(`
                        <td name="tut">
                        <strong>Permutation type</strong>: Gene-based permutation to estimate statistical significance p-values is recommended for GWAS. This is more stringent, and the user can choose to run marker-based permutation but it may be biased toward genes with many markers. <br>
                            <strong>Default value</strong>: "Gene".
                         </td>

                        `);

        $('#SSEAparameterstable').find('td[name="val2"]').eq(-1).after(`

                        <td name="tut">
                        <strong>Max Genes in Gene Sets</strong>: defines the maximum gene number that a gene set can have. <br>
                                <strong>Options</strong>: Number between 2 and 10,000; suggested between 200-800 <br>
                                <strong>Default value</strong>: 500
                         </td>

                        `);
        $('#SSEAparameterstable').find('td[name="val3"]').eq(-1).after(`
                        <td name="tut">
                            <strong>Min Genes in Gene Sets</strong>: defines the minimal gene number that a gene set can have. <br>
                                <strong>Options</strong>: Number between 2 and less than Max Genes in Gene Sets <br>
                                <strong>Default value</strong>: 10
                        </td>`);

        $('#SSEAparameterstable').find('td[name="val4"]').eq(-1).after(`
                        <td name="tut">
                            <strong>Max Overlap in Merging Gene Mapping</strong>: Overlap ratio threshold for merging genes with shared markers (SNPs). Over this overlap ratio, the genes will be merged.<br>
                            <strong>Default Value</strong>: 0.33
                        </td>`);

        $('#SSEAparameterstable').find('td[name="val5"]').eq(-1).after(`
                        <td name="tut">
                            <strong>Min Module Overlap Allowed for Merging</strong>: Minimum gene overlap ratio between modules (gene sets) that will have them merged (to merge redundant modules). For instance, for the default value of 0.33, the modules need to have an overlap ratio of 0.33 or greater to be merged. <br>
                           <strong>Options</strong>: 0 to 1 (Use 1 to skip merging) <br>
                            <strong>Default value</strong>: 0.33 (33% overlap)
                        </td>`);


        $('#SSEAparameterstable').find('td[name="val6"]').eq(-1).after(`
                        <td name="tut">
                            <strong>Number of Permutations</strong>: the number of gene or marker permutations conudcted in the MSEA analysis <br>
                           <strong>Options</strong>: 1000 to 20,000 (for publication, recommend >= 10,000) <br>
                            <strong>Default value</strong>: 2000
                        </td>`);

        $('#SSEAparameterstable').find('td[name="val7"]').eq(-1).after(`
                        <td name="tut">
                            <strong>MSEA to KDA export FDR cutoff</strong>: Gene sets must pass this FDR threshold to be exported to KDA. We recommend 5 (5%) for formal analysis. If no gene sets pass, the top 10 will be used in KDA.  <br>
                            <strong>Options</strong>: Between 0 to 25 (25 is 25%) <br>
                            <strong>Default value</strong>: 25
                        </td>`);

        $('#SSEAparameterstable').find('th[name="val"]').eq(-1).after('<th name="tut">Comments</th>');

        $('.tutorialbox').show();
        $('.tutorialbox').html('The purpose of the pipeline is to take an association dataset for a given disease or phenotype from the user as input and integrate the association data with functional genomics information, pathways, and gene networks to derive pathways, gene networks and key regulatory genes for the disease or phenotype.');





      }
      $("#myTutButton_SSEA").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
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

  $('select.SSEA').on('change', function() {
    var select = $(this).find('option:selected').index(); //get the select index (i.e. 1 = upload)
    if (select != 1)
      $(this).parent().next().hide(); //hide the upload form

    if (select == 1)
      $(this).parent().next().show(); //show the upload form

    if (select > 1){
      $(this).parent().nextAll(".alert-SSEA").eq(0).html(successalert).hide().fadeIn(300);
    } //sample has been chosen

    else if (select == 1){ //upload has been chosen
      $("#MAF_uploaded_file").hide()
      //$(this).parent().nextAll(".alert-SSEA").eq(0).html('').hide();
      $(this).parent().nextAll(".alert-SSEA").eq(0).html(uploadalert).hide().fadeIn(300);
    }
    else //nothing has been chosen
      $(this).parent().nextAll(".alert-SSEA").eq(0).empty();


  });

  //trigger the change at start of page
  //Helpful if a user comes back with the sessionID
  $('select.SSEA').each(function() {
    $(this).trigger('change');

  });

  $('#module').on('change', function(){
    var select = $(this).find('option:selected').index();
    if (select == 1) {
      $('#gsetd_row').show();
    } else {
      $('#gsetd_row').hide();
    }
  });


  /**********************************************************************************************
  Upload functions -- uses AJAX to send data to a PHP file and then upload the file onto the server if conditions are correct
  ***********************************************************************************************/

  //Marker Association File Upload
  $("#MAFskippeduploadInput").on("change", function() {
    $("#MAFlabelname").html("Select another file?");
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#MAFskippeduploadInput"); //get the id
      control.replaceWith(control = control.clone().val('')); //replace with clone
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
            marker_association_file = fullPath;
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#MAFfilereturn').html(filename);
            $('#MAF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
            $("#MAF_uploaded_file").show()
          } else {
            $('#MAF_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong>' + resp.msg + '</div>');
            //var control = $("#MAFskippeduploadInput"); //get the id
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


  //Marker Mapping File Upload
  $("#MMFskippeduploadInput").on("change", function(event) {
    $("#MMFlabelname").html("Select another file?");
    var name = this.files[0].name;
    var file = this.files[0];
    var ext = name.split('.').pop().toLowerCase();
    var fsize = file.size || file.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#MMFskippeduploadInput"); //get the id
      control.replaceWith(control = control.clone().val('')); //replace with clone
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
            mapping_file.push(fullPath.replace("./Data/Pipeline/", ""));
            //mapping_file = fullPath.replace("./Data/Pipeline/", "");
            var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
            $('#MMFfilereturn').html(filename);
            $('#MMF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
            $('#alertMMF').show();
          } else {
            $('#MMF_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong>' + resp.msg + '</div>');
            var control = $("#MAFskippeduploadInput"); //get the id
            control.replaceWith(control = control.clone().val('')); //replace with clone
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

  //Gene Set File Upload    
  $('#GSETuploadInput').on("change", function() {
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
            module_set_file = fullPath.replace("./Data/Pipeline/", "");
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



  //Gene Set Description File Upload 
  $("#GSETDuploadInput").on("change", function() {
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
            module_info_file = fullPath.replace("./Data/Pipeline/", "");
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


  $(document).ready(function() {
    $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + session_id);
    $('#session_id').css("padding", "17px 30px");
    window.prettyPrint() && prettyPrint();
    $('#mapping_file').multiselect({
      enableCaseInsensitiveFiltering: true,
      maxHeight: 600,
      onChange: function(option, checked) {
        // Get selected options.
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
          $("#MMFupload2").hide();
          $("#mapping_btn").html("Please select option");
        } else {
          $("#MMFupload1").hide();
          $("#MMFupload2").show();
          $("#mapping_btn").html(selectedOptions.length + " selected");
        }
        // multi select was changed from 5 to 10 - 07.18.2024 Dan
        if (selectedOptions.length >= 10) {
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