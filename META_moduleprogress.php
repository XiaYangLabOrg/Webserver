<?php
$sessionID="";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
$FILE_UPLOAD=$ROOT_DIR."Data/Pipeline/Resources/meta_temp/";
if (isset($_GET['metasessionID'])) {
  $meta_sessionID = $_GET["metasessionID"];
  $fjson = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "data.json";
  if (file_exists($fjson)) {
    $data = json_decode(file_get_contents($fjson));
  }
}
if (isset($_GET['marker_association'])) {
  $marker_association = $_GET["marker_association"];
}

if (isset($_GET['mapping'])) {
  $mapping = $_GET["mapping"];
  if (count($mapping) > 1) {
    foreach ($mapping as &$value) {
      //$newMappingcontent .= readMappingFile($value);
      $mapping_val .= ", " . basename($value);
    }
    $mapping_val = substr($mapping_val, 2);
  } else {
    if (gettype($mapping) == "array") {
      $mapping_val = basename($mapping[0]);
    } else {
      $mapping_val = basename($mapping);
    }
  }
}

if (isset($_GET['perm_type'])) {
  $perm_type = $_GET["perm_type"];
}

if (isset($_GET['maxoverlap'])) {
  $maxoverlap = $_GET["maxoverlap"];
}

if (isset($_GET['sseanperm'])) {
  $sseanperm = $_GET["sseanperm"];
}

if (isset($_GET['sseafdr'])) {
  $sseafdr = $_GET["sseafdr"];
}

if (isset($_GET['enrichment'])) {
  $enrichment = $_GET["enrichment"];
}

if (isset($_GET['mdf'])) {
  $mdf = $_GET["mdf"];
}

if (isset($_GET['mdf_ntop'])) {
  $mdf_ntop = $_GET['mdf_ntop'];
}

if (isset($_GET['MAFConvert'])) {
  $MAFConvert = $_GET["MAFConvert"];
} else {
  $MAFConvert = "none";
}

if (isset($_GET['MMFConvert'])) {
  $MMFConvert = $_GET["MMFConvert"];
} else {
  $MMFConvert = "none";
}

if (isset($_GET['sessionID'])) {
  $sessionID = $_GET["sessionID"];
  $json = array();

  //$fpath_random = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "list_strings";

  //$num_iterations = file($fpath_random);
  //for ($i = 0; $i < (count($num_iterations)); $i++) {

  $json['session'] = $sessionID;
  //$fpathOut = "./Data/Pipeline/Resources/meta_temp/$new_random_string" . "PARAM";

  //$fdr_file = "./Data/Pipeline/Resources/meta_temp/$new_random_string" . "PARAM_SSEA_FDR";
  $json['perm'] = $perm_type;
  $json['maxoverlap'] = $maxoverlap;
  $json['numperm'] = $sseanperm;
  $json['fdrcutoff'] = $sseafdr;
  if (!empty($mdf)) {

    $json['mdf'] = $mdf;
    $json['mdf_ntop'] = $mdf_ntop;
  }


  $json['association'] = $marker_association;
  if ($mapping == "0") {
    $mapping = "None Provided";
  }
  $json['marker'] = $mapping;

  $json['enrichment'] = $enrichment;

  $json['MAFConvert'] = $MAFConvert;
  $json['MMFConvert'] = $MMFConvert;


  if (empty($data->data)) {
    $data['data'][] = $json;
  } else {
    $data->data[] = $json;
  }

  $fp = fopen($fjson, 'w');
  fwrite($fp, json_encode($data));
  fclose($fp);
  chmod($fjson, 0777);


  /***************************************
Session ID
Since we don't have a database, we have to update the txt file with the path information
   ***************************************/
  $fsession = "./Data/Pipeline/Resources/session/$meta_sessionID" . "_session.txt";

  if (file_exists($fsession)) {
    $session = explode("\n", file_get_contents($fsession));
    //Create different array elements based on new line
    $mergeomics_arr = preg_split("/[\t]/", $session[1]);
    $mergeomics_path = $mergeomics_arr[1];

    if ($mergeomics_path == "1") {
      $lines = file($fsession);
      $result = NULL;

      foreach ($lines as $line) {
        if (substr($line, 0, 2) == 'Me') {
          $result .= 'Mergeomics_Path:' . "\t" . "1.25" . "\n";
        } else {
          $result .= $line;
        }
      }

      file_put_contents($fsession, $result);
    }
    // else {
    //     $lines = file($fsession);
    //     $result = NULL;

    //     foreach ($lines as $line) {
    //         if (substr($line, 0, 2) == 'Cu') {
    //             $result .= 'Current_Path:' . "\t" . $sessionID . "\n";
    //         } else {
    //             $result .= $line;
    //         }
    //     }

    //     file_put_contents($fsession, $result);
    // }
  }
}

