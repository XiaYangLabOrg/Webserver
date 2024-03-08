<?php
include "functions.php";
//This run files is for when the user runs MSEA
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
/* Initialize PHP variables
sessionID = the saved session 

GET = if the user enters the link directly
POST = if PHP enters the link

*/

if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
}
if (isset($_GET['run'])) {
    $run = $_GET['run'];
}


/* This gets the path of all the parameter files that were created in _parameters.php
    This is where you can change/optimize the code if needed 
*/
if (!file_exists($ROOT_DIR . "/Data/Pipeline/Results/ssea/$sessionID.MSEA_modules_pval.txt") || $run == "T") {
    $msea_json = $ROOT_DIR . "Data/Pipeline/Resources/msea_temp/$sessionID" . "data.json";
    $data = json_decode(file_get_contents($msea_json),true)["data"][0];
    $perm_type = $data["perm"];
    $max_gene = $data["maxgenes"];
    $min_gene = $data["mingenes"];
    $minoverlap = $data["minoverlap"];
    $maxoverlap = $data["maxoverlap"];
    $mseanperm = $data["numperm"];
    $mseafdr = $data["fdrcutoff"];
    $marker_association = $data["association"];
    $mapping = $data["marker"];
    $mdf = $data["mdf"];
    $mdf_ntop = $data["mdf_ntop"];
    $module = $data["geneset"];
    $enrichment = $data["enrichment"];
    $module_info = $data["genedesc"];
    $MAFConvert = $data["MAFConvert"];
    $MMFConvert = $data["MMFConvert"];
    $GSETConvert = $data["GSETConvert"];


    if (!empty($mdf)) {
        $outpath = str_replace($ROOT_DIR . "Data/Pipeline/", "", runMDFscript($sessionID, $marker_association, $mapping, $mdf, $mdf_ntop, "none"));
        $marker_association = $outpath . "marker.txt";
        $mapping = $outpath . "genes.txt";
    }

    //Path of where the R code/file is created
    $fpathOut = $ROOT_DIR . "Data/Pipeline/$sessionID" . "analyze.R";

    //enrichment variable. Will create a mapping file from MARKER if mapping does not exist
    // $enrichment = trim(file_get_contents($fpath1));


    // $associationfile = $ROOT_DIR . "/Data/Pipeline/" . $enrichment; //Association file
    // $checkmapping = $ROOT_DIR . "/Data/Pipeline/Resources/msea_temp/$sessionID" . "MAPPING"; //check if a mapping file exists
    if (empty($mapping)) {
        $mapping = "/Resources/msea_temp/" . $sessionID . "genfile_for_geneEnrichment.txt"; //create fake mapping file
    }

    // $modulefile = trim(file_get_contents($fpath2)); //Gene sets
    // $infofile = trim(file_get_contents($fpath3)); //Gene sets description
    // $fdrfile = trim(file_get_contents($fpath4)); //FDRcutoff

    // $par = file_get_contents($fpathparam); //msea parameters
    // $par .= "\n";

    //store some R variables with the file information 
    $absolute_path = $ROOT_DIR . "Data/Pipeline/";
    $out = "job.msea\$folder <- \"Results\"" . "\n";
    $out .= "job.msea\$permtype <- \"$perm_type\"" . "\n";
    $out .= "job.msea\$maxgenes <- \"$max_gene\"" . "\n";
    $out .= "job.msea\$mingenes <- \"$min_gene\"" . "\n";
    $out .= "rmax<-$minoverlap" . "\n";
    $out .= "job.msea\$maxoverlap <-$maxoverlap" . "\n";
    $out .= "job.msea\$nperm <- $mseanperm" . "\n";
    $out .= "job.msea\$label <- \"$sessionID\"" . "\n"; //label

    if($MAFConvert!=="none"){
        $out .= "MAFConvert <- \"$MAFConvert\"" . "\n"; //label
    }
    if($MMFConvert!=="none"){
        $out .= "MMFConvert <- \"$MMFConvert\"" . "\n"; //label
    }
    if($GSETConvert!=="none"){
        $out .= "GSETConvert <- \"$GSETConvert\"" . "\n"; //label
    }

    $file1 = "job.msea\$genfile <- \"$absolute_path" . "$mapping\""; //genfile (mapping file/path_to_cat_GWAS)
    $file2 = "job.msea\$marfile <- \"$absolute_path" . "$marker_association\""; //marfile (Associationfile)
    $file3 = "job.msea\$modfile <- \"$absolute_path" . "$module\""; //modfile (modulefile)

    //if the user did not select an information file
    if (empty($module_info)) {
        //then set as empty 
        $file4 = "";
    } 
    else if($module_info=="None Provided"){
        $file4 = "";
    }
    else{    //otherwise set the R variable to file4

        $file4 = "job.msea\$inffile <- \"$absolute_path" . "$module_info\"";
    }


    $file5 = "FDR_filter <- (" . "$mseafdr" . "/100)";



    //if a user mapping file does not exist, create some R code to make fake mapping file. We will add this to the generated R file.
    $source .= 'rm(list=ls())'."\n";
    $source = 'source("' . $ROOT_DIR . 'R_Scripts/cle.r")';
    if (!file_exists($mapping)) {
        $add = 'file <- "' . $marker_association . '"
            marker_associations <- read.delim(file, stringsAsFactors = FALSE)
            marker_associations_base = unlist(strsplit(file, split = "/"))[length(unlist(strsplit(file, split = "/")))]'."\n";
        $add2 = 'if(file.exists(paste0("'.$ROOT_DIR.'Data/Pipeline/tmpFileEncoding/",marker_associations_base))){
                cat("genfile created in upload step\n")
            } else {
                genfile = data.frame("GENE"=unique(marker_associations$MARKER), "MARKER" = unique(marker_associations$MARKER), stringsAsFactors = FALSE)
                write.table(genfile, "' . $ROOT_DIR . '/Data/Pipeline/Resources/msea_temp/' . $sessionID . 'genfile_for_geneEnrichment.txt", row.names = FALSE, quote = FALSE, sep = "\t")
                system("chmod +x ' . $ROOT_DIR . 'Data/Pipeline/Resources/msea_temp/' . $sessionID . 'genfile_for_geneEnrichment.txt")
            }';
    }

    //add additional R code that is needed
    //You can put it in this page if you dont want to use .txt files
    $h = file_get_contents("./Data/Pipeline/Resources/part1_MSEA.txt");
    $m = file_get_contents("./Data/Pipeline/Resources/part2.txt");
    //$t=file_get_contents("./Data/Pipeline/Resources/part3.txt");


    //combine the variables into 1
    $data = $out . "\n" . $file1 . "\n" . $file2 . "\n" . $file3 . "\n" . $file4 . "\n" . $file5 . "\n";


    //start creating the R file
    $fp = fopen($fpathOut, "w");
    fwrite($fp, $source . "\n");
    if (!file_exists($mapping)) { //if a user mapping file does not exist, add R code to make fake mapping file
        //fwrite($fp, $add . "\n" . $add2 . "\n" . $add3 . "\n" . $add4 . "\n");
        fwrite($fp, $add . "\n" . $add2 ."\n");
    }

    fwrite($fp, $h . "\n");
    //fwrite($fp, $par);
    fwrite($fp, $data);
    fwrite($fp, $m);
    fclose($fp);

    chmod($fpathOut, 0777);  //change permissions to 777. I think 777 is too much. Probably could change it to 644, but check if it executes
}

