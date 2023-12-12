#
# Generate input files for Himmeli. The network visualization
# is a streamlined depiction of the module enrichment in hub
# neighborhoods. For instance, only links that connect a key
# driver to another node are depicted.
#
# Input:
#   job               KDA data list as returned by kda.finish()
#
# Optional input:
#   modules           array of module names to be visualized
#   ndrivers          maximum number of drivers per module
#
# Written by Ville-Petteri Makinen 2013
#
kda2himmeli <- function(job, modules=NULL, ndrivers=5) {

  # Import node values.
  cat("\nImporting node data...\n")
  valdata <- tool.read(job$nodfile)

  # Set node sizes.
  z <- as.double(valdata$VALUE)
  z <- (z/quantile(z, 0.95) + rank(z)/length(z))
  valdata$SIZE <- pmin(4.0, z)

  # Select subset of genes.
  valdata <- kda2himmeli.identify(valdata, "NODE", job$graph$nodes)
  print(summary(valdata))

  # Select top scoring modules.
 cat("\nForwarding KDA results to Himmeli...\n")
  if(is.null(modules) & (is.null(job$ssearesults) == FALSE)) {
    tmp <- job$ssearesults
    tmp <- tmp[order(tmp$P),]
    modules <- tmp$modules
    if(length(modules) > 8) modules <- modules[1:8]
  }

  # Convert module names to indices.
  if(is.null(modules) == FALSE) {
    modules <- match(modules, job$modules)
    modules <- modules[which(modules > 0)]
    if(length(modules) < 1) stop("Unknown module names.")
  }

  # Select top key drivers from each module.
  drivers <- kda2himmeli.drivers(job$results, modules, ndrivers)
  mods <- unique(drivers$MODULE)
  palette <- kda2himmeli.colormap(length(mods))

  # Process each module separately.
  edgdata <- data.frame()
  noddata <- data.frame()
  for(i in 1:length(mods)) {
    rows <- which(drivers$MODULE == mods[i])
    tmp <- kda2himmeli.exec(job, valdata, drivers[rows,], mods, palette)
    edgdata <- rbind(edgdata, tmp$edat)
    noddata <- rbind(noddata, tmp$vdat)
  }

  # Create work folder.
  dpath <- file.path(job$folder, "himmeli")
  if(file.exists(dpath) == FALSE)
    dir.create(path=dpath, recursive=TRUE)

  # Save data files.
  edgfile <- file.path(job$folder, "kda2himmeli.edges.txt")
  nodfile <- file.path(job$folder, "kda2himmeli.nodes.txt")
  tool.save(frame=edgdata, file=edgfile)
  tool.save(frame=noddata, file=nodfile)
  
  # Configuration data.
  gname <- file.path(dpath, job$label)
  instr <- paste("GraphName", gname, sep="\t")
  instr <- c(instr, paste("EdgeFile", edgfile, sep="\t"))
  instr <- c(instr, paste("EdgeTailVariable", "TAIL", sep="\t"))
  instr <- c(instr, paste("EdgeHeadVariable", "HEAD", sep="\t"))
  instr <- c(instr, paste("EdgeWeightVariable", "WEIGHT", sep="\t"))
  instr <- c(instr, paste("EdgeColorVariable", "COLOR", sep="\t"))
  instr <- c(instr, paste("VertexFile", nodfile, sep="\t"))
  instr <- c(instr, paste("VertexNameVariable", "NODE", sep="\t"))
  instr <- c(instr, paste("VertexColorVariable", "COLOR", sep="\t"))
  instr <- c(instr, paste("VertexLabelVariable", "LABEL", sep="\t"))
  instr <- c(instr, paste("VertexShapeVariable", "SHAPE", sep="\t"))
  instr <- c(instr, paste("VertexSectorVariable", "SECTOR", sep="\t"))
  instr <- c(instr, paste("VertexSizeVariable", "SIZE", sep="\t"))
  instr <- c(instr, paste("DistanceUnit", "1.1", sep="\t"))
  instr <- c(instr, paste("ChassisMode", "on", "2.0", sep="\t"))

  # Color info.
  modnames <- job$modules[mods]
  for(j in 1:ncol(palette)) {
    c <- palette[,j]
    value <- sprintf("%02d%02d%02d", c[1], c[2], c[3])
    value <- paste("VertexColorInfo", modnames[j], value, sep="\t")
    instr <- c(instr, value)
  }

  # Save configuration data.
  fname <- file.path(job$folder, "kda2himmeli.config.txt")
  write.table(x=instr, sep="\t", file=fname, na="",
              row.names=FALSE, quote=FALSE)
  cat("\rSaved ", length(instr), " rows in '", fname, "'.\n", sep="")
  return(job)
}

