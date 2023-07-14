<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<?php include_once("analyticstracking.php") ?>

<!-- Includes all the font/styling/js sheets -->
<?php include_once("head.inc") ?>

<style>

.btn:focus {
outline: none !important;
box-shadow: none !important;
}

#sidebar.active .custom-menu {
margin-right: -30px !important;
}


p {
	margin-top: 20px;
	font-size: 15px;
}

table {
  font-size: 18px;
  margin-bottom: 20px;
  width: 100%;
  text-align:  left;
}

a span {
    opacity: 0;
    margin-left: 2%;
}

a:hover span {
    opacity: 1;
}

span {
    font-size: 20px;
}

.tut_pic {
    margin-left: 3%;
    border: 1px solid gray;
    border-radius: 10px;
}


td {
  vertical-align: top;
}


a.case {
  color: #000;
}

a.case:hover{
  color:#e83c3c;
}

table {
  margin-bottom: 4px;
}

th {
  font-size: 24px;
}

i.icon-search-plus:hover {
    display: block;
}

td:nth-child(2) {
	border-left: 15px solid transparent;
	-webkit-background-clip: padding;
	-moz-background-clip: padding;
	background-clip: padding-box;
}

th:nth-child(2) {
	border-left: 15px solid transparent;
	-webkit-background-clip: padding;
	-moz-background-clip: padding;
	background-clip: padding-box;
}

.instruction-table > td:nth-child(1) {
  width: 3em;
  min-width: 3em;
  max-width: 3em;
}

.instruction-table > td:nth-child(2) {
  width: 32em;
  min-width: 32em;
  max-width: 32em;
}

.instruction-table > td:nth-child(3) {
  width: 50em;
  min-width: 50em;
  max-width: 50em;
}

.instruction-table-toggle > td:nth-child(1) {
  width: 3em;
  min-width: 3em;
  max-width: 3em;
}

.instruction-table-toggle > td:nth-child(2) {
  width: 32em;
  min-width: 32em;
  max-width: 32em;
}

.instruction-table > td:nth-child(3) {
	border-left: 15px solid transparent;
	-webkit-background-clip: padding;
	-moz-background-clip: padding;
	background-clip: padding-box;
}

table.instructions{
  border-collapse: separate;
  border-spacing: 0 40px;
}

table.interpretation {
	border: 1px solid black;
    border-collapse: collapse;
    /*margin: 0px 350px;*/
      margin-left: auto;
  	 margin-right: auto;
}

.interpretation > th {
	font-size: 18px;
	border: 1px solid black;
    border-collapse: collapse;
    padding: 1% 1% 1% 2%;
}

.interpretation > th:nth-child(1) {
	width: 14em;
	padding: 1% 1% 1% 2%;
}

.interpretation > td {
	font-size: 16px;
	border: 1px solid black;
    border-collapse: collapse;
    padding: 1% 1% 1% 2%;
}

table.samplefile {
	border: 1px solid gray;
    border-collapse: collapse;
    padding: 3px;
}

.samplefile > th {
	border: 1px solid gray;
    border-collapse: collapse;
    padding: 3px;
}

.samplefile > td {
	border: 1px solid gray;
    border-collapse: collapse;
    padding: 3px;
}


.circle {
  display: flex;
  border: 3px solid #e83c3c;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  justify-content: center;
  align-items: center;
  box-shadow: 0 1px 10px rgba(255, 0, 0, 0.46);
  text-align: center;
  /*margin: 30px 0;*/
}

td img{

  vertical-align: middle;

}

.samplefile {
  font-size: 16px;
}

.samplefileprov {
  font-size: 16px;
}

.floatbox {
	display: inline-block;
	width: 175px;
	margin: 0;
	padding-top: 5px;
	float: left;
	height:45px;
}

.floatbox > p {
  /*margin-top: 5%;*/
  margin-bottom: 0;
  color: #00004d;
  font-size: 18px;
}

ul {
	list-style-type: none;
}

#sidebar ul li a {
	padding: 15px 35px !important;
	font-size: 16px !important;
	display: block;
	color: rgba(255, 255, 255, 0.6);
	border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

ul.nav1 ul {
	display:none;
}


</style>
<!-- START body of pipeline ----------------------------------------------------------------------------->

<body class="stretched">

  <!-- Include the Run Mergeomics header ------------------------------------------------------------------>
  <?php include_once("headersecondary_tut.inc") ?>



  <!-- Page title block ---------------------------------------------------------------------------------->
<section id="page-title">

<div class="margin_rm">
  <div class="container clearfix" style="text-align: center;">
	<h2>Mergeomics Tutorial</h2>

  </div>
</div>

</section>

<nav id="sidebar" style="margin-top: -88px;max-width: 260px !important; /*overflow-y: auto;*/">
	        <div class="custom-menu">
          <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i id="sidebar_icon" class="icon-bars"></i>
            <span class="sr-only">Toggle Menu</span>
          </button>
        </div>

<h1><a href="#" class="session" style="padding: 28px 20px;">Quick navigation</a></h1>

<h2 style="padding: 0px 10px 10px 24px; font-size: 18px;color: #fff; font-weight: normal;">Click on data type to expand steps:</h2>

<ul class="list-unstyled components nav1">
  <li class="active">
  	<a href="#datatypes" class="navGWAS sub1 navig">GWAS</a>
	<ul style="padding-left:15px">
		<li><a class="navGWAS navig" href="#MDF" style="padding: 5px 10px 5px 30px;">Marker dependency filtering</a></li>
		<li><a class="navGWAS navig" href="#SSEA" style="padding: 5px 10px 5px 30px;">Marker set enrichment analysis</a></li>
		<li><a class="navGWAS navig" href="#SSEAtoKDA" style="padding: 5px 10px 5px 30px;">Key driver analysis</a></li>
		<li><a class="navGWAS navig" href="#SSEAtoKDAtoPharm" style="padding: 5px 10px 5px 30px;">KDA to PharmOmics</a></li>
		<li><a class="navGWAS navig" href="#SSEAtoPharm" style="padding: 5px 10px 5px 30px;">MSEA to PharmOmics</a></li>
  	</ul>
  </li>
  <li class="active">
	<a href="#datatypes" class="navETPMWAS sub1 navig">EWAS/TWAS/PWAS/MWAS</a>
	<ul style="padding-left:15px">
		<li><a class="navETPMWAS navig" href="#MSEA" style="padding: 5px 10px 5px 30px;">Marker set enrichment analysis</a></li>
		<li><a class="navETPMWAS navig" href="#MSEAtoKDA" style="padding: 5px 10px 5px 30px;">Key driver analysis</a></li>
		<li><a class="navETPMWAS navig" href="#MSEAtoKDAtoPharm" style="padding: 5px 10px 5px 30px;">KDA to PharmOmics</a></li>
		<li><a class="navETPMWAS navig" href="#MSEAtoPharm" style="padding: 5px 10px 5px 30px;">MSEA to PharmOmics</a></li>
  	</ul>
  </li>
  <li class="active">
	<a href="#datatypes" class="navMeta sub1 navig">Multiple of different or the same type of omics data</a> 
	<ul style="padding-left:15px">
		<li><a class="navMeta navig" href="#Meta" style="padding: 5px 10px 5px 30px;">Marker set enrichment analysis</a></li>
		<li><a class="navMeta navig" href="#MetatoKDA" style="padding: 5px 10px 5px 30px;">Key driver analysis</a></li>
		<li><a class="navMeta navig" href="#MetatoKDAtoPharm" style="padding: 5px 10px 5px 30px;">KDA to PharmOmics</a></li>
		<li><a class="navMeta navig" href="#MetatoPharm" style="padding: 5px 10px 5px 30px;">MSEA to PharmOmics</a></li>
  	</ul>
  </li>
  <li class="active">
	<a href="#datatypes" class="navGeneList sub1 navig">List of Genes</a>
	<ul style="padding-left:15px">
		<li><a class="navGeneList genelist" id="runKDA" href="#KDAtoggle" style="padding: 5px 10px 5px 30px;">Key driver analysis</a></li>
		<li><a class="navGeneList genelist" id="testEnrichment" href="#Enrichtoggle" style="padding: 5px 10px 5px 30px;">Gene set enrichment analysis</a></li>
		<li><a class="navGeneList genelist" id="runPharmOmics" href="#Pharmtoggle" style="padding: 5px 10px 5px 30px;">PharmOmics</a></li>
  	</ul>
  </li>
  <!--
   <li class="active">
	<a href="tutorial_PharmOmics.php">PharmOmics</a>
  </li>
  -->
</ul>
  <p style="padding-top:0px;">
  	Mergeomics is being actively developed by the Yang Lab in the Department of Integrative Biology and Physiology at UCLA. 
  </p>

</nav>

