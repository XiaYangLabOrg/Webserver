# 
# SNP set enrichment analysis with gene-level permutations.
#
# Input:
#   job$seed       seed for random number generator
#   job$permtype   random permutation algorithm
#   job$nperm      maximum nubmer of permutations
#   job$database   see ssea.prepare()
#
# Output:
#   job$results    data frame:
#                  MODULE    module identity (indexed)
#                  P         enrichment P-values
#                  FREQ      enrichment P-values (raw frequencies)
#
# Written by Ville-Petteri Makinen 2013
#
ssea.analyze <- function(job) {
  cat("\nEstimating enrichment...\n")  
  set.seed(job$seed)
  
  # Observed enrichment scores.
  db <- job$database
  scores <- ssea.analyze.observe(db)
  nmods <- length(scores)
  
  # Simulated scores.
  nperm <- job$nperm
  nullsets <- ssea.analyze.simulate(db, scores, nperm, job$permtype)

  # Estimate scores based on Gaussian distribution.
  cat("\nNormalizing scores...\n")
  znull <- double()
  zscores <- NA*scores
  for(i in which(0*scores == 0)) {
    x <- nullsets[[i]]    
    x <- x[which(0*x == 0)]
    param <- tool.normalize(x)
    z <- tool.normalize(x, param)
    zscores[i] <- tool.normalize(scores[i], param)
    znull <- c(znull, z)
  }

  # Estimate hit frequencies.
  freq <- NA*scores
  nnull <- length(znull)
  for(i in which(0*scores == 0))
    freq[i] <- sum(zscores[i] <= znull)/nnull
  
  # Estimate scores based on frequencies.
  hscores <- NA*freq
  rows <- which(freq > 5.0/nnull)
  hscores[rows] <- qnorm(freq[rows], lower.tail=FALSE)

  # Fill in scores for low frequencies.
  if(length(rows) < length(freq)) {
    omega <- which.max(zscores)
    hscores[omega] <- zscores[omega]
    rows <- c(rows, omega)
    pt <- approx(x=zscores[rows], y=hscores[rows], xout=zscores)
    hscores <- pt$y
  }
  
  # Estimate statistical significance.
  z <- 0.5*(zscores + hscores)
  pvalues <- pnorm(z, lower.tail=FALSE)
 
  # Collect results.
  res <- data.frame(MODULE=(1:nmods), stringsAsFactors=FALSE)
  res$P <- pvalues
  res$FREQ <- freq

  # Remove missing scores.
  targets <- which(0*scores == 0)
  job$results <- res[targets,]
  return(job)
}

#----------------------------------------------------------------------------

ssea.analyze.simulate <- function(db, observ, nperm, permtype) {

  # Include only non-empty modules for simulation.
  nmods <- length(db$modulesizes)
  targets <- which(db$modulesizes > 0)
  hits <- rep(NA, nmods)
  hits[targets] <- 0
  
  # Prepare data structures to hold null samples.
  keys <- rep(0, nperm)
  scores <- rep(NA, nperm)
  scoresets <- list()
  for(i in 1:nmods)
    scoresets[[i]] <- double()
  
  # Simulate random scores.
  nelem <- 0
  snull <- double()
  stamp <- Sys.time()
  for(k in 1:nperm) {
    if(permtype == "gene") snull <- ssea.analyze.randgenes(db, targets)
    if(permtype == "locus") snull <- ssea.analyze.randloci(db, targets)
    if(length(snull) < 1) stop("Unknown permutation type.")

    # Check data capacity.
    ntarg <- length(targets)
    if((nelem + ntarg) >= length(keys)) {
      keys <- c(keys, rep(0, nelem))
      scores <- c(scores, rep(NA, nelem))
    }
    
    # Collect scores.
    for(i in 1:ntarg) {
      nelem <- (nelem + 1)
      t <- targets[i]
      keys[nelem] <- t
      scores[nelem] <- snull[i]
      hits[t] <- (hits[t] + (snull[i] > observ[t]))
    }

    # Drop less significant modules.
    hmax <- min(sqrt(nperm/k), 10)
    targets <- which(hits < hmax)
    if(length(targets) < 1) break
    
    # Progress report.
    delta <- as.double(Sys.time() - stamp)
    if((delta < 10.0) & (k < nperm)) next
    cat(sprintf("%d/%d cycles\n", k, nperm))
    stamp <- Sys.time()
  }
  
  # Remove missing entries.
  scores <- scores[1:nelem]
  keys <- keys[1:nelem]

  # Organize null scores into lists.
  st <- tool.aggregate(keys)
  labels <- as.integer(st$labels)
  blocks <- st$blocks
  for(i in 1:length(blocks)) {
    key <- labels[i]
    rows <- blocks[[i]]
    scoresets[[key]] <- scores[rows]
  }
  return(scoresets)
}

