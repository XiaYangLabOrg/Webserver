#
# Translate words.
#
# Written by Ville-Petteri Makinen 2013
#
tool.translate <- function(words, from, to) {

  # Check translation table.
  if(length(from) != length(to)) stop("Incompatible inputs.")
  rows <- which((is.na(from) == FALSE) & (is.na(to) == FALSE))
  from <- as.character(from[rows])
  to <- as.character(to[rows])
  
  # Find words that can be translated.
  pos <- match(words, from)
  words[which(is.na(pos))] <- NA

  # Translate words.
  rows <- which(pos > 0)
  pos <- pos[rows]
  words[rows] <- to[pos]
  return(words)
}