<div class="margin_rm" style="margin-bottom: 200px;">
	<div class="container clearfix" id="myContainer" style="margin-bottom: 40px;">
		<p style="font-size: 20px;margin-bottom: 0;margin-top: 5%;">
			Mergeomics is a flexible and streamlined pipeline that is able to retrieve meaningful biological insight from multiple types of omics data using multiple levels of analysis. Users can easily build their workflow based on their specific data and desired analysis. To facilitate one's analysis, we provide many different types of sample files including SNP to gene mapping, linkage disequilibrium files, and biological networks. <br><br> The two core functions of Mergeomics are marker set enrichment analysis (MSEA) and key driver analysis (KDA). Depending on the 7data type, there are slightly different considerations for MSEA, and so we have segmented the tutorial based on the specific data from the user. From MSEA to KDA, biological markers from significant marker sets found in MSEA and a network is input into KDA. The user can also run KDA as a first step using a list of markers (i.e genes) (tutorial in 'List(s) of genes' button below).<br><br>Sample input files can be found <a href="samplefiles.php">here</a>. Sample outputs can be found <a href="sample_output.php">here</a>.<br><br>In addition to this tutorial, we have also embedded instructions throughout the pipeline workflow itself. Look for this button:</p>
		<button class="button button-3d button-rounded button" disabled><i class="icon-question1"></i>Click for tutorial</button>
		<p style="font-size: 20px;margin-bottom: 0;">**Please save your session ID which can be copied by clicking on it at the top of the left sidebar so that you may return to your session at a later time or we recommend to enter your email when prompted to receive session information and results! (valid for 48 hours)</p>
		<p class="instructiontext" id="datatypes" style="font-size: 20px;margin-bottom: 0px;">For an in-depth tutorial on how one can use Mergeomics, click below on the data type you have.</p>
	   	<div class="row clearfix">
			<div class="col-lg-3 center">
				<div class="button-wrapper">
					<div id="GWASoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
					  <a id="GWASinfo" class="runm button-inner" href="#myGWASinfo">GWAS</a>
					</div>
				</div>
			</div>
			<div class="col-lg-3 center">
				<div class="button-wrapper">
				  <div id="GWASoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
					  <a id="TPEMWASinfo" class="runm button-inner" href="#myTPEMWASinfo">TWAS/PWAS/EWAS/MWAS</a>
				  </div>
				</div>
			</div>
			<div class="col-lg-3 center">
				<div class="button-wrapper">
				  <div id="GWASoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
					  <a id="METAinfo" class="runm button-inner" href="#myMETAinfo">Multiple of different or the same omics data</a>
				  </div>
				</div>
			</div>
			<div class="col-lg-3 center">
				<div class="button-wrapper">
				  <div id="GWASoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
					  <a id="GeneListInfo" class="runm button-inner" href="#myGeneListInfo">List(s) of genes</a>
				  </div>
				</div>
			</div>
	  	</div>
		<div>
			<div id="myGWASinfo" style="display:none;padding: 10px 40px;">
				<p class="instructiontext" id="MDF" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Preprocess data: marker dependency filtering (MDF).</p>
			  	<table class="instructions" style="margin: 0% 0% 0%;">
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>1</div>
						</div>
					  </td>
					  <td>
						<b>Start pipeline.</b> If you have a single GWAS study, click on 'Individual GWAS Enrichment'. A common preprocessing step of GWAS analysis is to correct for SNP dependencies based on linkage disequilibrium. We include marker dependency filtering (<b>MDF</b>) in our GWAS pipeline by default for this step which also maps SNPs to genes. You may skip this step in this workflow by clicking the "Skip MDF <i class="icon-fast-forward1"></i>" button.
					  </td>
					  <td>
						<a href="#"><img style="width: 60%;height: auto;border: 0px solid gray;" class="tut_pic" src="tutorial_imgs/GWASstep1.png" alt="GWAS button">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>2</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Upload or select association file.</b> A two column file is required with SNPs in the 'MARKER' column and association strength (e.g., -log10 transformed p-values, effect size, etc.) in the 'VALUE' column. Many different GWAS sample files are provided including metabolic, neurological, and psychiatric disorders. When uploading a GWAS file, upload all associations including those that do not reach nominal significance. 
					  </td>
					  <td style="padding-top: 30px;">
						<div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
					  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
						<p style="margin:0;"><b>File format</b></p>
						<table class="samplefile">
						  <thead>
							<tr class="samplefile">
							  <th style="font-size: 16px;">MARKER</th>
							  <th style="font-size: 16px;">VALUE</th>
							</tr>
						  </thead>
						  <tbody>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">rs4747841</td>
							  <td data-column="VALUE(Header): ">0.1452</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">rs4749917</td>
							  <td data-column="VALUE(Header): ">0.1108</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">rs737656</td>
							  <td data-column="VALUE(Header): ">1.3979</td>

							</tr>
						  </tbody>
						</table>
					  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>Type 2 diabetes</td>
							</tr>
							<tr>
							  <td>Alzheimer's disease</td>
							</tr>
							<tr>
							  <td>HDLC levels</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle'>
						  <div>3</div>
						</div>
					  </td>
					  <td>
						 <b>Upload or select SNP to gene mapping file.</b> SNPs in 'MARKER' column and mappped genes in 'GENE' column (i.e., using eQTLs, distance-based, etc.)
					  </td>
					  <td>
					<div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
					  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
						<p style="margin:0;"><b>File format</b></p>
						<table class="samplefile">
						  <thead>
							<tr class="samplefile">
							  <th style="font-size: 16px;">GENE</th>
							  <th style="font-size: 16px;">MARKER</th>
							</tr>
						  </thead>
						  <tbody>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">CDK6</td>
							  <td data-column="VALUE(Header): ">rs10</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">AGER</td>
							  <td data-column="VALUE(Header): ">rs1000</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">N4BP2</td>
							  <td data-column="VALUE(Header): ">rs1000000</td>

							</tr>
						  </tbody>
						</table>
					  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>20kb Distance Mapping</td>
							</tr>
							<tr>
							  <td>GTEx tissue-specific eQTLs</td>
							</tr>
							<tr>
							  <td>RegulomeDB Mapping </td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 80px;">
						<div class='circle'>
						  <div>4</div>
						</div>
					  </td>
					  <td style="padding-top: 80px;">
						 <b>Upload or select linkage disequilibrium file.</b> A three column file is needed with SNPs in 'MARKERa' and 'MARKERb' columns and the correlation value in the 'WEIGHT' column. We provide 1000 Genomes linkage disequilibrium files from all 26 populations as sample linkage files. An example of a sample choice is "CEU LD50". LD50 means that it will filter out SNPs with a correlation of 50% or higher. The three letter code refers to the population which can be decoded <a href="https://www.internationalgenome.org/category/population/">here</a>.
					  </td>
					  <td style="padding-top: 80px;">
					  <div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
					  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
						<p style="margin:0;"><b>File format</b></p>
						<table class="samplefile">
						  <thead>
							<tr class="samplefile">
							  <th style="font-size: 16px;">MARKERa</th>
							  <th style="font-size: 16px;">MARKERb</th>
							  <th style="font-size: 16px;">WEIGHT</th>
							</tr>
						  </thead>
						  <tbody>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">rs12565</td>
							  <td data-column="VALUE(Header): ">rs29776</td>
							  <td data-column="VALUE(Header): ">0.611</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">rs11804</td>
							  <td data-column="VALUE(Header): ">rs29776</td>
							  <td data-column="VALUE(Header): ">1</td>
							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">rs12138</td>
							  <td data-column="VALUE(Header): ">rs12562</td>
							  <td data-column="VALUE(Header): ">0.575</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>1000 Genomes LD Files</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>5</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Choose top percentage of associations.</b> Adjust accordingly based on the size of your GWAS. For example, try using 100 for small GWAS studies and 20 for large GWAS studies.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 50%;height: auto;" class="tut_pic" src="tutorial_imgs/GWAS_top_associations.png" alt="GWAS top associations">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle'>
						  <div>6</div>
						</div>
					  </td>
					  <td>
						 <b>Review files and submit MDF job.</b> Click 'Click to Review' to see files and parameters chosen. Enter an email and click 'Send Email' to receive emails when the job starts and when the job ends with a link to return to your session to continue onto MSEA. <b>Check the spam folder if the email is missing.</b> Then click 'Run MDF Pipeline' to submit the job. Depending on the size of the inputs, the analysis can range from 5 minutes to 30 minutes. 
					  </td>
					  <td>
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MDF_review_files.png" alt="MDF review">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="padding-top: 50px;">
							<p class="instructiontext" id="SSEA" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run marker set enrichment analysis (MSEA)</p>
						</td>
					</tr>
				</table>
				<table class="instructions" style="border-spacing: 0px;">
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>7</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve MDF results and continue to MSEA.</b> The corrected association file, subsetted mapping file, and a review of the chosen files and parameters will be available for download. To continue to MSEA, click 'Run MSEA Pipeline'.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 70%;height: auto;" class="tut_pic" src="tutorial_imgs/MDF_results.png" alt="MDF results">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 50px;">
						<div class='circle'>
						  <div>8</div>
						</div>
					  </td>
					  <td style="padding-top: 50px;">
						 <b>Select/upload gene sets.</b> These are the gene sets that will be tested for association to the disease. Gene sets can be knowledge-driven canonical pathways or data-driven coexpression modules.
					  </td>
					  <td style="padding-top: 50px;">
					  <div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
					  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
						<p style="margin:0;"><b>File format</b></p>
						<table class="samplefile">
						  <thead>
							<tr class="samplefile">
							  <th style="font-size: 16px;">MODULE</th>
							  <th style="font-size: 16px;">GENE</th>
							</tr>
						  </thead>
						  <tbody>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">Cell cycle</td>
							  <td data-column="VALUE(Header): ">CDC16</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">Cell cycle</td>
							  <td data-column="VALUE(Header): ">ANAPC1</td>
							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">WGCNA Brown</td>
							  <td data-column="VALUE(Header): ">XRCC5</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>Canonical pathways (KEGG, Reactome, BioCarta)</td>
							</tr>
							<tr>
							  <td>WGCNA Coexpression Modules</td>
							</tr>
							<tr>
							  <td>MEGENA Coexpression Modules</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 120px;">
						<div class='circle'>
						  <div>9</div>
						</div>
					  </td>
					  <td style="padding-top: 120px;">
						 <b>(Optional) Select/upload gene sets descriptions.</b> An optional file to include in order to annotate modules in results files. The DESCR column has a full description of the MODULE. Minimum columns needed are MODULE and DESCR. If selecting a sample gene set, the descriptions file will be added automatically.
					  </td>
					  <td style="padding-top: 120px;">
					  <div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
					  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
						<p style="margin:0;"><b>File format</b></p>
						<table class="samplefile" style="width: 32em;">
						  <thead>
							<tr class="samplefile">
							  <th style="font-size: 16px;">MODULE</th>
							  <th style="font-size: 16px;">SOURCE</th>
							  <th style="font-size: 16px;">DESCR</th>
							</tr>
						  </thead>
						  <tbody>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">Cell cycle</td>
							  <td data-column="VALUE(Header): ">KEGG</td>
							  <td data-column="VALUE(Header): ">Mitotic cell cycle progression is accomplished through a reproducible sequence of events - S, M, G1, and G2 phases.</td>
							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">WGCNA Brown</td>
							  <td data-column="VALUE(Header): ">WGCNA Liver Coexpression Module</td>
							  <td data-column="VALUE(Header): ">Immune function</td>
							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">Proteasome Pathway</td>
							  <td data-column="VALUE(Header): ">BioCarta</td>
							  <td data-column="VALUE(Header): ">https://www.gsea-msigdb.org/gsea/msigdb/cards/ BIOCARTA_PROTEASOME_PATHWAY</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 250px;">
						<div class='circle'>
						  <div>10</div>
						</div>
					  </td>
					  <td style="padding-top: 250px;">
						 <b>Choose MSEA parameters.</b> Default parameters are recommended settings. A description of each parameter can be viewed upon clicking on the 'Click For Tutorial' button. <b>Max Overlap for Merging Gene Mapping</b> is the overlap ratio threshold for merging of genes with shared SNPs. <b>Min Module Overlap Allowed for Merging</b> is the minimum overlap ratio for which a module will remain independent. Modules with overlap ratios above this value will be merged. <b>Number of Permutations:</b> For formal analysis, 10,000 permutations should be used, and 2,000 can be set for exploratory analysis. <b>MSEA to KDA export FDR cutoff:</b> Modules with an FDR less than this cutoff will be used for key driver analysis (KDA). If no modules pass this significance, the top 10 pathways regardless of FDR will be export to KDA. The user must interpret the results accordingly.
					  </td>
					  <td style="padding-top: 250px;">
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/SSEA_parameters.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>11</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Review files/parameters and submit MSEA job.</b> Click 'Click to Review' to see files and parameters chosen. Click 'Run MSEA Pipeline' to submit the job. Depending on the size of the inputs and number of permutations, the analysis can range from 5 minutes to 2 hours. To speed up computation time, decrease the number of permutations. Click on the 'Click to see runtime joblog' toggle to see the job progress in real time. This will be available for download on the results page.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 70%;height: auto;" class="tut_pic" src="tutorial_imgs/Review_SSEA_inputs.png" alt="MSEA review">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>12</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve MSEA results.</b> At the conclusion of MSEA, a download results table with appear and if an email address was entered, these results will also be emailed to that address. The <b>Module Details file</b> lists genes mapped from SNPs of the modules and the association strengths of the SNPs (as given in the association input file). The <b>Modules Results Summary</b> file reports module significance and number of genes and markers that contributed to the module. The <b>Merged Modules Results Summary</b> file is the full results for supersets (similar modules merged) and independent modules (not similar to any other modules). The <b>Merged Modules Nodes for KDA</b> file lists the nodes or gene sets that will be used for KDA. The website displays individual module results and merged modules results (pictured is the display of individual module results and users can toggle to the 'Merge Module Results'). Below is the interpretation of those tables.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/GWAS_MSEA_Results.png" alt="MSEA results">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  	<td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">MSEA Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:42em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Module ID</td>
								<td>Module id/gene set id from input gene set</td>
							  </tr>
							  <tr class="interpretation">
								<td>MSEA:P-Value</td>
								<td>Set enrichment p-value</td>

							  </tr>
							  <tr class="interpretation">
								<td>MSEA:FDR</td>
								<td>False discovery rate for set enrichment</td>
							  </tr>
							  <tr class="interpretation">
								<td>Description</td>
								<td>Gene set description, if included</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Genes</td>
								<td>Top five genes in the gene set with the highest values (e.g. lowest p-values) for the association study</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Marker</td>
								<td>Top five SNPs in the gene set with the highest values for the association study</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Association Score</td>
								<td>Top five highest values for the association study</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 7em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  <td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">Merged Modules Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:39em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Merge Module ID</td>
								<td>New module id/gene set after merge</td>
							  </tr>
							  <tr class="interpretation">
								<td>Merge Module P-value</td>
								<td>Merged set enrichment p-value</td>

							  </tr>
							  <tr class="interpretation">
								<td>Frequency</td>
								<td>Equivalent to FDR</td>
							  </tr>
							  <tr class="interpretation">
								<td>Number of Genes</td>
								<td>Number of genes in the gene set after merging</td>
							  </tr>
							  <tr class="interpretation">
								<td>Number of Markers</td>
								<td>Number of association study markers in the merged module</td>
							  </tr>
							  <tr class="interpretation">
								<td>Density</td>
								<td>Number of markers per gene</td>
							  </tr>
							  <tr class="interpretation">
								<td>Description</td>
								<td>Functional description of the merged module</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 7em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>13</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Choose to run wKDA or PharmOmics analysis (optional).</b> Users can stop their analysis at MSEA or continue to wKDA (go to step 14) or PharmOmics (go to step 20) if there are significant results from MSEA (FDR less than 0.05 or 0.25 are the recommended significance levels). User can choose one route and still run the other by opening the MSEA toggle and clicking on the other analysis. 
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_post_options.png" alt="Post MSEA options">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="padding-top: 3.5%;">
							<p class="instructiontext" id="SSEAtoKDA" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run MSEA to key driver analysis (KDA)</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>14</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Select/upload network file.</b> The network file describes molecular connections. In a directed network, the source is in the 'HEAD' column and the target is in the 'TAIL' column. The network need not be directed or have weights (the 'WEIGHT' column can have all '1's).
					  </td>
					  <td style="padding-top: 30px;">
						  <div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
						  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
							<p style="margin:0;"><b>File format</b></p>
							<table class="samplefile">
							  <thead>
								<tr class="samplefile">
								  <th style="font-size: 16px;">HEAD</th>
								  <th style="font-size: 16px;">TAIL</th>
								  <th style="font-size: 16px;">WEIGHT</th>
								</tr>
							  </thead>
							  <tbody>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1BG</td>
								  <td data-column="VALUE(Header): ">SNHG6</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1BG</td>
								  <td data-column="VALUE(Header): ">UNC84A</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1CF</td>
								  <td data-column="VALUE(Header): ">KIAA1958</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
							  </tbody>
							</table>
						  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>Tissue-specific gene regulatory networks</td>
							</tr>
							<tr>
							  <td>Protein-protein interaction network</td>
							</tr>
							<tr>
							  <td>Transcription factors and targets</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 70px;">
						<div class='circle'>
						  <div>15</div>
						</div>
					  </td>
					  <td style="padding-top: 70px;">
						 <b>Choose KDA parameters.</b> The <b>search depth</b> defines the distance (number of connections) away from a potential key driver that will be considered its local network neighborhood. A search depth of 1 is recommended but can be increased for sparse networks or small input gene lists. The <b>edge type</b> can be either 'Undirected' or 'Directed'; the former means that directionality (source and target designation) is not considered, and the latter means it is considered. The <b>min overlap</b> is the threshold above which hubs will be designated as co-hubs. The <b>edge factor</b> is the degree of influence of a edge weight. 0 is no influence (all weights are equal), 0.5 is partial influence, and 1 is full influence.
					  </td>
					  <td style="padding-top: 70px;">
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_parameters.png" alt="KDA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>16</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Review KDA files/parameters and submit job.</b> Click 'Click to Review' to see files and parameters chosen. Click 'Run wKDA Pipeline' to submit the job. Depending on the size of the inputs, the analysis can range from 10 minutes to 2 hours.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_review_files.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>17</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve KDA results.</b> At the conclusion of KDA, key driver results files and cytoscape files are generated. The 'Key Drivers Results' file lists for each key driver its P and FDR values, the module of which it is a key driver for, the total number of neighbors ('N.neigh'), the number of neighbors that are members of the module ('N.obsrv'), and the expected (null) number of neighbors that are members of the module as calculated by permutation. The cytoscape files can be used on Cytoscape Desktop for the user to customize the network visualization. Cytoscape visualization of the top 5 key drivers for each module can be viewed on the browser by clicking on 'Display KDA subnetwork'. The analysis can be concluded at this step or subnetwork genes can be used for drug repositioning in the PharmOmics pipeline by clicking on 'Run PharmOmics pipeline'. In the case that no significant (FDR < 0.05) key drivers are found, cytoscape files will not be generated. However, an option to view module gene overlap (if any) with network genes will appear.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_results_table.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  <td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">KDA Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:40em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Merge Module ID</td>
								<td>New module id/gene set after merge</td>
							  </tr>
							  <tr class="interpretation">
								<td>Key Driver Node</td>
								<td>Key driver genes in the merged module</td>
							  </tr>
							  <tr class="interpretation">
								<td>P-value</td>
								<td>Enrichment p-value for key driver genes</td>
							  </tr>
							  <tr class="interpretation">
								<td>FDR</td>
								<td>False discovery rate for the enrichment value of the key driver node</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Genes</td>
								<td>Total number of nodes in the gene module</td>
							  </tr>
							  <tr class="interpretation">
								<td>KD Subnetwork Genes</td>
								<td>Total number of genes in key driver subnetwork</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module and Subnetwork Overlap</td>
								<td>Number of key driver neighbors that are members of the module</td>
							  </tr>
							  <tr class="interpretation">
								<td>Fold Enrichment</td>
								<td>Enrichment of KD subnetwork genes within the gene module</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 9em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="padding-top: 3.5%;">
							<p class="instructiontext" id="SSEAtoKDAtoPharm" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run KDA to PharmOmics</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>18</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>(Optional) Select inputs for KDA to PharmOmics analysis.</b> The user can conclude analysis at KDA or run drug based repositioning on KDA subnetwork results. Choose to run overlap based drug repositioning (around 5 min to run) or network based drug repositioning (around 30 minutes to 2 hours to run). Next, choose all genes from the generated subnetwork, only genes from input modules (i.e. gene sets) in the subnetwork (not including subnetwork genes that are not members of input modules), or the user can choose specific modules to include. For drug network repositioning, you additionally need to select or upload a network and select the species. Also for network drug repositioning, we offer only the option to query meta signatures (studies using different dose and treatment durations are meta-analyzed). You may use your results in our separate PharmOmics pipeline to run the dose/time segregated analysis which will take around 3 hours to complete and will require a login due to heavy use of computational resources.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_to_PharmOmics_options.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>19a</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve PharmOmics results from overlap based drug repositioning.</b> Image on the right shows results from the overlap based drug repositioning option. The results ranks the drugs based on concordance between the KDA subnetwork genes and the drug signatures genes (Jaccard score). Data from our comprehensive species- and tissue-specific drug signatures are used as well as L1000 signatures. The drug repositioning results and the genes used for repositioning can be obtained from the download links.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_toPharmApp3_Results.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>19b</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve PharmOmics results from network based drug repositioning.</b> Image on the right shows results from the network based drug repositioning option. The results ranks the drugs based on the significance of connectivity between drug signature genes and input genes as defined by a gene network model. Signatures from our comprehensive species- and tissue-specific drug signatures database are used. The drug repositioning results and the genes used for repositioning can be obtained from the download links. We also provide links to visualize the network model of drug genes and their first neighbor input genes (input genes having one edge distance from drug gene).
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_to_PharmApp2_Results.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
			  	</table>
			  	<table>
					<tr>
						<td style="padding-top: 3.5%;">
							<p class="instructiontext" id="SSEAtoPharm" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run MSEA to PharmOmics</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>20</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Select input modules and drug repositioning type.</b> Choose to run overlap (around 5 min to run) or network based drug repositioning (around 30 minutes to 2 hours to run). Next, choose to run all modules below a specified significance threshold or select specific modules. Finally, choose whether to use genes from the entire geneset or only those genes that were mapped from SNPs (genes derived from association data). For drug network repositioning, you additionally need to select or upload a network and select the species. Also for network drug repositioning, we offer only the option to query meta signatures (studies using different dose and treatment durations are meta-analyzed). You may use your results in our separate PharmOmics pipeline to run the dose/time segregated analysis which will take around 3 hours to complete and will require a login due to heavy use of computational resources. Please refer to steps 19a and 19b to read interpretation of PharmOmics results. 
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_to_Pharm.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
			</div>
			<div id="myTPEMWASinfo" class="data-info" style="display:none;padding: 3% 0% 3% 2%;">
				<p style="font-size: 18px;margin-top: 0px;margin-bottom: 0px;">The workflow for epigenome-, transcriptome-, proteome-, and metabolome-wide association studies is similar to GWAS except that it does not include a mapping file or marker dependency filtering by default though it is included as optional steps in this pipeline. This is because TWAS and PWAS markers do not need to be mapped to genes (if gene sets are to be tested). However, we leave the options to include a mapping file and run marker dependency filtering as epigenome markers such as CpG sites may be mapped to genes and also may have dependencies. Currently, we do not provide any metabolite marker sets or metabolite to gene mapping files, but the user can provide these files.</p>
				<p class="instructiontext" id="MSEA" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run marker set enrichment analysis (MSEA).</p>
				<table class="instructions" style="margin: 0% 0% 0%;">
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>1</div>
						</div>
					  </td>
					  <td>
						<b>Start pipeline.</b> If you have a single TWAS, EWAS, PWAS, or MWAS study, click on 'Individual EWAS, TWAS, PWAS, or MWAS enrichment' on the Run Mergeomics page.
					  </td>
					  <td>
						<a href="#">
							<img style="width: 80%;height: auto;border: 0px solid gray;" class="tut_pic" src="tutorial_imgs/StartETPMS.png" alt="ETPMWAS button">
							<span class="icon-search-plus"></span>
						</a>
						
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle'>
						  <div>2</div>
						</div>
					  </td>
					  <td>
						 <b>Upload or select association file.</b> A two column file is required with markers (i.e. genes, methylation sites, etc.) in the 'MARKER' column and association strength (e.g., -log10 transformed p-values, fold change, etc.) in the 'VALUE' column. You may first <strong>upload all associations including those that do not reach nominal significance</strong> and adjust the cutoff of signals to include accordingly (top 50%, 20%, etc.).
					  </td>
					  <td>
						<div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
					  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
						<p style="margin:0;"><b>File format</b></p>
						<table class="samplefile">
						  <thead>
							<tr class="samplefile">
							  <th style="font-size: 16px;">MARKER</th>
							  <th style="font-size: 16px;">VALUE</th>
							</tr>
						  </thead>
						  <tbody>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">C1QA</td>
							  <td data-column="VALUE(Header): ">5.348</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">GFAP</td>
							  <td data-column="VALUE(Header): ">1.907</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">JUNB</td>
							  <td data-column="VALUE(Header): ">0.425</td>

							</tr>
						  </tbody>
						</table>
					  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>Example EWAS</td>
							</tr>
							<tr>
							  <td>Example TWAS (DEGs set)</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>3</div>
						</div>
					  </td>
					  <td>
						<b>(Optional) Upload/select mapping file.</b> If your markers do not match those of the marker sets to be enriched, then select 'Yes' to the question 'Would you like to use a mapping file?' and upload or select a mapping file. For example, we provide CG methylation probe ID to gene mappings and gene sets can subsequently be tested for enrichment. Transcriptome and proteome data usually do not require a mapping file. The required file format is pictured to the right ('MARKER' and 'GENE' columns).
					  </td>
					  <td>
						<a href="#">
							<img style="width: 80%;height: auto;border: 0px solid gray;" class="tut_pic" src="tutorial_imgs/ETPMWAS_mapping_option.png" alt="ETPMWAS mapping option">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>4</div>
						</div>
					  </td>
					  <td>
						<b>(Optional) Upload marker dependency file.</b> If there are dependencies between markers that may lead to spurious associations, a dependency file can be uploaded and if both constituents of any of the marker pairs in the dependency file is detected, the marker with the highest association value will be kept. This option only appears if a mapping file is used.
					  </td>
					  <td>
						<a href="#">
							<img style="width: 80%;height: auto;border: 0px solid gray;" class="tut_pic" src="tutorial_imgs/ETPMWAS_dependency_option.png" alt="ETPMWAS mapping option">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 50px;">
						<div class='circle'>
						  <div>5</div>
						</div>
					  </td>
					  <td style="padding-top: 50px;">
						 <b>Select/upload gene sets.</b> These are the gene sets that will be tested for association to the disease. Gene sets can be knowledge-driven canonical pathways or data-driven coexpression modules.
					  </td>
					  <td style="padding-top: 50px;">
					  <div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
					  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
						<p style="margin:0;"><b>File format</b></p>
						<table class="samplefile">
						  <thead>
							<tr class="samplefile">
							  <th style="font-size: 16px;">MODULE</th>
							  <th style="font-size: 16px;">GENE</th>
							</tr>
						  </thead>
						  <tbody>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">Cell cycle</td>
							  <td data-column="VALUE(Header): ">CDC16</td>

							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">Cell cycle</td>
							  <td data-column="VALUE(Header): ">ANAPC1</td>
							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">WGCNA Brown</td>
							  <td data-column="VALUE(Header): ">XRCC5</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>Canonical pathways (KEGG, Reactome, BioCarta)</td>
							</tr>
							<tr>
							  <td>WGCNA Coexpression Modules</td>
							</tr>
							<tr>
							  <td>MEGENA Coexpression Modules</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 120px;">
						<div class='circle'>
						  <div>6</div>
						</div>
					  </td>
					  <td style="padding-top: 120px;">
						 <b>(Optional) Select/upload gene sets descriptions.</b> An optional file to include in order to annotate modules in results files. The DESCR column has a full description of the MODULE. Minimum columns needed are MODULE and DESCR. If a sample gene set is chosen, the descriptions will be added automatically.
					  </td>
					  <td style="padding-top: 120px;">
					  <div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 4%;">
					  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
						<p style="margin:0;"><b>File format</b></p>
						<table class="samplefile" style="width: 32em;">
						  <thead>
							<tr class="samplefile">
							  <th style="font-size: 16px;">MODULE</th>
							  <th style="font-size: 16px;">SOURCE</th>
							  <th style="font-size: 16px;">DESCR</th>
							</tr>
						  </thead>
						  <tbody>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">Cell cycle</td>
							  <td data-column="VALUE(Header): ">KEGG</td>
							  <td data-column="VALUE(Header): ">Mitotic cell cycle progression is accomplished through a reproducible sequence of events - S, M, G1, and G2 phases.</td>
							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">WGCNA Brown</td>
							  <td data-column="VALUE(Header): ">WGCNA Liver Coexpression Module</td>
							  <td data-column="VALUE(Header): ">Immune function</td>
							</tr>
							<tr class="samplefile">
							  <td data-column="MARKER(Header): ">Proteasome Pathway</td>
							  <td data-column="VALUE(Header): ">BioCarta</td>
							  <td data-column="VALUE(Header): ">https://www.gsea-msigdb.org/gsea/msigdb/cards/ BIOCARTA_PROTEASOME_PATHWAY</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 180px;">
						<div class='circle'>
						  <div>7</div>
						</div>
					  </td>
					  <td style="padding-top: 180px;">
						 <b>Choose MSEA parameters.</b> Default parameters are recommended settings. A description of each parameter can be viewed upon clicking on the 'Click For Tutorial' button. For EWAS/TWAS/PWAS/MWAS data, the <b>permutation type</b> is set to "marker" and <b>Max Overlap for Merging Gene Mapping</b> is set to 1 (means no merging) (only applies to GWAS data). <b>Min Module Overlap Allowed for Merging</b> is the minimum overlap ratio for which a module will remain independent. Modules with overlap ratios above this value will be merged. Set to 1 to skip module merging. <b>Number of Permutations:</b> For formal analysis, 10,000 permutations should be used, and 2,000 can be set for exploratory analysis. <b>MSEA to KDA export FDR cutoff:</b> Modules with an FDR less than this cutoff will be used for key driver analysis (KDA). If no modules pass this significance, the top 10 pathways regardless of FDR will be export to KDA. The user must interpret the results accordingly.
					  </td>
					  <td style="padding-top: 180px;">
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/ETPMWAS_parameters.png" alt="ETPMWAS parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>8</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Review files/parameters and submit MSEA job.</b> Click 'Click to Review' to see files and parameters chosen. Click 'Run MSEA Pipeline' to submit the job. Depending on the size of the inputs and number of permutations, the analysis can range from 10 minutes to 2 hours. To speed up computation time, decrease the number of permutations.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 70%;height: auto;" class="tut_pic" src="tutorial_imgs/ETPMWAS_review_param_files.png" alt="ETPMWAS review">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>9</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve MSEA results.</b> At the conclusion of MSEA, a download results table with appear and if an email address was entered, these results will also be emailed to that address. The <b>Module Details file</b> lists genes mapped from SNPs of the modules and the association strengths of the SNPs (as given in the association input file). The <b>Modules Results Summary</b> file reports module significance and number of genes and markers that contributed to the module. The <b>Merged Modules Results Summary</b> file is the full results for supersets (similar modules merged) and independent modules (not similar to any other modules). The <b>Merged Modules Nodes for KDA</b> file lists the nodes or gene sets that will be used for KDA. The website displays individual module results and merged modules results (pictured is the display of individual module results and users can toggle to the 'Merge Module Results'). Below is the interpretation of those tables.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_Results.png" alt="MSEA results">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  	<td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">MSEA Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:42em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Module ID</td>
								<td>Module id/gene set id from input gene set</td>
							  </tr>
							  <tr class="interpretation">
								<td>MSEA:P-Value</td>
								<td>Set enrichment p-value</td>

							  </tr>
							  <tr class="interpretation">
								<td>MSEA:FDR</td>
								<td>False discovery rate for set enrichment</td>
							  </tr>
							  <tr class="interpretation">
								<td>Description</td>
								<td>Gene set description, if included</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Genes</td>
								<td>Top five genes in the gene set with the highest values (e.g. lowest p-values) for the association study</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Marker</td>
								<td>Top five SNPs in the gene set with the highest values for the association study</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Association Score</td>
								<td>Top five highest values for the association study</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 7em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  <td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">Merged Modules Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:39em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Merge Module ID</td>
								<td>New module id/gene set after merge</td>
							  </tr>
							  <tr class="interpretation">
								<td>Merge Module P-value</td>
								<td>Merged set enrichment p-value</td>

							  </tr>
							  <tr class="interpretation">
								<td>Frequency</td>
								<td>Equivalent to FDR</td>
							  </tr>
							  <tr class="interpretation">
								<td>Number of Genes</td>
								<td>Number of genes in the gene set after merging</td>
							  </tr>
							  <tr class="interpretation">
								<td>Number of Markers</td>
								<td>Number of association study markers in the merged module</td>
							  </tr>
							  <tr class="interpretation">
								<td>Density</td>
								<td>Number of markers per gene</td>
							  </tr>
							  <tr class="interpretation">
								<td>Description</td>
								<td>Functional description of the merged module</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 7em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>10</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Choose to run wKDA or PharmOmics analysis (optional).</b> Users can stop their analysis at MSEA or continue to wKDA (go to step 11) or PharmOmics (go to step 17) if there are significant results from MSEA (FDR less than 0.05 or 0.25 are the recommended significance levels). Users can choose one route and still run the other by opening the MSEA toggle and clicking on the other analysis. 
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_post_options.png" alt="Post MSEA options">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="padding-top: 3.5%;">
							<p class="instructiontext" id="MSEAtoKDA" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run MSEA to key driver analysis (KDA)</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>11</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Select/upload network file.</b> The network file describes molecular connections. In a directed network, the source is in the 'HEAD' column and the target is in the 'TAIL' column. The network need not be directed or have weights (the 'WEIGHT' column can have all '1's)
					  </td>
					  <td style="padding-top: 30px;">
						  <div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
						  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
							<p style="margin:0;"><b>File format</b></p>
							<table class="samplefile">
							  <thead>
								<tr class="samplefile">
								  <th style="font-size: 16px;">HEAD</th>
								  <th style="font-size: 16px;">TAIL</th>
								  <th style="font-size: 16px;">WEIGHT</th>
								</tr>
							  </thead>
							  <tbody>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1BG</td>
								  <td data-column="VALUE(Header): ">SNHG6</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1BG</td>
								  <td data-column="VALUE(Header): ">UNC84A</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1CF</td>
								  <td data-column="VALUE(Header): ">KIAA1958</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
							  </tbody>
							</table>
						  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>Tissue-specific gene regulatory networks</td>
							</tr>
							<tr>
							  <td>Protein-protein interaction network</td>
							</tr>
							<tr>
							  <td>Transcription factors and targets</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 60px;">
						<div class='circle'>
						  <div>12</div>
						</div>
					  </td>
					  <td style="padding-top: 60px;">
						 <b>Choose KDA parameters.</b> The 'search depth' defines the distance (number of connections) away from a potential key driver that will be considers its local network neighborhood. A search depth of 1 is recommended but can be increased for sparse networks or small input gene lists. The 'edge type' can be either 'Undirected' or 'Directed'; the former means that directionality (source and target designation) is not considered and the latter means it is considered. The 'min overlap' is the threshold above which hubs will be designated as co-hubs. The 'Edge factor' is the degree of influence of a edge weight. 0 is no influence (all weights are equal), 1 is full influence, and 0.5 is partial influence.
					  </td>
					  <td style="padding-top: 60px;">
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_parameters.png" alt="KDA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>13</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Review KDA files/parameters and submit job.</b> Click 'Click to Review' to see files and parameters chosen. Click 'Run wKDA Pipeline' to submit the job. Depending on the size of the inputs, the analysis can range from 10 minutes to 2 hours.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_review_files.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>14</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve KDA results.</b> At the conclusion of KDA, key driver results files and cytoscape files are generated. The 'Key Drivers Results' file lists for each key driver its P and FDR values, the module of which it is a key driver for, the total number of neighbors ('N.neigh'), the number of neighbors that are members of the module ('N.obsrv'), and the expected (null) number of neighbors that are members of the module as calculated by permutation. The cytoscape files can be used on Cytoscape Desktop for the user to customize the network visualization. Cytoscape visualization of the top 5 key drivers for each module can be viewed on the browser by clicking on 'Display KDA subnetwork'. The analysis can be concluded at this step or subnetwork genes can be used for drug repositioning in the PharmOmics pipeline by clicking on 'Run PharmOmics pipeline'.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_results_table.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  <td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">KDA Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:40em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Merge Module ID</td>
								<td>New module id/gene set after merge</td>
							  </tr>
							  <tr class="interpretation">
								<td>Key Driver Node</td>
								<td>Key driver genes in the merged module</td>
							  </tr>
							  <tr class="interpretation">
								<td>P-value</td>
								<td>Enrichment p-value for key driver genes</td>
							  </tr>
							  <tr class="interpretation">
								<td>FDR</td>
								<td>False discovery rate for the enrichment value of the key driver node</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Genes</td>
								<td>Total number of nodes in the gene module</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module and Subnetwork Overlap</td>
								<td>Number of key driver neighbors that are members of the module</td>
							  </tr>
							  <tr class="interpretation">
								<td>Fold Enrichment</td>
								<td>Enrichment of KD subnetwork genes within the gene module</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 9em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td>
							<p class="instructiontext" id="MSEAtoKDAtoPharm" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run MSEA to PharmOmics</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>15</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>(Optional) Select inputs for KDA to PharmOmics analysis.</b> The user can conclude analysis at KDA or run drug based repositioning on KDA subnetwork results. Choose to run overlap based drug repositioning (around 5 minutes to run) or network based drug repositioning (around 30 minutes to 2 hours to run). Next, choose all genes from the generated subnetwork, only genes from input modules (i.e., gene sets) in the subnetwork (not including subnetwork genes that are not members of input modules), or the user can choose specific modules to include. For drug network repositioning, you additionally need to select or upload a network and select the species. Also for network drug repositioning, we offer only the option to query meta signatures (studies using different dose and treatment durations are meta-analyzed). You may use your results in our separate PharmOmics pipeline to run the dose/time segregated analysis which will take around 3 hours to complete and will require a login due to heavy use of computational resources.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_to_PharmOmics_options.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>16a</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve PharmOmics results from overlap based drug repositioning.</b> Image on the right shows results from the overlap based drug repositioning option. The results ranks the drugs based on concordance between the KDA subnetwork genes and the drug signatures genes (Jaccard score). Data from our comprehensive species- and tissue-specific drug signatures are used as well as L1000 signatures. The drug repositioning results and the genes used for repositioning can be obtained from the download links.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_to_PharmApp3_Results.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>16b</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve PharmOmics results from network based drug repositioning.</b> Image on the right shows results from the network based drug repositioning option. The results ranks the drugs based on the significance of connectivity between drug signature genes and input genes as defined by a gene network model. Signatures from our comprehensive species- and tissue-specific drug signatures database are used. The drug repositioning results and the genes used for repositioning can be obtained from the download links. We also provide links to visualize the network model of drug genes and their first neighbor input genes (input genes having one edge distance from drug gene).
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_to_PharmApp2_Results.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
			  	</table>
			  	<table>
					<tr>
						<td>
							<p class="instructiontext" id="MSEAtoPharm" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run MSEA to PharmOmics</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>17</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Select input modules and drug repositioning type.</b> Choose to run overlap (around 5 min to run) or network based drug repositioning (around 30 minutes to 2 hours to run). Next, choose to run all modules below a specified significance threshold or select specific modules. For drug network repositioning, you additionally need to select or upload a network and select the species. Also for network drug repositioning, we offer only the option to query meta signatures (studies using different dose and treatment durations are meta-analyzed). You may use your results in our separate PharmOmics pipeline to run the dose/time segregated analysis which will take around 3 hours to complete and will require a login due to heavy use of computational resources. Please refer to steps 16a and 16b to read interpretation of PharmOmics results. 
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_to_Pharm.png" alt="MSEA to Pharm parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
			</div>
			<div id="myMETAinfo" class="data-info" style="display:none;padding: 3% 0% 3% 2%;">
				<p style="font-size: 18px;margin-top: 0px;">In meta-MSEA, a marker set level meta analysis is done for any combination of multiple omics studies (multiple of the same type or different types). MSEA is run separately on each dataset and a meta p-value is calculated across the datasets.</p>
				<p class="instructiontext" id="Meta" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">
				Add datasets and run meta-MSEA
				</p>
				<table class="instructions" style="margin: 0% 0% 0%;">
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>1</div>
						</div>
					  </td>
					  <td>
						<b>Start pipeline.</b> If you have multiple omics studies (multiple of the same type or multiple of different types) to analyze and would like to run a marker set level meta analysis, click on 'Multiple Omics Datasets (GWAS, EWAS, TWAS, PWAS, MWAS) enrichment'.
					  </td>
					  <td>
						<img style="width: 30%;height: auto;border: 0px solid gray;" class="tut_pic" src="tutorial_imgs/StartMeta_button.png" alt="Meta button">
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle'>
						  <div>2</div>
						</div>
					  </td>
					  <td>
						 <b>Choose type of association data to add.</b> According to your data type, select 'GWAS Enrichment' or 'EWAS/TWAS/PWAS/MWAS Enrichment'. This separation exists because the preprocessing of and parameters for GWAS and EWAS/TWAS/PWAS/MWAS data for MSEA are slightly different.
					  </td>
					  <td>
					  	<a href="#">
					  		<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/StartMeta_buttons.png" alt="Meta options">
					  		<span class="icon-search-plus"></span>
					  	</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>3</div>
						</div>
					  </td>
					  <td>
						<b>Select/upload files and parameters for MSEA for each dataset.</b> The workflow for adding files and parameters for GWAS and EWAS/TWAS/PWAS/MWAS data is the same as in the individual pipelines. Refer to the 'Run marker set enrichment analysis section' for <a class="navGWAS" href="tutorial.php#SSEA">GWAS</a> and for <a class="navETPMWAS" href="#">EWAS/TWAS/PWAS/MWAS</a>. You may also refer to the 'Data input details' and 'Parameters details' sections below.
					  </td>
					  <td>
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_inputs_v2.png" alt="MSEA inputs">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>4</div>
						</div>
					  </td>
					  <td>
						<b>Select parameters for MSEA for each dataset.</b> "Gene" permutation is recommended for GWAS and "Marker" is the only option for EWAS/TWAS/PWAS/MWAS. "Max Overlap for Merging Gene Mapping" is the overlap ratio threshold for merging genes with shared markers (SNPs). This applied only to GWAS data and is set to 1 for EWAS/TWAS/PWAS/MWAS data. The "MSEA to KDA export FDR cutoff" is the cutoff for the individual MSEA to be considered for KDA. Set the value to 100 to have no cutoff apply for the specific dataset. The module/gene set will still need to pass the Meta-MSEA FDR cutoff.
					  </td>
					  <td>
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/Meta_Individ_Dataset_Parameters.png" alt="MSEA inputs">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>5</div>
						</div>
					  </td>
					  <td>
						<b>Review datasets.</b> Each dataset addition with be appended to this review table. Added datasets can be deleted and more datasets can be added by clicking on 'Add another association data'. The minimum number of datasets is 2 and the maximum is 5. Because MSEA is run on mutiple datasets, the runtime may be very long (an individual MSEA run is usually 5 minutes to 30 minutes). Runtime can be shortened by decreasing number of permutations.
					  </td>
					  <td>
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/Meta_review.png" alt="ETPMWAS mapping option">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>6</div>
						</div>
					  </td>
					  <td>
						<b>Select marker sets and parameters for Meta-MSEA.</b>. "Min Module Overlap Allowed for Merging" is the minimum gene overlap ratio between modules that will have them merged (to merge redundant modules). We recommend the default value. "MSEA to KDA export Meta FDR cutoff" is the meta FDR cutoff to consider modules/gene sets for KDA. The modules must pass all individual MSEA cutoffs as well as the meta FDR cutoff to be included in KDA. To run meta-MSEA, click on 'Run multiple omics datasets enrichment'.
					  </td>
					  <td>
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/MetaMSEA_param.png" alt="MSEA inputs">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td>
						<div class='circle' style="position: center;">
						  <div>7</div>
						</div>
					  </td>
					  <td>
						<b>Retrieve meta-MSEA results.</b> Result files available for download include the 'Modules Details' file which lists the genes that contributed to the module's association, the 'Full Results File' which lists for each module the P and FDR values of association significance and the number of genes that contributed to the module's association. The 'Merged Modules Full Results' file shows the results for nonredundant sets (similar gene sets are merged based on 'Min Module Overlap Allowed for Merging' parameter). The 'Merged Modules' file contains the genes that will be used as input to KDA if the user would like to run wKDA directly using the 'Run wKDA Pipeline' button. The 'Meta MSEA Individual Study P and FDR Results' file lists for each individual MSEA the association P and FDR values. Each dataset has an ID which can be decoded with the "Meta MSEA File and Parameter Selection" File. The 'Individual MSEA Result Files' is a zip file containing full MSEA results for each study. Interpretation for the interactive result tables is below. You may conclude your analysis at MSEA or continue to KDA (step 6). Please note that the default meta FDR cut off for associated modules from meta-MSEA is 25% (MSEA to KDA export FDR cutoff parameter). If you would like to be more stringent or lenient in your inclusion of modules, you may either edit the 'Merged Modules' file to only include your desired modules according to the significance results and use them in the standalone wKDA pipeline or rerun the analysis with a more stringent cutoff. PharmOmics can also be run on results from MSEA by clicking on 'Run PharmOmics Pipeline' (go to step 14).
					  </td>
					  <td>
						<a href="#">
							<img style="width: 80%;height: auto;" class="tut_pic" src="tutorial_imgs/Meta_Results.png" alt="ETPMWAS mapping option">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  	<td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">MSEA Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:42em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Module ID</td>
								<td>Module id/gene set id from input gene set</td>
							  </tr>
							  <tr class="interpretation">
								<td>MSEA:P-Value</td>
								<td>Set enrichment p-value</td>

							  </tr>
							  <tr class="interpretation">
								<td>MSEA:FDR</td>
								<td>False discovery rate for set enrichment</td>
							  </tr>
							  <tr class="interpretation">
								<td>Description</td>
								<td>Gene set description, if included</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Genes</td>
								<td>Top five genes in the gene set with the highest values (e.g. lowest p-values) for the association study</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Marker</td>
								<td>Top five SNPs in the gene set with the highest values for the association study</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Top Association Score</td>
								<td>Top five highest values for the association study</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 7em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  <td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">Merged Modules Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:39em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Merge Module ID</td>
								<td>New module id/gene set after merge</td>
							  </tr>
							  <tr class="interpretation">
								<td>Merge Module P-value</td>
								<td>Merged set enrichment p-value</td>

							  </tr>
							  <tr class="interpretation">
								<td>Frequency</td>
								<td>Equivalent to FDR</td>
							  </tr>
							  <tr class="interpretation">
								<td>Number of Genes</td>
								<td>Number of genes in the gene set after merging</td>
							  </tr>
							  <tr class="interpretation">
								<td>Number of Markers</td>
								<td>Number of association study markers in the merged module</td>
							  </tr>
							  <tr class="interpretation">
								<td>Density</td>
								<td>Number of markers per gene</td>
							  </tr>
							  <tr class="interpretation">
								<td>Description</td>
								<td>Functional description of the merged module</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 7em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  <td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">Combined Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:39em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Module</td>
								<td>Module name</td>
							  </tr>
							  <tr class="interpretation">
								<td>Description</td>
								<td>Module description</td>
							  </tr>
							  <tr class="interpretation">
								<td>P.values</td>
								<td>P Values for individual MSEA studies (P-value=1 if study missing)</td>
							  </tr>
							  <tr class="interpretation">
								<td>FDR.values</td>
								<td>FDR Values for individual MSEA studies (FDR=1 if study missing)</td>
							  </tr>
							  <tr class="interpretation">
								<td>Meta P</td>
								<td>Meta P Value</td>
							  </tr>
							  <tr class="interpretation">
								<td>Meta FDR</td>
								<td>Meta FDR Value</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 7em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="padding-top: 3.5%;">
							<p class="instructiontext" id="MetatoKDA" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run Meta-MSEA to key driver analysis (KDA)</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>8</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Select/upload network file.</b> The network file describes molecular connections. In a directed network, the source is in the 'HEAD' column and the target is in the 'TAIL' column. The network need not be directed or have weights (the 'WEIGHT' column can have all '1's)
					  </td>
					  <td style="padding-top: 30px;">
						  <div class="table-responsive floatbox" style="overflow: visible;width:30%;padding: 0% 0%;margin-left: 3%;">
						  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
							<p style="margin:0;"><b>File format</b></p>
							<table class="samplefile">
							  <thead>
								<tr class="samplefile">
								  <th style="font-size: 16px;">HEAD</th>
								  <th style="font-size: 16px;">TAIL</th>
								  <th style="font-size: 16px;">WEIGHT</th>
								</tr>
							  </thead>
							  <tbody>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1BG</td>
								  <td data-column="VALUE(Header): ">SNHG6</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1BG</td>
								  <td data-column="VALUE(Header): ">UNC84A</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
								<tr class="samplefile">
								  <td data-column="MARKER(Header): ">A1CF</td>
								  <td data-column="VALUE(Header): ">KIAA1958</td>
								  <td data-column="VALUE(Header): ">1</td>
								</tr>
							  </tbody>
							</table>
						  </div>
					  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 5%;">
						<p style="margin:0;"><b>Example sample files provided</b></p>
						<table class="samplefileprov">
						  <tbody>
							<tr>
							  <td>Tissue-specific gene regulatory networks</td>
							</tr>
							<tr>
							  <td>Protein-protein interaction network</td>
							</tr>
							<tr>
							  <td>Transcription factors and targets</td>
							</tr>
						  </tbody>
						</table>
					  </div>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 60px;">
						<div class='circle'>
						  <div>9</div>
						</div>
					  </td>
					  <td style="padding-top: 60px;">
						 <b>Choose KDA parameters.</b> The 'search depth' defines the distance (number of connections) away from a potential key driver that will be considers its local network neighborhood. A search depth of 1 is recommended but can be increased for sparse networks or small input gene lists. The 'edge type' can be either 'Undirected' or 'Directed'; the former means that directionality (source and target designation) is not considered and the latter means it is considered. The 'min overlap' is the threshold above which hubs will be designated as co-hubs. The 'Edge factor' is the degree of influence of a edge weight. 0 is no influence (all weights are equal) and 1 is full influence.
					  </td>
					  <td style="padding-top: 60px;">
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_parameters.png" alt="KDA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>10</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Review KDA files/parameters and submit job.</b> Click 'Click to Review' to see files and parameters chosen. Click 'Run wKDA Pipeline' to submit the job. Depending on the size of the inputs, the analysis can range from 10 minutes to 2 hours.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_review_files.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>11</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve KDA results.</b> At the conclusion of KDA, key driver results files and cytoscape files are generated. The 'Key Drivers Results' file lists for each key driver its P and FDR values, the module of which it is a key driver for, the total number of neighbors ('N.neigh'), the number of neighbors that are members of the module ('N.obsrv'), and the expected (null) number of neighbors that are members of the module as calculated by permutation. The cytoscape files can be used on Cytoscape desktop for the user to customize the network visualization. Cytoscape visualization of the top 5 key drivers for each module can be viewed on the browser by clicking on 'Display KDA subnetwork'. The analysis can be concluded at this step or subnetwork genes can be used for drug repositioning in the PharmOmics pipeline by clicking on 'Run PharmOmics pipeline'.
					  </td>
					  <td style="padding-top: 30px;">
						<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_results_table.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="width: 8em">
						</td>
					  <td>
						<p class="instructiontext" style="font-size: 18px;margin-bottom: 0px; padding: 0px;text-align: center;font-weight: bold;">KDA Results Interpretation</p>
						<div class="table-responsive" style="overflow: visible;">
						  <table class="interpretation" style="width:40em;">
							<thead>
							  <tr class="interpretation">
								<th>Field Name</th>
								<th>Description</th>
							  </tr>
							</thead>
							<tbody>
							  <tr class="interpretation">
								<td>Merge Module ID</td>
								<td>New module id/gene set after merge</td>
							  </tr>
							  <tr class="interpretation">
								<td>Key Driver Node</td>
								<td>Key driver genes in the merged module</td>
							  </tr>
							  <tr class="interpretation">
								<td>P-value</td>
								<td>Enrichment p-value for key driver genes</td>
							  </tr>
							  <tr class="interpretation">
								<td>FDR</td>
								<td>False discovery rate for the enrichment value of the key driver node</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module Genes</td>
								<td>Total number of nodes in the gene module</td>
							  </tr>
							  <tr class="interpretation">
								<td>Module and Subnetwork Overlap</td>
								<td>Number of key driver neighbors that are members of the module</td>
							  </tr>
							  <tr class="interpretation">
								<td>Fold Enrichment</td>
								<td>Enrichment of KD subnetwork genes within the gene module</td>
							  </tr>
							</tbody>
						  </table>
						</div>
					  </td>
					  <td style="width: 9em;">
					  </td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="padding-top: 3.5%;">
							<p class="instructiontext" id="MetatoKDAtoPharm" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run KDA to PharmOmics</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>12</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>(Optional) Select inputs for KDA to PharmOmics analysis.</b> The user can conclude analysis at KDA or run drug based repositioning on KDA subnetwork results. Choose to run overlap (around 5 min to run) or network based drug repositioning (around 30 minutes to 2 hours to run). Next, choose all genes from the generated subnetwork, only genes from input modules (i.e. gene sets) in the subnetwork (not including subnetwork genes that are not members of input modules), or the user can choose specific modules to include. For drug network repositioning, you additionally need to select or upload a network and select the species. Also for network drug repositioning, we offer only the option to query meta signatures (studies using different dose and treatment durations are meta-analyzed). You may use your results in our separate PharmOmics pipeline to run the dose/time segregated analysis which will take around 3 hours to complete and will require a login due to heavy use of computational resources.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_to_PharmOmics_options.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>13a</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve PharmOmics results from overlap based drug repositioning.</b> Image on the right shows results from the overlap based drug repositioning option. The results ranks the drugs based on concordance between the KDA subnetwork genes and the drug signatures genes (Jaccard score). Data from our comprehensive species- and tissue-specific drug signatures are used as well as L1000 signatures. The drug repositioning results and the genes used for repositioning can be obtained from the download links.
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_to_PharmApp3_Results.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>13b</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Retrieve PharmOmics results from network based drug repositioning.</b> Image on the right shows results from the network based drug repositioning option. The results ranks the drugs based on the significance of connectivity between drug signature genes and input genes as defined by a gene network model. Signatures from our comprehensive species- and tissue-specific drug signatures database are used. The drug repositioning results and the genes used for repositioning can be obtained from the download links. We also provide links to visualize the network model of drug genes and their first neighbor input genes (input genes having one edge distance from drug gene).
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_to_PharmApp2_Results.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
			  	</table>
			  	<table>
					<tr>
						<td>
							<p class="instructiontext" id="MetatoPharm" style="font-size: 20px;margin-bottom: 0px; padding: 0px;text-align: left;font-weight: bold;">Run Meta-MSEA to PharmOmics</p>
						</td>
					</tr>
				</table>
				<table>
					<tr class="instruction-table">
					  <td style="padding-top: 30px;">
						<div class='circle'>
						  <div>14</div>
						</div>
					  </td>
					  <td style="padding-top: 30px;">
						 <b>Select input modules and drug repositioning type.</b> Choose to run overlap (around 5 min to run) or network based drug repositioning (around 30 minutes to 2 hours to run). Next, choose to run all modules below a specified significance threshold or select specific modules. For drug network repositioning, you additionally need to select or upload a network and select the species. Also for network drug repositioning, we offer only the option to query meta signatures (studies using different dose and treatment durations are meta-analyzed). You may use your results in our separate PharmOmics pipeline to run the dose/time segregated analysis which will take around 3 hours to complete and will require a login due to heavy use of computational resources. Please refer to steps 11a and 11b to read interpretation of PharmOmics results. 
					  </td>
					  <td style="padding-top: 30px;">
					  	<a href="#">
							<img style="width: 60%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_to_Pharm.png" alt="MSEA parameters">
							<span class="icon-search-plus"></span>
						</a>
					  </td>
					</tr>
				</table>
			</div>
			<div id="myGeneListInfo" class="data-info" style="display:none; padding: 3% 0% 3% 2%;">
				<p style="font-size: 18px;margin-top: 0px;">For a list of genes with no corresponding association values, there are a number of analyses that can be run to derive biological meaning. One can test whether this set or sets of genes are enriched for an GWAS, TWAS, PWAS, or EWAS study. One can also use this list of genes as input for key driver analysis to see whether these genes are enriched in neighbors of key regulatory genes. Click below to see a tutorial of the different options.</p>
				<div class="toggle toggle-border" style="width: 98%;" id="KDAtoggle">
	                <div class="togglet toggleta" id="KDAtogglet"><i class="toggle-closed icon-minus-square"></i><i class="toggle-open icon-plus-square1"></i>
	                  <div class="capital">Option 1 - Key driver analysis</div>
	                </div>
	                <div class="togglec">
		                <table class="instructions" style="margin: 0% 0% 0%; font-size: 15px;">
							<tr class="instruction-table-toggle">
							  <td>
								<div class='circle' style="position: center;">
								  <div>1</div>
								</div>
							  </td>
							  <td>
								<b>Start pipeline.</b> Click on 'Weighted Key Driver Analysis'.
							  </td>
							  <td>
								<a href="#">
									<img style="width: 90%;height: auto;border: 0px solid gray;" class="tut_pic" src="tutorial_imgs/KDAstart.png" alt="KDA button">
									<span class="icon-search-plus"></span>
								</a>
							  </td>
							</tr>
							<tr class="instruction-table-toggle">
							  <td>
								<div class='circle'>
								  <div>2</div>
								</div>
							  </td>
							  <td>
								 <b>Upload OR copy and paste genes/nodes.</b> From the 'Please select option' drop down menu, click on 'Upload Gene Sets' to upload a tab delimited gene sets file with a 'MODULE' 'NODE' header where the 'MODULE' denotes the gene set name and 'NODE' contains the corresponding genes. If you want to test just one gene list, the'MODULE' column can have a single arbitrary name or you can click on the 'Input single list of genes' option to drag and drop a text file or click to copy and paste a list of genes (shown on the picture on the right). In the next step, a gene sets description file can be uploaded.
							  </td>
							  <td>
							  	<a href="#">
							  		<img style="width: 90%;height: auto;" class="tut_pic" src="tutorial_imgs/KDAgenesets.png" alt="KDA button">
							  		<span class="icon-search-plus"></span>
							  	</a>
							  </td>
							</tr>
							<tr class="instruction-table-toggle">
							  <td>
								<div class='circle'>
								  <div>3</div>
								</div>
							  </td>
							  <td>
								 <b>Select/upload network file.</b> The network file describes molecular connections. In a directed network, the source is in the 'HEAD' column and the target is in the 'TAIL' column. The network need not be directed or have weights (the 'WEIGHT' column can have all '1's). We provide sample tissue-specific gene regulatory networks and a protein-protein interaction network.
							  </td>
							  <td>
								  <div class="table-responsive floatbox" style="overflow: visible;width:60%;padding: 0% 0%;margin-left: 3%;">
								  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
									<p style="margin:0;"><b>File format</b></p>
									<table class="samplefile">
									  <thead>
										<tr class="samplefile">
										  <th style="font-size: 16px;">HEAD</th>
										  <th style="font-size: 16px;">TAIL</th>
										  <th style="font-size: 16px;">WEIGHT</th>
										</tr>
									  </thead>
									  <tbody>
										<tr class="samplefile">
										  <td data-column="MARKER(Header): ">A1BG</td>
										  <td data-column="VALUE(Header): ">SNHG6</td>
										  <td data-column="VALUE(Header): ">1</td>
										</tr>
										<tr class="samplefile">
										  <td data-column="MARKER(Header): ">A1BG</td>
										  <td data-column="VALUE(Header): ">UNC84A</td>
										  <td data-column="VALUE(Header): ">1</td>
										</tr>
										<tr class="samplefile">
										  <td data-column="MARKER(Header): ">A1CF</td>
										  <td data-column="VALUE(Header): ">KIAA1958</td>
										  <td data-column="VALUE(Header): ">1</td>
										</tr>
									  </tbody>
									</table>
								  </div>
							  </td>
							</tr>
							<tr class="instruction-table-toggle">
							  <td style="padding-top: 2%;">
								<div class='circle'>
								  <div>4</div>
								</div>
							  </td>
							  <td style="padding-top: 2%;">
								 <b>Select parameters for key driver analysis.</b> The <b>search depth</b> defines the distance (number of connections) away from a potential key driver that will be considered its local network neighborhood. A search depth of 1 is recommended. The <b>edge type</b> can be either 'Undirected' or 'Directed'; the former means that directionality (source and target designation) is not considered, and the latter means it is considered. The <b>min overlap</b> is the threshold above which hubs will be designated as co-hubs. The <b>Edge factor</b> is the degree of influence of a edge weight. 0 is no influence (all weights are equal)1 is full influence, and 0.5 is partial influence.
							  </td>
							  <td style="padding-top: 2%;">
							  	<a href="#">
							  		<img style="width: 90%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_parameters.png" alt="KDA parameters">
							  		<span class="icon-search-plus"></span>
							  	</a>
							  </td>
							</tr>
							<tr class="instruction-table-toggle">
							  <td>
								<div class='circle'>
								  <div>5</div>
								</div>
							  </td>
							  <td>
								 <b>Review KDA files/parameters and submit job.</b> Click 'Click to Review' to see files and parameters chosen. Click 'Run wKDA Pipeline' to submit the job. Depending on the size of the inputs, the analysis can range from 10 minutes to 2 hours.
							  </td>
							  <td>
							  	<a href="#">
									<img style="width: 90%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_review_files.png" alt="MSEA parameters">
									<span class="icon-search-plus"></span>
								</a>
							  </td>
							</tr>
							<tr class="instruction-table-toggle">
							  <td>
								<div class='circle'>
								  <div>6</div>
								</div>
							  </td>
							  <td>
								 <b>Retrieve KDA results.</b> At the conclusion of KDA, key driver results files and cytoscape files are generated. The 'Key Drivers Results' file lists for each key driver its P and FDR values, the module of which it is a key driver for, the total number of neighbors ('N.neigh'), the number of neighbors that are members of the module ('N.obsrv'), and the expected (null) number of neighbors that are members of the module as calculated by permutation. The cytoscape files can be used on Cytoscape Desktop for the user to customize the network visualization. Cytoscape visualization of the top 5 key drivers for each module can be viewed on the browser by clicking on 'Display KDA subnetwork'. The analysis can be concluded at this step or subnetwork genes can be used for drug repositioning in the PharmOmics pipeline by clicking on 'Run PharmOmics pipeline'. In the interactive result table, the 'Merge Module ID' is the module name after merging redundant modules, 'Key Driver Node' is the key driver gene for the module, the 'P-value' is the enrichment p-value of the key driver gene's neighbors for module members, FDR is the false discovery rate for the enrichment value, 'Module Genes' is the total number of nodes in the gene module, 'KD Subnetwork Genes' is the total number of genes in the key driver subnetwork, 'Module and Subnetwork Overlap' is the number of key driver neighbors that are members of the module, and 'Fold Enrichment' is the enrichment of KD subnetwork genes within the gene module. 
							  </td>
							  <td>
								<a href="#">
									<img style="width: 90%;height: auto;" class="tut_pic" src="tutorial_imgs/KDA_results_table.png" alt="MSEA parameters">
									<span class="icon-search-plus"></span>
								</a>
							  </td>
							</tr>
						</table>
	                </div>
    			</div>
    			<div class="toggle toggle-border" style="width: 98%;" id="Enrichtoggle">
	                <div class="togglet toggleta" id="Enrichtogglet"><i class="toggle-closed icon-minus-square"></i><i class="toggle-open icon-plus-square1"></i>
	                  <div class="capital">Option 2 - Test enrichment for disease associations</div>
	                </div>
	                <div class="togglec">
	                	<table class="instructions" style="margin: 0% 0% 0%; font-size: 15px;">
	                		<tr class="instruction-table-toggle">
							  <td>
								<div class='circle' style="position: center;">
								  <div>1</div>
								</div>
							  </td>
							  <td>
								<b>Prepare gene sets file.</b> Arrange your gene set(s) in a two column file with columns 'MODULE' and 'GENE'. If you have just a few gene sets or one gene set to test, it is advised to add them to a larger gene set list. You may download the sample canonical pathways (KEGG, Reactome, and BioCarta) from our <a href="samplefiles.php">resources page</a> and add your geneset. The gene sets file format is shown on the right.
							  </td>
							  <td>
								  <div class="table-responsive" style="overflow: visible;width:40%;padding: 0% 0%;margin-left: 3%;">
								  <!--<div class="table-responsive" style="display: inline;padding: 5% 0%;width:40%;">-->
									<p style="margin:0;"><b>File format</b></p>
									<table class="samplefile">
									  <thead>
										<tr class="samplefile">
										  <th style="font-size: 16px;">MODULE</th>
										  <th style="font-size: 16px;">GENE</th>
										</tr>
									  </thead>
									  <tbody>
										<tr class="samplefile">
										  <td data-column="MARKER(Header): ">GeneSetOfInterest</td>
										  <td data-column="VALUE(Header): ">CDC16</td>
										</tr>
										<tr class="samplefile">
										  <td data-column="MARKER(Header): ">GeneSetOfInterest</td>
										  <td data-column="VALUE(Header): ">EIF2AK2</td>
										</tr>
										<tr class="samplefile">
										  <td data-column="MARKER(Header): ">GeneSetOfInterest</td>
										  <td data-column="VALUE(Header): ">XRCC5</td>
										</tr>
									  </tbody>
									</table>
								  </div>
							  </td>
							</tr>
							<tr class="instruction-table-toggle">
							  <td>
								<div class='circle' style="position: center;">
								  <div>2</div>
								</div>
							  </td>
							  <td>
								<b>Choose pipeline to test gene sets in MSEA.</b> Choose pipeline of interest depending on which disease study (GWAS, EWAS/TWAS/PWAS/MWAS, or multiple studies) you would like to test for association of your gene set. We provide many example GWAS files.
							  </td>
							  <td>
								<a href="#">
									<img style="width: 70%;height: auto;" class="tut_pic" src="tutorial_imgs/PipelineOptions.png" alt="KDA button">
									<span class="icon-search-plus"></span>
								</a>
							  </td>
							</tr>
							<tr class="instruction-table-toggle">
							  <td>
								<div class='circle' style="position: center;">
								  <div>3</div>
								</div>
							  </td>
							  <td>
								<b>Upload gene sets.</b> Upload your custom gene sets in the 'Marker Sets' input and select/upload remaining files and parameters (refer to embedded tutorials in the pipeline or the <a class="navGWAS" href="tutorial.php#GWASMSEAstart">GWAS</a> and <a class="navETPMWAS" href="#">EWAS/TWAS/PWAS/MWAS</a> pipeline tutorials).
							  </td>
							  <td>
								<a href="#">
									<img style="width: 70%;height: auto;" class="tut_pic" src="tutorial_imgs/Upload_GeneSets.png" alt="KDA button">
									<span class="icon-search-plus"></span>
								</a>
							  </td>
							</tr>
							<tr class="instruction-table-toggle">
							  <td>
								<div class='circle' style="position: center;">
								  <div>4</div>
								</div>
							  </td>
							  <td>
								<b>Retrieve MSEA results.</b> In the MSEA results, you can see the degree of enrichment of your gene set(s). P-values, FDRs, and number of genes ('NGENES') from the association data contributed to the enrichment of the module are recorded. In the 'Module Details' file, the genes that contributed to the module's enrichment is recorded.
							  </td>
							  <td>
								<a href="#">
									<img style="width: 70%;height: auto;" class="tut_pic" src="tutorial_imgs/MSEA_Results.png" alt="KDA button">
									<span class="icon-search-plus"></span>
								</a>
							  </td>
							</tr>
						</table>
	                </div>
    			</div>
    			 <div class="toggle toggle-border" style="width: 98%;" id="Pharmtoggle">
	                <div class="togglet toggleta" id="Pharmtogglet"><i class="toggle-closed icon-minus-square"></i><i class="toggle-open icon-plus-square1"></i>
	                  <div class="capital">Option 3 - PharmOmics network or overlap drug repositioning</div>
	                </div>
	                <div class="togglec" style="padding: 1.5% 3% 2%;font-size: 18px;">
	                	We have developed PharmOmics, a drug repositioning tool that utilizes an extensive curation of species- and tissue-specific in vivo and in vitro drug signatures from GEO, ArrayExpress, TG-GATEs, and drugMatrix. We offer network drug repositioning (App 2) and overlap-based drug repositioning (App 3) which also includes L1000 signatures. You may test your gene list for concordance with drug signatures in the <a href="http://mergeomics.research.idre.ucla.edu/runpharmomics.php">PharmOmics pipeline.</a> As with our MSEA pipeline, the tutorial is embedded in the pipeline, but you can also view more detail in the <a href="http://mergeomics.research.idre.ucla.edu/runpharmomics.php">PharmOmics tutorial.</a>
	                </div>
    			</div>
			</div>
		</div>

		<h3 style="margin-top: 2%;">Data input details</h3>
		<p style="margin: 0;font-size: 17px;">All files are recommended to be in UTF-8/ASCII encoded format.</p>
		<ul style="margin-left: 1.5%; list-style-type: disc; font-size: 17px;">
			<li style="margin-bottom: 0;"><strong>Association data (GWAS, EWAS, TWAS, PWAS): </strong>Mergeomics uses as input summary statistics of association studies. For GWAS/EWAS, summary statistics is usually freely available or can be requested. Users should download the entire file and extract the SNP/Epigenetic marker and P value columns, then -log10 transform the P values so that higher values indicate higher association. These two columns should then be labeled 'MARKER' and 'VALUE', respectively. For TWAS/PWAS data, in the 'MARKER' column is the gene/protein and in the 'VALUE' column it can be -log10 P value or fold change (differentially expressed genes and proteins). Importantly, the whole data should be input, not just nominally significant values</li>
			<li><strong>Gene sets ("marker sets"): </strong>These can be from canonical pathways or coexpression modules from WGCNA and MEGENA where the gene set name goes into the 'MODULE' column and the corresponding genes into the 'GENE' column. For example, for WGCNA, the output is multiple files for each module name (a color), each of which contains all the genes in the given module. The user will need to combine these files to form one tab delimited two-column .txt file, where the ‘MODULE’ column contains the WGCNA module names, e.g. Brown, and the ‘GENE’ column contains the constituent genes of each module. In other words, each gene in the ‘GENE’ column has its corresponding module name/color in the adjacent ‘MODULE’ column. If uploading a gene set, an option to upload gene sets descriptions will appear. This input is completely optional and links a description to the module if the user desires.</li>
			<li><strong>Networks: </strong>Networks can include gene regulatory, protein-protein interaction, transcription factor-target, and others. The file will need to contain the following columns: “HEAD”, “TAIL”, and “WEIGHT”. “HEAD” and “TAIL” indicate the pairs of connected genes by network edges in the network and for directed networks they represent source and target, respectively. “WEIGHT” refers to a measure of strength or confidence of the edge and every value can be set to “1” for an unweighted network.</li>
		</ul>

		<h3 style="margin-top: 2%;">Parameters details</h3>
		<h4>Marker dependency filtering (MDF)</h4>
		<ul style="margin-left: 1.5%; list-style-type: disc; font-size: 17px;margin-bottom: 0;">
			<li><u>Linkage disequilibrium (LD) threshold:</u> We recommend either 50% or 70% LD threshold and also recommend the user try at both 50% and 70%.</li>
			<li><u>Top percentage of associations:</u> We recommend 50%, but this can be reduced to 20% for larger studies or increased to 100% for smaller studies. We recommend the user to tune this parameter to see what fits best for their data.</li>
		</ul>
		<h4>Marker set enrichment analysis (MSEA)</h4>
		<ul style="margin-left: 1.5%; list-style-type: disc; font-size: 17px;margin-bottom: 0;">
			<li><u>Permutation type:</u> "Gene" is recommended for GWAS to reduce bias from many markers (SNPs) mapping to the same gene. This is more stringent, however, and the user can still choose to run marker (SNP) based permutation. For EWAS/TWAS/PWAS/MWAS, "Marker" is the only permutation type that can be used as the above scenario does not apply.</li>
			<li><u>Max Overlap for Merging Gene Mapping:</u> This is the overlap ratio threshold for merging genes with shared markers (SNPs). Over this overlap ratio, the genes will be merged. We recommend the default value. This consideration does not apply to EWAS/TWAS/PWAS/MWAS so only 1 can be used which indicates no merging.</li>
			<li><u>Min/Max Genes in Gene Sets:</u> These parameters specify what is the minimum and maximum number of genes to be included in the analysis (will filter out modules with gene number < minimum and > maximum).</li>
			<li><u>Max Overlap for Merging Gene Mapping:</u> This is the overlap ratio threshold for merging genes with shared markers (SNPs). Over this overlap ratio, the genes will be merged. We recommend the default value. This consideration does not apply to EWAS/TWAS/PWAS/MWAS so only 1 can be used which indicates no merging.</li>
			<li><u>Min Module Overlap Allowed for Merging:</u> This is the minimum gene overlap ratio between modules that will have them merged (to merge redundant modules). For instance, for the default value of 0.33, the modules need to have an overlap ratio of 0.33 or greater to be merged. For less merging (more independent modules), a higher overlap threshold can be set (e.g., 0.5) and for more merging (less independent modules), a lower ratio can be set (e.g., 0.2). Set a value of 1 to skip module merging. </li>
			<li><u>Number of permutations:</u> We set the default to 2000 for an exploratory analysis and recommend this value to start with as the run time may be significantly increased with a higher permutation number. We recommend 10,000 for formal analysis.</li>
			<li><u>MSEA to KDA export FDR cutoff:</u> The parameter is for exporting results to KDA. Modules/gene sets with FDR less than this cutoff will be used for KDA. We recommend using 5 (FDR<0.05) for formal analysis but set the default to 25 (FDR<0.25) for exploratory analysis. If no modules pass this threshold, then the top 10 pathways will be used for KDA but please note if this is the case and interpret results accordingly.</li>
		</ul>
		<h4>Key Driver Analysis (KDA)</h4>
		<ul style="margin-left: 1.5%; list-style-type: disc; font-size: 17px;">
			<li><u>Search depth:</u> Determines the search distance used to define the key driver's local network neighborhood (1 refers to 1 connection away). We recommend 1 but can be increased to 2 or 3 for more sparse networks or for smaller input gene lists.</li>
			<li><u>Edge Type:</u> If users would like to consider directionality (key driver gene must be upstream of target genes), choose "Directed", otherwise choose "Undirected".</li>
			<li><u>Min Hub Overlap:</u> The ratio threshold above which hubs will be considered co-hubs. Default value is recommended.</li>
			<li><u>Edge factor:</u> The degree of influence of the weights (in the 'WEIGHT' column). 1 is full influence, 0.5 is partial influence, and 0 is no influence. If an unweighted network is uploaded, users should choose 0.</li>
		</ul>

		<h3 style="margin-top: 2%;">Video Tutorials</h3>
		<h4 style="margin-top: 2%;">Overview</h4>
		<iframe width="630" height="518" src="https://www.youtube.com/embed/u93LcX-6M5M">
		</iframe>
		<h4 style="margin-top: 2%;">File upload</h4>
		<iframe width="630" height="518" src="https://www.youtube.com/embed/d8OQGSJXmEE">
		</iframe>
		<h4 style="margin-top: 2%;">Individual GWAS enrichment</h4>
		<iframe width="630" height="518" src="https://www.youtube.com/embed/ZYJTu43sTa0">
		</iframe>
		<h4 style="margin-top: 2%;">Individual EWAS, TWAS, PWAS, or MWAS enrichment</h4>
		<iframe width="630" height="518" src="https://www.youtube.com/embed/hCDnYWoEkj0">
		</iframe>
		<h4 style="margin-top: 2%;">Weighted Key Driver Analysis</h4>
		<iframe width="630" height="518" src="https://www.youtube.com/embed/SUZyT0BgdLQ">
		</iframe>

		<p class="instructiontext" style="font-size: 20px;margin-bottom: 0px;">To see descriptions on how the pipeline can be used, click on the use cases below.</p>
	<table>
	  <thead>
		<tr>
		  <th>Use case</th>
		  <th>Data</th>
		  <th>Analysis</th>
		</tr>
	  </thead>
	  <tbody>
		<tr>
		  <td style="width: 40em;min-width: 40em;max-width: 40em;">
			<a id="case1" class="case" href="#myCase1">1. What are the genetic mechanisms of autism spectrum disorder (ASD)? What are the key regulators of those gene sets associated with ASD? What drugs can be used to target these disease networks? </a>
		  </td>
		  <td style="width: 12em;min-width: 12em;max-width: 12em;">GWAS</td>
		  <td style="width: 16em;min-width: 16em;max-width: 16em;">MDF <i class="icon-line-arrow-right"></i> MSEA <i class="icon-line-arrow-right"></i> KDA <i class="icon-line-arrow-right"></i> PharmOmics</td>
		</tr>
	  </tbody>
	</table>
	<table>
	  <tr id="myCase1" style="display:none;">
		  <td>
		  	<h4>MDF</h4>
			<p style="font-size: 16px; margin:0;"> GWAS summary statistics can be pulled from a study on ASD (detailing SNPs and their association values, i.e. p-values). The file format for input into MSEA is a two column tab delimited text file with two columns: 'MARKER' and 'VALUE'. SNPs occupy the 'MARKER' value and any measure of association strength can be put into the 'VALUE' column (most commonly used is <b>-log10 transformed</b> p-values but another measure such as effect size can be used). A higher value should indicate stronger association (hence -log10 transformed p-values). A recommended preprocessing step for GWAS is marker dependency filtering (MDF) which corrects for linkage disequilibrium. We offer the 1000 Genomes linkage files and will match the linkage population to the population that was tested in the GWAS (e.g. CEU is central europeans; the population codes can be accessed <a href="https://www.internationalgenome.org/category/population/">here</a>). To enrich SNPs for gene sets, a SNP to gene mapping file is used such as expression quantitative trait loci (eQTLs), and we choose to use brain tissue-specific eQTLs from GTEx. If running MDF, another parameter is to choose the percent top associations. We recommend 50% generally, but can be adjusted to 100% for small studies or 25% for large studies. If skipping MDF, the user will have to adjust the top percentage of associations to include before uploading their association data.</p>
			<h4>MSEA</h4>
			<p style="font-size: 16px; margin:0;">
			After results are produced by MDF, we choose the inputs and parameters for MSEA. "Gene" based permutation is chosen to avoid bias from multiple markers mapping to the same gene (gene set could have high significance from a large amount of markers but those markers could map to just one gene). We may choose "marker" based permutation as an alternate, more lenient analysis. Since this is a an exploratory analysis, 1000 permutations are chosen and for formal analysis, 10000 number of permutations will be set which may take longer. Since we want to focus on the most significant pathways in the KDA, a MSEA to KDA export FDR cutoff of 5 is chosen (5% FDR). We leave the other parameters as the default values as this is recommended. More details of these parameters can be found above in the 'Parameters details' section. Finally, the marker sets (i.e. gene sets) chosen are the WGCNA and MEGENA coexpression modules for brain cortex made from GTEx expression data ('Brain Cortex Coexpression Modules'). In the results, enrichment P and FDR values are recorded for each gene set for genes mapped from SNPs.
			</p>
			<h4>KDA</h4>
			<p style="font-size: 16px; margin:0;">From MSEA, we retrieve P and FDR values for each gene set. Modules from MSEA passing the FDR threshold to export results from MSEA to KDA will be undergo merging to combine potentially highly similar pathways and then be fed into wKDA. We leave most parameters to default values. Because we are using a dense network and have many genes (~500) as input into KDA, we leave the search depth to 1 to simplify the analysis and focus on more direct connections. We do not want to require the key driver to be upstream of the target gene, so we set the edge type to "Undirected". To have the edge weight to have partial influence on the analysis, we set edge weight to 0.5. We choose to run wKDA on a brain gene regulatory network on these significant modules. In the results, we identify key regulator genes of the modules based on network topology. A disease subnetwork is generated from the top 5 key drivers from each module (in the 'Cytoscape Edges' file).</p>
			<h4>PharmOmics</h4>
			<p style="font-size: 16px; margin:0 0 5% 0;">In the direct path from KDA to PharmOmics, the disease subnetwork genes can be used for overlap or network-based drug repositioning which can give gene network regulatory informed therapeutic insight into ASD. In overlap-based drug repositioning, the drugs are ranked based on the significance of the Jaccard score between the subnetwork genes and drug signature genes. In network-based drug repositioning, drugs are ranked based on the connectivity of their signature genes with the wKDA derived subnetworks as defined by a gene network model. Alternatively, if enough significant key drivers are identified, these key drivers can be used for drug repositioning.</p>
		  </td>
	  </tr>
	</table>
	<table>
		<tr>
		  <td style="width: 40em;min-width: 40em;max-width: 40em;">
			<a id="case2" class="case" href="#myCase2">
			2. What functional gene sets are enriched for differentially expressed genes between high sucrose treated mice and untreated mice and do they overlap with type 2 diabetes GWAS?</a>
		  </td>
		  <td style="width: 12em;min-width: 12em;max-width: 12em;">TWAS, GWAS</td>
		  <td style="width: 16em;min-width: 16em;max-width: 16em;">Meta-MSEA</td>
		</tr>
	</table>
	<table>
	  <tr id="myCase2" style="display:none;">
		  <td>
			<p style="font-size: 16px; margin:0 0 5% 0;"> For this aim, TWAS and GWAS data can be run in meta-MSEA. TWAS data can be the differentially expressed genes results obtained from comparing RNA-seq data from an insulin resistance mice model versus that of wild-type mice.  The association file would have genes in the 'MARKER' column and the -log10 transformed p-values or absolute log fold change of expression in the 'VALUE' column (higher values indicate stronger association). Importantly, users should input the full association data, including the values that do not meet nominal significance. User can also trying including just the top 75%, 50%, and 25% of signals. Secondly, the summary statistics from a type 2 diabetes GWAS study and an association file for input into MSEA is prepared with SNPs in the 'MARKER' column and -log10 transformed p-value (or other association measure) in the 'VALUE' column. Since we are testing for enrichment of gene sets, SNPs will need to be mapped to genes whereas TWAS data do not need this further step. Please refer to the tutorial for further detail. In meta-MSEA, a meta enrichment for the pathways is calculated based on the individual GWAS and TWAS module results. With this analysis, we can boost the power to observe consistent pathways reflecting important biology across omics layers. The users should be mindful the individual enrichment values as well as the meta enrichment values. </p>
		  </td>
	  </tr>
	</table>
	<table>
		<tr>
		  <td style="width: 40em;min-width: 40em;max-width: 40em;">
			<a id="case3" class="case" href="#myCase3">3. Which cell type specific differentially expressed genes (DEGs) of beta-amyloid aggregation mouse models are enriched for Alzheimer's disease GWAS? </a>
		  </td>
		  <td style="width: 12em;min-width: 12em;max-width: 12em;">Gene lists, GWAS</td>
		  <td style="width: 16em;min-width: 16em;max-width: 16em;">MSEA</td>
		</tr>
	</table>
	<table>
	  <tr id="myCase3" style="display:none;">
		  <td>
			<p style="font-size: 16px; margin:0 0 5% 0;"> To answer this question, we can obtain cell type specific DEGs by performing single-cell RNA-seq mice with mutations causing rapid beta-amyloid aggregation and wild type mice and comparing the cell type gene expression levels. The DEGs for each cell type will be the gene sets for enrichment in MSEA (cell type in the 'MODULE' column and DEGs in the 'GENE' column). This gene set can be uploaded into the MSEA pipeline with summary statistics from an Alzheimer's disease (AD) GWAS study. Using this analysis, you can see which cell types may be important for AD pathogenesis.</p>
		  </td>
	  </tr>
	</table>
	<table>
		<tr>
		  <td style="width: 40em;min-width: 40em;max-width: 40em;">
			<a id="case4" class="case" href="#myCase4">
			4. What genes are key regulators for protein folding genes in a brain gene regulatory network?</a>
		  </td>
		  <td style="width: 12em;min-width: 12em;max-width: 12em;">Single gene list</td>
		  <td style="width: 16em;min-width: 16em;max-width: 16em;">KDA</td>
		</tr>
	</table>
	<table>
	  <tr id="myCase4" style="display:none;">
		  <td>
			<p style="font-size: 16px; margin:0;"> A curated list of protein folding related genes can be used as input to KDA. The geneset list may have multiple sets designated in the 'MODULE' with corresponding genes in the 'NODE' column or just one set (the same value in the MODULE column). Or the genes can be directly input into a text field upon choosing the 'Input single list of genes' option. Key drivers found may be important regulators of protein folding.</p>
		  </td>
	  </tr>
	</table>

	</div>
