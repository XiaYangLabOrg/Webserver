#!/bin/bash

random=$1


cd /var/www/mergeomics/html/Data/Pipeline

#mkdir $output

#chmod -R 0777 Results

Rscript ./$random"enrichment.R"