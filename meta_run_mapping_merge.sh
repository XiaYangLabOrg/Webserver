#!/bin/bash

random=$1

/u/local/Modules/default/init/modules.sh


cd /home/www/abhatta3-webserver/Data/Pipeline

DEST="/home/www/abhatta3-webserver/Data/Pipeline/Resources/meta_temp/"$random"_cat_GWAS"
FILES=$(grep -v '^#' "/home/www/abhatta3-webserver/Data/Pipeline/Resources/meta_temp/"$random"GWAS_file_list")

echo -e "GENE\tMARKER" >$DEST
for FILE in $FILES
do
sed -e'1d' $FILE >>$DEST
done