#!/bin/bash

random=$1


cd /home/www/abhatta3-webserver/Data/Pipeline

mkdir ./Results/meta_ssea/$random".meta.inter.results"

chmod -R 0777 ./Results/meta_ssea/$random".meta.inter.results"

mkdir ./Results/meta_ssea/$random"_meta_result"

chmod -R 0777 ./Results/meta_ssea/$random"_meta_result"

/home/www/abhatta3-webserver/R-3.4.4/bin/Rscript ./$random"METAanalyze.R"