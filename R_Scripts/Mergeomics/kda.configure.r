#
# Set parameters for a key driver analysis
#
# Input:
#   plan$label        unique identifier for the analysis
#   plan$folder       parent folder for results
#   plan$netfile      path to network file
#                     columns: TAIL HEAD WEIGHT
#   plan$modfile      path to module file
#                     columns: MODULE NODE
#
# Optional:
#   plan$inffile      path to module info file
#                     columns: MODULE DESCR
#   plan$nodfile      path to node selection file
#                     columns: NODE
#   plan$depth        search depth for subgraph search
#   plan$direction    use zero for undirected, negative for
#                     downstream and positive for upstream
#   plan$maxoverlap   maximum allowed overlap between two
#                     key driver neighborhoods
#   plan$minsize      minimum module size
#   plan$mindegree    minimum node degree to qualify as a hub
#   plan$maxdegree    maximum node degree to include
#   plan$edgefactor   influence of node strengths:
#                     0.0 no influence, 1.0 full influence
#   plan$seed         seed for random number generator
#
# Output:
#   job               data structure for KDA
#
# Written by Ville-Petteri Makinen 2013
#
kda.configure <- function(plan) {

  cat("\nParameters:\n")
  plan$stamp <- Sys.time()
  if(is.null(plan$folder)) stop("No parent folder.")
  if(is.null(plan$label)) stop("No job label.")
  if(is.null(plan$netfile)) stop("No network file.")
  if(is.null(plan$modfile)) stop("No module file.")

  if(is.null(plan$depth)) plan$depth <- 1
  plan$depth <- round(plan$depth)
  if(plan$depth < 1) stop("Unusable search depth.")
  cat("  Search depth: ", plan$depth, "\n", sep="")

  if(is.null(plan$direction)) plan$direction <- 0
  plan$direction <- round(plan$direction)
  if(abs(plan$direction) > 1) stop("Unusable search direction.")
  cat("  Search direction: ", plan$direction, "\n", sep="")

  if(is.null(plan$maxoverlap)) plan$maxoverlap <- 0.33
  if(plan$maxoverlap > 1) stop("Unusable overlap limit.")
  if(plan$maxoverlap < 0) stop("Unusable overlap limit.")
  cat("  Maximum overlap: ", plan$maxoverlap, "\n", sep="")

  if(is.null(plan$minsize)) plan$minsize <- 20
  if(plan$minsize < 1) stop("Unusable size limit.")
  cat("  Minimum module size: ", plan$minsize, "\n", sep="")

  if(is.null(plan$mindegree)) plan$mindegree <- "automatic"
  if(is.null(plan$maxdegree)) plan$maxdegree <- "automatic"
  cat("  Minimum degree: ", plan$mindegree, "\n", sep="")
  cat("  Maximum degree: ", plan$maxdegree, "\n", sep="")

  if(is.null(plan$edgefactor)) plan$edgefactor <- 0.5
  if(plan$edgefactor > 1) stop("Unusable edge factor.")
  if(plan$edgefactor < 0) stop("Unusable edge factor.")
  cat("  Edge factor: ", plan$edgefactor, "\n", sep="")
  
  if(is.null(plan$seed)) plan$seed <- 1
  cat("  Random seed: ", plan$seed, "\n", sep="")
  return(plan)
}
