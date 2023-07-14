<?php

if(isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
}


if(isset($_GET['app3email'])) {
    $emailid = $_GET['app3email'];
}
else{
    $emailid = "";
}

if($emailid != "") {
    $emailid .= "\n";
}

$femail="./Data/Pipeline/Results/shinyapp3_email/$sessionID"."email";
$email_sent="./Data/Pipeline/Results/shinyapp3_email/$sessionID"."sent_email";


if($emailid!="")
{
    $parts = explode("@", $emailid);
    $name = $parts[0];
    $domain = $parts[1];
    if(trim($domain) == 'ucla.edu'){
        $newid = "$name"."@g.ucla.edu";
    }
    else{
        $newid = $emailid;
    }
    $myfile = fopen($femail, "w");
    fwrite($myfile, $newid);
    fclose($myfile);
}


?>