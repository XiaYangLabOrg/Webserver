#
#
#
tool.metap <- function(datasets, idcolumn, pcolumn, weights=NULL) {

  # Collect identities.
  id <- character()
  nsets <- length(datasets)
  for(i in 1:nsets) {
    dat <- datasets[[i]]
    id <- as.character(dat[,idcolumn])
  }

  # Remove duplicates.
  id <- unique(id[which(id != "")])

  # Check weights.
  if(is.null(weights)) weights <- rep(1, nsets)
  if(length(weights) != nsets) stop("Incompatible weights.")
  if(sum(weights <= 0.0) > 0) stop("Non-positive weights.")
  weights <- nsets*(weights/sum(weights))

  # Calculate meta scores.
  h <- rep(0, length(id))
  z <- rep(0.0, length(id))
  for(i in 1:nsets) {
    dat <- datasets[[i]]
    pos <- match(dat[,idcolumn], id)  
    rows <- which(pos > 0)
    pos <- pos[rows]
    p <- as.double(dat[rows,pcolumn])
    p <- pmax(p, .Machine$double.xmin)
    p <- pmin(p, (1 - .Machine$double.eps))
    z[pos] <- (z[pos] + weights[i]*qnorm(p))
    h[pos] <- (h[pos] + (weights[i])^2)
  }

  # Estimate meta P-values.
  rows <- which(h > 0.0)
  z[rows] <- z[rows]/sqrt(h[rows])
  pmeta <- pnorm(z)

  # Return results.
  res <- data.frame(ID=id, P=pmeta)
  names(res) <- c(idcolumn, pcolumn)
  res <- res[order(res$P),]
  return(res)
}
