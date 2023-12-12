# Organize and save results.
#
# Input:
#   job$label      unique identifier for the analysis
#   job$folder     output folder for results
#   job$results    data frame:
#                  MODULE    module identity (indexed)
#                  P         enrichment P-values
#   job$database   see ssea.prepare()
#
# Output:
#   job$results       updated columns in data frame:
#                     NGENES   number of distinct member genes
#                     NLOCI    number of distinct member loci
#                     DENSITY  ratio of distinct to non-distinct loci
#                     FDR      false discovery rates
#   job$generesults   data frame:
#                     GENE     gene identity (indexed)
#                     NLOCI    gene size
#                     SCORE    unadjusted enrichment score
#                     LOCUS    locus with maximum value
#                     VALUE    locus value
#
# Results are also saved in tab-delimited text files.
#
# Written by Ville-Petteri Makinen 2013
#
ssea.finish <- function(job) {
  cat("\nPostprocessing results...\n")
  job <- ssea.finish.fdr(job)
  job <- ssea.finish.genes(job)
  job <- ssea.finish.details(job)
  return(job)
}

#----------------------------------------------------------------------------

ssea.finish.genes <- function(job) {
  db <- job$database
  gen2loci <- db$gene2loci
  loc2row <- db$locus2row
  values <- job$locdata$VALUE
  
  # Collect top loci within genes.
  toploci <- integer()
  topvals <- double()
  ngenes <- length(gen2loci)
  for(k in 1:ngenes) {
    locset <- gen2loci[[k]]
    locvals <- values[loc2row[locset]]
    ind <- which.max(locvals)
    toploci[k] <- locset[ind]
    topvals[k] <- locvals[ind]
  }

  # Create data frame.
  res <- data.frame(GENE=(1:ngenes))
  res$SCORE <- db$genescores
  res$NLOCI <- db$genesizes
  res$LOCUS <- toploci
  res$VALUE <- topvals
  job$generesults <- res

  # Restore original identities.
  res$GENE <- job$genes[res$GENE]
  res$LOCUS <- job$loci[res$LOCUS]

  # Save results.
  jdir <- file.path(job$folder, "ssea")
  fname <- paste(job$label, ".genes.txt", sep="")
  tool.save(frame=res, file=fname, directory=jdir)
  return(job)
}

#----------------------------------------------------------------------------

ssea.finish.fdr <- function(job) {
  res <- job$results

  # Add module statistics.
  db <- job$database
  res$NGENES <- db$modulesizes[res$MODULE]
  res$NLOCI <- db$modulelengths[res$MODULE]
  res$DENSITY <- db$moduledensities[res$MODULE]

  # Estimate false discovery rates.
  res$FDR <- tool.fdr(res$P)
  job$results <- res
 
  # Merge with module info.
  res <- merge(res, job$modinfo, all.x=TRUE)
  
  # Sort according to significance.
  res <- res[order(res$P),]
  
  # Restore module names.
  res$MODULE <- job$modules[res$MODULE]

  # Save full results.
  jdir <- file.path(job$folder, "ssea")
  fname <- paste(job$label, ".results.txt", sep="")
  tool.save(frame=res, file=fname, directory=jdir)
  
  # Prepare results for post-processing.
  header <- rep("MODULE", 4)
  header[[2]] <- paste("P.", job$label, sep="")
  header[[3]] <- paste("FDR.", job$label, sep="")
  header[[4]] <- "DESCR"
  res <- res[,c("MODULE", "P", "FDR", "DESCR")]
  names(res) <- header

  # Make numbers nicer to look at.
  pvals <- character()
  fdrates <- character()
  for(i in 1:nrow(res)) {
    pvals[i] <- sprintf("%.2e", res[i,2])
    fdrates[i] <- sprintf("%.4f", res[i,3])
  }
  res[,2] <- pvals
  res[,3] <- fdrates
  
  # Save P-values.
  fname <- paste(job$label, ".pvalues.txt", sep="")
  tool.save(frame=res, file=fname, directory=jdir)
  return(job)
}

#----------------------------------------------------------------------------

ssea.finish.details <- function(job) {

  # Find signficant modules.
  res <- job$results
  mask <- which(res$FDR < 0.25)
  if(length(mask) < 5) {
    mask <- order(res$P)
    mask <- mask[1:min(5,length(mask))]
  }
  
  # Collect gene members of top modules.
  dtl <- data.frame()
  mod2genes <- job$database$module2genes
  for(k in res[mask,"MODULE"]) {
    genset <- mod2genes[[k]]
    tmp <- data.frame(MODULE=k, GENE=genset)
    dtl <- rbind(dtl, tmp)
  }
  
  # Merge with gene results.
  dtl <- merge(dtl, job$generesults, all.x=TRUE)
  
  # Merge with module info.
  if(nrow(job$modinfo) > 0)
    dtl <- merge(dtl, job$modinfo, all.x=TRUE)
  else
    dtl$DESCR = ""
  
  # Merge with module statistics.
  dtl <- merge(res[,c("MODULE", "P", "FDR")], dtl, all.y=TRUE)
  
  # Sort according to enrichment and locus value.
  scores <- 1000*rank(-(dtl$P))
  gscores <- tool.unify(dtl$VALUE)  
  rows <- order((scores + gscores), decreasing=TRUE)
  dtl <- dtl[rows,]
  
  # Restore names and sort columns.
  dtl$MODULE <- job$modules[dtl$MODULE]
  dtl$GENE <- job$genes[dtl$GENE]
  dtl$LOCUS <- job$loci[dtl$LOCUS]
  dtl <- dtl[,c("MODULE", "FDR", "GENE", "NLOCI",
                "LOCUS", "VALUE", "DESCR")]

  # Make numbers look nicer.
  values <- character()
  fdrates <- character()
  for(i in 1:nrow(dtl)) {
    values[i] <- sprintf("%.2f", dtl[i,"VALUE"])
    fdrates[i] <- sprintf("%.2f%%", 100*dtl[i,"FDR"])
  }
  dtl$FDR <- fdrates
  dtl$VALUE <- values
  
  # Save contents.
  jdir <- file.path(job$folder, "ssea")
  fname <- paste(job$label, ".details.txt", sep="")
  tool.save(frame=dtl, file=fname, directory=jdir)
  return(job)
}