/***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we have a txt file with the path information
 ***************************************/


$fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
//$fpostOut = "./Data/Pipeline/Resources/msea_temp/$sessionID" . "_MSEA_postdata.txt";
if (file_exists($fsession)) {

    $session = explode("\n", file_get_contents($fsession));
    //Create different array elements based on new line
    $pipe_arr = preg_split("/[\t]/", $session[0]);
    $pipeline = $pipe_arr[1];

    if ($pipeline == "MSEA") //check if the pipeline is MSEA. Probably not needed for this pipeline
    {
        $data = file($fsession); // reads an array of lines
        function replace_a_line($data)
        {
            if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.25")) { //change from 1.25 --> 1.5
                return 'Mergeomics_Path:' . "\t" . "1.5" . "\n";
            }
            return $data;
        }
        $data = array_map('replace_a_line', $data);
        file_put_contents($fsession, implode('', $data));
    }
}

?>

<script type="text/javascript">

function kda2networkAjaxtest() {
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
          //text = text.replace(/\s/g, '');
            if(!text.includes("MSEA COMPLETE") || !text.includes("Execution Halted")) {
                timeOutVar=setTimeout(function() {
                    $self();
                }, 10000);   
            }else{
                clearTimeout(timeOutVar);
                $('#myMSEA_review').load("/result_SSEA.php?sessionID=" + string + "&rmchoice=2");
            }
          

            $('#msearuntime').html(text);

        }
      };
      $http.open('GET', 'runtime.php' + '?sessionID=' + string + "&pipeline=msea&date=" + new Date().getTime(), true);
      $http.send(null);

    }

}

