#
# Add internal positive control modules.
#
# Input:
#   job$modules      module identities as characters
#   job$genes        gene identities as characters
#   job$moddata      preprocessed module data (indexed identities)
#   job$database     see ssea.prepare()
#
# Output:
#   job$modules      augmented module names
#   job$moddata      augmented module data
#   job$database     augmented database
#
# Written by Ville-Petteri Makinen 2013
#
ssea.control <- function(job) {
  cat("\nAdding positive controls...\n")
  db <- job$database
  gene2loci <- db$gene2loci
  locus2row <- db$locus2row
  observed <- db$observed
  expected <- db$expected

  # Find slots for control module.
  modules <- job$modules
  slotA <- which(modules == "_ctrlA")
  slotB <- which(modules == "_ctrlB")
  if(length(slotA) != 1) stop("No control slot A.")
  if(length(slotB) != 1) stop("No control slot B.")
  
  # Calculate gene scores.
  ngens <- length(gene2loci)
  scores <- rep(NA, ngens)
  for(k in 1:ngens) {
    loci <- gene2loci[[k]]
    nloci <- length(loci)
    if(nloci < 1) next

    # Calculate total counts.
    rows <- locus2row[loci]
    e <- nloci*expected
    o <- observed[rows,]
    if(nloci > 1) o <- colSums(o)
    
    # Estimate enrichment score.
    scores[k] <- ssea.analyze.statistic(o, e)
  }
  
  # Select top genes.
  sizes <- db$modulesizes
  sizes <- sizes[which(sizes > 0)]
  ntop <- floor(median(sizes))
  genesA <- order(scores, decreasing=TRUE)
  genesA <- genesA[1:ntop]

  # Collect genes within modules.
  members <- integer()
  mod2gen <- db$module2genes
  for(k in 1:length(mod2gen))
    members <- c(members, mod2gen[[k]])
  members <- unique(members)
 
  # Select top genes among module members.
  genesB <- order(scores[members], decreasing=TRUE)
  genesB <- members[genesB[1:ntop]]
  
  # Collect loci.
  locsetA <- integer()
  locsetB <- integer()
  for(k in genesA)
    locsetA <- c(locsetA, gene2loci[[k]])
  for(k in genesB)
    locsetB <- c(locsetB, gene2loci[[k]])
  locsetA <- unique(locsetA)
  locsetB <- unique(locsetB)

  # Force matching number of loci.
  modlen <- min(length(locsetA), length(locsetB))
  
  # Create new modules.
  db$genescores <- scores
  db$modulesizes[[slotA]] <- ntop
  db$modulesizes[[slotB]] <- ntop
  db$modulelengths[[slotA]] <- modlen
  db$modulelengths[[slotB]] <- modlen
  db$moduledensities[[slotA]] <- length(locsetA)/sum(db$genesizes[genesA])
  db$moduledensities[[slotB]] <- length(locsetB)/sum(db$genesizes[genesB])
  db$module2genes[[slotA]] <- genesA
  db$module2genes[[slotB]] <- genesB

  # Update module data.
  tmpA <- data.frame(MODULE=slotA, GENE=genesA)
  tmpB <- data.frame(MODULE=slotB, GENE=genesB)
  job$moddata <- rbind(job$moddata, tmpA, tmpB)

  # Return results.
  job$database <- db; remove(db); gc(FALSE)
  nmem <- (object.size(job))*(0.5^20)
  cat("Job: ", nmem, " Mb\n", sep="")
  return(job)
}
