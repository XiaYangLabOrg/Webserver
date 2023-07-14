#
# Import data for key driver analysis.
#
# Input:
#   job               data structure for KDA
#
# Output:
#   job$graph         indexed topology, see tool.graph()
#   job$modules       module identities
#   job$modinfo       module descriptions (indexed)
#   job$moddata       module data (indexed)
#                     columns: MODULE NODE
#   job$module2nodes  lists of node indices for each module
#   job$modulesizes   module sizes
#
# Written by Ville-Petteri Makinen 2013
#
kda.start <- function(job) {

  # Import topology.
  edgdata <- kda.start.edges(job)
  moddata <- kda.start.modules(job, edgdata)

  # Import module descriptions.
  modinfo <- tool.read(job$inffile, c("MODULE", "DESCR"))
  modinfo <- unique(modinfo)
  if(nrow(modinfo) > 0) print(summary(modinfo))

  # Create an indexed graph structure.
  job$graph <- tool.graph(edgdata)
  nmem <- (object.size(job$graph))*(0.5^20)
  cat("Graph: ", nmem, " Mb\n", sep="")
  remove(edgdata)
  gc(FALSE)

  # Convert identities to indices.
  modules <- unique(moddata$MODULE)
  modinfo <- kda.start.identify(modinfo, "MODULE", modules)
  moddata <- kda.start.identify(moddata, "MODULE", modules)
  moddata <- kda.start.identify(moddata, "NODE", job$graph$nodes)

  # Collect module members.
  st <- tool.aggregate(moddata$MODULE)
  blocks <- st$blocks
  members <- as.integer(moddata$NODE)
  for(k in 1:length(blocks)) {
    mask <- blocks[[k]]
    blocks[[k]] <- members[mask]
  }

  # Finish results.
  job$modules <- modules
  job$modinfo <- modinfo
  job$moddata <- moddata
  job$module2nodes <- blocks
  job$modulesizes <- st$lengths
  return(job)
}

#----------------------------------------------------------------------------

kda.start.edges <- function(job) {
  
  # Import edge data.
  cat("\nImporting edges...\n")
  varnames <- c("TAIL", "HEAD", "WEIGHT")
  edgdata <- tool.read(file=job$netfile, vars=varnames)
  edgdata$WEIGHT <- suppressWarnings(as.double(edgdata$WEIGHT))
  edgdata <- edgdata[which(edgdata$WEIGHT > 0),]

  # Collect node names.
  nodes <- character()
  if(job$direction >= 0) nodes <- c(nodes, edgdata$TAIL)
  if(job$direction <= 0) nodes <- c(nodes, edgdata$HEAD)

  # Select nodes.
  if(is.null(job$nodfile) == FALSE) {
    cat("Selecting nodes...\n")
    symdata <- tool.read(file=job$nodfile, vars=c("NODE"))
    nodes <- intersect(as.character(symdata), nodes)
  }

  # Calculate node degrees.
  struct <- tool.aggregate(nodes)
  degrees <- struct$lengths
  nodes <- struct$labels

  # Automatic maximum degree.
  if(job$maxdegree == "automatic") {
    dmax <- floor(0.05*length(nodes))
    mask <- which(degrees <= dmax)
    degrees <- degrees[mask]
    nodes <- nodes[mask]
    job$maxdegree <- dmax
  }

  # Filter edges.
  posT <- match(edgdata$TAIL, nodes)
  posH <- match(edgdata$HEAD, nodes)
  rows <- which(posT*posH > 0)
  edgdata <- edgdata[rows,]

  # Print report.
  print(summary(edgdata))
  return(edgdata)
}

#----------------------------------------------------------------------------

kda.start.modules <- function(job, edgdata) {

  # Import module data.
  cat("\nImporting modules...\n")
  moddata <- tool.read(file=job$modfile, vars=c("MODULE", "NODE"))
  moddata <- unique(moddata)

  # Collect node names.
  nodes <- character()
  if(job$direction >= 0) nodes <- c(nodes, edgdata$TAIL)
  if(job$direction <= 0) nodes <- c(nodes, edgdata$HEAD)

  # Filter module members.
  pos <- match(moddata$NODE, nodes)
  moddata <- moddata[which(pos > 0),]

  # Remove small modules.
  st <- tool.aggregate(moddata$MODULE)
  mask <- which(st$lengths >= job$minsize)
  mods <- as.character(st$labels[mask])
  pos <- match(moddata$MODULE, mods)
  moddata <- moddata[which(pos > 0),]

  # Print report.
  print(summary(moddata))
  return(moddata)
}

#----------------------------------------------------------------------------

kda.start.identify <- function(dat, varname, labels) {
  if(nrow(dat) < 1) return(dat)

  # Find matching identities.
  pos <- match(dat[,varname], labels)
  rows <- which(pos > 0)

  # Select subset.
  dat[,varname] <- pos
  res <- dat[rows,]
  return(res)
}
