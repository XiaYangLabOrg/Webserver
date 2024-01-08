<?php
include 'functions.php';

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




$index_file = file_get_contents("./include/pharmomics/Pharmomics_Meta_Human_Gene_Df/gene_list.json");
// ini_set("memory_limit", "3000M");
$json = json_decode($index_file, true);
$gene_arr = $json;

// foreach ($json as $gene) {
//   array_push($gene_arr, $gene['Gene']);
// }

$gene_list = array_unique($gene_arr);
sort($gene_list);

?>

<style type="text/css">
  #cover-spin {
    position: fixed;
    width: 100%;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.7);
    z-index: 9999;
    display: none;
  }

  @-webkit-keyframes spin {
    from {
      -webkit-transform: rotate(0deg);
    }

    to {
      -webkit-transform: rotate(360deg);
    }
  }

  @keyframes spin {
    from {
      transform: rotate(0deg);
    }

    to {
      transform: rotate(360deg);
    }
  }

  #cover-spin::after {
    content: '';
    display: block;
    position: absolute;
    left: 48%;
    top: 40%;
    width: 40px;
    height: 40px;
    border-style: solid;
    border-color: black;
    border-top-color: transparent;
    border-width: 4px;
    border-radius: 50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
  }

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

  /* added by Jess */
  .ajaxloading {
    display: none;
    /*position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;*/
  }



  body.loading .ajaxloading {
    overflow: hidden;
  }


  /* Anytime the body has the loading class, our
   modal element will be visible */

  body.loading .ajaxloading {
    display: inline;
  }

  .autocomplete {
    position: relative;
    display: inline-block;

  }

  input {
    border: 1px solid transparent;
    background-color: #f1f1f1;
    padding: 10px;
    font-size: 16px;
  }

  input[type=text] {
    background-color: #f1f1f1;
    width: 100%;
  }

  .autocomplete-items {
    overflow: auto;
    height: 250px;
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    /*position the autocomplete items to be the same width as the container:*/
    top: 100%;
    left: 0;
    right: 0;
  }

  .autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: #fff;
    border-bottom: 1px solid #d4d4d4;
  }

  /*when hovering an item:*/
  .autocomplete-items div:hover {
    background-color: #e9e9e9;
  }

  /*when navigating through the items using the arrow keys:*/
  .autocomplete-active {

    background-color: DodgerBlue !important;
    color: #ffffff;
  }

  .search-button {
    position: absolute;
    right: 10px;
    top: 17px;
    background-color: #778899;
    border-radius: 50%;
    border: 0;
    color: #FFF;
    width: 30px;
    height: 30px;
    outline: 0;
  }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
  <div class="alert alert-warning" style="margin: 0 auto; width: 50%;">
    <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i><strong>Note:</strong> You may experience delays to load the gene regulation information. We are working on increasing the tool's efficiency. We appreciate your patience!
  </div>
</div> -->

<!-- <div id="preloader" class="instructiontext" style="text-align: center;display:none;"></div> -->

<br>

<!-- <div class="ajaxloading" style="text-align: center;padding: 20px 20px 0 20px;font-size: 18px;">
  <div class="text">
    <span>Loading data</span><span class="dots">...</span>
  </div>
</div> -->


<!-- Description ============Start table========================================= -->
<!--<form enctype="multipart/form-data" action="app1_parametersgene.php" name="select" id="app1dataform">-->
<div class="table-responsive" style="overflow: visible;">
  <!--Make table responsive--->
  <div class="alert alert-warning" style="margin: 0 auto; width: 35%;text-align: center;">
    <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i>
    This function is currently based on only PharmOmics Meta human data.
  </div>
  <table class="table table-bordered" style="text-align: center;margin-top: 2%;" id="app1networktable">
    <thead>
      <tr>
        <!--First row of table------------Column Headers------------------------------>
        <th colspan="2" name="val_app1">PharmOmics gene regulation review</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <!--Second row of table------------------------------------------>
        <td style="width: 100%;">
          <h4 class="instructiontext" style="font-size: 15px;padding-top: 0;">
            Search gene of interest
          </h4>
          <div class="autocomplete" style="width:300px;padding-bottom: 1.5%;">
            <input id="genes" type="text" name="genes" placeholder="Gene" autocomplete="off">
            <button class="search-button" id="search_gene"><i class="fa fa-search"></i></button>
          </div>

          <!-- <select style="width: 100%;" name="gene_name" size="1" id="myGeneName">
               <?php
                // foreach ($gene_list as $item) {
                //   echo "<option value = $item>$item</option>";
                // }
                ?>
            </select> -->
        </td>
      </tr>
      <!--Added by Jess----------->
      <tr>
        <td>
          <!--Second column-------------------------------->
          <table id="datatable_app1_gene" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
              <tr>
                <!--Header names are overwritten anyway?----------->
                <th>Gene</th>
                <th>Direction</th>
                <th>Tissue</th>
                <th>Drug</th>
                <th>Dataset</th>
              </tr>
            </thead>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<div id="preloader" class="instructiontext" style="text-align: center;"></div>
