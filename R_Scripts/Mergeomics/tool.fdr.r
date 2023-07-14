# 
# Written by Ville-Petteri Makinen 2013
#
tool.fdr <- function(p, f=NULL) {
  if(length(p) < 5) return(NA*p)
  if(is.null(f)) return(tool.fdr.bh(p))
  return(tool.fdr.empirical(p, f))
}

#---------------------------------------------------------------------------

tool.fdr.bh <- function(p) {
  nelem <- length(p)

  # Revert to z-scores.
  pvals <- pmax(p, .Machine$double.xmin)
  pvals <- pmin(pvals, (1 - .Machine$double.eps))
  z <- qnorm(pvals)
  
  # Benjamini–Hochberg (1995) false discovery rate.
  fdr <- p.adjust(pvals, method="fdr")
  fdr <- pmax(fdr, .Machine$double.xmin)

  # Sort data points.
  mask <- order(z)
  xcoord <- z[mask]
  ycoord <- fdr[mask]
  
  # Remove steps in FDR values.
  prev <- 1
  for(i in 2:nelem) {
    if(ycoord[i] == ycoord[prev]) {
      ycoord[i] = NA
      next
    }
    window <- prev:(i-1)
    xcoord[prev] <- mean(xcoord[window])
    prev <- i
  }

  # Select distinct points.
  rows <- which(0*ycoord == 0)
  xcoord <- xcoord[rows]
  ycoord <- ycoord[rows]

  # Add sentinels.
  xcoord <- c((min(z) - 1.0), xcoord, (max(z) + 1.0))
  ycoord <- c(0.0, ycoord, 1.0)
  
  # Interpolate missing points.
  points <- approx(xcoord, ycoord, xout=z)
  return(points$y)
}

#---------------------------------------------------------------------------

tool.fdr.empirical <- function(p, f0) {
  
  # Estimate raw false discovery rates.
  f <- rank(p)/length(p)
  fdr <- pmin(pmax(f0/f, p), 1)
 
  # Pre-defined sections for sampling the FDR curve.
  p <- pmin(p, (1.0 - .Machine$double.eps))
  p <- pmax(p, .Machine$double.xmin)
  z <- qnorm(p, lower.tail=TRUE)
  alpha <- sort(unique(floor(z)))

  # Estimate pivot points.
  xcoord <- (min(z) - 1.0)
  ycoord <- 0.0
  for(a in alpha) {
    o <- (a + 1.0)
    elem <- which((z >= a) & (z < o))
    xcoord <- c(xcoord, mean(z[elem]))
    ycoord <- c(ycoord, mean(fdr[elem]))
  }  
  
  # Interpolate back to the original resolution.
  points <- approx(xcoord, ycoord, xout=z)
  return(points$y)
}
