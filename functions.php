<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
// error_reporting(0);
// ini_set('display_errors', 'Off');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function scientificNotation($val)
{
  $exp = floor(log($val, 10));
  if ($val == 0) {
    return 0;
  } else {
    return sprintf('%.4fE%+03d', $val / pow(10, $exp), $exp);
  }
}

function generateRandomString($length = 10)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}
function readMappingFile($path)
{
    $handle = fopen($path, "r");
    $content = "";
    if ($handle) {
        $row = 0;
        while (($line = fgets($handle)) !== false) {
            $row++;
            if ($row > 1) {
                $content .= $line;
            }
        }
        fclose($handle);
        return $content;
    }
}
function debug_to_console($data)
{
	$output = $data;
	if (is_array($output))
		$output = implode(',', $output);

	echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
function sendEmail( $recipient,$title, $body, $email_sent){
    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';
    $env=parse_ini_file("../.env");
    $mail = new PHPMailer(true);
    try {
        //Server settings
        #$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $env["EMAIL_USERNAME"];                  //SMTP username
        $mail->Password   = $env["EMAIL_PASSWORD"];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom($env["EMAIL_USERNAME"], 'Mergeomics Team');
        $mail->addAddress($recipient);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $title;
        $mail->Body    = $body;
        #$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mail->send();
        #echo 'Message has been sent';
        $myfile = fopen($email_sent, "w");
        fwrite($myfile, $recipient);
        fclose($myfile);
    } catch (Exception $e) {
        debug_to_console("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }


}

function generateMendatoryFiles($sessionID, $marker, $mapping)
{
    $ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
    $OUTPATH = $ROOT_DIR . "Data/Pipeline/Resources/meta_temp/" . $sessionID;
    $MARKER_FILE = $OUTPATH . "MARKER";
    $fp = fopen($MARKER_FILE, "w");
    fwrite($fp, $marker . "\n");
    fclose($fp);

    $MAPPING_FILE = $OUTPATH . "MAPPING";
    $fp = fopen($MAPPING_FILE, "w");
    fwrite($fp, $mapping . "\n");
    fclose($fp);
}
function runMDFscript($sessionID, $marker, $mapping, $mdffile, $ntop, $metasessionID)
{
    $ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
    $OUTPATH = $ROOT_DIR . "Data/Pipeline/Resources/ldprune_temp/" . $sessionID . "_output/";
    $contents = "#!/bin/bash\n" .
        /* Remove dependent markers and prepare an optimized marker and gene file
           for marker set enrichment analysis (MSEA). You must have the MDPrune software
           installed to use this script.
        
           Written by Ville-Petteri Makinen 2013, Modified by Le Shu 2015

           Original marker file. This must have two columns names 'MARKER' and
           'VALUE', where value denotes the association to the trait of interest.
           The higher the value, the stronger the association (e.g. -log P). */
        "MARFILE=\"" . $marker . "\"\n" .
        /* Mapping between genes and markers. This must have the columns 'GENE'
           and 'MARKER'.*/
        "GENFILE=\"" . $mapping . "\"\n" .
        /* The third input file defines the marker dependency structure (e.g. Linkage disequilibrium) between markers. It has three columns
          'MARKERa', 'MARKERb' and 'WEIGHT'. Marker pairs with WEIGHT > Cutoff
           are considered dependent and will be filtered.*/
        "MDSFILE=\"" . $mdffile . "\"\n" .
        // Folder to hold the results.
        "OUTPATH=\"" . $OUTPATH . "\"\n" .
        "TRIALNAME=\"" . $OUTPATH . "\"\n" .
        "mkdir -p \$TRIALNAME\n" .
        "chmod a+rwx \$TRIALNAME\n" .

        /*  To increase result robustness and conserve memory and time, it is sometimes useful
            to limit the number of markers. Here, only the top 50% associations are considered.*/
        "NTOP=" . $ntop / 100 . "\n" .
        "echo -e \"MARKER\tVALUE\" > \$TRIALNAME/header.txt\n" .
        "nice sort -r -g -k 2 \$MARFILE > \$TRIALNAME/sorted.txt\n" .
        "NMARKER=$(wc -l < \$TRIALNAME/sorted.txt)\n" .
        "NMAX=$(echo \"(\$NTOP*\$NMARKER)/1\" | bc)\n" .
        "nice head -n \$NMAX \$TRIALNAME/sorted.txt > \$TRIALNAME/top.txt\n" .
        "cat \$TRIALNAME/header.txt \$TRIALNAME/top.txt > \$TRIALNAME/subset.txt\n" .
        // Remove Markers in dependency structure and create input files for MSEA.
        "nice " . $ROOT_DIR . "Data/Pipeline/Resources/LD_files/mdprune \$TRIALNAME/subset.txt \$GENFILE \$MDSFILE \$OUTPATH";
    $fpathOut = $ROOT_DIR . "Data/Pipeline/$sessionID" . "preprocess.bash";
    $fp = fopen($fpathOut, "w");
    fwrite($fp, $contents);
    fclose($fp);
    chmod($fpathOut, 0777);

    if($metasessionID!=="none"){
        $outfile = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . $metasessionID . "_joblog.txt";
    } else{
        $outfile = $ROOT_DIR . "Data/Pipeline/Results/ssea/" . $sessionID . ".MSEA_joblog.txt";
    }

    //shell_exec('cd ' . $ROOT_DIR . 'Data/Pipeline; ' . $ROOT_DIR . 'run_ld_prune.sh ' . $sessionID);
    shell_exec('cd ' . $ROOT_DIR . 'Data/Pipeline; bash ' . $sessionID . 'preprocess.bash ' . '2>&1 | tee -a ' . $outfile);

    return $OUTPATH;
}


?>
