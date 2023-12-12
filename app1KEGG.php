<?php

if(isset($_POST['sessionID']) ? $_POST['sessionID'] : null) 
{
        $sessionID=$_POST['sessionID'];
} 

if(isset($_POST['drug_name']) ? $_POST['drug_name'] : null) 
{
        $drug_name=$_POST['drug_name'];
        $drug_name = preg_replace('/[0-9]+/', '', $drug_name);
} 

if(isset($_POST['type']) ? $_POST['type'] : null) 
{
        $type=$_POST['type'];
} 

if($type == 'organs')
{
	$species_arr = $_POST['species'];
	$species = $species_arr[0];

	$organs_arr = $_POST['organs'];
	$organs = implode("\n", $organs_arr);
}
else
{
	$species_arr = $_POST['species'];
	$species = implode("\n", $species_arr);


	$organs_arr = $_POST['organs'];
	$organs = $organs_arr[0];
}

# this file creates the app1Drug.R script
# user input is var (drugname), species, organs

# this is where the drug name is stored
$fpath1= "$drug_name"; 
# this is where the organ name is stored
$fpath2= "$species"; 
# ^^ in this file, it should hold the species as one single string with "\n" or "\t" or "," or " " separating the individual genes
# this is where the species name is stored
$fpath3="$organs"; 
# ^^ in this file, it should hold the organs as one single string with "\n" or "\t" or "," or " " separating the individual genes

$fpathOut="./Data/Pipeline/Resources/shinyapp1_temp/$sessionID"."app1KEGG.R";

$file1 = $fpath1; 
$file2 = json_encode($fpath2); 
$file3 = json_encode($fpath3);

$file1 = 'input$var <-'.'"'.$file1.'"';
$file2 = 'input$species <- unlist(strsplit('.$file2.",".'"\n|\t|,"'."))";
$file3 = 'input$organs <- unlist(strsplit('.$file3.",".'"\n|\t|,"'."))";

$data="rm(list=ls())\ninput = list()"."\n".$file1."\n".$file2."\n".$file3."\n";
$analysis=file_get_contents("./R_Scripts/Pharmomics/app1KEGG");
$output="\nwrite(x = forAjax, file = \"$sessionID"."_pathway_intersections.txt\",sep=\"\")\n
write.table(allpathways, \"$sessionID"."_pathway_results.txt\", row.names = FALSE, sep =\"\\t\", quote = FALSE)\n";

$fp = fopen($fpathOut, "w");
fwrite($fp, $data);
fwrite($fp, $analysis);
fwrite($fp, $output);
fclose($fp);
chmod($fpathOut, 0777);

$output = shell_exec('./app1KEGG.sh '.$sessionID.' 2>&1');

if (strpos($output, 'No similarities') !== false) {
	if($type == 'organs')
    	echo 'No similarities found between organs';
    else
    	echo 'No similarities found between species';
}
else
{
		if($type == 'organs')
		{
			$im = file_get_contents('./Data/Pipeline/Resources/shinyapp1_temp/Cross_organs_comparison_upset.png');
		    $imdata = base64_encode($im);
		     unlink("./Data/Pipeline/Resources/shinyapp1_temp/Cross_organs_comparison_upset.png");
			 //unlink("./Data/Pipeline/Resources/shinyapp1_temp/".$sessionID."app1KEGG.R");
		    echo $imdata;
		}
		else
		{
			$im = file_get_contents('./Data/Pipeline/Resources/shinyapp1_temp/Cross_species_comparison_upset.png');
		    $imdata = base64_encode($im);
		    unlink("./Data/Pipeline/Resources/shinyapp1_temp/Cross_species_comparison_upset.png");
			//unlink("./Data/Pipeline/Resources/shinyapp1_temp/".$sessionID."app1KEGG.R");
		    echo $imdata; 
		} 

}



?>