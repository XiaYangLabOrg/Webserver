#!/bin/bash

random=$1


cd Data/Pipeline;

#mkdir $output

#chmod -R 0777 Results

Rscript ./$random"analyzekda.R"