<?php 
include_once("functions.php");

$connection = ssh2_connect($env["HOFFMAN2_SERVER_IP"], 22);
ssh2_auth_password($connection, $env["PHARMOMICS_USERNAME"], $env["PHMARMOMICS_PASSWORD"]);
$fpathOut="./Data/Pipeline/7DEB5jWjP2_app2_seg.R";
ssh2_scp_send($connection, $fpathOut, '/u/scratch/m/mergeome/app2seg/', 0644);

?>