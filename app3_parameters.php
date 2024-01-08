<?php
include "functions.php";

if (isset($_POST['sessionID']) ? $_POST['sessionID'] : null) {
  $sessionID = $_POST['sessionID'];
}
if (isset($_GET['sessionID']) ? $_GET['sessionID'] : null) {
  //If sessionID is received from post call, it means its from session loading
  $sessionID = $_GET['sessionID'];
}

if ($sessionID == null) {
  $sessionID = generateRandomString(10);
}

$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
$fsession = $ROOT_DIR . "Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$session_write = NULL;
//initiate session file
if (!file_exists($fsession)) {
  $sessionfile = fopen($fsession, "w");
  $session_write .= "Pipeline:" . "\t" . "Pharmomics_App3" . "\n";
  $session_write .= "Pharmomics_Path:" . "\t" . "1.0" . "\n";
  fwrite($sessionfile, $session_write);
  fclose($sessionfile);
  chmod($fsession, 0755);
}

?>




<style type="text/css">
  textarea {
    width: 85%;
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

  .app3 {
    width: 500px !important;
    margin: 0 auto !important;
  }
</style>

<div id="errormsg_app3" class='alert alert-danger nobottommargin alert-top' style="display: none; text-align: center;">
  <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
  <i class="icon-remove-sign"></i>
  <strong>Error! </strong>
  <p id="errorp_app3" style="white-space: pre;"></p>
</div>



<!-- Grid container for MDF ===================================================== -->
<div class="gridcontainer">

  <!-- Description ===================================================== -->
  <h4 class="instructiontext">
    This part of the pipeline performs overlap based drug repositioning (PharmOmics) <br> based on user input genes and provides a hepatotoxicity overlap score for user input genes.
  </h4>


  <!--Start app3 Tutorial --------------------------------------->

  <div style="text-align: center;">
    <button class="button button-3d button-rounded button" id="myTutButton_app3"><i class="icon-question1"></i>Click for tutorial</button>
  </div>

  <div class='tutorialbox' style="display: none;"></div>
  <!--End app3Tutorial --------------------------------------->



</div>
<!--End of gridcontainer ----->





<!-- Description ============Start table========================================= -->
<form enctype="multipart/form-data" action="app3_parameters.php" name="select" id="app3dataform">
  <div class="table-responsive" style="overflow: visible;">
    <!--Make table responsive--->
    <table class="table table-bordered" style="text-align: center;" ; id="app3networktable">

      <thead>
        <tr>
          <!--First row of table------------Column Headers------------------------------>
          <th colspan="2" name="val_app3">Drug Repositioning Analysis</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!--Second row of table------------------------------------------>
          <td>
            <h4 class="instructiontext" style="font-size: 15px;">Input upregulated genes for repositioning, <br> separated by line breaks</h4>


            <textarea name="upregulatedgenes" id="dropzone" placeholder="Drop text file(s) 
or 
click to manually input genes" required="required"></textarea>


          </td>
          <td name="val1_app3">

            <h4 class="instructiontext" style="font-size: 15px;">Input downregulated genes for test repositioning, <br> separated by line breaks (Optional)</h4>

            <textarea name="downregulatedgenes" id="dropzone2" placeholder="Drop text file(s) 
or 
click to manually input genes" required="required"></textarea>
            <br>






          </td>
        </tr>
      </tbody>
    </table>
    <div id="buttons" style="width: 100%; text-align: center;">
      <button id="reset" type="button" class="buttonp">Clear Fields</button> <button id="samplegenes" type="button" class="buttonp">Add sample upregulated genes</button>
    </div>
  </div>


</form>
<!--End of app3 form -------------------------------------->


<h5 style="text-align:center;color: #00004d;">Enter your e-mail id for job completion notification (Optional)
  <div id="complete_email"></div>
  <input type="text" name="email" id="yourEmail">

  <button type="button" class="button button-3d button-small nomargin" id="emailSubmit">Send email</button>

</h5>

<!----------------------------------------End of shinyapp3 maintable ----------------------------------------------->

<!-------------------------------------------------Start Review button ----------------------------------------------------->
<div id="Validatediv_app3" style="text-align: center;">
  <button type="button" class="button button-3d button-large nomargin" id="Validatebutton_app3">Submit Job</button>
</div>
<!-------------------------------------------------End Review button ----------------------------------------------------->

<script>
  var string = "<?php echo $sessionID; ?>";
  localStorage.setItem("on_load_session", string);
  $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + string);
  $('#session_id').css("padding", "17px 30px");

  $("#emailSubmit").on('click', function() {
    var email = $("input[name=email]").val();
    $.ajax({
      type: 'GET',
      url: "pharmomics3_email.php",
      data: {
        sessionID: string,
        app3email: email
      },
      success: function(data) {
        $("#complete_email").html('<div class="alert alert-success" style="display: inline-flex; padding: 5px;"><i class="i-rounded i-small icon-check" style="background-color: #2ea92e;"></i><strong style="margin-top: 5px;"></strong>' + email + '</div>');
        $("#yourEmail").css("display", "none");
        $("#emailSubmit").css("display", "none");
      }
    });
    return false;

  });


  $("#Validatebutton_app3").on('click', function() {
    var networktype = $("#myNetwork").prop('selectedIndex');

    errorlist = [];

    if (!$("#dropzone").val())
      errorlist.push('No upregulated genes have been entered!');


    if (errorlist.length === 0) {

      $("#app3dataform").submit();
    } else {
      var result = errorlist.join("\n");
      //alert(result);
      $('#errorp_app3').html(result);
      $("#errormsg_app3").fadeTo(2000, 500).slideUp(500, function() {
        $("#errormsg_app3").slideUp(500);
      });

    }


    return false;


  });




  $('#app3dataform').submit(function(e) {


    e.preventDefault();
    $('#APP3tab2').show();
    $('#APP3tab2').click();
    var form_data = new FormData(document.getElementById('app3dataform'));
    form_data.append("sessionID", string);

    $.ajax({
      'url': 'run_app3.php',
      'type': 'POST',
      'data': form_data,
      processData: false,
      contentType: false,
      'success': function(data) {
        $("#myAPP3_run").html(data);
      }
    });

  });

  var dropzone = document.querySelector('#dropzone');
  var dropzone2 = document.querySelector('#dropzone2');
  dropzone.addEventListener("dragenter", onDragEnter, false);
  dropzone.addEventListener('dragover', onDragOver, false);
  dropzone.addEventListener('drop', onDrop1, false);
  dropzone2.addEventListener("dragenter", onDragEnter, false);
  dropzone2.addEventListener('dragover', onDragOver, false);
  dropzone2.addEventListener('drop', onDrop2, false);

  function onDragEnter(e) {
    e.stopPropagation();
    e.preventDefault();
  }

  function onDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // it's a copy!
  }

  function onDrop1(evt) {
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

  function onDrop2(evt) {
    evt.stopPropagation();
    evt.preventDefault();

    var files = evt.dataTransfer.files; // object FileList
    for (var i = 0; i < files.length; i++) {
      if (files[i].type == "text/plain") {
        var reader = new FileReader();
        reader.onload = function(event) {
          dropzone2.value += event.target.result.replace(/[^a-z0-9\n]/gi, '');
          //console.log(event.target)
        }
        //instanceOfFileReader.readAsText(blob[, encoding]);
        reader.readAsText(files[i], "UTF-8");
      } else {
        console.log(files[i].type);
      }
    }
  }




  $("#dropzone").keyup(function(event) {

    // skip for arrow keys
    if (event.which >= 37 && event.which <= 40) return;

    // format number
    $(this).val(function(index, value) {
      return value
        .replace(/[^a-z0-9\n]/gi, '');
    });
  });


  $("#dropzone2").keyup(function(event) {

    // skip for arrow keys
    if (event.which >= 37 && event.which <= 40) return;

    // format number
    $(this).val(function(index, value) {
      return value
        .replace(/[^a-z0-9\n]/gi, '');
    });
  });




  $(document).on("click", "#samplegenes", function() {

    $.ajax({
      url: "Data/Pipeline/Resources/app2samplegenes.txt",
      dataType: "text",
      success: function(data) {
        $("#dropzone").val(data);
      }
    });
  });


  var button = document.getElementById("myTutButton_app3");
  var val = 0;

  //begin function for when button is clicked-------------------------------------------------------------->
  button.addEventListener("click", function() {

    //Keep track of when tutorial is opened/closed-------------------------------------------------------------->
    var $this = $(this);

    //If tutorial is already opened yet, then do this-------------------------------------------------------------->
    if ($this.data('clicked')) {

      $('.tutorialbox').hide();

      $('#app3networktable').find('tr').each(function() {
        $(this).find('td[name="tut"]').eq(-1).remove();
        $(this).find('th[name="tut"]').eq(-1).remove();
      });




      $this.data('clicked', false);
      val = val - 1;
      $("#myTutButton_app3").html('<i class="icon-question1"></i>Click for Tutorial'); //Change name of button to 'Click for Tutorial'

    }

    //If tutorial is not opened yet, then do this-------------------------------------------------------------->
    else {
      $this.data('clicked', true);
      val = val + 1; //val counter to not duplicate prepend function
      if (val == 1) //Only prepend the tutorial once
      {
        $('#app3networktable').find('th[name="val_app3"]').eq(-1).after('<th name="tut">Tutorial</th>');

        $('#app3networktable').find('td[name="val1_app3"]').eq(-1).after(`
                                    <td name="tut" style="text-align: left;font-size: 20px;">
                                    <ol style="padding: 0 50px;">
                                    <p><li> <strong>Input upregulated genes</strong>. If single list of genes, input genes in the upregulated genes box.<br>
                                             <br>
                                        </li>
                                        <li> <strong>(Optional)Input downregulated genes</strong> <br>
                                            <br>
                                        </li>
                                        <li> (Optional) Enter an email address to have your results emailed to you after job completion. <br><br>
                                        </li>
                                        <li> Click the "Submit Job" button to run the analysis. Each job will take several minutes to finish. <br><br>
                                        </li>
                                        <li> After the job is completed, results will be available for review and download.
                                        </li>
                                        </ol>
                                     </p>
                                     </td>

                                    `);

        $('.tutorialbox').show();
        $('.tutorialbox').html('This tool calculates the degree and significance of direct overlap between input genes and drug genes using the Jaccard score (unsigned for single list of genes, signed for up- and downregulated genes).');


      }
      $("#myTutButton_app3").html("Close Tutorial"); //Change name of button to 'Close Tutorial'
    }



  });






  $('#reset').click(function() {
    $('#dropzone').val("");
    $('#dropzone2').val("");
  });
</script>