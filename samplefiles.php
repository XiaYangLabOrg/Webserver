<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<?php include_once("analyticstracking.php") ?>
 
 <!-- Includes all the font/styling/js sheets -->
<?php include_once("head.inc") ?>

<style>

ul.samplef {
	list-style-type: none;
	font-size: 20px;
}


ul.samplef > li {
	padding-left: 2%;
}

ul.ref {
	font-size: 18px;
	list-style-type: none;
}

ul.ref > li {
	padding-left: 1%;
}

a {
  color: #000;
}

a:hover{
  color:#e83c3c;
}

a.redirect {
  color:#e83c3c;
}

a.redirect:hover{
  color: #000;
}

table.assoc_resources {
	border: 1px solid gray;
    border-collapse: collapse;
    padding: 3px;
}

.assoc_resources > th {
	border: 1px solid gray;
    border-collapse: collapse;
    padding: 8px;
    font-size: 15px;
}

.assoc_resources > td {
	border: 1px solid gray;
    border-collapse: collapse;
    padding: 3px;
}

</style>

<body class="stretched">

<?php include_once("headersecondary_resources.inc") ?>

<?php 

// Association files
$gwas1 = "./Data/Pipeline/Resources/sample_GWAS/CARDIOGRAM_CAD.txt";
$gwas2 = "./Data/Pipeline/Resources/sample_GWAS/DIAGRAM_T2D.txt";
$gwas3 = "./Data/Pipeline/Resources/sample_GWAS/MAGIC.fastingglucose.txt";
$gwas4 = "./Data/Pipeline/Resources/sample_GWAS/glgc.hdl.txt";
$gwas5 = "./Data/Pipeline/Resources/sample_GWAS/glgc.ldl.txt";
$gwas6 = "./Data/Pipeline/Resources/sample_GWAS/glgc.tc.txt";
$gwas7 = "./Data/Pipeline/Resources/sample_GWAS/glgc.tg.txt";
$gwas8 = "./Data/Pipeline/Resources/sample_GWAS/GIANT_BMIall.txt";
$gwas9 = "./Data/Pipeline/Resources/sample_GWAS/AD_IGAP.txt";
$gwas10 = "./Data/Pipeline/Resources/sample_GWAS/EAGLE_ADHD.txt";
$gwas11 = "./Data/Pipeline/Resources/sample_GWAS/PGC_Schizophrenia.txt";
$gwas12 = "./Data/Pipeline/Resources/sample_GWAS/Mouse_Sample_GWAS.txt";
$twas1 = "./Data/Pipeline/Resources/sample_TWAS/Sample_TWAS.txt";
$ewas1 = "./Data/Pipeline/Resources/sample_EWAS/Sample_EWAS.txt";
$pwas1 = "./Data/Pipeline/Resources/sample_PWAS/Sample_PWAS.txt";
$ewas2 = "./Data/Pipeline/Resources/sample_EWAS/EWAS_GSE31835.txt";
$ewas3 = "./Data/Pipeline/Resources/sample_EWAS/EWAS_GSE63315.txt";
$gwas13 = "./Data/Pipeline/Resources/sample_GWAS/GWAS_Psoriasis.txt";

