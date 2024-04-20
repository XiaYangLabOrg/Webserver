<?php
include "functions.php";
$old = ini_set('memory_limit', '2000M');
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
}

if (isset($_GET['run'])) {
    $run = $_GET['run'];
}


// $fpath1 = "./Data/Pipeline/Resources/LD_files/file1.txt";
// $file1 = trim(file_get_contents($fpath1));
// $fpath2 = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_marker";
// $file2 = trim(file_get_contents($fpath2));

//Dan - 07242020
$file1 = "#!/bin/bash\n" .
    "# Remove dependent markers and prepare an optimized marker and gene file\n" .
    "# for marker set enrichment analysis (MSEA). You must have the MDPrune software\n" .
    "# installed to use this script.\n" .
    "#\n" .
    "# Written by Ville-Petteri Makinen 2013, Modified by Le Shu 2015\n" .
    "#\n" .
    "#\n" .
    "# Original marker file. This must have two columns names 'MARKER' and\n" .
    "# 'VALUE', where value denotes the association to the trait of interest.\n" .
    "# The higher the value, the stronger the association (e.g. -log P).";
$file2 = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_marker";


// $tocountlines = "./Data/Pipeline/" . "$file2";
//Dan - 07242020
$tocountlines = $file2;
$linecount = intval(exec("wc -l '$tocountlines'"));

// $linecount = count(file("$tocountlines")); 

// echo "There are $linecount lines in $file2"; 



// $file2 = "MARFILE=" . "\"$file2\"";
// $fpath3 = "./Data/Pipeline/Resources/LD_files/file2.txt";
// $file3 = trim(file_get_contents($fpath3));
// $fpath4 = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_mapping";
// $file4 = trim(file_get_contents($fpath4));
// $file4 = "GENFILE=" . "\"$file4\"";
// $fpath5 = "./Data/Pipeline/Resources/LD_files/file3.txt";
// $file5 = trim(file_get_contents($fpath5));
// $fpath6 = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_linkage";
// $file6 = trim(file_get_contents($fpath6));
// $file6 = "MDSFILE=" . "\"$file6\"";
// $fpath7 = "./Data/Pipeline/Resources/LD_files/file4.txt";
// $file7 = trim(file_get_contents($fpath7));
// $file8 = "OUTPATH=\"Resources/ldprune_temp/$sessionID" . "_output\"";
// $testing = "TRIALNAME=\"" . $ROOT_DIR . "/Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output\"" . "\n";
// $testing .= "mkdir -p \$TRIALNAME" . "\n";
// $testing .= "chmod a+rwx \$TRIALNAME" . "\n";

// $fpath9 = "./Data/Pipeline/Resources/LD_files/file5.txt";
// $file9 = trim(file_get_contents($fpath9));
// $fpath10 = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_percent_markers";
// $file10 = trim(file_get_contents($fpath10));
// $fpath11 = "./Data/Pipeline/Resources/LD_files/file6.txt";
// $file11 = trim(file_get_contents($fpath11));

//Dan - 07242020
//$fjson = "./Data/Pipeline/Resources/ssea_temp/$sessionID" . "data.json";
$fjson = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "data.json";
$json = json_decode(file_get_contents($fjson))->data;
$mdf = $ROOT_DIR . "Data/Pipeline/" . basename($json[0]->mdf);
$mdf_ntop = $json[0]->mdf_ntop;
$marker_association = $ROOT_DIR . "Data/Pipeline/" . basename($json[0]->association);
$mapping =  $ROOT_DIR . "Data/Pipeline/" . basename($json[0]->marker);
$MMFConvert =  $json[0]->MMFConvert;

if (is_string($mapping)) {
    //File will be generated in result_MDF.php
    $mapping = "Data/Pipeline/Resources/ssea_temp/" . $sessionID . ".mappingfile.txt";
} else {
    $mapping = $mapping[0];
    if($MMFConvert!=="none"){
        //File will be generated in result_MDF.php
        $mapping = "Data/Pipeline/Resources/ssea_temp/Converted_" . basename($mapping);
    }
}


$file2 = "MARFILE=\"" . $marker_association . "\"";
$file3 = "# Mapping between genes and markers. This must have the columns 'GENE'\n" .
    "# and 'MARKER'.      ";

$file4 = "GENFILE=\"" . $mapping . "\"";


$file5 = "# The third input file defines the marker dependency structure (e.g. Linkage disequilibrium) between markers. It has three columns\n" .
    "#'MARKERa', 'MARKERb' and 'WEIGHT'. Marker pairs with WEIGHT > Cutoff\n" .
    "# are considered dependent and will be filtered.";

