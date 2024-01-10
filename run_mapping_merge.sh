#!/bin/bash

random=$1

/u/local/Modules/default/init/modules.sh


cd /var/www/mergeomics/html/Data/Pipeline

DEST="/var/www/mergeomics/html/Data/Pipeline/Resources/ssea_temp/"$random"_cat_GWAS"
FILES=$(grep -v '^#' "/var/www/mergeomics/html/Data/Pipeline/Resources/ssea_temp/"$random"GWAS_file_list")

echo -e "GENE\tMARKER" >$DEST
for FILE in $FILES
do
sed -e'1d' $FILE >>$DEST
done