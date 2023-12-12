#
# Generate input files for cytoscape. The network visualization
# is a streamlined depiction of the module enrichment in hub
# neighborhoods. Hence, only links that connect a key
# driver to another node are depicted within a particular depth.
#
# Input:
#   job               KDA data list as returned by kda.finish()
#
# Optional input:
#   modules           array of module names to be visualized
#   ndrivers          maximum number of drivers per module
#   depth 	      search depth of subgraphs
#
# Written by Ville-Petteri Makinen 2013, modified by Zeyneb Kurt 2015
#
kda2cytoscape <- function(job, modules=NULL, nmodules=NULL, ndrivers=5, depth=1) {

  # Import node values.
  cat("\nImporting node data...\n")
  valdata <- tool.read(job$nodfile)

  # Set node sizes.
  z <- as.double(valdata$VALUE)
  z <- (z/quantile(z, 0.95) + rank(z)/length(z))
  valdata$SIZE <- pmin(4.0, z)

  # Select subset of genes.
  valdata <- kda2cytoscape.identify(valdata, "NODE", job$graph$nodes)
  print(summary(valdata))

  # Convert module names to indices.
  if(!is.null(modules)) {
    modules <- match(modules, job$modules)
    modules <- modules[which(modules > 0)]
    if(length(modules) < 1) stop("Unknown module names.")
  }

  # If modules are not specified, assign them from the results.
  if(is.null(modules)){
    if(!is.null(job$results)) {
      tmp <- job$results
      tmp <- tmp[order(tmp$P),]
      modules <- unique(tmp$MODULE)
      }
  }

  if (!is.null(nmodules) && (length(modules) > nmodules) )
    modules <- modules[1:nmodules]

  # Select top key drivers from each module.
  drivers <- kda2cytoscape.drivers(job$results, modules, nmodules, ndrivers)
  mods <- unique(drivers$MODULE)
  modnames <- job$modules[mods]
  palette <- kda2cytoscape.colormap(length(mods))


  # Create work folder.
  dpath <- file.path(job$folder, "cytoscape")
  if(file.exists(dpath) == FALSE)
    dir.create(path=dpath, recursive=TRUE)
  # Save top KDAs into file
  drivers$MODNAMES <- modnames[match(drivers$MODULE, mods)]
  drivers$NODNAMES <- valdata[match(drivers$NODE, valdata$NODE), "GENE"]
  for(i in 1:dim(drivers)[1])
    drivers$COLOR[i] <- sprintf("#%02d%02d%02d", palette[1,match(drivers$MODULE[i], mods)], 
    palette[2,match(drivers$MODULE[i], mods)], palette[3,match(drivers$MODULE[i], mods)])

  kdafile <- file.path(dpath, "kda2cytoscape.top.kdas.txt")
  tool.save(frame=drivers, file=kdafile) 
   

  # Process each module separately.
  edgdata <- data.frame()
  noddata <- data.frame()
  for(i in 1:length(mods)) {
    rows <- which(drivers$MODULE == mods[i])
    tmp <- kda2cytoscape.exec(job, valdata, drivers[rows,], mods, palette, depth)
    tmpfile <- file.path(dpath, paste("mod_", i, "_edges.txt", sep=""))
    tool.save(frame=tmp$edat, file=tmpfile)  
    tmpfile <- file.path(dpath, paste("mod_", i, "_nodes.txt", sep=""))
    tool.save(frame=tmp$vdat[, c("MODULE", "NODE", "GENE", "COLOR", "SIZE", "SHAPE", "SECTOR", "URL", "LABELSIZE")], file=tmpfile)  
    edgdata <- rbind(edgdata, tmp$edat)
    noddata <- rbind(noddata, tmp$vdat)
  }

  # Save data files.
  edgfile <- file.path(dpath, "kda2cytoscape.edges.txt")
  nodfile <- file.path(dpath, "kda2cytoscape.nodes.txt")
  tool.save(frame=edgdata, file=edgfile)
  tool.save(frame=noddata[, c("MODULE", "NODE", "GENE", "COLOR", "SIZE", "SHAPE", "SECTOR", "URL", "LABELSIZE")], file=nodfile)


  # Color info.
  instr <- NULL
  instr <- paste("MODULES", "COLOR", sep="\t")

  for(j in 1:ncol(palette)) {
    c <- palette[,j]
    value <- sprintf("#%02d%02d%02d", c[1], c[2], c[3])
    value <- paste(modnames[j], value, sep="\t")
    instr <- c(instr, value)
  }

  fname <- file.path(dpath, "module.color.mapping.txt")
  write.table(x=instr, sep="\t", file=fname, na="", row.names=FALSE, quote=FALSE, col.names=TRUE)

  cat("\rInformation is stored into relevant files.\n", sep="")
  return(job)
}

#----------------------------------------------------------------------------

