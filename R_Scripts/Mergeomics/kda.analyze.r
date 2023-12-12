#
# Determine statistical significance of key driver genes.
#
# Input:
#   job$graph            see tool.graph()
#   job$graph$hubs       nodes considered hubs (indexed)
#   job$graph$hubnets    lists of neighboring nodes (indexed)
#   job$graph$cohubsets  lists of overlapping hubs (indexed)
#   job$module2nodes     lists of node indices for each module
#
# Output:
#   job$results          data frame of results (indexed)
#
# Written by Ville-Petteri Makinen 2013
#
kda.analyze <- function(job) {
  cat("\nAnalyzing network...\n")
  nmods <- length(job$modules)

  # Analyze modules.
  res <- data.frame()
  hubs <- job$graph$hubs
  for(i in 1:nmods) {
    members <- job$module2nodes[[i]]
    p <- kda.analyze.exec(members, job$graph, 2000)
    mask <- which(p >= 0.0)
    if(length(mask) < 1) next

    # Find top hit.
    tmp <- data.frame(MODULE=i, NODE=hubs[mask], P=p[mask])
    pmin <- min(tmp$P)
    hit <- which(tmp$P == pmin)
    hit <- tmp$NODE[hit[1]]

    # Update results.
    nmemb <- length(members)
    name <- job$graph$nodes[hit]
    kd <- sprintf("%s, n=%d, p=%.2e", name, nmemb, pmin)
    cat(job$modules[i], ": ", kd, "\n", sep="")
    res <- rbind(res, tmp)
  }

  # Estimate false discovery rates.
  res$FDR <- p.adjust(res$P, method="fdr")
  job$results <- res
  return(job)
}

#----------------------------------------------------------------------------

kda.analyze.exec <- function(memb, graph, nsim) {
  hubs <- graph$hubs
  hubnets <- graph$hubnets
  nhubs <- length(hubs)
  nnodes <- length(graph$nodes)
  nmemb <- length(memb)

  # Observed enrichment scores.
  obs <- rep(NA, nhubs)
  for(k in 1:nhubs) {
    g <- hubnets[[hubs[k]]]
    obs[k] <- kda.analyze.test(g$RANK, g$STRENG, memb, nnodes)
  }

  # Estimate P-values.
  pvals <- rep(NA, nhubs)
  for(k in which(obs > 0)) {
    g <- hubnets[[hubs[k]]]

    # First pass.
    x <- kda.analyze.simulate(obs[k], g, nmemb, nnodes, 200)

    # Estimate preliminary P-value.
    param <- tool.normalize(x)
    z <- tool.normalize(obs[k], param)
    p <- pnorm(z, lower.tail=FALSE)
    if(p*nhubs > 2.0) next

    # Estimate final P-value.
    n <- (nsim - length(x))
    y <- kda.analyze.simulate(obs[k], g, nmemb, nnodes, n)
    param <- tool.normalize(c(x, y))
    z <- tool.normalize(obs[k], param)
    p <- pnorm(z, lower.tail=FALSE)
    p <- max(p, .Machine$double.xmin)
    if(p*nhubs > 1.0) next

    # Apply Bonferroni adjustment.
    pvals[k] <- p*nhubs
  }
  return(pvals)
}
  
#----------------------------------------------------------------------------

kda.analyze.simulate <- function(o, g, nmemb, nnodes, nsim) {
  neigh <- as.integer(g$RANK)
  w <- as.double(g$STRENG)

  # Simulate null distribution.
  nfalse <- 0
  x <- rep(NA, nsim)
  for(n in 1:nsim) {
    if(nfalse > 10) break
    memb <- sample.int(nnodes, nmemb) 
    x[n] <- kda.analyze.test(neigh, w, memb, nnodes)
    if(is.na(x[n])) x[n] <- rnorm(1)
    nfalse <- (nfalse + as.integer(x[n] >= o))
  }

  # Trim results.
  x <- x[which(0*x == 0)]
  return(x)
}

#----------------------------------------------------------------------------

#kda.analyze.test <- function(ind, w, members, nnodes) {
#  shared <- which(match(ind, members) > 0)
#  obsmass <- sum(w[shared])
#  return(obsmass)
#}

#----------------------------------------------------------------------------

kda.analyze.test <- function(neigh, w, members, nnodes) {
  
  # Check if enough neighbors.
  nneigh <- length(neigh)
  if(nneigh < 3) return(0.0) 
  
  # Find member nodes.
  nmemb <- length(members)
  pos <- match(neigh, members)
  shared <- which(pos > 0)
  
  # Background edge mass.
  totmass <- sum(w)
  
  # Edge mass captured by members.
  obsmass <- sum(w[shared])

  # Effective number of shared nodes.
  rho <- (obsmass/totmass)
  nobserv <- rho*nneigh

  # Expected number of shared nodes.
  nexpect <- (nmemb/nnodes)*nneigh

  # Calculate enrichment score.
  if(nobserv < 1.0) return(NA); 
  z <- (nobserv - nexpect)/(sqrt(nexpect) + 1.0)
  return(z)
}
