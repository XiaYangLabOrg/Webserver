<?php
include 'functions.php';
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
function generatesessionIDing($length = 10)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $sessionIDing = '';
  for ($i = 0; $i < $length; $i++) {
    $sessionIDing .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $sessionIDing;
}

$geneset = "";
$genesetd = "";
$network = "";
$kdadepth = "";
$kdadirect = "";
$minKDA = "";
$edgewKDA = "";
$genesetd_content="";
if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
  $fjson = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "param.json";
  if (file_exists($fjson)) {
    $data = json_decode(file_get_contents($fjson),true)["data"][0];
    $geneset = $data["geneset"];
    $genesetd = $data["genesetd"];
    $network = $data["network"];
    $kdadepth = $data["kdadepth"];
    $kdadirect = $data["kdadirect"];
    $minKDA = $data["minKDA"];
    $edgewKDA = $data["edgewKDA"];
  }
  if ($genesetd == 2) {
    $genesetd_content = file_get_contents("./Data/Pipeline/Resources/kda_temp/" . $sessionID . "_nodes_file.txt");
    $genesetd_content = str_replace("MODULE\tNODE\n", "", $genesetd_content);
    $genesetd_content = str_replace("Input_GeneList\t", "", $genesetd_content);
    $genesetd_content = str_replace("\n", "\\n", $genesetd_content);
  }
} else {
  $sessionID = generatesessionIDing(10);
}

if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  $sessionID = $_POST['sessionID'];
}


$fpostOut = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "_KDA_postdata.txt";
$fsession = $ROOT_DIR . "Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$session_write = NULL;

debug_to_console($fsession);
//if the sessionID does not exist
if (!file_exists($fsession)) {
  //create the session txt file

  $sessionfile = fopen($fsession, "w");
  $session_write .= "Pipeline:" . "\t" . "KDA" . "\n";
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
    if ($key == "inputgenesKDA") {
      continue;
    }
    $postwrite .= $key . "\t" . $value . "\n";
  }
  fwrite($fp, $postwrite);
  fclose($fp);
  chmod($fpostOut, 0774);
}





$fpath = "./Data/Pipeline/Resources/kda_temp/$sessionID";


$kdapath = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDA";


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
  $moduleformChoice2 = $explodeformChoice[2]; // initialize the data to form choice ($kdaformChoice = 1)


  //repeat above for the other form choices
  $splitformChoice1 = preg_split("/[\t]/", $postdata[1]);
  $explodeformChoice1 = explode("|", $splitformChoice1[1]);
  $descformChoice3 = $explodeformChoice1[3];

  $splitformChoice2 = preg_split("/[\t]/", $postdata[2]);
  $explodeformChoice2 = explode("|", $splitformChoice2[1]);
  $kdaformChoice = $explodeformChoice2[1];
}






