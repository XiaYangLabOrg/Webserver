#!/bin/bash

random=$1

cd /var/www/mergeomics/html/Data/Pipeline

Rscript ./$random"analyze.R"