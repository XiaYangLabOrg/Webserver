#!/bin/bash

random=$1


/u/local/Modules/default/init/modules.sh


cd /home/www/abhatta3-webserver/Data/Pipeline

bash ./$random"preprocess.bash"