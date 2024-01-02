<?php
include 'functions.php';
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
function debug_to_console($data)
{
	$output = $data;
	if (is_array($output))
		$output = implode(',', $output);

	echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}


if (isset($_GET['metasessionID'])) {
	$meta_sessionID = $_GET["metasessionID"];
}


if (isset($_GET['sessionID'])) {
	//$sessionID = $_GET["sessionID"];
	$fsession = "./Data/Pipeline/Resources/session/$meta_sessionID" . "_session.txt";
	$session = explode("\n", file_get_contents($fsession));
	$cur_path_array = preg_split("/[\t]/", $session[2]);
	$sessionID = $cur_path_array[1];
} else {
	$fsession = "./Data/Pipeline/Resources/session/$meta_sessionID" . "_session.txt";
	$session = explode("\n", file_get_contents($fsession));
	$cur_path_array = preg_split("/[\t]/", $session[2]);
	$sessionID = $cur_path_array[1];
}

if (isset($_GET['sessionload'])) {
	$redirectedFromSessionLoad = $_GET["sessionload"];
}

/***************************************
Session ID
Need to update the session for the user
Since we don't have a database, we have a txt file with the path information
 ***************************************/


$fsession = $ROOT_DIR . "Data/Pipeline/Resources/session/$meta_sessionID" . "_session.txt";
if (file_exists($fsession)) {

	$session = explode("\n", file_get_contents($fsession));
	//Create different array elements based on new line
	$pipe_arr = preg_split("/[\t]/", $session[0]);
	$pipeline = $pipe_arr[1];

	if ($pipeline == "META") //check if the pipeline is MSEA. Probably not needed for this pipeline
	{
		$data = file($fsession); // reads an array of lines
		function replace_a_line($data)
		{
			if (stristr($data, 'Mergeomics_Path:' . "\t" . "1.5")) { //change from 1.25 --> 1.5
				return 'Mergeomics_Path:' . "\t" . "1.75" . "\n";
			}
			return $data;
		}
		$data = array_map('replace_a_line', $data);
		file_put_contents($fsession, implode('', $data));
	}
}


//Avoid running same pipeline twice if the page is redirected from session load - Dan 20200813


