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
    margin-left: 2%;
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

#pictext{
    width: 400px;
    background: yellow;
}
#floated{
    float: left;
    width: 150px;
    background: red;
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
	<h2>PharmOmics Tutorial</h2>

  </div>
</div>

</section>

<nav id="sidebar" style="margin-top: -88px;max-width: 260px !important;">
	        <div class="custom-menu">
          <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i id="sidebar_icon" class="icon-bars"></i>
            <span class="sr-only">Toggle Menu</span>
          </button>
        </div>

<h1><a href="#" class="session" style="padding: 28px 20px;">Quick navigation</a></h1>

<ul class="list-unstyled components nav1">
  <li class="active">
  	<a href="#App1" class="navToApp">DEG and pathway review/Species and tissue comparison</a>
  </li>
  <li class="active">
	<a href="#App2" class="navToApp">Network drug repositioning</a>
  </li>
  <li class="active">
	<a href="#App3" class="navToApp">Overlap drug repositioning</a> 
  </li>
</ul>
  <p style="padding-top:0px;">
  	PharmOmics is being actively developed by the Yang Lab in the Department of Integrative Biology and Physiology at UCLA. 
  </p>

</nav>

<div class="margin_rm" style="margin-bottom: 200px;">
	<div class="container clearfix" id="myContainer" style="margin-bottom: 40px;">
		 <!--
		<div id="pictext" style="font-size: 20px;">
			<div id="floated"><img style="width: 80%;height: auto;border: 1px solid gray;" class="tut_pic" src="tutorial_imgs/PharmOmics_Webserver.png" alt="PharmOmics overview"></div> 
			PharmOmics is a comprehensive drug knowledgebase and analytical tool comprised of genomic footprints of drugs in individual tissues from multiple species (human, mouse, and rat) based on global transcriptome data from public repositories (GEO, ArrayExpress, TG-GATEs, drugMatrix). The PharmOmics web server features three functions: i) species- and tissue-stratified drug DEGs and pathway signatures query, ii) network-based drug repositioning, and iii) overlap-based drug repositioning.
		</div>-->
		<div style="margin-top: 4%;font-size: 20px;margin: 3% 5% 0%;">
				PharmOmics is a comprehensive drug knowledgebase and analytical tool comprised of genomic footprints of drugs in individual tissues from multiple species (human, mouse, and rat) based on global transcriptome data from public repositories (GEO, ArrayExpress, TG-GATEs, drugMatrix). The PharmOmics web server features three functions: i) species- and tissue-stratified drug DEGs and pathway signatures query, ii) network-based drug repositioning, and iii) overlap-based drug repositioning.
		</div>
		<img style="width: 90%;height: auto;margin: 1% 3% 0% 5%;" class="tut_pic" src="tutorial_imgs/PharmOmics_Webserver.png" alt="PharmOmics overview">
		<p class="instructiontext" style="font-size: 20px;margin-bottom: 0px;">Click below to show tutorials for the different applications.</p>
		<div class="toggle toggle-border" style="width: 98%;" id="App1">
	        <div class="togglet toggleta" id="app1togglet"><i class="toggle-closed icon-minus-square"></i><i class="toggle-open icon-plus-square1"></i>
	          <div class="capital">Application 1 - Drug DEG and Pathway query and species/tissue comparison</div>
	        </div>
	        <div class="togglec" id="app1tut">
		        <p style="font-size: 20px;margin: 2% 1% 1% 1%;padding: 0px;text-align: left;">
		        	App1 displays for each drug the studies curated, differentially expressed genes (DEGs) and pathway regulation preview (top 50 DEGs and top 20 pathways) for meta-analyzed and dose/time segregated signatures and features a between-species/between-tissues DEGs/pathways comparison tool. 
		        	<br>
		        	<br>
		        	First, choose a drug under 'Select drug class or drug name of interest'. DEG and pathway information for that drug will appear at the bottom in the 'Gene Regulation' (shown by default) and 'Pathway Regulation' tabs. The species and tissues for which there is drug data available will also appear, and the user must click on the species and tissues to see studies curated. Full meta-analyzed and dose segregated species- and tissue-specific DEG and pathway signatures for the drug can be downloaded at the bottom by clicking on 'Download Drug Gene Signatures'. 
		        </p>
		        <img style="width: 98%;height: auto;margin-left: 1%; border:0;" class="tut_pic" src="tutorial_imgs/App1Form.png" alt="App1 Form">
		        <img style="width: 100%;height: auto;margin: 1% 0 0; border:0;" class="tut_pic" src="tutorial_imgs/App1DownloadDrugData.png" alt="App1 download drug data">
		        <p style="font-size: 20px;margin: 1% 1% 1% 1%;padding: 0px;text-align: left;">
		        	If multiple tissues and a single species are selected or multiple species and a single tissue are selected, species/tissue DEGs/pathways comparison can be run by clicking on 'Run DEGs Comparison' and 'Run Pathways Comparison'. Results will appear in the Species/Tissue Comparison tab. If there is overlap, a plot showing the counts of species-/tissue-specific DEGs/pathways and overlaps is generated in the 'Degree of DEG/Pathway Overlap' tab which can be downloaded by clicking on the plot. Regardless of whether there are overlaps, the 'DEG/Pathway Overlap Summary' shows the genes/pathways that overlapped (if any) and those specific to the species/tissue. This summary can be downloaded using the 'Download Results' button. Currently, we use meta-analyzed and limma combined data for species/tissue comparison. 
		        </p>
		        <img style="width: 100%;height: auto;margin-left: 0;" class="tut_pic" src="tutorial_imgs/App1ComparisonUpset.png" alt="App1 Comparison Plot">
		        <img style="width: 100%;height: auto;margin: 1% 0 0;" class="tut_pic" src="tutorial_imgs/App1ComparisonSummary.png" alt="App1 download drug data">
	        </div>
		</div>
		<div class="toggle toggle-border" style="width: 98%;" id="App2">
	        <div class="togglet toggleta" id="app2togglet"><i class="toggle-closed icon-minus-square"></i><i class="toggle-open icon-plus-square1"></i>
	          <div class="capital">Application 2 - Network drug repositioning</div>
	        </div>
	        <div class="togglec" id="app2tut">
	        	<p style="font-size: 20px;margin: 2% 1% 1% 1%;padding: 0px;text-align: left;">
	        	1. <b>Select signature type to query.</b> The PharmOmics database offers species- and tissue-specific signatures that are meta-analyzed across dose and time regimens as well as dose and time segregated signatures. Drug repositioning with meta signatures will take around 25 minutes-2 hours and is species matched (1251 human signatures and 1696 mouse/rat signatures). For dose/time segregated signatures (158 human signatures and 11,542 rat signatures), all species-specific signatures will be used for repositioning (genes will be converted to their human/rat orthologs), and the species and tissue information is retained in the results. This large dataset necessitates more computational resources; to avoid overwelming our server, we require a login for this analysis and limit the user to 5 jobs a day (login prompt will appear if a dose/time segregated signature type is selected). Running with the complete dose/time segregated signatures will take around 10 hours. The 'top 500 genes' option means that only the top 500 genes by significance per signature are considered for repositioning, and this greatly reduces computing time to around 3 hours. 
	        	</p>
	        	<p style="font-size: 20px;margin: 1% 1% 1% 1%;padding: 0px;text-align: left;">
	        	2. <b>Select or upload network.</b> We offer sample liver, kidney, and multi-tissue gene regulatory networks. The user may upload their own network which must be a tab delimited .txt file with columns 'HEAD' and 'TAIL'. The file format will appear if the 'Upload network' option is selected. The maximum number of nodes allowed in the network is 35000. For uploading networks with a large number of unique nodes, more computing time will be needed. We are working to update and add new sample networks.
	        	</p>
	        	<p style="font-size: 20px;margin: 1% 1% 1% 1%;padding: 0px;text-align: left;">
	        	3. <b>Select species.</b> The species selected should match those of the input genes.
	        	</p>
	        	<p style="font-size: 20px;margin: 1% 1% 1% 1%;padding: 0px;text-align: left;">
	        	4. <b>Input genes.</b> Copy and paste or manually enter a gene list into the text field or drag and drop a .txt file with a single column of genes. 
	        	</p>
	        	<p style="font-size: 20px;margin: 1% 1% 1% 1%;padding: 0px;text-align: left;">
	        	5. <b>(Optional) Submit email for results to be sent to you.</b> Enter email in the text field and click 'Send email' to receive results to your email. If you do not receive results, the email may have been sent to spam. Alternatively, enter your session ID in the PharmOmics home session ID prompt, and the results should load if finished.
	        	</p>
	        	<p style="font-size: 20px;margin: 1% 1% 1% 1%;padding: 0px;text-align: left;">
	        	6. <b>Run drug repositioning.</b> Click 'Submit job' to run the analysis. A loading page will then appear. The results will automatically load once the analysis is complete. You may need to refresh the page if the time elapsed is over the estimated running time (around 25-2 hours minutes for meta signatures, 3 hours for dose/time segregated top 500 genes signatures, and 12 hours for dose/time segregated complete signatures). For meta signatures, if the job is running successfully, the progress bar will show the percentage of signatures that have been analyzed (wait a few minutes to start seeing progress). If a network was uploaded, additional time is required to build the distance matrix.
	        	</p>
	        	<img style="width: 100%;height: auto;margin-left: 0;" class="tut_pic" src="tutorial_imgs/App2_inputs.png" alt="App2 inputs">
	        	<p style="font-size: 20px;margin: 1% 1% 1% 1%;padding: 0px;text-align: left;">
	        	7. <b>Retrieve results.</b> The z-score, z-score rank, and p-values are recorded for all species- and tissue-specific signatures. In the downloadable results file, overlap between network drug genes and network input genes is in the 'Drug_gene_input_gene_overlap_in_network' column, drug genes whose first network neighbors are input genes are in the 'Drug_genes_directly_connected_to_input_gene' column, and input genes whose first network neighbors are drug genes are in the 'Input_genes_directly_connected_to_drug_gene' column. In the 'Visualization Link' column of the interactive results table, the drug gene overlap with the network and first neighbor connections to disease genes can be visualized by clicking the 'Display network' button. 
	        	</p>
	        	<p style="font-size: 24px;margin: 1% 1% 0% 0%;padding: 0px;text-align: center;">
	        	<b>Results example querying meta signatures</b>
	        	<img style="width: 100%;height: auto;margin-left: 0;border: 0;" class="tut_pic" src="tutorial_imgs/App2_Meta_Results.png" alt="App2 Meta Results">
	        	</p>
	        	<p style="font-size: 24px;margin: 1% 1% 0% 0%;padding: 0px;text-align: center;">
	        	<b>Results example querying dose segregated signatures</b>
	        	</p>
	        	<img style="width: 100%;height: auto;margin-left: 0;border: 0;" class="tut_pic" src="tutorial_imgs/App2_DoseSeg_Results.png" alt="App2 Dose Seg Results">
	        	<p style="font-size: 24px;margin: 1% 1% 0% 0%;padding: 0px;text-align: center;">
	        	<b>Additional network drug gene information columns in downloadable result file</b>
	        	</p>
	        	<img style="width: 100%;height: auto;margin-left: 0;border: 0;" class="tut_pic" src="tutorial_imgs/App2AdditionalInfo.png" alt="App2 additional info">
	        	<p style="font-size: 24px;margin: 1% 1% 0% 0%;padding: 0px;text-align: center;">
	        	<b>Drug Network Visualization</b>
	        	</p>
	        	<img style="width: 100%;height: auto;margin-left: 0;" class="tut_pic" src="tutorial_imgs/App2_drug_network.png" alt="Drug network">
	        </div>
		</div>
		<div class="toggle toggle-border" style="width: 98%;" id="App3">
	        <div class="togglet toggleta" id="app3togglet"><i class="toggle-closed icon-minus-square"></i><i class="toggle-open icon-plus-square1"></i>
	          <div class="capital">Application 3 - Overlap drug repositioning</div>
	        </div>
	        <div class="togglec" id="app3tut">
	        	<p style="font-size: 20px;margin: 2% 1% 1% 1%;padding: 0px;text-align: left;">
				1. <b>Input genes.</b> Either copy and paste genes into fields or drag and drop a text file. For a single gene list, input genes onto the upregulated genes field on the left. To run the drug repositioning analysis, click 'Submit job'. The analysis will take around 5 minutes to complete. Optionally, enter email and click 'Send email' to receive results via email. If you do not receive results, the email may have been sent to spam. Alternatively, enter your session ID in the PharmOmics home session ID prompt and the results should load if finished.
				</p>
				<img style="width: 95%;height: auto;" class="tut_pic" src="tutorial_imgs/App3_inputs.png" alt="App3 inputs">
				<p style="font-size: 20px;margin: 2% 1% 1% 1%;padding: 0px;text-align: left;">
				2. <b>Retrieve results.</b> A download link for the results will appear as well as an interactive display of the results. In the downloadable results file, additional information includes the gene overlap between drug genes and input genes.
				</p>
				<img style="width: 98%;height: auto;border:0;" class="tut_pic" src="tutorial_imgs/App3_result.png" alt="App3 results">
				<p style="font-size: 20px;margin: 1% 1% 0% 0%;padding: 0px;text-align: center;">
	        	<b>Additional gene overlap information columns in downloadable result file</b>
	        	</p>
	        	<img style="width: 98%;height: auto;border:0;" class="tut_pic" src="tutorial_imgs/App3AdditionalInfo.png" alt="App3 more info">
	        </div>
		</div>

		<h3 style="margin-top: 2%;margin-bottom: 2%;">Video Tutorial</h3>
		<iframe width="630" height="518" src="https://www.youtube.com/embed/hswvkPIib_c">
		</iframe>
	</div>
</div>


</body>

</html>

<link href="include/select2.css" rel="stylesheet" />
<script src="include/js/plugins.js"></script>
<script src="include/js/bs-filestyle.js"></script>
<script src="include/js/functions.js"></script>
<script type="text/javascript">

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

$(".navToApp").on('click', function(){
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

/*
$(".navApp1").on('click', function(e){

  $("#app1togglet").click();

});

$(".navApp2").on('click', function(e){
	
  $("#app2togglet").click();

});

$(".navApp3").on('click', function(e){
	
  $("#app3togglet").click();

});
*/

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

$(document).scroll(function() {
  if ($(window).scrollTop() > 100 && $(window).width() < 992) {

	$("#sidebar").css("margin-top", "-206px");

  } else if ($(window).scrollTop() < 100 && $(window).width() < 992) {

	$("#sidebar").css("margin-top", "-106px");

  }
});


</script>






