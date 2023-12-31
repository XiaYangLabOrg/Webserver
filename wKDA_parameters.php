<?php
function debug_to_console($data)
{
  $output = $data;
  if (is_array($output))
    $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
//This KDA parameters files is for the MDF, MSEA, and META pipeline. The wKDA-only pipeline has a different php file ("KDAstart_parameters.php").
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";

/* Initialize PHP variables
sessionID = the saved session 

rmchoice = type of pipeline chouce

GET = if the user enters the link directly
POST = if PHP enters the link

*/

if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];

  $geneset = null;
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
  }else{
    $network = "";
    $kdadepth = "";
    $kdadirect = "";
    $minKDA = "";
    $edgewKDA = "";
  }
}


if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  $sessionID = $_POST['sessionID'];
}


if (isset($_GET['rmchoice']) ? $_GET['rmchoice'] : null) {
  $rmchoice = $_GET['rmchoice'];
}

if (isset($_POST['rmchoice']) ? $_POST['rmchoice'] : null) {
  $rmchoice = $_POST['rmchoice'];
}


if ($rmchoice == 1) {
  $fjson = "./Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
} else if ($rmchoice == 2) {
  $fjson = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "data.json";
} else if ($rmchoice == 3) {
  $fjson = "./Data/Pipeline/Resources/meta_temp/$sessionID" . "metaparam.json";
}
if (file_exists($fjson)) {
  $data = json_decode(file_get_contents($fjson),true)["data"][0];
  if ($geneset == null) {
    $geneset = $data["geneset"];
    $genesetd = $data["genedesc"];
  }
}


//Path to where POST (user selected) data is stored
$fpostOut = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "_KDA_postdata.txt";

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


/* Creates the KDAmodule file from the SSEA results  */
$file_path = fopen($ROOT_DIR . "Data/Pipeline/Resources/kda_temp/" . "$sessionID" . "KDAMODULE", "w");
if ($rmchoice == 3)
  $mod_file = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/$sessionID" . "_meta_result/ssea/$sessionID" . ".MSEA_merged_modules.txt";
else
  $mod_file = $ROOT_DIR . "Data/Pipeline/Results/ssea/$sessionID.MSEA_merged_modules.txt";

fwrite($file_path, $mod_file);
fclose($file_path);
chmod($ROOT_DIR . "Data/Pipeline/Resources/kda_temp/" . "$sessionID" . "KDAMODULE", 755); //sets permission, change from plus to .


/* Creates the KDA description file from previous results  */
//$file_path = fopen($ROOT_DIR . "Data/Pipeline/Resources/kda_temp/" . "$sessionID" . "DESC", "w");

//JD change do this in wkda_moduleprogress and run_wKDA
//Checks which pipeline and set correct file path
/*
if ($rmchoice == 1)
  $fpathloc = $ROOT_DIR . "Data/Pipeline/Resources/ssea_temp/$sessionID" . "DESC";
else if ($rmchoice == 2)
  $fpathloc = $ROOT_DIR . "Data/Pipeline/Resources/msea_temp/$sessionID" . "DESC";
else
  $fpathloc = $ROOT_DIR . "Data/Pipeline/Resources/meta_temp/$sessionID" . "DESC";


$gwas_file = file_get_contents($fpathloc);
fwrite($file_path, $gwas_file);
fclose($file_path);
chmod($ROOT_DIR + "Data/Pipeline/Resources/kda_temp/" . "$sessionID" . "DESC", 0644); //sets permission
*/


/* Initializes form data variables. Imo this is not very efficient, but this is what was here before. So I didn't change it.
There are definitely better ways to do this though...

 */
$data = (isset($_POST['formChoice_wKDA']) ? $_POST['formChoice_wKDA'] : null);
$data2 = (isset($_POST['kdaparam_depth']) ? $_POST['kdaparam_depth'] : null);
$data3 = (isset($_POST['kdaparam_direct']) ? $_POST['kdaparam_direct'] : null);
debug_to_console("Data:".$data);
debug_to_console("Data2:".$data2);
debug_to_console("Data3:".$data3);