</div>


</body>

</html>

<link href="include/select2.css" rel="stylesheet" />
<script src="include/js/plugins.js"></script>
<script src="include/js/bs-filestyle.js"></script>
<script src="include/js/functions.js"></script>
<script type="text/javascript">

/*$( function() { 
  $( "#Tut_tabs" ).tabs();
} );*/

$('#sidebarCollapse').on('click', function() {

  if ($("#sidebar.active")[0]) {
    $('#sidebar').toggleClass('active');
    $('.container').toggleClass('no_sidebar');
    $('.margin_rm').toggleClass('no_margin');
  } else {
    $('#sidebar').toggleClass('active');
    $('.container').toggleClass('no_sidebar');
    $('.margin_rm').toggleClass('no_margin');
  }

});




$(".navGWAS").on('click', function(e){
  var x = document.getElementById("myGWASinfo");
  if (x.style.display === "none") {
	x.style.display = "block";
	$("#GWASinfo").addClass('runm_active');
	$("#TPEMWASinfo").removeClass('runm_active');
	$("#METAinfo").removeClass('runm_active');
	$("#GeneListInfo").removeClass('runm_active');
  }
  $("#myGWASinfo").siblings().hide();

  //e.preventDefault();
});

$(".navETPMWAS").on('click', function(e){
  var x = document.getElementById("myTPEMWASinfo");
  if (x.style.display === "none") {
	x.style.display = "block";
	$("#GWASinfo").removeClass('runm_active');
	$("#TPEMWASinfo").addClass('runm_active');
	$("#METAinfo").removeClass('runm_active');
	$("#GeneListInfo").removeClass('runm_active');
  }
  $("#myTPEMWASinfo").siblings().hide();

  //e.preventDefault();
});