kda2cytoscape.exec <- function(job, valdata, drivers, modpool, palette, graph.depth=1) {
  
  # Create star topology.
  edgdata <- data.frame()
  for(i in unique(drivers$NODE)) {
    # tmp <- kda2cytoscape.edges(job$graph, i, job$depth, job$direction)
    tmp <- kda2cytoscape.edges(job$graph, i, graph.depth, job$direction)
    edgdata <- rbind(edgdata, tmp)
  }

  # Select affected nodes.
  tmp <- c(edgdata$TAIL, edgdata$HEAD)
  pos <- match(valdata$NODE, tmp)
  valdata <- valdata[which(pos > 0),]
  
  # Assign node shapes.
  valdata$SHAPE <- "ellipse"
  valdata$SIZE <- 50
  pos <- match(valdata$NODE, drivers$NODE);
  valdata[which(pos > 0),"SHAPE"] <- "diamond"
  valdata[which(pos > 0),"SIZE"] <- 100
  
  # Trace module memberships.
  noddata <- kda2cytoscape.colorize(valdata, job$moddata, modpool, palette)
  
  # Trim edge dataset.
  edgdata <- edgdata[which(edgdata$TAIL != edgdata$HEAD),]
  edgdata <- unique(edgdata[,c("TAIL", "HEAD", "WEIGHT")])
  edgdata$COLOR <- "#808080"
  
  # Restore original identities.
  edgdata$TAIL <- job$graph$nodes[edgdata$TAIL]
  edgdata$HEAD <- job$graph$nodes[edgdata$HEAD]
  noddata$NODE <- job$graph$nodes[noddata$NODE]
  noddata$LABEL <- noddata$NODE
  
  # Make identities unique for the current module.
  modtag <- job$modules[drivers[1,"MODULE"]]
  noddata[1:nrow(noddata), "MODULE"] <- modtag
  edgdata[1:nrow(edgdata), "MODULE"] <- modtag

  # Return results.
  res <- list(edat=edgdata, vdat=noddata)
  return(res)
}  

#----------------------------------------------------------------------------

kda2cytoscape.drivers <- function(data, modules, nmodules=NULL, ndriv) {

  nmods <- length(unique(data$MODULE))
  # Select modules.
  if(!is.null(modules)) {
    nmods <- length(unique(modules))
    pos <- match(data$MODULE, modules)
    data <- data[which(pos > 0),]
    if(nrow(data) < 1) stop("No data on target modules.")
  }
  if (!is.null(nmodules)) 
    nmods <- nmodules

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

kda2cytoscape.edges <- function(graph, center, depth, direction) {
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

kda2cytoscape.colorize <- function(noddata, moddata, modpool, palette) {

  # Google chart service.
  urlbase <- "http://chart.apis.google.com/chart?cht=p&chs=200x200"
  urlbase <- paste(urlbase, "chf=bg,s,00000000", sep="&")

  # Collect module memberships.
  pos <- match(moddata$NODE, noddata$NODE)
  moddata <- moddata[which(pos > 0),c("MODULE","NODE")]
  moddata <- unique(moddata)
  
  # Merge duplicate rows.
  st <- tool.aggregate(moddata$NODE)
  blocks <- st$blocks
  colors <- rep(NA, length(blocks))
  sectors <- rep("", length(blocks))
  urls <- rep("", length(blocks))

  for(k in 1:length(blocks)) {
    chd <- ""
    chco <- ""
    rows <- blocks[[k]]
    mods <- moddata[rows,"MODULE"]
    mods <- intersect(mods, modpool)
    mods <- sort(unique(mods))
    n <- length(mods)
    if(n < 1) next

    for(i in 1:n) {
      c <- palette[,which(modpool == mods[i])]
      c <- sprintf("%02d%02d%02d", c[1], c[2], c[3])
      if(i < 2) {
        chd <- "chd=t:1"
        chco <- paste("chco=", c, sep="")

        cc <- paste("#", c, sep="")
        colors[k] <- cc

        sectors[k] <- paste(sectors[k], "1:", c, sep="")
      } else {
        chd <- paste(chd, 1, sep=",")
        chco <- paste(chco, c, sep="|")
        sectors[k] <- paste(sectors[k], ",1:", c, sep="")
      }
    }
    urls[k] <- paste(urlbase, chd, chco, sep="&")
  }

  # Combine results.
  label.size <- rep(12, length(blocks))
  res <- data.frame(NODE=as.integer(st$labels), COLOR=colors, SECTOR=sectors, URL=urls, LABELSIZE=label.size, stringsAsFactors=FALSE)
  res <- merge(noddata, res, all.x=TRUE)

  # Fill in missing values.
  rows <- which(is.na(res$COLOR))
  res[rows,"COLOR"] <- "#909090"

  res[which(res$SIZE == 100), "COLOR"] <- colors[1]
  res[which(res$SIZE == 100), "LABELSIZE"] <- 18


  return(res)
}

#----------------------------------------------------------------------------

kda2cytoscape.colormap <- function(ncolors) {
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

kda2cytoscape.identify <- function(dat, varname, labels) {
  if(nrow(dat) < 1) return(dat)
  
  # Find matching identities.
  pos <- match(dat[,varname], labels)
  rows <- which(pos > 0)

  # Select subset.
  dat[,varname] <- pos
  res <- dat[rows,]
  return(res)
}

