#!/bin/bash
random=$1

cd /var/www/mergeomics/html/Data/Pipeline/Resources/shinyapp1_temp # change to right location
​
Rscript ./$random"app1KEGG.R"