$(".navMeta").on('click', function(e){
  var x = document.getElementById("myMETAinfo");
  if (x.style.display === "none") {
	x.style.display = "block";
	$("#GWASinfo").removeClass('runm_active');
	$("#TPEMWASinfo").removeClass('runm_active');
	$("#METAinfo").addClass('runm_active');
	$("#GeneListInfo").removeClass('runm_active');
  }
  $("#myMETAinfo").siblings().hide();

  //e.preventDefault();
});

$(".navGeneList").on('click', function(e){
  var x = document.getElementById("myGeneListInfo");
  if (x.style.display === "none") {
	x.style.display = "block";
	$("#GWASinfo").removeClass('runm_active');
	$("#TPEMWASinfo").removeClass('runm_active');
	$("#METAinfo").removeClass('runm_active');
	$("#GeneListInfo").addClass('runm_active');
  }
  $("#myGeneListInfo").siblings().hide();

  //e.preventDefault();
});

$('.sub1').click(function() {
	text1 = $(this).text();
	//console.log(text1);
  $(this).next('ul').toggle();

});

$(".navig").on('click', function(){
  var href = $(this).attr('href');

	var val = $(href).offset().top - $(window).scrollTop() - 65;
	if (val<=0 || ($(window).scrollTop()!=0 && $(window).scrollTop() < $(href).offset().top)){ 
	// below item or scrolled down but not below item
	var val = $(href).offset().top - 65;
	} 

  $(window).scrollTop(
    val
  );

  return false;
});