#----------------------------------------------------------------------------

kda2himmeli.exec <- function(job, valdata, drivers, modpool, palette) {
  
  # Create star topology.
  edgdata <- data.frame()
  for(i in unique(drivers$NODE)) {
    tmp <- kda2himmeli.edges(job$graph, i, job$depth, job$direction)
    edgdata <- rbind(edgdata, tmp)
  }

  # Select affected nodes.
  tmp <- c(edgdata$TAIL, edgdata$HEAD)
  pos <- match(valdata$NODE, tmp)
  valdata <- valdata[which(pos > 0),]
  
  # Assign node shapes.
  valdata$SHAPE <- "circle"
  pos <- match(valdata$NODE, drivers$NODE);
  valdata[which(pos > 0),"SHAPE"] <- "star"
  
  # Trace module memberships.
  noddata <- kda2himmeli.colorize(valdata, job$moddata, modpool, palette)
  
  # Trim edge dataset.
  edgdata <- edgdata[which(edgdata$TAIL != edgdata$HEAD),]
  edgdata <- unique(edgdata[,c("TAIL", "HEAD", "WEIGHT")])
  edgdata$COLOR <- "808080"
  
  # Restore original identities.
  edgdata$TAIL <- job$graph$nodes[edgdata$TAIL]
  edgdata$HEAD <- job$graph$nodes[edgdata$HEAD]
  noddata$NODE <- job$graph$nodes[noddata$NODE]
  noddata$LABEL <- noddata$NODE
  
  # Make identities unique for the current module.
  modtag <- job$modules[drivers[1,"MODULE"]]
  for(i in 1:nrow(noddata)) 
    noddata[i,"NODE"] <- paste(noddata[i,"NODE"], modtag, sep="@")
  for(i in 1:nrow(edgdata)) {
    edgdata[i,"TAIL"] <- paste(edgdata[i,"TAIL"], modtag, sep="@")
    edgdata[i,"HEAD"] <- paste(edgdata[i,"HEAD"], modtag, sep="@")
  }
  
  # Return results.
  res <- list(edat=edgdata, vdat=noddata)
  return(res)
}  

#----------------------------------------------------------------------------

kda2himmeli.drivers <- function(data, modules, ndriv) {
  nmods <- 8 # optimal number of colors

  # Select modules.
  if(is.null(modules) == FALSE) {
    nmods <- length(unique(modules))
    pos <- match(data$MODULE, modules)
    data <- data[which(pos > 0),]
    if(nrow(data) < 1) stop("No data on target modules.")
  }
  
  # Include only significant key drivers.
  rows <- which(data$FDR < 0.05)
  data <- data[rows,]
  if(nrow(data) < 1) stop("No key drivers for target modules.")

  # Separate modules.
  st <- tool.aggregate(data$MODULE)
  blocks <- st$blocks
 
  # Collect top drivers.
  peaks <- double()
  scores <- data$P
  for(k in 1:length(blocks)) {
    rows <- blocks[[k]]  
    peaks[k] <- min(scores[rows])
  }

  # Collect drivers from top modules.
  ind <- integer()
  mask <- order(peaks)
  mask <- mask[1:min(length(mask),nmods)]
  for(k in mask) { 
    rows <- blocks[[k]]  
    rows <- rows[order(scores[rows])]
    rows <- rows[1:min(length(rows),ndriv)]
    ind <- c(ind, rows)
  }
  return(data[ind,c("MODULE","NODE")])
}

