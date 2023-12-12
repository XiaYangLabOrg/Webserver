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
    <a name="LDPrune"></a>Marker Dependency Filtering (MDF)
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
        Before running the Mergeomics pipeline, if users are providing their own association data (i.e. GWAS, EWAS, etc.),
        we recommend that users utilize the provided <b>MD Prune</b> script on their on association data to account for any dependencies (if known) between markers (i.e. LD in GWAS).

        <br>
        <br>

        <b>NOTE</b>: Currently, the webserver only provides sample files for LD in CEU GWAS populations for a number of different LD thresholds. If you are using a different population, species, or data type, then you will need to upload your own 
        marker dependency file. However, it is important to note that if your file is larger than 400MB, it cannot be uploaded to our server so you will have to run the MDF yourself using the provided script and the directions <a href = "#Prune">below</a>.
        </p>
        </div>
  
        <h1><a name="MDF_web"></a>MDF Webserver Module</h1>
        <div class="boxed">
        <p>
            <ol>
            <li><h2><a name="Prune"></a>Upload Association Data</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li><a name="Prune"></a>
                        Users must select or provide an <b>Association Data</b> file that gives the correlation of markers with the specific phenotype/disease (<b>-log10 p value</b>) and follows the format specified in <a href="#Table 1">Table 1</a>. For demonstration purposes, if users would like to download the sample <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Association/glgc.ldl.txt">GWAS</a> file (which is the same LDL GWAS file displayed below) and then upload it to complete the tutorial that is feasible as well.
                    </li>

                    <ul style="text-align: center;">
                        <li class="product"><img src="Tutorial1_files/image071.jpg" width="350px" border=1 style="border-color: grey" ></li>
                        <li class="product"><table id="ver-minimalist">
                        <tr>
                            <th>MARKER</th>
                            <th>VALUE</th>
                        </tr>
                        <tr>
                            <td>rs4747841</td>
                            <td>0.1452</td>
                        </tr>
                        <tr>
                            <td>rs4749917</td>
                            <td>0.1108</td>
                        </tr>
                        <tr>
                            <td>rs737656</td>
                            <td>1.3979</td>
                        </tr>
                        </table></li>
                    </ul>

                </ol>

            <li><h2><a name="Prune"></a>Upload Marker Mapping File</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li><a name="Prune"></a>
                        Users must select or provide a <b>Marker Mapping</b> file that maps each marker in the association file to a specific gene and follows the format specified in <a href="#Table 1">Table 1</a>. For demonstration purposes, if users would like to download the sample <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Mapping/gene2loci.050kb.txt">Mapping</a> file (which is the same 50kb distance mapping displayed below) and then upload it to complete the tutorial this is feasible as well.
                    </li>

                    <ul style="text-align: center;">
                            <li class="product"><img src="Tutorial1_files/image072.jpg" width="350px" border=1 style="border-color: grey" ></li>
                            <li class="product"><table id="ver-minimalist">
                            <tr>
                                <th>GENE</th>
                                <th>MARKER</th>
                            </tr>
                            <tr>
                                <td>CDK6</td>
                                <td>rs10</td>
                            </tr>
                            <tr>
                                <td>AGER</td>
                                <td>rs1000</td>
                            </tr>
                            <tr>
                                <td>N4BP2</td>
                                <td>rs1000000</td>
                            </tr>
                            </table></li>
                    </ul>
                </ol>

            <li><h2><a name="Prune"></a>Select/Upload Marker Dependency File</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li><a name="Prune"></a>
                        Users select from pre-uploaded <b>Marker Dependency</b> files or provide their own. The provided files are a selection of LD files for GWAS data for different LD cutoffs in the CEU population. Additional LD files can be obtained from <a href="http://hapmap.ncbi.nlm.nih.gov/downloads/ld_data/?N=D">HapMap</a>. These files give the dependency of the different markers on eachother and must follow the format specified in <a href="#Table 1">Table 1</a>.
                    </li>

                    <ul style="text-align: center;">
                            <li class="product"><img src="Tutorial1_files/image073.jpg" width="350px" border=1 style="border-color: grey" ></li>
                            <li class="product"><table id="ver-minimalist">
                            <tr>
                                <th>MARKERa</th>
                                <th>MARKERb</th>
                                <th>WEIGHT</th>
                            </tr>
                            <tr>
                                <td>rs12565</td>
                                <td>rs29776</td>
                                <td>0.611</td>
                            </tr>
                            <tr>
                                <td>rs11804</td>
                                <td>rs29776</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>rs12138</td>
                                <td>rs12562</td>
                                <td>0.575</td>
                            </tr>
                            </table></li>
                    </ul>
                    <li>
                        If the user chooses to upload an association dataset, the user will be redirected to an upload page:
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image003.jpg" border=1 style="border-color: grey" ></li>
                    </ul>
                    <li>
                        After selecting the appropriate dataset, click <b>[Upload File]</b> and make sure you see the "Data Submitted" checkmark:
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image074.jpg" border=1 style="border-color: grey" ></li>
                    </ul>
                    <li>
                        Click <b>[Back to Marker Dependency Filtering]</b> after uploading your input file.
                    </li>
                    <br>
                    <b>NOTE:</b> There is a file size upload limit of 400MB (if the <b>Marker Dependency</b> file you want to use is larger than this, please follow the tutoral on how to use the <a href="#MDF_local">local version</a>.)
                </ol>

                
            <li><h2><a name="Prune"></a>Select Percentage of Top Markers</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li><a name="Prune"></a>
                        To speed computation time, we can select a certain percentage of our markers to be considered in the <b>Marker Dependency Filtering</b>. This filtering is done based on percentage of top markers, as sorted by p-value (in the <b>Marker Association</b> file).

                        <br>

                        <b>Default value:</b> 50%

                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image075.jpg" border=1 style="border-color: grey" ></li>
                    </ul>
                </ol>

            <li><h2><a name="Prune"></a>Enter Email and Run</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li><a name="Prune"></a>
                        <b>Enter Your Email ID</b> in the text box and press submit <b>(Optional)</b> if you prefer to get notification emails regarding job start and job completion. The job completion alert will also give you a link for you to download your results and provide the results as attachments. We will delete your e-mail id after job completion and this e-mail id will not be used for any further communication.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image076.jpg" width="500px" border=1 style="border-color: grey" ></li>
                    </ul>
                    <li><a name="Prune"></a>
                        <b>Click on Run MDF Button</b>
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image077.jpg" border=1 style="border-color: grey" ></li>
                    </ul>
                </ol>

            <li><h2><a name="Prune"></a>Job Execution</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li><a name="Prune"></a>
                        <b>Wait for your results.</b> Your job may take 30 minutes or more. This page will load your results after execution is done. If you want to close your browser then please copy the link in this page to see your results at a later time. If you have provided your e-mail id then we will send you this link in the job completion e-mail alert.
                    </li>
                </ol>

            <li><h2><a name="Prune"></a>Results</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li>
                        You can continue directly to the <b>MSEA</b> pipeline using the resulting MDF-corrected association and mapping files by clicking the <b>[Run MSEA]</b> button.</p>
                    </li>
                    <li>
                        You can download the MDF-corrected association and mapping files using the corresponding download links on the results page. These files can then be uploaded while running the <a href="#Tutorial 1">MSEA</a> or <a href="#MetaSSEA">Meta MSEA</a> pipelines.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image078.jpg" width="550px" border=1 style="border-color: grey" ></li>
                    </ul>
                </ol>
            </ol>
        </p>
        </div>


        <h1><a name="MDF_local"></a>Download MDF Script and Run Locally</h1>
        <div class="boxed">
        <p>
            <ol>
            <li>
                Users must first download the <a href="http://mergeomics.research.idre.ucla.edu/Download/MDPrune/mdprune"> MD Prune script</a> and corresponding <a href="http://mergeomics.research.idre.ucla.edu/Download/MDPrune/preprocess.bash"> bash file</a> from the Downloads section. The MD Prune script calls the bash script, and only the file names in the bash script need to be modified.
            </li>
            <li>
                Users must change the path of the <b>MARFILE="../resources/gwas/CAD2.new.txt"</b> to the pathway to their association (i.e. GWAS) file. Information on the association file and the required file format is located in <a href="#Table 1"> Table 1</a>. Additionally, a sample association file can be obtained from <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Association/Sample_GWAS.txt">here</a> in the Downloads section.
            </li>
            <li>
                Users must change the path of the <b>GENFILE="../resources/mapping/gene2loci.020kb.txt"</b> to the pathway to their mapping file. Information on the mapping file and the require file format is located in <a href="#Table 1"> Table 1</a>. Additionally, a sample mapping file can be obtained from <a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Mapping/genes.hdlc_040kb_ld70.human.txt">here</a> in the Downloads section.
            </li>
            <li>
                The last file that is needed for the MD Prune script is a marker dependency file which needs to have the associated path altered: <b>MDSFILE="../resources/linkage/ld70.ceu.txt"</b> This file defines the dependency structure between markers. These files can be obtained for LD of GWAS loci for different human populations from the HapMap consortium <a href="http://hapmap.ncbi.nlm.nih.gov/downloads/ld_data/2009-04_rel27/">here</a>, in addition to commonly used LD files, which are provided as sample files.
            </li>
            <li>
                Optionally, the output path for the dependency corrected association and mapping files can be specified: <b>OUTPATH="output/"</b> And the percentage of top associated markers can be limited to speed computation: <b>NTOP=0.5</b>. The output, dependency corrected association and mapping files, can then be used in the following <a href="#Tutorial 1">MSEA pipeline</a>. 
            </li>
            <br>

            <ul style="text-align: center;">
                <li class="product"><img src="Tutorial1_files/image065.jpg" border=1 style="border-color: grey" ></li>
            </ul>
                
            </ol>
        </p>
        </div>


    </div> <!-- end of right content -->
    <div class="clear"></div>

        <div class="footer">
        <div class="footer_right">
        <a href="https://yanglab.ibp.ucla.edu/">Yang Lab - UCLA</a>
        </div>
        <div class="clear"></div>
    </div>

</div>

      
</body>
</html>