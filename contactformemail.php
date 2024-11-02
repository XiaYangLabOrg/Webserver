<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
#require('./PHPMailer-master/class.phpmailer.php');
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
$env=parse_ini_file("../.env");
$mail = new PHPMailer(true);



$from = $_POST['email']; // this is the sender's Email address
$name = $_POST['name'];
$last_name = $_POST['last_name'];
$subject = $_POST['subject'];
$message = $name ." wrote the following:" . "\n\n" . $_POST['message'];
  
    // You can also use header('Location: thank_you.php'); to redirect to another page.
   



//$mail->IsSMTP(); // telling the class to use SMTP
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

$mail->Subject = $subject;
$mail->Body = $message;

//$address = "dougvarneson@gmail.com";
$address = 'xyang123@g.ucla.edu';
$mail->AddAddress($address);
if ($subject == "Mergeomics Support: Mergeomics Pipeline" || $subject == "Mergeomics Support: Pharmomics Pipeline") {
    $address = 'montyblencowe@ucla.edu';
    $mail->AddAddress($address);
    $address = 'smha118@g.ucla.edu';
    $mail->AddAddress($address);
} elseif ($subject == "Mergeomics Support: Report a bug"){
    $address = 'mcheng7777@g.ucla.edu';
    $mail->AddAddress($address);
} else{
    $address = 'montyblencowe@ucla.edu';
    $mail->AddAddress($address);
    $address = 'mcheng7777@g.ucla.edu';
    $mail->AddAddress($address);
}


if ($subject!="(no subject)"){
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "E-Mail Sent! Thank you " . $name . ", we will contact you shortly.";
       
    }
}
      






?>