#----------------------------------------------------------------------------

ssea.analyze.observe <- function(db) {
  mod2gen <- db$module2genes
  gene2loci <- db$gene2loci
  locus2row <- db$locus2row
  observed <- db$observed
  expected <- db$expected
  nmods <- length(mod2gen)  

  # Test every module.
  scores <- rep(NA, nmods)
  for(k in 1:nmods) {
    genes <- mod2gen[[k]]

    # Collect loci.
    loci <- integer()
    for(i in genes) 
      loci <- c(loci, gene2loci[[i]])

    # Determine data rows.
    loci <- unique(loci)
    rows <- locus2row[loci]
    nloci <- length(rows)    

    # Calculate total counts.
    e <- nloci*expected
    o <- observed[rows,]
    if(nloci > 1) o <- colSums(o)
    
    # Estimate enrichment.
    scores[k] <- ssea.analyze.statistic(o, e)
  }
  return(scores)
}

#----------------------------------------------------------------------------

ssea.analyze.randgenes <- function(db, targets) {
  mod2gen <- db$module2genes
  modsizes <- db$modulesizes
  modlengths <- db$modulelengths
  gene2loci <- db$gene2loci
  locus2row <- db$locus2row
  observed <- db$observed
  expected <- db$expected
  
  # Test target modules.
  scores <- double()
  nrows <- length(locus2row)
  npool <- length(gene2loci)
  for(k in targets) {
    msize <- modsizes[[k]]
    nloci <- modlengths[[k]]

    # Collect pre-defined number of loci from random genes.
    loci <- integer()
    genes <- sample.int(npool, (msize + 10))
    while(length(loci) < nloci) {
      for(i in genes) {
        tmp <- gene2loci[[i]]
        loci <- c(loci, tmp)
      }
      loci <- unique(loci)
      genes <- sample.int(npool, msize)
    }
    
    # Determine data rows.
    loci <- loci[1:nloci]
    rows <- locus2row[loci]
    
    # Calculate total counts.
    e <- nloci*expected
    o <- observed[rows,]
    if(nloci > 1) o <- colSums(o)
    
    # Estimate enrichment.
    z <- ssea.analyze.statistic(o, e)
    scores <- c(scores, z)
  }
  return(scores)
}

#----------------------------------------------------------------------------

ssea.analyze.randloci <- function(db, targets) {
  modlengths <- db$modulelengths
  locus2row <- db$locus2row
  observed <- db$observed
  expected <- db$expected
  
  # Test target modules.
  scores <- double()
  nrows <- length(locus2row)
  for(k in targets) {
    nloci <- modlengths[[k]]
    
    # Determine data rows.
    loci <- sample.int(nrows, nloci)
    rows <- locus2row[loci]
    
    # Calculate total counts.
    e <- nloci*expected
    o <- observed[rows,]
    if(nloci > 1) o <- colSums(o)
    
    # Estimate enrichment.
    z <- ssea.analyze.statistic(o, e)
    scores <- c(scores, z)
  }

  # Return results.
  return(scores)
}

#----------------------------------------------------------------------------

ssea.analyze.statistic <- function(o, e) {
  z <- (o - e)/(sqrt(e) + 1.0)
  return(mean(z))
}
