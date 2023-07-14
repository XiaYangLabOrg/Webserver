<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<?php include_once("analyticstracking.php") ?>
 
 <!-- Includes all the font/styling/js sheets -->
<?php include_once("head.inc") ?>

<style>

ol.samplef {
	font-size: 20px;
	padding-left: 2%;
}


</style>

<body class="stretched">

<?php include_once("headersecondary_tut.inc") ?>

<?php

$bashscript = "./Download/MDPrune/preprocess.bash";

?>


<!-- Page title block ---------------------------------------------------------------------------------->
 <section id="page-title">
     <div class="margin_rm" style="margin-left: 0;">
		<div class="container clearfix" style="text-align: center;">
			<h2>Tools</h2>
		</div>
    </div>
</section>

<section id="content" style="margin-bottom: 0px;">
	<div class="content-wrap">
		<div class="margin_rm">
			<div class="container clearfix" style="padding: 0% 15% 0% 0%;">
				<a href="https://bioconductor.org/packages/release/bioc/html/Mergeomics.html" style="font-size: 20px;">Mergeomics R package</a>
				<p class="instructiontext" style="padding-left: 0; margin-bottom: 0;text-align: left;font-size: 20px;">Run Marker Dependency Filtering (MDF) locally</p>
				<ol class="samplef" style="margin-bottom: 0;">
					<li>
						Download the <a href="http://mergeomics.research.idre.ucla.edu/Download/MDPrune/mdprune">MDF script</a> and corresponding <a href=<?php print($bashscript); ?> download>bash file</a>. Only the file names in the bash script need to be modified. The script must be run on a Linux distribution. Sample association, mapping, and marker dependency files can be downloaded from the <a href="samplefiles.php">sample files page</a>.
					</li>
					<li>
						Change the path of the <b>MARFILE="../resources/gwas/CAD2.new.txt"</b> to the path of the association (e.g. GWAS) file, a two column ('MARKER', 'VALUE') tab delimited file with biological markers (e.g. SNPs) in the 'MARKER' column and association strength (e.g. -log10 transformed p-values) in the 'VALUE' column.
					</li>
					<li>
						Change the path of the <b>GENFILE="../resources/mapping/gene2loci.020kb.txt"</b> to the pathway to the mapping file, a two column ('GENE', 'MARKER') tab delimited file with biological markers (e.g. SNPs) in the 'MARKER' column and corresponding genes in the 'GENE' column.
					</li>
					<li>
						Change the path of the <b>MDSFILE="../resources/linkage/ld70.ceu.txt"</b> to the pathway to the dependency file, a three column ('MARKERa', 'MARKERb', WEIGHT) tab delimited file that defines the dependency structure (e.g. linkage disequilibrium) between markers.
					</li>
	            	<li>
			            Optionally, the output path for the dependency corrected association and mapping files can be specified, <b>OUTPATH="output/"</b>, and the percentage of top associated markers can be limited to speed computation, <b>NTOP=0.5</b>. The output, dependency corrected association and mapping files, can then be used in the MSEA pipeline. 
	            	</li>
				</ol>
				<br>
				<img src="Tutorial1_files/image065.jpg" border=1 style="border-color: grey;" ></li>
			</div>
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