$file6 =  "MDSFILE=\"" . $mdf . "\"";

$file7 = "# Folder to hold the results.";
$file8 = "OUTPATH=\"" . $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_output\"";

$TRIALNAME = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output";
$testing = "TRIALNAME=\"" . $TRIALNAME . "\"\n";
$testing .= "mkdir -p \$TRIALNAME" . "\n";
#$testing .= "chmod a+rwx \$TRIALNAME" . "\n";

$file9 = "# To increase result robustness and conserve memory and time, it is sometimes useful
          # to limit the number of markers. Here, only the top 50% associations are considered.";

$file11 = "echo -e \"MARKER\tVALUE\" > \$TRIALNAME/header.txt\n" .
    "nice sort -r -g -k 2 \$MARFILE > \$TRIALNAME/sorted.txt\n" .
    "NMARKER=$(wc -l < \$TRIALNAME/sorted.txt)\n" .
    "NMAX=$(echo \"(\$NTOP*\$NMARKER)/1\" | bc)\n" .
    "nice head -n \$NMAX \$TRIALNAME/sorted.txt > \$TRIALNAME/top.txt\n" .
    "cat \$TRIALNAME/header.txt \$TRIALNAME/top.txt > \$TRIALNAME/subset.txt\n" .
    "# Remove Markers in dependency structure and create input files for MSEA.\n" .
    "nice " . $ROOT_DIR . "Data/Pipeline/Resources/LD_files/mdprune \$TRIALNAME/subset.txt \$GENFILE \$MDSFILE \$OUTPATH\n";

$file12 = "echo \"MDF COMPLETE\"\n";
// $test = $file10*10;

// $markers_to_use = round($linecount/100*$file10);

$markers_to_use = $mdf_ntop / 100;

$markers_to_use = "NTOP=" . "$markers_to_use";

$fpathOut = $ROOT_DIR . "Data/Pipeline/$sessionID" . "preprocess.bash";


$data = $file1 . "\n" . $file2 . "\n" . $file3 . "\n" . $file4 . "\n" . $file5 . "\n" . $file6 . "\n" . $file7 . "\n" . $file8 . "\n" . $testing . "\n" . $file9 . "\n" . $markers_to_use . "\n" . $file11 . "\n". $file12;


$fp = fopen($fpathOut, "w");
fwrite($fp, $data);
fclose($fp);

chmod($fpathOut, 0777);


$gwas_location = $ROOT_DIR . "/Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/MDF_corrected_mapping.txt";

$gwas_path = "./Data/Pipeline/Resources/ssea_temp/" . "$sessionID" . "path_to_cat_GWAS";
$gwas_file = fopen($gwas_path, "w");
fwrite($gwas_file, $gwas_location);
fclose($gwas_file);

chmod($gwas_path, 0777);

$gwas_path2 = "./Data/Pipeline/Resources/ssea_temp/" . "$sessionID" . "GWAS_file_list";
$gwas_file2 = fopen($gwas_path2, "w");
fwrite($gwas_file2, $gwas_location);
fclose($gwas_file2);

chmod($gwas_path2, 0777);

$mapping_location = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/" . "$sessionID" . "_output/MDF_corrected_association.txt";

$mapping_path = "./Data/Pipeline/Resources/ssea_temp/" . "$sessionID" . "LOCI";
$mapping_file = fopen($mapping_path, "w");
fwrite($mapping_file, $mapping_location);
fclose($mapping_file);

chmod($mapping_path, 0777);


$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
$fpostOut = "./Data/Pipeline/Resources/ldprune_temp/$sessionID" . "_MDF_postdata.txt";
if (file_exists($fsession)) {
    $data = file($fsession); // reads an array of lines
    function replace_a_line($data)
    {
        if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.25")) {
            return 'Mergeomics_Path:' . "\t" . "1.5" . "\n";
        }
        return $data;
    }
    $data = array_map('replace_a_line', $data);
    file_put_contents($fsession, implode('', $data));
}
?>


<script type="text/javascript">

