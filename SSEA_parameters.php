<?php
include "functions.php";
//This parameters files is for when the user chooses MDF in mergeomics
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";


/* Initialize PHP variables
sessionID = the saved session 

rmchoice = type of pipeline chouce

GET = if the user enters the link directly
POST = if PHP enters the link

*/

#$FILE_UPLOAD=$ROOT_DIR."Data/Pipeline/Resources/ssea_temp/";
//This function creates a random session ID string
if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
}
//If the user submits the form, get the sessionID
if (isset($_POST['sessionID'])) {
  $sessionID = $_POST['sessionID'];
}


/* Initializes form data variables. Imo this is not very efficient, but this is what was here before. So I didn't change it.
There are definitely better ways to do this though...

 */

/***************************************
Session ID
Since we don't have a database, we have to update the txt file with the path information
 ***************************************/
$fsession = $ROOT_DIR ."Data/Pipeline/Resources/session/$sessionID" . "_session.txt";

if (file_exists($fsession)) {

  $data = file($fsession); // reads an array of lines
  function replace_a_line($data)
  {
    if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.75")) {
      return 'Mergeomics_Path:' . "\t" . "2" . "\n";
    }
    return $data;
  }
  $data = array_map('replace_a_line', $data);
  file_put_contents($fsession, implode('', $data));
}




$fjson = $ROOT_DIR . "Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
$json = json_decode(file_get_contents($fjson),true)["data"][0];

$marker_association = $json["association"];
$mapping = $json["marker"];

?>

<!-- Error message div for SSEA ===================================================== -->
<div id="errormsg_SSEA" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_SSEA" style="white-space: pre;"></p>
</div>
</div>




<!-- Grid container for SSEA ===================================================== -->
<div class="gridcontainer">

  <!-- Description ===================================================== -->
  <h4 class="instructiontext">
    This part of the pipeline starts from MSEA and then it gives the option for performing wKDA on MSEA results.
  </h4>

  <!--Start ssea Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_SSEA"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox' style="display: none;"></div>
  <!--End ssea Tutorial --------------------------------------->


</div>
<!--End of grid container --->

