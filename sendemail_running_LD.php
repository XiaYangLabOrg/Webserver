
<?php


require('./PHPMailer-master/class.phpmailer.php');

 if(isset($_GET['randomstr'])) {
        $random_string=$_GET['randomstr'];
    }

$emailid="./Data/Pipeline/Results/ld_prune_email/$random_string"."email";


$mail = new PHPMailer();

$mail->Body = 'Your Marker Dependency Filtering job is running. We will send you a notification with a link to your results after completion.';
$mail->Body .= "\n";
$mail->Body .= 'If you close your browser, you can get your results from: http://mergeomics.research.idre.ucla.edu/ld_prune_result.php?My_key=';
$mail->Body .= "$random_string";
$mail->Body .= ' when the pipeline is complete';

 $mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
$mail->Username   = "darneson@g.ucla.edu";  // GMAIL username
$mail->Password   = "friday180";            // GMAIL password


$mail->SetFrom('darneson@g.ucla.edu', 'Doug Arneson');

$mail->Subject    = "MDF Execution Started";

$address = trim(file_get_contents($emailid));
$mail->AddAddress($address);

if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
        $myfile = fopen("./Data/Pipeline/Results/ld_prune_email/$random_string"."sent_email", "w");
        fwrite($myfile, $address);
        fclose($myfile);
}






?>