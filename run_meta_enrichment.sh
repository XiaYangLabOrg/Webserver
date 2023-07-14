#!/bin/bash

random=$1


cd /home/www/abhatta3-webserver/Data/Pipeline

#mkdir $output

#chmod -R 0777 Results

/home/www/abhatta3-webserver/R-3.4.4/bin/Rscript ./$random"enrichment.R"