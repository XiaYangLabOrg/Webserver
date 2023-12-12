#
# Prepare graph topology for key driver analysis.
#
# Input:
#   job$graph         see tool.graph()
#   job$depth         search depth for subgraph search
#   job$direction     use zero for undirected, negative for
#                     downstream and positive for upstream
#   job$maxoverlap    maximum allowed overlap between two
#                     key driver neighborhoods
#   job$mindegree     minimum hub degree to include
#   job$edgefactor    influence of node strengths:
#                     0.0 no influence, 1.0 full influence
#
# Output:
#   job$graph$hubs       nodes considered hubs (indexed)
#   job$graph$hubnets    lists of neighboring nodes (indexed)
#   job$graph$cohubsets  lists of overlapping hubs (indexed)
#
# Written by Ville-Petteri Makinen 2013
#
kda.prepare <- function(job) {

  # Determine minimum hub degree.
  nnodes <- length(job$graph$nodes)
  if(job$mindegree == "automatic") {
    dmin <- nnodes/median(job$modulesizes)
    job$mindegree <- round(0.1*dmin)
  }

  # Collect neighbors.
  cat("\nCollecting hubs...\n")
  job$graph <- kda.prepare.screen(job$graph, job$depth, job$direction,
                                  job$edgefactor, job$mindegree)
  if(length(job$graph$hubs) < 1) stop("No usable hubs detected.")
  
  # Collect overlapping co-hubs.
  job$graph <- kda.prepare.overlap(job$graph, job$direction,
                                   job$maxoverlap)

  # Print report.
  nhubs <- length(job$graph$hubs)
  nmem <- (object.size(job$graph))*(0.5^20)
  cat(sprintf("%d hubs (%.2f%%)\n", nhubs, 100*nhubs/nnodes))
  cat("Graph: ", nmem, " Mb\n", sep="")
  return(job)
}

#---------------------------------------------------------------------------

kda.prepare.screen <- function(graph, depth, direction, efactor, dmin) {
  stamp <- Sys.time()
  hubnets <- list()  
  accepted <- integer()
  nnodes <- length(graph$nodes)

  # Determine strength cutoff.
  stren <- rep(0, nnodes)
  if(direction <= 0) stren <- (stren + graph$outstats$STRENG)
  if(direction >= 0) stren <- (stren + graph$instats$STRENG)
  slimit <- quantile(stren, 0.75)

  # Select hubs.
  accepted <- integer()
  for(i in which(stren > slimit)) {
    g <- tool.subgraph.search(graph, i, depth, direction)

    # Apply edge factor.
    g$STRENG <- (g$STRENG)^efactor

    # Use average strength for hub itself (by definition, the hub
    # typically has huge strength within its neighborhood).
    mask <- which(g$LEVEL < 1)
    g[mask,"STRENG"] <- median(g$STRENG)
    
    # Progress report.
    delta <- as.double(Sys.time() - stamp)
    if((delta >= 10.0) & (i > 1)) {
      cat(sprintf("%d hubs (%d nodes)\n", length(accepted), i))
      stamp <- Sys.time()
    }

    # Exclude extreme neighborhoods.
    if(nrow(g) > nnodes/3) next
    if(nrow(g) < dmin) next

    # Store subnetwork.
    hubnets[[i]] <- g[,c("RANK", "STRENG")]
    accepted <- c(accepted, i)
  }
  
  # Return results.
  graph$hubs <- accepted
  graph$hubnets <- hubnets
  return(graph)
}

#---------------------------------------------------------------------------

kda.prepare.overlap <- function(graph, direction, rmax) {
  hubs <- graph$hubs
  nhubs <- length(hubs)
  hubnets <- graph$hubnets
  stamp <- Sys.time()

  # Determine node strengths.
  nnodes <- length(graph$nodes)
  stren <- rep(0.0, nnodes)
  if(direction <= 0) stren <- (stren + graph$outstats$STRENG)
  if(direction >= 0) stren <- (stren + graph$instats$STRENG)
  
  # Sort hubs according to strength.
  mask <- order(stren[hubs], decreasing=TRUE)
  hubs <- hubs[mask]

  # Collect overlapping co-hubs.
  cohubsets <- list()
  for(i in 1:nhubs) {
    key <- hubs[i]

    # Progress report.
    delta <- as.double(Sys.time() - stamp)
    if((delta >= 10.0) & (i > 1)) {
      cat(sprintf("%d/%d co-hub sets\n", i, nhubs))
      stamp <- Sys.time()
    }

    # Neibhgorhood topology.
    g <- hubnets[[key]]
    neighbors <- g$RANK
    locals <- intersect(neighbors, hubs)
    locals <- setdiff(locals, key)
    strenA <- g$STRENG

    # Calculate overlaps.
    cohubs <- integer()
    overlaps <- double()
    for(k in locals) {
      w <- hubnets[[k]]
      strenB <- w$STRENG      

      # Find overlapping nodes.
      posA <- match(neighbors, w$RANK)
      posB <- match(w$RANK, neighbors)
      sharedA <- which(posA > 0)
      sharedB <- which(posB > 0)
      uniqA <- which(is.na(posA))
      uniqB <- which(is.na(posB))

      # Calculate strength sums.
      wsharedA <- sum(strenA[sharedA])
      wsharedB <- sum(strenB[sharedB])
      wuniqA <- sum(strenA[uniqA])
      wuniqB <- sum(strenB[uniqB])
       
      # Average symmetric sum.
      wshared <- 0.5*(wsharedA + wsharedB)

      # Overlap ratio.
      r <- wshared/(wuniqA + wuniqB + wshared)
      if(r < rmax) next

      # Update data structures.
      cohubs <- c(cohubs, k)
    }

    # Store results.
    mask <- order(stren[cohubs], decreasing=TRUE)
    cohubsets[[key]] <- cohubs[mask]
  }

  # Return results.
  graph$cohubsets <- cohubsets
  return(graph)
}
