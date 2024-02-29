<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
$env=parse_ini_file(".env");

if (isset($_POST['sessionID'])) {
    $sessionID = $_POST['sessionID'];
}

if (isset($_POST['signature_select'])) {
    $signature = $_POST['signature_select'];
}
$file2="";
if (isset($_POST['network_select']) && isset($_POST['species_select'])) {
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
$filename = $ROOT_DIR."Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_genes.txt";
$f = fopen($filename, 'w');
fwrite($f, $final);
fclose($f);



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
    $fpathOut = $ROOT_DIR."Data/Pipeline/$sessionID" . "app2.R";
} else {
    $fpathOut = $ROOT_DIR."Data/Pipeline/$sessionID" . "_app2_seg.R";
}

$sshpass_cmd_in_R="cat(paste0(\"~/bin/sshpass -p '".$env["MERGEOMICS_SERVER_PASSWORD"]."' scp -r \", drugNetworksDir,\" ".$env["MERGEOMICS_SERVER_USERNAME"]."@".$env["MERGEOMICS_SERVER_IP"].":/var/www/mergeomics/html/Data/Pipeline/Resources/shinyapp2_temp/\", folder,\"/\n\"))" . "\n".
                  "system(paste0(\"~/bin/sshpass -p '".$env["MERGEOMICS_SERVER_PASSWORD"]."' scp -r \", drugNetworksDir,\" ".$env["MERGEOMICS_SERVER_USERNAME"]."@".$env["MERGEOMICS_SERVER_IP"].":/var/www/mergeomics/html/Data/Pipeline/Resources/shinyapp2_temp/\", folder,\"/\"))" ."\n".
                  "cat(paste0(\"~/bin/sshpass -p '".$env["MERGEOMICS_SERVER_PASSWORD"]."' scp \", data_dir, sessionID,\"_app2result.txt ".$env["MERGEOMICS_SERVER_USERNAME"]."@".$env["MERGEOMICS_SERVER_IP"].":/var/www/mergeomics/html/Data/Pipeline/Results/shinyapp2/\",sessionID,\"_app2result.txt\"))"."\n".
                  "system(paste0(\"~/bin/sshpass -p '".$env["MERGEOMICS_SERVER_PASSWORD"]."' scp \", data_dir, sessionID,\"_app2result.txt ".$env["MERGEOMICS_SERVER_USERNAME"]."@".$env["MERGEOMICS_SERVER_IP"].":/var/www/mergeomics/html/Data/Pipeline/Results/shinyapp2/\",sessionID,\"_app2result.txt\"))" ."\n".
                  "cat(paste0(\"~/bin/sshpass -p '".$env["MERGEOMICS_SERVER_PASSWORD"]."' scp \", data_dir, sessionID,\"_app2result_hepatotox.txt ".$env["MERGEOMICS_SERVER_USERNAME"]."@".$env["MERGEOMICS_SERVER_IP"].":/var/www/mergeomics/html/Data/Pipeline/Results/shinyapp2/\",sessionID,\"_app2result_hepatotox.txt\"))"."\n".
                  "system(paste0(\"~/bin/sshpass -p '".$env["MERGEOMICS_SERVER_PASSWORD"]."' scp \", data_dir, sessionID,\"_app2result_hepatotox.txt ".$env["MERGEOMICS_SERVER_USERNAME"]."@".$env["MERGEOMICS_SERVER_IP"].":/var/www/mergeomics/html/Data/Pipeline/Results/shinyapp2/\",sessionID,\"_app2result_hepatotox.txt\"))" ."\n".
                  "cat(paste0(\"touch \", data_dir, sessionID, \"_is_done\"))"."\n".
                  "system(paste0(\"touch \", data_dir, sessionID, \"_is_done\"))"."\n".
                  "cat(paste0(\"~/bin/sshpass -p '".$env["MERGEOMICS_SERVER_PASSWORD"]."' scp \", data_dir, sessionID, \"_is_done ".$env["MERGEOMICS_SERVER_USERNAME"]."@".$env["MERGEOMICS_SERVER_IP"].":/var/www/mergeomics/html/Data/Pipeline/Resources/shinyapp2_temp/\",sessionID, \"_is_done\"))"."\n".
                  "system(paste0(\"~/bin/sshpass -p '".$env["MERGEOMICS_SERVER_PASSWORD"]."' scp \", data_dir, sessionID, \"_is_done ".$env["MERGEOMICS_SERVER_USERNAME"]."@".$env["MERGEOMICS_SERVER_IP"].":/var/www/mergeomics/html/Data/Pipeline/Resources/shinyapp2_temp/\",sessionID, \"_is_done\"))"."\n";
                  
                  
                  
                  