$(".genelist").on('click', function(){
	var href = $(this).attr('href');
	console.log(href);
	if ($(href).children('.togglec').css('display') == 'none') {
	  $(href).children(0).click();
	}
	var val = $(href).offset().top - $(window).scrollTop() - 65;
	if (val<=0 || ($(window).scrollTop()!=0 && $(window).scrollTop() < $(href).offset().top)){ 
	// below item or scrolled down but not below item
	var val = $(href).offset().top - 65;
	} 

	$(window).scrollTop(
	//$(href).offset().top - $(window).offset().top + $(window).scrollTop() - 60
	//$(href).offset().top - $(window).scrollTop() - 60
	val
	);

	return false;
});

$("#runKDA").on('click',function(){
	$("#KDAtoggle").click();
});

$("#testEnrichment").on('click',function(){
	$("#Enrichtoggle").click();
});

$("#runPharmOmics").on('click',function(){
	$("#Pharmtoggle").click();
});

$(".button-inner").on('click',function(e){
  var id = $(this).attr('href');
  var x = document.getElementById(id.replace("#",""));
  if (x.style.display === "none") {
	x.style.display = "block";
  } else {
	x.style.display = "none";
  }
  $(id).siblings().hide();
	e.preventDefault();
})

$(".button-wrapper").click(function(e) {
  var $this = $(this).find('.button-inner');

  var name_type = $this.closest(".col-lg-3.center.bottommargin").attr('name');

  if ($this.hasClass("runm_active"))
  //Keep track if button is clicked-------------------------------------------------------------->
  {
	$this.data('clicked', true);
  } else {
	$this.data('clicked', false);
  }
  $('.runm.button-inner').removeClass('runm_active');
  //$('.button.button-rounded.button-reveal.button-large.button-teal').hide();


  if ($this.data('clicked')) //if it's already been clicked, then do this
  {
	//$this.parent().nextAll('.button.button-rounded.button-reveal.button-large.button-teal').eq(0).hide();
	$this.removeClass('runm_active');
	//$("#flowchart").attr("src", img0.src);
	$this.data('clicked', false);

  } else //if it hasn't been clicked, then do this
  {
	//$this.parent().nextAll('.button.button-rounded.button-reveal.button-large.button-teal').eq(0).show();
	$this.addClass('runm_active');
	/*if (name_type == "GWAS")
	  $("#flowchart").attr("src", gwas0.src);
	else if (name_type == "MSEA")
	  $("#flowchart").attr("src", msea0.src);
	else if (name_type == "META")
	  $("#flowchart").attr("src", meta0.src);
	else
	  $("#flowchart").attr("src", kda0.src);*/

	$this.data('clicked', true);

  }

  e.preventDefault();

});

