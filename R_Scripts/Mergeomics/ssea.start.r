#
# Create a job for SNP set enrichment analysis
#
# Input:
#   plan$label       unique identifier for the analysis
#   plan$folder      output folder for results
#   plan$modfile     path to module file
#                    columns: MODULE GENE
#   plan$locfile     path to locus file
#                    columns: LOCUS VALUE
#   plan$genfile     path to gene file
#                    columns: GENE LOCUS
#
# Optional input:
#   plan$inffile     path to module info file
#                    columns: MODULE DESCR
#   plan$seed        seed for random number generator
#   plan$permtype    'gene' for gene-level, 'locus' for locus-level    
#   plan$nperm       maximum number of random permutations
#   plan$mingenes    minimum number of genes per module (after merging)
#   plan$maxgenes    maximum number of genes per module
#   plan$quantiles   cutoffs for test statistic
#   plan$maxoverlap  maximum overlap allowed between genes
#
# Output:
#   job$modules       module identities as characters
#   job$genes         gene identities as characters
#   job$loci          locus identities as characters
#   job$moddata       preprocessed module data (indexed identities)
#   job$modinfo       preprocessed module info (indexed identities)
#   job$gendata       preprocessed mapping data (indexed identities)
#   job$locdata       preprocessed locus data (indexed identities)
#   job$geneclusters  genes with shared loci
#
#  Output also includes the items from input list.
#
# Written by Ville-Petteri Makinen 2013
#
ssea.start <- function(plan) {

  # Check parameters.
  job <- ssea.start.configure(plan)

  # Create output folder.
  dir.create(path=job$folder, recursive=FALSE, showWarnings=FALSE)
  if(file.access(job$folder, 2) != 0)
    stop("Cannot access '" + job$folder + "'.")

  # Import gene sets.
  cat("\nImporting modules...\n")
  modinfo <- tool.read(plan$inffile, c("MODULE", "DESCR"))
  moddata <- tool.read(plan$modfile, c("MODULE", "GENE"))
  moddata <- unique(na.omit(moddata))
  modinfo <- unique(na.omit(modinfo))
  if(nrow(modinfo) > 0) print(summary(modinfo))
  print(summary(moddata))

  # Add slots for control modules.
  modules <- unique(moddata$MODULE)
  if((sum(modules == "_ctrlA") > 0) | (sum(modules == "_ctrlB") > 0))
    stop("Module names '_ctrlA' and '_ctrlB' are reserved.")
  modules <- c(modules, "_ctrlA", "_ctrlB")
  tmp <- data.frame(MODULE=c("_ctrlA", "_ctrlB"),
                    DESCR=c("Top genes", "Top genes (module members)"))
  modinfo <- rbind(modinfo, tmp)
  
  # Import locus values.
  cat("\nImporting locus values...\n")
  locdata <- tool.read(job$locfile, c("LOCUS", "VALUE"))
  locdata$VALUE <- as.double(locdata$VALUE)
  rows <- which(0*(locdata$VALUE) == 0)
  locdata <- unique(na.omit(locdata[rows,]))
  print(summary(locdata))
    
  # Import mapping data. 
  cat("\nImporting mapping data...\n")
  gendata <- tool.read(job$genfile, c("GENE", "LOCUS"))
  gendata <- unique(na.omit(gendata))
  print(summary(gendata))
 
  # Remove genes with no locus values.
  pos <- match(gendata$LOCUS, locdata$LOCUS)
  gendata <- gendata[which(pos > 0),]
  
  # Merge overlapping genes.
  cat("\nMerging genes containing shared loci...\n")
  gendata <- tool.coalesce(items=gendata$LOCUS, groups=gendata$GENE,
                           rcutoff=job$maxoverlap)
  job$geneclusters <- gendata[,c("CLUSTER","GROUPS")]
  job$geneclusters <- unique(job$geneclusters)

  # Update gene symbols.
  moddata <- ssea.start.relabel(moddata, gendata)  
  gendata <- unique(gendata[,c("GROUPS", "ITEM")])
  names(gendata) <- c("GENE", "LOCUS")
  
  # Collect identities.
  job$modules <- modules
  job$loci <- intersect(gendata$LOCUS, locdata$LOCUS)
  pos <- match(gendata$LOCUS, job$loci)
  job$genes <- gendata[which(pos > 0), "GENE"]
  job$genes <- unique(job$genes)
  
  # Exclude missing data and factorize identities.
  job$modinfo <- ssea.start.identify(modinfo, "MODULE", job$modules)
  job$moddata <- ssea.start.identify(moddata, "MODULE", job$modules)
  job$moddata <- ssea.start.identify(job$moddata, "GENE", job$genes)
  job$gendata <- ssea.start.identify(gendata, "GENE", job$genes)
  job$gendata <- ssea.start.identify(job$gendata, "LOCUS", job$loci)
  job$locdata <- ssea.start.identify(locdata, "LOCUS", job$loci)
  
  # Show job size.
  nmem <- (object.size(job))*(0.5^20)
  cat("Job: ", nmem, " Mb\n", sep="")

  # Clean-up.
  remove(modinfo)
  remove(moddata)
  remove(gendata)
  remove(locdata)
  gc(FALSE)
  return(job)
}

