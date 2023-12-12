#
# Determine the network neighbors for a set of nodes. The first
# argument is the indexed graph structure from tool.graph().
# The second argument is the list of seed node names and the third
# indicates the maximum nuber of links to connect neighbors.
# The fourth input sets the directionality: use a negative value
# for dowstream, positive for upstream or zero for undirected.
#
# Output:
#   res$RANK    - indices of neighboring nodes (including seeds)
#   res$LEVEL   - num of edges away from seed 
#   res$STRENG  - sum of adjacent edge weights within neighborhood
#   res$DEGREE  - number of adjacent edges within neighborhood
#
# Written by Ville-Petteri Makinen 2013
#
tool.subgraph <- function(graph, seeds, depth=1, direction=0) {
  depth <- as.integer(depth[[1]])
  direction <- as.integer(direction[[1]])

  # Convert seed names to indices.
  nodes <- graph$nodes
  ranks <- match(seeds, nodes)
  ranks <- ranks[which(ranks > 0)]
  ranks <- as.integer(ranks)
  
  # Find neighbors.
  res <- tool.subgraph.search(graph, ranks, depth[1], direction)
  return(res)
}

#---------------------------------------------------------------------------

tool.subgraph.search <- function(graph, seeds, depth, direction) {
  tails <- graph$tails
  heads <- graph$heads
  weights <- graph$weights
  tail2edge <- list()
  head2edge <- list()

  # Downstream and upstream searches.
  if(direction <= 0) tail2edge <- graph$tail2edge
  if(direction >= 0) head2edge <- graph$head2edge

  # Collect neighboring nodes.
  visited <- seeds
  levels <- 0*seeds
  for(i in 1:depth) {
     
    # Find edges to adjacent nodes.
    foundT <- tool.subgraph.find(seeds, tail2edge, heads, visited)
    foundH <- tool.subgraph.find(seeds, head2edge, tails, visited)
    
    # Expand neighborhood.
    seeds <- unique(c(foundT, foundH))
    visited <- c(visited, seeds)
    levels <- c(levels, (0*seeds + i))
    if(length(seeds) < 1) break
  }
  
  # Calculate node degrees and strengths.
  res <- data.frame(RANK=visited, LEVEL=levels, DEGREE=0,
                    STRENG=0.0, stringsAsFactors=FALSE)
  res <- tool.subgraph.stats(res, tail2edge, heads, weights)
  res <- tool.subgraph.stats(res, head2edge, tails, weights)
  return(res)
}

#---------------------------------------------------------------------------

tool.subgraph.find <- function(seeds, edgemap, dirct, visited) {
  if(length(edgemap) < 1) return(integer())
  
  # Collect all neighboring nodes.
  nneigh <- length(seeds)
  neighbors <- seeds
  for(i in seeds) {
    
    # Edges adjacent to the seed.
    mask <- edgemap[[i]]
    nmask <- length(mask)
    if(nmask < 1) next

    # Check capacity.
    if((length(neighbors) - nneigh) < nmask)
      neighbors <- c(neighbors, 0*neighbors, 0*mask)

    # Store node indices.    
    neighbors[nneigh+(1:nmask)] <- dirct[mask]
    nneigh <- (nneigh + nmask)
  }

  # Remove nodes already visited.
  neighbors <- unique(neighbors[1:nneigh])
  neighbors <- setdiff(neighbors, visited)
  return(neighbors)
}

#---------------------------------------------------------------------------

tool.subgraph.stats <- function(frame, edgemap, dirct, weights) {
  if(length(edgemap) < 1) return(frame)
  nmemb <- nrow(frame)
  wcounts <- frame$DEGREE
  wsums <- frame$STRENG
  members <- frame$RANK
  for(i in members) {
    edges <- edgemap[[i]]
    pos <- match(dirct[edges], members)
    edges <- edges[which(pos > 0)]
    if(length(edges) < 1) next
    rows <- match(dirct[edges], members)
    wcounts[rows] <- (wcounts[rows] + 1)
    wsums[rows] <- (wsums[rows] + weights[edges])
  }
  frame$DEGREE <- wcounts
  frame$STRENG <- wsums
  return(frame)
}