$(".tut_pic").click(function(e) {
	e.preventDefault();
	var a = $(this).parent().parent().attr('name');
    var path = $(this).prop('src');

    $(this).magnificPopup({
      items: {
        src: path
      },
      type: 'image',
      closeOnContentClick: true,
      closeBtnInside: true,
      mainClass: 'mfp-no-margins',
      image: {
        markup: '<div class="mfp-figure">'+
        '<div class="mfp-close"></div>'+
        '<div class="mfp-img"></div>'+
        '<div class="mfp-bottom-bar" style="text-align:center;">'+
        '<div class="mfp-title">'+a+'</div>'+
        '</div>'+
        '</div>',
         verticalFit: true
        }
                
      }).magnificPopup('open'); 
});


$("#case1").on('click', function(){
	var x = document.getElementById("myCase1");
  if (x.style.display === "none") {
	x.style.display = "block";
  } else {
	x.style.display = "none";
  }

});

$("#case2").on('click', function(){
	var x = document.getElementById("myCase2");
  if (x.style.display === "none") {
	x.style.display = "block";
  } else {
	x.style.display = "none";
  }

});

$("#case3").on('click', function(){
	var x = document.getElementById("myCase3");
  if (x.style.display === "none") {
	x.style.display = "block";
  } else {
	x.style.display = "none";
  }

});

$("#case4").on('click', function(){
	var x = document.getElementById("myCase4");
  if (x.style.display === "none") {
	x.style.display = "block";
  } else {
	x.style.display = "none";
  }

});

$(document).scroll(function() {
  if ($(window).scrollTop() > 100 && $(window).width() < 992) {

	$("#sidebar").css("margin-top", "-206px");

  } else if ($(window).scrollTop() < 100 && $(window).width() < 992) {

	$("#sidebar").css("margin-top", "-106px");

  }
});


</script>