?>
<style type="text/css">
  textarea {
    width: 90%;
    height: 500px;
    box-sizing: border-box;
    background-image: linear-gradient(135deg,
        rgba(0, 0, 0, 0.03) 25%,
        transparent 25%,
        transparent 50%,
        rgba(0, 0, 0, 0.03) 50%,
        rgba(0, 0, 0, 0.03) 75%,
        transparent 75%,
        transparent);
    background-size: 25px 25px;
    background-color: steelblue;
    border: 4px solid #e0e0e0;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, .75);
  }

  textarea:focus {
    background-color: white;
    background-image: none;
    border: 4px solid #e0e0e0;
    box-shadow: 0px 0px 0px 0px;
  }

  textarea::placeholder {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    padding: 40% 0 0 0;
    background-image: url(https://cdn.iconscout.com/icon/free/png-512/txt-file-20-504249.png);
    background-repeat: no-repeat;
    background-size: 15%;
    background-position: 50% 40%;
    color: white;
  }

  textarea:focus::placeholder {
    color: transparent;
    background-image: none;
  }



  textarea:valid {
    background-color: white;
    background-image: none;
  }
</style>

<div id="errormsg_wKDA" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_wKDA" style="white-space: pre;"></p>
</div>




<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">

  <!-- Description ===================================================== -->
  <h4 style="color: #00004d; text-align: center; padding: 20px;">
    This part of the pipeline is for performing wKDA directly on input modules.
  </h4>


  <!--Start wKDA Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_wKDA"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox' style="display: none;"></div>
  <!--End wKDA Tutorial --------------------------------------->



</div>
<!--End of gridcontainer ----->


<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="wKDA_parameters.php" name="select3" id="wKDAdataform">
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center" ; id="wKDAmaintable">
      <thead>
        <tr>
          <!--First row of table------------Column Headers------------------------------>
          <th>Type of File</th>
          <th class="uploadwidth">Upload/Select File</th>
          <th name="val_wKDA">Sample File Format</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!--Second row of table------------------------------------------>
          <td data-column="File type &#xa;" style="font-size: 18px;">Nodes

            <div class="informationtext" data-toggle="modal" data-target="#GSETinfomodal" href="#GSETinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>

          </td>
          <!--Second row|first column of table------------------------------------------>
          <td data-column="Upload/Select File &#xa;">
            <!--Start MDF Upload ----------------------------->
            <div id="Selectupload_GSET" class="selectholder KDA" align="center">
              <select class="wKDAGenes" name="formChoice2_MSEA" size="1" id="Geneset_form">
                <option value="0">Please select option</option>
                <option value="1">Upload Gene Sets</option>
                <option value="2">Input single list of genes</option>
                <option value="Resources/glgc.ldl.msea.gene.sets.txt">Sample wKDA nodes list</option>
              </select>
              <br>

              <!-- Mapping Association File Upload div --->
            </div>
            <!--End selectupload_Gset div-->

            <div id="GSETupload" style="display: none;">

              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

              <div class="input-file-container" name="Gene Sets File" style="width: fit-content;">
                <input class="input-file" id="GSETuploadInput" name="GSETuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <!--
                <label id="GSETlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> <?php if ($uploaded_module !== 0) {
                                                                                                                      print("Select another file?");
                                                                                                                    } else {
                                                                                                                      print("Select a file...");
                                                                                                                    } ?></label>
                -->
                <label id="GSETlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
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
            </div> <!-- End of upload div--->
            <!--
            <div class="alert-wKDA" id="alert_GSET"><?php if ($uploaded_module !== 0) {
                                                      echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                    } else {
                                                      print("");
                                                    } ?></div> -->
            <div class="alert-wKDA" id="alert_GSET"></div>
            <!--Div to alert user of certain comment (i.e. success) -->

            <div id="dropGeneList" style="display: none;">
              <!-- Text drop area --->
              <textarea name="inputgenesKDA" id="dropzoneKDA" placeholder="Drop text file(s) or click to manually input genes" required="required"></textarea>
            </div>

            <div id="GSETConvertContainer" style="display: none;">
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
            </div>

          </td>
          <!--Second row|second column of table------------------------------------------>
          <td data-column="Sample Format &#xa;" name="val_wKDA">
            <!--Start Second row|third column of table------------------------------------------>

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="Name of node list (i.e. canonical pathway, co-expression module, or MSEA results)">MODULE</a></th>
                    <th><a href="#">NODE</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="MODULE(Header): ">Cell cycle</td>
                    <td data-column="NODE(Header): ">CDC16</td>

                  </tr>
                  <tr>
                    <td data-column="MODULE(Header): ">Cell cycle</td>
                    <td data-column="NODE(Header): ">ANAPC1</td>

                  </tr>
                  <tr>
                    <td data-column="MODULE(Header): ">WGCNA Brown</td>
                    <td data-column="NODE(Header): ">XRCC5</td>

                  </tr>
                </tbody>
              </table>
              <p>A <strong>TAB</strong> deliminated text file that contains a set or sets of genes desired for finding enrichment in networks. UTF-8/ASCII encoded files recommended. Sample files for all inputs can be found <a href="samplefiles.php">here</a>.</p>
            </div>


          </td>
          <!--End Second row|third column of table------------------------------------------>

        </tr>
        <tr id="gsetd_row" style="display:none;">
          <td data-column="File type &#xa;" style="font-size: 18px;">Node Sets Description<br>(Optional)

            <div class="informationtext" data-toggle="modal" data-target="#GSETDinfomodal" href="#GSETDinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>

          </td>
          <td data-column="Upload/Select File &#xa;">
            <!--Start Gene Set Description Input ----------------------------->
            <div id="Selectupload_GSETD" class="selectholder KDA" align="center">
              <select class="wKDA" name="formChoice3_MSEA" size="1" id="Genesetd_form">
                <option value="0">Please select option</option>
                <option value="1">Upload Gene Sets Descriptions</option>
                <option value="2" selected="">No Gene Sets Description</option>
                <option value="Resources/pathways/KEGG_Reactome_BioCarta_info_abbr.txt">Sample gene sets description</option>
              </select>
              <?php
              if (isset($_POST['formChoice3_MSEA'])) {
                $ch = $descformChoice3;
                if ($ch == 1) {
                  if ($_FILES['GSETDuploadedfile']['name'] !== "") {
                    $a = $_FILES['GSETDuploadedfile']['name'];
                    $txt = "Resources/kda_temp/" . $a . "\n";
                    $test = fopen("./Data/Pipeline/Resources/kda_temp/$sessionID" . "DESC", "w");
                    fwrite($test, $txt);
                    fclose($test);
                  }
                } else if ($ch == 2) {
                  $txt = "None";
                  $fpathloci = $fpath . "DESC";
                  $myfile = fopen($fpathloci, "w");
                  fwrite($myfile, $txt);
                  fclose($myfile);
                }
              }

              ?>


              <?php $fpathloci = $fpath . "DESC";
              if (strpos(trim(file_get_contents($fpathloci)), "None") !== false) {
                $uploaded_description = 0;
              } else {
                if (file_exists($fpathloci)) {
                  $gwas_file = file_get_contents($fpathloci);
                  $split_file = explode("/", $gwas_file);
                  $uploaded_description = $split_file[2];
                } else
                  $uploaded_description = 0;
              }


              ?>

              <!-- Marker Mapping File Upload div --->
            </div> <!-- End Gene Set Description input -->

            <div id="GSETDupload" style="display: none;">

              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

              <div class="input-file-container" name="Gene Sets Description File" style="width: fit-content;">
                <input class="input-file" id="GSETDuploadInput" name="GSETDuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="GSETDlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> <?php if ($uploaded_description !== 0) {
                                                                                                                      print("Select another file?");
                                                                                                                    } else {
                                                                                                                      print("Select a file...");
                                                                                                                    } ?></label>
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

            </div> <!-- End of upload div--->

            <!--
            <div class="alert-wKDA" id="alert_GSETD"><?php if ($uploaded_description !== 0) {
                                                        echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                      } else {
                                                        print("");
                                                      } ?></div> -->
            <div class="alert-wKDA" id="alert_GSETD"></div>
            <!--Div to alert user of certain comment (i.e. success) -->


          </td>
          <td data-column="Sample Format &#xa;" name="val2_MSEA">
            <!--Third row|Third column (Gene Set Description sample format) -------------------->

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" tooltip="Name of node list (i.e. canonical pathway, co-expression module, or MSEA results)">MODULE</a></th>
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
        <tr>
          <!--Second row of table------------------------------------------>
          <td data-column="File type &#xa;" style="font-size: 18px;">Network

            <div class="informationtext" data-toggle="modal" data-target="#wKDAnetworkinfomodal" href="#wKDAnetworkinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>

          </td>
          <!--Second row|first column of table------------------------------------------>
          <td data-column="Upload/Select File &#xa;">
            <!--Start Network for wKDA Upload ----------------------------->
            <div id="Selectupload_wKDA" class="selectholder KDA" align="center">
              <select class="wKDA" name="formChoice_wKDA" size="1" id="NetworkwKDA_form">
                <option value="0">Please select an option</option>
                <option value="1">Upload Network</option>
                <option value="Resources/networks/bayesian.hs.adipose.txt">Bayesian Adipose Network</option>
                <option value="Resources/networks/bayesian.hs.blood.txt">Bayesian Blood Network</option>
                <option value="Resources/networks/bayesian.hs.brain.txt">Bayesian Brain Network</option>
                <option value="Resources/networks/bayesian.hs.kidney.txt">Bayesian Kidney Network</option>
                <option value="Resources/networks/bayesian.hs.liver.txt">Bayesian Liver Network</option>
                <option value="Resources/networks/bayesian.hs.muscle.txt">Bayesian Muscle Network</option>
                <option value="Resources/networks/networks.hs.all.txt">Bayesian Multitissue Network</option>
                <option value="Resources/networks/GIANT.adipose.txt">GIANT Adipose Network</option>
                <option value="Resources/networks/GIANT.blood.txt">GIANT Blood Network</option>
                <option value="Resources/networks/GIANT.brain.txt">GIANT Brain Network</option>
                <option value="Resources/networks/GIANT.kidney.txt">GIANT Kidney Network</option>
                <option value="Resources/networks/GIANT.liver.txt">GIANT Liver Network</option>
                <option value="Resources/networks/GIANT.muscle.txt">GIANT Muscle Network</option>
                <option value="Resources/networks/FANTOM5_adipose_tissue.txt">FANTOM5 Adipose Network</option>
                <option value="Resources/networks/FANTOM5_blood.txt">FANTOM5 Blood Network</option>
                <option value="Resources/networks/FANTOM5_brain.txt">FANTOM5 Brain Network</option>
                <option value="Resources/networks/FANTOM5_kidney.txt">FANTOM5 Kidney Network</option>
                <option value="Resources/networks/FANTOM5_liver.txt">FANTOM5 Liver Network</option>
                <option value="Resources/networks/FANTOM5_skeletal_muscle.txt">FANTOM5 Skeletal Muscle Network</option>
                <option value="Resources/networks/string_PPI_top5perc.txt">String PPI Network</option>
              </select>



              <br>



              <!-- Mapping Association File Upload div --->
            </div>
            <!--End selectupload_Gset div-->

            <div id="wKDAupload" style="display: none;">

              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>

              <div class="input-file-container" name="Network for wKDA" style="width: fit-content;">
                <input class="input-file" id="wKDAuploadInput" name="wKDAuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <label id="wKDAlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> <?php if ($uploaded_kda !== 0) {
                                                                                                                      print("Select another file?");
                                                                                                                    } else {
                                                                                                                      print("Select a file...");
                                                                                                                    } ?></label>
                <!--Progress bar ------------------------------>
                <div id="wKDAprogressbar" class="progress active" style='display: none;'>
                  <div id="wKDAprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="wKDAprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <p id="wKDAfilereturn" class="file-return"><?php if ($uploaded_kda !== 0) {
                                                              print($uploaded_kda);
                                                            } ?></p>
                <span id='wKDA_uploaded_file'><?php if ($uploaded_kda !== 0) {
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
                  <select class="btn dropdown-toggle btn-light" name="NetConvert" size="1" id="NetConvert" style="font-size: 18px;">
                    <option value="none" selected>None</option>
                    <option value="entrez">Entrez to gene symbol</option>
                    <option value="ensembl">Ensembl to gene symbol</option>
                  </select>
                </td>
              </table>
            </div> <!-- End of upload div--->

            <div class="alert-wKDA" id="alert_wKDA"><?php if ($uploaded_kda !== 0) {
                                                      echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                    } else {
                                                      print("");
                                                    } ?></div>
            <!--Div to alert user of certain comment (i.e. success) -->


          </td>
          <!--Second row|second column of table------------------------------------------>
          <td data-column="Sample Format &#xa;" name="val1_wKDA">
            <!--Start Second row|third column of table------------------------------------------>

            <div class="table-responsive" style="overflow: visible;">
              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#">HEAD</a></th>
                    <th><a href="#">TAIL</a></th>
                    <th><a href="#">WEIGHT</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td data-column="HEAD(Header): ">A1BG</td>
                    <td data-column="TAIL(Header): ">SNHG6</td>
                    <td data-column="WEIGHT(Header): ">1</td>
                  </tr>
                  <tr>
                    <td data-column="HEAD(Header): ">A1BG</td>
                    <td data-column="TAIL(Header): ">UNC84A</td>
                    <td data-column="WEIGHT(Header): ">1</td>
                  </tr>
                  <tr>
                    <td data-column="HEAD(Header): ">A1CF</td>
                    <td data-column="TAIL(Header): ">KIAA1958</td>
                    <td data-column="WEIGHT(Header): ">1</td>
                  </tr>
                </tbody>
              </table>
              <br>
              <p>A <strong>TAB</strong> deliminated text file that
                contains network edges from pre-defined networks. UTF-8/ASCII encoded files recommended. </p>
            </div>
          </td>
          <!--End Second row|third column of table------------------------------------------>
        </tr>
      </tbody>
    </table>
    <!--End of wKDA maintable -->
  </div>


  <!-------------------------------------------------Start of wKDA Parameters table ----------------------------------------------------->
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center;" id="wKDAparameterstable">
      <thead>
        <tr>
          <th colspan='3' style="border-bottom: aliceblue;">Parameters for wKDA</th>
        </tr>
        <tr>
          <th>Parameter type</th>
          <th name="val">Input</th>
        </tr>
      </thead>
      <tbody>
        <tr name="KDA Search Depth">
          <td>Search depth [1-3]:</td>

          <td name="val1">
            <div class="selectholder KDA"><select name="kdaparam_depth" size="1" id="kda_depth">
                <option value="0">Please select option</option>
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </div>
            <!--End selectholder KDA div--->


          </td>
        </tr>
        <tr name="Edge type for wKDA">

          <td>Edge type: </td>

          <td name="val2">
            <div class="selectholder KDA">
              <select name="kdaparam_direct" size="1" id="kda_direct">
                <option value="0">Please select option</option>
                <option value="1" selected>Undirected</option>
                <option value="2">Directed</option>
              </select>

              <!--End selectholder KDA div --->

              <?php

              if ($par_direct3 != 0) {
                $fpathparam = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDAPARAM";
                $test = file($fpathparam);
                // echo "$test[0]";
                array_push($test, "asdf");
                $txt = "job.kda\$direction <- $par_direct3\n";
                $test[1] = $txt;
                // echo "$test[1]";
                $test = array_slice($test, 0, 2);
                // echo "$test[1]";


                $stuff = fopen($fpathparam, "w");
                foreach ($test as $value) {
                  fwrite($stuff, $value);
                }
                fclose($stuff);
              }

              ?>

          </td>


        </tr>

        <tr name="Min Overlap for wKDA">

          <td>Min Hub Overlap:</td>

          <td name="val3"><input class="wkdaparameter" id="minwKDA" type="text" name="minwKDA_overlap" value="<?php if (isset($_POST['minwKDA_overlap']) ? $_POST['minwKDA_overlap'] : null) {
                                                                                                                print($_POST['minwKDA_overlap']);
                                                                                                              } else {
                                                                                                                print("0.33");
                                                                                                              } ?>">
          </td>


        </tr>

        <tr name="Edge factor for wKDA">

          <td>Edge factor:</td>

          <td name="val4"><input class="wkdaparameter" id="edgewKDA" type="text" name="edge_factor" value="<?php if (isset($_POST['edge_factor']) ? $_POST['edge_factor'] : null) {
                                                                                                              print($_POST['edge_factor']);
                                                                                                            } else {
                                                                                                              print("0.0");
                                                                                                            } ?>">
          </td>

        </tr>
      </tbody>
    </table>
  </div>
  <!--End of responsive div for parameters table -->
  <br>

  <!-------------------------------------------------End of wKDA Parameters table ----------------------------------------------------->
  <!-------------------------------------------------Start Review button ----------------------------------------------------->
  <div id="Validatediv_wKDA" style="text-align: center;">
    <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_wKDA">Click to Review</button>

