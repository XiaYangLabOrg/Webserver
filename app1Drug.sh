#!/bin/bash
random=$1

cd /home/www/abhatta3-webserver/Data/Pipeline/Resources/shinyapp1_temp # change to right location

/home/www/abhatta3-webserver/R-3.4.4/bin/Rscript ./$random"app1Drug.R"