$femail = "./Data/Pipeline/Results/meta_email/$meta_sessionID" . "email";
$email_sent = "./Data/Pipeline/Results/meta_email/$meta_sessionID" . "sent_email";


if (isset($_GET['METAemail'])) {
  $emailid = $_GET['METAemail'];
} else {
  $emailid = "";
}


if ($emailid != "") {
  $parts = explode("@", $emailid);
  $name = $parts[0];
  $domain = $parts[1];
  if (trim($domain) == 'ucla.edu') {
    $newid = "$name" . "@g.ucla.edu";
  } else {
    $newid = $emailid;
  }
  $myfile = fopen($femail, "w");
  fwrite($myfile, $newid);
  fclose($myfile);
}





if ((!(file_exists($email_sent)))) {
  if (file_exists($femail)) {
    $sendemail = 'Yes';
  } else {
    $sendemail = 'No';
  }
}

// $fpath = "./Data/Pipeline/Resources/meta_temp/$sessionID";
$pv = "";



?>
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

  /* table td {
        max-width: 200px;
        white-space: nowrap;
        text-overflow: ellipsis;
        word-break: break-all;
        overflow: hidden;
    } */
</style>
<link rel="stylesheet" href="include/bs-datatable.css" type="text/css" />
<div id="errormsg_META" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_META" style="white-space: pre;"></p>
</div>
</div>

<h4 style="color: #00004d; text-align: center; padding: 40px;font-size:25px;" id="head_desc">

  Please review the files and the individual MSEA parameters you have selected/uploaded in the overview table and select meta-MSEA parameters below before executing the pipeline.

</h4>
<div style="width:100%;">
  <table class="table table-bordered review" id="metareviewtable">
    <thead>
      <tr>
        <th>Click for Parameters</th>
        <th>Type of Enrichment</th>
        <th>Association File</th>
        <th>Mapping File</th>
        <th>Delete</th>
      </tr>
    </thead>
  </table>
</div>


<br>


<div style="text-align: center;margin-bottom: 0px;">



  <div class="button-wrapper">
    <div id="myAddAssociation_div" class="button-container button-outer" style="display: table;height: 75px;width: 40%;margin:0 auto;margin-bottom: 2%;">
      <a href="#" class="runm button-inner" id="myAddAssociation" style="font-size: 16px !important"> <i class="icon-plus-sign"></i> Add another association data</a>
    </div>
  </div>

</div>


<!-------------------------------------------------Start of Meta-MSEA Parameters table ----------------------------------------------------->

