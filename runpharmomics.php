<?php
include "functions.php";


if (isset($_GET['fromapp2']) ? $_GET['fromapp2'] : null) {
  $fromapp2 = $_GET['fromapp2'];
}
if (isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {
  $sessionID = $_GET['sessionID'];
  $fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
  if (!file_exists($fsession)) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
      // this is ajax request, do something
      echo "Session ID does not exist!";
      exit;
    } else {
      //this is not an ajax request
      header("Location: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?message=invalid");
      exit;
    }
  }
  // Create an array of the current session file
  $session = explode("\n", file_get_contents($fsession));
  //Create different array elements based on new line
  $pipe_arr = preg_split("/[\t]/", $session[0]);
  $pipeline = $pipe_arr[1];
  // added by JD
  $step_arr = preg_split("/[\t]/", $session[1]);
  $step = $step_arr[1];
  if ($step == 1.25 and substr($pipeline, 11, 4) == "App2") {
    $sig_arr = preg_split("/[\t]/", $session[2]);
    $signature = $sig_arr[1];
    $type_arr = preg_split("/[\t]/", $session[3]);
    $type = $type_arr[1];
  }
  $pharmomics_arr = preg_split("/[\t]/", $session[1]);
  $pharmomics_split = explode("|", $pharmomics_arr[1]);
  $pharmomics_path = $pharmomics_split[0];

  if (substr($pipeline, 0, 10) != "Pharmomics") {
    header("Location: /runmergeomics.php?sessionID=" . $sessionID);
  }



  //javascript controlling session reloading
  $json = json_encode(array(
    "Pharmomics_App1" => array(
      "1" => 'setTimeout(function() {
                $("#myAPP1").load("/app1_parameters.php?sessionID=' . $sessionID . '");
                $("#myAPP1_review").load("/app1_parametersgene.php?sessionID=' . $sessionID . '");
                $("#APP1togglet").show();
                $("#APP1tabheader").show()
              }, 400);',
    ),
    "Pharmomics_App2" => array(
      "1" => '$("#myAPP2").load("/app2_parameters.php?sessionID=' . $sessionID . '");
              setTimeout(function(){
                $("#APP2togglet").show();
                $("#APP2tabheader").show()
              },400);',
      "1.25" => '
                setTimeout(function(){
                  var form_data = new FormData(document.getElementById("app2dataform"));
                  form_data.append("sessionID", "' . $sessionID . '");
                  form_data.append("signature_select", "' . $signature . '");
                  form_data.append("type", "' . $type . '");
                  $.ajax({
                    "url": "run_app2.php",
                    "type": "POST",
                    "data": form_data,
                    processData: false,
                    contentType: false,
                    "success": function(data) {
                      $("#myAPP2_run").html(data);
                    }
                  });
                  $("#APP2tab2").show();
                  $("#APP2togglet").show();
                  $("#APP2tabheader").show()
                  $("#APP2tab2").click();         
                }, 800);',



    ),
    "Pharmomics_App3" => array(
      "1" => '$("#myAPP3").load("/app3_parameters.php?sessionID=' . $sessionID . '");
            setTimeout(function(){
              $("#APP3togglet").show();
              $("#APP3tabheader").show();
            },400);',
      "1.25" => '
        setTimeout(function(){
          var form_data = new FormData(document.getElementById("app3dataform"));
          form_data.append("sessionID", "' . $sessionID . '");
          $.ajax({
            "url": "run_app3.php",
            "type": "POST",
            "data": form_data,
            processData: false,
            contentType: false,
            "success": function(data) {
              $("#myAPP3_run").html(data);
            }
          });
          $("#APP3tab2").show();
          $("#APP3togglet").show();
          $("#APP3tabheader").show()
          $("#APP3tab2").click();
         
        },500);
    
      
      '





    ),
  ));
  $url = json_decode($json, true);
  $x = 1;
  $write_url = NULL;

  if ($pipeline == "Pharmomics_App1")
    $write_url .= "startAPP1();\r\n";
  else if ($pipeline == "Pharmomics_App2")
    $write_url .= "startAPP2();\r\n";
  else if ($pipeline == "Pharmomics_App3")
    $write_url .= "startAPP3();\r\n";


  while ($x <= $pharmomics_path) {
    $write_url .= $url[$pipeline][strval($x)] . "\r\n";
    $x = $x + 0.25;
  }
  $furlOut = "./Data/Pipeline/Resources/session/$sessionID" . "pharmomicsurl.js";
  $fp = fopen($furlOut, 'w');
  fwrite($fp, $write_url);
  fclose($fp);
  chmod($furlOut, 0775);
}