if($data!=null){
  if (strlen($data) < 3) {
    $kdaformChoice = 0;
    $par_depth = 0;
    $par_direct = 0;
  } else {
    $pieces = explode("|", $data);
    $sessionID = $pieces[0];
    $kdaformChoice = (int)$pieces[1];
    $par_depth = (int)$pieces[2];
    $par_direct = (int)$pieces[3];
  }
}


if($data2!=null){
  if (strlen($data2) < 3) {
    $path = 0;
    $kda = 0;
    $par_depth2 = 0;
    $par_direct2 = 0;
  } else {
    $pieces2 = explode("|", $data2);
    $sessionID = $pieces2[0];
    $path = (int)$pieces2[1];
    $kda = (int)$pieces2[2];
    $par_depth2 = (int)$pieces2[3];
    $par_direct2 = (int)$pieces2[4];
  }
}


if($data3!=null){
  if (strlen($data3) < 3) {
    $kda2 = 0;
    $par_depth3 = 0;
    $par_direct3 = 0;
  } else {
    $pieces3 = explode("|", $data3);
    $sessionID = $pieces3[0];
    $kda2 = (int)$pieces3[1];
    $par_depth3 = (int)$pieces3[2];
    $par_direct3 = (int)$pieces3[3];
  }
}



//Store some file path variables to be used later
$fpath = "./Data/Pipeline/Resources/$sessionID";
$kdapath = "./Data/Pipeline/Resources/kda_temp/$sessionID" . "KDA";

/***************************************
Session ID
Since we don't have a database, we have to update the txt file with the path information
 ***************************************/

$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";


#LEFT OFF HERE seems we have problem with session file
if (file_exists($fsession)) //checks if the session file exists already
{
  //if it does exist, update session

  $session = explode("\n", file_get_contents($fsession));
  //Create different array elements based on new line
  debug_to_console($session);
  $pipe_arr = preg_split("/[\t]/", $session[0]);
  debug_to_console($pipe_arr);
  $pipeline = $pipe_arr[1];

  if ($pipeline == "GWASskipped") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.75")) {
        return 'Mergeomics_Path:' . "\t" . "2" . "\n";
      }
      return $data;
    }
  } else if ($pipeline == "GWAS") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "2.75")) {
        return 'Mergeomics_Path:' . "\t" . "3" . "\n";
      }
      return $data;
    }
  } else if ($pipeline == "MSEA" || $pipeline == "META") {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
      if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.75")) { //change from 1.75 --> 2
        return 'Mergeomics_Path:' . "\t" . "2" . "\n";
      }
      return $data;
    }
  }
  $data = array_map('replace_a_line', $data);
  file_put_contents($fsession, implode('', $data));
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
  $kdaformChoice = $explodeformChoice[1]; // initialize the data to form choice ($kdaformChoice = 1)

  //repeat above for the other form choices
  $splitformChoice2 = preg_split("/[\t]/", $postdata[1]);
  $explodeformChoice2 = explode("|", $splitformChoice2[1]);
  $par_depth2 = $explodeformChoice2[3];

  $splitformChoice3 = preg_split("/[\t]/", $postdata[2]);
  $explodeformChoice3 = explode("|", $splitformChoice3[1]);
  $par_direct3 = $explodeformChoice3[3];

  $splitminwKDA = preg_split("/[\t]/", $postdata[3]);
  $minwKDA = $splitminwKDA[1];

  $splitedgewKDA = preg_split("/[\t]/", $postdata[4]);
  $edgewKDA = $splitedgewKDA[1];
}

?>


