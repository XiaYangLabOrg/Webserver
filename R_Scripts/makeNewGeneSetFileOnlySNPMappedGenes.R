library(dplyr)
makeNewGeneSetFileOnlySNPMappedGenes <- function(geneset_file, details_file, return_only_mod_genes=TRUE){
  details <- read.delim(details_file, header=TRUE, stringsAsFactors = FALSE)
  geneset <- read.delim(geneset_file, header=TRUE, stringsAsFactors = FALSE)
  details = details[!(grepl("_ctrl", details$MODULE)),]
  result_all = data.frame(stringsAsFactors = FALSE)
  result = data.frame(stringsAsFactors = FALSE)
  for(mod in unique(details$MODULE)){
    genes = details$GENE[details$MODULE==mod]
    multiple_genes <- genes[grep(",", genes)]
    split_genes <- unlist(sapply(multiple_genes, 
                                 FUN = function(x){return(unlist(strsplit(x,
                                                                          split = ",")))}))
    trimmed_genes <- genes[!grepl(",", genes)]
    genes <- c(trimmed_genes, split_genes)
    if(!return_only_mod_genes){
      result_all = rbind(result_all, 
                         data.frame("MODULE"=mod, 
                                    "GENE"=genes, stringsAsFactors = FALSE))
    }
    toTrim = c()
    for(iter in 1:length(genes)){
      if(!(genes[iter] %in% geneset$GENE[geneset$MODULE==mod])) toTrim = c(toTrim,iter)
    }
    if(length(toTrim)>0) genes = genes[-toTrim]
    if(return_only_mod_genes){
      result = rbind(result, 
                     data.frame("MODULE"=mod, 
                                "GENE"=genes, stringsAsFactors = FALSE))
    }
  }
 if(!return_only_mod_genes){
    write.table(result_all, "./Results/ssea/New_GeneSets_SNP_mapped_all.txt", sep="\t", quote = FALSE, row.names = FALSE)
  }
  else{
    write.table(result, "./Results/ssea/New_GeneSets_SNP_mapped.txt", sep="\t", quote = FALSE, row.names = FALSE)
  }
}




