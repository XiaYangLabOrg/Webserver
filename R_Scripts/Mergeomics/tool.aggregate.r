#
# Sort an array and find the indices of blocks with the same values.
# The second argument sets the minimum block size to be included.
#
# Output list:
#   res$labels     shared values within blocks
#   res$lengths    numbers of entries in blocks
#   res$blocks     integer arrays of entry positions within blocks
#   res$ranks      entry positions included in blocks
#
# Written by Ville-Petteri Makinen 2013
#
tool.aggregate <- function(entries, limit=1) {
  res <- list()
  res$blocks <- list()
  
  # Check input size.
  nelem <- length(entries)
  if(nelem < 1) stop("Unusable input.")
  
  # Factorize entries.
  entries <- as.factor(entries)

  # Convert factors to integers.
  elevels <- levels(entries)
  entries <- as.integer(entries)
  
  # Sort entries.
  mask <- order(entries)

  # Remove missing entries.  
  rows <- which(entries > 0)
  mask <- intersect(mask, rows)
  if(length(mask) < 1) stop("Unusable input.")
  
  # Single entry.
  if(length(mask) < 2) {
    label <- na.omit(elevels)
    res$labels <- label
    res$lengths <- 1
    res$blocks[[1]] <- 1
    res$ranks <- 1
    return(res)
  }
  
  # Starting point.
  nstack <- 1
  stack <- integer(length=nelem)
  stack[nstack] <- mask[1]
  prev <- entries[mask[1]]
  mask <- mask[2:nelem]

  # Find segments of identical entries.
  buffer <- list()
  subsets <- list()
  for(i in mask) {
    if(entries[i] != prev) {

      # Clear stack.
      if(nstack >= limit) {
        ind <- (length(buffer) + 1)
        buffer[[ind]] <- stack[1:nstack]
      }
      nstack <- 0

      # Buffering for speed-up.
      if(length(buffer) > 120) {
        subsets <- c(subsets, buffer)
        buffer <- list()
      }
    }

    # Add item to stack.
    nstack <- (nstack + 1)
    stack[nstack] <- i
    prev <- entries[i]
  }

  # Clear last item(s).
  if(nstack >= limit) {
    ind <- (length(buffer) + 1)
    buffer[[ind]] <- stack[1:nstack]
  }

  # Clear buffer.
  if(length(buffer) > 0)
    subsets <- c(subsets, buffer)

  # Check if any subsets.
  nuniq <- length(subsets)
  if(nuniq < 1) return(NULL)
  
  # Determine additional attributes.
  loci <- rep(NA, nelem)
  sizes <- integer(nuniq)
  identities <- integer(nuniq)
  for(k in 1:nuniq) {
    mask <- as.integer(subsets[[k]])
    identities[k] <- entries[mask[1]]
    sizes[k] <- length(mask)
    loci[mask] <- mask
  }
  
  # Finish.
  res$labels <- elevels[identities]
  res$lengths <- sizes
  res$blocks <- subsets
  res$ranks <- na.omit(loci)
  return(res)
}