if ($redirectedFromSessionLoad != "T") {
	//$fpathlist = $ROOT_DIR . "Data/Pipeline/Resources/meta_temp/$sessionID" . "list_strings";
	$datajson = json_decode(file_get_contents($ROOT_DIR . "Data/Pipeline/Resources/meta_temp/" . $meta_sessionID . "data.json"));
	$json = $datajson->data;
	$trait_list = "trait=c(";
	$perm_list = "ptype=c(";
	$max_overlap_list = "overlap_threshold=c(";
	$nperm_list = "numperm=c(";
	$cutoff_list = "cutoff=c(";
	$MAFConvert_list = "MAFConvert_list=c(";
	$MMFConvert_list = "MMFConvert_list=c(";
	// echo "$fpath1";
	foreach ($json as $element) {
		$session = $element->session;
		$permtype = $element->perm;
		$maxoverlap = $element->maxoverlap;
		$nperm = $element->numperm;
		$marker_association = $element->association;
		$mapping = $element->marker;
		$cutoff = $element->fdrcutoff;
		$MAFConvert = $element->MAFConvert;
		$MMFConvert = $element->MMFConvert;
		if (count($mapping) > 1) {
			$newMappingcontent = "GENE" . "\t" . "MARKER" . "\n";
			foreach ($mapping as &$value) {
				$newMappingcontent .= readMappingFile($ROOT_DIR . "Data/Pipeline/" . $value);
			}
			$mapping = "Resources/meta_temp/" . $meta_sessionID . ".mappingfile.txt";
			$fp = fopen("./Data/Pipeline/" . $mapping, 'w');
			fwrite($fp, $newMappingcontent);
			fclose($fp);
		} else {
			if (gettype($mapping) == "array") {
				$mapping = $mapping[0];
			}
		}
		if ($mapping == "None Provided") {
			$file = new SplFileObject($ROOT_DIR . "Data/Pipeline/" . $marker_association);
			$mapping = $ROOT_DIR . "Data/Pipeline/Resources/msea_temp/" . $session . "genfile_for_geneEnrichment.txt"; //create fake mapping file
			$fp = fopen($mapping, "w");
			fwrite($fp, "GENE\tMARKER\n");
			// Loop until we reach the end of the file.
			while (!$file->eof()) {
				// Echo one line from the file.
				$line = $file->fgets();
				if (strpos($line, "MARKER") == false) {
					$MARKER = explode("\t", $line);
					fwrite($fp, $MARKER[0] . "\t" . $MARKER[0] . "\n");
				}
			}
			// Unset the file to call __destruct(), closing the file handle.
			$file = null;
			fclose($fp);
			chmod($mapping, 0775);
		}
		$mdf_file = $element->mdf;
		$mdf_ntop = $element->mdf_ntop;

		if (!empty($mdf_file)) {
			if ($MMFConvert !== "none") {
				shell_exec($ROOT_DIR . 'R-3.4.4/bin/Rscript ./Data/Pipeline/geneConversion.R ' . $sessionID . " " . $MMFConvert . " " . $mapping);
				$mapping = "Resources/meta_temp/Converted_" . basename($mapping);
				debug_to_console($mapping);
			}
			$outpath = str_replace($ROOT_DIR . "Data/Pipeline/", "", runMDFscript($session, $marker_association, $mapping, $mdf_file, $mdf_ntop, $meta_sessionID));
			$marker_association = $outpath . "marker.txt";
			$mapping = $outpath . "genes.txt";
		}
		generateMendatoryFiles($session, $marker_association, $mapping);
		$trait_list .= '"';
		$trait_list .= "$element->session";
		$trait_list .= '",';

		$perm_list .= "\"$permtype\"";
		$perm_list .= ',';

		$max_overlap_list .= "$maxoverlap";
		$max_overlap_list .= ',';

		$nperm_list .= "$nperm";
		$nperm_list .= ',';

		$cutoff_list .= "$cutoff";
		$cutoff_list .= ',';

		$MAFConvert_list .= "\"$MAFConvert\"";
		$MAFConvert_list .= ',';

		$MMFConvert_list .= "\"$MMFConvert\"";
		$MMFConvert_list .= ',';
	}

	$trait_list = rtrim($trait_list, ',');
	$trait_list .= ')' . "\r\n";

	//$perm_list = str_replace(array("\r", "\n"), '', $perm_list);
	$perm_list = rtrim($perm_list, ',');
	$perm_list .= ')' . "\r\n";
	// echo "$perm_list";

	//$overlap_list = str_replace(array("\r", "\n"), '', $overlap_list);
	$max_overlap_list = rtrim($max_overlap_list, ',');
	$max_overlap_list .= ')' . "\r\n";

	//$nperm_list = str_replace(array("\r", "\n"), '', $nperm_list);
	$nperm_list = rtrim($nperm_list, ',');
	$nperm_list .= ')' . "\r\n";

	$cutoff_list = rtrim($cutoff_list, ',');
	$cutoff_list .= ')' . "\r\n";

	$MAFConvert_list = rtrim($MAFConvert_list, ',');
	$MAFConvert_list .= ')' . "\r\n";

	$MMFConvert_list = rtrim($MMFConvert_list, ',');
	$MMFConvert_list .= ')' . "\r\n";

	$datajson = json_decode(file_get_contents($ROOT_DIR . "Data/Pipeline/Resources/meta_temp/" . $meta_sessionID . "metaparam.json"));
	$json = $datajson->data;
	$max_gene = $json[0]->maxgenes;
	$min_gene = $json[0]->mingenes;
	$minoverlap = $json[0]->minoverlap;
	$fdrval = $json[0]->fdrcutoff;
	$module_file = $json[0]->geneset;
	$module_info = $json[0]->genedesc;
	$GSETConvert = $json[0]->GSETConvert;

	$max_list = "maximum_genes <- $max_gene\n";
	$min_list = "minimum_genes <- $min_gene\n";
	$rmax_list = "rmax <- $minoverlap\n";
	$metafdrcutoff = "fdr_cutoff <- $fdrval/100\n";
	$metafdrcutoff .= "GSETConvert <- \"$GSETConvert\"\n";

	$part4 = 'index=0
if(dir.exists("' . $ROOT_DIR . 'Data/Pipeline/Results/meta_ssea/' . $meta_sessionID . '.meta.inter.results")){
	system("chmod 777 ' . $ROOT_DIR . 'Data/Pipeline/Results/meta_ssea/' . $meta_sessionID . '.meta.inter.results")
}
# Import library scripts
source("' . $ROOT_DIR . 'R_Scripts/meta_cle.r")
joblist=list()  #meta
for (trait.item in trait){
		index=index+1
		job.ssea <- list()
		job.ssea$label <- paste(trait.item,sep=".")
		job.ssea$folder <- paste("' . $ROOT_DIR . 'Data/Pipeline/Results/meta_ssea/' . $meta_sessionID . '.meta.inter.results",sep="")
		job.ssea$genfile <- readLines(paste("' . $ROOT_DIR . 'Data/Pipeline/Resources/meta_temp/",trait.item,"MAPPING",sep=""))[1]
		job.ssea$genfile <- as.vector(job.ssea$genfile)
		job.ssea$locfile <- readLines(paste("' . $ROOT_DIR . 'Data/Pipeline/Resources/meta_temp/",trait.item,"MARKER",sep=""))[1]
		job.ssea$locfile <- as.vector(job.ssea$locfile)
		job.ssea$modfile <- "' . $module_file . '"
		job.ssea$inffile <- "' . $module_info . '"
		MAFConvert <- MAFConvert_list[index]
		MMFConvert <- MMFConvert_list[index]
		if(MAFConvert!="none"){
		  if(MAFConvert=="entrez"){
		    gene_conversion <- read.delim("./Resources/Entrez_Symbol_Mapping_Final.txt")
		  } else {
		    gene_conversion <- read.delim("./Resources/Ensembl_Symbol_Mapping_Final.txt")
		  }
		  job.ssea$locfile <- convertGenes(fileToConvert = job.ssea$locfile,
		                                  colToConvert = "MARKER",
		                                  gene_conversion = gene_conversion)
		  if(grepl("genfile_for_geneEnrichment",job.ssea$genfile)){
		    job.ssea$genfile <- convertGenes(fileToConvert = job.ssea$genfile,
		                                     colToConvert = c("MARKER","GENE"),
		                                     gene_conversion = gene_conversion)
		  }
		}

		if(MMFConvert!="none" & !grepl("_output/", job.ssea$genfile)){
		  if(MMFConvert=="entrez"){
		    gene_conversion <- read.delim("./Resources/Entrez_Symbol_Mapping_Final.txt")
		  } else {
		    gene_conversion <- read.delim("./Resources/Ensembl_Symbol_Mapping_Final.txt")
		  }
		  job.ssea$genfile <- convertGenes(fileToConvert = job.ssea$genfile,
		                                  colToConvert = "GENE",
		                                  gene_conversion = gene_conversion)
		  
		}

		if(GSETConvert!="none"){
		  if(GSETConvert=="entrez"){
		    gene_conversion <- read.delim("./Resources/Entrez_Symbol_Mapping_Final.txt")
		  } else {
		    gene_conversion <- read.delim("./Resources/Ensembl_Symbol_Mapping_Final.txt")
		  }
		  job.ssea$modfile <- convertGenes(fileToConvert = job.ssea$modfile,
		                                  colToConvert = "GENE",
		                                  gene_conversion = gene_conversion)
		}

		if(job.ssea$inffile=="None Provided"){
			job.ssea$inffile = NULL
		}
		job.ssea$permtype <- ptype[index]       #optional
		job.ssea$maxgenes <- maximum_genes       #optional
		job.ssea$mingenes <- minimum_genes     #optional
		job.ssea$maxoverlap <- overlap_threshold[index] #optional
		job.ssea$nperm <- numperm[index]        #optional
		job.ssea <- ssea.start(job.ssea)
		job.ssea <- ssea.prepare(job.ssea)
		job.ssea <- ssea.control(job.ssea)
		job.ssea <- ssea.analyze(job.ssea)
		job.ssea <- ssea.finish(job.ssea)
		joblist[[index]]=job.ssea       #meta
}
saveRDS(joblist, "' . $ROOT_DIR . 'Data/Pipeline/Results/meta_ssea/' . $meta_sessionID . '.meta.inter.results/' . $meta_sessionID . '.joblist.rds")' . "\r\n";
	// echo "$part4";

	$mlabel = "\n" . 'meta_label="' . "$meta_sessionID" . '_META"' . "\r\n";
	// echo "$mlabel";

	$mfolder = "meta_folder=\"" . $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . $meta_sessionID . "_meta_result\"\r\n";
	// echo "$mfolder";

	// $runjob = "ssea.meta(joblist,meta_label,meta_folder)";
	$runjob = "job.meta <- ssea.meta(joblist,meta_label,meta_folder)" . "\r\n";
	$runjob .= "\n";
	$runjob .= 'fullData <- read.delim("' . $ROOT_DIR . 'Data/Pipeline/Results/meta_ssea/' . $meta_sessionID . '_meta_result/ssea/' . $meta_sessionID . '_META.combined.results.txt")
fullData_site <- data.frame("MODULE"=fullData$MODULE, "DESCR"=fullData$DESCR,stringsAsFactors = FALSE)
concatenate=function(myvect, mysep="")
{
	if(length(myvect)==0) return(myvect)
	if(length(myvect)==1) return(myvect)
	string = ""
	for(item in myvect){
		string = paste(string, item, sep = mysep)
	}
	string = substring(string, first=(nchar(mysep)+1))
	return(string)
}
metap <- fullData$META.P
metafdr <- fullData$META.FDR

fullData$META.P <- NULL
fullData$META.FDR <- NULL
fullData$Cochran.P <- NULL

modify_cols <- colnames(fullData)[grep(".P", colnames(fullData), fixed = TRUE)]
modify_cols <- c(modify_cols, colnames(fullData)[grep(".FDR", colnames(fullData), fixed = TRUE)])
for(col in modify_cols){
	fullData[,col] <- formatC(fullData[,col], format = "e", digits = 3)
	if(grepl(".P", col, fixed = TRUE)){
			fullData[,col] <- paste(fullData[,col], gsub(".P","",col, fixed = TRUE), sep = "_")
	}
	else{
			fullData[,col] <- paste(fullData[,col], gsub(".FDR","",col, fixed = TRUE), sep = "_")
	}
}

fullData_site$P.values <- vapply(fullData_site$MODULE, function(x){
	p_res <- fullData[fullData$MODULE==x,grepl(".P", colnames(fullData), fixed = TRUE)]
	p_res <- p_res[!grepl("NA_",p_res)]
	return(do.call("paste",c(as.list(p_res), list("sep"=", "))))
}, FUN.VALUE = "character")
fullData_site$FDR.values <- vapply(fullData_site$MODULE, function(x){
	fdr_res <- fullData[fullData$MODULE==x,grepl(".FDR", colnames(fullData), fixed = TRUE)]
	fdr_res <- fdr_res[!grepl("NA_",fdr_res)]
	return(do.call("paste",c(as.list(fdr_res), list("sep"=", "))))
}, FUN.VALUE = "character")
fullData_site$META.P <- metap
fullData_site$META.FDR <- metafdr
fullData_site$P.values <- as.character(fullData_site$P.values)
fullData_site$FDR.values <- as.character(fullData_site$FDR.values)
write.table(fullData_site, "' . $ROOT_DIR . 'Data/Pipeline/Results/meta_ssea/' . $meta_sessionID . '_meta_result/' . $meta_sessionID . '.MSEA_meta_combined_result_site.txt",
		row.names = FALSE, quote = FALSE, sep = "\t")';
	$runjob .= "# Create intermediary datasets for KDA." . "\r\n";
	$runjob .= "cutoff <- cutoff/100" . "\r\n";
	$runjob .= "names(cutoff) <- trait" . "\r\n";
	$runjob .= "job.kda <- metassea2kda(job.meta, joblist, rmax=rmax, filter=fdr_cutoff, individual_cutoffs=cutoff)" . "\r\n";
	$runjob .= "cat(\"META-MSEA COMPLETE\")" . "\r\n";
	/*
	$runjob .= "syms <- tool.read(\"Resources/symbols.txt\")" . "\r\n";
	$runjob .= "syms <- syms[,c(\"HUMAN\", \"MOUSE\")]" . "\r\n";
	$runjob .= "names(syms) <- c(\"FROM\", \"TO\")" . "\r\n";
	$runjob .= "job.kda <- metassea2kda(job.meta, joblist, symbols=syms)" . "\r\n";
	*/


	$fpathOut = $ROOT_DIR . "Data/Pipeline/$meta_sessionID" . "METAanalyze.R";

	$fp = fopen($fpathOut, "w");
	fwrite($fp, $trait_list);
	fwrite($fp, $perm_list);
	fwrite($fp, $max_list);
	fwrite($fp, $min_list);
	fwrite($fp, $rmax_list);
	fwrite($fp, $max_overlap_list);
	fwrite($fp, $nperm_list);
	fwrite($fp, $cutoff_list);
	fwrite($fp, $metafdrcutoff);
	fwrite($fp, $MAFConvert_list);
	fwrite($fp, $MMFConvert_list);
	fwrite($fp, $part4);
	fwrite($fp, $mlabel);
	fwrite($fp, $mfolder);
	fwrite($fp, $runjob);


	fclose($fp);

	chmod($fpathOut, 0775);





	$resultfile = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.pvalues.txt";


	$resultfiledesc = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.details.txt";

	$fpath = $ROOT_DIR . "Data/Pipeline/Results/$meta_sessionID.txt";


	$results_sent = $ROOT_DIR . "Data/Pipeline/Results/meta_email/$meta_sessionID" . "sent_results";
	$email = $ROOT_DIR . "Data/Pipeline/Results/meta_email/$meta_sessionID" . "email";

	$outfile = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . $meta_sessionID . "_joblog.txt";

	//if (!file_exists($resultfile) && !(file_exists($ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID.MSEA_modules_pval.txt"))) {
	//shell_exec('./meta_run_ssea_hdlc.sh ' . $sessionID);
	// debug_to_console("cd " . $ROOT_DIR . "Data/Pipeline;" .
	// 	"mkdir ./Results/meta_ssea/" . $meta_sessionID . ".meta.inter.results;" .
	// 	"chmod -R 777 ./Results/meta_ssea/" . $meta_sessionID . ".meta.inter.results;" .
	// 	"mkdir ./Results/meta_ssea/" . $meta_sessionID . "_meta_result;" .
	// 	"chmod -R 777 ./Results/meta_ssea/" . $meta_sessionID . "_meta_result;" .
	// 	$ROOT_DIR . "R-3.4.4/bin/Rscript ./" . $meta_sessionID . "METAanalyze.R");
	shell_exec("cd " . $ROOT_DIR . "Data/Pipeline;" .
		"mkdir ./Results/meta_ssea/" . $meta_sessionID . ".meta.inter.results;" .
		"chmod -R 777 ./Results/meta_ssea/" . $meta_sessionID . ".meta.inter.results;" .
		"mkdir ./Results/meta_ssea/" . $meta_sessionID . "_meta_result;" .
		"chmod -R 777 ./Results/meta_ssea/" . $meta_sessionID . "_meta_result;" .
		$ROOT_DIR . "R-3.4.4/bin/Rscript ./" . $meta_sessionID . "METAanalyze.R 2>&1 | tee -a " . $outfile . ";" .
		"mv " . $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . $meta_sessionID . ".meta.inter.results/ssea " . $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . $meta_sessionID . ".meta.inter.results/Individual_MSEA_Results;" .
		"cd ./Results/meta_ssea/" . $meta_sessionID . ".meta.inter.results;" .
		"zip -r Individual_MSEA_Results.zip Individual_MSEA_Results/");
	//}

	// RENAME FILES

	// RENAME PVALUE FILE
	$pvalue_file = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.pvalues.txt";
	$pvalue_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_modules_pval.txt";
	if (file_exists($pvalue_file)) {
		rename($pvalue_file, $pvalue_file_renamed);
	}

	// RENAME DETAILS FILE
	$details_file = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.details.txt";
	$details_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_top_modules_details.txt";
	if (file_exists($details_file)) {
		rename($details_file, $details_file_renamed);
	}

	// RENAME RESULTS FILE
	$results_file = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.results.txt";
	$results_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_modules_full_result.txt";
	if (file_exists($results_file)) {
		rename($results_file, $results_file_renamed);
	}
	// RENAME NODES FILE
	$nodes_file = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.nodes.txt";
	$nodes_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_genes_top_marker.txt";
	if (file_exists($nodes_file)) {
		rename($nodes_file, $nodes_file_renamed);
	}
	// RENAME INFO FILE
	$info_file = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.info.txt";
	$info_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_merged_modules_full_result.txt";
	if (file_exists($info_file)) {
		rename($info_file, $info_file_renamed);
	}
	// RENAME MODULES FILE
	$modules_file = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.modules.txt";
	$modules_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_merged_modules.txt";
	if (file_exists($modules_file)) {
		rename($modules_file, $modules_file_renamed);
	}
	// RENAME OVERVIEW FILE
	$overview_file = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_overview.txt";
	$overview_file_renamed = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_file_parameter_selection.txt";
	if (!file_exists($overview_file) && !file_exists($overview_file_renamed)) {
		$overview_json_file = $ROOT_DIR . "Data/Pipeline/Resources/meta_temp/" . $meta_sessionID . "data.json";
		$overview_json = json_decode(file_get_contents($overview_json_file));
		$fp = fopen($overview_file, 'w');
		fwrite($fp, $json);
		foreach ($overview_json->data as $item) { //foreach element in $arr
			fwrite($fp, "[" . $item->enrichment . "." . $item->session . "]\n"); //etc
			fwrite($fp, "Description\tFilename/Parameter\n"); //etc
			fwrite($fp, "Association Data\t" . $item->association . "\n");
			fwrite($fp, "Mapping File\t" . $item->marker . "\n");
			fwrite($fp, "Permutation Type\t" . $item->perm . "\n");
			fwrite($fp, "Max Gene Overlap Allowed for Merging\t" . $item->maxoverlap . "\n");
			fwrite($fp, "Number of Permutations\t" . $item->numperm . "\n");
			fwrite($fp, "Individual MSEA to KDA FDR Cutoff\t" . $item->fdrcutoff . "\n\n");
		}

		$meta_param_json_file = $ROOT_DIR . "Data/Pipeline/Resources/meta_temp/" . $meta_sessionID . "metaparam.json";
		$meta_param_json = json_decode(file_get_contents($meta_param_json_file));
		$json = $meta_param_json->data;
		fwrite($fp, "Meta-MSEA parameters\n");
		fwrite($fp, "Gene Sets\t" . $json[0]->geneset . "\n");
		fwrite($fp, "Gene Sets Description\t" . $json[0]->genedesc . "\n");
		fwrite($fp, "Max Genes in Gene Sets\t" . $json[0]->maxgenes . "\n");
		fwrite($fp, "Min Genes in Gene Sets\t" . $json[0]->mingenes . "\n");
		fwrite($fp, "Min Module Overlap Allowed for Merging\t" . $json[0]->minoverlap . "\n");
		fwrite($fp, "Meta-MSEA to KDA FDR Cutoff\t" . $json[0]->fdrcutoff . "\n\n");

		fclose($fp);
		chmod($overview_file, 0777);
	}
	if (!file_exists($overview_file_renamed)) {
		rename($overview_file, $overview_file_renamed);
	}
}

