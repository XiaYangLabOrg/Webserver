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
        <li><a href="http://mergeomics.research.idre.ucla.edu/contact.html">Contact</a></li>
        <li><a href="http://mergeomics.research.idre.ucla.edu/Download/">Downloads</a></li>
    </ul>
</div>


<!-- end of header -->

<div class="center_content_pages">
  <div class="pages_banner">
    Additional Data Types: Utilizing Diverse Association Types in MSEA
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
          As long as the user supplies the necessary <a href="tutorial_MSEA.php#GWAS">association</a> and marker-to-gene <a href="tutorial_MSEA.php#Locus">mapping files</a>, it is possible to conduct MSEA on data types other than human GWAS (i.e. GWAS from another species, EWAS, metabolome, etc.). MSEA is robust accross multiple species and data types. Here, we provide sample files and examples as to how MSEA can be applied to <a href="#MouseGWAS">Mouse GWAS</a> and <a href="#HumanEWAS">Human EWAS</a> data types. The output formats of these analyes are conssitent with the <a href="tutorial_MSEA.php#Tutorial 1">MSEA Example</a> above, and thus can be utilized in the downstream <a href="tutorial_KDA.php#SSEA2KDA">wKDA Analysis</a>.

        </p>        
        </div>


        <h1><a name="MouseGWAS"></a>Mouse GWAS Example</h1>
        <div class="boxed">
        <p>
            <ol>
            <li>
                To conduct MSEA on data types other than the <a href="tutorial_MSEA.php#Tutorial 1">Human GWAS Example</a> provided above is quite simple and follows much the same procedure as the Human GWAS MSEA example. The only differences are the <a href="tutorial_MSEA.php#GWAS">association</a> files and marker-to-gene <a href="tutorial_MSEA.php#Locus">mapping</a> files are <b>supplied by the user</b>.
            </li>
            <li>
                <b>Example Mouse GWAS Association</b> files have been provided in the <a href="tutorial_MSEA.php#GWAS">Association Dataset</a> section and can be implemented as demonstrated there. These files have the marker to trait association from the mouse GWAS. The full file is also available for <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Association/Mouse_Sample_GWAS.txt">download</a> at the download section.
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image048.jpg" width="350px" border=1 style="border-color: grey" ></li>
              <li class="product"><table id="ver-minimalist">
              <tr>
                  <th>MARKER</th>
                  <th>VALUE</th>
              </tr>
              <tr>
                  <td>rs13459</td>
                  <td>2.202</td>
              </tr>
              <tr>
                  <td>rs13462</td>
                  <td>1.114</td>
              </tr>
              <tr>
                  <td>rs12463</td>
                  <td>0.939</td>
              </tr>
              </table></li>
            </ul>
            <li>
                <b>Example Mouse GWAS Marker-to-Gene Mapping</b> files have been provided in the <a href="tutorial_MSEA.php#Locus">Marker Mapping</a> section. These files map the mouse GWAS loci to genes. The full file is also available for <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Mapping/Mouse_Sample_Locus_Mapping.txt">download</a> in the download section.
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image049.jpg" width="200px" border=1 style="border-color: grey" ></li>
              <li class="product"><img src="Tutorial1_files/image050.jpg" width="225px" border=1 style="border-color: grey" ></li>
              <li class="product"><table id="ver-minimalist" style="width:125px">
              <tr>
                  <th>GENE</th>
                  <th>MARKER</th>
              </tr>
              <tr>
                  <td>MACROD1</td>
                  <td>rs27240</td>
              </tr>
              <tr>
                  <td>CTSF</td>
                  <td>rs38597</td>
              </tr>
              <tr>
                  <td>ANXA4</td>
                  <td>rs31420</td>
              </tr>
              </table></li>
            </ul>
            <li>
                The <b>Selection of Parameters</b> is identical to those demonstrated in the <a href="tutorial_MSEA.php#SSEAParameters">MSEA Parameter Selection</a> section of the tutorial.
            </li>
            <li>
                The remainder of the MSEA pipeline for Mouse GWAS follows the <b>same workflow</b> as the MSEA <a href="tutorial_MSEA.php#Tutorial 1">Human GWAS Tutorial</a> shown above, picking back up at Step 4: <a href="tutorial_MSEA.php#Gene_Sets">Gene Sets</a>. 
            </li>
                
            </ol>
            <b>IMPORTANT:</b> This methodology can be applied to <a href="tutorial_Meta.php">Meta-MSEA</a>. Specifically, multiple types of assocation data (i.e. GWAS from multiple species, EWAS, metabolome, etc.) can all be integrated using Meta-MSEA as long as the user provides the correct <a href="tutorial_MSEA.php#GWAS">association</a> and marker-to-gene <a href="tutorial_MSEA.php#Locus">mapping</a> files.
        </p>
        </div>


        <h1><a name="HumanEWAS"></a>Human EWAS Example</h1>
        <div class="boxed">
        <p>
            <ol>
            <li>
                To conduct MSEA on data types other than the <a href="#Tutorial 1">Human GWAS Example</a> provided above is quite simple and follows much the same procedure as the Human GWAS MSEA example. The only differences are the <a href="tutorial_MSEA.php#GWAS">association</a> files and marker-to-gene <a href="tutorial_MSEA.php#Locus">mapping</a> files are <b>supplied by the user</b>.
            </li>
            <li>
                <b>Example Human EWAS Association</b> files have been provided in the <a href="tutorial_MSEA.php#GWAS">Association Dataset</a> section and can be implemented as demonstrated there. These files have the marker to trait association from the human EWAS. The full file is also available for <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Association/Sample_EWAS.txt">download</a> at the download section.
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image052.jpg" width="350px" border=1 style="border-color: grey" ></li>
              <li class="product"><table id="ver-minimalist">
              <tr>
                  <th>MARKER</th>
                  <th>VALUE</th>
              </tr>
              <tr>
                  <td>cg24411</td>
                  <td>0.0006</td>
              </tr>
              <tr>
                  <td>cg10222</td>
                  <td>0.0004</td>
              </tr>
              <tr>
                  <td>cg00962</td>
                  <td>0.0013</td>
              </tr>
              </table></li>
            </ul>
            <li>
                <b>Example Human EWAS Marker-to-Gene Mapping</b> files have been provided in the <a href="tutorial_MSEA.php#Locus">Marker Mapping</a> section. These files map the human EWAS markers to genes. The full file is also available for <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Mapping/Sample_EWAS_mapping.txt">download</a> in the download section.
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image049.jpg" width="200px" border=1 style="border-color: grey" ></li>
              <li class="product"><img src="Tutorial1_files/image055.jpg" width="225px" border=1 style="border-color: grey" ></li>
              <li class="product"><table id="ver-minimalist" style="width:125px">
              <tr>
                  <th>GENE</th>
                  <th>MARKER</th>
              </tr>
              <tr>
                  <td>A1CF</td>
                  <td>cg24411</td>
              </tr>
              <tr>
                  <td>A1CF</td>
                  <td>cg10222</td>
              </tr>
              <tr>
                  <td>A2BP1</td>
                  <td>cg00962</td>
              </tr>
              </table></li>
            </ul>
            <li>
                The <b>Selection of Parameters</b> is identical to those demonstrated in the <a href="tutorial_MSEA.php#SSEAParameters">MSEA Parameter Selection</a> section of the tutorial, with the addition of one step: it is run with <b>marker permutation</b>.
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image051.jpg" width="500px" border=1 style="border-color: grey" ></li>
            </ul>
            <li>
                The remainder of the MSEA pipeline for Human EWAS follows the <b>same workflow</b> as the MSEA <a href="tutorial_MSEA.php#Tutorial 1">Human GWAS Tutorial</a> shown above, picking back up at Step 4: <a href="tutorial_MSEA.php#Gene_Sets">Gene Sets</a>. 
            </li>
                
            </ol>
            <b>IMPORTANT:</b> This methodology can be applied to <a href="tutorial_Meta.php">Meta-MSEA</a>. Specifically, multiple types of assocation data (i.e. GWAS from multiple species, EWAS, metabolome, etc.) can all be integrated using Meta-MSEA as long as the user provides the correct <a href="tutorial_MSEA.php#GWAS">association</a> and marker-to-gene <a href="tutorial_MSEA.php#Locus">mapping</a> files.
        </p>
        </div>

<br>
<br>



</div> <!-- end of right content -->

<div class="footer">
    <div class="footer_right">
    <a href="https://yanglab.ibp.ucla.edu/">Yang Lab - UCLA</a>
    </div>
    <div class="clear"></div>
</div>

</body>
</html>