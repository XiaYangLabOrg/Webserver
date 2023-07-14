#
# Convert an edge dataset into indexed graph representation.
# The input is a data frame with three columns TAIL, HEAD and WEIGHT
# for the edge end-points.
#
# Return value:
#   res$nodes      - N-element array of node names
#   res$tails      - K-element array of node indices
#   res$heads      - K-element array of node indices
#   res$weights    - K-element array of edge weights
#   res$tail2edge  - N-element list of adjacent edge indices
#   res$head2edge  - N-element list of adjacent edge indices
#   res$outstats   - N-row data frame of node statistics
#   res$instats    - N-row data frame of node statistics
#   res$stats      - N-row data frame of node statistics
#
# Written by Ville-Petteri Makinen 2013
#
tool.graph <- function(edges) {
  tails <- as.character(edges$TAIL)
  heads <- as.character(edges$HEAD)
  wdata <- as.double(edges$WEIGHT)

  # Remove empty end-points and non-positive weights.
  mask <- which((tails != "") & (heads != "") &
                (tails != heads) & (wdata > 0))
  tails <- tails[mask]
  heads <- heads[mask]
  wdata <- wdata[mask]
  nedges <- length(mask)

  # Create factorized representation.
  labels <- as.character(c(tails, heads))
  labels <- as.factor(labels)
  labelsT <- as.integer(labels[1:nedges])
  labelsH <- as.integer(labels[(nedges+1):(2*nedges)])
  
  # Create edge lists.
  nodnames <- levels(labels)
  nnodes <- length(nodnames)
  elistT <- tool.graph.list(labelsT, nnodes)
  elistH <- tool.graph.list(labelsH, nnodes)
    
  # Collect results.
  res <- list()
  res$nodes <- as.character(nodnames)
  res$outstats <- tool.graph.degree(elistT, wdata)
  res$instats <- tool.graph.degree(elistH, wdata)
  res$stats <- (res$outstats + res$instats)
  res$tail2edge <- elistT
  res$head2edge <- elistH
  res$tails <- as.integer(labelsT)
  res$heads <- as.integer(labelsH)
  res$weights <- wdata
  return(res)
}

#---------------------------------------------------------------------------

tool.graph.list <- function(entries, nnodes) {

  # Allocate list.
  groups <- list()
  for(i in 1:nnodes)
    groups[[i]] <- integer()
  
  # Find entry groups.
  st <- tool.aggregate(entries)
  labels <- as.integer(st$labels)
  blocks <- st$blocks

  # Reorganize according to node indices.
  nblocks <- length(blocks)
  for(k in 1:nblocks) {
    ind <- labels[[k]]
    groups[[ind]] <- blocks[[k]]
  }
  
  # Return edge lists.
  return(groups)
}

#---------------------------------------------------------------------------

tool.graph.degree <- function(node2edge, weights) {
  nnodes <- length(node2edge)
  stren <- rep(0.0, nnodes)
  degrees <- rep(0, nnodes)
  for(i in 1:nnodes) {
    rows <- node2edge[[i]]
    degrees[i] <- length(rows)
    if(degrees[i] < 1) next
    stren[i] <- sum(weights[rows])
  }
  res <- data.frame(DEGREE=degrees, STRENG=stren)
  return(res)
}