function mdfAjax() {
    var
      $http,
      text,
      $self = arguments.callee;
      var string = "<?php echo $sessionID; ?>";
    if (window.XMLHttpRequest) {
      $http = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
      try {
        $http = new ActiveXObject('Msxml2.XMLHTTP');
      } catch (e) {
        $http = new ActiveXObject('Microsoft.XMLHTTP');
      }
    }

    if ($http) {
      $http.onreadystatechange = function() {
        if (/4|^complete$/.test($http.readyState)) {

            text = $http.responseText;
            //check mdf log has finished with "MDF COMPLETE" string at the end and terminate the loop Dec 26. 2023 -Dan
            timeOutVar=null;
            if (!text.includes("MDF COMPLETE")) {
                timeOutVar=setTimeout(function() {
                    $self();
                }, 10000);
            }else{
                if (typeof timeOutVar !== 'undefined'){
                    clearTimeout(timeOutVar);
                }
                $('#myLDPrune_review').load("/result_MDF.php?sessionID=<?php echo $sessionID ?>");
            }
          
          //text = text.replace(/\s/g, '');


            $('#mdfruntime').html(text);

        }
      };
      $http.open('GET', 'runtime.php' + '?sessionID=' + string + "&pipeline=mdf&date=" + new Date().getTime(), true);
      $http.send(null);

    }

}

</script>

 <script type="text/javascript">
  //setTimeout(function() {
    mdfAjax();
  //}, 10000);
</script>


<table class="table table-bordered" style="text-align: center" ;>
    <thead>
        <tr>
            <th>Marker Dependency Filtering Job is running</th>
        </tr>
    </thead>
    <tbody>


        <tr>
            <td>
                <div class="loading-window">
                    <div class="DNA_cont">
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                        <div class="nucleobase"></div>
                    </div>

                    <div class="text">
                        <span>Running</span><span class="dots">...</span>
                    </div>
                </div>
                </div>

                <div style="text-align: center;">
                    <h4 class="instructiontext">
                        Marker Dependency Filtering is running on data. You can wait to get the results in about 20 minutes. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=<?php print($sessionID); ?>
                    </h4>
                </div>
            </td>

        </tr>

    </tbody>
</table>

<div class="toggle toggle-border" id="MDFRtToggle">
    <div class="togglet toggleta"><i class="icon-plus-square1" id="runtimeicon"></i>
      <div class="capital">Click to see runtime log</div>
    </div>
    <div class="togglec" id="MDFrt" style="display:none;font-size: 16px;padding-top: 1%;">
        <div id="mdfruntime"></div>
    </div>
</div>

<script type="text/javascript">
var string = "<?php echo $sessionID; ?>"; //gets the sessionID from php and store in js variable 

$("#MDFRtToggle").on('click', function(e){
    var x = document.getElementById("MDFrt");
    if (x.style.display === "none") {
        x.style.display = "block";
        $("#runtimeicon").removeClass("icon-plus-square1");
        $("#runtimeicon").addClass("icon-minus-square");
    }
    else {
        x.style.display = "none";
        $("#runtimeicon").removeClass("icon-minus-square");
        $("#runtimeicon").addClass("icon-plus-square1");
    }
    
    e.preventDefault();
});
</script>


<?php

$email_sent = "./Data/Pipeline/Results/ld_prune_email/$sessionID" . "sent_email";
$email = "./Data/Pipeline/Results/ld_prune_email/$sessionID" . "email";



if ((!(file_exists($email_sent)))) {
    if (file_exists($email)) {
        #PHPMailer has been updated to the most recent version (https://github.com/PHPMailer/PHPMailer)
        #Mail function is written at sendEmail in functions.php - Jan.3.2024 Dan
        $recipient = trim(file_get_contents($email));
        $title="Mergeomics - Marker Dependency Filtering (MDF) Execution Started";
        $body= "Your Marker Dependency Filtering job is running. We will send you a notification with a link to your results after completion.";
        $body.= "\n";
        $body.= "If you close your browser, you can get your results from: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=";
        $body.="$sessionID";
        $body.=" when the pipeline is complete";
        sendEmail($recipient,$title,$body,$email_sent);
 ?>
        <script type="text/javascript">
            $('#myLDPrune_review').load("/result_MDF.php?sessionID=<?php echo $sessionID ?>&run=<?php print($run); ?>");
        </script>
    <?php
    } else {
    ?>

        <script type="text/javascript">
            $('#myLDPrune_review').load("/result_MDF.php?sessionID=<?php echo $sessionID ?>&run=<?php print($run); ?> ");
        </script>

    <?php

    }
} else {
    ?>

    <script type="text/javascript">
        $('#myLDPrune_review').load("/result_MDF.php?sessionID=<?php echo $sessionID ?>&run=<?php print($run); ?>");
    </script>

<?php
}
?>