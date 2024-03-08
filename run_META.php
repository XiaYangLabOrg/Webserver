<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['sessionID'])) {
    $sessionID = $_GET["sessionID"];
}

if (isset($_GET['metasessionID'])) {
    $meta_sessionID = $_GET["metasessionID"];
}

if (isset($_GET['module'])) {
    $module = $_GET["module"];
}

if (isset($_GET['module_info'])) {
    $module_info = $_GET["module_info"];
}

if (isset($_GET['max_gene'])) {
    $max_gene = $_GET["max_gene"];
}
if (isset($_GET['min_gene'])) {
    $min_gene = $_GET["min_gene"];
}

if (isset($_GET['minoverlap'])) {
    $minoverlap = $_GET["minoverlap"];
}

if (isset($_GET['metafdr'])) {
    $metafdr = $_GET["metafdr"];
}

if (isset($_GET['GSETConvert'])) {
    $GSETConvert = $_GET["GSETConvert"];
} else{
    $GSETConvert = "none";
}

$fjson = "./Data/Pipeline/Resources/meta_temp/$meta_sessionID" . "metaparam.json";

if (!file_exists($fjson)) {

    $json = array();

    $json['session'] = $meta_sessionID;
    $json['maxgenes'] = $max_gene;
    $json['mingenes'] = $min_gene;
    $json['minoverlap'] = $minoverlap;
    $json['fdrcutoff'] = $metafdr;
    $json['geneset'] =  $module;
    $json['GSETConvert'] =  $GSETConvert;

    if ($module_info == "no") {
        $module_info = "None Provided";
    }
    $json['genedesc'] = $module_info;


    $data['data'][] = $json;

    $fp = fopen($fjson, 'w');
    fwrite($fp, json_encode($data));
    fclose($fp);
    chmod($fjson, 0777);
}


/***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we have a txt file with the path information
 ***************************************/


$fsession = $ROOT_DIR . "Data/Pipeline/Resources/session/$meta_sessionID" . "_session.txt";
if (file_exists($fsession)) {
    $session = explode("\n", file_get_contents($fsession));
    //Create different array elements based on new line
    $pipe_arr = preg_split("/[\t]/", $session[0]);
    $pipeline = $pipe_arr[1];

    if ($pipeline == "META") //check if the pipeline is MSEA. Probably not needed for this pipeline
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
$email_sent = "./Data/Pipeline/Results/meta_email/$meta_sessionID" . "sent_email";
$email = "./Data/Pipeline/Results/meta_email/$meta_sessionID" . "email";

if ((!(file_exists($email_sent)))) {
    if (file_exists($email)) {
        #PHPMailer has been updated to the most recent version (https://github.com/PHPMailer/PHPMailer)
        #Mail function is written at sendEmail in functions.php - Jan.3.2024 Dan
        $recipient = trim(file_get_contents($email));
        $title = "Mergeomics - Meta Marker Set Enrichment Analysis (META-MSEA) Execution started";
        $body  = "Your META-MSEA job is running. We will send you a notification with a link to your results after completion.\n";
        $body .= "If you close your browser, you can get your results from: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=";
        $body .= "$sessionID";
        $body .= " when the pipeline is complete";
        sendEmail($recipient,$title,$body,$email_sent);
    }
}

?>

<script type="text/javascript">
    function kda2networkAjaxtest() {
        var
            $http,
            text,
            $self = arguments.callee;
        var string = "<?php echo $meta_sessionID; ?>";
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
                    if (!text.includes("META-MSEA COMPLETE") || !text.includes("Execution Halted")) {
                        timeOutVar=setTimeout(function() {
                            $self();
                        }, 10000);
                    }else{
                        clearTimeout(timeOutVar);
                        $('#myMETA_review').load("/result_META.php?metasessionID=" + meta_sessionId + "&sessionID=" + sessonId+"&sessionload=T");
                    }
                    $('#metaruntime').html(text);

                }
            };
            $http.open('GET', 'runtime.php' + '?sessionID=' + string + "&pipeline=meta&date=" + new Date().getTime(), true);
            $http.send(null);

        }

    }
</script>

<script type="text/javascript">
    kda2networkAjaxtest();
</script>

<table class="table table-bordered" style="text-align: center" ;>
    <thead>
        <tr>
            <th>Meta-Marker Set Enrichment Analysis Job is running</th>
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
                        META-MSEA is running on data. You can wait to get the results in about 30 minutes to 2 hours (depending on amount of inputs). If you chose to run MDF, the run will be longer.If you close your browser then you can get your results from http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=<?php print($meta_sessionID); ?>
                    </h4>
                </div>
            </td>

        </tr>

    </tbody>
</table>

<div class="toggle toggle-border" id="MetaRtToggle">
    <div class="togglet toggleta"><i class="icon-plus-square1" id="runtimeiconmeta"></i>
        <div class="capital">Click to see runtime log</div>
    </div>
    <div class="togglec" id="Metart" style="display:none;font-size: 16px;padding-top: 1%;">
        <div id="metaruntime"></div>
    </div>
</div>


<script type="text/javascript">
    var sessonId = "<?php echo $sessionID; ?>";
    var meta_sessionId = "<?php echo $meta_sessionID; ?>";
    $('#myMETA_review').load("/result_META.php?metasessionID=" + meta_sessionId + "&sessionID=" + sessonId);

    $("#MetaRtToggle").on('click', function(e) {
        var x = document.getElementById("Metart");
        if (x.style.display === "none") {
            x.style.display = "block";
            $("#runtimeiconmeta").removeClass("icon-plus-square1");
            $("#runtimeiconmeta").addClass("icon-minus-square");
        }
        else {
            x.style.display = "none";
            $("#runtimeiconmeta").removeClass("icon-minus-square");
            $("#runtimeiconmeta").addClass("icon-plus-square1");
        }

        e.preventDefault();
    });
</script>