<!-- Description ============Start table========================================= -->
<div class="table-responsive" style="overflow: visible;">
  <!--Make table responsive--->
  <table class="table table-bordered" style="text-align: center" ; id="SSEAmaintable">
    <thead>
      <tr>
        <th colspan='3' style="border-bottom: aliceblue;">Select genesets for Meta-MSEA</th>
      </tr>
      <tr>
        <!--First row of table------------Column Headers------------------------------>
        <th>Type of File</th>
        <th class="uploadwidth">Upload/Select File</th>
        <th name="val_SSEA">Sample File Format</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <!--Third row of table------------------------------------------>
        <td data-column="File Type &#xa;">Gene Sets

          <div>
            <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>Functionally related gene sets such as co-regulation, shared response, co-localization on chromosomes, or participants of specific biological processes. Typical sources of gene sets includes canonical pathways such as Reactome and KEGG, or coexpression modules constructed using algorithms like weighted coexpression gene networks analysis (WGCNA).
          </div>

        </td>
        <!--Third row|first column of table------------------------------------------>
        <td data-column="Upload/Select File &#xa;">
          <!--Start MDF Upload ----------------------------->
          <div id="Selectupload_GSET" class="selectholder META" align="center">
            <select class="META" name="formChoice_SSEA" size="1" id="module">
              <option value="0">Please select option</option>
              <option value="private_data">Upload Gene Sets</option>
              <option value="Resources/pathways/KEGG.txt">KEGG pathways</option>
              <option value="Resources/pathways/Reactome.txt">Reactome pathways</option>
              <option value="Resources/pathways/BioCarta.txt">BioCarta pathways</option>
              <option value="Resources/pathways/KEGG_Reactome_BioCarta.txt">KEGG, Reactome, and BioCarta pathways</option>
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
              <label id="GSETlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i>Select a file...</label>
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

          <div class="alert-META" id="alert_GSET"></div>
          <!--Div to alert user of certain comment (i.e. success) -->


        </td>
        <!--Second row|second column of table------------------------------------------>
        <td data-column="Sample Format &#xa;" name="val1_SSEA">
          <!--Start Second row|third column of table------------------------------------------>

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
            <p>A <strong>TAB</strong> deliminated text file that contains collections of pre-defined sets of genes that are functionally related</p>
          </div>

        </td>
        <!--End Second row|third column of table------------------------------------------>

      </tr>
      <tr id="gsetd_row" style="display:none;">
        <td data-column="File type &#xa;">Gene Sets Description <br>(Recommended)

          <div>
            <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>To better annotate the gene sets in MSEA output, a description file for the gene sets is needed to specify the source of the gene set and a detailed description of the functional information used to group genes.
          </div>

        </td>
        <td data-column="Upload/Select File &#xa;">
          <!--Start Gene Set Description Input ----------------------------->
          <div id="Selectupload_GSETD" class="selectholder META" align="center">
            <select class="META" name="formChoice2_SSEA" size="1" id="module_info">
              <option value="0">Please select option</option>
              <option value="private_data">Upload Gene Sets descriptions</option>
              <option value="no" selected>No Gene Sets Description</option>
            </select>


            <?php
            /*
            $fpathloci = $fpath . "DESC";
            if (file_exists($fpathloci)) {
              $gwas_file = file_get_contents($fpathloci);
              $split_file = explode("/", $gwas_file);
              $uploaded_description = $split_file[2];
            } else {
              $uploaded_description = 0;
            }
            */
            ?>


            <!-- Marker Mapping File Upload div --->
          </div> <!-- End Gene Set Description input -->

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
              <p id="GSETDfilereturn" class="file-return"></p>
              <span id='GSETD_uploaded_file'></span>
            </div>
          </div> <!-- End of upload div--->


          <div class="alert-META" id="alert_GSETD"></div>
          <!--Div to alert user of certain comment (i.e. success) -->


        </td>
        <td data-column="Sample Format &#xa;" name="val2_SSEA">
          <!--Third row|Third column (Gene Set Description sample format) -------------------->

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
            <p>A <strong>TAB</strong> deliminated text file that contains detailed descriptions of the gene sets (i.e. full name of a biological pathway)</p>
          </div>


        </td>
      </tr>
    </tbody>
  </table>
</div>

<!--Start meta Tutorial --------------------------------------->

<div style="text-align: center;">
  <button class="button button-3d button-rounded button" id="myTutButton_meta"><i class="icon-question1"></i>Click for tutorial</button>
</div>

<div class='tutorialbox' style="display: none;"></div>
<!--End meta Tutorial --------------------------------------->