<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="SSEA_parameters.php" name="select2" id="SSEAdataform">
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
          <td data-column="File type &#xa;"> Gene Sets

            <div class="informationtext" data-toggle="modal" data-target="#GSETinfomodal" href="#GSETinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>

          </td>
          <!--Second row|first column of table------------------------------------------>
          <td data-column="Upload/Select File &#xa;">
            <!--Start MDF Upload ----------------------------->
            <div id="Selectupload_GSET" class="selectholder SSEA" align="center">
              <select class="SSEA" name="formChoice_SSEA" size="1" id="module">
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
            </div>
            <!--End selectupload_Gset div-->
            <!-- Gene Set File Upload div --->
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

            <div class="alert-SSEA" id="alert_GSET"></div>
            <!--Div to alert user of certain comment (i.e. success) -->


          </td>
          <!--Second row|second column of table------------------------------------------>
          <td data-column="Sample Format &#xa;" name="val1_SSEA">
            <!--Start Second row|third column of table------------------------------------------>

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="Name of marker set (I.e. canonical pathway or co-expression module)" style="position: relative;">MODULE</a></th>
                    <th><a href="#">GENE</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="MARKER(Header): ">Cell cycle</td>
                    <td data-column="GENE(Header): ">CDC16</td>

                  </tr>
                  <tr>
                    <td data-column="MARKER(Header): ">Cell cycle</td>
                    <td data-column="GENE(Header): ">ANAPC1</td>

                  </tr>
                  <tr>
                    <td data-column="MARKER(Header): ">WGCNA Brown</td>
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
          <td data-column="File type &#xa;">Gene Sets Description<br><strong>(Optional)</strong>

            <div class="informationtext" data-toggle="modal" data-target="#GSETDinfomodal" href="#GSETDinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>

          </td>
          <td data-column="Upload/Select File &#xa;">
            <!--Start Gene Set Description Input ----------------------------->
            <div id="Selectupload_GSETD" class="selectholder SSEA" align="center">
              <select class="SSEA" name="formChoice2_SSEA" size="1" id="module_info">
                <option value="0">Please select option</option>
                <option value="private_data">Upload Gene Sets descriptions</option>
                <option value="no" selected>No Gene Sets Description</option>
              </select>



            </div> <!-- End Gene Set Description input -->
            <!--Gene Set Description Upload div --->
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


            <div class="alert-SSEA" id="alert_GSETD"></div>
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
          <!---------------Start of Permutation input ------------->
          <td>Permutation type:</td>

          <td name="val1">
            <div class="selectholder SSEA">
              <select class="btn dropdown-toggle btn-light" name="permuttype" id="permuttype" size="1">
                <option value="gene" selected>Gene</option>
                <option value="marker">Marker</option>
              </select>
            </div>
          </td>



        </tr>
        <!---------------Start of Max Genes in Gene Sets input ------------->
        <tr name="Max Genes in Gene Sets">

          <td>Max Genes in Gene Sets:</td>

          <td name="val2">
            <input class='sseaparameter' type="text" name="maxgene" id="maxgene" value="500">
          </td>


        </tr>
        <!---------------Start of Min Genes in Gene Sets input ------------->
        <tr name="Min Genes in Gene Sets">

          <td>Min Genes in Gene Sets:</td>

          <td name="val3">
            <input class='sseaparameter' type="text" name="mingene" id="mingene" value="10">
          </td>

        </tr>
        <!---------------Start of Max Overlap for Merging Gene Mapping input ------------->
        <tr name="Max Overlap for Merging Gene Mapping">

          <td>Max Overlap for Merging Gene Mapping:</td>

          <td name="val4">
            <input class='sseaparameter' id="maxoverlap" type="text" name="gene_overlap" value="0.33">
          </td>


        </tr>
        <!---------------Start of Min Overlap Allowed for Merging input ------------->
        <tr name="Min Overlap Allowed for Merging">

          <td>Min Module Overlap Allowed for Merging:</td>

          <td name="val5">
            <input class='sseaparameter' id="minoverlap" type="text" name="overlap" value="0.33">
          </td>


        </tr>
        <!---------------Start of Number of Permutations input ------------->
        <tr name="Number of Permutations">

          <td>Number of Permutations:</td>

          <td name="val6">
            <input class='sseaparameter' id="sseanperm" type="text" name="permu" value="2000">
          </td>

        </tr>
        <!---------------Start of MSEA FDR cutoff input ------------->
        <tr name="MSEA FDR cutoff">

          <td>MSEA to KDA export FDR cutoff:<br>
            <div class="alert alert-warning" style="margin: 0 auto; width: 90%;margin-top: 10px;">
              <i class="icon-warning-sign" style="margin-right: 6px;font-size: 12px;"></i>This parameter is used for exporting results to KDA. If no modules pass this significance level, the top 10 pathways will be exported to KDA. Make note if this is the case and interpret downstream results cautiously.
            </div>
          </td>
          <td name="val7">
            <input class='sseaparameter' id="sseafdr" type="text" name="sseafdr" value="5">
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
  var marker_association_file = "<?php echo $marker_association; ?>";
  var mapping_file = "<?php echo $mapping; ?>";
  var module_set_file = null;
  var module_info_file = null;
  var permtype = null;
  var maxgene = null;
  var mingene = null;
  var maxoverlap = null;
  var minoverlap = null;
  var sseanperm = null;
  var sseafdr = null;
  var GSETConvert = null;
  var file_upload_target_path="Resources/ssea_temp/";
  $("#MDFflowChart").next().addClass("activeArrow");
  $("#MSEAflowChart").addClass("activePipe").html('<a href="#SSEAtoggle" class="pipelineNav" id="SSEAtoggleNav">MSEA</a>').css("opacity","1");

  $("#SSEAtoggleNav").on('click', function(e){
    var href = $(this).attr('href');
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

  $('html,body').animate({
    scrollTop: $("#MDFtoggle").offset().top
  }); //scrolls to the bottom



  /**********************************************************************************************
  Review and submit functions
  ***********************************************************************************************/
  function SSEAreview() //This function gets the review table for SSEA
  {
    $.ajax({
      url: "SSEA_moduleprogress.php",
      method: "GET",
      data: {
        sessionID: session_id,
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
    SSEAreview()

    // $.ajax({
    //   'url': 'SSEA_parameters.php',
    //   'type': 'POST',
    //   'data': form_data,
    //   processData: false,
    //   contentType: false,
    //   'success': function(data) {
    //     $("#mySSEA").html(data);

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
      select2 = $("select[name='formChoice2_SSEA'] option:selected").index();


    var selectarray = [select, select2];
    var idarray = ['GSETuploadInput', 'GSETDuploadInput'];
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


    $('.sseaparameter').each(function() {
      if ($(this).val() == "") {
        errorlist.push($(this).parent().parent().attr('name') + ' is empty!');

      }
    });

    if (errorlist.length === 0) {
      $(this).html('Please wait ...');
      //.attr('disabled', 'disabled');
      setTimeout(function() {

        GSETConvert = $("#GSETConvert").val();

        if ($("#module").val() != "private_data") {
          module_set_file = $("#module").val();
          GSETConvert = "none";
        }
        if ($("#module_info").val() != "private_data") {
          module_info_file = $("#module_info").val();
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
  //Can only input values from 0-1. If they try to type "2", it won't appear
  $("#minoverlap, #maxoverlap").inputFilter(function(value) {
    return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });
  //Can only input values from 0-25. If they try to type "50", it won't appear
  $("#sseafdr").inputFilter(function(value) {
    return /^\d*$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 25));
  });

  ///////////////////////////////////////////////End Validation/REVIEW button/////////////////////////////////////////////////////////////




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

  /**********************************************************************************************
  Tutorial Button Script -- Append the tutorial to the form
  ***********************************************************************************************/

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
                                <strong>Permutation type</strong>: Gene-based permutation to estimate statistical significance p-values is recommended for GWAS. This is more stringent, and the user can choose to run marker-based permutation but it may be biased toward genes with many markers.<br>
                                    <strong>Default value</strong>: Gene
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
                                    <strong>Max Overlap in Merging Gene Mapping</strong>: Overlap ratio threshold for merging genes with shared markers (SNPs). Over this overlap ratio, the genes will be merged. <br>
                                    <strong>Options</strong>: 0 to 1 (Use 1 to skip merging) <br>
                                    <strong>Default Value</strong>: 0.33
                                </td>`);

        $('#SSEAparameterstable').find('td[name="val5"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Min Module Overlap Allowed for Merging</strong>: Minimum gene overlap ratio between modules that will have them merged (to merge redundant modules). For instance, for the default value of 0.33, the modules need to have an overlap ratio of 0.33 or greater to be merged. This is to reduce artefacts from shared markers. <br>
                                   <strong>Options</strong>: 0 to 1 (Use 1 to skip merging) <br>
                                    <strong>Default value</strong>: 0.33 (33% overlap)
                                </td>`);


        $('#SSEAparameterstable').find('td[name="val6"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Number of Permutations</strong>: the number of permutations conducted to estimate statistical significance <br>
                                   <strong>Options</strong>: 1000 to 20,000 (for publication, we recommend >= 10,000) <br>
                                    <strong>Default value</strong>: 2000
                                </td>`);

        $('#SSEAparameterstable').find('td[name="val7"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>MSEA to KDA export FDR cutoff</strong>: Gene sets must pass this FDR threshold to be exported to KDA. We recommend 5 (5%) for formal analysis. If no gene sets pass, the top 10 will be used in KDA. <br>
                                    <strong>Options</strong>: Between 0 to 25 (25 is 25%) <br>
                                    <strong>Default value</strong>: 5
                                </td>`);

        $('#SSEAparameterstable').find('th[name="val"]').eq(-1).after('<th name="tut">Tutorial</th>');

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

  //Gene Sets File  
  $('#GSETuploadInput').on("change", function() {
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
      fd.append("path", file_upload_target_path);
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



  //Gene Sets Description File   
  $("#GSETDuploadInput").on("change", function() {
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
      fd.append("path", file_upload_target_path);
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
</script>