$login_button = '';
$scriptUri = "http://" . $_SERVER["HTTP_HOST"] . "/runpharmomics.php?fromapp2=true";
#debug_to_console($scriptUri);



#$google_client->setRedirectUri($scriptUri);

//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
// if (isset($_GET["code"])) {
//   //It will Attempt to exchange a code for an valid authentication token.
//   #  $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
//   $google_client->authenticate($_GET['code']);
//   $_SESSION['access_token'] = $google_client->getAccessToken();
//   //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
//   if (!isset($token['error'])) {
//     //Set the access token used for requests
//     # $google_client->setAccessToken($token['access_token']);

//     //Store "access_token" value in $_SESSION variable for future use.
//     #  $_SESSION['access_token'] = $token['access_token'];

//     //Create Object of Google Service OAuth 2 class
//     $google_service = new Google_Service_Oauth2($google_client);

//     //Get user profile data from google
//     $data = $google_service->userinfo->get();
//     //debug_to_console($data);
//     //Below you can find Get profile data and store into $_SESSION variable
//     if (!empty($data['given_name'])) {
//       $_SESSION['user_first_name'] = $data['given_name'];
//     }

//     if (!empty($data['family_name'])) {
//       $_SESSION['user_last_name'] = $data['family_name'];
//     }

//     if (!empty($data['email'])) {
//       $_SESSION['user_email_address'] = $data['email'];
//     }

//     if (!empty($data['gender'])) {
//       $_SESSION['user_gender'] = $data['gender'];
//     }

//     if (!empty($data['picture'])) {
//       $_SESSION['user_image'] = $data['picture'];
//     }
//   }
// }

?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.css" />
<style>
  .btn-secondary {
    color: #fff !important;
    background-color: steelblue !important;
    border-color: steelblue !important;
  }

  .btn:focus {
    outline: none !important;
    box-shadow: none !important;
  }

  #sidebar.active .custom-menu {
    margin-right: -30px !important;
  }
</style>

<!-- Includes google analytic tracking -->
<?php include_once("analyticstracking.php") ?>

<!-- Includes all the font/styling/js sheets -->
<?php include_once("head_pharmomics.inc") ?>


<!-- START body of pipeline ----------------------------------------------------------------------------->

