<?php
$draw=$_POST['draw'];
$start=$_POST['start'];
$length=$_POST['length'];
$end=$start+$length;
$resultfile=$_POST['resultfile'];

#echo $resultfile;
$sed_cmd="sed -";
$linecount=shell_exec("wc -l ".$resultfile);
echo $linecount;


?>