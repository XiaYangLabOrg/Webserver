<?php
include "functions.php";
//This parameters files is for when the user chooses MDF in mergeomics


/* Initialize PHP variables
sessionID = the saved session 

rmchoice = type of pipeline chouce

GET = if the user enters the link directly
POST = if PHP enters the link

*/

//Check if the sessionID exists and the user has returned to the form
if (isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {
  //if it does exist, then initialize the sessionID with specified one
  $sessionID = $_GET['sessionID'];
} else {
  //if it does not exist, then create a new random string
  $sessionID = generateRandomString(10);
}

//check if form has been submitted and then initialize it
if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  $sessionID = $_POST['sessionID'];
}


/***************************************
Session ID
Since we don't have a database, we have to create txt file with the path information
 ***************************************/
$fpostOut = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_MDF_postdata.txt";
$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$session_write = NULL;

if (!file_exists($fsession)) {
  $sessionfile = fopen($fsession, "w");
  $session_write .= "Pipeline:" . "\t" . "GWAS" . "\n";
  $session_write .= "Mergeomics_Path:" . "\t" . "1" . "\n";
  $session_write .= "Pharmomics_Path:" . "\t" . "0|0" . "\n";
  fwrite($sessionfile, $session_write);
  fclose($sessionfile);
  chmod($fsession, 0775);
}

//if the POST data doesn't exist yet, create it and store on server
$postwrite = "";
if (!empty($_POST)) {
  $fp = fopen($fpostOut, "w");
  foreach ($_POST as $key => $value) {
    $postwrite .= $key . "\t" . $value . "\n";
  }
  fwrite($fp, $postwrite);
  fclose($fp);
  chmod($fpostOut, 0774);
}
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
<!-- Error message div for MDF ===================================================== -->
<div id="errormsg" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp" style="white-space: pre;"></p>
</div>
</div>