//Mapping files
$map1 = "./Data/Pipeline/Resources/mappings/gene2loci.010kb.txt";
$map2 = "./Data/Pipeline/Resources/mappings/gene2loci.020kb.txt";
$map3 = "./Data/Pipeline/Resources/mappings/gene2loci.050kb.txt";
$map4 = "./Data/Pipeline/Resources/mappings/gene2loci.regulome.txt";
$map5 = "./Data/Pipeline/Resources/mappings/Mouse_Sample_Locus_Mapping.txt";
$map6 = "./Data/Pipeline/Resources/mappings/Sample_EWAS_Mapping.txt";
$map7 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Adipose_Subcutaneous.txt";
$map8 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Adipose_Visceral_Omentum.txt";
$map9 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Adrenal_Gland.txt";
$map10 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Artery_Aorta.txt";
$map11 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Artery_Coronary.txt";
$map12 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Artery_Tibial.txt";
$map13 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Amygdala.txt";
$map14 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Anterior_cingulate_cortex_BA24.txt";
$map15 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Caudate_basal_ganglia.txt";
$map16 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Cerebellar_Hemisphere.txt";
$map17 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Cerebellum.txt";
$map18 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Cortex.txt";
$map19 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Frontal_Cortex_BA9.txt";
$map20 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Hippocampus.txt";
$map21 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Hypothalamus.txt";
$map22 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Nucleus_accumbens_basal_ganglia.txt";
$map23 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Putamen_basal_ganglia.txt";
$map24 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Spinal_cord_cervical_c-1.txt";
$map25 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Brain_Substantia_nigra.txt";
$map26 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Breast_Mammary_Tissue.txt";
$map27 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Cells_Cultured_fibroblasts.txt";
$map28 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Cells_EBV-transformed_lymphocytes.txt";
$map29 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Colon_Sigmoid.txt";
$map30 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Colon_Transverse.txt";
$map31 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Esophagus_Gastroesophageal_Junction.txt";
$map32 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Esophagus_Mucosa.txt";
$map33 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Esophagus_Muscularis.txt";
$map34 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Heart_Atrial_Appendage.txt";
$map35 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Heart_Left_Ventricle.txt";
$map36 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Kidney_Cortex.txt";
$map37 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Liver.txt";
$map38 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Lung.txt";
$map39 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Minor_Salivary_Gland.txt";
$map40 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Muscle_Skeletal.txt";
$map41 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Nerve_Tibial.txt";
$map42 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Ovary.txt";
$map43 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Pancreas.txt";
$map44 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Pituitary.txt";
$map45 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Prostate.txt";
$map46 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Skin_Not_Sun_Exposed_Suprapubic.txt";
$map47 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Skin_Sun_Exposed_Lower_leg.txt";
$map48 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Small_Intestine_Terminal_Ileum.txt";
$map49 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Spleen.txt";
$map50 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Stomach.txt";
$map51 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Testis.txt";
$map52 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Thyroid.txt";
$map53 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Uterus.txt";
$map54 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Vagina.txt";
$map55 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/Whole_Blood.txt";
$map56 = "./Data/Pipeline/Resources/GTEx_v8_eQTL/combined_49esnps.txt";
$map57 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Adipose_Subcutaneous.txt";
$map58 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Adipose_Visceral_Omentum.txt";
$map59 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Adrenal_Gland.txt";
$map60 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Artery_Aorta.txt";
$map61 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Artery_Coronary.txt";
$map62 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Artery_Tibial.txt";
$map63 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Amygdala.txt";
$map64 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Anterior_cingulate_cortex_BA24.txt";
$map65 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Caudate_basal_ganglia.txt";
$map66 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Cerebellar_Hemisphere.txt";
$map67 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Cerebellum.txt";
$map68 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Cortex.txt";
$map69 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Frontal_Cortex_BA9.txt";
$map70 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Hippocampus.txt";
$map71 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Hypothalamus.txt";
$map72 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Nucleus_accumbens_basal_ganglia.txt";
$map73 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Putamen_basal_ganglia.txt";
$map74 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Spinal_cord_cervical_c-1.txt";
$map75 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Brain_Substantia_nigra.txt";
$map76 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Breast_Mammary_Tissue.txt";
$map77 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Cells_Cultured_fibroblasts.txt";
$map78 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Cells_EBV-transformed_lymphocytes.txt";
$map79 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Colon_Sigmoid.txt";
$map80 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Colon_Transverse.txt";
$map81 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Esophagus_Gastroesophageal_Junction.txt";
$map82 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Esophagus_Mucosa.txt";
$map83 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Esophagus_Muscularis.txt";
$map84 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Heart_Atrial_Appendage.txt";
$map85 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Heart_Left_Ventricle.txt";
$map86 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Kidney_Cortex.txt";
$map87 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Liver.txt";
$map88 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Lung.txt";
$map89 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Minor_Salivary_Gland.txt";
$map90 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Muscle_Skeletal.txt";
$map91 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Nerve_Tibial.txt";
$map92 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Ovary.txt";
$map93 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Pancreas.txt";
$map94 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Pituitary.txt";
$map95 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Prostate.txt";
$map96 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Skin_Not_Sun_Exposed_Suprapubic.txt";
$map97 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Skin_Sun_Exposed_Lower_leg.txt";
$map98 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Small_Intestine_Terminal_Ileum.txt";
$map99 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Spleen.txt";
$map100 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Stomach.txt";
$map101 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Testis.txt";
$map102 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Thyroid.txt";
$map103 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Uterus.txt";
$map104 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Vagina.txt";
$map105 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/Whole_Blood.txt";
$map106 = "./Data/Pipeline/Resources/GTEx_v8_sQTL/combined_49ssnps.txt";
$map107 = "./Data/Pipeline/Resources/mappings/Sample_EWAS_Mapping_cpgtogene.txt";