$fp = fopen($fpathOut, "w");
fwrite($fp, $data);
fwrite($fp, $analysis);
fwrite($fp,$sshpass_cmd_in_R);
//fwrite($fp, $output);
fclose($fp);
chmod($fpathOut, 0777);

if ($signature == 2 or $signature == 3) {
    //append to file that this type of signature has run
    $dose_seg_runs_file = $ROOT_DIR."Data/Pipeline/Resources/shinyapp2_temp/Dose_seg_runs" . date("Y.m.d") . ".txt";
    if (file_exists($dose_seg_runs_file)) {
        shell_exec("echo \"$sessionID" . "\" >> " . $dose_seg_runs_file);
    } else {
        shell_exec("echo \"$sessionID" . "\" > " . $dose_seg_runs_file);
    }

    // move files to hoffman2 server
    #echo "sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp ".$fpathOut. " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/"."\n";
    #echo "sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp ".$filename. " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/"."\n";
    $logfile="./Data/Pipeline/Resources/shinyapp2_temp/$sessionID"."logfile.txt";
    $connection = ssh2_connect($env["HOFFMAN2_SERVER_IP"], 22);
    ssh2_auth_password($connection, $env["PHARMOMICS_USERNAME"], $env["PHMARMOMICS_PASSWORD"]);
    ssh2_scp_send($connection, $fpathOut, '/u/scratch/m/mergeome/app2seg/'.basename($fpathOut), 0644);
    ssh2_scp_send($connection, $filename, '/u/scratch/m/mergeome/app2seg/'.basename($filename), 0644);

    // shell_exec("sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp ".$fpathOut. " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/ | tee " . $logfile);
    // shell_exec("sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp ".$filename. " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/ | tee " . $logfile);
    // move network if user uploaded
    if ($network == 1) {
        #echo "sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp " . $network_str . " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/";
        ssh2_scp_send($connection, $network_str, '/u/scratch/m/mergeome/app2seg/'.basename($network_str), 0644);
        #shell_exec("sshpass -p \"".$env["PHMARMOMICS_PASSWORD"]."\" scp " . $network_str . " ".$env["PHARMOMICS_USERNAME"]."@".$env["HOFFMAN2_SERVER_IP"].":/u/scratch/m/mergeome/app2seg/ | tee " . $logfile);
    }
}


?>

<script type="text/javascript">
    function app2Ajax() {
        var $http,
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
                    console.log(text);
                    if (!text.includes("100%")) {
                        timeOutVar=setTimeout(function() {
                            $self();
                        }, 10000);
                    }else{
                        if (typeof timeOutVar !== 'undefined'){
                            clearTimeout(timeOutVar);
                        }
                        $('#myAPP2_run').load("/result_shinyapp2.php?sessionID="+string+"&type=pharm&signature="+signature);
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
    // if (signature == 1) {
        //setTimeout(function() {
        app2Ajax();
        //}, 100);
    // }
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
    localStorage.setItem("on_load_session", string);
    $('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + string);
    $('#session_id').css("padding", "17px 30px");
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

    loadData();
</script>