$resultfile = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_modules_pval.txt";

$meta_param_json_file = $ROOT_DIR . "Data/Pipeline/Resources/meta_temp/" . $meta_sessionID . "metaparam.json";
$meta_param_json = json_decode(file_get_contents($meta_param_json_file));
$json = $meta_param_json->data;
$genedescfile = $json[0]->genedesc;

$data = file_get_contents($resultfile); //read the file

$convert = explode("\n", $data); //create array separate by new line

$resultdownload = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.details.txt";


// $fpathparam="./Data/Pipeline/Resources/ssea_temp/$sessionID"."PARAM_SSEA_FDR";

// $fdrval = trim(file_get_contents($fpathparam)); //read the file




//$fpathparam = $ROOT_DIR . "Data/Pipeline/Resources/meta_temp/$sessionID" . "MODULE"; //changed back to sessionID

//$modulefilecontent = trim(file_get_contents($fpathparam)); //read the file


//$module_file = $ROOT_DIR . "Data/Pipeline/" . $modulefilecontent;

$details_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_top_modules_details.txt";
$resultfiledesc = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_top_modules_details.txt";
$pvalues_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_modules_pval.txt";
$results_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_modules_full_result.txt";
$nodes_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_genes_top_marker.txt";
$info_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_merged_modules_full_result.txt";
$modules_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_merged_modules.txt";
$overview_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_file_parameter_selection.txt";
//$combinedmeta_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/" . "$meta_sessionID" . ".MSEA_meta_combined_result.txt";
$combinedmeta_file = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.combined.results.txt";
$Individual_files = "./Data/Pipeline/Results/meta_ssea/" . $meta_sessionID . ".meta.inter.results/Individual_MSEA_Results.zip";
$outfile = "./Data/Pipeline/Results/meta_ssea/" . $meta_sessionID . "_joblog.txt";