// linkage files
$mdfile1 = "./Data/Pipeline/Resources/LD_files/ld70.ceu.txt";
$mdfile2 = "./Data/Pipeline/Resources/LD_files/ld50.ceu.txt";
$mdfile3 = "./Data/Pipeline/Resources/LD_files/ld30.ceu.txt";
$mdfile4 = "./Data/Pipeline/Resources/LD_files/ld20.ceu.txt";
$mdfile5 = "./Data/Pipeline/Resources/LD_files/ld10.ceu.txt";
$mdfile6 = "./Data/Pipeline/Resources/LD_files/ld70.acb.txt";
$mdfile7 = "./Data/Pipeline/Resources/LD_files/ld50.acb.txt";
$mdfile8 = "./Data/Pipeline/Resources/LD_files/ld70.asw.txt";
$mdfile9 = "./Data/Pipeline/Resources/LD_files/ld50.aswtxt";
$mdfile10 = "./Data/Pipeline/Resources/LD_files/ld70.cdx.txt";
$mdfile11 = "./Data/Pipeline/Resources/LD_files/ld50.cdx.txt";
$mdfile12 = "./Data/Pipeline/Resources/LD_files/ld70.chb.txt";
$mdfile13 = "./Data/Pipeline/Resources/LD_files/ld50.chb.txt";
$mdfile14 = "./Data/Pipeline/Resources/LD_files/ld70.chs.txt";
$mdfile15 = "./Data/Pipeline/Resources/LD_files/ld50.chs.txt";
$mdfile16 = "./Data/Pipeline/Resources/LD_files/ld70.clm.txt";
$mdfile17 = "./Data/Pipeline/Resources/LD_files/ld50.clm.txt";
$mdfile18 = "./Data/Pipeline/Resources/LD_files/ld70.esn.txt";
$mdfile19 = "./Data/Pipeline/Resources/LD_files/ld50.esn.txt";
$mdfile20 = "./Data/Pipeline/Resources/LD_files/ld70.fin.txt";
$mdfile21 = "./Data/Pipeline/Resources/LD_files/ld50.fin.txt";
$mdfile22 = "./Data/Pipeline/Resources/LD_files/ld70.gbr.txt";
$mdfile23 = "./Data/Pipeline/Resources/LD_files/ld50.gbr.txt";
$mdfile24 = "./Data/Pipeline/Resources/LD_files/ld70.gih.txt";
$mdfile25 = "./Data/Pipeline/Resources/LD_files/ld50.gih.txt";
$mdfile26 = "./Data/Pipeline/Resources/LD_files/ld70.gwd.txt";
$mdfile27 = "./Data/Pipeline/Resources/LD_files/ld50.gwd.txt";
$mdfile28 = "./Data/Pipeline/Resources/LD_files/ld70.ibs.txt";
$mdfile29 = "./Data/Pipeline/Resources/LD_files/ld50.ibs.txt";
$mdfile30 = "./Data/Pipeline/Resources/LD_files/ld70.itu.txt";
$mdfile31 = "./Data/Pipeline/Resources/LD_files/ld50.itu.txt";
$mdfile32 = "./Data/Pipeline/Resources/LD_files/ld70.jpt.txt";
$mdfile33 = "./Data/Pipeline/Resources/LD_files/ld50.jpt.txt";
$mdfile34 = "./Data/Pipeline/Resources/LD_files/ld70.khv.txt";
$mdfile35 = "./Data/Pipeline/Resources/LD_files/ld50.khv.txt";
$mdfile36 = "./Data/Pipeline/Resources/LD_files/ld70.lwk.txt";
$mdfile37 = "./Data/Pipeline/Resources/LD_files/ld50.lwk.txt";
$mdfile38 = "./Data/Pipeline/Resources/LD_files/ld70.msl.txt";
$mdfile39 = "./Data/Pipeline/Resources/LD_files/ld50.msl.txt";
$mdfile40 = "./Data/Pipeline/Resources/LD_files/ld70.mxl.txt";
$mdfile41 = "./Data/Pipeline/Resources/LD_files/ld50.mxl.txt";
$mdfile42 = "./Data/Pipeline/Resources/LD_files/ld70.pel.txt";
$mdfile43 = "./Data/Pipeline/Resources/LD_files/ld50.pel.txt";
$mdfile44 = "./Data/Pipeline/Resources/LD_files/ld70.pjl.txt";
$mdfile45 = "./Data/Pipeline/Resources/LD_files/ld50.pjl.txt";
$mdfile46 = "./Data/Pipeline/Resources/LD_files/ld70.pur.txt";
$mdfile47 = "./Data/Pipeline/Resources/LD_files/ld50.pur.txt";
$mdfile48 = "./Data/Pipeline/Resources/LD_files/ld70.stu.txt";
$mdfile49 = "./Data/Pipeline/Resources/LD_files/ld50.stu.txt";
$mdfile50 = "./Data/Pipeline/Resources/LD_files/ld70.tsi.txt";
$mdfile51 = "./Data/Pipeline/Resources/LD_files/ld50.tsi.txt";
$mdfile52 = "./Data/Pipeline/Resources/LD_files/ld70.yri.txt";
$mdfile53 = "./Data/Pipeline/Resources/LD_files/ld50.yri.txt";
$mdfile54 = "./Data/Pipeline/Resources/LD_files/md_example_50.txt";