<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">



  <!-- Description ===================================================== -->
  <h4 style="color: #00004d; text-align: center; padding: 20px;">
    This part of the pipeline takes disease/phenotype association files and corrects for marker dependency.<br> Resulting files can be used in MSEA. This step is also optional.
  </h4>

  <!--Start MDF Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_MDF"><i class="icon-question1"></i>Click for tutorial</button>
    <button class="button button-3d button-rounded button" id="skipbutton_MDF" data-toggle="modal" data-target="#skipMDFmodal" href="#skipMDFmodal"><i class="icon-fast-forward1"></i>Skip MDF</button>
  </div>

  <script type="text/javascript">
    /***************************************
Function to skip MDF
***************************************/
    function skipMDF() {

      var string = "<?php echo $sessionID; ?>";
      $('#MDFtoggle').fadeOut();
      $('#MDFtoggle').remove();
      $('#skipMDFmodal').modal('hide');
      $('body').removeClass('modal-open');
      $('.modal-backdrop').remove();
      $("#MAFuploadInput, #MMFuploadInput").off('change');
      $('#mySSEA').load('/SSEAskipped_parameters.php?sessionID=' + string);
      $('#SSEAtoggle').show();
      $('#SSEAtogglet').click();
      return false;
    }
  </script>




  <!--End MDF Tutorial --------------------------------------->


  <!-- Description ============Start table========================================= -->
  <!-- <form enctype="multipart/form-data" action="MDF_parameters.php" name="select" id="Markerdataform"> -->
  <table class="table table-bordered" style="text-align: center" ; id="maintable">
    <thead>
      <tr>
        <!--First row of table------------------------------------------>
        <th>Type of File</th>
        <th class="uploadwidth">Upload/Select File</th>
        <th name="val">Sample File Format</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <!--Second row of table------------------------------------------>
        <td data-column="File type &#xa;" style="font-size: 16px;">Marker Association File <br>

          <div class="informationtext" data-toggle="modal" data-target="#MAFinfomodal" href="#MAFinfomodal">
            <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
          </div>
        </td>
        <!--Second row|first column of table------------------------------------------>
        <td data-column="Upload/Select File &#xa;">
          <!--Second row|second column of table------------------------------------------>
          <!--Start MDF Select Form ----------------------------->
          <div id="Selectupload" class="selectholder LDPrune" align="center">
            <select class="LDPrune" name="formChoice" size="1" id="marker_association">
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


            <!--End MDF SELECT FORM------------------------------------->

            <br>


          </div> <!-- End Select upload div---->


          <!-- Marker  Association File Upload div --->
          <div id="MAFupload" style="display: none;">

            <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file. </div>

            <div class="input-file-container" name="Marker Association File" style="width: fit-content;">
              <input class="input-file" id="MAFuploadInput" name="MAFuploadedfile" type="file" accept="text/plain" data-show-preview="false">
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
          <!--Div to alert user of certain comment (i.e. success) -->
          <div class="alert-MDF" id="alert1"></div>


        </td>
        <td data-column="Sample Format &#xa;" name="val1">
          <!--Second row|third column of table------------------------------------------>
          <!--Start MDF Sample File Format -------------------->
          <div class="table-responsive" style="overflow: visible;">
            <table class="table">
              <thead>
                <tr>
                  <th><a href="#" tooltip="SNP, chromosomal position, etc." style="position: relative;">MARKER</a></th>
                  <th><a href="#" tooltip="Association strength which can be -log10(p-value), effect size, absolute value of the log fold change, etc. Larger values signify higher association strength." style="position: relative;">VALUE</a></th>
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


        <td data-column="File type &#xa;" style="font-size: 16px;">Marker Mapping File

          <div class="informationtext" data-toggle="modal" data-target="#MMFinfomodal" href="#MMFinfomodal">
            <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
          </div>

        </td>
        <!--Third row|first column of table------------------------------------------>
        <td>
          <!--Third row|second column of table------------------------------------------>
          <!--Start MMF Select ----------------------------->
          <div id="Selectupload2">
            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#example-modal-modal" id="mapping_btn">Please select option</button>
            <div class="modal fade" id="example-modal-modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Please select mapping file(s)</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  </div>
                  <p style="margin-bottom: 0;padding: 0 5%;text-align: left;margin-top: 3%;">One selection is recommended to preserve tissue specificity (in the case of eQTLs/sQTLs) but multiple can be selected which will create a combined mapping file.</p>
                  <div class="modal-body">
                    <select class="SSEA" name="formChoice2" size="1" id="mapping_file" multiple="multiple">
                      <optgroup class="only_one_mapping" label="Only One Mapping file can be selected">
                        <option value="Resources/mappings/gene2loci.010kb.txt">Chromosomal distance 10Kb</option>
                        <option value="Resources/mappings/gene2loci.020kb.txt">Chromosomal distance 20Kb</option>
                        <option value="Resources/mappings/gene2loci.050kb.txt">Chromosomal distance 50Kb</option>
                        <option value="Resources/GTEx_v8_eQTL/combined_49esnps.txt">GTEx 49 Combined eQTLs</option>
                        <option value="Resources/GTEx_v8_sQTL/combined_49ssnps.txt">GTEx 49 Combined sQTLs</option>
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
                <input class="input-file" id="MMFuploadInput" name="MMFuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="MMFlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="MMFprogressbar" class="progress active" style='display: none;'>
                  <div id="MMFprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="MMFprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <!--<p id="MMFfilereturn" class="file-return"><?php if ($uploaded_mapping !== 0) {
                                                            print($uploaded_mapping);
                                                          } ?></p> --->
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


            <!--<div class="alert-SSEA" id="alert2"><?php if ($uploaded_mapping !== 0) {
                                                  echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                } else {
                                                  print("");
                                                } ?></div> --->
            <div class="alert-SSEA" id="alert2"></div>
          </div>
          <!--Div to alert user of certain comment (i.e. success) -->
          <br>
          <div id="MMFupload2" style="display:none;">
            <div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Successful!</strong></div>
          </div>
        </td>
        <!--End of MMF Upload column -------------------->
        <td data-column="Sample Format &#xa;" name="val2">
          <!--Third row|Third column (Sample file format) -------------------->


          <div class="table-responsive" style="overflow: visible;">
            <table class="table">
              <thead>
                <tr>
                  <th><a href="#">GENE</a></th>
                  <th><a href="#" tooltip="SNP, chromosomal position, etc." style="position: relative;">MARKER</a></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td data-column="MARKER(Header): ">CDK6</td>
                  <td data-column="VALUE(Header): ">rs10</td>

                </tr>
                <tr>
                  <td data-column="MARKER(Header): ">AGER</td>
                  <td data-column="VALUE(Header): ">rs1000</td>

                </tr>
                <tr>
                  <td data-column="MARKER(Header): ">N4BP2</td>
                  <td data-column="VALUE(Header): ">rs1000000</td>

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
        <!--Start of Marker Dependency File row -------------------->
        <td data-column="File Type &#xa;" style="font-size: 16px;">Marker Dependency File <br> (i.e. 1000G LD File) <br> The three letter code in the sample files refers to the population which can be decoded <a href="https://www.internationalgenome.org/category/population/">here</a>.

          <div class="informationtext" data-toggle="modal" data-target="#MDFinfomodal" href="#MDFinfomodal">
            <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
          </div>

        </td>
        <!--Fourth row|first column (Type of File)-------------------->
        <td data-column="Upload/Select File &#xa;">
          <!--Fourth row|second column (Upload/Select File) -------------------->
          <div id="Selectupload3" class="selectholder LDPrune" align="center">

            <select class="LDPrune" name="formChoice3" size="1" id="mdf_file">
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
              <option value="Resources/LD_files/ld50.TSI.txt">TSI LD50</option>
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

          <div class="alert-MDF" id="alert3"></div>
          <!--Div to alert user of certain comment (i.e. success) -->




          <!-- </form> -->
          <!--End of MDF form ------------------------(This form combines all three input options)--------------------------------------->





        </td> <!-- End of MDF upload row -------------->
        <td data-column="Sample Format &#xa;" name="val3">
          <!--Fourth row|Third column (Marker Dependency Sample File Format)---->

          <div class="table-responsive" style="overflow: visible;">
            <table class="table">
              <thead>
                <tr>
                  <th><a href="#" tooltip="SNP, chromosomal position, etc." style="position: relative;">MARKERa</a></th>
                  <th><a href="#" tooltip="SNP, chromosomal position, etc." style="position: relative;">MARKERb</a></th>
                  <th><a href="#" tooltip="Correlation value between MARKERa and MARKERb &#013; (0-100)" style="position: relative;">WEIGHT</a></th>
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
            <p>A <strong>TAB</strong> deliminated text file that provides the two markers and their correlation. The markers provided in this file will be filtered out. For example, to filter out markers above 50% correlation, provide the file with correlations above this threshold.</p>
          </div>



        </td>
        <!--End Marker Dependency Sample File Format column -->


      </tr>

    </tbody>
  </table>
  <!--End of MDF maintable -->



  <!---------------------------- Percentage div --------------------->
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center" ; id="percenttable">


      <thead>
        <tr>
          <th>Percentage of Top Markers (sorted by association strength)</th>

        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div id="PercentageMDF">



              <div class="datagrid">
                <p class="nobottommargin">Percentage of Markers:</p>
                <input type="text" id="percent_markers" name="percentage" value="50">
                <p>Between 1-100. Default is 50.</p>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>



  </div>
  <!--End of responsive div--->



  <!------------------------------Review button div----------------------------------------->
  <div id="Validatediv" style="text-align: center;">
    <button type="button" class="button button-3d button-large nomargin" id="Validatebutton">Click to Review</button>
  </div>

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
              <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;"> Marker-disease association summary statistics including marker IDs and association strength (–log10 transformed association p-values, effect size, etc. can be used). Larger values in the 'VALUE' indicate higher association strengths. For GWAS, markers are rs IDs or chromosomal positions. UTF-8/ASCII encoded files are preferred.
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
              <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;"> SNP to gene mapping file. We provide distance-based, regulome, and tissue-specific e/sQTLs from GTEx. You may combine up to 5 of select mapping files but one is recommended. To choose a sample mapping, click on "Please select option" and then "None selected" on the window that pops up.
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
  <div id="MDFinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 35px;">Marker Dependency File</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body" style="text-align: center;">
            <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
              <div class="style-msg infomsg" style="margin: 0 auto; width: 90%;padding:30px;font-size: 18px;"> Population-specific linkage disequilibrium (LD) of SNPs describing the correlations of SNPs. If you would like to filter out SNPs above 50% correlation, include the LD file with correlations 50% or above.
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
  <!-- script for tutorial[Click on button] -->
  <script>
    /**********************************************************************************************
    Javascript functions/scripts (These are inlined because it was easier to do)
    You can technically extract it and just call it externally if you want to keep the php page cleaner, but not needed
    ***********************************************************************************************/

    var session_id = "<?php echo $sessionID; ?>"; //get sessionID and store to javascript variable
    var n = localStorage.getItem('on_load_session');
    localStorage.setItem("on_load_session", session_id);
    var marker_association_file = null;
    var mapping_file = [];
    //var mapping_file = null;
    var mdffile = null;
    var mdf_ntop = null;
    var MMFConvert = null;


    $(document).ready(function() {

      //change the sessionID to current on sidebar
      $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + session_id).attr('tooltip','Save your session ID! Click to copy.');
      $('#session_id').css("padding", "17px 30px");

    });
    /**********************************************************************************************
    Tutorial Button Script -- Append the tutorial to the form
    ***********************************************************************************************/

    var button = document.getElementById("myTutButton_MDF");
    var val = 0; //We only want to append once, even if the user clicks on the tutorial button multiple times

    //begin function for when button is clicked-------------------------------------------------------------->
    button.addEventListener("click", function() {

      //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
      var $this = $(this);

      //If tutorial is already opened yet, then do this-------------------------------------------------------------->
      if ($this.data('clicked')) {

        //remove tutorial from maintable
        $('#maintable').find('tr').each(function() {
          $(this).find('td[name="tut"]').eq(-1).remove();
          $(this).find('th[name="tut"]').eq(-1).remove();
        });
        //remove tutorial from maintable
        $('#percenttable').find('tr').each(function() {
          $(this).find('td[name="tut"]').eq(-1).remove();
          $(this).find('th[name="tut"]').eq(-1).remove();
        });


        $this.data('clicked', false);
        val = val - 1;
        $("#myTutButton_MDF").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

      }

      //If tutorial is not opened yet, then do this-------------------------------------------------------------->
      else {
        $this.data('clicked', true);
        val = val + 1; //val counter to not duplicate prepend function


        if (val == 1) //Only prepend the tutorial once
        {


          $('#maintable').find('td[name="val1"]').eq(-1).after(`
                    <td name="tut">
                    <p>Users must select or upload an Association Data file <strong>(.txt)</strong> that gives the correlation of markers with the specific phenotype/disease <strong>(-log10 p value)</strong> and follows the format specified the sample format to the left.
                        <br><br>
                        For demonstration purposes, if users would like to download the sample <a href="/Download/Sample_Files/Association/glgc.ldl.txt" download>GWAS file (i.e. GLGC LDL GWAS file)</a> and then upload it to complete the tutorial, that is feasible as well.
                     </p>
                     </td>

                    `);
          $('#maintable').find('td[name="val2"]').eq(-1).after(`

                    <td name="tut">
                    <p>Users must select or upload a Marker Mapping file <strong>(.txt)</strong> that maps each marker (SNP) in the association file to a specific gene and follows the format specified in the sample format to the left. 
                    <br><br>

                        To select a sample SNP to gene mapping file (we provide all GTEx v8 e/sQTLs and distance based mapping), click on 'Please select option' and then 'None selected'. Choosing one mapping file is recommended to preserve tissue specificity, but we leave the option to combine. To test multiple different mapping methods on their own with the same GWAS file, one can utilize the meta function to run multiple MSEAs in a single session.
                     </p>
                     </td>

                    `);
          $('#maintable').find('td[name="val3"]').eq(-1).after(`
                    <td name="tut">
                        <p>Users must select or upload a Marker Dependency files <strong>(.txt)</strong>. The provided files are a selection of LD files for GWAS data for different LD cutoffs in the CEU population. 
                        <br><br>
                         These files give the dependency of the different markers and must follow the format specified in the sample format to the left.
                        </p>



                    </td>`);
          $('#maintable').find('th[name="val"]').eq(-1).after('<th name="tut" style="width: 30%;"">Tutorial</th>');

          $('#percenttable').find('th').eq(-1).after('<th name="tut" style="width: 30%;"">Tutorial</th>');


          $('#percenttable').find('td').eq(-1).after(`
                    <td name="tut">
                    <p>To reduce noise from low signals, we select a certain top percentage of markers to be considered. This filtering is done based on percentage of top markers, as sorted by the 'VALUE' colum. For very small studies, we use 100 (all associations), for moderately sized studies, we use 50, and for very large studies, we choose 20-25. <br>
                        <strong>Default value: 50%</strong>
                     </p>
                     </td>
48
                    `);


        }
        $("#myTutButton_MDF").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
      }



    });


    /**********************************************************************************************
    Review and submit functions
    ***********************************************************************************************/

    function review() //This function will send form data with an AJAX call to moduleprogress.php
    {

      $.ajax({
        url: "MDF_moduleprogress.php",
        method: "GET",
        data: {
          sessionID: session_id,
          marker_association: marker_association_file,
          mapping: mapping_file,
          mdf: mdffile,
          MMFConvert: MMFConvert,
          enrichment: "GWAS",
          mdf_ntop: mdf_ntop,
        },
        success: function(data) {
          $('#myLDPrune_review').html(data);
        }
      });
      $('#MDFtab2').show();
      $('#MDFtab2').click();

    }








    // });
    //End submit function for form



    /**********************************************************************************************
Validation/REVIEW button -- Function for clicking 'Click to review button
Will create an error message at the top if user forgets or does not have all data entered into form
***********************************************************************************************/

    $("#Validatebutton").on('click', function() {
      //Get index of all upload form inputs
      var select = $("select[name='formChoice'] option:selected").index(),
        // select2 = $("select[name='formChoice2'] option:selected").index(),
        select3 = $("select[name='formChoice3'] option:selected").index();

      //initialize arrays to check against formchoice w/ mapping file input
      var selectarray = [select, select3];
      var idarray = ['MAFuploadInput', 'MDFuploadInput'];

      //This is the error array. If there is something in here, then that means there is an error
      //We use ".push" to add things into array
      var errorlist = [];
      selectarray.forEach(myFunction); //for loop with the created function

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

      //check if the errorlist array is empty
      if (errorlist.length === 0) {
        $(this).html('Please wait ...');
        setTimeout(function() {
          MMFConvert = $("#MMFConvert").val();

          if ($("#marker_association").val() != "private_data") {
            marker_association_file = $("#marker_association").val();
          }
          /*
          if ($("#mapping_file").val() != "private_data") { // no longer applies
            //mapping_file = $("#mapping_file").val();
            MMFConvert = "none";
          }
          */
          if ($("#mdf_file").val() != "private_data") {
            mdffile = $("#mdf_file").val();
          }

          mdf_ntop = $("#percent_markers").val();
          // $("#Markerdataform").submit();
          review();
          $(this).html('Click to review');
        }, 500);
      } else {
        //if errorlist array is not empty, then slidedown error message
        var result = errorlist.join("\n");
        //alert(result);
        $('#errorp').html(result);
        $("#errormsg").fadeTo(2000, 500).slideUp(500, function() {
          $("#errormsg").slideUp(500);
        });
      }
    });


    /**********************************************************************************************
    Set up Select slide down js function
    ***********************************************************************************************/

    $('.selectholder.LDPrune').each(function() {
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
          $('.activeselectholder.LDPrune').each(function() {
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
    $('.selectholder.LDPrune .selectdropdown span').click(function() {

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
      var container = $(".selectholder.LDPrune");

      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        $('.activeselectholder.LDPrune').each(function() {
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



    // set up select boxes

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
    $('select.LDPrune').on('change', function() {
      var select = $(this).find('option:selected').index();
      if (select != 1)
        $(this).parent().next().hide();

      if (select == 1)
        $(this).parent().next().show();

      if (select > 1)
        $(this).parent().nextAll(".alert-MDF").eq(0).html(successalert).hide().fadeIn(300);
      else if (select == 1)
        $(this).parent().nextAll(".alert-MDF").eq(0).html(uploadalert).hide().fadeIn(300);
      else
        $(this).parent().nextAll(".alert-MDF").eq(0).empty();
    });

    //trigger the change at start of page
    //Helpful if a user comes back with the sessionID
    $('select.LDPrune').each(function() {
      $(this).trigger('change');
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

    //Will not allow user to put a decimal or go under 1 or above 100
    $("#percent_markers").inputFilter(function(value) {
      return /^\d*$/.test(value) && (value === "" || (parseInt(value) > 0 && parseInt(value) <= 100));
    });
    /**********************************************************************************************
    Upload functions -- uses AJAX to send data to a PHP file and then upload the file onto the server if conditions are correct
    ***********************************************************************************************/
    //Marker Associative File
    $("#MAFuploadInput").on("change", function() {
      $("#MAFlabelname").html("Select another file?");
      var name = this.files[0].name;
      var file = this.files[0];
      var ext = name.split('.').pop().toLowerCase();
      var fsize = file.size || file.fileSize;
      if (fsize > 400000000) {
        alert("File Size is too big");
        var control = $("#MAFuploadInput"); //get the id
        //control.replaceWith(control = control.clone().val('')); //replace with clone
      } else {
        var fd = new FormData();
        fd.append("afile", file);
        fd.append("path", "./Data/Pipeline/Resources/ssea_temp/");
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
              marker_association_file = fullPath.replace("/var/www/mergeomics/html/./Data/Pipeline/","");
              var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
              $('#MAFfilereturn').html(filename);
              $('#MAF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
            } else {
              $('#MAF_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong>' + resp.msg + '</div>');
              var control = $("#MAFuploadInput"); //get the id
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
        $("#MAFuploadInput").focus();
      }
    });
    $("#MAFlabelname").on("click", function(event) {
      $("#MAFuploadInput").focus();
      return false;
    });


    $("#MMFuploadInput").on("change", function(event) {
      $("#MMFlabelname").html("Select another file?");
      var name = this.files[0].name;
      var file = this.files[0];
      var ext = name.split('.').pop().toLowerCase();
      var fsize = file.size || file.fileSize;
      if (fsize > 400000000) {
        alert("File Size is too big");
        var control = $("#c"); //get the id
        control.replaceWith(control = control.clone().val('')); //replace with clone
      } else {
        var fd = new FormData();
        fd.append("afile", file);
        fd.append("path", "./Data/Pipeline/Resources/ssea_temp/");
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
              mapping_file.push(fullPath.replace("./Data/Pipeline/", "")); // get error with this
              //mapping_file = fullPath.replace("./Data/Pipeline/", ""); // should only be one file?
              var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
              $('#MMFfilereturn').html(filename);
              $('#MMF_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
              $('#alertMMF').show();
            } else {
              $('#MMF_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong>' + resp.msg + '</div>');
              var control = $("#MMFuploadInput"); //get the id
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
        $("#MMFuploadInput").focus();
      }
    });
    $("#MMFlabelname").on("click", function(evetnt) {
      $("#MMFuploadInput").focus();
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
        control.replaceWith(control = control.clone().val('')); //replace with clone
      } else {
        var fd = new FormData();
        fd.append("afile", file);
        fd.append("path", "./Data/Pipeline/Resources/ssea_temp/");
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
              mdffile = fullPath.replace("./Data/Pipeline/", "");
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
          if(selectedOptions.length!==0){ // added in bc was getting error uploading an MMF file
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