#
# Calculate overlaps between groups of items.
#
# Input:
#   items     array of item identities
#   groups    array of group identities for items
#
#  Optional input:
#   nbackground   total number of items
#
#  Output:
#   res       data frame:
#             A      group name
#             B      group name
#             POSa   group name rank
#             POSb   group name rank
#             Na     group A size
#             Nb     group B size
#             Nab    shared items
#             R      overlap ratio
#             F      fold change to null expectation
#             P      overlap P-value (Fisher's test)
#
# Written by Ville-Petteri Makinen 2013
#
tool.overlap <- function(items, groups, nbackground=NULL) {

  # Check arguments.
  if(length(items) != length(groups))
    stop("Incompatible inputs.")
  
  # Remove duplicate entries.
  data <- data.frame(ITEM=items, GROUP=groups, stringAsFactors=FALSE)
  data <- unique(data)

  # Convert to integers.
  items <- as.integer(as.factor(data$ITEM))
  groups <- as.factor(data$GROUP)
  grouplevs <- levels(groups)
  groups <- as.integer(groups)
  
  # Determine block structure.
  modules <- tool.aggregate(groups)
  labels <- modules$labels
  blocks <- modules$blocks

  # Convert labels to original identities.
  labels <- as.integer(labels)
  labels <- grouplevs[labels]
  
  # Default background size.
  nitems <- length(unique(items))
  if(is.null(nbackground)) nbackground <- nitems
  if(nbackground < nitems)
    stop("tool.overlap: Invalid background size.")

  # Number of shared items for each pair of blocks.
  row <- 1
  stamp <- Sys.time()
  nblocks <- length(blocks)
  nrows <- (nblocks + 1)*nblocks/2;
  mtx <- matrix(nrow=nrows, ncol=5)
  for(a in 1:nblocks) {
    maskA <- blocks[[a]]
    setA <- items[maskA]
    nA <- length(maskA)
    for(b in a:nblocks) {
      maskB <- blocks[[b]]
      setB <- items[maskB]      
      nB <- length(setB)
      ind <- intersect(setA, setB)
      nAB <- length(ind)
      mtx[row,] <- c(a, b, nA, nB, nAB)
      row <- (row + 1)

      # Progress report.
      if(as.double(Sys.time() - stamp) < 10.0) next
      cat(sprintf("\r%d/%d ", row, nrows))
      stamp <- Sys.time()
    }
  }
  cat(sprintf("\r%d comparisons\n", nrows))

  # Calculate fold enrichment.
  numA <- mtx[,3]
  numB <- mtx[,4]
  numAB <- mtx[,5]
  fAB <- (sqrt(numAB)*sqrt(nbackground)/sqrt(numA)/sqrt(numB))^2
    
  # Estimate statistical significance.
  pAB <- phyper(numAB, numB, (nbackground - numB), numA, lower.tail=FALSE)
  pAB <- (pAB + .Machine$double.xmin)
  
  # Fix diagonal values.
  mask <- which(mtx[,1] == mtx[,2])
  fAB[mask] <- 1.0
  pAB[mask] <- 1.0

  # Fix P-values for small fold changes.
  mask <- which(fAB < 1.0)
  pAB[mask] <- 1.0
  
  # Collect results.
  res <- data.frame(A=labels[mtx[,1]], B=labels[mtx[,2]],
                    POSa=as.integer(mtx[,1]), POSb=as.integer(mtx[,2]),
                    Na=numA, Nb=numB, Nab=numAB,
                    R=numAB/(numA + numB - numAB),
                    F=fAB, P=pAB, stringsAsFactors=FALSE)
  return(res)
}
