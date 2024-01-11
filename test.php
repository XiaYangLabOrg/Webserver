<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("functions.php");
$env=parse_ini_file(".env");
echo $env["HOFFMAN2_SERVER_IP"];
$connection = ssh2_connect($env["HOFFMAN2_SERVER_IP"], 22);
ssh2_auth_password($connection, $env["PHARMOMICS_USERNAME"], $env["PHMARMOMICS_PASSWORD"]);
$fpathOut="./Data/Pipeline/7DEB5jWjP2_app2_seg.R";
ssh2_scp_send($connection, $fpathOut, '/u/scratch/m/mergeome/app2seg/', 0644);

?>