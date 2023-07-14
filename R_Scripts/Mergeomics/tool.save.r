#
# Save a data frame in tab-delimited file. Compression
# only works on UNIX-family systems with gzip.
#
# Written by Ville-Petteri Makinen 2013
#
tool.save <- function(frame, file, directory=NULL, verbose=TRUE,
                      compression=FALSE) {
  if(verbose) cat("\rWriting to file... ")

  # Concatenate directory prefix.
  fname <- file
  if(is.null(directory) == FALSE) {
    if(file.exists(directory) == FALSE)
      dir.create(path=directory, recursive=TRUE)
    fname <- file.path(directory, file)
  }

  # Write data to file.
  write.table(x=frame, sep="\t", file=fname, na="",
              row.names=FALSE, quote=FALSE)

  # Compress file.
  if(compression) {
    if(verbose) cat("\rCompressing file... ")
    system(paste("gzip -f \"", fname, "\"", sep=""))
    fname <- paste(fname, ".gz", sep="")
  }

  # Print report.
  n <- nrow(frame)
  if(verbose) cat("\rSaved ", n, " rows in '", fname, "'.\n", sep="")
  return(fname)  
}
