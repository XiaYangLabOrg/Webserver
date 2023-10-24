<?php
require('./PHPMailer-master/class.phpmailer.php');

$mail = new PHPMailer();



    $from = $_POST['email']; // this is the sender's Email address
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $subject = $_POST['subject'];
    $message = $name ." wrote the following:" . "\n\n" . $_POST['message'];
  
    // You can also use header('Location: thank_you.php'); to redirect to another page.
   

$mail->Body = $message;

//$mail->IsSMTP(); // telling the class to use SMTP

$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
$mail->Username   = "dougvarneson@gmail.com";  // GMAIL username
$mail->Password   = "friday11";            // GMAIL password

$mail->SetFrom($from, $name);

$mail->Subject = $subject;


//$address = "dougvarneson@gmail.com";
$address = 'xyang123@g.ucla.edu';
$mail->AddAddress($address);
if ($subject == "Mergeomics Support: Mergeomics Pipeline" || $subject == "Mergeomics Support: Pharmomics Pipeline") {
    $address = 'montyblencowe@ucla.edu';
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