// genesets
$geneset1 = "./Data/Pipeline/Resources/pathways/KEGG_Reactome_BioCarta.txt";
$genesetinfo1 = "./Data/Pipeline/Resources/pathways/KEGG_Reactome_BioCarta_info.txt";
$geneset2 = "./Data/Pipeline/Resources/pathways/MSigDB_canonical_pathways.txt";
$geneset3 = "./Data/Pipeline/Resources/pathways/MSigDB_regulatory_target.txt";
$geneset4 = "./Data/Pipeline/Resources/pathways/MSigDB_cell_type_signatures.txt";
$geneset5 = "./Data/Pipeline/Resources/pathways/MSigDB_chemical_genetic_perturbations.txt";
$geneset6 = "./Data/Pipeline/Resources/pathways/GO_biological_process.txt";
$geneset7 = "./Data/Pipeline/Resources/pathways/Adipose_Subcutaneous_Coexp.txt";
$geneset8 = "./Data/Pipeline/Resources/pathways/Adipose_Visceral_Omentum_Coexp.txt";
$geneset9 = "./Data/Pipeline/Resources/pathways/Adrenal_Gland_Coexp.txt";
$geneset10 = "./Data/Pipeline/Resources/pathways/Artery_Aorta_Coexp.txt";
$geneset11 = "./Data/Pipeline/Resources/pathways/Artery_Tibial_Coexp.txt";
$geneset12 = "./Data/Pipeline/Resources/pathways/Brain_Cerebellar_Hemisphere_Coexp.txt";
$geneset13 = "./Data/Pipeline/Resources/pathways/Brain_Cerebellum_Coexp.txt";
$geneset14 = "./Data/Pipeline/Resources/pathways/Brain_Cortex_Coexp.txt";
$geneset15 = "./Data/Pipeline/Resources/pathways/Brain_Frontal_Cortex_BA9_Coexp.txt";
$geneset16 = "./Data/Pipeline/Resources/pathways/Brain_Hippocampus_Coexp.txt";
$geneset17 = "./Data/Pipeline/Resources/pathways/Brain_Hypothalamus_Coexp.txt";
$geneset18 = "./Data/Pipeline/Resources/pathways/Colon_Sigmoid_Coexp.txt";
$geneset19 = "./Data/Pipeline/Resources/pathways/Esophagus_Mucosa_Coexp.txt";
$geneset20 = "./Data/Pipeline/Resources/pathways/Esophagus_Muscularis_Coexp.txt";
$geneset21 = "./Data/Pipeline/Resources/pathways/Heart_Left_Ventricle_Coexp.txt";
$geneset22 = "./Data/Pipeline/Resources/pathways/Liver_Coexp.txt";
$geneset23 = "./Data/Pipeline/Resources/pathways/Muscle_Skeletal_Coexp.txt";
$geneset24 = "./Data/Pipeline/Resources/pathways/Nerve_Tibial_Coexp.txt";
$geneset25 = "./Data/Pipeline/Resources/pathways/Pancreas_Coexp.txt";
$geneset26 = "./Data/Pipeline/Resources/pathways/Pituitary_Coexp.txt";
$geneset27 = "./Data/Pipeline/Resources/pathways/Spleen_Coexp.txt";
$geneset28 = "./Data/Pipeline/Resources/pathways/Stomach_Coexp.txt";
$geneset29 = "./Data/Pipeline/Resources/pathways/Thyroid_Coexp.txt";
$geneset30 = "./Data/Pipeline/Resources/pathways/Whole_Blood_Coexp.txt";

//networks
$network1 = "./Data/Pipeline/Resources/networks/networks.hs.adipose.txt";
$network2 = "./Data/Pipeline/Resources/networks/networks.hs.blood.txt";
$network3 = "./Data/Pipeline/Resources/networks/networks.hs.brain.txt";
$network4 = "./Data/Pipeline/Resources/networks/networks.hs.kidney.txt";
$network5 = "./Data/Pipeline/Resources/networks/networks.hs.liver.txt";
$network6 = "./Data/Pipeline/Resources/networks/networks.hs.muscle.txt";
$network7 = "./Data/Pipeline/Resources/networks/networks.hs.inwebPPI.txt";


?>

<!-- Page title block ---------------------------------------------------------------------------------->
 <section id="page-title">
     <div class="margin_rm" style="margin-left: 0;">
		<div class="container clearfix" style="text-align: center;">
			<h2>Sample Files</h2>
		</div>
    </div>
</section>

