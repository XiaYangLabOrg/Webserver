#
# Generate inputs for key driver analysis.
#
# Input:
#   job               SSEA data list as returned by ssea.finish().
#
# Optional input:
#   symbols           data.frame for translating gene symbols
#                     columns: FROM TO
#   rmax              maximum allowed overlap between gene sets
#
# Output:
#   plan$label        unique identifier for the analysis
#   plan$parent       parent folder for results
#   plan$modfile      path to module file
#                     columns: MODULE NODE
#   plan$inffile      path to module info file
#                     columns: MODULE DESCR
#   plan$nodfile      path to node selection file
#                     columns: NODE
#
# Written by Ville-Petteri Makinen 2013
#
ssea2kda <- function(job, symbols=NULL, rmax=NULL) {
  cat("\nForwarding SSEA results to KDA...\n")
  if(is.null(rmax)) rmax <- 0.33

  # Collect genes and top loci from original files.
  noddata <- ssea2kda.import(job$genfile, job$locfile)
  
  # Select candidate modules.
  res <- job$results
  res <- res[order(res$P),]
  rows <- which(res$FDR < 0.25)
  if(length(rows) < 20) rows <- (1:20)
  if(length(rows) < nrow(res)) res <- res[rows,]

  # Collect member genes.
  moddata <- job$moddata
  pos <- match(moddata$MODULE, res$MODULE)
  moddata <- moddata[which(pos > 0),]
  
  # Restore original identities.
  modinfo <- job$modinfo
  modinfo$MODULE <- job$modules[modinfo$MODULE]
  moddata$MODULE <- job$modules[moddata$MODULE]
  moddata$GENE <- job$genes[moddata$GENE]
  
  # Merge and trim overlapping modules.
  moddata$OVERLAP <- moddata$MODULE
  moddata <- tool.coalesce(items=moddata$GENE, groups=moddata$MODULE,
                           rcutoff=rmax)
  moddata$MODULE <- moddata$CLUSTER
  moddata$GENE <- moddata$ITEM
  moddata$OVERLAP <- moddata$GROUPS
  moddata <- moddata[,c("MODULE", "GENE", "OVERLAP")]
  moddata <- unique(moddata)
  
  # Calculate enrichment scores for merged modules.
  tmp <- unique(moddata[,c("MODULE","OVERLAP")])
  res <- ssea2kda.analyze(job, moddata)
  res <- merge(res, tmp)
  res <- merge(res, modinfo, all.x=TRUE)    
  
  # Mark modules with overlaps.
  for(i in which(moddata$MODULE != moddata$OVERLAP))
    moddata[i,"MODULE"] <- paste(moddata[i,"MODULE"], "..", sep=",")
  for(i in which(res$MODULE != res$OVERLAP))
    res[i,"MODULE"] <- paste(res[i,"MODULE"], "..", sep=",")

  # Separate merged genes.
  nodes <- character()
  genenames <- job$genes
  for(i in 1:length(genenames)) {
    segm <- strsplit(genenames[i], ",", fixed=TRUE)
    nodes <- c(nodes, segm[[1]])
  }

  # Expand rows with merged genes.
  tmp <- data.frame(stringsAsFactors=FALSE)
  for(i in 1:nrow(moddata)) {
    segm <- strsplit(moddata[i,"GENE"], ",", fixed=TRUE)
    segm <- segm[[1]]
    if(length(segm) < 2) next
    batch <- data.frame(MODULE=moddata[i,"MODULE"], GENE=segm,
                        stringsAsFactors=FALSE)
    tmp <- rbind(tmp, batch)
    moddata[i,] <- NA
  }
  moddata <- na.omit(moddata[,c("MODULE","GENE")])
  moddata <- unique(rbind(moddata, tmp))
  
  # Translate gene symbols.
  moddata$NODE <- moddata$GENE
  noddata$NODE <- noddata$GENE
  if(is.null(symbols) == FALSE) {
    moddata$NODE <- tool.translate(words=moddata$NODE, from=symbols$FROM,
                                   to=symbols$TO)
    noddata$NODE <- tool.translate(words=noddata$NODE, from=symbols$FROM,
                                   to=symbols$TO)
    moddata <- na.omit(moddata)
    noddata <- na.omit(noddata)
  }
  
  # Save module info for KDA.
  res <- res[order(res$P),]
  inffile <- "ssea2kda.info.txt"
  tool.save(frame=res, file=inffile, directory=job$folder)  
 
  # Save modules for KDA.
  modfile <- "ssea2kda.modules.txt"
  tool.save(frame=unique(moddata[,c("MODULE", "NODE", "GENE")]),
            file=modfile, directory=job$folder)  

  # Save nodes for KDA.
  nodfile <- "ssea2kda.nodes.txt"
  tool.save(frame=unique(noddata[,c("NODE", "GENE", "LOCUS", "VALUE")]),
            file=nodfile, directory=job$folder)  

  # Return KDA plan template.
  plan <- list()
  plan$label <- job$label
  plan$folder <- job$folder
  plan$inffile <- file.path(job$folder, inffile)
  plan$modfile <- file.path(job$folder, modfile)
  plan$nodfile <- file.path(job$folder, nodfile)
  plan$ssearesults <- res
  return(plan)
}

