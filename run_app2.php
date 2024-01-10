<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";

if (isset($_POST['sessionID'])) {
    $sessionID = $_POST['sessionID'];
}

if (isset($_POST['signature_select'])) {
    $signature = $_POST['signature_select'];
}

if (isset($_POST['network_select']) and isset($_POST['species_select'])) {
    $network = $_POST['network_select'];
    $species = $_POST['species_select'];
    if ($network == 1) {
        $network_str = $ROOT_DIR . "Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_network.txt";
        if ($species == 1) { // custom upload and human
            $file2 = "network_file <- \"" . $network_str . "\"" . "\n" .
                "species <- \"Human\"";
        } else { // custom upload and mouse
            $file2 = "network_file <- \"" . $network_str . "\"" . "\n" .
                "species <- \"Mouse\"";
        }
    } else {
        if ($network == 2) {
            $network_str = "tissue <- \"liver\"\n";
        } else if ($network == 3) {
            $network_str = "tissue <- \"kidney\"\n";
        } else {
            $network_str = "tissue <- \"combined\"\n";
        }

        if ($species == 1) {
            $file2 = $network_str . "species <- \"Human\"";
        } else {
            $file2 = $network_str . "species <- \"Mouse\"";
        }
    }
}

if (isset($_POST['inputgenes'])) {
    $input = $_POST['inputgenes'];
}


//$input = str_replace("\r\n", '\n', $input);
$input = explode("\n", $input);
$final = "GENE\n";

foreach ($input as $line) {
    $final .= "$line" . "\n";
}
$filename = "./Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_genes.txt";
$f = fopen($filename, 'w');
fwrite($f, $final);
fclose($f);

//$fpath2 = $network_str; //change to user input
//$file2 = json_encode($fpath2); 


//$file1 = "Genes <- read.delim(\"/home/www/abhatta3-webserver/Data/Pipeline/Resources/shinyapp2_temp/$sessionID"."_genes.txt\", stringsAsFactors = FALSE)\nGenes <- Genes\$GENE";


$file3 = "sessionID <- \"$sessionID" . "\"";


if ($signature == 1) {
    $analysis = file_get_contents("./Data/Pipeline/Resources/app2_analysis_meta");
} else {
    if ($signature == 2) {
        $file2 .= "\n" . "gene_signatures <- \"top500\"\n";
    } else {
        $file2 .= "\n" . "gene_signatures <- \"all\"\n";
    }
    $analysis = file_get_contents("./Data/Pipeline/Resources/app2_analysis_seg");
}

//$data = $file1 . "\n" . $file2 . "\n" . $file3 . "\n";
$data = $file2 . "\n" . $file3 . "\n";


//$output = "\nwrite.table(tableresult, " . '"' . "" . $ROOT_DIR . "Data/Pipeline/Results/shinyapp2/$sessionID" . '_app2result.txt", ' . "row.names=FALSE, quote = FALSE, sep =" . '"\t")';

if ($signature == 1) {
    $fpathOut = "./Data/Pipeline/$sessionID" . "app2.R";
} else {
    $fpathOut = "./Data/Pipeline/$sessionID" . "_app2_seg.R";
}


$fp = fopen($fpathOut, "w");
fwrite($fp, $data);
fwrite($fp, $analysis);
//fwrite($fp, $output);
fclose($fp);
chmod($fpathOut, 0777);

if ($signature == 2 or $signature == 3) {
    //append to file that this type of signature has run
    $dose_seg_runs_file = "./Data/Pipeline/Resources/shinyapp2_temp/Dose_seg_runs" . date("Y.m.d") . ".txt";
    if (file_exists($dose_seg_runs_file)) {
        shell_exec("echo \"$sessionID" . "\" >> " . $dose_seg_runs_file);
    } else {
        shell_exec("echo \"$sessionID" . "\" > " . $dose_seg_runs_file);
    }

    // move files to hoffman2 server
    shell_exec("sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp ".$fpathOut. " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/");
    shell_exec("sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp ".$filename. " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/");
    // move network if user uploaded
    if ($network == 1) {
        shell_exec("sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp " . $network_str . " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/");
    }
}


