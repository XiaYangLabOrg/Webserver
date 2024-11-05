<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("functions.php");
$env=parse_ini_file("../.env");
echo $env["HOFFMAN2_SERVER_IP"]."<br>";
echo $env["PHARMOMICS_USERNAME"];
echo "current user: ".get_current_user();
echo "script was executed under user: ".exec('whoami');
$connection = ssh2_connect($env["HOFFMAN2_SERVER_IP"], 22);
ssh2_auth_password($connection, $env["PHARMOMICS_USERNAME"], $env["PHMARMOMICS_PASSWORD"]);
$fpathOut="/home/smha118/mergeomics/html/Data/Pipeline/7DEB5jWjP2_app2_seg.R";
#sah2_scp_send($connection, $fpathOut, '/u/scratch/m/mergeome/app2seg/7DEB5jWjP2_app2_seg.R', 0644);
$stream=ssh2_exec($connection, "/usr/bin/ls .");
stream_set_blocking( $stream, true );
$stream_out = ssh2_fetch_stream( $stream, SSH2_STREAM_STDIO );
echo stream_get_contents($stream_out);
fclose($stream);


?>