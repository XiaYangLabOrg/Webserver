<?php
include "functions.php";
ini_set("memory_limit", "128M");

$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  $sessionID = $_POST['sessionID'];
} else if (isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {
  //If sessionID is received from post call, it means its from session loading
  $sessionID = $_GET['sessionID'];
} else {
  $sessionID = generateRandomString(10);
}

$fsession = $ROOT_DIR . "Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$session_write = NULL;
//initiate session file
if (!file_exists($fsession)) {
  $sessionfile = fopen($fsession, "w");
  $session_write .= "Pipeline:" . "\t" . "Pharmomics_App1" . "\n";
  $session_write .= "Pharmomics_Path:" . "\t" . "1.0" . "\n";
  fwrite($sessionfile, $session_write);
  fclose($sessionfile);
  chmod($fsession, 0755);
}


$index_file = file_get_contents("./include/pharmomics/PharmOmics_indexcatalog_Jan2021.json");
$json = json_decode($index_file, true);
$drug_arr = array();
$species_arr = array();

foreach ($json as $drug) {
  array_push($drug_arr, $drug['Drug name']);
}

$drug_list = array_unique($drug_arr);
sort($drug_list);
//echo nl2br(join("\n", $drug_list));
//echo nl2br(join("\n", $species_list));

?>

<style type="text/css">
  .buttonp {
    display: inline-block;
    position: relative;
    cursor: pointer;
    outline: none;
    white-space: nowrap;
    margin: 5px;
    padding: 0 22px;
    font-size: 14px;
    height: 40px;
    line-height: 40px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none;
    color: #333;
    text-shadow: none !important;
    border-radius: 3px;
    border-bottom: 3px solid rgba(0, 0, 0, 0.15);
    background-color: #e7e7e7b3;
    transition: 200ms linear;
  }

  .buttonp:active,
  .button:active {
    top: 2px;
    box-shadow: none;
  }

  .buttonp:hover {
    background-color: darkgray;
    color: black;
  }

  i {
    padding-right: 8px;
  }

  div#Info_tabs,
  div#APP1_organs_tabs {
    /* Modified: margin size and added Info_tabs */
    margin: 0px 0 0 0 !important;
  }

  div#APP1_species_tabs {
    /* Modified: margin size and added Info_tabs */
    margin: 20px 0 0 0 !important;
  }

  .comparison_pic:hover {
    opacity: 80%;
    cursor: pointer;
  }

  #GeneRegulation,
  #SpeciesOrganComparison {
    /* Modified */
    font-size: 15px;
  }

  .tableresult::-webkit-scrollbar {
    display: none;
  }

  /*table td {  
  max-width: 120px;
  max-height: 50px;
  overflow: scroll;
}*/

  h4 {
    margin: 20px 0 0 0 !important;
  }

  /* added by Jess 
.ajaxloading {
    display:    none;
    /*position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
}

body.loading .ajaxloading {
    overflow: hidden;   
}

// Anytime the body has the loading class, our modal element will be visible 
body.loading .ajaxloading {
    display: block;
}
*/

.dataTables_info {
	margin-bottom: 1%;
}

</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css" />
<div id="errormsg_app1" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_app1" style="white-space: pre;"></p>
</div>


</div>


<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">
  <!--Added by Jess
  <div class="ajaxloading" style="text-align: center;padding: 20px 0px 0 20px;font-size: 18px;">
    <div class="text">
      <span>Loading dose segregated data</span><span class="dots">...</span>
    </div>
  </div>------>

  <!-- Description ===================================================== -->
  <h4 class="instructiontext">
    This part of the pipeline gives gene regulation information for a drug and performs gene/pathway cross tissue-species comparison based on user input
  </h4>

  <!-- Download data ==========
  <div style="text-align: center;">
       <a href="include/pharmomics/PharmOmics_allDEG.json" download="PharmOmics_allDEG.json" class="buttonp" role="button" id="DEG_button"><i class="icon-download1"></i>Whole DEG Download (.json)</a>
        <a href="include/pharmomics/PharmOmics_indexcatalog.json" download="PharmOmics_indexcatalog.json" class="buttonp" role="button" id="index_button"><i class="icon-download1"></i>Whole Index Download (.json)</a>
    </div>-->
  <!--Start app3 Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_app1"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox_app1' style="display: none;"></div>
  <!--End app3Tutorial --------------------------------------->



</div>
<!--End of gridcontainer ----->