<div class="table-responsive" style="overflow: visible;">
  <!--Make table responsive--->
  <table class="table table-bordered" style="text-align: center;" id="METAparameterstable">
    <thead>
      <tr>
        <th colspan='3' style="border-bottom: aliceblue;">Select parameters for Meta-MSEA</th>
      </tr>
      <tr>
        <th>Parameter type</th>
        <th name="val">Input</th>
      </tr>
    </thead>
    <tbody>

      <tr name="Max Genes in Gene Sets">

        <td>Max Genes in Gene Sets:</td>

        <td name="val2"><input class='METAparameter' type="text" name="maxgene" id="maxgene" value="500">
        </td>


      </tr>

      <tr name="Min Genes in Gene Sets">

        <td>Min Genes in Gene Sets:</td>

        <td name="val3"><input class='METAparameter' type="text" name="mingene" id="mingene" value="10">
        </td>

      </tr>

      <tr name="Min Overlap Allowed for Merging">

        <td>Min Module Overlap Allowed for Merging:</td>

        <td name="val5"><input class='METAparameter' id="minoverlap" type="text" name="overlap" id="minoverlap" value="0.33">
        </td>


      </tr>

      <tr name="MSEA FDR cutoff">

        <td>
          MSEA to KDA export Meta FDR cutoff:
          <div class="alert alert-warning" style="margin: 0 auto; width: 90%;margin-top: 10px;">
            <i class="icon-warning-sign" style="margin-right: 6px;font-size: 12px;"></i>This parameter is used for exporting meta results (significant modules) to KDA. This cutoff applies to the meta FDR value, and the module also has to pass the FDR cutoffs for all the individual datasets (default is 50% for the individual datasets). If no modules pass this significance level, the top 10 pathways will be exported to KDA. Make note if this is the case and interpret downstream results cautiously.
          </div>
        </td>

        <td name="val7"><input class='METAparameter' type="text" name="metafdr" id="metafdr" value="25"></td>




      </tr>

    </tbody>
  </table>
</div>

<!-- <a href="#" class="runm button-inner" id="GWAS_type">GWAS <br> Enrichment</a> -->
<h5 style="color: #00004d;">Enter your e-mail id for job completion notification (Optional)
  <?php if (isset($_GET['METAemail']) ? $_GET['METAemail'] : null) {
  ?>
    <div class="alert alert-success" style="display: inline-flex; padding: 5px;">
      <i class="i-rounded i-small icon-check" style="background-color: #2ea92e;"></i><strong style="margin-top: 5px;">
        <?php
        print($newid);
        ?>
      </strong>
    </div>
  <?php
  } else {
  ?>

    <input type="text" name="METAemail" id="yourEmail_META">

    <button type="button" class="button button-3d button-small nomargin" id="METAemailSubmit">Send email</button>
  <?php
  }

  ?>
</h5>


<div style="text-align: center;margin-bottom: 0px;">


  <div class="button-wrapper">
    <div id="myRunMETA_div" class="button-container button-outer" style="display: table;height: 75px;width: 40%;margin:0 auto;">
      <a href="#" class="runm button-inner" id="myRunMETA" style="font-size: 16px !important;"> Run Meta-MSEA pipeline</a>
    </div>
  </div>


</div>