?>




<table class="table table-bordered review" style="text-align: center" ; id="MSEAresultstable">

	<thead>
		<tr>
			<th colspan="3">Download Output Files</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				Meta Modules Details File
			</td>
			<td>
				Lists for each module the genes that contributed to the module's enrichment, the corresponding markers in the association file and their association strengths
			</td>
			<td>
				<a href=<?php print($details_file); ?> download> Download</a>
			</td>
		</tr>
		<!--
		<tr>
			<td>
				Meta MSEA Modules P Values File
			</td>
			<td>
				<a href=<?php print($pvalues_file); ?> download> Download</a>
			</td>
		</tr>
		-->
		<tr>
			<td>
				Meta Modules Results Summary
			</td>
			<td>
				Records for each module the enrichment p-value, FDR, and number of genes and markers contributing to the enrichment
			</td>
			<td>
				<a href=<?php print($results_file); ?> download> Download</a>
			</td>
		</tr>
		<!--
		<tr>
			<td>
				Meta MSEA Genes Top Markers File
			</td>
			<td>
				<a href=<?php print($nodes_file); ?> download> Download</a>
			</td>
		</tr>
		-->
		<tr>
			<td>
				Meta Merged Modules Results File
			</td>
			<td>
				Records MSEA results for merged modules (same data fields as the 'Modules Results Summary File'). Merged modules contain supersets of individual modules that share genes at a ratio above the 'Max Overlap Allowed for Merging' parameter (some modules may remain independent). MSEA is rerun on these merged modules and the results are recorded in this file.
			</td>
			<td>
				<a href=<?php print($info_file); ?> download> Download</a>
			</td>
		</tr>
		<tr>
			<td>
				Meta Merged Modules Nodes for KDA
			</td>
			<td>
				Lists the genes (nodes) of non-redundant supersets (merged modules) that will automatically be used as input for the next optional step of the analysis, key driver analysis (KDA). These genes are members of modules that passed the user specified FDR cutoff ('MSEA to KDA export FDR cutoff'; default is 25%). If no modules passed this significance, then the top 10 modules are used. Please refer to your results if this was the case and interpret results from KDA accordingly. You may rerun the analysis with a different threshold.
			</td>
			<td>
				<a href=<?php print($modules_file); ?> download> Download</a>
			</td>
		</tr>
		<tr>
			<td>
				Individual Study P and FDR Results File
			</td>
			<td>
				Combined summary file containing the P and FDR values for each individual MSEA run. Refer to the file and parameter selection file below for the individual MSEA run codes
			</td>
			<td>
				<a href=<?php print($combinedmeta_file); ?> download> Download</a>
			</td>
		</tr>
		<tr>
			<td>
				Individual MSEA Result Files
			</td>
			<td>
				Zip file containing individual MSEA result files. Refer to the file and parameter selection file below for the individual MSEA run codes
			</td>
			<td>
				<a href=<?php print($Individual_files); ?> download> Download</a>
			</td>
		</tr>
		<tr>
			<td>
				Meta MSEA File and Parameter Selection File
			</td>
			<td>
				Lists chosen files and parameters for this Meta MSEA run for each study
			</td>
			<td>
				<a href=<?php print($overview_file); ?> download> Download</a>
			</td>
		</tr>
		<tr>
			<td>
				Meta MSEA Job log
			</td>
			<td>
				Runtime outputs and errors (if any) of job
			</td>
			<td>
				<a href=<?php print($outfile); ?> download> Download</a>
			</td>
		</tr>
	</tbody>