#---------------------------------------------------------------------------

ssea2kda.import <- function(genfile, locfile) {
  
  # Import locus values.
  cat("\nImporting locus values...\n")
  locdata <- tool.read(locfile, c("LOCUS", "VALUE"))
  locdata$VALUE <- as.double(locdata$VALUE)
  rows <- which(0*(locdata$VALUE) == 0)
  locdata <- unique(na.omit(locdata[rows,]))
  print(summary(locdata))
    
  # Import mapping data. 
  cat("\nImporting mapping data...\n")
  gendata <- tool.read(genfile, c("GENE", "LOCUS"))
  gendata <- unique(na.omit(gendata))
  print(summary(gendata))
  
  # Merge datasets.
  data <- merge(gendata, locdata)

  # Find top loci.
  mask <- integer()
  st <- tool.aggregate(data$GENE)
  blocks <- st$blocks
  for(k in 1:length(blocks)) {
    rows <- blocks[[k]]
    ind <- which.max(data[rows,"VALUE"])
    mask <- c(mask, rows[ind])
  }
  return(data[mask,])
}

#---------------------------------------------------------------------------

ssea2kda.analyze <- function(job, moddata) {
  
  # Convert identities to indices.
  moddata <- ssea.start.identify(moddata, "MODULE", job$modules)
  moddata <- ssea.start.identify(moddata, "GENE", job$genes)

  # Collect row indices for each module.
  st <- tool.aggregate(moddata$MODULE)
  keys <- as.integer(st$labels)
  blocks <- st$blocks
  nmods <- length(blocks)

  # Prepare gene lists.
  genlists <- list()
  for(k in 1:nmods) genlists[[k]] <- integer()
 
  # Collect gene sets.
  modsizes <- rep(0, nmods)
  genes <- as.integer(moddata$GENE)
  for(k in 1:length(blocks)) {
    key <- keys[k]
    rows <- blocks[[k]]
    members <- unique(genes[rows])
    genlists[[key]] <- as.integer(members)
    modsizes[[key]] <- length(members)
  }

  # Determine locus set sizes.
  modlengths <- rep(0, nmods)
  moddensities <- rep(0.0, nmods)
  loclists <- job$database$gene2loci
  for(k in 1:length(genlists)) {
    locset <- integer()
    for(i in genlists[[k]])
      locset <- c(locset, loclists[[i]])
    modlengths[[k]] <- length(unique(locset))
    moddensities[[k]] <- modlengths[[k]]/length(locset)
  }

  # Update database.
  job$database$modulesizes <- modsizes
  job$database$modulelengths <- modlengths
  job$database$moduledensities <- moddensities
  job$database$module2genes <- genlists

  # Run enrichment analysis.
  job <- ssea.analyze(job)
  res <- job$results
  
  # Restore module identities.
  res$NGENES <- modsizes[res$MODULE]
  res$NLOCI <- modlengths[res$MODULE]
  res$DENSITY <- moddensities[res$MODULE]
  res$MODULE <- job$modules[res$MODULE]
  return(res)
}
