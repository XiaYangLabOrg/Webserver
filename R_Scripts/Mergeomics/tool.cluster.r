#
# Use hierarchical clustering to assign nodes into clusters.
#
# Input:
#   edges    data.frame:
#            A        item name
#            B        item name
#            POSa     item name rank
#            POSb     item name rank
#            R        overlap between A and B
#  cutoff    maximum overlap not considered clustered
#            
# Output:
#   res      data frame
#            CLUSTER  cluster rank
#            NODE     item name
#
# Written by Ville-Petteri Makinen 2013
#
tool.cluster <- function(edges, cutoff=NULL) {
  
  # Default output.
  a <- edges$A; b <- edges$B
  labels <- unique(c(a, b))
  res <- data.frame(CLUSTER=labels, stringsAsFactors=FALSE)
  res$NODE <- labels

  # Check if clustering is needed.
  r <- as.double(edges$R)
  posA <- as.integer(edges$POSa)
  posB <- as.integer(edges$POSb)
  ndim <- max(c(posA, posB))
  if(sum(posA != posB) < 1) return(res)
  if(max(r) <= 0.0) return(res)

  # Allocate distance matrix.
  mtx <- matrix(data=0.0, nrow=ndim, ncol=ndim)
  labels <- rep(NA, ndim)

  # Collect group labels.
  for(k in 1:nrow(edges)) {
    labels[posA[k]] <- a[k]
    labels[posB[k]] <- b[k]
  }
  
  # Recreate matrix form.
  for(k in 1:nrow(edges)) {
    i <- posA[k]
    j <- posB[k]
    mtx[i,j] <- r[k]
    mtx[j,i] <- r[k]
  }

  # Hierarchical clustering.
  d <- as.dist(1 - mtx)
  tree <- hclust(d)
  
  # Height cutoff.
  hlim <- max(tree$height)
  if(is.null(cutoff) == FALSE) hlim <- (1.0 - cutoff)
  
  # Find clusters.
  clusters <- tool.cluster.static(tree, hlim)
  
  # Enumerate clusters with singletons included.
  mask <- which(clusters == 0)
  clusters[mask] <- -mask
  clusters <- as.factor(clusters)
  clusters <- as.integer(clusters)

  # Create supergroups.
  res <- data.frame(CLUSTER=clusters, stringsAsFactors=FALSE)
  res$NODE <- labels[1:length(clusters)]
  return(res)
}

#---------------------------------------------------------------------------

tool.cluster.static <- function(dendro, hlim) {
  merged <- dendro$merge
  heights <- dendro$height
  ndim <- (length(heights) + 1)
  
  # Assign clusters by static cut.
  clusters <- rep(0, ndim)
  for(i in which(heights <= hlim)) {
    a <- as.integer(merged[i, 1])
    b <- as.integer(merged[i, 2])

    # Put previous cluster (if any) in A.
    if(a < 0) {
      tmp <- a
      a <- b
      b <- tmp
    }

    # De novo merging.
    if(a < 0) {
      clusters[-a] <- i
      clusters[-b] <- i
      next
    }

    # Merge with previous cluster.
    if(b < 0) {
      mask <- which(clusters == a)
      mask <- c(mask, -b)
      clusters[mask] <- i
      next;
    }

    # Merge two clusters.
    mask <- which((clusters == a) | (clusters == b))
    clusters[mask] <- i;
  }
  return(clusters)
}