<!-- Error message box (slides up and down) ===================================================== -->
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
    This part of the pipeline is for performing KDA on modules obtained from MSEA.
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
          <td data-column="File type &#xa;">Network for wKDA

            <div class="informationtext" data-toggle="modal" data-target="#wKDAnetworkinfomodal" href="#wKDAnetworkinfomodal">
              <div class="divider divider-short divider-rounded divider-center"><i class="icon-info"></i></div>
            </div>

          </td>
          <!--Second row|first column of table------------------------------------------>
          <td data-column="Upload/Select File &#xa;">
            <!--Start Network for wKDA Form ----------------------------->
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
            </div>
            <!--End selectupload_wKDA div-->

            <!-- Network for wKDA File Upload div --->
            <div id="wKDAupload" style="display: none;">
              <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file</div>
              <div class="input-file-container" name="Network for wKDA" style="width: fit-content;">
                <input class="input-file" id="wKDAuploadInput" name="wKDAuploadedfile" type="file" accept="text/plain" data-show-preview="false">
                <!--
                <label id="wKDAlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> <?php if ($uploaded_kda !== 0) {
                                                                                                                      print("Select another file?");
                                                                                                                    } else {
                                                                                                                      print("Select a file...");
                                                                                                                    } ?></label>
                -->
                <label id="wKDAlabelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file...</label>
                <!--Progress bar ------------------------------>
                <div id="wKDAprogressbar" class="progress active" style='display: none;'>
                  <div id="wKDAprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="wKDAprogresspercent"></span>
                  </div>
                </div>
                <!--Progress bar ------------------------------>
                <!--
                <p id="wKDAfilereturn" class="file-return"><?php if ($uploaded_kda !== 0) {
                                                              print($uploaded_kda);
                                                            } ?></p>
                <span id='wKDA_uploaded_file'><?php if ($uploaded_kda !== 0) {
                                                echo nl2br('<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;margin:0px;"></i><strong>Success!</strong> </div>');
                                              } else {
                                                print("");
                                              } ?></span>-->
                <p id="wKDAfilereturn" class="file-return"></p>
                <span id='wKDA_uploaded_file'></span>
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
            <!--
            <div class="alert-wKDA" id="alert_wKDA"><?php if ($uploaded_kda !== 0) {
                                                      echo nl2br('<div class="alert alert-warning"><div class="sb-msg"><i class="icon-warning-sign"></i> <strong>Maximum File Size:</strong> 400Mb</div><div class="sb-msg"><i class="icon-warning-sign"></i><strong>Accepted file type:</strong> *.txt</div></div>');
                                                    } else {
                                                      print("");
                                                    } ?></div>
            -->
            <div class="alert-wKDA" id="alert_wKDA"></div>
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
                    <td data-column="TAIL(Header): ">A1BG</td>
                    <td data-column="HEAD(Header): ">SNHG6</td>
                    <td data-column="WEIGHT(Header): ">1</td>
                  </tr>
                  <tr>
                    <td data-column="TAIL(Header): ">A1BG</td>
                    <td data-column="HEAD(Header): ">UNC84A</td>
                    <td data-column="WEIGHT(Header): ">1</td>
                  </tr>
                  <tr>
                    <td data-column="TAIL(Header): ">A1CF</td>
                    <td data-column="HEAD(Header): ">KIAA1958</td>
                    <td data-column="WEIGHT(Header): ">1</td>
                  </tr>
                </tbody>
              </table>
              <br>
              <p>A <strong>TAB</strong> deliminated text file that
                contains network edges from pre-defined networks</p>
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
        <!--KDA Search Depth text input--->
        <tr name="KDA Search Depth">
          <td data-column="File type &#xa;">Search depth [1-3]:</td>
          <!--End KDA Search Depth text input--->


          <!--KDA Depth select input--->
          <td name="val1">
            <div class="selectholder KDA">
              <select name="kdaparam_depth" size="1" id="kda_depth">
                <option value="0">Please select option</option>
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
              <!--KDA Depth select input--->
            </div>
            <!--End selectholder KDA div--->

          </td>
        </tr>
        <tr name="Edge type for wKDA">
          <!--Edge type for wKDA select input--->
          <td>Edge type: </td>

          <td name="val2">
            <div class="selectholder KDA">
              <select name="kdaparam_direct" size="1" id="kda_direct">
                <option value="0">Please select option</option>
                <option value="1" selected>Undirected</option>
                <option value="2">Directed</option>
              </select>
              <!--End Edge type for wKDA select input --->
            </div>
            <!--End selectholder KDA div --->


          </td>


        </tr>

        <tr name="Min Overlap for wKDA">

          <td>Min Hub Overlap:</td>

          <td name="val3"><input class="wkdaparameter" id="minwKDA" type="text" name="minwKDA_overlap" value="0.33">
          </td>


        </tr>

        <tr name="Edge factor for wKDA">

          <td>Edge factor:</td>
          <!--Edge factor text input--->
          <td name="val4"><input class="wkdaparameter" id="edgewKDA" type="text" name="edge_factor" value="0.0">
          </td>
          <!--End Edge factor text input--->
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

