<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); 
#App2 contains large result files, which throws fatal error for table to load. This is temporary fix. Will have to apply memory efficient loading in the future.
ini_set('memory_limit', '-1');
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
$env=parse_ini_file(".env");
function scientificNotation($val)
{
  $exp = floor(log($val, 10));
  if ($val == 0) {
    return 0;
  } else {
    return sprintf('%.4fE%+03d', $val / pow(10, $exp), $exp);
  }
}


if (isset($_GET['sessionID'])) {
  $sessionID = $_GET['sessionID'];
  $fsession = "./Data/Pipeline/Resources/session/$sessionID" . "_session.txt";
  if (file_exists($fsession)) {
    $data = file($fsession); // reads an array of lines
    $data_old = $data;
    function replace_a_line($data)
    {
      if (stristr($data, 'Pharmomics_Path:' . "\t" . "4.5")) {
        return 'Pharmomics_Path:' . "\t" . "4.75" . "\n";
      }
      if (stristr($data, 'Pharmomics_Path:' . "\t" . "1.0")) {
        return 'Pharmomics_Path:' . "\t" . "1.25" . "\n";
      }
      return $data;
    }
    $data = array_map('replace_a_line', $data);
    if($data != $data_old){
      file_put_contents($fsession, implode('', $data));
    }

    $session_write = NULL;
  }
}

if (isset($_GET['type']) ? $_GET['type'] : null) {

  $type = $_GET['type'];
  if ($type == 'ssea' || $type == 'msea') {
    $resultfile =$ROOT_DIR ."Data/Pipeline/Results/shinyapp3/$sessionID" . ".SSEA2PHARM_app3result.txt";
    $genefile = $ROOT_DIR ."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".SSEA2PHARM_genes.txt";
  } else {
    $resultfile =$ROOT_DIR . "Data/Pipeline/Results/shinyapp3/$sessionID" . ".KDA2PHARM_app3result.txt";
    $genefile = $ROOT_DIR ."Data/Pipeline/Resources/shinyapp3_temp/$sessionID" . ".KDA2PHARM_genes.txt";
  }

  if ($type == 'pharm') {
    $resultfile = $ROOT_DIR ."Data/Pipeline/Results/shinyapp3/$sessionID" . "_app3result.txt";
    $resultfiletox = $ROOT_DIR ."Data/Pipeline/Results/shinyapp3/$sessionID" . "_app3result_hepatotox.txt";
  }
}


if (isset($_GET['run'])) {
  $run = $_GET['run'];
}



if (isset($_GET['run'])) {
  if($run=='T'){
    $outfile =$ROOT_DIR . "Data/Pipeline/Results/shinyapp3/" . $sessionID . "out.txt";
    shell_exec("sh run_app3.sh $sessionID | tee " . $outfile);
    sleep(1);
    if (file_exists($outfile)) {
      unlink($outfile);
    }
    chmod($resultfile, 0777);
  }
}
else if($type == 'pharm'){
  if (!file_exists($resultfile)) {
    $outfile = $ROOT_DIR ."Data/Pipeline/Results/shinyapp3/" . $sessionID . "out.txt";
    shell_exec("sh run_app3.sh $sessionID | tee " . $outfile);
    sleep(1);
    if (file_exists($outfile)) {
      unlink($outfile);
    }
    chmod($resultfile, 0777);
  }
}


debug_to_console($resultfile);
?>



<table class="table table-bordered review" style="text-align: center" id="shinyapp3resultstable">
  <thead>
    <tr>
      <th colspan="2">
        Download Output Files
      </th>
    </tr>
    <tr>
      <td>
        Overlap Based Drug Repositioning Analysis File
      </td>
      <td>
        <a href=<?php print($resultfile); ?> download> Download</a>
      </td>
    </tr>
    <?php
    if ($type == 'ssea' || $type == 'msea' || $type == 'wkda') { ?>
      <tr>
        <td>
          Genes used for repositioning
        </td>
        <td>
          <a href=<?php print($genefile); ?> download> Download</a>
        </td>
      </tr>
    <?php } else {
    ?>
      <tr>
        <td>
          Hepatotoxicity ADR Scoring
        </td>
        <td>
          <a href=<?php print($resultfiletox); ?> download> Download</a>
        </td>
      </tr>

    <?php }
    ?>

  </thead>
</table>

<link rel="stylesheet" href="include/bs-datatable.css" type="text/css" />