<script src="include/js/bs-datatable.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $(document).scrollTop($("#head_desc").offset().top);
    var string = "<?php echo $sessionID; ?>";
    var meta_string = "<?php echo $meta_sessionID; ?>";
    var module_set_file = null;
    var module_info_file = null;
    var maxgene = null;
    var mingene = null;
    var minoverlap = null;
    var metafdr = null;
    var GSETConvert = null;
    var GSET_target_path="<?php echo $FILE_UPLOAD;?>";
    function METAdone() //This function starts the submission of the job
    {
      $.ajax({
        url: "run_META.php",
        method: "GET",
        data: {
          sessionID: string,
          metasessionID: meta_string,
          module: module_set_file,
          module_info: module_info_file,
          // perm_type: permtype,
          max_gene: maxgene,
          min_gene: mingene,
          minoverlap: minoverlap,
          GSETConvert: GSETConvert,
          metafdr: metafdr,
        },
        success: function(data) {
          $('#myMETA').html(data);
        }
      });
      $("#METAtogglet").html("<i class='toggle-closed icon-remove-circle'></i><i class='toggle-open icon-remove-circle'></i><div class='capital'>Step 1: Meta-MSEA</div>");
      $('#METAtab1').html("Results");
    }

    /* Formatting function for row details - modify as you need */
    function format(d) {
      // `d` is the original data object for the row
      return '<div class="slider">' +
        '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
        '<tr>' +
        '<td style="width:50%;">Permutation Type:</td>' +
        '<td style="width:50%;">' + d.perm + '</td>' +
        '</tr>' +
        /*
        '<tr>' +
        '<td style="width:50%;">Max Genes in Gene Sets:</td>' +
        '<td style="width:50%;">' + d.maxgenes + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td style="width:50%;">Min Genes in Gene Sets:</td>' +
        '<td style="width:50%;">' + d.mingenes + '</td>' +
        '</tr>' +
        */
        '<tr>' +
        '<td style="width:50%;">Max Gene Overlap Allowed for Merging:</td>' +
        '<td style="width:50%;">' + d.maxoverlap + '</td>' +
        '</tr>' +
        /*
        '<tr>' +
        '<td style="width:50%;">Min Overlap Allowed for Merging:</td>' +
        '<td style="width:50%;">' + d.minoverlap + '</td>' +
        '</tr>' +
        */
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
    // setTimeout(function() {
    //     $($.fn.dataTable.tables(true)).DataTable()
    //         .columns.adjust();
    // }, 500);
    var table;

    setTimeout(function() {
      table = $('#metareviewtable').DataTable({
        //responsive: true,
        "paging": false,
        "autoWidth": false,
        "ajax": "./Data/Pipeline/Resources/meta_temp/" + meta_string + "data.json",
        "scrollX": true,
        "columnDefs": [{
          "targets": [2],
          "data": "description",
          "render": function(data, type, row, meta) {
            data = JSON.stringify(data)
              .replace(/^.*[\\\/]/, '') //get File name from full path
              .replace(row["session"], '')
              .replace('"', '');
            data = data.replace('"', '');
            return data; //replace session id from uploaded file
          },
        }, {
          "targets": [3],
          "data": "description",
          "render": function(data, type, row, meta) {

            var value = "";
            for (var i in data) {
              value = value + ", " + data[i].split('/').reverse()[0];
            }

            return value.substring(1); //replace session id from uploaded file
          },
        }],
        "columns": [{
            "class": 'details-control',
            "orderable": false,
            "data": null,
            "width": "8%",
            "target": 0,
            "defaultContent": ''
          },
          {
            "data": "enrichment",
            "width": "28%",
            "target": 1
          },
          {
            "data": "association",
            "width": "28%",
            "target": 2
          },
          {
            "data": "marker",
            "width": "28%",
            "target": 3
          },
          {
            "data": null,
            "width": "8%",
            "sortable": false,
            "target": 4,
            "defaultContent": "<button>Delete</button>"
          }
        ],
        "order": [
          [1, 'asc']
        ],
        "initComplete": function(settings, json) {

          $('#metareviewtable tbody').on('click', 'button', function() {
            var data = table.row($(this).parents('tr')).data();
            var con = confirm("Are you sure you want to delete this " + data.enrichment + " enrichment?");
            if (con) {
              METAdelete(data.session);
              table.row($(this).parents('tr')).remove().draw(false);
              if (!table.data().any()) {
                //If table is empty rollback session.txt file to previous step.
                $("#myMETA").load("/META_buttons.php?rollback=T&metasessionID=" + meta_string);
              }
            }
          });
        }
      });
      $(window).resize(function() {
        $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
      });

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
    }, 900);



    function METAdelete($rowsession) //This function gets the review table for MSEA
    {
      $.ajax({
        url: 'META_delete.php',
        type: 'GET',
        data: {
          sessiondelete: $rowsession,
          sessionID: string,
          metasessionID: meta_string
        },
        success: function(data) {}
      });
    }

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


    $("#METAemailSubmit").on('click', function() {
      var email = $("input[name=METAemail]").val();
      $('#myMETA').empty();
      $('#myMETA').load("/META_moduleprogress.php?METAemail=" + email + "&metasessionID=" + meta_string);
      return false;

    });


    $("#myAddAssociation").on('click', function() {
      $("#myMETA").empty();

      $("#myMETA").load("/META_buttons.php?oldsession=" + string + "&metasessionID=" + meta_string, function() {
        $("#myMETA").hide().slideDown('slow');
      });
    });

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

    $('select.META').on('change', function() {
      var select = $(this).find('option:selected').index();
      if (select != 1)
        $(this).parent().next().hide();

      if (select == 1)
        $(this).parent().next().show();

      if (select > 1)
        $(this).parent().nextAll(".alert-META").eq(0).html(successalert).hide().fadeIn(300);
      else if (select == 1)
        $(this).parent().nextAll(".alert-META").eq(0).html(uploadalert).hide().fadeIn(300);
      else
        $(this).parent().nextAll(".alert-META").eq(0).empty();
    });

    $('select.META').each(function() {
      $(this).trigger('change');
    });

    $('#module').on('change', function() {
      var select = $(this).find('option:selected').index();
      if (select == 1) {
        $('#gsetd_row').show();
      } else {
        $('#gsetd_row').hide();
      }
    });

    //GeneSet UPLOAD EVENT HANDLER
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
        fd.append("path", GSET_target_path);
        fd.append("data_type", "gene_set");
        fd.append("session_id", meta_string);
        console.log(GSET_target_path);
        console.log(meta_string);
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
            
            console.log(resp);
            $('#GSETprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
            $('#GSETprogressbar').hide();
            if (resp.status == 1) {
              var fullPath = resp.targetPath;
              module_set_file = fullPath.replace("/var/www/mergeomics/html/./Data/Pipeline/", "");
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

    //GeneSetDesc upload event handler
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
        fd.append("path", GSET_target_path);
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

    $("#myRunMETA").on('click', function() {
      if (!table.data().any()) {
        $('#errorp_META').html('No Association Files are selected!');
        $("#errormsg_META").fadeTo(2000, 500).slideUp(500, function() {
          $("#errormsg_META").slideUp(500);
        });
      } else if (table.data().count() == 1) {
        $('#errorp_META').html('Only one association data input! Add more datasets for a meta-analysis.');
        $("#errormsg_META").fadeTo(2000, 800).slideUp(800, function() {
          $("#errormsg_META").slideUp(800);
        });
      } else {
        if ($("#module").val() != "private_data") {
          module_set_file = $("#module").val();
        }
        if ($("#module_info").val() != "private_data") {
          if ($("#module").val() != "private_data") { // user chose sample
            module_info_file = module_set_file.replace(".txt", "_info.txt");
          } else { // user chose to upload module sets but left option as "No Gene Sets Description"
            module_info_file = $("#module_info").val();
          }
        }

        GSETConvert = $("#GSETConvert").val();

        var select = $("select[name='formChoice_SSEA'] option:selected").index();
        var select2 = $("select[name='formChoice2_SSEA'] option:selected").index();
        var errorlist = [];

        // not sure why this isn't working. says errorlist is not defined
        /*
        var selectarray = [select, select2];
        selectarray.forEach(myFunction);
        */

        $('.METAparameter').each(function() {
          if ($(this).val() == "") {
            errorlist.push($(this).parent().parent().attr('name') + ' is empty!');

          }
        });

        maxgene = $("#maxgene").val();
        mingene = $("#mingene").val();
        minoverlap = $("#minoverlap").val();
        metafdr = $("#metafdr").val();
        if (errorlist.length === 0) {
          if (select === 0) {
            $('#errorp_META').html('No marker set selected!');
            $("#errormsg_META").fadeTo(2000, 500).slideUp(500, function() {
              $("#errormsg_META").slideUp(500);
            });
          } else {
            /*
            if (select2 === 0) {
              $('#errorp_META').html('No marker set descriptions selected! If none, select No Gene Sets Description');
              $("#errormsg_META").fadeTo(2000, 500).slideUp(500, function() {
                $("#errormsg_META").slideUp(1000);
              });
            } else {
            */
            $("#myMETA").empty();
            /*
            $("#myMETA").load("/run_META.php?sessionID=" + string + "&metasessionID=" + meta_string, function() {
                $("#myMETA").hide().slideDown('slow');
            });*/
            METAdone();
            //}

          }
        } else {
          var result = errorlist.join("\n");
          //alert(result);
          $('#errorp_META').html(result);
          $("#errormsg_META").fadeTo(2000, 500).slideUp(500, function() {
            $("#errormsg_META").slideUp(500);
          });
        }
      }
    });

    // set up select boxes
    $('.selectholder.META').each(function() {
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
          $('.activeselectholder.META').each(function() {
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
    $('.selectholder.META .selectdropdown span').click(function() {
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
      var container = $(".selectholder.META");

      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        $('.activeselectholder.META').each(function() {
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

    // Added by Jess tutorial
    var myTutButton_meta = document.getElementById("myTutButton_meta");
    var val_meta = 0;

    //begin function for when button is clicked-------------------------------------------------------------->
    myTutButton_meta.addEventListener("click", function() {

      //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
      var $this_meta = $(this);

      //If tutorial is already opened yet, then do this-------------------------------------------------------------->
      if ($this_meta.data('clicked')) {


        $('.tutorialbox').hide();

        $('#METAparameterstable').find('tr').each(function() {
          $(this).find('td[name="tut"]').eq(-1).remove();
          $(this).find('th[name="tut"]').eq(-1).remove();
        });


        $this_meta.data('clicked', false);
        val_meta = val_meta - 1;
        $("#myTutButton_meta").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

      }

      //If tutorial is not opened yet, then do this-------------------------------------------------------------->
      else {
        $this_meta.data('clicked', true);
        val_meta = val_meta + 1; //val counter to not duplicate prepend function


        if (val_meta == 1) //Only prepend the tutorial once
        {


          $('#METAparameterstable').find('td[name="val2"]').eq(-1).after(`

                                <td name="tut">
                                <strong>Max Genes in Gene Sets</strong>: defines the maximum gene number that a gene set can have. <br>
                                        <strong>Options</strong>: Number between 2 and 10,000; suggested between 200-800 <br>
                                        <strong>Default value</strong>: 500
                                 </td>

                                `);
          $('#METAparameterstable').find('td[name="val3"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Min Genes in Gene Sets</strong>: defines the minimal gene number that a gene set can have. <br>
                                        <strong>Options</strong>: Number between 2 and less than Max Genes in Gene Sets <br>
                                        <strong>Default value</strong>: 10
                                </td>`);


          $('#METAparameterstable').find('td[name="val5"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Min Module Overlap Allowed for Merging</strong>: defines the minimum overlap ratio between gene sets if the user prefers to merge overlapping gene sets that are associated with the disease/trait as determined by MSEA into merged supersets. Modules with overlap ratios over this value will be merged.<br>
                                   <strong>Options</strong>: 0 to 1 (Use 1 to skip merging) <br>
                                    <strong>Default value</strong>: 0.33 (33% overlap)
                                </td>`);


          $('#METAparameterstable').find('td[name="val7"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Meta-MSEA to KDA export FDR cutoff</strong>: FDR should within the specified FDR cutoff. <br>
                                    <strong>Options</strong>: Between 0 to 25 (25 is 25%) <br>
                                    <strong>Default value</strong>: 25
                                </td>`);

          $('#METAparameterstable').find('th[name="val"]').eq(-1).after('<th name="tut">Comments</th>');

          $('.tutorialbox').show();
          $('.tutorialbox').html('The purpose of the pipeline is to take an association dataset for a given disease or phenotype from the user as input and integrate the association data with functional genomics information, pathways, and gene networks to derive pathways, gene networks and key regulatory genes for the disease or phenotype.');


        }
        $("#myTutButton_meta").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
      }


    });
  });
</script>