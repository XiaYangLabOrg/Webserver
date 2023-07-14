#
# Merge multiple SSEA results into meta SSEA.
#
# Input:
#   jobs    SSEA list objects
#   label   label for meta job
#   folder  parent folder for meta job
#
# Output:
#   meta    SSEA list object
#
# Written by Ville-Petteri Makinen 2013
#
ssea.meta <- function(jobs, label, folder) {

  # Create meta job.
  cat("\nMerging jobs...\n")
  meta <- list()
  meta$label <- label
  meta$folder <- folder 
  meta$modfile <- "undefined"
  meta$genfile <- "undefined"
  meta$locfile <- "undefined"
  meta <- ssea.start.configure(meta)
  
  # Collect data.
  meta$results <- data.frame()
  meta$modinfo <- data.frame()
  meta$moddata <- data.frame()
  meta$gendata <- data.frame()
  meta$locdata <- data.frame()
  for(k in 1:length(jobs)) {
    job <- jobs[[k]]
    results <- job$results
    modinfo <- job$modinfo
    moddata <- job$moddata
    gendata <- job$gendata
    locdata <- job$locdata
    
    # Restore original identities.
    results$MODULE <- job$modules[results$MODULE]
    modinfo$MODULE <- job$modules[modinfo$MODULE]
    moddata$MODULE <- job$modules[moddata$MODULE]
    moddata$GENE <- job$genes[moddata$GENE]
    gendata$GENE <- job$genes[gendata$GENE]
    gendata$LOCUS <- job$loci[gendata$LOCUS]
    locdata$LOCUS <- job$loci[locdata$LOCUS]

    # Update meta sets.
    meta$results <- rbind(meta$results, results)
    meta$modinfo <- rbind(meta$modinfo, modinfo)
    meta$moddata <- rbind(meta$moddata, moddata)
    meta$gendata <- rbind(meta$gendata, gendata)
    meta$locdata <- rbind(meta$locdata, locdata)
  }
  
  # Remove duplicate rows (non-numeric values only).
  meta$modinfo <- unique(meta$modinfo)
  meta$moddata <- unique(meta$moddata)
  meta$gendata <- unique(meta$gendata)

  # Determine identities.
  meta$modules <- unique(meta$moddata$MODULE)
  meta$genes <- unique(meta$gendata$GENE)
  meta$loci <- unique(meta$gendata$LOCUS)

  # Convert identities to indices.
  meta$results <- ssea.start.identify(meta$results, "MODULE", meta$modules)
  meta$modinfo <- ssea.start.identify(meta$modinfo, "MODULE", meta$modules)
  meta$moddata <- ssea.start.identify(meta$moddata, "MODULE", meta$modules)
  meta$moddata <- ssea.start.identify(meta$moddata, "GENE", meta$genes)
  meta$gendata <- ssea.start.identify(meta$gendata, "GENE", meta$genes)
  meta$gendata <- ssea.start.identify(meta$gendata, "LOCUS", meta$loci)
  meta$locdata <- ssea.start.identify(meta$locdata, "LOCUS", meta$loci)
  
  # Convert locus values to z-scores.
  values <- meta$locdata$VALUE
  qvals <- tool.unify(values)
  qvals <- pmax(qvals, .Machine$double.xmin)
  qvals <- pmin(qvals, (1.0 - .Machine$double.eps))
  zvals <- qnorm(qvals)

  # Merge matching loci.
  st <- tool.aggregate(meta$locdata$LOCUS)
  blocks <- st$blocks
  for(k in 1:length(blocks)) {
    rows <- blocks[[k]]
    z <- sum(zvals[rows])
    zvals[rows] <- z/sqrt(length(rows))
  }

  # Convert back to original data space.
  meta$locdata$VALUE <- quantile(values, pnorm(zvals))
  meta$locdata <- unique(meta$locdata)
  
  # Construct hierarchical representation.
  cat("\nPreparing data structures...\n")
  ngens <- length(meta$genes) 
  nmods <- length(meta$modules)
  meta$database <- ssea.prepare.structure(meta$moddata, meta$gendata,
                                          nmods, ngens)

  # Determine test cutoffs.
  lengths <- meta$database$modulelengths
  mu <- median(lengths[which(lengths > 0)])
  meta$quantiles <- seq(0.5, (1.0 - 1.0/mu), length.out=10)
  
  # Calculate hit counts.
  nloci <- length(meta$loci)
  hits <- ssea.prepare.counts(meta$locdata, nloci, meta$quantiles)
  meta$database <- c(meta$database, hits)
  
  # Check result values.
  meta$results <- meta$results[,c("MODULE", "P")]
  pvalues <- pmax(meta$results$P, .Machine$double.xmin)
  pvalues <- pmin(pvalues, (1.0 - .Machine$double.eps))
  meta$results$P <- pvalues
  
  # Calculate meta P-values.
  cat("\nPostprocessing meta results...\n")
  st <- tool.aggregate(meta$results$MODULE)
  blocks <- st$blocks
  for(k in 1:length(blocks)) {
    rows <- blocks[[k]]
    tmp <- meta$results[rows,]
    z <- qnorm(tmp$P)
    z <- sum(z)/sqrt(length(z))
    meta$results[rows,"P"] <- NA
    meta$results[rows[1],"P"] <- pnorm(z)
  }

  # Finish and save statistics.
  meta$results <- na.omit(meta$results)
  meta <- ssea.finish.fdr(meta)
  meta <- ssea.finish.details(meta)
  return(meta)
}