<?php
if ($type =='wkda') { ?>
<div class="table-responsive">
  <table id="shinyapp3_resultskda" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>
          Database
        </th>
        <th>
          Method
        </th>
        <th>
          Drug
        </th>
        <th>
          Species
        </th>
        <th>
          Tissue or Cell Line
        </th>
        <th>
          Study
        </th>
        <th>
          Dose
        </th>
        <th>
          Treatment Duration
        </th>
        <th>
           <a href="#" tooltip="Also known as Jaccard index or similarity coefficient, reflects the similarity and diversity of sample sets" style="position: relative; z-index: 1001;">Jaccard Score</a>
        </th>
        <th>
          <a href="#" tooltip="A value equal to or less than 1 means no association between the sets and higher values reflect greater association" style="position: relative; z-index: 1001;">Odds Ratio</a>
        </th>
        <th>
          <a href="#" tooltip="Probability of a statistical measure to be greater/less (more extreme) than or equal to the observed result" style="position: relative; z-index: 1001;">P value</a>
        </th>
        <th>
          <a href="#" tooltip="Jaccard scoring percentile across tissues within the same species" style="position: relative; z-index: 1001;">Within Species Rank</a>
        </th>
      </tr>
    </thead>
    <?php
    $count = 1;
    $result = file($resultfile);
    foreach ($result as $line) {
      $line_array = explode("\t", $line);
      $database = trim($line_array[0]);
      $method = trim($line_array[1]);
      $drug = trim($line_array[2]);
      $species = trim($line_array[3]);
      $tissue = trim($line_array[4]);
      $study = trim($line_array[5]);
      $dose = trim($line_array[6]);
      $time = trim($line_array[7]);
      $jaccard = number_format(floatval($line_array[8]), 3, ".", "");
      $odds = number_format(floatval($line_array[9]), 3, ".", "");
      $pvalue = scientificNotation(trim((float)$line_array[10]));
      $rank = number_format(floatval($line_array[11]), 5, ".", "");
      $sider = trim($line_array[17]);
      if ($sider!="none") {
        $sider = '<a href=' . $sider . '> SIDER';
      }
      if ($count == 1) {
        $count++;
        continue;
      }
      eHepatotoxicity ADR scoring of input genescho "<tr><td>$database</td><td>$method</td><td>$drug</td><td>$species</td><td>$tissue</td><td>$study</td><td>$dose</td><td>$time</td><td>$jaccard</td><td>$odds</td><td>$pvalue</td><td>$rank</td><td>$sider</td></tr>";
    }

    ?>
  </table>
</div>
<?php
} else { 
  ?>

<div style="margin-bottom: 5%; padding-bottom: 1%;" class="table-responsive">
  <table id="shinyapp3_results" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>
          Database
        </th>
        <th>
          Method
        </th>
        <th>
          Drug
        </th>
        <th>
          Species
        </th>
        <th>
          Tissue or Cell Line
        </th>
        <th>
          Study
        </th>
        <th>
          Dose
        </th>
        <th>
          Treatment Duration
        </th>
        <th>
          <a href="#" tooltip="Also known as Jaccard index or similarity coefficient, reflects the similarity and diversity of sample sets" style="position: relative; z-index: 1001;">Jaccard Score</a>
        </th>
        <th>
        	<a href="#" tooltip="A value equal to or less than 1 means no association between the sets and higher values reflect greater association" style="position: relative; z-index: 1001;">Odds Ratio</a>
        </th>
        <th>
        	<a href="#" tooltip="Probability of a statistical measure to be greater/less (more extreme) than or equal to the observed result" style="position: relative; z-index: 1001;">P value</a>
        </th>
        <th>
        	<a href="#" tooltip="Jaccard scoring percentile across tissues within the same species" style="position: relative; z-index: 1001;">Within Species Rank</a>
        </th>
        <th>
        	SIDER link
        </th>
      </tr>
    </thead>
    <?php
    $count = 1;
    $result = file($resultfile);
    foreach ($result as $line) {
      $line_array = explode("\t", $line);
      $database = trim($line_array[0]);
      $method = trim($line_array[1]);
      $drug = trim($line_array[2]);
      $species = trim($line_array[3]);
      $tissue = trim($line_array[4]);
      $study = trim($line_array[5]);
      $dose = trim($line_array[6]);
      $time = trim($line_array[7]);
      $jaccard = number_format(floatval($line_array[8]), 3, ".", "");
      $odds = number_format(floatval($line_array[9]), 3, ".", "");
      $pvalue = scientificNotation(trim((float)$line_array[10]));
      $rank = number_format(floatval($line_array[11]), 5, ".", "");
      $sider = trim($line_array[17]);
      if ($sider!="none") {
        $sider = '<a href=' . $sider . '> SIDER';
      }
      if ($count == 1) {
        $count++;
        continue;
      }
      echo "<tr><td>$database</td><td>$method</td><td>$drug</td><td>$species</td><td>$tissue</td><td>$study</td><td>$dose</td><td>$time</td><td>$jaccard</td><td>$odds</td><td>$pvalue</td><td>$rank</td><td>$sider</td></tr>";
    }

    ?>
  </table>
</div>

  <?php
}