<body class="stretched">

  <!-- Include the Run Mergeomics header ------------------------------------------------------------------>
  <?php include_once("headersecondary_pharmomics.inc") ?>



  <!-- Page title block ---------------------------------------------------------------------------------->
  <section id="page-title">

    <div class="margin_rm">
      <div class="container clearfix" style="text-align: center;">
        <h2>PharmOmics Pipeline</h2>

      </div>
    </div>

  </section>




  <!---------------- Pipeline starting points: 3 animated buttons ------------------------------------------------------------>
  <section id="content" style="margin-bottom: 0px;">


    <div class="content-wrap" style="padding: 20px 0 0 0;">

      <nav id="sidebar">
        <div class="custom-menu">
          <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i id="sidebar_icon" class="icon-bars"></i>
            <span class="sr-only">Toggle Menu</span>
          </button>
        </div>
        <h1><a href="#" id="session_id" class="session" onclick="copy(this)">New Job</a></h1>
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="home.php"><span class="fa fa-home mr-3"></span> Homepage</a>
          </li>
          <li>
            <a data-toggle="modal" data-target="#sessionIDmodal" href="#sessionIDmodal" tooltip="Enter existing session"><span class="fa fa-user mr-3"></span> Input Session ID <i class="icon-line2-question"></i></a>
            <!--<div class="collapse" id="session_enter" data-parent="#sidebar">
                    <a href="#" class="list-group-item" data-parent="#session_enter" style="background-color: #587d90;">Test</a>
                </div>-->
          </li>
          <li>
            <a href="/runpharmomics.php"><span class="fa fa-sticky-note mr-3"></span> Run New Job <i class="icon-line2-question"></i></a>
          </li>
          <!-- <li>
            <a href="#" tooltip="Download files of current session"><span class="fa fa-sticky-note mr-3"></span> Download files <i class="icon-line2-question"></i></a>
          </li>

          <li>
            <a data-toggle="modal" data-target="#PIPELINEmap" href="#PIPELINEmap" tooltip="Pipeline map of session"><span class="fa fa-sticky-note mr-3"></span> Pipeline Map <i class="icon-line2-question"></i></a>
          </li> -->
          <p>Pharmomics is being actively developed by the Yang Lab in the Department of Integrative Biology and Physiology at UCLA. <br><br>

            Chen Y., Diamante G., Ding J., Nghiem T., Yang J., Ha S., Cohn P., Arneson D., Blencowe M., Garcia J., Zaghari N., Patel P., Yang X. (2022). PharmOmics: A species- and tissue-specific drug signature database and gene-network-based drug repositioning tool. iScience, 25(4):104052. doi: 10.1016/j.isci.2022.104052.
          </p>


          <p> </p>

        </ul>

      </nav>
      <!-- <?php #include_once("announcement_modal.php") ?> -->
      <div class="margin_rm">

        <div class="container clearfix" id="myContainer">


          <div class="row clearfix">

            <!--------------------- APP1 buttons ------------------------------------------------------------>

            <div class="col-lg-4 center bottommargin" name="APP1" id="APP1container">

              <div class="button-wrapper">
                <div id="APP1outline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
                  <a href="#" class="runp button-inner" id="APP1button"> Signature/Pathway <br>Overview</a>
                </div>
                <a href="#" class="button button-rounded button-reveal button-large button-teal" id="APP1start" style="display: none;"><i class="icon-play"></i><span>Run Pipeline</span></a>
              </div>

              <div class="toggle toggle-border" style="display:none;" id="APP1toggle">
                <div class="togglet" id="APP1togglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Signature/Pathway Overview</div>
                </div>

                <div class="tabs tabs-bb togglec" id="APP1tabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="APP1tab1" href="#myAPP1">Drug Overview</a></li>
                    <li><a id="APP1tab2" href="#myAPP1_review">Gene Overview</a></li>
                  </ul>

                  <div class="tab-container" style="overflow-x: auto;">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myAPP1"></div> <!-- Start tab content for APP1 -->

                    <div class="togglec" id="myAPP1_review"></div>

                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End APP1 toggle-->



            </div> <!-- End APP1 container -->





            <!----------------- (APP2) button ------------------------------------------------------------>

            <div class="col-lg-4 center bottommargin" name="APP2" id="APP2container">
              <div class="button-wrapper">
                <div id="APP2outline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
                  <a href="#" class="runp button-inner" id="APP2button">Network Drug Repositioning<br>and ADR analysis</a>
                </div>
                <a href="#" class="button button-rounded button-reveal button-large button-teal" id="APP2start" style="display: none;"><i class="icon-play"></i><span>Run Pipeline</span></a>
              </div>

              <div class="toggle toggle-border" id="APP2toggle" style="display: none;">
                <!-- Start first toggle/step in APP2 -->
                <div class="togglet" id="APP2togglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Network Drug Repositioning and ADR analysis</div>
                </div>


                <div class="tabs tabs-bb togglec" id="APP2tabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="APP2tab1" href="#myAPP2">Network and Genes Input</a></li>
                    <li><a id="APP2tab2" href="#myAPP2_run" style="display: none;">Review Files</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->

                    <div class="togglec" id="myAPP2"></div>

                    <div class="togglec" id="myAPP2_run"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End first toggle/step in APP2 -->


            </div>
            <!-----------------End (APP2) button ------------------------------------------------------------>


            <!----------------Start of APP3 button ------------------------------------------------------------>


            <div class="col-lg-4 center bottommargin" name="APP3" id="APP3container">

              <div class="button-wrapper">
                <div id="APP3outline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
                  <a href="#" class="runp button-inner" id="APP3button">Overlap Drug Repositioning<br>and ADR analysis</a>
                </div>
                <a href="#" class="button button-rounded button-reveal button-large button-teal" id="APP3start" style="display: none;"><i class="icon-play"></i><span>Run Pipeline</span></a>
              </div>

              <div class="toggle toggle-border" id="APP3toggle" style="display: none;">
                <!-- Start first toggle/step in APP3 -->
                <div class="togglet" id="APP3togglet"><i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i>
                  <div class="capital">Overlap Drug Repositioning and ADR analysis</div>
                </div>


                <div class="tabs tabs-bb togglec" id="APP3tabheader">
                  <!-- Start tab headers -->

                  <ul class="tab-nav clearfix">
                    <li><a id="APP3tab1" href="#myAPP3">Input Genes</a></li>
                    <li><a id="APP3tab2" href="#myAPP3_run" style="display: none;">Results</a></li>
                  </ul>

                  <div class="tab-container">
                    <!-- Start TAB container -->


                    <div class="togglec" id="myAPP3"></div>
                    <div class="togglec" id="myAPP3_run"></div>


                  </div> <!-- End tab container -->
                </div> <!-- End tab header -->

              </div> <!-- End first toggle/step in APP3 -->



            </div>
            <!-----------------End (APP3) button ------------------------------------------------------------>



          </div> <!-- End row clearfix -->

          <div id="flowchart_div" class="row clearfix">


            <div class="col-full center" data-animate="pulse">
              <img style="width: 80%;height: auto;" id="flowchart" src="include/pharmomics/Pharmomics_overview.png" alt="Overview">
              <a id="APP1bubble" class="draw-border" data-toggle="modal" data-target="#APP1modal" href="#APP1modal"></a>
              <a id="APP2bubble" class="draw-border" data-toggle="modal" data-target="#APP2modal" href="#APP2modal"></a>
              <a id="APP3bubble" class="draw-border" data-toggle="modal" data-target="#APP3modal" href="#APP3modal"></a>





            </div>


          </div>
          <div class="row clearfix" style="margin-top:2%">
          	<p style="font-size: 18px">If you use the PharmOmics web server in published research, please be sure to cite the manuscript:<br>
          	Chen Y., Diamante G., Ding J., Nghiem T., Yang J., Ha S., Cohn P., Arneson D., Blencowe M., Garcia J., Zaghari N., Patel P., Yang X. (2022). PharmOmics: A species- and tissue-specific drug signature database and gene-network-based drug repositioning tool. iScience, 25(4):104052. doi: 10.1016/j.isci.2022.104052.</p>
          </div>


        </div> <!-- End container clearfix -->
      </div>
      <!--Margin left div--->
    </div><!-- End content-wrap -->


  </section> <!-- End button section -->

  <!----------------------------------MODAL ------------------------------------------>




  <div id="sessionIDmodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 45px;">Enter Session ID</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body" style="text-align: center;">
            <!-- <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
                        <div class="alert alert-warning" style="margin: 0 auto; width: 80%;">
                          <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i><strong>Note:</strong> SessionIDs/Progress will be released early next week. We apologize for any inconvenience this may cause. For now, please use the email option to have your results emailed to you if you need to be away from your computer.
                        </div>
                      </div> -->
            <p class="instructiontext" style="margin: 0;">Session IDs are only valid for 24 hours. <br>If 24 hours has already passed, please start a new job.</p>
            <br>
            <form action="runpharmomics.php" name="sessionform" id="mySessionform">
              <input type="text" name="sessionID" id="mySessionID" />
            </form>
            <div id="myIDpreload"></div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="submitID()">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="loadIDmodal" class="modal fade bs-example-modal-lg" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" id="sessionIDtitle" style="font-size: 45px;">Session ID</h4>

          </div>
          <div class="modal-body" id="loadIDbody" style="text-align: center;">
          </div>

        </div>
      </div>
    </div>
  </div>

  <div id="APP1modal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">PharmOmics</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pharmomics/App1.png">
            <br>
            <h4 class="instructiontext">PharmOmics is a comprehensive drug knowledgebase comprised of genomic footprints derived from meta-analysis of microarray and RNA sequencing data relevant to drugs from tissues and cells derived from human, mouse, and rat samples in GEO, ArrayExpress, TG-GATEs and drugMatrix data repositories.</h4>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="APP2modal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">PharmOmics</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pharmomics/App2.png">
            <br>
            <p style="padding: 0 5%; font-size: 20px;">This tool ranks drugs based on the connectivity of drug signatures to input genes as defined by a gene network model. distance(I,D) is a network proximity measurement between drug (D) and input genes (I).</p>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="APP3modal" class="modal fade bs-example-modal-xlg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="font-size: 35px;margin-right: -30px;">PharmOmics</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <img src="include/pictures/App3.png">
            <br>
            <p style="padding: 0 5%; font-size: 20px;">The Jaccard score is used as the direct overlap measurement between input genes and disease genes (unsigned for single list of genes, signed for up- and downregulated genes). The gene overlap fold enrichment, odds ratio, Fisher's exact test p-value, and within-species rank is also calculated.</p>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script src="include/js/functions.js?20200803"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.js"></script>
  <script type="text/javascript">
    /*Sidebar Jquery Event handlers *

1) Change the container width and sidebar active state based on window side

*/    
    $(document).ready(function() {
      if ($(window).width() < 992) {
        $('.container').addClass('no_sidebar');
        $('.margin_rm').addClass('no_margin');
      }
    });

    $(window).on('resize', function() {
      if ($(window).width() > 992 && $("#sidebar.active")[0]) {
        $('.container').addClass('no_sidebar');
        $('.margin_rm').addClass('no_margin');
      } else if ($(window).width() < 992 && $("#sidebar.active")[0]) {
        $('.container').removeClass('no_sidebar');
        $('.margin_rm').removeClass('no_margin');
      } else if ($(window).width() < 992 && $("#sidebar")[0]) {
        $('.container').addClass('no_sidebar');
        $('.margin_rm').addClass('no_margin');
      } else {
        $('.container').removeClass('no_sidebar');
        $('.margin_rm').removeClass('no_margin');
      }

    });

    $('#sidebarCollapse').on('click', function() {

      if ($("#sidebar.active")[0]) {
        $('#sidebar').toggleClass('active');
        $('.container').toggleClass('no_sidebar');
        $('.margin_rm').toggleClass('no_margin');
      } else {
        $('#sidebar').toggleClass('active');
        $('.container').toggleClass('no_sidebar');
        $('.margin_rm').toggleClass('no_margin');
      }


    });

    $(document).scroll(function() {
      if ($(window).scrollTop() > 100 && $(window).width() < 992) {

        $("#sidebar").css("margin-top", "-206px");

      } else if ($(window).scrollTop() < 100 && $(window).width() < 992) {

        $("#sidebar").css("margin-top", "-106px");

      }
    });


    /*End Sidebar Jquery Event Handler*/



    function submitID() {


      $("#myIDpreload").html(`<p class='instructiontext' style='font-size: 15px; margin:0;padding:0px'>Loading session...</p><br><img src='include/pictures/ajax-loader.gif' />`);
      $("#mySessionform").submit();
      return false;

    }

    //Needs to combine all 4 functions into 1....
    function startAPP1() {
      //Remove animation from container---------------------------------------------------------->
      $("#APP1container").removeAttr("data-animate");
      $("#APP1container").removeClass("pulse animated");

      //Fade out and up the other two buttons------------------------------------------------------->
      $("#APP2container").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#APP3container").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#APP1start").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#flowchart").animate({
        height: 0,
        opacity: 0
      }, 'slow');

      //Wait about until other buttons fade out and then change size of APP1 button------------------------------------------------------->
      window.setTimeout(function() {
        $("#APP1container").addClass("col_full").removeClass("col-lg-4 center bottommargin");
        $("#APP1outline").removeClass();
        //$("p").css({"background-color": "yellow", "font-size": "200%"});
        $("#APP1outline").css({
          "height": "100px",
          "margin": "10px 0"
        });
        $("#APP1button:first-child").removeClass();
        $("#APP1button:first-child").addClass("button button-3d button-rounded button runp runm_pipeline noHover");
        $("#APP1button").unbind("click").click(function() {});

        $("#APP1container").hide().show("slide", {
          direction: "up"
        }, 500);
        $("#APP1toggle").show();
        //Remove unwanted divs from DOM---------------------------------------------------------------------------------------------->
        $("#APP2container").remove();
        $("#APP3container").remove();
        $("#APP3start").remove();
        //$("#APP1bubble, #APP2bubble, #APP3bubble, #KDAbubble, #DRUGbubble, #NETWORKbubble, #JACCARDbubble").hide();


      }, 800); //End of setTimeout function
    }


    function startAPP2() {
      //Remove animation from container---------------------------------------------------------->
      $("#APP2container").removeAttr("data-animate");
      $("#APP2container").removeClass("pulse animated");

      //Fade out and up the other two buttons------------------------------------------------------->
      $("#APP1container").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#APP3container").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#APP2start").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#flowchart").animate({
        height: 0,
        opacity: 0
      }, 'slow');


      //Wait about until other buttons fade out and then change size of APP1 button------------------------------------------------------->
      window.setTimeout(function() {
        $("#APP2container").addClass("col_full").removeClass("col-lg-4 center bottommargin");
        $("#APP2outline").removeClass();
        $("#APP2outline").css({
          "height": "100px",
          "margin": "10px 0"
        });

        $("#APP2button:first-child").removeClass();
        $("#APP2button:first-child").addClass("button button-3d button-rounded button runp runm_pipeline noHover");

        $("#APP2container").hide().show("slide", {
          direction: "up"
        }, 500);
        $("#APP2toggle").show();

        //Remove unwanted divs from DOM---------------------------------------------------------------------------------------------->
        $("#APP1container").remove();
        $("#APP3container").remove();
        $("#APP2start").remove();
        //$("#APP1bubble, #APP2bubble, #APP3bubble, #KDAbubble, #DRUGbubble, #NETWORKbubble, #JACCARDbubble").hide();

      }, 800); //End of setTimeout function
    }


    function startAPP3() {
      //Remove animation from container---------------------------------------------------------->
      $("#APP3container").removeAttr("data-animate");
      $("#APP3container").removeClass("pulse animated");

      //Fade out and up the other two buttons------------------------------------------------------->
      $("#APP1container").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#APP2container").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#APP3start").animate({
        height: 0,
        opacity: 0
      }, 'slow');
      $("#flowchart").animate({
        height: 0,
        opacity: 0
      }, 'slow');

      //Wait about until other buttons fade out and then change size of APP1 button------------------------------------------------------->
      window.setTimeout(function() {
        $("#APP3container").addClass("col_full").removeClass("col-lg-4 center bottommargin");
        $("#APP3outline").removeClass();
        $("#APP3outline").css({
          "height": "100px",
          "margin": "10px 0"
        });

        $("#APP3button:first-child").removeClass();
        $("#APP3button:first-child").addClass("button button-3d button-rounded button runp runm_pipeline noHover");

        $("#APP3container").hide().show("slide", {
          direction: "up"
        }, 500);
        $("#APP3toggle").show();

        //Remove unwanted divs from DOM---------------------------------------------------------------------------------------------->
        $("#APP1container").remove();
        $("#APP2container").remove();
        $("#APP3start").remove();
        //$("#APP1bubble, #APP2bubble, #APP3bubble, #KDAbubble, #DRUGbubble, #NETWORKbubble, #JACCARDbubble").hide();

      }, 800); //End of setTimeout function
    }



    //Click the APP1 Enrichment program [Will only run once]------------------------------------------------------------------------>
    $("#APP1start").one("click", function() {
      startAPP1();
      //Wait until APP1 button appears and then load APP1 pipeline----------------------------------------------------->
      window.setTimeout(function() {
        $("#myAPP1").load('/app1_parameters.php');
        $("#myAPP1_review").load('/app1_parametersgene.php');
        $('#APP1togglet').click();
      }, 400);
    });

    $("#APP2start").one("click", function() {
      startAPP2();
      //Wait until APP1 button appears and then load APP1 pipeline----------------------------------------------------->
      window.setTimeout(function() {
        $("#myAPP2").load('/app2_parameters.php');
        $('#APP2togglet').click();

      }, 400);

    });

    $("#APP3start").one("click", function() {
      startAPP3();
      //Wait until APP1 button appears and then load APP1 pipeline----------------------------------------------------->
      window.setTimeout(function() {
        $("#myAPP3").load('/app3_parameters.php');
        $('#APP3togglet').click();
      }, 400);

    });

    function preload(arrayOfImages) {
      $(arrayOfImages).each(function() {
        $('<img />').attr('src', this).appendTo('body').css('display', 'none');
      });
    }

    // Usage:

    preload([
      'include/pictures/Pharmomics_overview.png'
    ]);

    var img0 = new Image();
    img0.src = "include/pharmomics/Pharmomics_overview.png";
    var APP10 = new Image();
    APP10.src = "include/pharmomics/Overview_app1.png";
    //APP10.src = "include/pharmomics/Pharmomics_overview.png";
    var APP20 = new Image();
    APP20.src = "include/pharmomics/Overview_app2.png";
    //APP20.src = "include/pharmomics/Pharmomics_overview.png";
    var APP30 = new Image();
    APP30.src = "include/pharmomics/Overview_app3.png";
    //APP30.src = "include/pharmomics/Pharmomics_overview.png";



    $(".button-wrapper").click(function() {
      var $this = $(this).find('.button-inner');
      var name_type = $this.closest(".col-lg-4.center.bottommargin").attr('name');

      if ($this.hasClass("runm_active"))
      //Keep track if button is clicked-------------------------------------------------------------->
      {
        $this.data('clicked', true);
      } else {
        $this.data('clicked', false);
      }
      $('.runp.button-inner').removeClass('runm_active');
      $('.button.button-rounded.button-reveal.button-large.button-teal').hide();


      if ($this.data('clicked')) //if it's already been clicked, then do this
      {
        $this.parent().nextAll('.button.button-rounded.button-reveal.button-large.button-teal').eq(0).hide();
        $this.removeClass('runm_active');
        $("#flowchart").attr("src", img0.src);
        $this.data('clicked', false);

      } else //if it hasn't been clicked, then do this
      {
        $this.parent().nextAll('.button.button-rounded.button-reveal.button-large.button-teal').eq(0).show();
        $this.addClass('runm_active');
        if (name_type == "APP1")
          $("#flowchart").attr("src", APP10.src);
        else if (name_type == "APP2")
          $("#flowchart").attr("src", APP20.src);
        else if (name_type == "APP3")
          $("#flowchart").attr("src", APP30.src);

        $this.data('clicked', true);

      }


    });
  </script>
  <script src="include/js/plugins.js"></script>
  <!-- Footer Scripts IMPORTANT!
  ============================================= -->
  <script src="include/js/functions.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.5.1/gsap.min.js"></script>
  <script type="text/javascript">
  console.log("check");
  </script>
  <?php


  if (isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {


  ?>

    <script type="text/javascript">
      console.log("hh");
      var string = "<?php echo $sessionID; ?>";
      var url = "/Data/Pipeline/Resources/session/" + string + "pharmomicsurl.js";
      $('#content').css('opacity', '0%');
      $('.modal-backdrop.fade').css({
        "opacity": "100%",
        "background-color": "white"
      });
      $('#loadIDmodal').modal('toggle');
      $("#sessionIDtitle").html("Session ID: " + string);
      $("#loadIDbody").html("<p class='instructiontext' style='font-size: 25px; margin:10px 0 0 0;padding:0px'>Loading session<span class = 'dots'>...</span><br>This may take a few seconds</p>");


      //Wait until GWAS button appears and then load MDF pipeline-----------------------------------------------------

      $.getScript(url, function() {
        $(document).ajaxStop(function() {
          setTimeout(function() {
            $('.modal-backdrop.fade').css('opacity', '0%');
            $('#content').css('opacity', '100%');
            $('#loadIDmodal').modal('hide');
          }, 1000);
        });

      });
    </script>


  <?php
  } else {
  ?>
    <script type="text/javascript">
      var n = localStorage.getItem('on_load_session');
      console.log("haha");
      if (n != null) {
        $(window).on('load', function() {
            // Run code
            var result = confirm("Would you like to resume where you left off? \nSession ID: " + n + "\n(Note: Your session is available for 48 hrs)");

            if (result) {
              $(location).attr('href', '/runpharmomics.php?sessionID=' + n);
              localStorage.clear();
            } else {
              localStorage.clear();
            }
          });
      }
      var fromapp2 = "<?php echo $fromapp2 ?>";
      if (fromapp2 == "true") {
        $(location).attr('href', '/runpharmomics.php?sessionID=' + n);
      } else {
        //add data we are interested in tracking to an array
        var values = new Array();
        var oneday = new Date();
        oneday.setHours(oneday.getHours() + 24); //one day from now
        values.push(n);
        values.push(oneday);
        try {
          localStorage.setItem(0, values.join(";"));
        } catch (e) {}

        //check if past expiration date
        var values = localStorage.getItem(0).split(";");

        if (values[1] < new Date()) {
          localStorage.clear();
        }

        
      }
    </script>
  <?php
  }
  ?>

  <!-- Go To Top button
  ============================================= -->
  <div id="gotoTop" class="icon-angle-up"></div>

  <!-- External JavaScripts IMPORTANT!
  ============================================= -->






</body>

</html>