<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="app1_parameters.php" name="select" id="app1dataform">
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center;" id="app1networktable">
      <thead>
        <tr>
          <!--First row of table------------Column Headers------------------------------>
          <th colspan="2" name="val_app1">PharmOmics gene and pathway review</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!--Second row of table------------------------------------------>
          <td style="width: 40%;">
            <h4 class="instructiontext" style="font-size: 16px;margin-top: 0 !important;padding-bottom: 10px;">
              Select drug class or drug name of interest
            </h4>
            <select style="width: 100%;" name="drug_name" size="1" id="myDrugName">
              <?php
              foreach ($drug_list as $item) {
                #debug_to_console("$item");
                echo "<option value = \"$item\">$item</option>";
                #echo $item;
              }

              ?>
            </select>

            <h4 class="instructiontext" style="font-size: 16px;margin-top: 0 !important;padding-bottom: 10px;">
              Select species of interest
            </h4>
            <div id="mySpecies">
              <p style="margin-bottom: 0;">Please select a drug first</p>
            </div>
            <h4 class="instructiontext" style="font-size: 16px;margin-top: 0 !important;padding-bottom: 10px;">
              Select tissue of interest
            </h4>
            <div id="myOrgans">
              <p>Please select a drug first</p>
            </div>
            <!--Jess moved buttons-------------------------->
            <div style="text-align: center;display:none;" id="myComboButtons">
              <button class="buttonp" id="drugclass_button"><i class="icon-enter"></i>Run DEGs comparison</button>
              <button class="buttonp" id="pathways_button"><i class="icon-enter"></i>Run pathways comparison</button>
            </div>
          </td>
          <td>
            <!--Second column-------------------------------->
           <h4 style="font-size: 16px;padding-top: 0;margin-top: 0 !important;">
            Studies Curated
          </h4>
            <table id="datatable_app1" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <!--Header names are overwritten anyway?----------->
                  <th>Drug Name</th>
                  <th>Study Name</th>
                  <th>Organ/Tissue</th>
                  <th>Organism</th>
                  <th>Weblink</th>
                </tr>
              </thead>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
    <div id="preloader" class="instructiontext" style="text-align: center;display:none;"></div>
    <table>
      <!-- added by Jess, new table, no header, add no style for now -->
      <tr>
        <td>
          <div class="tabs tabs-bordered clearfix" id="Info_tabs">
            <!-- Start tab headers -->
            <ul class="tab-nav clearfix">
              <li><a id="GeneRegulation" href="#myGeneRegulation">Gene Regulation</a></li>
              <li><a id="PathwayRegulation" href="#myPathwayRegulation">Pathway Regulation</a></li>
              <li><a id="SpeciesOrganComparison" href="#mySpeciesOrganComparison">Species/Tissue Comparison</a></li>
            </ul>
            <!-- Start <div class="tab-container" id="species_tab_containers">   TAB container -->
            <!-- Table for gene regulation -->
            <div class="tab-content clearfix" id="myGeneRegulation">
              <h4>
                PharmOmics Meta
              </h4>
              <p style="text-align: left;margin-bottom:0px; padding: 1% 5%;">
                The differential gene expression method used was characteristic direction. Studies of different doses and treatment durations were combined and meta-analyzed. Shown are the top 50 significantly regulated genes, with orange genes being upregulated and blue being downregulated. The genes displayed are ordered by signficance (p-value). Only differentially expressed genes (DEGs) that are FDR<0.05 are shown (may be less than 50).
              </p>
              <table id="datatable_deg_app1" class="table table-striped table-bordered-pharm" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Drug</th>
                    <th>Species</th>
                    <th>Tissue</th>
                    <th>Top DEGs</th>
                    <th>Dataset</th>
                  </tr>
                </thead>
              </table>
              <h4>
                PharmOmics Dose Segregated
              </h4>
              <p style="text-align: left;margin-bottom:0px; padding: 1% 5%;">
                The DEG method used was Limma. Shown are the top 50 significantly regulated genes, with orange genes being upregulated and blue being downregulated. The genes displayed are ordered by signficance (p-value). Only differentially expressed genes (DEGs) that are FDR<0.05 are shown (may be less than 50).
              </p>
              <table id="datatable_deg_dose_app1" class="table table-striped table-bordered-pharm" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Drug</th>
                    <th>Species</th>
                    <th>Tissue</th>
                    <th>Study</th>
                    <th>Dose</th>
                    <th>Time</th>
                    <th>Control sample size</th>
                    <th>Treatment sample size</th>
                    <th>Top DEGs</th>
                    <th>Dataset</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-content clearfix" id="myPathwayRegulation">
              <h4>
                PharmOmics Meta
              </h4>
              <p style="text-align: left;margin-bottom:0px; padding: 1% 5%;">
                The DEG method used was characteristic direction. Pathway enrichment tool used was Enrichr. Shown are the top 10 KEGG pathways (ordered by significance) followed by the top 10 GO pathways (ordered by significance).
              </p>
              <table id="datatable_pathway_app1" class="table table-striped table-bordered-pharm" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Drug</th>
                    <th>Species</th>
                    <th>Tissue</th>
                    <th>Pathway</th>
                    <th>Dataset</th>
                  </tr>
                </thead>
              </table>
              <h4>
                PharmOmics Dose Segregated
              </h4>
              <p style="text-align: left;margin-bottom:0px; padding: 1% 5%;">
                The DEG method used was Limma. Pathway enrichment tools used were Enrichr and ROntoTools ('Network' enrichment).
                Shown are the top 10 KEGG pathways (ordered by significance) followed by the top 10 GO pathways (ordered by significance).
              </p>
              <table id="datatable_pathway_doseSeg_app1" class="table table-striped table-bordered-pharm" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Drug</th>
                    <th>Species</th>
                    <th>Tissue</th>
                    <th>Study</th>
                    <th>Dose</th>
                    <th>Time</th>
                    <th>Control sample size</th>
                    <th>Treatment sample size</th>
                    <th>Enrichment</th>
                    <th>Pathway</th>
                    <th>Dataset</th>
                  </tr>
                </thead>
              </table>
            </div>

            <!-- Subtabs for Species/Organ Comparison -->
            <div class="tab-content clearfix" id="mySpeciesOrganComparison">
              <!-- Beginning of what was on the web server previously-->
              <h4>Species Comparison</h4>
              <div class="tabs tabs-bordered clearfix" id="APP1_species_tabs">
                <!-- Start tab headers -->
                <ul class="tab-nav clearfix">
                  <li><a id="APP1_species_tab1" href="#mySpecies_comparison">Degree of DEG Overlap</a></li>
                  <li><a id="APP1_species_tab2" href="#mySpecies_geneoverlap">DEG Overlap Summary</a></li>
                  <li><a id="APP1_species_tab3" href="#mySpecies_pathway">Degree of Pathway Overlap</a></li>
                  <li><a id="APP1_species_tab5" href="#mySpecies_pathoverlap">Pathway Overlap Summary</a></li>
                </ul>
                <div class="tab-container" id="species_tab_containers">
                  <!-- Start TAB container -->
                  <div class="tab-content clearfix" id="mySpecies_comparison" name="Cross-Species Gene Comparison">
                    <p class="instructiontext" style="font-size: 20px;"> Click "Run DEGs comparison" once selections are made (multiple species, one tissue) </p>
                  </div>
                  <div class="tab-content clearfix" id="mySpecies_geneoverlap">
                    <p id="speciesCompOverlapPreText" class="instructiontext" style="font-size: 20px;"> Click "Run DEGs comparison" once selections are made (multiple species, one tissue) </p>
                    <table id="datatable_speciesCompOverlap" class="table table-striped table-bordered-pharm" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Tissue</th>
                          <th>Gene</th>
                          <th>Species_Direction</th>
                        </tr>
                      </thead>
                    </table>
                    <div id="downloadSpeciesDEG" style="padding:0 30%;">
                    </div>
                  </div>
                  <div class="tab-content clearfix" id="mySpecies_pathway" name="Cross-Species Pathway Comparison">
                    <p class="instructiontext" style="font-size: 20px;"> Click "Run pathways comparison" once selections are made (multiple species, one tissue) </p>
                  </div>
                  <div class="tab-content clearfix" id="mySpecies_pathoverlap">
                    <p id="speciesPathOverlapPreText" class="instructiontext" style="font-size: 20px;"> Click "Run pathways comparison" once selections are made (multiple species, one tissue) </p>
                    <table id="datatable_speciesPathOverlap" class="table table-striped table-bordered-pharm" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Pathway</th>
                          <th>Species</th>
                          <th>Tissue</th>
                        </tr>
                      </thead>
                    </table>
                    <div id="downloadSpeciesPathIntersect" style="padding:0 30%;">
                    </div>
                  </div>
                </div> <!-- End tab container -->
              </div> <!-- End tab header -->
              <br>
              <h4>Tissue Comparison</h4>
              <div class="tabs tabs-bordered clearfix" id="APP1_organs_tabs">
                <ul class="tab-nav clearfix">
                  <li><a id="APP1_organs_tab1" href="#myOrgans_comparison">Degree of DEG Overlap</a></li>
                  <li><a id="APP1_organs_tab2" href="#myOrgans_geneoverlap">DEG Overlap Summary</a></li>
                  <li><a id="APP1_organs_tab3" href="#myOrgans_pathway">Degree of Pathway Overlap</a></li>
                  <li><a id="APP1_organs_tab5" href="#myOrgans_pathoverlap">Pathway Overlap Summary</a></li>
                </ul>

                <div class="tab-container" id="organ_tab_containers">
                  <div class="tab-content clearfix" id="myOrgans_comparison" name="Cross-Organ Gene Comparison" style="margin-bottom: 3%;">
                    <p class="instructiontext" style="font-size: 20px;"> Click "Run DEGs comparison" once selections are made (multiple tissues, one species)</p>
                  </div>
                  <!--<div class="tab-content clearfix" id="myOrgans_geneoverlap">Coming soon</div>-->
                  <div class="tab-content clearfix" id="myOrgans_geneoverlap">
                    <p id="tissCompOverlapPreText" class="instructiontext" style="font-size: 20px;"> Click "Run DEGs comparison" once selections are made (multiple tissues, one species)</p>
                    <table id="datatable_tissueCompOverlap" class="table table-striped table-bordered-pharm" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Species</th>
                          <th>Gene</th>
                          <th>Tissue_Direction</th>
                        </tr>
                      </thead>
                    </table>
                    <div id="downloadOrganDEG" style="padding:0 30%;">
                    </div>
                  </div>
                  <div class="tab-content clearfix" id="myOrgans_pathway" name="Cross-Organ Pathway Comparison">
                    <p class="instructiontext" style="font-size: 20px;"> Click "Run pathways comparison" once selections are made (multiple tissues, one species) </p>
                  </div>
                  <div class="tab-content clearfix" id="myOrgans_pathoverlap">
                    <p id="tissPathOverlapPreText" class="instructiontext" style="font-size: 20px;"> Click "Run pathways comparison" once selections are made (multiple tissues, one species) </p>
                    <table id="datatable_organPathOverlap" class="table table-striped table-bordered-pharm" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Pathway</th>
                          <th>Tissue</th>
                          <th>Species</th>
                        </tr>
                      </thead>
                    </table>
                    <div id="downloadOrganPathIntersect" style="padding:0 30%;">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End </div>  tab container -->
          </div> <!-- End of Info Tabs -->
        </td>
      </tr>
      <tr>
      	<td>
      		<div id="downloadDrugData"></div>
      	</td>
      </tr>
    </table>
  </div>