<section id="content" style="margin-bottom: 0px;">
	<div class="content-wrap">
		<div class="container clearfix" style="margin-left: 8%;">
			<p class="instructiontext" style="padding-left: 0; margin-bottom: 0;text-align: left;">Click on sample data below to download.</p>
			<h2>Association Files</h2>
			<ul class="samplef">
				<li><a href=<?php print($gwas4); ?> download>Sample Human GWAS </a></li>
				<li><a href=<?php print($gwas12); ?> download>Sample Mouse GWAS</a></li>
				<li><a href=<?php print($ewas1); ?> download>Sample Human EWAS</a></li>
				<li><a href=<?php print($twas1); ?> download>Sample Human TWAS</a></li>
				<li><a href=<?php print($pwas1); ?> download>Sample Human PWAS</a></li>
				<li><a href=<?php print($ewas2); ?> download>Psoriasis EWAS GSE31835</a></li>
				<li><a href=<?php print($ewas3); ?> download>Psoriasis EWAS GSE63315</a></li>
				<li><a href=<?php print($gwas13); ?> download>Psoriasis GWAS</a></li>
			</ul>
			<h2>Association Files Sources</h2>
			<table class="assoc_resources" style="text-align: left;width: 90%;">
				<thead>
			       <tr class="assoc_resources">
			         <th>Data type</th>
			         <th>Trait</th>
			         <th>Paper</th>
			         <th>Summary statistics link</th>
			       </tr>
			     </thead>
			     <tbody>
					<tr class="assoc_resources">
			         <td rowspan='22' style="vertical-align: middle;padding: 10px;">
			           GWAS
			         </td>
			         <td>
			           Alzheimer's disease
			         </td>
			         <td>
			         	Marioni, R. E., et al. (2018). "GWAS on family history of Alzheimer’s disease." Translational Psychiatry 8(1): 99.
			         </td>
			         <td>
			         	https://www.ccace.ed.ac.uk/node/335
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Attention Deficit Hyperactivity Disorder
			         </td>
			         <td>
			         	Middeldorp, C. M., et al. (2016). "A Genome-Wide Association Meta-Analysis of Attention-Deficit/Hyperactivity Disorder Symptoms in Population-Based Pediatric Cohorts." Journal of the American Academy of Child & Adolescent Psychiatry 55(10): 896-905.e896.
			         </td>
			         <td>
			         	https://tweelingenregister.vu.nl/eagle-gwa-meta-analyses-summary-results
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Alcohol Dependence
			         </td>
			         <td>
			         	Olfson, E. and L. J. Bierut (2012). "Convergence of Genome-Wide Association and Candidate Gene Studies for Alcoholism." Alcoholism: Clinical and Experimental Research 36(12): 2086-2094.
			         </td>
			         <td>
			         	https://www.ncbi.nlm.nih.gov/projects/SNP/gViewer/gView.cgi?aid=2907
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Body Mass Index
			         </td>
			         <td>
			         	Locke, A. E., et al. (2015). "Genetic studies of body mass index yield new insights for obesity biology." Nature 518(7538): 197-206.
			         </td>
			         <td>
			         	http://portals.broadinstitute.org/collaboration/giant/index.php/GIANT_consortium_data_files
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Breast Cancer
			         </td>
			         <td>
			         	Rashkin, S. R., et al. (2020). "Pan-cancer study detects genetic risk variants and shared genetic basis in two large cohorts." Nature Communications 11(1): 4423.
			         </td>
			         <td>
			         	https://www.ebi.ac.uk/gwas/studies/GCST90011804
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Coronary Artery Disease
			         </td>
			         <td>
			         	Nikpay, M., et al. (2015). "A comprehensive 1000 Genomes–based genome-wide association meta-analysis of coronary artery disease." Nature Genetics 47(10): 1121-1130.
			         </td>
			         <td>
			         	http://www.cardiogramplusc4d.org/data-downloads/
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Fasting Glucose
			         </td>
			         <td>
			         	Manning, A. K., et al. (2012). "A genome-wide approach accounting for body mass index identifies genetic variants influencing fasting glycemic traits and insulin resistance." Nature Genetics 44(6): 659-669.
			         </td>
			         <td>
			         	http://magicinvestigators.org/downloads/
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Heart Failure
			         </td>
			         <td>
			         	Shah, S., et al. (2020). "Genome-wide association and Mendelian randomisation analysis provide insights into the pathogenesis of heart failure." Nature Communications 11(1): 163.
			         </td>
			         <td>
			         	https://www.ebi.ac.uk/gwas/studies/GCST009541
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           High Density Lipoproteins (HDL)
			         </td>
			         <td>
			         	Willer, C. J., et al. (2013). "Discovery and refinement of loci associated with lipid levels." Nature Genetics 45(11): 1274-1283.
			         </td>
			         <td>
			         	http://csg.sph.umich.edu//abecasis/public/lipids2013/
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Low Density Lipoproteins (LDL)
			         </td>
			         <td>
			         	Willer, C. J., et al. (2013). "Discovery and refinement of loci associated with lipid levels." Nature Genetics 45(11): 1274-1283.
			         </td>
			         <td>
			         	http://csg.sph.umich.edu//abecasis/public/lipids2013/
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Major Depressive Disorder
			         </td>
			         <td>
			         	Coleman, J. R. I., et al. (2020). "Genome-wide gene-environment analyses of major depressive disorder and reported lifetime traumatic experiences in UK Biobank." Molecular Psychiatry 25(7): 1430-1446.
			         </td>
			         <td>
			         	https://www.ebi.ac.uk/gwas/studies/GCST009979
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Parental Lifespan
			         </td>
			         <td>
			         	Timmers, P. R., et al. (2019). "Genomics of 1 million parent lifespans implicates novel pathways and common diseases and distinguishes survival chances." Elife 8.
			         </td>
			         <td>
			         	https://www.ebi.ac.uk/gwas/studies/GCST009890
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Parkinson’s Disease
			         </td>
			         <td>
			         	Blauwendraat, C., et al. (2019). "Parkinson's disease age at onset genome-wide association study: Defining heritability, genetic loci, and α-synuclein mechanisms." Movement Disorders 34(6): 866-875.
			         </td>
			         <td>
			         	https://www.ebi.ac.uk/gwas/studies/GCST007780
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Psoriasis
			         </td>
			         <td>
			         	Nair, R. P., et al. (2009). "Genome-wide scan reveals association of psoriasis with IL-23 and NF-κB pathways." Nature Genetics 41(2): 199-204.
			         </td>
			         <td>
			         	https://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/study.cgi?study_id=phs000019.v1.p1
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Severe illness in COVID-19
			         </td>
			         <td>
			         	Pairo-Castineira, E., et al. (2021). "Genetic mechanisms of critical illness in COVID-19." Nature 591(7848): 92-98.
			         </td>
			         <td>
			         	https://www.ebi.ac.uk/gwas/studies/GCST90013414
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Schizophrenia
			         </td>
			         <td>
			         	Ripke, S., et al. (2014). "Biological insights from 108 schizophrenia-associated genetic loci." Nature 511(7510): 421-427.
			         </td>
			         <td>
			         	https://www.med.unc.edu/pgc/download-results/
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Stroke
			         </td>
			         <td>
			         	Malik, R., et al. (2018). "Multiancestry genome-wide association study of 520,000 subjects identifies 32 loci associated with stroke and stroke subtypes." Nature Genetics 50(4): 524-537.
			         </td>
			         <td>
			         	https://www.ebi.ac.uk/gwas/studies/GCST005838
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Psoriasis
			         </td>
			         <td>
			         	Nair, R. P., et al. (2009). "Genome-wide scan reveals association of psoriasis with IL-23 and NF-κB pathways." Nature Genetics 41(2): 199-204.
			         </td>
			         <td>
			         	https://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/study.cgi?study_id=phs000019.v1.p1
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Systemic Lupus Erythematosus
			         </td>
			         <td>
			         	Wang, Y.-F., et al. (2021). "Identification of 38 novel loci for systemic lupus erythematosus and genetic heterogeneity between ancestral groups." Nature Communications 12(1): 772.
			         </td>
			         <td>
			         	https://www.ebi.ac.uk/gwas/studies/GCST90011866
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Type 2 Diabetes
			         </td>
			         <td>
			         	Fuchsberger, C., et al. (2016). "The genetic architecture of type 2 diabetes." Nature 536(7614): 41-47.
			         </td>
			         <td>
			         	http://diagram-consortium.org/downloads.html
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Total Cholesterol
			         </td>
			         <td>
			         	Willer, C. J., et al. (2013). "Discovery and refinement of loci associated with lipid levels." Nature Genetics 45(11): 1274-1283.
			         </td>
			         <td>
			         	http://csg.sph.umich.edu//abecasis/public/lipids2013/
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Triglycerides
			         </td>
			         <td>
			         	Willer, C. J., et al. (2013). "Discovery and refinement of loci associated with lipid levels." Nature Genetics 45(11): 1274-1283.
			         </td>
			         <td>
			         	http://csg.sph.umich.edu//abecasis/public/lipids2013/
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			       	<td rowspan='4' style="vertical-align: middle;padding: 10px;">
			           EWAS
			         </td>
			         <td>
			           Birth Weight
			         </td>
			         <td>
			         	Küpers, L. K., et al. (2019). "Meta-analysis of epigenome-wide association studies in neonates reveals widespread differential DNA methylation associated with birthweight." Nature Communications 10(1): 1893.
			         </td>
			         <td>
			         	https://zenodo.org/record/2222287#.YHvBTy-z29s
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Maternal Anxiety
			         </td>
			         <td>
			         	Sammallahti, S., et al. (2021). "Maternal anxiety during pregnancy and newborn epigenome-wide DNA methylation." Molecular Psychiatry.
			         </td>
			         <td>
			         	https://zenodo.org/record/4147845#.YHvB4S-z29s
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Social communication
			         </td>
			         <td>
			         	Rijlaarsdam, J., et al. (2021). "Epigenetic profiling of social communication trajectories and co-occurring mental health problems: a prospective, methylome-wide association study." Development and Psychopathology: 1-10.
			         </td>
			         <td>
			         	https://zenodo.org/record/4031357#.YHvCwy-z29t
			         </td>
			       </tr>
			       <tr class="assoc_resources">
			         <td>
			           Psoriasis 
			         </td>
			         <td>
			         	Roberson, E. D. O., et al. (2012). "A Subset of Methylated CpG Sites Differentiate Psoriatic from Normal Skin." Journal of Investigative Dermatology 132(3): 583-592., Gu, X., et al. (2015). "Correlation between Reversal of DNA Methylation and Clinical Symptoms in Psoriatic Epidermis Following Narrow-Band UVB Phototherapy." Journal of Investigative Dermatology 135(8): 2077-2083.
			         </td>
			         <td>
			         	https://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=GSE31835, https://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=GSE63315
			         </td>
			       </tr>
			     </tbody>
			</table>

			<h2>Mapping Files</h2>
			<ul class="samplef" style="margin-bottom: 10px;">
				<li><a href=<?php print($map12); ?> download>Sample SNP to gene</a></li>
				<li><a href=<?php print($map107); ?> download>Sample CpG site to gene</a></li>
				<!--
				<li><a href=<?php print($map105); ?> download>GTEx Whole Blood eQTL</a></li>
				<li><a href=<?php print($map47); ?> download>GTEx Skin eQTL</a></li> -->
			</ul>

	      	<h2>Marker Dependency Files</h2>
	      	<ul class="samplef" style="margin-bottom: 10px;">
	      		<li><a href=<?php print($mdfile1); ?> download>Sample linkage disequilibrium</a></li>
				<li><a href=<?php print($mdfile54); ?> download>Sample methylation disequilibrium</a></li>
			</ul>

	      	<h2>Marker sets</h2>
          <ul class="samplef" style="margin-bottom: 10px;">
            <li><a href=<?php print($geneset1); ?> download>Sample marker set/gene set</a></li>
            <li><a href=<?php print($genesetinfo1); ?> download>Sample marker set/gene set descriptions</a></li>
          </ul>
          <h4>Information on coexpression modules</h4>
          <div style="font-size:16px; width: 70%;margin-bottom: 2%;">
          Includes WGCNA (1) and MEGENA (2) coexpression networks made from GTEx (3) human gene expression data. Both WGCNA and MEGENA network methods are based on hierarchical clustering to assign co-regulated genes into the same coexpression module. Agglomerative hierarchical clustering is used in WGCNA, whereas divisive clustering is used in MEGENA. Gene-clusters are identified by merging (in agglomerative) or splitting (in divisive) based on a distance measure (e.g. 1-|correlation|). In WGCNA, 1 minus topological overlap matrix (TOM), hence dissTOM=1-TOM, was used as the distance measure. TOM is based on the correlation score (edge weight) between two genes (nodes) but also considers the edge weights of common neighbors of these two nodes in the network. To calculate the distance between two clusters, average dissTOM score of all gene pairs (each pair includes one gene from each cluster, while comparing 2 clusters) is used. In MEGENA, a shortest path distance (SPD) based distance measure is used. To create compact modules, a nested k-medoids clustering, which defines k-best clusters at each step that minimizes the SPD within each cluster, is used. Nested k-medoids clustering is ran until no more compact child cluster can be defined. MEGENA performs multi-scale clustering, which assigns a gene into numerous modules from different scales. Finally, we annotated each module with its functions by using curated biological pathways from the Reactome database (4) based on a hypergeometric test (one-tailed Fisher Exact test). <br> For WGCNA, we utilized a r2 >0.7 for soft threshold selection but in cases where this threshold could not be reached we used a default soft threshold = 6 and we used a k = 100. For MEGENA, we used the "Spearman" method for correlation, we set our min module size = 10 and max module size = 2500. Recommended or default parameters were used for other criteria.
          </div>
          <!--
	      	<h3>Curated gene sets</h3>
	      	<div style="font-size:18px">
				Gene sets curated from Kyoto Encyclopedia of Genes and Genomes (KEGG), Reactome, BioCarta, Molecular Signatures Database (MSigDB), and the Gene Ontology Consortium [12-16].
			</div>
	      	<ul class="samplef" style="margin-bottom: 10px;">
				<li><a href=<?php print($geneset2); ?> download>MSigDB Canonical Pathways</a></li>
				<li><a href=<?php print($geneset3); ?> download>MSigDB Regulatory Target (TFs and miRNA)</a></li>
				<li><a href=<?php print($geneset4); ?> download>MSigDB Cell Type Signatures</a></li>
				<li><a href=<?php print($geneset5); ?> download>MSigDB chemical genetic perturbations</a></li>
				<li><a href=<?php print($geneset6); ?> download>GO Biological Process</a></li>
	      	</ul>
        -->

	      	<h2>Biological Networks</h2>
	      	<ul class="samplef" style="margin-bottom: 10px;">
				    <!-- <li><a href=<?php //print($network7); ?> download>Sample molecular network</a></li> -->

			   </ul>
         <h4>Information on bayesian networks</h4>
         <div style="font-size:16px;width: 70%">
         Bayesian composite human and mouse networks are made using RIMBANet (5,6) with tissue-specific expression data and the priors transcription factor-target pairs and eQTLs
        </div>
      <ul class="samplef" style="margin-bottom: 10px;">
        <li><a href=<?php print($network1); ?> download>Human Adipose Network</a></li>
        <li><a href=<?php print($network2); ?> download>Human Blood Network</a></li>
        <li><a href=<?php print($network3); ?> download>Human Brain Network</a></li>
        <li><a href=<?php print($network4); ?> download>Human Kidney Network</a></li>
        <li><a href=<?php print($network5); ?> download>Human Liver Network</a></li>
        <li><a href=<?php print($network6); ?> download>Human Muscle Network</a></li>
      </ul>
        <h4>Information on FANTOM5 transcription factor networks</h4>
         <div style="font-size:16px;width: 70%">
         FANTOM5 networks (7) are optimized by choosing a weight cutoff that yields a scale-free network (reaching -0.95 correlation between the log of degrees and log of the number of nodes)
        </div>
        <h4>Information on STRING PPI network</h4>
         <div style="font-size:16px;width: 70%">
         To reduce density of the STRING PPI (8) network, the top 5% of edges by "combined_score" are kept
        </div>
       <ul class="samplef" style="margin-bottom: 2%;">
        <li><a href=<?php print($network7); ?> download>Human PPI Network</a></li>
      </ul>

        <h2>References</h2>
        <ul class="ref" style="width: 70%;">
          <li>1. Langfelder, P. and S. Horvath (2008). "WGCNA: an R package for weighted correlation network analysis." BMC Bioinformatics 9(1): 559.</li>
           <li>2. Song, W. M. and Zhang, B. (2015) Multiscale Embedded Gene Co-expression Network Analysis. PLoS Comput Biol 11(11): e1004574.
            </li>
          <li>3. (2020). "The GTEx Consortium atlas of genetic regulatory effects across human tissues." Science 369(6509): 1318.
          </li>
          <li>4. Fabregat, A., et al. (2017). "Reactome pathway analysis: a high-performance in-memory approach." BMC Bioinformatics 18(1): 142.</li>
          <li>5. Zhu, J., et al. (2007). "Increasing the Power to Detect Causal Associations by Combining Genotypic and Expression Data in Segregating Populations." PLOS Computational Biology 3(4): e69.
          </li>
          <li>6. Zhu, J., et al. (2008). "Integrating large-scale functional genomic data to dissect the complexity of yeast regulatory networks." Nature Genetics 40(7): 854-861.</li>
          <li>
          7. Marbach, D., Lamparter, D., Quon, G., Kellis, M., Kutalik, Z. and Bergmann, S. (2016) Tissue-specific regulatory circuits reveal variable modular perturbations across complex diseases. Nat Methods, 13, 366-370.</li>
          <li>8. Szklarczyk, D., Gable, A.L., Nastou, K.C., Lyon, D., Kirsch, R., Pyysalo, S., Doncheva, N.T., Legeay, M., Fang, T., Bork, P. et al. (2021) The STRING database in 2021: customizable protein-protein networks, and functional characterization of user-uploaded gene/measurement sets. Nucleic Acids Res, 49, D605-d612.</li>
        </ul>
		</div>
	</div>


</section>




</body>
</html>


  <!-- External JavaScripts IMPORTANT!
  ============================================= -->
  <script src="include/js/jquery.js"></script>
  <script src="include/js/plugins.js"></script>

  <!-- Footer Scripts IMPORTANT!
  ============================================= -->
  <script src="include/js/functions.js"></script>