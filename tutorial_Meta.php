<!DOCTYPE html>
<?php include_once("analyticstracking.php") ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mergeomics | A Web server for Identifying Pathological Pathways, Networks, and Key Regulators via Multidimensional Data Integration</title>
<link rel="stylesheet" type="text/css" href="backup_originalwebserver/style.css" />
<link rel="stylesheet" href="backup_originalwebserver/table_style.css">
<script type="text/javascript" src="backup_originalwebserver/js/jquery-1.3.2.min.js" ></script>
<script type="text/javascript" src="backup_originalwebserver/js/jquery-ui.min.js" ></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 5000, true);
});
</script>
<!-- Cufon START  -->
<script type="text/javascript" src="backup_originalwebserver/js/cufon-yui.js"></script>
<script src="backup_originalwebserver/js/GeosansLight_500.font.js" type="text/javascript"></script>
<script type="text/javascript">
Cufon.replace('.logo', { fontFamily: 'GeosansLight' });
Cufon.replace('h1', { fontFamily: 'GeosansLight' });
Cufon.replace('h2', { fontFamily: 'GeosansLight' });
</script>    
<!-- Cufon END  -->
</head>
<body>
<div id="main_container">

        
  <div class="header">

            <div class="logo"><a href="home.php">Mergeomics</a></div>
            <div class="slogan">| A Web Server for Multidimensional Data Integration</div> 
        
      <div class="header_socials">
            <a>Links:</a>
            <a target="_blank" title="join our google group for support" href="https://groups.google.com/forum/#!forum/mergeomics-support"><img src="images/googlegroups.png" alt="" title="" border="0" /></a>
            <a target="_blank" title="download on bioconductor" href="https://www.bioconductor.org/packages/3.3/bioc/html/Mergeomics.html" ><img src="images/bioconductor.jpg" alt="" title="" border="0" /></a>
            <a target="_blank" title="our lab website" href="https://yanglab.ibp.ucla.edu/" ><img src="images/ucla.jpg" alt="" title="" border="0" /></a>
            </div>

    </div> <!--end of header--> 

<div class="menu">
    <ul>
        <li><a href="http://mergeomics.research.idre.ucla.edu/home.php">Home</a></li>
        <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial.php">Tutorial<!--[if IE 7]><!--></a><!--<![endif]-->
        <ul>
            <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial.php#" title="">Overview</a></li>
            <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial_MDF.php" title="">MDF</a></li>
            <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial_MSEA.php" title="">MSEA</a></li>
            <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial_Meta.php" title="">Meta MSEA</a></li>
            <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial_KDA.php" title="">wKDA</a></li>
            <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial_vis.php" title="">Visualization</a></li>
            <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial_data.php" title="">Additional Data Types</a></li>
            <li><a href="http://mergeomics.research.idre.ucla.edu/tutorial_PharmOmics.php" title="">PharmOmics</a></li>
        </ul>
        </li>
        <li><a href="http://mergeomics.research.idre.ucla.edu/Download/">Downloads</a></li>
    </ul>
</div>


<!-- end of header -->

<div class="center_content_pages">
  <div class="pages_banner">
    <a name="Tutorial 1"></a>Meta Marker Set Enrichment Analysis (Meta MSEA)
  </div>

