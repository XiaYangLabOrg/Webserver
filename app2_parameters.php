<?php
include "functions.php";
$total_quota = 100;
$individual_user_quota = 5;
if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  //If sessionID is received from post call, it means its from runpharmomics.php
  $sessionID = $_POST['sessionID'];
  $fromjobsubmission = "T";
} else if (isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {
  //If sessionID is received from post call, it means its from session loading
  $sessionID = $_GET['sessionID'];
} else {
  $sessionID = generateRandomString(10);
}
//include('mysql_conn.php');
$login_button = '';
$scriptUri = "http://" . $_SERVER["HTTP_HOST"] . "/runpharmomics.php?fromapp2=true";


// $google_client->setRedirectUri($scriptUri);

// //This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
// if (isset($_GET["code"])) {
//   //It will Attempt to exchange a code for an valid authentication token.
//   #  $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
//   $google_client->authenticate($_GET['code']);
//   $_SESSION['access_token'] = $google_client->getAccessToken();

//   //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
//   if (!isset($token['error'])) {
//     //Set the access token used for requests
//     #    $google_client->setAccessToken($token['access_token']);

//     //Store "access_token" value in $_SESSION variable for future use.
//     #    $_SESSION['access_token'] = $token['access_token'];

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
// $user_name = $_SESSION['user_first_name'] . " " . $_SESSION['user_last_name'];
// $user_email = $_SESSION['user_email_address'];
// //This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
// if (!isset($_SESSION['access_token'])) {
//   //Create a URL to obtain user authorization
//   //$login_button = '<a href="' . $google_client->createAuthUrl() . '"><img src="sign-in-with-google.png" /></a>';
//   $login_button = '<a href="' . $google_client->createAuthUrl() . '" class="google btn"><i class="fa fa-google fa-fw"></i> Login with Google+</a>';
//   $islogin = False;
// } else {
//   $islogin = True;
// }
//Always true until we sort out https - 11.24.2021- DAN
$islogin = True;



//MYSQL get quota
// $start_date = date("Y-m-d");
// $stop_date = date('Y-m-d', strtotime($stop_date . ' +1 day'));
// #$sql = "SELECT uid, sessionID, user_email, user_name, cmds, date_submitted  FROM hoffman2.hoffman2_logs where date_submitted>=\'" . $start_date . "\' and date_submitted <\'" . $stop_date . "\';";

// $sql_toal_quota = "SELECT count(*) as count  FROM hoffman2_logs where date_submitted >= \"" . $start_date . "\";";
// $sql_user_quota = "SELECT count(*) as count  FROM hoffman2_logs where date_submitted >= \"" . $start_date . "\" and user_email=\"" . $user_email . "\";";
// $result_total_quota = $conn->query($sql_toal_quota);
// $result_user_qutoa = $conn->query($sql_user_quota);
// if (mysqli_num_rows($result_total_quota) > 0) {
//   while ($row = mysqli_fetch_assoc($result_total_quota)) {
//     debug_to_console('dailyUsage: ' . $row['count']);
//     $dailyUsage = $row['count'];
//   }
// } else {
//   debug_to_console("0 results");
// }

// if (mysqli_num_rows($result_user_qutoa) > 0) {
//   while ($row = mysqli_fetch_assoc($result_user_qutoa)) {
//     debug_to_console('userUsage: ' . $row['count']);
//     $userUsage = $row['count'];
//   }
// } else {
//   debug_to_console("0 results");
// }
// mysqli_close($conn);



$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$session_write = NULL;

//initiate session file
if (!file_exists($fsession)) {
  $dose_seg_runs_file = "./Data/Pipeline/Resources/shinyapp2_temp/Dose_seg_runs" . date("Y.m.d") . ".txt";
  if (file_exists($dose_seg_runs_file)) {
    $linecount = 0;
    $handle = fopen($dose_seg_runs_file, "r");
    while (!feof($handle)) {
      $line = fgets($handle);
      $linecount++;
    }
    fclose($handle);
    if ($linecount > 50) {
      $reached_doseseg_limit = "yes";
    } else {
      $reached_doseseg_limit = "no";
    }
  } else {
    $reached_doseseg_limit = "no";
  }



  // $reached_doseseg_limit = "no";
  // if ($dailyUsage > $total_quota || $userUsage > $individual_user_quota) {
  //   $reached_doseseg_limit = "yes";
  // }


  $sessionfile = fopen($fsession, "w");
  $session_write .= "Pipeline:" . "\t" . "Pharmomics_App2" . "\n";
  $session_write .= "Pharmomics_Path:" . "\t" . "1.0" . "\n";
  fwrite($sessionfile, $session_write);
  fclose($sessionfile);
  chmod($fsession, 0755);
}

// $user_usage_count = "./User_usage/" . $user_email . "." . date("Y.m.d") . ".json";
// $userUsage = 0;

// if (file_exists($user_usage_count)) {
//   $jsondata = json_decode(file_get_contents($user_usage_count));
//   $userUsage = count($jsondata);
//   if ($userUsage >= $individual_user_quota) {
//     $reached_doseseg_limit = "yes";
//   }
// }
?>




<style type="text/css">
  textarea {
    width: 50%;
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
  }

  .buttonp:active,
  .button:active {
    top: 2px;
    box-shadow: none;
  }


  .app2 {
    width: 65% !important;
    margin: 0 auto !important;
  }

  i {
    padding-right: 8px;
  }

  .samplefile th,
  .samplefile td {
    padding: 0.25rem !important;
    height: 30px !important;
  }


  * {
    box-sizing: border-box;
  }



  /* style inputs and link buttons */
  input,
  .btn {
    /*width: 100%;*/
    padding: 12px;
    border: none;
    border-radius: 4px;
    margin: 5px 0;
    opacity: 0.85;
    display: inline-block;
    font-size: 17px;
    line-height: 20px;
    text-decoration: none;
    /* remove underline from anchors */
  }

  /* input:hover,
  .btn:hover {
    opacity: 1;
  } */

  /* add appropriate colors to fb, twitter and google buttons */
  .fb {
    background-color: #3B5998;
    color: white;
  }

  .twitter {
    background-color: #55ACEE;
    color: white;
  }

  .google {
    background-color: #dd4b39;
    color: white;
  }

  /* style the submit button */
  input[type=submit] {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
  }

  input[type=submit]:hover {
    background-color: #45a049;
  }

  /* Two-column layout */
  .col {
    float: left;
    width: 50%;
    margin: auto;
    padding: 0 50px;
    margin-top: 6px;
  }

  /* Clear floats after the columns */
  .row:after {
    content: "";
    display: table;
    clear: both;
  }

  /* vertical line */
  .vl {
    position: absolute;
    left: 50%;
    transform: translate(-50%);
    border: 2px solid #ddd;
    height: 175px;
  }

  /* text inside the vertical line */
  .vl-innertext {
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    border-radius: 50%;
    padding: 8px 10px;
  }

  /* hide some text on medium and large screens */
  .hide-md-lg {
    display: none;
  }

  /* bottom container
  .bottom-container {
    text-align: center;
    background-color: #666;
    border-radius: 0px 0px 4px 4px;
  } */

  /* Responsive layout - when the screen is less than 650px wide, make the two columns stack on top of each other instead of next to each other */
  @media screen and (max-width: 650px) {
    .col {
      width: 100%;
      margin-top: 0;
    }

    /* hide the vertical line */
    .vl {
      display: none;
    }

    /* show the hidden text on small screens */
    .hide-md-lg {
      display: block;
      text-align: center;
    }
  }

  .blur {
    -webkit-filter: blur(5px);
    -moz-filter: blur(5px);
    -o-filter: blur(5px);
    -ms-filter: blur(5px);
    filter: blur(5px);
    background-color: #ccc;
  }
</style>

<div id="errormsg_app2" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_app2" style="white-space: pre;"></p>
</div>

<div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">

  <!-- Grid container for MDF=====================================================-->
  <div class="gridcontainer">

    <!-- Description ===================================================== -->
    <h4 class="instructiontext">
      This part of the pipeline performs network based drug repositioning (PharmOmics) <br> based on user input genes and provides hepatotoxicity network scoring of user input genes.
    </h4>


    <!--Start app2 Tutorial --------------------------------------->
    <div style="text-align: center;">
      <button class="button button-3d button-rounded button" id="myTutButton_app2"><i class="icon-question1"></i>Click for tutorial</button>
    </div>

    <div class='tutorialbox' style="display: none;">
    </div>
    <!--End app2Tutorial --------------------------------------->



  </div>
  <!--End of gridcontainer ----->





  <!-- Description ============Start table========================================= -->
  <form enctype="multipart/form-data" action="app2_parameters.php" name="select" id="app2dataform">
    <div class="table-responsive" style="overflow: visible;">
      <!--Make table responsive--->
      <table class="table table-bordered" style="text-align: center;" ; id="app2networktable">
        <thead>
          <tr>
            <!--First row of table------------Column Headers------------------------------>
            <th name="val_app2">Drug Repositioning Analysis</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <!--Second row of table------------------------------------------>
            <td name="val1_app2">
              <h4 class="instructiontext" style="font-size: 16px;padding-bottom: 10px;">Select signature type to query
                <a style="color:#5f5e58;" data-toggle="modal" data-target="#addSignaturemodal" href="#addSignaturemodal">
                  <i class="icon-info-sign i-addmap" style="position: relative;"></i>
                </a>
              </h4>
              <div class="selectholder app2">
                <select class="btn dropdown-toggle btn-light app2" name="signature_select" size="1" id="mySigType">
                  <option value="1" selected>Meta</option>
                  <option value="2">Dose/time segregated - top 500 genes per signature</option>
                  <option value="3">Dose/time segregated all genes per signature</option>
                </select>
              </div>
              <div style="display:none;" id="login_panel">
                <div class="alert alert-warning" style="margin: 0 auto; margin-top:1%;width: 60%;">
                  <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i>
                  Due to high computing power needed for network repositioning with dose/time segregated signatures, we require a login. Please note that your personal information will not be stored other than your email for monitoring purposes. Additionally, we limit the user to 5 runs/day and 50 runs total per day. The quota will get refilled every midnight eastern time.
                </div>
                <br>
                <div class="panel panel-default">
                  <?php
                  echo '<div align="center">' . $login_button . '</div>';
                  ?>
                </div>
              </div>
              <div class="alert alert-warning" id="dosetimeallwarning" style="margin: 0 auto; margin-top: 1%;width: 40%;padding-bottom: 25px;display: none;">
                <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i>Running dose/time segregated signatures for all genes will take ~12 hours. To run a faster analysis (~3 hours) with dose/time segregated signatures, run the option with the top 500 genes per signature.
              </div>

              <div class="disable_all">
                <h4 class="instructiontext" style="font-size: 16px;padding-bottom: 10px;">Select or upload network for drug repositioning analysis</h4>
                <div class="selectholder app2">
                  <select class="btn dropdown-toggle btn-light app2" name="network_select" size="1" id="myNetwork">
                    <option value="0" disabled <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                                  echo "";
                                                } else {
                                                  echo "selected";
                                                } ?>>Please select option
                    </option>
                    <option value="1" <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                        $a = $_POST['network_select'];
                                        if ($a == 1) {
                                          echo "selected";
                                        }
                                      } else {
                                        echo "";
                                      }  ?>>Upload network file
                    </option>
                    <option value="2" <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                        $a = $_POST['network_select'];
                                        if ($a == 2) {
                                          echo "selected";
                                        }
                                      } else {
                                        echo "";
                                      }  ?>>Sample Liver Network
                    </option>
                    <option value="3" <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                        $a = $_POST['network_select'];
                                        if ($a == 3) {
                                          echo "selected";
                                        }
                                      } else {
                                        echo "";
                                      }  ?>>Sample Kidney Network
                    </option>
                    <option value="4" <?php if (isset($_POST['network_select']) ? $_POST['network_select'] : null) {
                                        $a = $_POST['network_select'];
                                        if ($a == 4) {
                                          echo "selected";
                                        }
                                      } else {
                                        echo "";
                                      }  ?>>Sample Multi-tissue Network
                    </option>
                  </select>
                  <?php
                  //checks if the USER submitted form and save the MMF data to $sessionID_mapping
                  /*if (isset($_POST['network_select'])) {
                  if ($a == 1 or $a == 2) {
                    if ($_FILES['NetworkApp2uploadedfile']['name'] !== "") // if user did upload a file
                    {
                      $b = $_FILES['NetworkApp2uploadedfile']['name'];
                      $txt = "Resources/shinyapp2_temp/" . $b . "\n";
                      $test = fopen("./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_network", "w");
                      fwrite($test, $txt);
                      fclose($test);
                    }
                  } else {
                    //do nothing
                  }
                //}*/
                  ?>
                </div>

                <div id="NetApp2upload" style="display: none;">
                  <!-- Start of upload div--->
                  <br>
                  <div style="color: black;"> Browse and select <strong>TAB</strong> delimited .txt file (Max size: 35000 unique nodes)</div>
                  <div class="input-file-container" name="Network for App2" style="width: fit-content;">
                    <input class="input-file" id="NetworkApp2uploadInput" name="NetworkApp2uploadedfile" type="file" accept="text/plain" data-show-preview="false">
                    <label id="NetworkApp2labelname" tabindex="0" class="input-file-trigger"><i class="icon-folder-open"></i> Select a file ...</label>
                    <!--Progress bar ------------------------------>
                    <div id="NetworkApp2progressbar" class="progress active" style='display: none;'>
                      <div id="NetworkApp2progresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span id="NetworkApp2progresspercent"></span>
                      </div>
                    </div>
                    <!--Progress bar ------------------------------>
                    <p id="NetworkApp2filereturn" class="file-return"></p>
                    <span id='NetworkApp2_uploaded_file'></span>
                  </div>
                </div> <!-- End of upload div--->

                <!--Div to alert user of certain comment (i.e. success) -->
                <div class="alert-app2" id="alert2"></div>

                <h4 class="instructiontext" style="font-size: 16px;padding-bottom: 10px;">Select species</h4>
                <div class="selectholder app2">
                  <select class="btn dropdown-toggle btn-light app2" name="species_select" size="1" id="mySpecies">
                    <option value="0" disabled <?php if (isset($_POST['species_select']) ? $_POST['species_select'] : null) {
                                                  echo "";
                                                } else {
                                                  echo "selected";
                                                } ?>>Please select option
                    </option>
                    <option value="1" <?php if (isset($_POST['species_select']) ? $_POST['species_select'] : null) {
                                        $b = $_POST['species_select'];
                                        if ($b == 1) {
                                          echo "selected";
                                        }
                                      } else {
                                        echo "";
                                      }  ?>>Human
                    </option>
                    <option value="2" <?php if (isset($_POST['species_select']) ? $_POST['species_select'] : null) {
                                        $b = $_POST['species_select'];
                                        if ($b == 2) {
                                          echo "selected";
                                        }
                                      } else {
                                        echo "";
                                      }  ?>>Mouse/Rat
                    </option>
                  </select>
                </div>

                <h4 class="instructiontext" style="font-size: 16px;padding-bottom: 10px;">Input genes (max 500) to test repositioning, separated by line breaks
                </h4>
                <textarea name="inputgenes" id="dropzone" placeholder="Drop text file(s) or click to manually input genes" required="required"></textarea>
                <br>
                <br>

                <button id="reset" type="button" class="buttonp"><i class="icon-remove"></i>Clear Fields</button> <button id="samplegenes" type="button" class="buttonp"><i class="icon-plus1"></i>Add sample genes</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </form>
  <!--End of app2 form -------------------------------------->
  <div class="disable_all">
    <h5 style="text-align:center;color: #00004d;">Enter your e-mail id for job completion notification (Optional)
      <div id="complete_email"></div>
      <input type="text" name="email" id="yourEmail">
      <button type="button" class="button button-3d button-small nomargin" id="emailSubmit"><i class="icon-email"></i>Send email</button>
    </h5>

    <!----------------------------------------End of shinyapp2 maintable ----------------------------------------------->

    <!-------------------------------------------------Start Review button ----------------------------------------------------->
    <div id="Validatediv_app2" style="text-align: center;">
      <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_app2"><i class="icon-enter"></i>Submit Job</button>
    </div>
    <!-------------------------------------------------End Review button ----------------------------------------------------->
  </div>
  <!---------------------------------------Modal information for signature type ---------------------------------------------->
  <div id="addSignaturemodal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="col-12 modal-title text-center" style="margin-right: -30px;font-size: 45px;">Drug signature type to query</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body" style="text-align: center;">
            <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
              <div class="style-msg infomsg" style="margin: 0 auto; width: 80%;padding:30px;font-size: 18px;text-align: left;"> For <b>Meta</b> signatures, different dose and time regimens are combined and meta-analyzed for 621 drugs for human and 241 drugs for mouse/rat. Currently, we provide liver meta signatures which are the most robust, and we are working to include more tissue-specific meta signatures. This analysis runs for around 20 minutes. <b>Dose/time segregated</b> signatures comprise all tissue-specific drug signatures across all doses and treatment durations and will take around 6 hours to complete. Because this mode necessitates many more computational resources, we require a user login and limit the user to one analysis per day.
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
  <!----------------------------------------------------------------End modal ----------------------------------------------->

  <script>
    $(document).ready(function() {
      $("#flowchart_div").hide();
      localStorage.setItem("on_load_session", string);
      $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + string);
      $('#session_id').css("padding", "17px 30px");

    });

    var string = "<?php echo $sessionID; ?>";
    //var reached_doseseg_limit = "<?php #echo $reached_doseseg_limit; ?>";
    var islogin = <?php echo json_encode($islogin) ?>;


    $("#emailSubmit").on('click', function() {
      var email = $("input[name=email]").val();
      $.ajax({
        type: 'GET',
        url: "pharmomics2_email.php",
        data: {
          sessionID: string,
          app2email: email
        },
        success: function(data) {
          $("#complete_email").html('<div class="alert alert-success" style="display: inline-flex; padding: 5px;"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;padding: 0% 0% 0% 0.3%;margin: 0% 0% 1% 0%;"></i><strong style="margin-top: 5px;"></strong>' + email + '</div>');
          $("#yourEmail").css("display", "none");
          $("#emailSubmit").css("display", "none");
        }
      });
      return false;

    });


    //commented out to temporarily remove login auth until we get https - 11.24.2021 -DAN
    // if (islogin) {
    //   $("#userinfo").show();
    //   var email = "<?php #echo $_SESSION['user_email_address'] ?>"
    //   $("#yourEmail").val(email);
    //   $("#emailSubmit").click();
    // } else {
    //   $("#userinfo").hide();
    // }

    //NETWORK FILE UPLOAD EVENT HANDLER
    $("#NetworkApp2uploadInput").on("change", function() {
      $("#NetworkApp2labelname").html("Select another file?");
      var name = this.files[0].name;
      var file = this.files[0];
      var ext = name.split('.').pop().toLowerCase();
      var fsize = file.size || file.fileSize;
      if (fsize > 50000000000) {
        alert("File Size is too big");
        var control = $("#NetworkApp2uploadInput"); //get the id
        control.replaceWith(control = control.clone().val('')); //replace with clone
      } else {
        var fd = new FormData();
        fd.append("afile", file);
        fd.append("path", "./Data/Pipeline/Resources/shinyapp2_temp/");
        fd.append("data_type", "network_app2");
        fd.append("session_id", string); //changed from session_id to string
        console.log(session_id);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload_app2Network.php', true);

        xhr.upload.onprogress = function(e) {
          if (e.lengthComputable) {
            $('#NetworkApp2progressbar').show();
            var percentComplete = (e.loaded / e.total) * 100;
            $('#NetworkApp2progresswidth').width(percentComplete.toFixed(2) + '%');
            $('#NetworkApp2progresspercent').html(percentComplete.toFixed(2) + '%');
          }
        };

        xhr.onload = function() {
          if (this.status == 200) {
            var resp = JSON.parse(this.response);
            $('#NetworkApp2progresswidth').css('width', '0%').attr('aria-valuenow', 0);
            $('#NetworkApp2progressbar').hide();
            console.log(resp.targetPath);
            if (resp.status == 1) {
              //var fullPath = resp.targetPath;
              //network_file = fullPath.replace("./Data/Pipeline/", "");
              //var filename = fullPath.replace(/^.*[\\\/]/, "").replace(session_id, "");
              $('#NetworkApp2filereturn').html(name);
              $('#NetworkApp2_uploaded_file').html(`<div class="alert alert-success"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;top: -5px;padding: 0% 0% 0% 0.3%;"></i><strong>Upload successful!</strong></div>`);
            } else {
              $('#NetworkApp2_uploaded_file').html('<div class="alert alert-danger"><i class="icon-remove-sign"></i><strong>Error</strong>' + resp.msg + '</div>');
              var control = $("#NetworkApp2uploadInput"); //get the id
              control.replaceWith(control = control.clone().val('')); //replace with clone
              $("#NetworkApp2filereturn").empty();
            }
          };
        };
        xhr.send(fd);
      }
    });
    $("#NetworkApp2labelname").on("keydown", function(event) {
      if (event.keyCode == 13 || event.keyCode == 32) {
        $("#NetworkApp2uploadInput").focus();
      }
    });
    $("#NetworkApp2labelname").on("click", function(event) {
      $("#NetworkApp2uploadInput").focus();
      return false;
    });

    $("#Validatebutton_app2").on('click', function() {
      var networktype = $("#myNetwork").prop('selectedIndex');

      errorlist = [];

      if (networktype == 0) {
        errorlist.push('A network has not been entered!');
      } else if (networktype == 1) {
        if ($("#NetworkApp2uploadInput").nextAll('.file-return').eq(0).text() == '') {
          errorlist.push($("#NetworkApp2uploadInput").parent().attr('name') + ' is not selected!');
        }
      }

      var speciestype = $("#mySpecies").prop('selectedIndex');

      if (speciestype == 0) {
        errorlist.push('Species is not selected!');
      }

      var signaturetype = $("#mySigType").prop('selectedIndex');

      //if (signaturetype == 1 | signaturetype == 2) {
      /*if (signaturetype == 2) {
        errorlist.push('Dose/segregated signatures type not ready yet!');
      }*/

      if (!$("#dropzone").val())
        errorlist.push('No input genes has been entered!');

      if (signaturetype == 1 | signaturetype == 2) {
        if (reached_doseseg_limit == "yes") {
          errorlist.push('Maximum number of dose/time segregated analyses reached for the day! Please try again tomorrow.');
        }
      }


      if (errorlist.length === 0) {
        $("#app2dataform").submit();
      } else {
        var result = errorlist.join("\n");
        //alert(result);
        $('#errorp_app2').html(result);
        $("#errormsg_app2").fadeTo(2000, 500).slideUp(500, function() {
          $("#errormsg_app2").slideUp(500);
        });

      }


      return false;


    });




    $('#app2dataform').submit(function(e) {


      e.preventDefault();
      $('#APP2tab2').show();
      $('#APP2tab2').click();
      var form_data = new FormData(document.getElementById('app2dataform'));
      form_data.append("sessionID", string);
      console.log(form_data);
      $.ajax({
        'url': 'run_app2.php',
        'type': 'POST',
        'data': form_data,
        processData: false,
        contentType: false,
        'success': function(data) {
          $("#myAPP2_run").html(data);
        }
      });

    });

    var dropzone = document.querySelector('#dropzone');
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
    $('.selectholder.app2').each(function() {
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
          $('.activeselectholder.app2').each(function() {
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
    $('.selectholder.app2 .selectdropdown span').click(function() {
      $(this).siblings().removeClass('active');
      $(this).addClass('active');
      var value = $(this).text();
      if (value.includes("Dose/time")) {
        if (!islogin) {
          setTimeout(
            function() {
              $("#login_panel").show();
            },
            200
          );
          $(".disable_all").addClass("blur");
          $("#Validatebutton_app2").hide();
        } else {
          $("#login_panel").hide();
          $(".disable_all").removeClass("blur");
          $("#Validatebutton_app2").show();
        }
      } else if (value.includes("Meta")) {
        $("#login_panel").hide();
        $(".disable_all").removeClass("blur");
        $("#Validatebutton_app2").show();
      }

      $(this).parent().siblings('select').val(value);
      $(this).parent().siblings('.desc').fadeOut(100, function() {
        $(this).text(value);
        $(this).fadeIn(100);
      });
      $(this).parent().siblings('select').children('option:contains("' + value + '")').prop('selected', 'selected');


      //Show select file box when option 1 is selected
      var select = $("#myNetwork").find('option:selected').index();
      if (select != 1)
        $("#myNetwork").parent().next().hide();
      if (select == 1)
        $("#myNetwork").parent().next().show();
      /*if (select > 1)
        $("#myNetwork").parent().nextAll(".alert-app2").eq(0).html(successalert).hide().fadeIn(300);*/
      if (select == 1)
        $("#myNetwork").parent().nextAll(".alert-app2").eq(0).html(uploadalert).hide().fadeIn(300);
      else
        $("#myNetwork").parent().nextAll(".alert-app2").eq(0).empty();

      var selectsig = $("#mySigType").find('option:selected').index();
      if (selectsig == 2)
        $("#mySigType").parent().next().next().show();
      else
        $("#mySigType").parent().next().next().hide();

    });
    //if user clicks somewhere else, it will close the dropdown box.
    $(document).mouseup(function(e) {
      var container = $(".selectholder.app2");

      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        $('.activeselectholder.app2').each(function() {
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

    var successalert = '<br><div class="alert alert-success" style="text-align: center;margin: 0 auto; width: 50%;"><i class="i-rounded i-small icon-check" stsuccessyle="background-color: #2ea92e;padding: 0% 0% 0% 0.3%;margin: 0% 0% 1% 0%;"></i><strong>Success!</strong> </div>';
    /*var uploadalert = `<div class="alert alert-warning" style="margin: 0 auto; width: 50%;">
                                    <div class="sb-msg">
                                        <i class="icon-warning-sign"></i> 
                                        <strong>Maximum File Size:</strong> 2.5Mb</div>
                                    <div class="sb-msg">
                                        <i class="icon-warning-sign"></i>    
                                        <strong>Accepted file type:</strong> *.txt</div>
                                    </div>`;*/
    var uploadalert = `<div style="padding:0% 40%;">
        			<p style="margin: 0;"><b>File format</b></p>
					<table class="samplefile">
					  <thead>
						<tr>
						  <th style="font-size: 16px;">HEAD</th>
						  <th style="font-size: 16px;">TAIL</th>
						</tr>
					  </thead>
					  <tbody>
						<tr>
						  <td data-column="MARKER(Header): ">A1BG</td>
						  <td data-column="VALUE(Header): ">SNHG6</td>
						</tr>
						<tr>
						  <td data-column="MARKER(Header): ">A1BG</td>
						  <td data-column="VALUE(Header): ">UNC84A</td>
						</tr>
						<tr>
						  <td data-column="MARKER(Header): ">A1CF</td>
						  <td data-column="VALUE(Header): ">KIAA1958</td>
						</tr>
					  </tbody>
					</table>
        			</div>`;

    $("#myNetwork").on("change", function() {
      var select = $(this).find('option:selected').index();
      if (select != 1)
        $(this).parent().next().hide();

      if (select == 1)
        $(this).parent().next().show();

      if (select > 1)
        $(this).parent().nextAll(".alert-app2").eq(0).html(successalert).hide().fadeIn(300);
      else if (select == 1)
        $(this).parent().nextAll(".alert-app2").eq(0).html(uploadalert).hide().fadeIn(300);
      else
        $(this).parent().nextAll(".alert-app2").eq(0).empty();
    });


    $('select.app2').each(function() {
      $(this).trigger('change');
    });


    $("#dropzone").keyup(function(event) {

      // skip for arrow keys
      if (event.which >= 37 && event.which <= 40) return;

      // format number
      $(this).val(function(index, value) {
        return value
          .replace(/[^a-z0-9\n]/gi, '');
      });
    });


    $("#samplegenes").on('click', function() {

      $.ajax({
        url: "Data/Pipeline/Resources/app2samplegenes.txt",
        dataType: "text",
        success: function(data) {
          $("#dropzone").val(data);
        }
      });

    });

    var button = document.getElementById("myTutButton_app2");
    var val = 0;

    //begin function for when button is clicked-------------------------------------------------------------->
    button.addEventListener("click", function() {

      //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
      var $this = $(this);

      //If tutorial is already opened yet, then do this-------------------------------------------------------------->
      if ($this.data('clicked')) {

        $('.tutorialbox').hide();

        $('#app2networktable').find('tr').each(function() {
          $(this).find('td[name="tut"]').eq(-1).remove();
          $(this).find('th[name="tut"]').eq(-1).remove();
        });




        $this.data('clicked', false);
        val = val - 1;
        $("#myTutButton_app2").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

      }

      //If tutorial is not opened yet, then do this-------------------------------------------------------------->
      else {
        $this.data('clicked', true);
        val = val + 1; //val counter to not duplicate prepend function


        if (val == 1) //Only prepend the tutorial once
        {
          $('#app2networktable').find('th[name="val_app2"]').eq(-1).after('<th name="tut">Tutorial</th>');

          $('#app2networktable').find('td[name="val1_app2"]').eq(-1).after(`
                                    <td name="tut" style="text-align: left;font-size: 20px;">
                                    <ol style="padding: 60px;">
                                    <p><li> <strong>Network and repositioning drug datasets:</strong> <br>
                                            We offer sample liver, kidney, and multi-tissue sample networks for repositioning and the user can upload their own custom network in a .txt file with the columns 'HEAD' and 'TAIL' denoting network connections between genes. For meta signatures, we currently provide liver-based DEG signatures which are the most robust. As resources grow, we hope to include more tissue signatures.<br><br>
                                        </li>
                                        <li> <strong>Input genes:</strong> <br>
                                             Input genes should be separated by line break delimiter. <br> We currently accept both human, mouse and rat gene symbols. <br> Different species will be converted automatically. <br> <br>You may click "Add Sample Genes" to view a sample format of the input genes (Hyperlipidemia dataset from Mergeomics pipeline) <br><br>
                                        </li>
                                        <li> (Optional) Enter an email address to have your results emailed to you after job completion. <br><br>
                                        </li>
                                        <li> Click the "Submit Job" button to run the analysis. Each job will take several minutes to finish. <br><br>
                                        </li>
                                        <li> After the job is completed, results will be available for review and download. <br> Drugs from LINCS1000 have labels appended with _LINCS1000
                                        </li>
                                        </ol>
                                     </p>
                                     </td>

                                    `);

          $('.tutorialbox').show();
          $('.tutorialbox').html('This tool ranks drugs based on the connectivity of drug signatures to input genes as defined by a gene network model.');


        }
        $("#myTutButton_app2").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
      }



    });


    $('#reset').click(function() {
      $('#dropzone').val("");
      $('.selectholder.app2 .selectdropdown span').siblings().removeClass('active');
      $('select>option:eq(0)').prop('selected', true);
      $('.desc').text("Please select option");
    });
  </script>