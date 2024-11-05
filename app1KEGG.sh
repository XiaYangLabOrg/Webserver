#!/bin/bash
random=$1

cd /home/smha118/mergeomics/html/Data/Pipeline/Resources/shinyapp1_temp # change to right location
​
Rscript ./$random"app1KEGG.R"