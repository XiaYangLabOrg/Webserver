#
# One-sided.
#
tool.normalize <- function(x, prm=NULL, inverse=FALSE) {
  
  # Apply transform.
  if(is.null(prm) == FALSE) {
    if(inverse == TRUE) {
      if(is.na(prm$mu)) {
        y <- (x*(prm$scale) + prm$offset)
        return(y)
      }
      z <- (x*(prm$sigma) + prm$mu)
      z <- (exp(z) - 1.0)/(prm$scale)
      z <- (z + prm$offset)
      return(z)
    } else {
      z <- (x - prm$offset)*(prm$scale)
      if(is.na(prm$mu)) return(z)
      mask <- which(z < 0.0)
      z[mask] <- z[mask]/(1.0 - z[mask])
      z <- (log(z + 1.0) - prm$mu)/(prm$sigma)
    }
    return(z)
  }

  # Remove unusable values.
  x <- x[which(0*x == 0)]
  
  # Default parameters.
  prm$offset <- mean(x)
  prm$scale <- sd(x)
  prm$mu <- NA
  prm$sigma <- NA
  prm$quality <- 0.0
  if(length(x) < 10) return(prm)
    
  # Disable warnings.
  prev <- getOption("warn"); options(warn=-1)

  # Ensure positivity and check size.
  xmin <- min(x)
  z <- (x[which(x > xmin)] - xmin)
  if(length(z) < 10) return(prm)
 
  # Scale by median.
  zmed <- median(z)
  z <- z/zmed

  # Find the best log transform.
  ctrl <- list(reltol=1e-3)
  gamma <- optim(par=1.0, fn=tool.normalize.quality, gr=NULL, z,
                 lower=-9, upper=9, control=ctrl)
  
  # Apply transform.
  z <- log(exp(gamma$par)*z + 1.0)
  
  # Evaluate fit quality.
  mu <- mean(z)
  sigma <- sd(z)
  z <- (z - mu)/sigma
  kappa <- ks.test(x=z, y="pnorm", exact=FALSE)

  # Enable warnings.
  options(warn=prev)
  
  # Collect transformation parameters.
  prm$offset <- xmin
  prm$scale <- exp(gamma$par)/zmed
  prm$quality <- kappa$p.value
  prm$mu <- mu
  prm$sigma <- sigma  
  return(prm)
}

#----------------------------------------------------------------------------

tool.normalize.quality <- function(g, z) {
  t <- log(exp(g)*z + 1.0)
  t <- (t - mean(t))/(sd(t) + 1e-20)
  if(length(t) < 1) return(0)
  suppressWarnings(res <- ks.test(x=t, y="pnorm", exact=FALSE))
  return(res$statistic)
}