?>

<script type="text/javascript">
    function app2Ajax() {
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
                    text = text.replace(/\s/g, '');

                    if (text.indexOf("100%") == -1) {
                        setTimeout(function() {
                            $self();
                        }, 10000);

                    }
                    $('#app2progresswidth').width(text);
                    $('#app2progresspercent').html(text);
                }
            };
            $http.open('GET', 'pharmomics_loadbar.php' + '?sessionID=' + string + "&date=" + new Date().getTime(), true);
            $http.send(null);

        }

    }
</script>

<script type="text/javascript">
    var signature = "<?php echo $signature; ?>";
    console.log("signature:" + signature);
    if (signature == 1) {
        //setTimeout(function() {
        app2Ajax();
        //}, 100);
    }
</script>


<!-- Description ===================================================== -->
<table class="table table-bordered" style="text-align: center;">
    <thead>
        <tr>
            <th>Network Based Drug Repositioning Job is running</th>
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
                <?php
                if ($signature == 1) { ?>
                    <div id="app2progressbar" class="progress active">
                        <div id="app2progresswidth" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                            <span id="app2progresspercent"></span>
                        </div>
                    </div>

                    <div style="text-align: center;">
                        <h4 class="instructiontext">
                            You can wait to get the results in about 40 minutes. This estimate will vary based on input data size and server load. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runpharmomics.php?sessionID=<?php print($sessionID); ?>
                        </h4>
                    </div>
                <?php
                } else if ($signature == 2) { ?>
                    <div style="text-align: center;">
                        <h4 class="instructiontext">
                            You can wait to get the results in about 2 hours. This estimate will vary based on input data size and server load. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runpharmomics.php?sessionID=<?php print($sessionID); ?>
                        </h4>
                    </div>
                <?php
                } else { ?>
                    <div style="text-align: center;">
                        <h4 class="instructiontext">
                            You can wait to get the results in about 4 hours. This estimate will vary based on input data size and server load. You can also try our meta signatures or dose/time segregated top 500 genes for a faster analysis. If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runpharmomics.php?sessionID=<?php print($sessionID); ?>
                        </h4>
                    </div>
                <?php
                }
                ?>
            </td>

        </tr>

    </tbody>
</table>
<?php

$email_sent = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "sent_email";
$email = "./Data/Pipeline/Results/shinyapp2_email/$sessionID" . "email";



if ((!(file_exists($email_sent)))) {
    if (file_exists($email)) {
        $recipient = trim(file_get_contents($email));
        $title = "Network Based Drug Repositioning Execution Started";
        $body  = "Your Network Based Drug Repositioning job is running. We will send you a notification with a link to your results after completion.\n";
        $body .= "If you close your browser, you can get your results from: http://".$_SERVER["HTTP_HOST"]."/runpharmomics.php?sessionID=";
        $body .= "$sessionID";
        $body .= " when the pipeline is complete";
        sendEmail($recipient,$title,$body,$email_sent);
    }
}
?>
<script type="text/javascript">
    var string = "<?php echo $sessionID; ?>";
    var signature = "<?php echo $signature; ?>";
    loadData();

    function loadData() {
        $.ajax({
            url: "result_shinyapp2.php",
            method: "GET",
            sync: false,
            data: {
                sessionID: string,
                type: "pharm",
                signature: signature,
            },
            success: function(data) {
                if (data.includes("Not ready!!")) {
                    setTimeout(function() {
                        loadData();
                    }, 5000)
                } else {
                    $('#myAPP2_run').html(data);
                }
            }
        })
        // $.ajax({
        //     type: "GET",
        //     url: "Product/GetProduct",
        //     dataType: "json",
        //     success: function(data) {
        //         //alert("succes");
        //         $("#name").html(data.Name);
        //         $("#price").html(data.Price);
        //     },
        //     error: function() {
        //         //alert("fail");
        //         //callback getMyJson here in 5 seconds
        //         setTimeout(function() {
        //             getMyJson();
        //         }, 5000)
        //     }
        // });
    }
</script>