#----------------------------------------------------------------------------

kda2himmeli.edges <- function(graph, center, depth, direction) {
  g <- tool.subgraph.search(graph, center, depth, direction)
  if(direction >= 0) {
    g$HEAD <- g$RANK
    g$TAIL <- center
  } else {
    g$TAIL <- g$RANK
    g$HEAD <- center
  }
  g$WEIGHT <- (g$STRENG)/(1.0 + g$LEVEL)
  return(g)
}

#---------------------------------------------------------------------------

kda2himmeli.colorize <- function(noddata, moddata, modpool, palette) {

  # Collect module memberships.
  pos <- match(moddata$NODE, noddata$NODE)
  moddata <- moddata[which(pos > 0),c("MODULE","NODE")]
  moddata <- unique(moddata)
  
  # Merge duplicate rows.
  st <- tool.aggregate(moddata$NODE)
  blocks <- st$blocks
  colors <- rep(NA, length(blocks))
  sectors <- rep("", length(blocks))
  for(k in 1:length(blocks)) {
    rows <- blocks[[k]]
    mods <- moddata[rows,"MODULE"]
    mods <- intersect(mods, modpool)
    mods <- sort(unique(mods))
    n <- length(mods)
    if(n < 1) next
    if(n < 2) {
      c <- palette[,which(modpool == mods[1])]
      colors[k] <- sprintf("%02d%02d%02d", c[1], c[2], c[3])
      next;
    }
    for(i in 1:n) {
      c <- palette[,which(modpool == mods[i])]
      c <- sprintf("%02d%02d%02d", c[1], c[2], c[3])
      if(i < 2) {
        sectors[k] <- paste(sectors[k], "1:", c, sep="")
      } else {
        sectors[k] <- paste(sectors[k], ",1:", c, sep="")
      }
    }
  }

  # Combine results.
  res <- data.frame(NODE=as.integer(st$labels), COLOR=colors,
                    SECTOR=sectors, stringsAsFactors=FALSE)
  res <- merge(noddata, res, all.x=TRUE)

  # Fill in missing values.
  rows <- which(is.na(res$COLOR))
  res[rows,"COLOR"] <- "909090"
  return(res)
}

#----------------------------------------------------------------------------

kda2himmeli.colormap <- function(ncolors) {
  palette <- rainbow(n=(ncolors + 2))
  palette <- (col2rgb(palette)/255)

  # Dampen raw colors.
  kappa <- pmax(0.25*palette[1,], 0.25*palette[2,], 0.4*palette[3,])
  palette[1,] <- ((1.0 - kappa)*palette[1,] + kappa)
  palette[2,] <- ((1.0 - kappa)*palette[2,] + kappa)
  palette <- round(99*palette)

  # Remove strongest greens (easy to confuse on screen).
  while(ncol(palette) > ncolors) {
    rb <- (palette[1,] + palette[3,]) 
    palette <- palette[,order(rb)]
    ind <- which.max(palette[,2])
    mask <- setdiff(1:ncol(palette), ind)
    palette <- palette[,mask]
  }
  
  # Sort by components.
  x <- (10000*palette[1,] + 100*palette[2,] + palette[3,])
  return(palette[,order(x)])
}

#----------------------------------------------------------------------------

kda2himmeli.identify <- function(dat, varname, labels) {
  if(nrow(dat) < 1) return(dat)
  
  # Find matching identities.
  pos <- match(dat[,varname], labels)
  rows <- which(pos > 0)

  # Select subset.
  dat[,varname] <- pos
  res <- dat[rows,]
  return(res)
}