#----------------------------------------------------------------------------

ssea.start.configure <- function(plan) {
  cat("\nParameters:\n")
  plan$stamp <- Sys.time()
  if(is.null(plan$folder)) stop("No output folder.")
  if(is.null(plan$label)) stop("No job label.")
  if(is.null(plan$modfile)) stop("No module file.")
  if(is.null(plan$genfile)) stop("No gene file.")
  if(is.null(plan$locfile)) stop("No locus file.")

  if(is.null(plan$permtype)) plan$permtype <- "gene"
  cat("  Permutation type: ", plan$permtype, "\n", sep="")

  if(is.null(plan$nperm)) plan$nperm <- 20000
  cat("  Permutations: ", plan$nperm, "\n", sep="")

  if(is.null(plan$seed)) plan$seed <- 1
  cat("  Random seed: ", plan$seed, "\n", sep="")

  if(is.null(plan$mingenes)) plan$mingenes <- 10
  if(is.null(plan$maxgenes)) plan$maxgenes <- 500
  cat("  Minimum gene count: ", plan$mingenes, "\n", sep="")
  cat("  Maximum gene count: ", plan$maxgenes, "\n", sep="")

  if(is.null(plan$maxoverlap)) plan$maxoverlap <- 0.33
  if(plan$permtype == "locus") plan$maxoverlap <- 1.0 # no effect
  cat("  Maximum overlap between genes: ", plan$maxoverlap, "\n", sep="")
  
  if(is.null(plan$quantiles) == FALSE) {
    cat("  Test quantiles:");
    for(q in plan$quantiles)
      cat(sprintf(" %.2f", 100*q), "%", sep="")
    cat("\n")
  }
  return(plan)
}

#----------------------------------------------------------------------------

ssea.start.relabel <- function(dat, grp) {
  
  # New gene group symbols.
  oldgenes <- character()
  newgenes <- character()
  syms <- unique(grp[,c("CLUSTER","GROUPS")])
  rows <- which(syms$CLUSTER != syms$GROUPS)
  for(i in rows) {
    g <- syms[i,"GROUPS"]
    a <- strsplit(g, ",", fixed=TRUE)
    a <- a[[1]]
    b <- rep(g, length(a))
    oldgenes <- c(oldgenes, a)
    newgenes <- c(newgenes, b)
  }
  
  # Update dataset.
  if(length(newgenes) < 1) return(dat)
  pos <- match(dat$GENE, oldgenes)
  rows <- which(pos > 0)
  dat[rows,"GENE"] <- newgenes[pos[rows]]
  return(unique(dat))
}

#----------------------------------------------------------------------------

ssea.start.identify <- function(dat, varname, labels) {
  if(nrow(dat) < 1) return(dat)
  
  # Find matching identities.
  pos <- match(dat[,varname], labels)
  rows <- which(pos > 0)

  # Select subset.
  dat[,varname] <- pos
  res <- dat[rows,]
  return(res)
}