if ($type =='pharm'){
?>

  <!--Hepatotoxicity table----------->
  <h3 style="margin-bottom: 1%;">Hepatotoxicity ADR scoring of input genes</h3>
  <p style="text-align: left;margin-bottom: 1%; font-size: 1rem;">
  Overlap score for user input genes against Comparative Toxicogenomics Database chemical induced liver injury signature. Jaccard score rank and p value are calculated against 4201 rat liver drug signatures in the database.
  </p>
  <div class="table-responsive">
    <table id="shinyapp3_resultstox" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>
            Adverse drug reaction
          </th>
          <th>
            Jaccard score
          </th>
          <th>
            Jaccard score rank
          </th>
          <th>
            Jaccard p value
          </th>
          <th>
            ADR genes and input genes overlap
          </th>
        </tr>
      </thead>
      <?php

      $count = 1;
      $result = file($resultfiletox);
      foreach ($result as $line) {
        $line_array = explode("\t", $line);
        $submod = trim($line_array[0]);

        $jaccard = number_format((float)$line_array[1], 3, ".", "");
        $jrank = number_format((float)$line_array[2], 3, ".", "");
        $jpvalue = scientificNotation(trim((float)$line_array[3]));

        $overlap = trim($line_array[4]);

        if ($count == 1) {
          $count++;
          continue;
        }

        echo "<tr><td>$submod</td><td>$jaccard</td><td>$jrank</td><td>$jpvalue</td><td>$overlap</td></tr>";
      }

      ?>
    </table>
  </div>

<?php
}
?>


<?php

$results_sent = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "sent_results";
$email = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "email";

if ((!(file_exists($results_sent)))) {
  if (file_exists($email)) {
    require('./PHPMailer-master/class.phpmailer.php');

    $resultfile = "./Data/Pipeline/Results/shinyapp3/$sessionID" . "_app3result.txt";

    $emailid = "./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "email";
    $mail = new PHPMailer();

    $mail->Body = 'Congratulations! You have successfully executed our pipeline. Please download your results.';
    $mail->Body .= "\n";
    $mail->Body .= 'Your results are available at: http://mergeomics.research.idre.ucla.edu/runpharmomics.php?sessionID=';
    $mail->Body .= "$sessionID";
    $mail->Body .= "\n";
    $mail->Body .= 'Note: Your results will be deleted from the server after 24 hours';

    //$mail->IsSMTP(); // telling the class to use SMTP

    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "smha118@g.ucla.edu";  // GMAIL username
    $mail->Password   = "mergeomics729@";            // GMAIL password

    $mail->SetFrom('smha118@g.ucla.edu', 'Daniel Ha');

    $mail->Subject    = "Overlap Based Drug Repositioning Execution Complete!";

    $file_to_attach = "$resultfile";

    $mail->addAttachment($file_to_attach, "Pharmomics_app3_result.txt");

    //$address = "dougvarneson@gmail.com";
    $address = trim(file_get_contents($emailid));
    $mail->AddAddress($address);

    if (!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    } else {

      $myfile = fopen("./Data/Pipeline/Results/shinyapp3_email/$sessionID" . "sent_results", "w");
      fwrite($myfile, $address);
      fclose($myfile);
    }
  }
}


if ($type == 'pharm') {
?>
  <script type="text/javascript">
    $("#APP3togglet").css("background-color", "#c5ebd4");
    $("#APP3togglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">App 3 - Overlap Based Drug Repositioning</div>`);
  </script>
<?php
}
?>


<script type="text/javascript">
  var type = "<?php echo $type; ?>";

  if (type=="wkda") {
    $("#shinyapp3_resultskda").dataTable({
      "order": [
        [8, 'desc']
      ],
      "dom": "Bfrtlip",
      "buttons": [
        'excelHtml5',
      ]
    }); //change column widths in .dataTable()
  }
  else{
    $("#shinyapp3_results").dataTable({
      "order": [
        [10, 'asc']
      ],
      "dom": "Bfrtlip",
      "buttons": [
        'excelHtml5',
      ]
    });
  }

  if (type=="pharm") {

    $("#shinyapp3_resultstox").dataTable({

      "dom": '<"top"Bf<"clear">>rt<"bottom"ilp<"clear">>',

      autoWidth: false,
      "columnDefs": [{
          "width": "15%"
        },
        {
          "width": "15%"
        },
        {
          "width": "15%"
        },
        {
          "width": "15%"
        },
        {
          "width": "40%"
        },
        {
          render: function(data, type, full, meta) {
            return "<div style='max-height: 50px; overflow-y: auto; text-align: left; min-width: 10em;'>" + data + "</div>";
          },
          targets: [4]
        }
      ]
    });
 }
</script>