<div id="cover-spin"></div>
<!--</form>-->
<!--End of app3 form -------------------------------------->


<link href="include/select2.css" rel="stylesheet" />
<script src="include/js/bs-datatable.js"></script>



<script type="text/javascript">
  var string = "<?php echo $sessionID; ?>";

  localStorage.setItem("on_load_session", string);
  $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + string);
  $('#session_id').css("padding", "17px 30px");

  //added by Jess
  $body = $("body");

  $(document).on({
    ajaxStart: function() {
      $body.addClass("loading");
    },
    ajaxStop: function() {
      $body.removeClass("loading");
    }
  });

  var table2 = $('#datatable_app1_gene').DataTable({
    "searching": true,
    "paging": true,
    "ordering": true,
    "pageLength": 25,
    "dom": '<"toolbar">frtip',
    data: [],
    columns: [{
        "title": "Gene"
      },
      {
        "title": "Direction"
      },
      {
        "title": "Tissue"
      },
      {
        "title": "Drug"
      },
      {
        "title": "Dataset"
      },
    ],
    rowCallback: function(row, data) {},
    "filter": false,
    "info": true,
    "processing": true,
    "retrieve": true,
    "columnDefs": [{
        "width": "20%"
      },
      {
        "width": "20%"
      },
      {
        "width": "20%"
      },
      {
        "width": "20%"
      },
      {
        "width": "20%"
      },
      {
        render: function(data, type, full, meta) {
          return "<div style='max-height: 100px; overflow-y: auto;'>" + data + "</div>";
        },
        targets: [3,4]
      }
    ]
  });

  $("div.toolbar").html('<b>Gene regulation</b>');

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
              id: [elem.innerText, i].join(""),
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


      $("#myGeneName").select2({
        ajax: {},
        placeholder: "Please select a gene",
        allowClear: true,
        dataAdapter: CustomData
      });
    });


  $(document).ready(function() {

    $('#cover-spin').hide();
    $.ajax({ // Jess added
      type: "GET",
      url: "include/pharmomics/Pharmomics_Meta_Human_Gene_Df/gene_list.json",
      dataType: "json",
      success: function(data) {
        autocomplete(document.getElementById("genes"), data);
      }
    });
  });

  $(document).on('keypress', function(e) {
    if (e.which == 13) {
      $('#search_gene').click();
    }
  });

  $('#search_gene').on('click', function() {
    $('#cover-spin').show(0);
    var gene = $("#genes").val();
    //convert = [],
    //convert_done = [];

    // function convert2draw(arr) {
    //   $.each(arr, function(key, value) {
    //     convert.push(value["Gene"]);
    //     convert.push(value["Direction"]);
    //     convert.push(value["Tissue"]);
    //     convert.push(value["Drug"]);
    //     convert.push(value["Dataset"]);
    //     convert_done.push(convert);
    //     convert = [];
    //   });

    //   table2.clear().draw();

    //   table2.rows.add(convert_done).draw();
    //   convert_done = [];
    // }
    if (gene != "") {

      $.ajax({ // Jess added
        type: "GET",
        //url: "app1_parametersgene_loadJson.php?gene=" + gene,
        url: "include/pharmomics/Pharmomics_Meta_Human_Gene_Df/" + gene + ".json",
        dataType: "json",
        // beforeSend: function() {
        //   $('#preloader').append(`Loading data...<br><img src="include/pictures/ajax-loader.gif">`).show();
        // },
        complete: function() {
          //$('#preloader').empty().hide();
          $("#cover-spin").hide();
        },
        success: function(data) {

          //data = JSON.parse(data);
          table2.clear().draw();

          table2.rows.add(data).draw();
          // degs = $.grep(data, function(v) {

          //   return v["Gene"] === gene;
          // });

          // convert2draw(degs);
          $('#cover-spin').hide();
        },
        error: function() {
          alert("json not found");
        }
      });
      //}, 500)

    }



  });

  function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) {
        return false;
      }
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            /*close the list of autocompleted values,
            (or any other open lists of autocompleted values:*/
            closeAllLists();
          });
          a.appendChild(b);
        }
      }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
    });

    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }

    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
      closeAllLists(e.target);
    });
  }
</script>