</table>




<br>
<div style="text-align: center;">
	<input type="button" class="button button-3d button-small nomargin" value="Click to Download All Files in Zip Folder" onclick="window.open('meta_zip.php?My_ses=<?php print($meta_sessionID); ?>','_self','resizable=yes')" />
</div>

<br>
<br>
<br>
<link rel="stylesheet" href="include/bs-datatable.css" type="text/css" />
<div id="tabs">

	<ul class="tab-nav tab-nav2 clearfix" style="display: table; margin: 0 auto;">
		<li><a href="#tabs-module">Module Results</a></li>
		<li><a href="#tabs-mergemodule">Merge Module Results</a></li>
		<li><a href="#tabs-combinedmeta">Combined Results</a></li>
	</ul>

	<div class="tab-container">

		<div class="tab-content clearfix" id="tabs-module">
			<div class="table-responsive">
				<table id="module" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th> Module ID</th>
							<th> Meta P-Value</th>
							<th>Meta FDR</th>
							<th>Cochran.Q</th>
							<th>Cochran.P</th>
							<th>Cochran.I2</th>
							<?php
							if ($genedescfile != "None Provided") {
							?>
								<th>Description</th>
							<?php
							}
							?>
							<th>Module Top Gene </th>
							<th> Module Top Marker </th>
							<th> Module Top Association Score</th>
							<!-- <th> Module Details </th> -->
						</tr>
					</thead>
					<tbody>
						<?php

						for ($i = 1; $i < (count($convert) - 1); $i++) {
							//echo $convert[$i]; //write value by index
							$convert_word = explode("\t", $convert[$i]);

							$fdr = $convert_word[2];
							$fdrword = explode("%", $fdr);
							$fdr = $fdrword[0];
							//print($fdr);

						?>
							<?php
							$moduleid = $convert_word[0];
							if ($moduleid != "_ctrlA" && $moduleid != "_ctrlB") {
								//$l = shell_exec('grep -w ' . $moduleid . ' ' . $results_file);
								$l = shell_exec('grep -w ' . $moduleid . ' ' . $resultfiledesc);

								$line = explode("\n", $l);
								$word = explode("\t", $line[0]);

								//debug_to_
								if ($word[0] == $moduleid && trim($moduleid) != "") {
									// prepare data for david
									/*
									$lw = shell_exec('grep -w ' . $moduleid . ' ' . $module_file);
									$linew = explode("\n", $lw);
									$dline = "";
									for ($k = 0; $k < (count($linew) - 1); $k++) {
										$wline = explode("\t", $linew[$k]);
										$dline .= $wline[1] . ",";
									}
									*/
									//top gene list;top loci;top score
									$genelist = "|";
									$locilist = "|";
									$scorelist = "|";
									for ($k = 0; $k < count($line) && $k < 5; $k++) {
										$dataline = explode("\t", $line[$k]);
										$genelist .= $dataline[2] . "|";
										$locilist .= $dataline[4] . "|";
										$scorelist .= $dataline[5] . "|";
									}
								} else {
									$genelist = "None";
									$locilist = "None";
									$scorelist = "None";
								}
							?>
								<tr>
									<?php
									if ($genedescfile != "None Provided") {
										for ($j = 0; $j < count($convert_word); $j++) {
									?>
											<td>
												<div style="overflow:auto; max-width:400px;display:block"> <?php print($convert_word[$j]);
																											?> </div>
											</td>
										<?php
										}
									} else {
										for ($j = 0; $j < (count($convert_word) - 1); $j++) {
										?>
											<td>
												<div style="overflow:auto; max-width:400px;display:block"> <?php print($convert_word[$j]);
																											?> </div>
											</td>
									<?php
										}
									}
									?>
									<td>
										<div style="overflow:auto; max-width:200px;display:block"> <?php print($genelist); ?> </div>
									</td>
									<td>
										<div style="overflow:auto; max-width:200px;display:block"> <?php print($locilist); ?> </div>
									</td>
									<td>
										<div style="overflow:auto; max-width:200px;display:block"> <?php print($scorelist); ?> </div>
									</td>
								</tr> <?php
									}
								}

										?>
					</tbody>
				</table>
			</div>
		</div>
		<!--End of module tab ------->


		<div class="tab-content clearfix" id="tabs-mergemodule">

			<?php

			$resultfile = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_merged_modules_full_result.txt";


			$data = file_get_contents($resultfile); //read the file

			$convert = explode("\n", $data); //create array separate by new line

			$resultdownload2 = "/Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "MSEA_merged_modules.txt";
			// $resultdownload2="/Data/Pipeline/Results/ssea/$sessionID.ssea2kda.modules.txt";

			?>



			<!-- Merge module ===================================================== -->
			<div class="table-responsive">
				<table id="merge_module" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Merge Module ID</th>
							<th> Merge Module P-Value</th>
							<th>Frequency</th>
							<th>Number of Genes</th>
							<th>Number of Markers </th>
							<th>Density </th>
							<th> Overlap</th>
							<th> Description </th>
						</tr>
					</thead>
					<tbody>
						<?php
						for ($i = 1; $i < (count($convert) - 1); $i++) {
							$convert_word = explode("\t", $convert[$i]);
							if ($convert_word[0] != "_ctrlA" && $convert_word[0] != "_ctrlB") {
								$p_val = ($convert_word[1] - 0);
								$freq = ($convert_word[2] - 0);
								$dense = ($convert_word[5] - 0);
						?>
								<tr>
									<td> <?php print($convert_word[0]); ?> </td>
									<td> <?php printf("%.2e", $p_val); ?> </td>
									<td> <?php printf("%.2e", $freq); ?> </td>
									<td> <?php print($convert_word[3]); ?> </td>
									<td> <?php print($convert_word[4]); ?> </td>
									<td> <?php printf("%.2f", $dense); ?> </td>
									<td>
										<div style="overflow:auto; max-width:200px;display:block"><?php print($convert_word[6]); ?></div>
									</td>
									<td>
										<div style="overflow:auto; max-width:400px;display:block"><?php print($convert_word[7]); ?></div>
									</td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>

		</div>
		<!--End of merge module tab ----------------->
		<div class="tab-content clearfix" id="tabs-combinedmeta">

			<?php

			$resultfile = $ROOT_DIR . "Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/" . "$meta_sessionID" . ".MSEA_meta_combined_result_site.txt";


			$data = file_get_contents($resultfile); //read the file

			$convert = explode("\n", $data); //create array separate by new line

			?>



			<!-- Merge module ===================================================== -->
			<div class="table-responsive">
				<table id="combined_meta" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Module</th>
							<th>Description</th>
							<th>P.values</th>
							<th>FDR.values</th>
							<th>Meta.P</th>
							<th>Meta.FDR</th>
						</tr>
					</thead>
					<tbody>
						<?php
						for ($i = 1; $i < (count($convert) - 1); $i++) {
							$convert_word = explode("\t", $convert[$i]);
							if ($convert_word[0] != "_ctrlA" && $convert_word[0] != "_ctrlB") {
								$p_val = ($convert_word[4] - 0);
								$fdr = ($convert_word[5] - 0);
						?>
								<tr>
									<td> <?php print($convert_word[0]); ?> </td>
									<td>
										<div style="overflow:auto; max-width:200px;display:block"><?php print($convert_word[1]); ?></div>
									</td>
									<td>
										<div style="overflow:auto; max-width:200px;display:block"><?php print($convert_word[2]); ?></div>
									</td>
									<td>
										<div style="overflow:auto; max-width:200px;display:block"><?php print($convert_word[3]); ?></div>
									</td>
									<td> <?php printf("%.2e", $p_val); ?> </td>
									<td> <?php printf("%.2e", $fdr); ?> </td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>

		</div>

	</div>
</div>



<br>
<br>
<br>



<h4 class="instructiontext">To continue directly to wKDA or PharmOmics (Drug Repositioning) using the MSEA Results click below:
	<br>
	<button type="button" class="button button-3d button-large pipeline" id="RunwKDA">Run wKDA Pipeline</button>
	<button type="button" class="button button-3d button-large pipeline" id="Runpharmomics">Run PharmOmics Pipeline</button>
</h4>
<div id="preload"></div>


<?php

require('./PHPMailer-master/class.phpmailer.php');


if (file_exists("./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.pvalues.txt")) {
	$resultfile = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.pvalues.txt";
} else {
	$resultfile = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_modules_pval.txt";
}
if (file_exists("./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.details.txt")) {
	$resultfiledesc = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.details.txt";
} else {
	$resultfiledesc = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_top_modules_details.txt";
}
if (file_exists("./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.results.txt")) {
	$results = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.results.txt";
} else {
	$results = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_modules_full_result.txt";
}
if (file_exists("./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.nodes.txt")) {
	$nodes = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.nodes.txt";
} else {
	$nodes = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_genes_top_marker.txt";
}
if (file_exists("./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.info.txt")) {
	$info = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.info.txt";
} else {
	$info = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_merged_modules_full_result.txt";
}
if (file_exists("./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.modules.txt")) {
	$mergemodules = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . "_META.ssea2kda.modules.txt";
} else {
	$mergemodules = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_meta_result/ssea/" . "$meta_sessionID" . ".MSEA_merged_modules.txt";
}
if (file_exists("./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_overview.txt")) {
	$overview = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . "_overview.txt";
} else {
	$overview = "./Data/Pipeline/Results/meta_ssea/" . "$meta_sessionID" . ".MSEA_file_parameter_selection.txt";
}

$email = "./Data/Pipeline/Results/meta_email/$meta_sessionID" . "email";


//$fpathparam="./Data/Pipeline/Resources/$meta_sessionID"."PARAM";

$mail = new PHPMailer();
$mail->Body = 'Congratulations! You have successfully executed our pipeline. Please download your results.';
$mail->Body .= "\n";
$mail->Body .= 'Your results are available at: http://mergeomics.research.idre.ucla.edu/runmergeomics.php?sessionID=';
$mail->Body .= "$meta_sessionID";
$mail->Body .= "\n";
$mail->Body .= 'Note: Your results will be deleted from the server after 24 hours';

$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
$mail->Username   = "smha118@g.ucla.edu";  // GMAIL username
$mail->Password   = "mergeomics729@";            // GMAIL password


$mail->SetFrom('smha118@g.ucla.edu', 'Daniel Ha');

$mail->Subject    = "Mergeomics Meta MSEA Execution Complete!";
$file_to_attach = "$resultfile";
$file_to_attach2 = "$resultfiledesc";
$file_to_attach3 = "$mergemodules";
$file_to_attach5 = "$results";
$file_to_attach6 = "$nodes";
$file_to_attach7 = "$info";
$file_to_attach8 = "$overview";
$file_to_attach9 = "$combinedmeta_file";
$file_to_attach10 = "$Individual_files";
$file_to_attach11 = "$outfile";

$mail->addAttachment($file_to_attach, 'Meta_MSEA_modules_pval.txt');
$mail->addAttachment($file_to_attach2, 'Meta_MSEA_top_modules_details.txt');
$mail->addAttachment($file_to_attach3, 'Meta_MSEA_merged_modules.txt');
$mail->addAttachment($file_to_attach5, 'Meta_MSEA_modules_full_result.txt');
$mail->addAttachment($file_to_attach6, 'Meta_MSEA_genes_top_marker.txt');
$mail->addAttachment($file_to_attach7, 'Meta_MSEA_merged_modules_full_result.txt');
$mail->addAttachment($file_to_attach8, 'Meta_MSEA_file_parameter_selection.txt');
$mail->addAttachment($file_to_attach9, 'Meta_MSEA_combined_individual_P_FDR_summary.txt');
$mail->addAttachment($file_to_attach10, 'Individual_MSEA_Results.zip');
$mail->addAttachment($file_to_attach11, 'Meta_MSEA_joblog.txt');
$address = trim(file_get_contents($email));
$mail->AddAddress($address);

if (!$mail->Send()) {
	#debug_to_console("Mailer Error: " . $mail->ErrorInfo);
} else {
	#debug_to_console("Message sent!");
	$myfile = fopen("./Data/Pipeline/Results/meta_email/$meta_sessionID" . "sent_results", "w");
	fwrite($myfile, $address);
	fclose($myfile);
}



?>

<script src="include/js/bs-datatable.js"></script>
<script type="text/javascript">
	var meta_string = "<?php echo $meta_sessionID; ?>";
	var string = "<?php echo $sessionID; ?>";

	$('#module').dataTable({
		//"paging": true,
		"order": [
			[1, 'asc']
		],
		// "lengthMenu": [
		// 	[5, 10, 25, 50, -1],
		// 	[5, 10, 25, 50, "All"]
		// ]
	});
	$('#merge_module').dataTable({
		//"paging": true,
		"order": [
			[1, 'asc']
		],
		// "lengthMenu": [
		// 	[5, 10, 25, 50, -1],
		// 	[5, 10, 25, 50, "All"]
		// ]
	});
	$('#combined_meta').dataTable({
		//"paging": true,
		"order": [
			[5, 'asc']
		],
		// "lengthMenu": [
		// 	[5, 10, 25, 50, -1],
		// 	[5, 10, 25, 50, "All"]
		// ]
	});
	$("#tabs").tabs();

	var function_for_display_animation = function() {
		$("#preload").html(`<h4 style="padding: 10px" class='instructiontext'>Transferring data to PharmOmics....<br><img src='include/pictures/ajax-loader.gif' /></h4>`);
	}
	var function_for_remove_animation = function() {
		$("#preload").html('');
	}

	//Adjust header and body width
	$("#METAtab1").html("Review Files");

	$("#METAtab1").one("click", function() {
		$("#myMETA").load("/META_moduleprogress.php?metasessionID=" + meta_string);
	})

	$("#METAtab2").show();
	$("#METAtab2").click();
	$("#METAtogglet").css("background-color", "#c5ebd4");
	$("#METAtogglet").html(`<i class="toggle-closed icon-ok-circle"></i><i class="toggle-open icon-ok-circle"></i><div class="capital">Step 1 - META-MSEA</div>`);
	// setTimeout(function() {
	//     ("#METAtogglet").click();
	// }, 500);
</script>

<script type="text/javascript">
	$('#RunwKDA').on('click', function() {
		$('#METAtogglet').click();
		$('#META2KDAtoggle').show();
		$('#myMETA2KDA').load('/wKDA_parameters.php?sessionID=' + meta_string + "&rmchoice=3"); // changed string to meta_string

		$('#META2KDAtogglet').click();
		return false;
	});

	$('#Runpharmomics').on('click', function() {
		function_for_display_animation();
		$('#myMETAMSEA2PHARM').load('/ssea2pharmomics_parameters.php?sessionID=' + meta_string + "&rmchoice=3", function_for_remove_animation);
		setTimeout(function() {
			$('#METAMSEA2PHARMtoggle').show();
			$('#METAtogglet').click();
			$('#METAMSEA2PHARMtogglet').click();

		}, 500);

		return false;
	});
</script>