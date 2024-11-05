#!/bin/bash

random=$1


cd /home/smha118/mergeomics/html/Data/Pipeline

mkdir ./Results/meta_ssea/$random".meta.inter.results"

chmod -R 0777 ./Results/meta_ssea/$random".meta.inter.results"

mkdir ./Results/meta_ssea/$random"_meta_result"

chmod -R 0777 ./Results/meta_ssea/$random"_meta_result"

Rscript ./$random"METAanalyze.R"