</form>
<!--End of app3 form -------------------------------------->




<link href="include/select2.css" rel="stylesheet" />
<script src="include/js/bs-datatable.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

<script type="text/javascript">
  /*added by Jess
$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});
*/


  var string = "<?php echo $sessionID; ?>";
  localStorage.setItem("on_load_session", string);
  $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + string);
  $('#session_id').css("padding", "17px 30px");

  $(function() { // Jess added
    $("#Info_tabs").tabs();
  });

  $(function() {
    $("#APP1_species_tabs").tabs();

    $("#APP1_organs_tabs").tabs();
  });


  var table = $('#datatable_app1').DataTable({
    "searching": false,
    "paging": true,
    "ordering": true,
    "pageLength": 5,
    "dom": "Bfrtlip",
    data: [],
    columns: [{
        "title": "Drug"
      },
      {
        "title": "Study"
      },
      {
        "title": "Tissue"
      },
      {
        "title": "Species"
      },
      {
        "title": "Web link"
      }
    ],
    rowCallback: function(row, data) {},
    "filter": false,
    "info": true,
    "processing": true,
    "retrieve": true
  });

  var table_deg = $('#datatable_deg_app1').DataTable({ // added Jess
    "autowidth": false,
    //"scrollx": true,
    //"scrolly": true,
    "searching": true,
    "paging": true,
    "ordering": true,
    "pageLength": 10,
    "dom": '<"top"f>rt<"bottom"ilp><"clear">',
    //"dom": '<"toolbar">frtip',
    //"dom": '<"pull-left"f><"pull-right"l>tip',
    buttons: [{
      extend: 'excelHtml5',
      text: 'Download table'
    }],
    data: [],
    columns: [{
        "title": "Drug"
      },
      {
        "title": "Species"
      },
      {
        "title": "Tissue"
      },
      {
        "title": "Top DEGs"
      },
      {
        "title": "Dataset"
      }
    ],
    rowCallback: function(row, data) {},
    "filter": false,
    "info": true,
    "processing": true,
    "retrieve": true,
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
        "width": "55%"
      },
      {
        "width": "15%"
      },
      {
        render: function(data, type, full, meta) {
          return "<div style='max-height: 100px; overflow-y: auto;'>" + data + "</div>";
        },
        targets: [3, 4]
      }

    ]
  });

  var table_deg_doseSeg = $('#datatable_deg_dose_app1').DataTable({ // added Jess
    "autowidth": false,
    //"scrollx": true,
    //"scrolly": true,
    "searching": true,
    "paging": true,
    "ordering": true,
    "pageLength": 10,
    //"dom": "Bfrtlip",
    "dom": '<"top"f>rt<"bottom"ilp><"clear">',
    //"dom": '<"toolbar">frtip',
    //"dom": '<"pull-left"f><"pull-right"l>tip',
    "buttons": [
      'excelHtml5'
    ],
    data: [],
    columns: [{
        "title": "Drug"
      },
      {
        "title": "Species"
      },
      {
        "title": "Tissue"
      },
      {
        "title": "Study"
      },
      {
        "title": "Dose"
      },
      {
        "title": "Time"
      },
      {
        "title": "Control sample size"
      },
      {
        "title": "Treatment sample size"
      },
      {
        "title": "Top DEGs"
      },
      {
        "title": "Dataset"
      }
    ],
    rowCallback: function(row, data) {},
    "filter": false,
    "info": true,
    "processing": true,
    "retrieve": true,
    "columnDefs": [{
        "width": "6%"
      },
      {
        "width": "6%"
      },
      {
        "width": "6%"
      },
      {
        "width": "6%"
      },
      {
        "width": "6%"
      },
      {
        "width": "6%"
      },
      {
        "width": "6%"
      },
      {
        "width": "6%"
      },
      {
        "width": "40%"
      },
      {
        "width": "12%"
      },
      {
        render: function(data, type, full, meta) {
          return "<div style='max-height: 100px; overflow-y: auto;'>" + data + "</div>";
        },
        targets: [8]
      }

    ]
  });


  //$('#datatable_deg_app1 td').css({"max-height": "100px", "overflow-y": "scroll"})

  var table_pathway = $('#datatable_pathway_app1').DataTable({ // added Jess
    "autowidth": false,
    //"scrollx": true,
    "searching": true,
    "paging": true,
    "ordering": true,
    "pageLength": 10,
    "dom": '<"top"f>rt<"bottom"ilp><"clear">',
    //"dom": '<"toolbar">frtip',
    //"dom": '<"pull-left"f><"pull-right"l>tip',
    "buttons": [
      'excelHtml5',
    ],
    data: [],
    columns: [{
        "title": "Drug"
      },
      {
        "title": "Species"
      },
      {
        "title": "Tissue"
      },
      {
        "title": "Pathway"
      },
      {
        "title": "Dataset"
      }
    ],
    rowCallback: function(row, data) {},
    "filter": false,
    "info": true,
    "processing": true,
    "retrieve": true,
    "columnDefs": [{
        "width": "15%"
      },
      {
        "width": "15%"
      },
      {
        "width": "15%"
      },
      {
        "width": "45%"
      },
      {
        "width": "15%"
      },
      {
        render: function(data, type, full, meta) {
          return "<div style='max-height: 100px; overflow-y: auto;'>" + data + "</div>";
        },
        targets: [3]
      }
    ]
  });

  var table_pathway_doseSeg = $('#datatable_pathway_doseSeg_app1').DataTable({ // added Jess
    "autowidth": false,
    //"scrollx": true,
    "searching": true,
    "paging": true,
    "ordering": true,
    "pageLength": 10,
    "dom": '<"top"f>rt<"bottom"ilp><"clear">',
    //"dom": '<"toolbar">frtip',
    //"dom": '<"pull-left"f><"pull-right"l>tip',
    "buttons": [
      'excelHtml5',
    ],
    data: [],
    columns: [{
        "title": "Drug"
      },
      {
        "title": "Species"
      },
      {
        "title": "Tissue"
      },
      {
        "title": "Study"
      },
      {
        "title": "Dose"
      },
      {
        "title": "Time"
      },
      {
        "title": "Control sample size"
      },
      {
        "title": "Treatment sample size"
      },
      {
        "title": "Enrichment"
      },
      {
        "title": "Pathway"
      },
      {
        "title": "Dataset"
      }
    ],
    rowCallback: function(row, data) {},
    "filter": false,
    "info": true,
    "processing": true,
    "retrieve": true,
    "columnDefs": [{
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "8%"
      },
      {
        "width": "20%"
      },
      {
        "width": "8%"
      },
      {
        render: function(data, type, full, meta) {
          return "<div style='max-height: 100px; overflow-y: auto;'>" + data + "</div>";
        },
        targets: [9]
      }
    ]
  });


  $.fn.select2.amd.require(
    ['select2/data/array', 'select2/utils'],
    function(ArrayData, Utils) {
      function CustomData($element, options) {
        CustomData.__super__.constructor.call(this, $element, options);
      }

      function contains(str1, str2) {
        return new RegExp(str2, "i").test(str1);
      }

      Utils.Extend(CustomData, ArrayData);

      CustomData.prototype.query = function(params, callback) {
        if (!("page" in params)) {
          params.page = 1;
        }
        var pageSize = 50;
        var results = this.$element.children().map(function(i, elem) {
          if (contains(elem.innerText, params.term)) {
            return {
              //This part was causing error in app1. -Dan
              //id: [elem.innerText, i].join(""),
              id: elem.innerText,
              text: elem.innerText
            };
          }
        });
        callback({
          results: results.slice((params.page - 1) * pageSize, params.page * pageSize),
          pagination: {
            more: results.length >= params.page * pageSize
          }
        });
      };


      $("#myDrugName").select2({
        ajax: {},
        placeholder: "Please select a drug",
        allowClear: true,
        dataAdapter: CustomData
      });
    });



  $(document).ready(function() {
    $("#myDrugName").val('').trigger('change');
  });


  $('#myDrugName').on('change', function() { // show species for which that drug has data for
    var drug = $("#myDrugName option:selected").text(),
      //organism_arr = new Array(),
    //  drug_list = {};

    drugdataname = drug;
    
    if(drugdataname.includes('/')){
      drugdataname = drugdataname.replace("/"," and ");
      //console.log(drugdataname);
    }

    if(drugdataname!==""){
      $("#downloadDrugData").html('<a href="./include/pharmomics/DEG_Pathway_Data/' + drugdataname + '_DEGs_Pathways_Signatures.txt" download class="button button-3d button-large" role="button" id="DEG_button" style="margin-top: 3%;"><i class="icon-download1"></i>Download drug gene signatures</a>');
    }
    
    $.ajax({
      type: "GET",
      url: "include/pharmomics/PharmOmics_indexcatalog_Jan2021.json",
      dataType: "json",
      success: function(data) {
        var found_drugs = $.grep(data, function(v) {
          return v["Drug name"] === drug;
        });
        $.each(found_drugs, function(key, value) {
          organism_arr.push(value.organism);
        });
        organism_arr.sort();
        organism_arr.forEach(function(x) {
          drug_list[x] = (drug_list[x] || 0) + 1;
        });

        $("#mySpecies").html('');

        $.each(drug_list, function(key, value) {
          $("#mySpecies").append('<div class="radioholder app1"><span class="tick"></span><input type="checkbox" style="display:none;" name="species[]" value="' + key + '"><span class="desc">' + key + ' [' + value + ']</span></div>');
        });

        if (!$('#myDrugName').val()) {

          $("#mySpecies").html('<p style="margin-bottom:0;">Please select a drug name</p>');
        }
        $("#myOrgans").html('<p>Please select at least one species</p>');

      },
      error: function() {
        alert("json not found");
      }
    });
    var convert = [],
      convert_done = [];

    function convert2drawDEG(arr) { // Jess added
      $.each(arr, function(key, value) {
        convert.push(value["Drug"]);
        convert.push(value["Species"]);
        convert.push(value["Tissue"]);
        convert.push(value["Top DEGs"]);
        convert.push(value["Dataset"]);
        convert_done.push(convert);
        convert = [];
      });

      table_deg.clear().draw();
      table_deg.rows.add(convert_done).draw();
      convert_done = [];
    }

    function convert2drawDEGDoseSeg(arr) { // Jess added
      $.each(arr, function(key, value) {
        convert.push(value["Drug"]);
        convert.push(value["Species"]);
        convert.push(value["Tissue"]);
        convert.push(value["Study"]);
        convert.push(value["Dose"]);
        convert.push(value["Time"]);
        convert.push(value["Control sample size"]);
        convert.push(value["Treatment sample size"]);
        convert.push(value["Top DEGs"]);
        convert.push(value["Dataset"]);
        convert_done.push(convert);
        convert = [];
      });

      table_deg_doseSeg.clear().draw();
      table_deg_doseSeg.rows.add(convert_done).draw();
      convert_done = [];
    }

    function convert2drawPathway(arr) { // Jess added
      $.each(arr, function(key, value) {
        convert.push(value["Drug"]);
        convert.push(value["Species"]);
        convert.push(value["Tissue"]);
        convert.push(value["Pathway"]);
        convert.push(value["Dataset"]);
        convert_done.push(convert);
        convert = [];
      });

      table_pathway.clear().draw();
      table_pathway.rows.add(convert_done).draw();
      convert_done = [];
    }

    function convert2drawPathwayDoseSeg(arr) { // Jess added
      $.each(arr, function(key, value) {
        convert.push(value["Drug"]);
        convert.push(value["Species"]);
        convert.push(value["Tissue"]);
        convert.push(value["Study"]);
        convert.push(value["Dose"]);
        convert.push(value["Time"]);
        convert.push(value["Control sample size"]);
        convert.push(value["Treatment sample size"]);
        convert.push(value["Enrichment"]);
        convert.push(value["Pathway"]);
        convert.push(value["Dataset"]);
        convert_done.push(convert);
        convert = [];
      });

      table_pathway_doseSeg.clear().draw();
      table_pathway_doseSeg.rows.add(convert_done).draw();
      convert_done = [];
    }
    
    $.ajax({ // Jess added
      type: "GET",
      url: "include/pharmomics/PharmOmicsMetaDEGSubset_Jan2022.json", // Doesn't work with Nov2020 for some reason
      dataType: "json",
      success: function(data) {

        degs = $.grep(data, function(v) {

          return v["Drug"] === drug;
        });

        convert2drawDEG(degs);

      },
      error: function() {
        alert("json not found");
      }
    });
    $.ajax({ // Jess added
      type: "GET",
      url: "include/pharmomics/DoseSegregatedDEGSubset_Jan2022.json",
      dataType: "json",
      beforeSend: function() {
        var loaddata = document.getElementById("preloader");
        if (!loaddata === null) {
          $('#preloader').append(`Loading data...<br><img src="include/pictures/ajax-loader.gif">`).show();
        }
      },
      complete: function() {
        $('#preloader').empty().hide();
      },
      success: function(data) {

        degs = $.grep(data, function(v) {

          return v["Drug"] === drug;
        });

        convert2drawDEGDoseSeg(degs);

      },
      error: function() {
        alert("json not found");
      }
    });

    $.ajax({ // Jess added
      type: "GET",
      url: "include/pharmomics/PharmOmicsMetaPathwaySubset_Jan2021.json",
      dataType: "json",
      success: function(data) {

        degs = $.grep(data, function(v) {

          return v["Drug"] === drug;
        });

        convert2drawPathway(degs);

      },
      error: function() {
        alert("json not found");
      }
    });

    $.ajax({ // Jess added
      type: "GET",
      url: "include/pharmomics/DoseSegregatedPathwaySubsetMelt_Jan2021.json",
      dataType: "json",
      success: function(data) {

        degs = $.grep(data, function(v) {

          return v["Drug"] === drug;
        });

        convert2drawPathwayDoseSeg(degs);

      },
      error: function() {
        alert("json not found");
      }
    });


  });


  // $("#mySpecies").on("change", 'input[name="species[]"]', function(event) { // show organs for which that species has data for

  //   var species_arr = new Array(),
  //     drug = $("#myDrugName option:selected").text(),
  //     organ_list = {};

  //   function displayOrgans(organs) {
  //     $("#myOrgans").empty();
  //     if (organs.length == 0) {
  //       $("#myOrgans").html("No matching organs for current species selection");
  //     } else {
  //       organs.sort();
  //       organs.forEach(function(x) {
  //         organ_list[x] = (organ_list[x] || 0) + 1;
  //       });
  //       $.each(organ_list, function(key, value) {
  //         $("#myOrgans").append('<div class="radioholder app1"><span class="tick"></span><input type="checkbox" style="display:none;" name="organs[]" value="' + key + '"><span class="desc">' + key + ' [' + value + ']</span></div>');
  //       });
  //       $("#myComboButtons").fadeOut();
  //     }

  //   }

  //   $('input[name="species[]"]:checked').each(function() {
  //     species_arr.push(this.value);
  //   });

  //   if (species_arr.length == 0) {
  //     table.clear().draw();
  //     $("#myOrgans").html("Please select at least one species");
  //     $("#myComboButtons").fadeOut();
  //     return;
  //   }

  //   $.ajax({
  //     type: "GET",
  //     url: "include/pharmomics/PharmOmics_indexcatalog_Jan2021.json",
  //     dataType: "json",
  //     success: function(data) {
  //       var found_drug = $.grep(data, function(v) {
  //         return v["Drug name"] === drug;
  //       });

  //       if (species_arr.length == 1) {
  //         var organ = [],
  //           found_organisms = $.grep(found_drug, function(v) {

  //             return v["organism"] === species_arr.toString();
  //           });
  //         $.each(found_organisms, function(key, value) {
  //           organ.push(value['organ/tissue']);
  //         });
  //         displayOrgans(organ);

  //       } else if (species_arr.length == 2) {

  //         var organ1 = [],
  //           organ2 = [],
  //           final_organ = [],
  //           found_organisms1 = $.grep(found_drug, function(v) {
  //             return v["organism"] === species_arr[0].toString();
  //           });
  //         $.each(found_organisms1, function(key, value) {
  //           organ1.push(value['organ/tissue']);
  //         });

  //         found_organisms2 = $.grep(found_drug, function(v) {
  //           return v["organism"] === species_arr[1].toString();
  //         });
  //         $.each(found_organisms2, function(key, value) {
  //           organ2.push(value['organ/tissue']);
  //         });


  //         var list = [organ1, organ2];
  //         var result = list.shift().reduce(function(res, v) {
  //           if (res.indexOf(v) === -1 && list.every(function(a) {
  //               return a.indexOf(v) !== -1;
  //             })) res.push(v);
  //           return res;
  //         }, []);

  //         $.each(result, function(index, item) {
  //           found_organs = $.grep(found_drug, function(v) {

  //             return (v["organ/tissue"] === result[index] && v["Drug name"] === drug) && (v['organism'] === species_arr[0].toString() || v['organism'] === species_arr[1].toString());
  //           });

  //           $.each(found_organs, function(key, value) {
  //             final_organ.push(value['organ/tissue']);
  //           });

  //         });
  //         displayOrgans(final_organ);
  //       } else if (species_arr.length == 3) {
  //         var organ1 = [],
  //           organ2 = [],
  //           organ3 = [],
  //           final_organ = [],
  //           found_organisms1 = $.grep(found_drug, function(v) {

  //             return v["organism"] === species_arr[0].toString();
  //           });
  //         $.each(found_organisms1, function(key, value) {
  //           organ1.push(value['organ/tissue']);
  //         });

  //         found_organisms2 = $.grep(found_drug, function(v) {

  //           return v["organism"] === species_arr[1].toString();
  //         });
  //         $.each(found_organisms2, function(key, value) {
  //           organ2.push(value['organ/tissue']);
  //         });

  //         found_organisms3 = $.grep(found_drug, function(v) {

  //           return v["organism"] === species_arr[2].toString();
  //         });
  //         $.each(found_organisms3, function(key, value) {
  //           organ3.push(value['organ/tissue']);
  //         });


  //         var list = [organ1, organ2, organ3];
  //         var result = list.shift().reduce(function(res, v) {
  //           if (res.indexOf(v) === -1 && list.every(function(a) {
  //               return a.indexOf(v) !== -1;
  //             })) res.push(v);
  //           return res;
  //         }, []);

  //         $.each(result, function(index, item) {

  //           found_organs = $.grep(found_drug, function(v) {

  //             return (v["organ/tissue"] === result[index] && v["Drug name"] === drug) && (v['organism'] === species_arr[0].toString() || v['organism'] === species_arr[1].toString() || v['organism'] === species_arr[2].toString());
  //           });

  //           $.each(found_organs, function(key, value) {
  //             final_organ.push(value['organ/tissue']);
  //           });
  //         });


  //         displayOrgans(final_organ);

  //       } else {
  //         //do nothing
  //       }


  //     },
  //     error: function() {
  //       alert("json not found");
  //     }
  //   });

  // });



  // $("#mySpecies").on("click", ".radioholder.app1", function(event) {
  //   if ($(this).children('input').prop('checked') == true)
  //     $(this).children('input').prop('checked', false);

  //   else
  //     $(this).children('input').prop('checked', true);

  //   $(this).children('input').trigger('change');
  // });

  // $("#mySpecies").on("change", ".radioholder.app1", function(event) {
  //   $('.radioholder.app1 :input').each(function() {
  //     if ($(this).prop('checked') == true) {
  //       $(this).parent().addClass('activeradioholder');
  //     } else {

  //       $(this).parent().removeClass('activeradioholder');

  //     }
  //   });
  //   var legchecked = $('input[name="species[]"]:checked').length;
  //   if (legchecked == 0) {
  //     $("#myOrgans").empty();
  //     $("#myOrgans").html('<p>Please select at least one species</p>');
  //   }
  // });

  // $("#myOrgans").on("click", ".radioholder.app1", function(event) {
  //   if ($(this).children('input').prop('checked') == true)
  //     $(this).children('input').prop('checked', false);

  //   else
  //     $(this).children('input').prop('checked', true);

  //   $(this).children('input').trigger('change');
  // });

  // $("#myOrgans").on("change", ".radioholder.app1", function(event) {
  //   $('.radioholder.app1 :input').each(function() {
  //     if ($(this).prop('checked') == true) {
  //       $(this).parent().addClass('activeradioholder');
  //     } else $(this).parent().removeClass('activeradioholder');
  //   });
  // });


  // $("#myOrgans").on("change", 'input[name="organs[]"]', function(event) {

  //   var species_arr = new Array(),
  //     organs_arr = new Array(),
  //     convert = [],
  //     convert_done = [],
  //     drug = $("#myDrugName option:selected").text(),
  //     organ_list = {};

  //   $('input[name="species[]"]:checked').each(function() {
  //     species_arr.push(this.value);
  //   });

  //   $('input[name="organs[]"]:checked').each(function() {
  //     organs_arr.push(this.value);
  //   });

  //   if (organs_arr.length == 0) {
  //     table.clear().draw();
  //     $("#myComboButtons").fadeOut();
  //     return;
  //   } else {
  //     $("#myComboButtons").fadeIn();
  //   }

  //   function convert2draw(arr) {
  //     $.each(arr, function(key, value) {
  //       convert.push(value["Drug name"]);
  //       convert.push(value["Study name"]);
  //       convert.push(value["organ/tissue"]);
  //       convert.push(value["organism"]);
  //       convert.push(value["weblink"]);
  //       convert_done.push(convert);
  //       convert = [];
  //     });

  //     table.clear().draw();
  //     table.rows.add(convert_done).draw();
  //     convert_done = [];

  //   }

  //   function additionalorgans(arr) {
  //     $.each(arr, function(key, value) {
  //       convert.push(value["Drug name"]);
  //       convert.push(value["Study name"]);
  //       convert.push(value["organ/tissue"]);
  //       convert.push(value["organism"]);
  //       convert.push(value["weblink"]);
  //       convert_done.push(convert);
  //       convert = [];
  //     });

  //     table.rows.add(convert_done).draw();
  //     convert_done = [];
  //   }

  //   $.ajax({
  //     type: "GET",
  //     url: "include/pharmomics/PharmOmics_indexcatalog_Jan2021.json",
  //     dataType: "json",
  //     success: function(data) {
  //       if (species_arr.length == 1) {
  //         $.each(organs_arr, function(index, item) {

  //           if (index === 0) {
  //             found_organs = $.grep(data, function(v) {

  //               return v["organ/tissue"] === organs_arr[index] && v["Drug name"] === drug && v['organism'] === species_arr[0].toString();
  //             });
  //             convert2draw(found_organs);
  //           } else {
  //             found_organs = $.grep(data, function(v) {

  //               return v["organ/tissue"] === organs_arr[index] && v["Drug name"] === drug && v['organism'] === species_arr[0].toString();
  //             });

  //             additionalorgans(found_organs);

  //           }




  //         });
  //       } else if (species_arr.length == 2) {
  //         $.each(organs_arr, function(index, item) {

  //           if (index === 0) {
  //             found_organs = $.grep(data, function(v) {

  //               return (v["organ/tissue"] === organs_arr[index] && v["Drug name"] === drug) && (v['organism'] === species_arr[0].toString() || v['organism'] === species_arr[1].toString());
  //             });

  //             convert2draw(found_organs);

  //           } else {
  //             found_organs = $.grep(data, function(v) {

  //               return (v["organ/tissue"] === organs_arr[index] && v["Drug name"] === drug) && (v['organism'] === species_arr[0].toString() || v['organism'] === species_arr[1].toString());
  //             });

  //             additionalorgans(found_organs);

  //           }




  //         });

  //       } else if (species_arr.length == 3) {

  //         $.each(organs_arr, function(index, item) {

  //           if (index === 0) {
  //             found_organs = $.grep(data, function(v) {

  //               return (v["organ/tissue"] === organs_arr[index] && v["Drug name"] === drug) && (v['organism'] === species_arr[0].toString() || v['organism'] === species_arr[1].toString() || v['organism'] === species_arr[2].toString());
  //             });

  //             convert2draw(found_organs);

  //           } else {

  //             found_organs = $.grep(data, function(v) {

  //               return (v["organ/tissue"] === organs_arr[index] && v["Drug name"] === drug) && (v['organism'] === species_arr[0].toString() || v['organism'] === species_arr[1].toString() || v['organism'] === species_arr[2].toString());
  //             });

  //             additionalorgans(found_organs);
  //           }
  //         });
  //       } else {
  //         //do nothing
  //       }

  //     },
  //     error: function() {
  //       alert("json not found");
  //     }
  //   });

  // });


  // var button = document.getElementById("myTutButton_app1");
  // var val = 0;

  // //begin function for when button is clicked-------------------------------------------------------------->
  // button.addEventListener("click", function() {
  //   //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
  //   var $this = $(this);

  //   //If tutorial is already opened yet, then do this-------------------------------------------------------------->
  //   if ($this.data('clicked')) {
  //     $('.tutorialbox_app1').empty();
  //     $('.tutorialbox_app1').hide();






  //     $this.data('clicked', false);
  //     val = val - 1;
  //     $("#myTutButton_app1").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

  //   }

  //   //If tutorial is not opened yet, then do this-------------------------------------------------------------->
  //   else {
  //     $this.data('clicked', true);
  //     val = val + 1; //val counter to not duplicate prepend function


  //     if (val == 1) //Only prepend the tutorial once
  //     {
  //       $('.tutorialbox_app1').show();
  //       $('.tutorialbox_app1').html(`<table class="table table-bordered" style="text-align: center;"; id="app1networktable"> 
  //             <thead>
  //             <tr>
  //               <th colspan="2" style="width:100%;">Tutorial</th>
  //             </tr>
  //             </thead>
  //               <tr>
  //                 <td name="tut" style="width:50%;">
  //                   <h4 class="instructiontext" style="font-size:20px;">
  //                   	Studies curated, differentially expressed genes (DEGs), and regulated pathways overview
  //                   </h4>
  //                   <p style="font-size:16px;">
  //                   	Select a drug and a preview of the DEGs and pathway signatures from all studies (meta-analyzed and dose/time segregated) will appear. The top 50 genes and top 20 pathways are shown. To see studies curated in the PharmOmics database, click on the species and tissues that appear for the drug. Further information such as the dose and time regimens can be viewed in the PharmOmics dose/time segregated tables of the Gene/Pathway Regulation tabs. To download full signatures of the drug, click on 'Download drug gene signatures' at the bottom of the page.
  //                   </p>
  //                 </td>
  //                 <td name="tut" style="width:50%;">
  //                   <h4 class="instructiontext" style="font-size:20px;">
  //                   	Run Species/Tissue Comparison
  //                   </h4>
  //                   <p style="font-size:16px;">
  //                   	After selecting a drug, the species and tissues for which data is available will appear. For cross species comparison, choose MULTIPLE species and ONE tissue of interest. For cross tissue comparison, choose ONE species and MULTIPLE organs of interest. If multiple species and multiple tissues are selected, the analysis will not initiate. Click 'Run DEGs Comparison' or 'Run Pathways Comparison' to run the comparisons. Results will appear in the 'Species/Tissue Comparison' tab. If there are overlaps, a plot of the counts of species/tissue-specific genes/pathways and overlaps will apear in the 'Degree of DEG/Pathway Overlap' tab (click on plot to download). Regardless of whether there are overlaps, a summary table with the overlapped genes (if any) and those specific to a species/tissue will appear in the 'DEG/Pathway Overlap Summary' tab (click on download button at the bottom to download).
  //                   </p>
  //                 </td>
  //              </tr> 

  //             </table> `);



  //     }
  //     $("#myTutButton_app1").html('<i class="icon-window-close1"></i>Close Tutorial'); //Change name of button to 'Close Tutorial'
  //   }


  // });

  // // Function start
  // $.fn.getFormObject = function() {
  //   var object = $(this).serializeArray().reduce(function(obj, item) {
  //     var name = item.name.replace("[]", "");
  //     if (typeof obj[name] !== "undefined") {
  //       if (!Array.isArray(obj[name])) {
  //         obj[name] = [obj[name], item.value];
  //       } else {
  //         obj[name].push(item.value);
  //       }
  //     } else {
  //       obj[name] = item.value;
  //     }
  //     return obj;
  //   }, {});
  //   return object;
  // }

  // /*
  //  * Convert data array to CSV string
  //  * @param arr {Array} - the actual data
  //  * @param columnCount {Number} - the amount to split the data into columns
  //  * @param initial {String} - initial string to append to CSV string
  //  * return {String} - ready CSV string
  //  */
  // function prepCSVRow(arr, columnCount, initial) {
  //   var row = ''; // this will hold data
  //   var delimeter = ','; // data slice separator, in excel it's `;`, in usual CSv it's `,`
  //   var newLine = '\r\n'; // newline separator for CSV row

  //   /*
  //    * Convert [1,2,3,4] into [[1,2], [3,4]] while count is 2
  //    * @param _arr {Array} - the actual array to split
  //    * @param _count {Number} - the amount to split
  //    * return {Array} - splitted array
  //    */
  //   function splitArray(_arr, _count) {
  //     var splitted = [];
  //     var result = [];
  //     _arr.forEach(function(item, idx) {
  //       if ((idx + 1) % _count === 0) {
  //         splitted.push(item);
  //         result.push(splitted);
  //         splitted = [];
  //       } else {
  //         splitted.push(item);
  //       }
  //     });
  //     return result;
  //   }
  //   var plainArr = splitArray(arr, columnCount);
  //   // don't know how to explain this
  //   // you just have to like follow the code
  //   // and you understand, it's pretty simple
  //   // it converts `['a', 'b', 'c']` to `a,b,c` string
  //   plainArr.forEach(function(arrItem) {
  //     arrItem.forEach(function(item, idx) {
  //       row += item + ((idx + 1) === arrItem.length ? '' : delimeter);
  //     });
  //     row += newLine;
  //   });
  //   return initial + row;
  // }


  // function downloadGenes(species, organs) {
  //   var drug = $("#myDrugName option:selected").text(),
  //     upgenes = '',
  //     downgenes = '',
  //     upgenes_arr = [],
  //     downgenes_arr = [],
  //     upgenes_arr_final = [],
  //     downgenes_arr_final = [];

  //   $.ajax({
  //     type: "GET",
  //     url: "include/pharmomics/PharmOmics_allDEG.json",
  //     dataType: "json",
  //     success: function(data) {


  //       drug_list = $.grep(data, function(v) {

  //         return v["drugs"] === drug && v["species"] === species && v["organs"] === organs;
  //       });

  //       $.each(drug_list, function(key, value) {
  //         upgenes = upgenes + value['genes_up'];
  //         upgenes_arr = upgenes.split(',');
  //         upgenes_arr_final.push(upgenes_arr);
  //         upgenes_arr = [];

  //         downgenes = downgenes + value['genes_down'];
  //         downgenes_arr = downgenes.split(',');
  //         downgenes_arr_final.push(downgenes_arr);
  //         downgenes_arr = [];
  //       });

  //       console.log(downgenes_arr_final);





  //     },
  //     error: function() {
  //       alert("json not found");
  //     }
  //   });
  // }

  // $("#myComboButtons").on('click', '#drugclass_button', function(event) {

  //   event.preventDefault();
  //   var form = $("#app1dataform").getFormObject(),
  //     species = form['species'],
  //     organs = form['organs'],
  //     titles = ['gene', 'species', 'organ', 'direction'],
  //     data = [];

  //   var form_data = new FormData(document.getElementById('app1dataform'));
  //   form_data.append("sessionID", string);

  //   if (Array.isArray(species) && typeof organs === 'string') {
  //     form_data.append("type", "species");
  //     $.ajax({
  //       'url': 'app1Drug.php',
  //       'type': 'POST',
  //       'data': form_data,
  //       processData: false,
  //       contentType: false,
  //       beforeSend: function() {
  //         $('#preloader').append(`Running cross-species gene comparison...<br><img src="include/pictures/ajax-loader.gif">`).show();
  //       },
  //       complete: function() {
  //         $('#preloader').empty().hide();
  //       },
  //       'success': function(data) {
  //         $("#SpeciesOrganComparison").click();
  //         $("#APP1_species_tab1").click();
  //         if (data == "No similarities found between species" || !data.replace(/\s/g, '').length || data.includes("similarities")) {
  //           $("#mySpecies_comparison").html('No similarities found between species');
  //         } else {
            
  //         $("#mySpecies_comparison").html('<a href="#" tooltip="Click me!"><img class="comparison_pic" src="data:image/png;base64,' + data + '" width="fit-content"></a>');
  //         }
  //         $("#speciesCompOverlapPreText").hide();
  //         $("#downloadSpeciesDEG").html('<br><br><a href="./Data/Pipeline/Resources/shinyapp1_temp/' + string + '_genes_result.txt" download class="button button-3d button-large" role="button" id="DEG_button"><i class="icon-download1"></i>Download results</a>');
  //         $("#datatable_speciesCompOverlap").dataTable({
  //           destroy: true,
  //           "dom": "Bfrtlip",
  //           "ajax": './Data/Pipeline/Resources/shinyapp1_temp/' + string + '_gene_intersections.txt',
  //           buttons: [{
  //             extend: 'excelHtml5',
  //             text: 'Download table'
  //           }]
  //         });

  //       }
  //     });
  //   } else if (Array.isArray(organs) && typeof species === 'string') {
  //     form_data.append("type", "organs");
  //     $.ajax({
  //       'url': 'app1Drug.php',
  //       'type': 'POST',
  //       'data': form_data,
  //       processData: false,
  //       contentType: false,
  //       beforeSend: function() {
  //         $('#preloader').append(`Running cross-tissue gene comparison...<br><img src="include/pictures/ajax-loader.gif">`).show();
  //       },
  //       complete: function() {
  //         $('#preloader').empty().hide();
  //       },
  //       'success': function(data) {
  //         $("#SpeciesOrganComparison").click();
  //         $("#APP1_organs_tab1").click();
  //         if (data == "No similarities found between tissues" || !data.replace(/\s/g, '').length) {
  //           $("#myOrgans_comparison").html('No similarities found between tissues');
  //         } else {
  //           $("#myOrgans_comparison").html('<a href="#" tooltip="Click me!"><img class="comparison_pic" src="data:image/png;base64,' + data + '" width="fit-content"></a>');
  //         }
  //         $("#downloadOrganDEG").html('<br><br><a href="./Data/Pipeline/Resources/shinyapp1_temp/' + string + '_genes_result.txt" download class="button button-3d button-large" role="button" id="DEG_button"><i class="icon-download1"></i>Download results</a>');
  //         $("#tissCompOverlapPreText").hide();
  //         $("#datatable_tissueCompOverlap").dataTable({
  //           destroy: true,
  //           "dom": "Bfrtlip",
  //           "ajax": './Data/Pipeline/Resources/shinyapp1_temp/' + string + '_gene_intersections.txt',
  //           buttons: [
  //             'excelHtml5'
  //           ]
  //         });
  //       }
  //     });
  //   } else {
  //     $('#errorp_app1').html(`Invalid Selections <br>
  //       For cross species comparison, choose MULTIPLE species and ONE tissue of interest <br>
  //       For cross tissue comparison, choose ONE species and MULTIPLE organs of interest`);
  //     $("#errormsg_app1").fadeTo(4000, 500).slideUp(500, function() {
  //       $("#errormsg_app1").slideUp(500);
  //     });
  //   }

  // });

  // $("#myComboButtons").on('click', '#pathways_button', function(event) {
  //   event.preventDefault();
  //   var form = $("#app1dataform").getFormObject(),
  //     species = form['species'],
  //     organs = form['organs'],
  //     titles = ['gene', 'species', 'organ', 'direction'],
  //     data = [];

  //   var form_data = new FormData(document.getElementById('app1dataform'));
  //   form_data.append("sessionID", string);

  //   if (Array.isArray(species) && typeof organs === 'string') {
  //     form_data.append("type", "species");

  //     $.ajax({
  //       'url': 'app1KEGG.php',
  //       'type': 'POST',
  //       'data': form_data,
  //       processData: false,
  //       contentType: false,
  //       beforeSend: function() {

  //         $('#preloader').append(`Running cross-species pathway comparison...<br><img src="include/pictures/ajax-loader.gif">`).show();
  //       },
  //       complete: function() {
  //         $('#preloader').empty().hide();
  //       },
  //       'success': function(data) {
  //         $("#SpeciesOrganComparison").click();
  //         $("#APP1_species_tab3").click();
  //         if (data == "No similarities found between species" || !data.replace(/\s/g, '').length) {
  //           $("#mySpecies_pathway").html('No similarities found between species');
  //         } else {
  //           $("#mySpecies_pathway").html('<a href="#" tooltip="Click me!"><img class="comparison_pic" src="data:image/png;base64,' + data + '" width="fit-content"></a>');
  //         }
  //         $("#speciesPathPreText").hide();
  //         $("#downloadSpeciesPathAll").html('<br><br><a href="./Data/Pipeline/Resources/shinyapp1_temp/' + string + '_pathway_results.txt" download class="button button-3d button-large" role="button" id="DEG_button"><i class="icon-download1"></i>Download results</a>');
  //         $("#speciesPathOverlapPreText").hide();
  //         $("#downloadSpeciesPathIntersect").html('<br><br><a href="./Data/Pipeline/Resources/shinyapp1_temp/' + string + '_pathway_intersections.txt" download class="button button-3d button-large" role="button" id="DEG_button"><i class="icon-download1"></i>Download results</a>');
  //         $("#datatable_speciesPathOverlap").dataTable({
  //           destroy: true,
  //           "dom": "Bfrtlip",
  //           buttons: [
  //             'excelHtml5',
  //           ],
  //           "ajax": './Data/Pipeline/Resources/shinyapp1_temp/' + string + '_pathway_intersections.txt'
  //         });
  //       }
  //     });
  //   } else if (Array.isArray(organs) && typeof species === 'string') {
  //     form_data.append("type", "organs");

  //     $.ajax({
  //       'url': 'app1KEGG.php',
  //       'type': 'POST',
  //       'data': form_data,
  //       processData: false,
  //       contentType: false,
  //       beforeSend: function() {
  //         $('#preloader').append(`Running cross-tissue pathway comparison...<br><img src="include/pictures/ajax-loader.gif">`).show();
  //       },
  //       complete: function() {
  //         $('#preloader').empty().hide();
  //       },
  //       'success': function(data) {
  //         $("#SpeciesOrganComparison").click();
  //         $("#APP1_organs_tab3").click();
  //         if (data == "No similarities found between organs" || !data.replace(/\s/g, '').length) {
  //           $("#myOrgans_pathway").html('No similarities found between organs');
  //         } else {

  //           $("#myOrgans_pathway").html('<a href="#" tooltip="Click me!"><img class="comparison_pic" src="data:image/png;base64,' + data + '" width="fit-content"></a>');
  //         }
  //         $("#tissPathPreText").hide();
  //         $("#downloadOrganPath").html('<br><br><a href="./Data/Pipeline/Resources/shinyapp1_temp/' + string + '_pathway_results.txt" download class="button button-3d button-large" role="button" id="DEG_button"><i class="icon-download1"></i>Download results</a>');
  //         $("#tissPathOverlapPreText").hide();
  //         $("#downloadOrganPathIntersect").html('<br><br><a href="./Data/Pipeline/Resources/shinyapp1_temp/' + string + '_pathway_intersections.txt" download class="button button-3d button-large" role="button" id="DEG_button"><i class="icon-download1"></i>Download results</a>');
  //         $("#datatable_organPathOverlap").dataTable({
  //           destroy: true,
  //           "dom": "Bfrtlip",
  //           buttons: [
  //             'excelHtml5',
  //           ],
  //           "ajax": './Data/Pipeline/Resources/shinyapp1_temp/' + string + '_pathway_intersections.txt'
  //         });
  //       }
  //     });
  //   } else {
  //     $('#errorp_app1').html(`Invalid Selections <br>
  //       For cross species comparison, choose MULTIPLE species and ONE tissue of interest <br>
  //       For cross tissue comparison, choose ONE species and MULTIPLE organs of interest`);
  //     $("#errormsg_app1").fadeTo(4000, 500).slideUp(500, function() {
  //       $("#errormsg_app1").slideUp(500);
  //     });
  //   }

  // });



  // $("#species_tab_containers").on('click', '.comparison_pic', function(e) {
  //   e.preventDefault();
  //   var a = $(this).parent().parent().attr('name');
  //   var path = $(this).prop('src');

  //   $(this).magnificPopup({
  //     items: {
  //       src: path
  //     },
  //     type: 'image',
  //     closeOnContentClick: true,
  //     closeBtnInside: true,
  //     mainClass: 'mfp-no-margins',
  //     image: {
  //       markup: '<div class="mfp-figure">' +
  //         '<div class="mfp-close"></div>' +
  //         '<div class="mfp-img"></div>' +
  //         '<div class="mfp-bottom-bar" style="text-align:center;">' +
  //         '<div class="mfp-title">' + a + '</div>' +
  //         '<a href="' + path + '" role="button" class="button button-3d button-rounded button" download="' + a + '"><i class="icon-download"></i>Download PNG Image</a>' +
  //         '</div>' +
  //         '</div>',
  //       verticalFit: true
  //     }

  //   }).magnificPopup('open');

  // });

  // $("#organ_tab_containers").on('click', '.comparison_pic', function(e) {
  //   e.preventDefault();
  //   var a = $(this).parent().parent().attr('name');
  //   var path = $(this).prop('src');

  //   $(this).magnificPopup({
  //     items: {
  //       src: path
  //     },
  //     type: 'image',
  //     closeOnContentClick: true,
  //     closeBtnInside: true,
  //     mainClass: 'mfp-no-margins',
  //     image: {
  //       markup: '<div class="mfp-figure">' +
  //         '<div class="mfp-close"></div>' +
  //         '<div class="mfp-img"></div>' +
  //         '<div class="mfp-bottom-bar" style="text-align:center;">' +
  //         '<div class="mfp-title">' + a + '</div>' +
  //         '<a href="' + path + '" role="button" class="button button-3d button-rounded button" download="' + a + '"><i class="icon-download"></i>Download PNG Image</a>' +
  //         '</div>' +
  //         '</div>',
  //       verticalFit: true
  //     }

  //   }).magnificPopup('open');


  // });
</script>