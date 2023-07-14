# Organize and save results.
#
# Input:
#   job$label      unique identifier for the analysis
#   job$folder     output folder for results
#
# Results are also saved in tab-delimited text files.
#
# Written by Ville-Petteri Makinen 2013
#
kda.finish <- function(job) {
  cat("\nFinishing results...\n")

  # Estimate additional measures.
  res <- kda.finish.estimate(job)

  # Save full results.
  res <- kda.finish.save(res, job)
  
  # Create a simpler file for viewing.
  res <- kda.finish.trim(res, job)
  
  # Create a summary file of top hits.
  res <- kda.finish.summarize(res, job)
  return(job)
}

#---------------------------------------------------------------------------

kda.finish.estimate <- function(job) {
  res <- job$results
  
  # Collect module sizes.
  sizes <- rep(0, nrow(res))
  modnames <- res$MODULE
  mod2nod <- job$module2nodes
  for(i in 1:nrow(res)) {
    key <- modnames[i]
    sizes[i] <- length(mod2nod[[key]])
  }
  res$N.mod <- sizes

  # Collect overlaps with hub neighborhoods.
  nnodes <- length(job$graph$nodes)
  hubnets <- job$graph$hubnets
  sizes <- matrix(nrow=nrow(res), ncol=4)
  nodenames <- res$NODE
  for(i in 1:nrow(res)) {
    key <- modnames[i]
    node <- nodenames[i]
    memb <- mod2nod[[key]]
    g <- hubnets[[node]]
    sizes[i,1] <- nrow(g)
    sizes[i,2] <- length(intersect(g$RANK, memb))
    sizes[i,3] <- nrow(g)*length(memb)/nnodes
    sizes[i,4] <- sum(node == memb)
  }

  # Update data frame.
  res$N.neigh <- sizes[,1]
  res$N.obsrv <- sizes[,2]
  res$N.expct <- sizes[,3]
  res$MEMBER <- (sizes[,4] > 0)
  res$FILL <- (res$N.obsrv)/(res$N.neigh + 1e-20)
  res$FOLD <- (res$N.obsrv)/(res$N.expct + 1e-20)
  return(res)
}

#---------------------------------------------------------------------------

kda.finish.save <- function(res, job) {

  # Collect co-hubs.
  mtx <- matrix(nrow=0, ncol=0)
  nodes <- job$graph$nodes
  masters <- unique(res$NODE)
  cohubsets <- job$graph$cohubsets
  for(key in masters) {
    cohubs <- cohubsets[[key]]
    cohubs <- unique(c(key, cohubs))
    tmp <- matrix(key, nrow=length(cohubs), ncol=2)
    tmp[,2] <- cohubs
    if(ncol(mtx) < 2) mtx <- tmp
    else mtx <- rbind(mtx, tmp)
  }

  # Convert indices to identities.
  dat <- data.frame(HUB=mtx[,1], NODE=mtx[,2])
  dat$NODE <- nodes[dat$NODE]
  dat$HUB <- nodes[dat$HUB]

  # Save co-hub information.
  jdir <- file.path(job$folder, "kda")
  fname <- paste(job$label, ".hubs.txt", sep="")
  tool.save(frame=dat, file=fname, directory=jdir)

  # Merge with module info.
  if(nrow(job$modinfo) > 0) res <- merge(res, job$modinfo, all.x=TRUE)

  # Convert indices to identities.
  res$MODULE <- job$modules[res$MODULE]
  res$NODE <- job$graph$nodes[res$NODE]

  # Sort and save results.
  res <- res[order(res$P),]
  fname <- paste(job$label, ".results.txt", sep="")
  tool.save(frame=res, file=fname, directory=jdir)
  return(res)
}

#---------------------------------------------------------------------------

kda.finish.trim <- function(res, job) {

  # Select columns.
  header <- c("MODULE", "NODE", "P", "FDR", "FOLD")
  if(is.null(res$DESCR) == FALSE) header <- c(header, "DESCR")
  res <- res[,header]

  # Make numbers nicer to look at.
  preals <- res$P; pvals <- rep("", nrow(res))
  fdreals <- res$FDR; fdrates <- rep("", nrow(res))
  ldreals <- res$FOLD; folds <- rep("", nrow(res))
  for(i in 1:nrow(res)) {
    pvals[i] <- sprintf("%.2e", preals[	i])
    if(is.na(fdreals[i])) fdrates[i] <- ""
    else fdrates[i] <- sprintf("%.4f", fdreals[i])
    folds[i] <- sprintf("%.2f", ldreals[i])
  }

  # Update results.
  res$P <- pvals
  res$FDR <- fdrates
  res$FOLD <- folds

  # Rename columns for post-processing.
  trimres <- res
  header[[3]] <- paste("P.", job$label, sep="")
  header[[4]] <- paste("FDR.", job$label, sep="")
  names(trimres) <- header

  # Save P-values.
  jdir <- file.path(job$folder, "kda")
  fname <- paste(job$label, ".pvalues.txt", sep="")
  tool.save(frame=trimres, file=fname, directory=jdir)  
  return(res)
}

#---------------------------------------------------------------------------

kda.finish.summarize <- function(res, job) {

  # Determine ranking scores.
  nres <- nrow(res)
  rA <- rank(as.double(res$P))
  rB <- (nres - rank(as.double(res$FOLD)))
  scores <- (rA*nrow(res) + rB)

  # Determine blocks of modules.
  struct <- tool.aggregate(res$MODULE)
  blocks <- struct$blocks

  # Find the top node for each block.
  tops <- rep(0, length(blocks))
  for(k in 1:length(blocks)) {
    rows <- blocks[[k]]
    tmp <- scores[rows]
    pos <- which(tmp == min(tmp))
    tops[k] <- rows[pos]
  }

  # Select top drivers.
  scores <- scores[tops]
  res <- res[tops,]

  # Save P-values.
  res <- res[order(scores),]
  jdir <- file.path(job$folder, "kda")
  fname <- paste(job$label, ".tophits.txt", sep="")
  tool.save(frame=res, file=fname, directory=jdir)  
  return(res)
}
