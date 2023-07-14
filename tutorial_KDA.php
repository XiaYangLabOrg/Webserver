<!DOCTYPE html>
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
    <a name="SSEA2KDA"></a>Run Weighted Key Driver Analysis Directly on MSEA Output
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

        <h1>MSEA to wKDA</h1>
        <div class="boxed">
        <p>
            <ol>
            <li>
                <b>[Run wKDA]</b> from <a href="tutorial_MSEA.php#SSEA Results">MSEA: Part 10a</a> will start the <b>Weighted Key Driver Analysis</b> pipeline for the list of significant merged-supersets in the  "Merged supersets" table (see <a href="tutorial_MSEA.php#SSEA Results">Merged Supersets</a>). wKDA takes a gene set as input and searchs for genes in a network whose neighborhoods are enriched for genes in the input gene set.
            </li>
        </p>
        </div>


        <h1><a name="Step14"></a>Parameters for wKDA</h1>
        <div class="boxed">
        <p>
            <ol>
            <li><h2>Enter wKDA Parameters</h2></li>
                <ol type="a" style="padding-left:20px">
                  <li>
                      <b>Enter wKDA Search Depth:</b> used to define a candidate key driver's local network neighborhood by considering genes at a given distance or depth
                      <br>
                      <b>Options:</b> 1/2/3. It indicates the maximum edge distance starting from each candidate key driver gene.
                      <br>
                      <b>Default Value:</b> 1
                  </li>
                  <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image035.jpg" width="200px" border=1 style="border-color: grey" ></li>
                    <li class="product"><img src="Tutorial1_files/image036.jpg" width="225px" border=1 style="border-color: grey" ></li>
                  </ul>
                  <li>
                      <b>Enter wKDA Edge Type:</b> defines whether the directionality of edges is considered.
                      <br>
                      <b>Options:</b> Incoming and Outgoing/Only Outgoing. The former ignores directionality and the latter considers directionality by requiring the candidate key driver to be upstream of its local neighborhood genes
                      <br>
                      <b>Default Value:</b> Incoming and Outgoing
                  </li>
                  <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image037.jpg" width="200px" border=1 style="border-color: grey" ></li>
                    <li class="product"><img src="Tutorial1_files/image038.jpg" width="225px" border=1 style="border-color: grey" ></li>
                  </ul>
                  <li>
                      <b>Enter wKDA Min Overlap:</b> Used as threshold for gene overlaps to group hubs as co-hubs.
                      <br>
                      <b>Options:</b> Between 0-1. The higher the value, the more the local network neighborhood of hubs must overlap to be considered co-hubs.
                      <br>
                      <b>Default Value:</b> 0.33
                  </li>
                  <li>
                      <b>Enter wKDA Edge Factor:</b> Used to weight edge info of network to the power of the value entered
                      <br>
                      <b>Options:</b> Between 0-1. A power of zero would set all the edge weights to be equal (1).
                      <br>
                      <b>Default Value:</b> 0.5
                  </li>
                  <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image070.jpg" width="275px" border=1 style="border-color: grey" ></li>
                  </ul>
                </ol>
        </p>
        </div>


        <h1><a name="Network"></a>Network for wKDA</h1>
        <div class="boxed">
        <p>
            <ol>
            <li><h2>Select/Upload Network</h2></li>
                <ol type="a" style="padding-left:20px">
                  <li>
                      <b>Select/Upload Network Menu</b> gives the user sample network datasets. The description for the sample network datasets is included in <a href="tutorial_MSEA.php#Table 1">Table 1</a>. The first option in the menu is for uploading your own network. The input file format is described in <a href="tutorial_MSEA.php#Table 1">Table 1</a>. Included are a number of tissue-specific bayesian networks and a PPI network.
                  </li>
                  <ul style="text-align: center;">
                            <li class="product"><img src="Tutorial1_files/image039.jpg" width="175px" border=1 style="border-color: grey" ></li>
                            <li class="product"><img src="Tutorial1_files/image040.jpg" width="200px" border=1 style="border-color: grey" ></li>
                            <li class="product"><table id="ver-minimalist" style="width:200px">
                            <tr>
                                <th>TAIL</th>
                                <th>HEAD</th>
                                <th>WEIGHT</th>
                            </tr>
                            <tr>
                                <td>A1BG</td>
                                <td>SNHG6</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>A1BG</td>
                                <td>UNC84A</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>A1CF</td>
                                <td>KIAA1958</td>
                                <td>1</td>
                            </tr>
                            </table></li>
                    </ul>
                </ol>
        </p>
        </div>

        <h1><a name="KDA email"></a>Enter Email and Run</h1>
        <div class="boxed">
        <p>
            <ol>
            <li><h2>Enter Email and Submit Job</h2></li>
                <ol type="a" style="padding-left:20px">
                  <li>
                      <b>Enter your Email ID</b> in the text box and press submit <b>(Optional)</b> if you prefer to get notification emails regarding job start and job completion. The job completion alert will also give you a link for you to download your results. We will delete your e-mail id after job completion and this e-mail id will not be used for any further communication.
                  </li>
                  <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image041.jpg" width="500px" border=1 style="border-color: grey" ></li>
                  </ul>
                </ol>
            <li><h2>Job Execution</h2></li>
                <ol type="a" style="padding-left:20px">
                  <li>
                      Your job execution may take <b>30 minutes or more</b>. This page will load your wKDA results after execution is done. If you want to close this browser then please copy the link in this page to see your results. If you have provided your e-mail id then we will send you this link in your job completion e-mail alert.
                  </li>
                  <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image042.jpg" width="500px" border=1 style="border-color: grey" ></li>
                  </ul>
                </ol>
        </p>
        </div>


        <h1><a name="KDA Results"></a>Weighted Key Driver Analysis Results</h1>
        <div class="boxed">
        <p>
            <ol>
            <li><h2>Display wKDA Results</h2></li>
                <ol type="a" style="padding-left:20px">
                  <li>
                      <b>wKDA Results Table</b> lists the key drivers (second column) in the merged supersets (first column) with the associated p-values and FDRs.
                  </li>
                  <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image057.jpg" width="670px" border=1 style="border-color: grey" ></li>
                  </ul>
                </ol>
            <li><h2>Interpretation of Results</h2></li>
                  <div class="CSSTableGenerator" style="width:700px">
                <table>
                    <tr>
                        <td>
                            Field Name
                        </td>
                        <td >
                            Description
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Merge Module ID
                        </td>
                        <td >
                            New module id/gene set after merge
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Module Description
                        </td>
                        <td >
                            Functional description of the merged module
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Key Driver Node
                        </td>
                        <td >
                            Key driver genes in the merged module
                        </td>
                    </tr>
                    <tr>
                        <td>
                            P-value
                        </td>
                        <td >
                            Enrichment p-value for key driver genes
                        </td>
                    </tr>
                    <tr>
                        <td>
                            FDR
                        </td>
                        <td >
                            False discovery rate for the enrichment value of the key driver node
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Module Genes
                        </td>
                        <td >
                            Total number of nodes in the gene module
                        </td>
                    </tr>
                    <tr>
                        <td>
                            KD Subnetwork Genes
                        </td>
                        <td >
                            Number of neighboring nodes for the key driver genes
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Module and Subnetwork Overlap
                        </td>
                        <td >
                            Number of GWAS loci present within the neighbors of the key driver
                        </td>
                    </tr>
                    <tr>
                        <td>
                           Fold Enrichment
                        </td>
                        <td >
                            Enrichment of KD subnetwork genes within the gene module
                        </td>
                    </tr>
                </table>
            </div>
            <li><h2>Display Network Graph</h2></li>
                <ol type="a" style="padding-left:20px">
                  <li>
                      To display the network graphs for the top key drivers, Click on the <b>[Display Network Graph]</b> button
                  </li>
                  <li>
                      See <a href = "tutorial_vis.php">Visualization</a> Section for a tutorial on the network visualization feature.
                  </li>
                </ol>
        </p>
        </div>