<!---------------------------------------Modal information for addMapping -------------------------------------------------------->
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




<script type="text/javascript">
  /**********************************************************************************************
Javascript functions/scripts (These are inlined because it was easier to do)
You can technically extract it and just call it externally if you want to keep the php page cleaner, but not needed
***********************************************************************************************/
      /*
      var toggles = ["#SSEAtoggle","#wKDAtoggle"];
      var navtoggle = ["#MSEAflowChart","#KDAflowChart"];
      for (i = 0; i < toggles.length; i++) {
        console.log(toggles[i]);
        console.log($(toggles[i]).css('display'));
        console.log(navtoggle[i]);
        if($(toggles[i]).css('display') == 'block'){
          //$(navtoggle[i]).removeAttr('style');
          $(navtoggle[i]).css('opacity','1');
          //$(navtoggle[i]).css('visibility','visible');
          //$(navtoggle[i]).addClass('activePipe').css('opacity','1');
          //.removeAttr("style")
        }
      }
      */

  var string = "<?php echo $sessionID; ?>"; //get sessionID and store to javascript variable
  var geneset = "<?php echo $geneset; ?>";
  var genesetd = "<?php echo $genesetd; ?>";
  var network = "<?php echo $network; ?>";
  var kdadepth = "<?php echo $kdadepth; ?>";
  var kdadirect = "<?php echo $kdadirect; ?>";
  var minKDA = "<?php echo $minKDA; ?>";
  var edgewKDA = "<?php echo $edgewKDA; ?>";
  var NetConvert = null;


  if (geneset) {
    $("#Geneset_form").val(geneset);
    $("#alert_GSET").html(successalert);
    if (geneset == "2") {
      $("#dropGeneList").show();
      $("#dropzoneKDA").val("<?php echo $genesetd_content; ?>");
    }

    $("#Genesetd_form").val(genesetd);
    $("#alert_GSETD").html(successalert);

    if (network) {
      $("#NetworkwKDA_form").val(network);
      $("#alert_wKDA").html(successalert);
    }
    if (kdadepth) {
      $("#kda_depth").val(kdadepth);
    }
    if (kdadirect) {
      $("#kda_direct").val(kdadirect);
    }
    if (minKDA) {
      $("#minwKDA").val(minKDA);
    }
    if (edgewKDA) {
      $("#edgewKDA").val(edgewKDA);
    }
  }
  
  //sidebar flowchart
  var rmchoice = "<?php echo $rmchoice; ?>";
  $("#MSEAflowChart").next().addClass("activeArrow");
  $("#MSEAtoPharmflowChart").next().addClass("activeArrow");
  if (rmchoice == 1) { // SSEA
    var link = "#wKDAtoggle";
  }
  else if (rmchoice == 2){ // ETPM
    var link = "#MSEA2KDAtoggle";
  }
  else { // meta
    var link = "#META2KDAtoggle";
  }
  $("#KDAflowChart").addClass("activePipe").html('<a href="' + link +'" class="pipelineNav" id="wKDAtoggleNav">KDA</a>').css("opacity","1");

  $("#wKDAtoggleNav").on('click', function(e){
    var href = $(this).attr('href');

    if ($(href).children('.togglec').css('display') == 'none') {
        $(href).children(0).click();
    }

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


  /**********************************************************************************************
  Set up Select slide down js function
  ***********************************************************************************************/
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

  /**********************************************************************************************
  Validation/REVIEW button -- Function for clicking 'Click to review button
  Will create an error message at the top if user forgets or does not have all data entered into form
  ***********************************************************************************************/

  $("#Validatebutton_wKDA").on('click', function() {

    //initialize variables to form choice
    var select = $("select[name='formChoice_wKDA'] option:selected").index(),
      select2 = $("select[name='kdaparam_depth'] option:selected").index(),
      select3 = $("select[name='kdaparam_direct'] option:selected").index();

    //initialize arrays to check against formchoice
    var selectarray = [select];
    var idarray = ['wKDAuploadInput'];
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
      }


    }

    //since these select options (kdaparam_depth and kdaparam_direct) does not have an upload form, I did them separately
    if (select2 == 0) {
      errorlist.push($("select[name='kdaparam_depth']").parent().parent().attr('name') + ' is empty!');
    }

    if (select3 == 0) {
      errorlist.push($("select[name='kdaparam_direct']").parent().parent().attr('name') + ' is empty!');
    }

    //check the text inputs. If they're empty, push the error message into error array
    $('.wkdaparameter').each(function() {
      if ($(this).val() == "") {
        errorlist.push($(this).parent().parent().attr('name') + ' is empty!');
      }
    });
    //check if the errorlist array is empty
    if (errorlist.length === 0) {
      //if errorlist array is empty, then submit form
      // $(this).html('Please wait ...')
      //   .attr('disabled', 'disabled');
      $("#wKDAdataform").submit();
    } else {
      //if errorlist array is not empty, then slidedown error message
      var result = errorlist.join("\n");
      //alert(result);
      $('#errorp_wKDA').html(result);
      $("#errormsg_wKDA").fadeTo(2000, 500).slideUp(500, function() {
        $("#errormsg_wKDA").slideUp(500); //then slide it back up
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
  $("#minwKDA").inputFilter(function(value) {
    return /^\d*[.]?\d{0,2}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });
  //Can only input values from 0-1. If they try to type "2", it won't appear
  $("#edgewKDA").inputFilter(function(value) {
    return /^\d*[.]?\d{0,1}$/.test(value) && (value === "" || (parseInt(value) >= 0 && parseInt(value) <= 1));
  });


  /**********************************************************************************************
  Tutorial Button Script -- Append the tutorial to the form
  ***********************************************************************************************/

  var myTutButton_wKDA = document.getElementById("myTutButton_wKDA");
  var val_wKDA = 0; //We only want to append once, even if the user clicks on the tutorial button multiple times

  //begin function for when button is clicked-------------------------------------------------------------->
  myTutButton_wKDA.addEventListener("click", function() {

    //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
    var $this_wKDA = $(this);

    //If tutorial is already opened yet, then do this-------------------------------------------------------------->
    if ($this_wKDA.data('clicked')) {

      //hide the tutorial box
      $('.tutorialbox').hide();

      //remove the tutorial from the wKDA parameters table
      $('#wKDAparameterstable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });
      //remove the tutorial from the wKDA main table
      $('#wKDAmaintable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });

      //change tutorial clicked to false. So next time, we add the tutorial. 
      $this_wKDA.data('clicked', false);
      val_wKDA = val_wKDA - 1;
      //Change name of button to 'Click for Tutorial'
      $("#myTutButton_wKDA").html('<i class="icon-question1"></i>Click for Tutorial');

    }

    //If tutorial is not opened yet, then do this-------------------------------------------------------------->
    else {
      $this_wKDA.data('clicked', true);
      val_wKDA = val_wKDA + 1; //val counter to not duplicate prepend function


      if (val_wKDA == 1) //Only prepend the tutorial once
      {

        //Find the last column and then append a new column.
        //Since each row has different information, we have to indiviualize the tutorial cell

        $('#wKDAparameterstable').find('td[name="val1"]').eq(-1).after(`
                    <td name="tut">
                    <strong>Search Depth</strong>: used to define a candidate key driver's local network neighborhood by considering genes at a given distance or depth <br>
                        <strong>Options</strong>: 1/2/3. It indicates the maximum edge distance starting from each candidate key driver gene. <br>
                        <strong>Default Value</strong>: 1
                     </td>

                    `);

        $('#wKDAparameterstable').find('td[name="val2"]').eq(-1).after(`

                    <td name="tut">
                      <strong>Enter wKDA Edge Type</strong>: defines whether the directionality of edges is considered. <br>
                              <strong>Options</strong>:  Undirected and Directed. The former ignores directionality and the latter considers directionality by requiring the candidate key driver to be upstream of its local neighborhood genes (HEAD is upstream of TAIL). <br>
                              <strong>Default value</strong>: Undirected
                     </td>

                    `);
        $('#wKDAparameterstable').find('td[name="val3"]').eq(-1).after(`
                    <td name="tut">
                        <strong>Enter wKDA Min Overlap</strong>: Used as threshold for gene overlaps to group hubs as co-hubs. <br>
                            <strong>Options</strong>: Between 0-1. The higher the value, the more the local network neighborhood of hubs must overlap to be considered co-hubs. <br>
                            <strong>Default value</strong>: 0.33
                    </td>`);

        $('#wKDAparameterstable').find('td[name="val4"]').eq(-1).after(`
                    <td name="tut">
                      <strong>Enter wKDA Edge Factor</strong>: Used to weight edge weight info of network to the power of the value entered<br>
                      <strong>Options</strong>: Between 0-1. A power of zero would set all the edge weights to be equal (1). <br>
                      <strong>Default Value</strong>: 0 (do not consider weights)
                    </td>`);



        $('#wKDAparameterstable').find('th[name="val"]').eq(-1).after('<th name="tut">Comments</th>');

        $('#wKDAmaintable').find('th[name="val_wKDA"]').eq(-1).after('<th name="tut">Comments</th>');

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
  $('select.wKDA').on('change', function() {

    var select = $(this).find('option:selected').index(); //get the select index (i.e. 1 = upload)
    if (select != 1)
      $(this).parent().next().hide(); //hide the upload form

    if (select == 1)
      $(this).parent().next().show(); //show the upload form

    if (select > 1) //sample has been chosen
      $(this).parent().nextAll(".alert-wKDA").eq(0).html(successalert).hide().fadeIn(300);
    else if (select == 1) //upload has been chosen
      $(this).parent().nextAll(".alert-wKDA").eq(0).html(uploadalert).hide().fadeIn(300);
    else //nothing has been chosen
      $(this).parent().nextAll(".alert-wKDA").eq(0).empty();


  });

  //trigger the change at start of page
  //Helpful if a user comes back with the sessionID
  $('select.wKDA').each(function() {
    $(this).trigger('change');

  });

  /**********************************************************************************************
  Upload functions -- uses AJAX to send data to a PHP file and then upload the file onto the server if conditions are correct
  ***********************************************************************************************/

  //Network for wKDA 
  $(document).on('change', '#wKDAuploadInput', function() {
    var rmchoice = "<?php echo $rmchoice; ?>"; //get the pipeline choice

    var name = document.getElementById("wKDAuploadInput").files[0].name; //initialize name of file
    var form_data = new FormData(document.getElementById('wKDAdataform')); //intialize form_data variable
    var ext = name.split('.').pop().toLowerCase(); //initialize extension variable
    /*Initialize a filereader to check for filesize. 
      This can be done on the php side too, but this allow you to check before sending data to the PHP file. 
      Saves some time */
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("wKDAuploadInput").files[0]);
    var f = document.getElementById("wKDAuploadInput").files[0];
    var fsize = f.size || f.fileSize;
    if (fsize > 400000000) {
      alert("File Size is too big");
      /*Reset the upload form */
      var control = $("#wKDAuploadInput"); //get the id
      //control.replaceWith(control = control.clone().val('')); //replace with clone
    } else {
      /*Append the uploadinput as well as pipeline choice into form data */
      form_data.append("wKDAuploadInput", document.getElementById('wKDAuploadInput').files[0]);
      form_data.append("rmchoice", rmchoice);
      //Conduct an AJAX to send data to PHP file
      $.ajax({
        /*Creates a progress bar and update percentage uploaded */
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
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
        /*Send the POST data to PHP */
        url: "upload_wKDA.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        //beforeSend is self-explanatory. Creates a File uploading... label before sending the data
        beforeSend: function() {
          $('#wKDA_uploaded_file').html("<label class='text-success'>File Uploading...</label>");
        },
        //Success is not technically "a file upload success". It's a success of whether the PHP file was read.
        //the PHP files outputs a certain text and we can use that to figure out any error or success attempt   
        success: function(data) {
          //Reset progress bar to 0
          $('#wKDAprogresswidth').css('width', '0%').attr('aria-valuenow', 0);
          $('#wKDAprogressbar').hide();

          if (data == 111) //111 = success
          {
            var fullPath = $('#wKDAuploadInput').val();
            var filename = fullPath.replace(/^.*[\\\/]/, "");
            network = "./Resources/kda_temp/" + filename;
            $('#wKDAfilereturn').html(filename);
            $('#wKDA_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;"></i><strong>Upload successful!</strong></div>`);
          } else if (data == 0) // 0 = Column headers are incorrect
          {

            $('#wKDA_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong> Column headers are incorrect! <br> Please refer to the sample file format and reupload!</div>`);
            /*Reset the upload form */
            var control = $("#wKDAuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#wKDAfilereturn").empty();
          } else if (data == 10) // 10 = no data was detected after columns
          {
            $('#wKDA_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong> Data not detected! <br> Please refer to the sample file format and reupload!</div>`);
            /*Reset the upload form */
            var control = $("#wKDAuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#wKDAfilereturn").empty();
          } else //Any other errors that the PHP file outputs
          {

            $('#wKDA_uploaded_file').html(`<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error: </strong>` + data + `</div>`);
            /*Reset the upload form */
            var control = $("#wKDAuploadInput"); //get the id
            //control.replaceWith(control = control.clone().val('')); //replace with clone
            $("#wKDAfilereturn").empty();
          }


        }

      });
    }
  });

  /*These events are for when the upload form is clicked */
  var fileInput6 = document.getElementById("wKDAuploadInput"),
    button6 = document.getElementById("wKDAlabelname"),
    the_return6 = document.getElementById("wKDAfilereturn");

  button6.addEventListener("keydown", function(event) {
    if (event.keyCode == 13 || event.keyCode == 32) {
      fileInput6.focus();
    }
  });
  button6.addEventListener("click", function(event) {
    fileInput6.focus();
    return false;
  });

  fileInput6.addEventListener("change", function(event) {

    button6.innerHTML = "Select another file?";
  });

/*
  $('html,body').animate({
    scrollTop: $("#SSEAtoggle").offset().top
  }); //just a simple scroll to the bottom
  */

  function wKDAreview() //This function gets the review table for wKDA
  {

    var choice = <?php echo $rmchoice ?>;
    var string = "<?php echo $sessionID; ?>"; // Jess redefine string
    //sends data to moduleprogress.php
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
        GSETConvert: "none",
        rerun: "T"
      },
      success: function(data) {
        if (choice == 1) {
          $('#mywKDA_review').html(data);
        } else if (choice == 2) {
          $("#myMSEA2KDA_review").html(data);
        } else if (choice == 3) {
          $("#myMETA2KDA_review").html(data);
        }
      }
    });

    if (choice == 1) {
      $('#wKDAtab2').show();
      $('#wKDAtab2').click();
    } else if (choice == 2) {
      $("#MSEA2KDAtab2").show();
      $("#MSEA2KDAtab2").click();
    } else if (choice == 3) {
      $("#META2KDAtab2").show();
      $("#META2KDAtab2").click();
    }





  }


  ///////////////Start Submit Function (wKDA form) -- Function for clicking 'Click to review button'///////////////////////////////////
  $("#NetworkwKDA_form").change(function() {
    network = $("#NetworkwKDA_form").val();
  })
  $('#wKDAdataform').submit(function(e) {
    console.log($("#NetworkwKDA_form").val());
    
    if ($("#NetworkwKDA_form").val() !== "1") { // preloaded network
      NetConvert = "none";
    }
    else{
      console.log($("#NetConvert").val());
      NetConvert = $("#NetConvert").val();
    }

    kdadepth = $("#kda_depth").val();
    kdadirect = $("#kda_direct").val();
    minKDA = $("#minwKDA").val();
    edgewKDA = $("#edgewKDA").val();
    e.preventDefault();
    wKDAreview();

    // var form_data = new FormData(document.getElementById('wKDAdataform'));
    // form_data.append("sessionID", string);
    // form_data.append("rmchoice", "1");

    // $.ajax({
    //   'url': 'wKDA_parameters.php',
    //   'type': 'POST',
    //   'data': form_data,
    //   processData: false,
    //   contentType: false,
    //   'success': function(data) {
    //     $("#mywKDA").html(data);

    //   }
    // });



  });
</script>