</form>
<!--End of wKDA form -------------------------------------->
</div>

<!-------------------------------------------------End Review button ----------------------------------------------------->


<!---------------------------------------Modal information for kda network -------------------------------------------------------->
<div id="wKDAnetworkinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 45px;">Network for wKDA Files</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="style-msg infomsg" style="margin: 0 auto; width: 80%;padding:30px;font-size: 18px;"> Gene or protein network detailing connections (edges) between the genes or proteins (nodes). The connections can be physical interactions between proteins or regulatory interaction (gene A regulates expression of gene B), for example.
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

<!---------------------------------------Modal information for addMapping -------------------------------------------------------->
<div id="GSETinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 45px;">Gene Sets Files</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="style-msg infomsg" style="margin: 0 auto; width: 80%;padding:30px;font-size: 18px;"> Functionally related gene sets such as co-regulation, shared response, co-localization on chromosomes, or participants of specific biological processes. Typical sources of gene sets includes canonical pathways such as Reactome and KEGG, or coexpression modules constructed using algorithms like weighted coexpression gene networks analysis (WGCNA).
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

<!---------------------------------------Modal information for addMapping -------------------------------------------------------->
<div id="GSETDinfomodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 45px;">Gene Sets Description Files</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
            <div class="style-msg infomsg" style="margin: 0 auto; width: 80%;padding:30px;font-size: 18px;"> To better annotate the gene sets in MSEA output, a description file for the gene sets is needed to specify the source of the gene set and a detailed description of the functional information used to group genes.
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
  var string = "<?php echo $sessionID; ?>";
  var geneset = "<?php echo $geneset; ?>";
  var genesetd = "<?php echo $genesetd; ?>";
  var network = "<?php echo $network; ?>";
  var kdadepth = "<?php echo $kdadepth; ?>";
  var kdadirect = "<?php echo $kdadirect; ?>";
  var minKDA = "<?php echo $minKDA; ?>";
  var edgewKDA = "<?php echo $edgewKDA; ?>";
  var NetConvert = null;
  var GSETConvert = null;
  var idarray = ['wKDAuploadInput'];

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
  $("#KDASTARTtoggleNav").on('click', function(e) {
    var href = $(this).attr('href');

    if ($(href).children('.togglec').css('display') == 'none') {
      $(href).children(0).click();
    }

    var val = $(href).offset().top - $(window).scrollTop() - 65; // at top

    if (val <= 0 || ($(window).scrollTop() != 0 && $(window).scrollTop() < $(href).offset().top)) {
      // below item or scrolled down but not below item
      var val = $(href).offset().top - 65;
    }

    $(window).scrollTop(
      val
    );

    return false;
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

  //If geneset is not empty - pretty much everything else is loaded from session loading -Dan
  if (geneset) {
    $("#Geneset_form").val(geneset);
    $("#alert_GSET").html(successalert);
    if (geneset == "2") {
      $("#dropGeneList").show();
      $("#GSETConvertContainer").show();
      $("#dropzoneKDA").val("<?php echo $genesetd_content; ?>");
    }

    $("#Genesetd_form").val(genesetd);
    $("#alert_GSETD").html(successalert);


    $("#NetworkwKDA_form").val(network);
    $("#alert_wKDA").html(successalert);

    $("#kda_depth").val(kdadepth);
    $("#kda_direct").val(kdadirect);
    $("#minwKDA").val(minKDA);
    $("#edgewKDA").val(edgewKDA);
  }




  var n = localStorage.getItem('on_load_session');
  localStorage.setItem("on_load_session", string);


  $(document).ready(function() {


    $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + string).attr('tooltip', 'Save your session ID! Click to copy.');
    $('#session_id').css("padding", "17px 30px");

  });

  var dropzone = document.querySelector('#dropzoneKDA');
  dropzone.addEventListener("dragenter", onDragEnter, false);
  dropzone.addEventListener('dragover', onDragOver, false);
  dropzone.addEventListener('drop', onDrop, false);


  function onDragEnter(e) {
    e.stopPropagation();
    e.preventDefault();
  }

  function onDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // it's a copy!
  }

  function onDrop(evt) {
    evt.stopPropagation();
    evt.preventDefault();

    var files = evt.dataTransfer.files; // object FileList
    for (var i = 0; i < files.length; i++) {
      if (files[i].type == "text/plain") {
        var reader = new FileReader();
        reader.onload = function(event) {
          dropzone.value += event.target.result.replace(/[^a-z0-9\n]/gi, '');
          //console.log(event.target)
        }
        //instanceOfFileReader.readAsText(blob[, encoding]);
        reader.readAsText(files[i], "UTF-8");
      } else {
        console.log(files[i].type);
      }
    }
  }


  // set up select boxes
  $('.selectholder.KDA').each(function() {
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
        $('.activeselectholder.KDA').each(function() {
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
  $('.selectholder.KDA .selectdropdown span').click(function() {

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
    var container = $(".selectholder.KDA");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      $('.activeselectholder.KDA').each(function() {
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


  ///////////////Start Validation/REVIEW button -- Function for clicking 'Click to review button'///////////////////////////////////
  $("#Validatebutton_wKDA").on('click', function() {
    var select = $("select[name='formChoice_wKDA'] option:selected").index(),
      select2 = $("select[name='kdaparam_depth'] option:selected").index(),
      select3 = $("select[name='kdaparam_direct'] option:selected").index();

    var selectarray = [select];
    var idarray = ['wKDAuploadInput'];
    var errorlist = [];
    selectarray.forEach(myFunction);


    if (select2 == 0) {
      errorlist.push($("select[name='kdaparam_depth']").parent().parent().attr('name') + ' is empty!');
    }

    if (select3 == 0) {
      errorlist.push($("select[name='kdaparam_direct']").parent().parent().attr('name') + ' is empty!');
    }

    $('.wkdaparameter').each(function() {
      if ($(this).val() == "") {
        errorlist.push($(this).parent().parent().attr('name') + ' is empty!');
      }
    });
    if (errorlist.length === 0) {
      // $(this).html('Please wait ...')
      //   .attr('disabled', 'disabled');
      $("#wKDAdataform").submit();
    } else {
      var result = errorlist.join("\n");
      //alert(result);
      $('#errorp_wKDA').html(result);
      $("#errormsg_wKDA").fadeTo(2000, 500).slideUp(500, function() {
        $("#errormsg_wKDA").slideUp(500);
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



  $("#minwKDA").inputFilter(function(value) {
    return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });

  $("#edgewKDA").inputFilter(function(value) {
    return /^\d*[.]?\d{0,1}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });


  ///////////////////////////////////////////////Start Tutorial Button script'///////////////////////////////////

  var myTutButton_wKDA = document.getElementById("myTutButton_wKDA");
  var val_wKDA = 0;

  //begin function for when button is clicked-------------------------------------------------------------->
  myTutButton_wKDA.addEventListener("click", function() {

    //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
    var $this_wKDA = $(this);

    //If tutorial is already opened yet, then do this-------------------------------------------------------------->
    if ($this_wKDA.data('clicked')) {


      $('.tutorialbox').hide();

      $('#wKDAparameterstable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });

      $('#wKDAmaintable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });


      $this_wKDA.data('clicked', false);
      val_wKDA = val_wKDA - 1;
      $("#myTutButton_wKDA").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

    }

    //If tutorial is not opened yet, then do this-------------------------------------------------------------->
    else {
      $this_wKDA.data('clicked', true);
      val_wKDA = val_wKDA + 1; //val counter to not duplicate prepend function


      if (val_wKDA == 1) //Only prepend the tutorial once
      {


        $('#wKDAparameterstable').find('td[name="val1"]').eq(-1).after(`
                                <td name="tut">
                                <strong>Search Depth</strong>: used to define a candidate key driver's local network neighborhood by considering genes at a given distance or depth <br>
                                    <strong>Options</strong>: 1/2/3. It indicates the maximum edge distance starting from each candidate key driver gene. <br>
                                    <strong>Default Value</strong>: 1
                                 </td>

                                `);

        $('#wKDAparameterstable').find('td[name="val2"]').eq(-1).after(`

                                <td name="tut">
                                <strong>Edge Type</strong>: defines whether the directionality of edges is considered. <br>
                                        <strong>Options</strong>:  Undirected and Directed. The former ignores directionality and the latter considers directionality by requiring the candidate key driver to be upstream of its local neighborhood genes (HEAD is upstream of TAIL). <br>
                                        <strong>Default value</strong>: Undirected
                                 </td>

                                `);
        $('#wKDAparameterstable').find('td[name="val3"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Min Hub Overlap</strong>: Used as threshold for gene overlaps to group hubs as co-hubs. <br>
                                        <strong>Options</strong>: Between 0-1. The higher the value, the more the local network neighborhood of hubs must overlap to be considered co-hubs. <br>
                                        <strong>Default value</strong>: 0.33
                                </td>`);

        $('#wKDAparameterstable').find('td[name="val4"]').eq(-1).after(`
                                <td name="tut">
                                    <strong>Edge Factor</strong>: Used to weight edge weight info of network to the power of the value entered<br>
                                    <strong>Options</strong>: Between 0-1. A power of zero would set all the edge weights to be equal (1). <br>
                                    <strong>Default Value</strong>: 0 (do not consider weights)
                                </td>`);



        $('#wKDAparameterstable').find('th[name="val"]').eq(-1).after('<th name="tut">Comments</th>');

        $('#wKDAmaintable').find('th[name="val_wKDA"]').eq(-1).after('<th name="tut">Comments</th>');

        $('#wKDAmaintable').find('td[name="val_wKDA"]').eq(-1).after(`
                                <td name="tut">
                                <strong>Select/Upload Nodes File</strong> Nodes to query network. Multiple node/gene sets can be uploaded (node/gene set name in the MODULE column). If multiple node/gene sets uploaded, KDA for each gene set with be run in parallel (KDs for specific node sets will be found). The user can also copy and paste a gene list in a text box that appears after selecting 'Input single list of genes'.
                                 </td>
                                `);

        $('#wKDAmaintable').find('td[name="val1_wKDA"]').eq(-1).after(`
                                <td name="tut">
                                <strong>Select/Upload Network Menu</strong> gives the user sample network datasets. The description for the sample network datasets is included in the Sample Format Table to your left. The first option in the menu is for uploading your own network. The input file format is described in Sample Format Table. Included are a number of tissue-specific bayesian networks and a PPI network.
                                 </td>

                                `);

        $('.tutorialbox').show();
        $('.tutorialbox').html('wKDA aims to pinpoint key regulator genes or key drivers (KDs) of the disease related gene sets from MSEA using gene network topology and edge weight information. Specifically, wKDA first screens the network for candidate hub genes. Then the disease gene sets are overlaid onto the subnetworks of the candidate hubs to identify KDs whose neighbors are enriched with disease genes.');





      }
      $("#myTutButton_wKDA").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
    }



  });







  ///////////////////////////////////////////////First input form (Gene Sets) function/////////////////////////////////////////////////////////////



  $('select.wKDA').on('change', function() {

    var select = $(this).find('option:selected').index();
    if (select != 1)
      $(this).parent().next().hide();

    if (select == 1)
      $(this).parent().next().show();

    if (select > 1)
      $(this).parent().nextAll(".alert-wKDA").eq(0).html(successalert).hide().fadeIn(300);
    else if (select == 1)
      $(this).parent().nextAll(".alert-wKDA").eq(0).html(uploadalert).hide().fadeIn(300);
    else
      $(this).parent().nextAll(".alert-wKDA").eq(0).empty();


  });

  $('select.wKDAGenes').on('change', function() {

    var select = $(this).find('option:selected').index();
    if (select != 1 || select != 2) {
      $(this).parent().next().hide();
      $("#dropGeneList").hide();
      $("#GSETConvertContainer").hide();
    }
    if (select == 1) {
      $(this).parent().next().show();
      $("#GSETConvertContainer").show();
    }

    if (select == 2) {
      $("#dropGeneList").show();
      $("#GSETConvertContainer").show();
    }
    /*$("#dropGeneList").html('<textarea name="inputgenesKDA" id="dropzoneKDA" placeholder="Drop text file(s) or click to manually input genes"></textarea>');*/

    if (select > 2)
      $(this).parent().nextAll(".alert-wKDA").eq(0).html(successalert).hide().fadeIn(300);
    else if (select == 1)
      $(this).parent().nextAll(".alert-wKDA").eq(0).html(uploadalert).hide().fadeIn(300);
    else
      $(this).parent().nextAll(".alert-wKDA").eq(0).empty();


  });


  $('select.wKDA').each(function() {
    $(this).trigger('change');

  });

  $('#Geneset_form').on('change', function() {
    var select = $(this).find('option:selected').index();
    if (select == 1) {
      $('#gsetd_row').show();
    } else {
      $('#gsetd_row').hide();
    }
  });




  $(document).on('change', '#GSETuploadInput', function() {
    var name = document.getElementById("GSETuploadInput").files[0].name;
    var form_data = new FormData(document.getElementById('wKDAdataform'));
    var ext = name.split('.').pop().toLowerCase();
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("GSETuploadInput").files[0]);
    var f = document.getElementById("GSETuploadInput").files[0];
    var fsize = f.size || f.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#GSETuploadInput"); //get the id
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      form_data.append("GSETuploadInput", document.getElementById('GSETuploadInput').files[0]);
      form_data.append("rmchoice", "4");
      $.ajax({
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
          //Upload progress
          xhr.upload.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
              $('#GSETprogressbar').show();
              var percentComplete = (evt.loaded / evt.total) * 100;
              //Do something with upload progress

              $('#GSETprogresswidth').width(percentComplete.toFixed(2) + '%');
              $('#GSETprogresspercent').html(percentComplete.toFixed(2) + '%');

            }

          }, false);
          return xhr;
        },
        url: "upload_GSET.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          $('#GSET_uploaded_file').html("<label class='text-success'>File Uploading...</label>");
        },
        success: function(data) {
          $('#GSETprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#GSETprogressbar').hide();
          if (data == 111) {
            var fullPath = $('#GSETuploadInput').val();
            var filename = fullPath.replace(/^.*[\\\/]/, "");
            geneset = "./Resources/kda_temp/" + filename;
            $('#GSETfilereturn').html(filename);
            $('#GSET_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
            $('#alert_GSET').hide();
          } else if (data == 0) {

            $('#GSET_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong> Column headers are incorrect! <br> Please refer to the sample file format and reupload!</div>`);
            var control = $("#GSETuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#GSETfilereturn").empty();
          } else if (data == 10) {
            $('#GSET_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong> Data not detected! <br> Please refer to the sample file format and reupload!</div>`);
            var control = $("#GSETuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#GSETfilereturn").empty();
          } else {

            $('#GSET_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error: </strong>` + data + `</div>`);
            0
            var control = $("#GSETuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#GSETfilereturn").empty();
          }


        }

      });
    }
  });


  $(document).on('change', '#GSETDuploadInput', function() {

    var name = document.getElementById("GSETDuploadInput").files[0].name;
    var form_data = new FormData(document.getElementById('wKDAdataform'));
    var ext = name.split('.').pop().toLowerCase();
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("GSETDuploadInput").files[0]);
    var f = document.getElementById("GSETDuploadInput").files[0];
    var fsize = f.size || f.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#GSETDuploadInput"); //get the id
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      form_data.append("GSETDuploadInput", document.getElementById('GSETDuploadInput').files[0]);
      form_data.append("rmchoice", "4");
      $.ajax({
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
          //Upload progress
          xhr.upload.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
              $('#GSETDprogressbar').show();
              var percentComplete = (evt.loaded / evt.total) * 100;
              //Do something with upload progress

              $('#GSETDprogresswidth').width(percentComplete.toFixed(2) + '%');
              $('#GSETDprogresspercent').html(percentComplete.toFixed(2) + '%');

            }

          }, false);
          return xhr;
        },
        url: "upload_GSETD.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          $('#GSETD_uploaded_file').html("<label class='text-success'>File Uploading...</label>");
        },
        success: function(data) {
          $('#GSETDprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#GSETDprogressbar').hide();
          if (data == 111) {
            var fullPath = $('#GSETDuploadInput').val();
            var filename = fullPath.replace(/^.*[\\\/]/, "");
            genesetd = "./Resources/kda_temp/" + filename;
            $('#GSETDfilereturn').html(filename);
            $('#GSETD_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
            $('#alert_GSETD').hide();
          } else if (data == 0) {

            $('#GSETD_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong> Column headers are incorrect! <br> Please refer to the sample file format and reupload!</div>`);
            var control = $("#GSETDuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#GSETDfilereturn").empty();
          } else if (data == 10) {
            $('#GSETD_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong> Data not detected! <br> Please refer to the sample file format and reupload!</div>`);
            var control = $("#GSETDuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#GSETDfilereturn").empty();
          } else {

            $('#GSETD_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error: </strong>` + data + `</div>`);
            var control = $("#GSETDuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#GSETDfilereturn").empty();
          }

        }
      });
    }
  });


  $(document).on('change', '#wKDAuploadInput', function() {
    var name = document.getElementById("wKDAuploadInput").files[0].name;
    var form_data = new FormData(document.getElementById('wKDAdataform'));
    var ext = name.split('.').pop().toLowerCase();
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("wKDAuploadInput").files[0]);
    var f = document.getElementById("wKDAuploadInput").files[0];
    var fsize = f.size || f.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      var control = $("#wKDAuploadInput"); //get the id
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      form_data.append("wKDAuploadInput", document.getElementById('wKDAuploadInput').files[0]);
      form_data.append("rmchoice", 4);
      $.ajax({
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
          //Upload progress
          xhr.upload.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
              $('#wKDAprogressbar').show();
              var percentComplete = (evt.loaded / evt.total) * 100;
              //Do something with upload progress

              $('#wKDAprogresswidth').width(percentComplete.toFixed(2) + '%');
              $('#wKDAprogresspercent').html(percentComplete.toFixed(2) + '%');

            }

          }, false);
          return xhr;
        },
        url: "upload_wKDA.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          $('#wKDA_uploaded_file').html("<label class='text-success'>File Uploading...</label>");
        },
        success: function(data) {
          $('#wKDAprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#wKDAprogressbar').hide();
          if (data == 111) {
            var fullPath = $('#wKDAuploadInput').val();
            var filename = fullPath.replace(/^.*[\\\/]/, "");
            network = "./Resources/kda_temp/" + filename;
            $('#wKDAfilereturn').html(filename);
            $('#wKDA_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
            $('#alert_wKDA').hide();
          } else if (data == 0) {

            $('#wKDA_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong> Column headers are incorrect! <br> Please refer to the sample file format and reupload!</div>`);
            var control = $("#wKDAuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#wKDAfilereturn").empty();
          } else if (data == 10) {
            $('#wKDA_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong> Data not detected! <br> Please refer to the sample file format and reupload!</div>`);
            var control = $("#wKDAuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#wKDAfilereturn").empty();
          } else {

            $('#wKDA_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error: </strong>` + data + `</div>`);
            0
            var control = $("#wKDAuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#wKDAfilereturn").empty();
          }


        }

      });
    }
  });

  var fileInput4 = document.getElementById("GSETuploadInput"),
    button4 = document.getElementById("GSETlabelname"),
    the_return4 = document.getElementById("GSETfilereturn"),
    fileInput5 = document.getElementById("GSETDuploadInput"),
    button5 = document.getElementById("GSETDlabelname"),
    the_return5 = document.getElementById("GSETDfilereturn"),
    fileInput6 = document.getElementById("wKDAuploadInput"),
    button6 = document.getElementById("wKDAlabelname"),
    the_return6 = document.getElementById("wKDAfilereturn");


  button4.addEventListener("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      fileInput4.focus();
    }
  });
  button4.addEventListener("click", function(event) {
    fileInput4.focus();
    return false;
  });

  button5.addEventListener("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      fileInput5.focus();
    }
  });
  button5.addEventListener("click", function(event) {
    fileInput5.focus();
    return false;
  });

  button6.addEventListener("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      fileInput6.focus();
    }
  });
  button6.addEventListener("click", function(event) {
    fileInput6.focus();
    return false;
  });

  fileInput4.addEventListener("change", function(event) {

    button4.innerHTML = "Select another file?";
  });


  fileInput5.addEventListener("change", function(event) {

    button5.innerHTML = "Select another file?";
  });

  fileInput6.addEventListener("change", function(event) {

    button6.innerHTML = "Select another file?";
  });



  ///////////////Start Submit Function (wKDA form) -- Function for clicking 'Click to review button'///////////////////////////////////


  function wKDAreview() //This function gets the review table for wKDA
  {
    var choice = 4;
    $.ajax({
      url: "wKDA_moduleprogress.php",
      method: "GET",
      data: {
        sessionID: string,
        rmchoice: choice,
        geneset: geneset,
        genesetd: genesetd,
        network: network,
        kdadepth: kdadepth,
        kdadirect: kdadirect,
        minKDA: minKDA,
        edgewKDA: edgewKDA,
        NetConvert: NetConvert,
        GSETConvert: GSETConvert,
        rerun: "T"
      },
      success: function(data) {
        $('#myKDASTART_review').html(data);
      }
    });
    $('#KDASTARTtab2').show();
    $('#KDASTARTtab2').click();
  }

  $("#dropzoneKDA").focusout(function() {
    var genelistval = $("#dropzoneKDA").val();
    $.ajax({
      type: "POST",
      dataType: 'JSON',
      data: {
        genelist: genelistval,
        sessionID: string
      },
      url: "KDAstart_parameters_write_input_gene_list.php",
      success: function(data) {
        geneset = data;
        $("#alert_GSET").html(successalert);
      }
    })
  })
  ///////////////Start Submit Function (wKDA form) -- Function for clicking 'Click to review button'///////////////////////////////////

  NetConvert = $("#NetConvert").val();
  genesetd = $("#Genesetd_form").val();
  GSETConvert = $("#GSETConvert").val();
  $("#Geneset_form").change(function() {
    if ($("#Geneset_form").val() == 1) {
      $("#GSET_uploaded_file").empty();
    }
    genesetval = $("#Geneset_form").val();
    geneset = $("#Geneset_form").val();

    if (genesetval == "Resources/glgc.ldl.msea.gene.sets.txt") {
      genesetd = "Resources/pathways/KEGG_Reactome_BioCarta_info_abbr.txt";
      GSETConvert = "none";
      NetConvert = "none";
    }

    /*
    if(genesetval=="1" || genesetval=="2"){
      $("#Genesetd_form").val("2");
      $("#Genesetd_form").click();
    }
    */
  });
  $("#GSETConvert").change(function() {
    GSETConvert = $("#GSETConvert").val();
  });
  $("#NetConvert").change(function() {
    NetConvert = $("#NetConvert").val();
  });
  $("#Genesetd_form").change(function() {
    genesetd = $("#Genesetd_form").val();

  });
  $("#NetworkwKDA_form").change(function() {
    network = $("#NetworkwKDA_form").val();
  })
  $('#wKDAdataform').submit(function(e) {
    kdadepth = $("#kda_depth").val();
    kdadirect = $("#kda_direct").val();
    minKDA = $("#minwKDA").val();
    edgewKDA = $("#edgewKDA").val();
    e.preventDefault();
    wKDAreview();
    // var form_data = new FormData(document.getElementById('wKDAdataform'));
    // form_data.append("sessionID", string);

    // $.ajax({
    //   'url': 'KDAstart_parameters.php',
    //   'type': 'POST',
    //   'data': form_data,
    //   processData: false,
    //   contentType: false,
    //   'success': function(data) {
    //     $("#myKDASTART").html(data);

    //   }
    // });



  });
</script>