</script>

 <script type="text/javascript">
  setTimeout(function() {
    kda2networkAjaxtest();
  }, 50);
</script>

<!-- Job running table ===================================================== -->
<table class="table table-bordered" style="text-align: center" ;>
    <thead>
        <tr>
            <th>Marker Set Enrichment Analysis Job is running</th>
        </tr>
    </thead>
    <tbody>


        <tr>
            <td>

                <!-- Loading window ===================================================== -->
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
                        MSEA is running on data. You can wait to get the results in about 30 minutes. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=<?php print($sessionID); ?>
                    </h4>
                </div>
            </td>

        </tr>

    </tbody>
</table>

<div class="toggle toggle-border" id="MSEARtToggle">
    <div class="togglet toggleta"><i class="icon-plus-square1" id="runtimeiconmsea"></i>
      <div class="capital">Click to see runtime log</div>
    </div>
    <div class="togglec" id="MSEArt" style="display:none;font-size: 16px;padding-top: 1%;">
        <div id="msearuntime"></div>
    </div>
</div>

<script type="text/javascript">
var string = "<?php echo $sessionID; ?>"; //gets the sessionID from php and store in js variable 

$("#MSEARtToggle").on('click', function(e){
    var x = document.getElementById("MSEArt");
    if (x.style.display === "none") {
        x.style.display = "block";
        $("#runtimeiconmsea").removeClass("icon-plus-square1");
        $("#runtimeiconmsea").addClass("icon-minus-square");
    }
    else {
        x.style.display = "none";
        $("#runtimeiconmsea").removeClass("icon-minus-square");
        $("#runtimeiconmsea").addClass("icon-plus-square1");
    }

    e.preventDefault();
});
</script>

<?php
/*
Email php block'

*/
$email_sent = "./Data/Pipeline/Results/msea_email/$sessionID" . "sent_email";
$email = "./Data/Pipeline/Results/msea_email/$sessionID" . "email";


//check if the email has been sent
if ((!(file_exists($email_sent)))) {
    //check if the email exists
    if (file_exists($email)) {
        #PHPMailer has been updated to the most recent version (https://github.com/PHPMailer/PHPMailer)
        #Mail function is written at sendEmail in functions.php - Jan.3.2024 Dan
        $recipient = trim(file_get_contents($email));
        $title = "Mergeomics - Marker Set Enrichment Analysis (MSEA) Execution Started";
        $body  = "Your Marker Set Enrichment Analysis job is running. We will send you a notification with a link to your results after completion.\n";
        $body .= "If you close your browser, you can get your results from: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=";
        $body .= "$sessionID";
        $body .= " when the pipeline is complete";
        sendEmail($recipient,$title,$body,$email_sent);
?>
        <script>
            //once the email has been sent, go to result page
            $('#myMSEA_review').load("/result_SSEA.php?sessionID=" + string + "&rmchoice=2&run=<?php print($run) ?>"); //go to the result SSEA w/ choice 2 (MSEA)
        </script>
    <?php
    } else {
    ?>
        <script>
            //if the email has already been sent, still go to result page
            $('#myMSEA_review').load("/result_SSEA.php?sessionID=" + string + "&rmchoice=2&run=<?php print($run) ?>"); //go to the result SSEA w/ choice 2 (MSEA)
        </script>

    <?php

    }
} else {
    ?>
    <script>
        //if no email, still go to result page
        $('#myMSEA_review').load("/result_SSEA.php?sessionID=" + string + "&rmchoice=2&run=<?php print($run) ?>"); //go to the result SSEA w/ choice 2 (MSEA)
    </script>

<?php
}
?>