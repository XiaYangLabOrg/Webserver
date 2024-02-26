<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
}

if (isset($_GET['rmchoice'])) {
    $rmchoice = $_GET['rmchoice'];
}

if (isset($_GET['run'])) {
  $run = $_GET['run'];
}

$start = 'cat("0%' . '\n")';
//$start1 = 'cat("10%'.'\n")';
$start1 = 'cat("2%' . '\n")';
//$start2 = 'cat("20%'.'\n")';
//$start3 = 'cat("30%'.'\n")';
$start4 = 'cat("80%' . '\n")';
$start5 = 'cat("100%' . '\n")';

//$fpath1="./Data/Pipeline/Resources/shinyapp3_temp/$sessionID".".SSEA2PHARM_genes.txt"; 
$fpath1 = $ROOT_DIR."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".SSEA2PHARM_genes.txt";

$fpathOut = $ROOT_DIR."Data/Pipeline/$sessionID" . "app3.R"; # change to pharmomics folder

//$file1 = json_encode(trim(file_get_contents($fpath1))); # hopefully this just outputs one string?
$file2 = '""';

//$file1 = str_replace("\/","/",$file1);

//$file1 = "Genes_up <- unlist(strsplit(".$file1.",".'"\n|\t|,| "'."))";
$file1 = "Genes_up <- read.delim(\"" . $fpath1 . "\", stringsAsFactors = FALSE, header=FALSE)\n
colnames(Genes_up) <- \"GENE\"
Genes_up <- unique(Genes_up\$GENE)";
$file2 = "Genes_down <- unlist(strsplit(" . $file2 . "," . '"\n|\t|,| "' . "))";

$data = $file1 . "\n" . $file2 . "\n";

//$analysis=file_get_contents("./R_Scripts/app3_analysis");
$analysis = file_get_contents($ROOT_DIR."Data/Pipeline/Resources/app3_analysis");

#write.table(result, "app3result.txt", row.names=FALSE, quote = FALSE, sep = "\t")

$output = "\nwrite.table(result, " . '"' . $ROOT_DIR."Data/Pipeline/Results/shinyapp3/$sessionID" . '.SSEA2PHARM_app3result.txt", ' . "row.names=FALSE, quote = FALSE, sep =" . '"\t")';
$fp = fopen($fpathOut, "w");
//fwrite($fp, "\n".$start."\n");
fwrite($fp, "\n" . $start1 . "\n");
//fwrite($fp, "\n".$start2."\n");
fwrite($fp, $data);
//fwrite($fp, "\n".$start3."\n");
fwrite($fp, $analysis);
//fwrite($fp, "\n".$start4."\n");
fwrite($fp, $output);
fwrite($fp, "\n".$start5."\n");
fclose($fp);
chmod($fpathOut, 0777);

$fsession = $ROOT_DIR."Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
if (file_exists($fsession)) {
    function replace_a_line($data, $rmchoice)
    {
        if (strpos($data, 'Pharmomics_Path') !== false) {
            $pharmomics_arr = preg_split("/[\t]/", $data);
            $pharmomics_arr2 = explode("|", $pharmomics_arr[1]);
            //$msea2pharmomics = $pharmomics_arr2[0];
            $kda2pharmomics = preg_replace('/\s+/', ' ', trim($pharmomics_arr2[1]));
            if ($rmchoice == 1) {
                return 'Pharmomics_Path:' . "\t" . "SSEAtoPharmomics,1.50|" . $$kda2pharmomics . "\n";
            } else {
                return 'Pharmomics_Path:' . "\t" . "MSEAtoPharmomics,1.50|" . $kda2pharmomics . "\n";
            }
        }
        return $data;
    }
    //$data = file($fsession); // reads an array of lines
    $handle = fopen($fsession, "r");
    $new_session_contetns = "";
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $new_session_contetns .= replace_a_line($line, $rmchoice);
        }
        fclose($handle);
    }
    if (!empty($new_session_contetns)) {
        file_put_contents($fsession, $new_session_contetns);
    }
}
?>
<script type="text/javascript">
    var sessionID="<?php echo $sessionID; ?>";
    var rmchoice="<?php echo $rmchoice; ?>";
    var run="<?php echo $run; ?>";
    function ssea2jaccardAjax() {
        var $http;
        var text;
        var $self = arguments.callee;
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
                    text = text.replace(/\s/g, '');
                    if (!text.includes("100%")){
                        timeOutVar=setTimeout(function() {
                        $self();
                        }, 10000);
                    }else{
                        clearTimeout(timeOutVar);
                        if(rmchoice==1){
                            $("#myssea2pharm_review").load("/result_shinyapp3.php?sessionID=" + sessionID + "&type=ssea");
                        }else if(rmchoice==2){
                            $("#mymsea2pharm_review").load("/result_shinyapp3.php?sessionID=" + sessionID + "&type=msea");
                        }else{
                            $("#myMETAMSEA2PHARM_review").load("/result_shinyapp3.php?sessionID=" + sessionID + "&type=ssea");
                        }

                    }
                    $('#ssea2jaccardprogresswidth').width(text);
                    $('#ssea2jaccardprogresspercent').html(text);
                }
            };
            $http.open('GET', 'pharmomics_loadbar.php' + '?sessionID=' + sessionID + "&date=" + new Date().getTime(), true);
            $http.send(null);
        }
    }
</script>

<script type="text/javascript">
  ssea2jaccardAjax();
</script>



<!-- Description ===================================================== -->
<table class="table table-bordered" style="text-align: center;">
    <thead>
        <tr>
            <th>Overlap Based Drug Repositioning Job is running</th>
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

                <div id="ssea2jaccardprogressbar" class="progress active">
                    <div id="ssea2jaccardprogresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span id="ssea2jaccardprogresspercent"></span>
                    </div>
                </div>

                <div style="text-align: center;">
                    <h4 class="instructiontext">
                        PharmOmics is running on data. You can wait to get the results in about 10 minutes. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=<?php print($sessionID); ?>
                    </h4>
                </div>
            </td>

        </tr>

    </tbody>
</table>

<script type="text/javascript">

    if(rmchoice==1){
        console.log("haha")
        console.log(rmchoice);
        $('#myssea2pharm_review').load("/result_shinyapp3.php?sessionID=" + sessionID + "&type=ssea&run="+run);
    }else if(rmchoice==2){
        console.log("haha2")
        console.log(rmchoice);
        $('#mymsea2pharm_review').load("/result_shinyapp3.php?sessionID=" + string + "&type=msea&run="+run);
    }else{
        console.log("haha3")
        console.log(rmchoice);
        $('#myMETAMSEA2PHARM_review').load("/result_shinyapp3.php?sessionID=" + string + "&type=ssea&run="+run);
    }
</script>