</div> <!-- end of right content -->


<div class="clear"></div>
<div class="pages_banner">
    <a name="Tutorial 2"></a>Run Weighted Key Driver Analysis Separately
</div>

<div class="right_content">

        <h1>Overview</h1>
        <div class="boxed">
        <p>

                The user may bypass MSEA and directly run wKDA if gene sets of interest are available and the user wishes to identify potential key drivers of the gene sets. This tutorial provides an overview of using our KDA pipeline available from <b>[Run wKDA]</b> tab. The example screen shots are included for most of the steps.
        </p>
        </div>

        <h1><a name="KDA_Gene_Set"></a>Gene Sets for wKDA</h1>
        <div class="boxed">
        <p>
            <ol>
            <li><h2>Select/Upload Gene Sets for wKDA</h2></li>
                <ol type="a" style="padding-left:20px">
                  <li>
                      In this step, the users upload their own gene set file or select a preuploaded sample file. The gene set file is a tab delimited text file with two fields, <b>MODULE</b> and <b>NODE</b>. <b>MODULE</b> includes module id which is a unique number for all the genes in the gene set. <b>NODE</b> includes gene names. One sample gene set file is available for <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/KDA_Input_Module/KDA_Input_Gene_Module.txt">download</a> at the download section.
                  </li>
                  <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image079.jpg" width="200px" border=1 style="border-color: grey" ></li>
                    <li class="product"><img src="Tutorial1_files/image080.jpg" width="225px" border=1 style="border-color: grey" ></li>
                    <li class="product"><table id="ver-minimalist" style="width:180px">
                            <tr>
                                <th>MODULE</th>
                                <th>NODE</th>
                            </tr>
                            <tr>
                                <td>7015</td>
                                <td>Afm</td>
                            </tr>
                            <tr>
                                <td>7015</td>
                                <td>Alkbh2</td>
                            </tr>
                            <tr>
                                <td>7015</td>
                                <td>Art3</td>
                            </tr>
                            </table></li>
                  </ul>
                </ol>
        </p>
        </div>

        <h1><a name="KDA Parameters"></a>Parameters for wKDA</h1>
        <div class="boxed">
        <p>
            <ol start = 2>
            <li><h2>Enter wKDA Parameters</h2></li>
                <ol type="a" style="padding-left:20px">
                  <li>
                      From this step onward follow the steps from <a href="#Step14">Parameters for wKDA</a> in MSEA to wKDA.
                  </li>
                </ol>
        </p>
        </div>
</div>


<div class="footer">
    <div class="footer_right">
    <a href="https://yanglab.ibp.ucla.edu/">Yang Lab - UCLA</a>
    </div>
    <div class="clear"></div>
</div>

</body>
</html>