#
# Convert a distribution to uniform ranks ]0,1[ with respect
# to a background distribution (or self if no
# background available).
#
# Written by Ville-Petteri Makinen 2013
#
tool.unify <- function(xtrait, xnull=NULL) {
  if(is.null(xnull)) xnull <- xtrait
  nt <- length(xtrait)
  n0 <- length(xnull)

  # Enforce distinct values.
  xmin <- min(xnull)
  sigma <- (max(xnull) - xmin)
  amp <- 0.001*sd(xnull)
  jitter <- seq(-amp/2, amp/2, length.out=n0)
  xnull <- (xnull + jitter)
  xnull <- (xnull - min(xnull))
  xnull <- xnull/max(xnull)
  xnull <- (xmin + xnull*sigma)
  
  # Cumulative reference distribution.
  f0 <- seq(0, 1, length.out=n0)
  q0 <- quantile(xnull, f0)
  q0 <- as.double(q0)

  # Make sure null range contains trait values.
  xmin <- min(xtrait)
  xmax <- max(xtrait)  
  if(xmin < q0[1]) q0[1] <- xmin
  if(xmax > q0[n0]) q0[n0] <- xmax

  # Map observations on null cumulative axis.
  out <- approx(x=q0, y=f0, xout=xtrait)
  return(out$y)
}
