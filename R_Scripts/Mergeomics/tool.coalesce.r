# Calculate overlaps between groups of items.
#
# Input:
#   items     array of item identities
#   groups    array of group identities for items
#
# Optional input:
#   rcutoff   maximum overlap not coalesced
#   ncore     minimum number of items required for trimming
#
# Output:
#   res       data frame:
#             CLUSTER  cluster identities
#             ITEM     item identities
#             GROUPS   comma separated group identities
#
# Due to trimming, output may contain fewer distinct items.
# Cluster identities are a subset of group identities.
#  
# Written by Ville-Petteri Makinen 2013
#
tool.coalesce <- function(items, groups, rcutoff=0.0, ncore=NULL) {

  # Check arguments.
  if(length(items) != length(groups))
    stop("Incompatible inputs.")
   
  # Default output.
  res <- data.frame(CLUSTER=groups, GROUPS=groups,
                    ITEM=items, stringsAsFactors=FALSE)
  if(rcutoff >= 1.0) return(res)
  
  # Check that group names are usable.  
  grlabels <- unique(groups)
  if(is.character(grlabels)) {
    for(s in grlabels) {
      segm <- strsplit(s, ",", fixed=TRUE)
      if(length(segm[[1]]) < 2) next
      stop("Group labels must not contain ','.")
    }
  }

  # Determine core item set size.
  nitems <- length(items)
  if(is.null(ncore)) {
    ncore <- nitems/length(unique(groups))
    ncore <- round(ncore)
  }

  # Convert to integers.
  itemlev <- as.factor(items)
  grouplev <- as.factor(groups)
  members <- as.integer(itemlev)
  modules <- as.integer(grouplev)
  itemlev <- levels(itemlev)
  grouplev <- levels(grouplev)

  # Determine item freguencies.
  freq <- table(members)
  labels <- as.integer(names(freq))
  freq <- as.integer(freq)
  
  # Limit the number of comparisons.
  kappa <- 0
  while(TRUE) {
    kappa <- (kappa + 1)
    shared <- which(freq > kappa)
    shared <- labels[shared]
    
    # Determine groups with overlaps.
    pos <- match(members, shared)
    rows <- which(pos > 0)
    mods <- unique(modules[rows])
    if(length(mods) < 2000) break
  }

  # Show warning if overlaps too extensive.
  if(kappa > 1) {
    cat("WARNING! Limited overlap analysis due ")
    cat("to large number of groups.\n")
  }
    
  # Determine subset with shared items.
  pos <- match(modules, mods)
  incl <- which(pos > 0)
  excl <- setdiff((1:nitems), incl)
  
  # Find and trim clusters.
  res <- tool.coalesce.exec(members[incl], modules[incl], rcutoff, ncore)
  res <- rbind(res, tool.coalesce.exec(members[excl], modules[excl], 1.0))
  
  # Convert identities back to original.
  res$ITEM <- itemlev[res$ITEM]
  res$CLUSTER <- grouplev[res$CLUSTER]
  groupdat <- rep("", nrow(res))
  groupsets <- as.character(res$GROUPS)  
  for(i in 1:nrow(res)) {
    gset <- strsplit(groupsets[i], ",", fixed=TRUE)
    gset <- as.integer(gset[[1]])
    groupdat[i] <- paste(grouplev[gset], collapse=",")
  }
  res$GROUPS <- groupdat
  return(res)
}

#---------------------------------------------------------------------------

tool.coalesce.exec <- function(items, groups, rcutoff, ncore) {
  if(is.numeric(items) == FALSE) stop("Unusable input.")
  if(is.numeric(groups) == FALSE) stop("Unusable input.")

  # Default output.
  res <- data.frame(CLUSTER=groups, GROUPS=groups, ITEM=items,
                    stringsAsFactors=FALSE)
  if(rcutoff >= 1.0) return(res)

  # Iterative merging and trimming.
  res$COUNT <- 0.0
  while(TRUE) {
    clust <- tool.coalesce.find(res, rcutoff)    
    if(is.null(clust)) break
    res <- tool.coalesce.merge(clust, ncore)
  }

  # Select columns.
  res$COUNT <- NULL
  res$CLUSTER <- 0
  itemdat <- res$ITEM
  clustdat <- res$CLUSTER
  groupdat <- res$GROUPS
  
  # Select representative label for clusters.
  st <- tool.aggregate(res$GROUP)
  blocks <- st$blocks
  labels <- st$labels
  for(k in 1:length(blocks)) {
    rows <- blocks[[k]]
    locals <- itemdat[rows]
    nodes <- strsplit(labels[[k]], ",", fixed=TRUE)
    nodes <- as.numeric(nodes[[1]])
    overlaps <- rep(0, length(nodes))
    for(i in 1:length(nodes)) {
      mask <- which(groups == nodes[i])
      shared <- intersect(items[mask], locals)
      overlaps[i] <- length(shared)
    }
    mask <- order(overlaps, decreasing=TRUE)
    clustdat[rows] <- nodes[mask[1]]
    groupdat[rows] <- paste(nodes[mask], collapse=",")
  }

  # Finish results.
  res$CLUSTER <- clustdat
  res$GROUPS <- groupdat
  return(res)
}

#---------------------------------------------------------------------------

tool.coalesce.find <- function(data, rmax) {

  # Harmonize column names.
  data <- data[,c("GROUPS", "ITEM", "COUNT")]
  names(data) <- c("NODE", "ITEM", "COUNT")

  # Find clusters.
  edges <- tool.overlap(items=data$ITEM, groups=data$NODE)
  clustdat <- tool.cluster(edges, cutoff=rmax)
  nclust <- length(unique(clustdat$CLUSTER))
  nnodes <- length(unique(clustdat$NODE))    
  if(nclust >= nnodes) return(NULL)
    
  # Merge with original dataset.
  res <- merge(clustdat, data)
  return(res)
}

#---------------------------------------------------------------------------

tool.coalesce.merge <- function(data, ncore) {

  # Determine item clusters.
  st <- tool.aggregate(data$CLUSTER)
  blocks <- st$blocks

  # Trim clusters.
  res <- data.frame()
  for(k in 1:length(blocks)) {
    rows <- blocks[[k]] 
    batch <- data[rows,]
    
    # Item hit counts.
    st <- tool.aggregate(batch$ITEM)
    counts <- as.integer(st$lengths)
    labels <- as.integer(st$labels)
    segm <- st$blocks
     
    # Add hit counts from previous round.
    for(j in 1:length(segm)) {
      mask <- segm[[j]]
      nj <- mean(batch[mask,"COUNT"])
      counts[j] <- sqrt(counts[j] + nj)
    }
    
    # Remove rarest items.
    levels <- sort(unique(counts))
    for(kappa in levels) {
      mask <- which(counts > kappa)
      nmask <- length(mask)
      if(nmask < ncore) break
      counts <- counts[mask]
      labels <- labels[mask]
    }

    # Collect groups.
    nodeset <- unique(batch$NODE)
    nodeset <- paste(nodeset, collapse=",")

    # Update results.
    tmp <- data.frame(GROUPS=nodeset, ITEM=labels,
                      COUNT=counts, stringsAsFactors=FALSE)
    res <- rbind(res, tmp)
  }
  return(res)
}
