<?php

if(isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
}


if(isset($_GET['app2email'])) {
    $emailid = $_GET['app2email'];
}
else{
    $emailid = "";
}

if($emailid != "") {
    $emailid .= "\n";
}

$femail="./Data/Pipeline/Results/shinyapp2_email/$sessionID"."email";
$email_sent="./Data/Pipeline/Results/shinyapp2_email/$sessionID"."sent_email";


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