<div class="left_content">                  
    <div class="box290">
    <h2>Quick Links</h2>
        <ul class="left_menu">
            <li><a href="tutorial.php#LDPrune" title="">Overview</a></li>
            <li><a href="tutorial_MDF.php#LDPrune" title="">MDF</a></li>
              <ul style="padding-left:15px">
                <li><a href="tutorial_MDF.php#MDF_web"" title="">Webserver</a></li>
                <li><a href="tutorial_MDF.php#MDF_local"" title="">Local</a></li>
              </ul>
            <li><a href="tutorial_MSEA.php#Tutorial 1" title="">MSEA</a>
              <ul style="padding-left:15px">
                <li><a href="tutorial_MSEA.php#GWAS">Association</a></li>
                <li><a href="tutorial_MSEA.php#Locus">Mapping</a></li>
                <li><a href="tutorial_MSEA.php#SSEAParameters">Parameters</a></li>
                <li><a href="tutorial_MSEA.php#Gene_Sets">Gene Sets</a></li>
                <li><a href="tutorial_MSEA.php#Gene_Sets_Desc">Description</a></li>
                <li><a href="tutorial_MSEA.php#Email/Run">Email/Submit</a></li>
                <li><a href="tutorial_MSEA.php#SSEA Results">Results</a></li>
              </ul>
            </li>
            <li><a href="tutorial_Meta.php">Meta MSEA</a></li>
            <li><a href="tutorial_data.php#AdditionalData">Additional Data Types</a>
              <ul style="padding-left:15px">
                <li><a href="tutorial_data.php#MouseGWAS">Mouse GWAS</a></li>
                <li><a href="tutorial_data.php#HumanEWAS">Human EWAS</a></li>
              </ul>
            </li>
            <li><a href="tutorial_KDA.php#SSEA2KDA">MSEA to wKDA</a>
              <ul style="padding-left:15px">
                <li><a href="tutorial_KDA.php#Step14">Parameters</a></li>
                <li><a href="tutorial_KDA.php#Network">Network</a></li>
                <li><a href="tutorial_KDA.php#KDA email">Email/Submit</a></li>
                <li><a href="tutorial_KDA.php#KDA Results">Results</a></li>
              </ul>
            <li><a href="tutorial_KDA.php#Tutorial 2">wKDA</a>
              <ul style="padding-left:15px">
                <li><a href="tutorial_KDA.php#KDA_Gene_Set">Gene Sets</a></li>
                <li><a href="tutorial_KDA.php#KDA parameters">Parameters</a></li>
                <li><a href="tutorial_KDA.php#KDA Results">Results</a></li>
              </ul>
            </li>
            <li><a href="tutorial_vis.php#Visualization">Visualization</a></li>
            <li><a href="tutorial_PharmOmics.php#PharmOmics">PharmOmics</a></li>
            <ul style="padding-left:15px">
                <li><a href="tutorial_PharmOmics.php#Application1">Application1</a></li>
                <li><a href="tutorial_PharmOmics.php#Application2">Application2</a></li>
            </ul>
        </ul>
    </div>
  </div> <!--end of left content-->

  <div class="right_content">

        <h1>Overview</h1>
        <div class="boxed">
        <p>
        <ol>
            <li><h2>Meta MSEA</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li>
                      <b>Meta MSEA</b> is a feature that supports meta-analysis at the pathway level. Meta-SSEA gives the user more power by combining results from independent association studies of different ethnicity,  platform, or even species. Additionally, the Meta-MSEA can be used to encorporate multiple types of data (i.e. GWAS and EWAS) as long as the appropriate <a href="tutorial_MSEA.php#Locus">mapping files</a> are supplied.</b>
                    </li>
                </ol>
              <li><h2>Workflow</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li>
                      The <b>Workflow of Meta-MSEA</b> is identical to <a href="tutorial_MSEA.php#Tutorial 1">MSEA</a> except for the addition of one step.  Meta-MSEA begins by following the same steps as MSEA steps 1-5 (from <a href="tutorial_MSEA.php#GWAS">association data</a> to <a href="tutorial_Meta.php#Gene_Sets_Desc">gene set description</a>).
                      <br>
                      <b>Note:</b> Please correct for any marker dependencies (if known) using <a href="tutorial_MDF.php#LDPrune">Marker Dependency Filtering</a>.
                    </li>
                </ol>
              <li><h2>Studies to Include</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li>
                      Once all the parameters have been selected and necessary files have been uploaded/selected, the user must then select whether they would like to <b>add an additional study</b> to the meta-analysis, or proceed with the analysis.                      
                    </li>
                </ol>
          </ol>

          <b>IMPORTANT:</b> There is a maximum limit of <b>5 separate studies</b> to be integrated in a single Meta-MSEA run. If the user wishes to conduct Meta-MSEA with more samples, then they can use the Mergeomics R script provided in the <a href="http://mergeomics.research.idre.ucla.edu/Download/Package/">Downloads</a> section.

        </p>        
        </div>





</div> <!-- end of right content -->

<div class="footer">
    <div class="footer_right">
    <a href="https://yanglab.ibp.ucla.edu/">Yang Lab - UCLA</a>
    </div>
    <div class="clear"></div>
</div>

</body>
</html>