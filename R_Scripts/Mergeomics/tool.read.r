#
# Read a data frame from a file. All lines with NAs are excluded.
# The second argument selects a subset of columns.
#
# Written by Ville-Petteri Makinen 2013
#
tool.read <- function(file, vars=NULL) {
  if(is.null(file)) return(data.frame())
  if(file == "") return(data.frame())
  dat <- read.delim(file=file, header=TRUE,
                    na.strings=c("NA", "NULL", "null", ""),
                    colClasses="character", comment.char="",
                    stringsAsFactors=FALSE)
  if(is.null(vars) == FALSE) dat <- dat[,vars]
  dat <- na.omit(dat)
  return(dat)
}
