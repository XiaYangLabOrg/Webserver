#
# Prepare an indexed database for SNP set enrichment analysis.
#
# Input:
#   job$modules      module identities as characters
#   job$genes        gene identities as characters
#   job$loci         locus identities as characters
#   job$moddata      preprocessed module data (indexed identities)
#   job$gendata      preprocessed mapping data (indexed identities)
#   job$locdata      preprocessed locus data (indexed identities)
#   job$mingenes     minimum module size allowed
#   job$maxgenes     maximum module size allowed
#   job$maxoverlap   maximum module overlap allowed (use 1.0 to skip)
#
# Optional input:
#   job$quantiles    quantile points for test statistic
#
# Output:
#   job$modules      finalized module names
#   job$moddata      finalized module data
#   job$gendata      finalized mapping data
#   job$locdata      finalized locus data
#   job$quantiles    verified quantile points
#   job$database$modulesizes
#                    gene counts for modules
#   job$database$modulelengths
#                    distinct locus counts for modules
#   job$database$moduledensities
#                    ratio between distinct and non-distinct loci
#   job$database$genesizes
#                    locus count for each gene
#   job$database$module2genes
#                    gene lists for each module
#   job$database$gene2loci
#                    locus lists for each gene 
#   job$database$locus2row
#                    row indices in the locus data frame for each locus
#   job$database$observed
#                    matrix of observed counts of values that exceed each
#                    quantile point for each locus
#   job$database$expected
#                    1.0 - quantile points
# 
# The database uses indexed identities for modules, genes and loci.
# Output also includes all the other items from input list.
#
# Written by Ville-Petteri Makinen 2013
#
ssea.prepare <- function(job) {
  cat("\nPreparing data structures...\n")
  
  # Remove extreme modules.
  st <- tool.aggregate(job$moddata$MODULE)
  mask <- which((st$lengths >= job$mingenes) &
                (st$lengths <= job$maxgenes)) 
  pos <- match(job$moddata$MODULE, st$labels[mask])
  job$moddata <- job$moddata[which(pos > 0),]
  
  # Construct hierarchical representation.
  ngens <- length(job$genes) 
  nmods <- length(job$modules)
  db <- ssea.prepare.structure(job$moddata, job$gendata, nmods, ngens)

  # Determine test cutoffs.
  if(is.null(job$quantiles)) {
    lengths <- db$modulelengths
    mu <- median(lengths[which(lengths > 0)])
    job$quantiles <- seq(0.5, (1.0 - 1.0/mu), length.out=10)
  }

  # Calculate hit counts.
  nloci <- length(job$loci)
  hits <- ssea.prepare.counts(job$locdata, nloci, job$quantiles)
  db <- c(db, hits)

  # Return results.
  job$database <- db; remove(db); gc(FALSE)
  nmem <- (object.size(job))*(0.5^20)
  cat("Job: ", nmem, " Mb\n", sep="")
  return(job)
}

#----------------------------------------------------------------------------

ssea.prepare.structure <- function(moddata, gendata, nmods, ngens) {
  
  # Prepare list structures. 
  genlists <- list()
  loclists <- list()
  for(k in 1:nmods) genlists[[k]] <- integer()
  for(k in 1:ngens) loclists[[k]] <- integer()
  modsizes <- rep(0, nmods)
  modlengths <- rep(0, nmods)
  moddensities <- rep(0.0, nmods)
  gensizes <- rep(0, ngens)

  # Collect row indices for each module.
  st <- tool.aggregate(moddata$MODULE)
  keys <- as.integer(st$labels)
  blocks <- st$blocks

  # Collect gene lists.
  genes <- as.integer(moddata$GENE)
  for(k in 1:length(blocks)) {
    key <- keys[k]
    rows <- blocks[[k]]
    members <- unique(genes[rows])
    genlists[[key]] <- as.integer(members)
    modsizes[[key]] <- length(members)
  }
 
  # Collect row indices for each gene.
  st <- tool.aggregate(gendata$GENE)
  keys <- as.integer(st$labels)
  blocks <- st$blocks
 
  # Collect locus lists.
  loci <- as.integer(gendata$LOCUS)
  for(k in 1:length(blocks)) {
    key <- keys[k]
    rows <- blocks[[k]]
    members <- unique(loci[rows])
    loclists[[key]] <- as.integer(members)
    gensizes[[key]] <- length(members)
  }

  # Count distinct loci in each module.
  for(k in which(modsizes > 0)) {
    locset <- integer()
    genset <- genlists[[k]]
    for(i in genset)
      locset <- c(locset, loclists[[i]])
    modlengths[[k]] <- length(unique(locset))
    moddensities[[k]] <- modlengths[[k]]/length(locset)
  }
  
  # Check data integrity.
  if(sum(gensizes == 0) > 0) stop("Incomplete locus data.")
  if(length(gensizes) != ngens) stop("Inconsistent gene data.")
  if(length(modsizes) != nmods) stop("Inconsistent module data.")
  
  # Return results.
  res <- list()
  res$modulesizes <- modsizes
  res$modulelengths <- modlengths
  res$moduledensities <- moddensities
  res$genesizes <- gensizes
  res$module2genes <- genlists
  res$gene2loci <- loclists
  return(res)
}

#----------------------------------------------------------------------------

ssea.prepare.counts <- function(locdata, nloci, quantiles) {

  # Make sure there are at least two points to prevent
  # R automagic on matrices to mess things up.
  if(length(quantiles) < 2) quantiles <- rep(quantiles, 2)

  # Create mapping table.
  nrows <- nrow(locdata)
  locmap <- rep(0, nloci)
  locmap[locdata$LOCUS] <- (1:nrows)
  
  # Convert values to standardized range.
  values <- tool.unify(locdata$VALUE)

  # Create bit matrix of values above quantiles.
  nquant <- length(quantiles)
  bits <- matrix(data=FALSE, nrow=nrows, ncol=nquant)
  for(i in 1:nrows)
    bits[i,] <- (values[i] > quantiles)

  # Return results.
  res <- list()
  res$locus2row <- locmap
  res$observed <- bits
  res$expected <- (1.0 - quantiles)
  return(res)
}
