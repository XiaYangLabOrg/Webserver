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
    <a name="Tutorial 1"></a>Marker Set Enrichment Analysis (MSEA)
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
        This tutorial provides an overview for using our integrated MSEA-wKDA pipeline from <b>[Run MSEA]</b> tab. This tutorial illustrates the different data input steps and result display steps in the sequences as they appear for the user. Example screenshots are included to illustrate many of the steps.
        </p>

        <p>The purpose of the pipeline is to take an association dataset for a given disease or phenotype from the user as input and integrate the association data with functional genomics information, pathways, and gene networks to derive pathways, gene networks and key regulatory genes for the disease or phenotype. </p>

        <p>There are two main steps in the analysis: Marker Set Enrichment Analysis (MSEA)<span style='font-size:12.0pt;line-height:115%;font-family:"Times New Roman","serif"'> </span><span style='font-size:12.0pt;line-height:115%;font-family:"Times New Roman","serif"'>[</span><a href="tutorial.php#_ENREF_1" title="Makinen, 2014 #274"><span style='font-size:12.0pt;line-height:115%;font-family:"Times New Roman","serif";color:windowtext;text-decoration:none'>1</span></a><span style='font-size:12.0pt;line-height:
        115%;font-family:"Times New Roman","serif"'>]</span> and Weighted Key Driver Analysis(wKDA) <span style='font-size:12.0pt;line-height:115%;font-family:"Times New Roman","serif";color:black'>[</span><a href="tutorial.php#_ENREF_2" title="Zhu, 2008 #50"><span style='font-size:12.0pt;line-height:115%;font-family:"Times New Roman","serif";color:black;text-decoration:none'>2-4</span></a><span style='font-size:12.0pt;line-height:115%;font-family:"Times New Roman","serif";color:black'>]</span>. MSEA aims to identify pathways or gene subnetworks that are enriched for genetic risks of the given disease/trait. wKDA takes the significant pathways and gene subnetworks identified from MSEA and integrates them with network models to identify the key regulators (drivers).</p>

        <p>Steps 2-7 require the users to upload datasets or select pre-defined datasets needed for integration (detailed in <a href="#Table 1">Table 1</a>). If uploading new data, the user needs to follow the format indicated below.</p>

        </div>

        <h2>Table 1. Descriptions of data categories, format, and preloaded sample files</h2>

        <!-- TABLE IS COLLAPSED -->
<div class="CSSTableGenerator" >
                <table>
                    <tr>
                        <td>
                            Data Category
                        </td>
                        <td >
                            Data Category Description
                        </td>
                        <td>
                            Format (Tab Delimited)
                        </td>
                        <td>
                            Preloaded Sample File Name
                        </td>
                        <td>
                            Sample File Description
                        </td>
                        <td>
                            Sample Data References
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="8">
                            Disease Association Data
                        </td>
                        <td rowspan="8">
                            Marker to trait association
                        </td>
                        <td rowspan="8">
                            Marker id, -log<sub>10</sub>p value [<a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Association/Sample_GWAS.txt">Example</a><span class=MsoHyperlink>]</span>
                        </td>
                        <td>
                            Sample GWAS
                        </td>
                        <td>
                            MDF-corrected LDL GWAS from GLGC
                        </td>
                        <td> <span style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_5"title="Teslovich, 2010 #1830"><span style='font-size:10.0pt;color:black; text-decoration:none'>5</span></a><span style='font-size:10.0pt;color:black'>]</span>
                        </td>
                    </tr>
                    <tr>
                      <td>
                        glgc.tc
                      </td>
                      <td>
                        Total cholesterol GWAS
                      </td>
                      <td>
                        <span style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_6"
                        title="Willer, 2013"><span style='font-size:10.0pt;color:black;
                        text-decoration:none'>6</span></a><span style='font-size:10.0pt;color:black'>]</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        glgc.tg
                      </td>
                      <td>
                        Triglyercid GWAS
                      </td>
                      <td>
                        <span style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_6"
                        title="Willer, 2013"><span style='font-size:10.0pt;color:black;
                        text-decoration:none'>6</span></a><span style='font-size:10.0pt;color:black'>]</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        glgc.ldl
                      </td>
                      <td>
                        LDL GWAS
                      </td>
                      <td>
                        <span style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_6"
                        title="Willer, 2013"><span style='font-size:10.0pt;color:black;
                        text-decoration:none'>6</span></a><span style='font-size:10.0pt;color:black'>]</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        glgc.hdl
                      </td>
                      <td>
                        HDL GWAS
                      </td>
                      <td>
                        <span style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_6"
                        title="Willer, 2013"><span style='font-size:10.0pt;color:black;
                        text-decoration:none'>6</span></a><span style='font-size:10.0pt;color:black'>]</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        cardiogram_c4d.cad
                      </td>
                      <td>
                        Coronary artery disease GWAS
                      </td>
                      <td>
                        <span style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_7"
                        title="Nikpay, 2015"><span style='font-size:10.0pt;color:black;
                        text-decoration:none'>7</span></a><span style='font-size:10.0pt;color:black'>]</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        diagram.t2d
                      </td>
                      <td>
                        Type 2 diabetes GWAS
                      </td>
                      <td>
                        <span style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_8"
                        title="Mahajan, 2014"><span style='font-size:10.0pt;color:black;
                        text-decoration:none'>8</span></a><span style='font-size:10.0pt;color:black'>]</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        magic.fastingglucose
                      </td>
                      <td>
                        Fasting glucose GWAS
                      </td>
                      <td>
                        <span style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_9"
                        title="Dupuis, 2010"><span style='font-size:10.0pt;color:black;
                        text-decoration:none'>9</span></a><span style='font-size:10.0pt;color:black'>]</span>
                      </td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <td rowspan="11">
                            Marker Mapping Data
                        </td>
                        <td rowspan="11">
                            Marker to gene mapping
                        </td>
                        <td rowspan="11">
              Marker id, gene
                symbol id [<a
                href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Mapping/genes.hdlc_040kb_ld70.human.txt">Example]</a>
                        </td>
                        <td>
                            esnp.all
                        </td>
                        <td>
                            Combined list of all eQTLs curated from literature
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[<a href="tutorial.php#_ENREF_6"
                title="Schadt, 2008 #1322"><span style='font-size:10.0pt;color:windowtext;
                text-decoration:none'>6-21</span></a><span style='font-size:10.0pt'>]</span>
                        </td>
                    </tr>
          <tr>
                        <td>
                            esnp.adipose
                        </td>
                        <td>
                            Adipose eQTLs
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_10"
                title="Carithers, 2015"><span style='font-size:10.0pt;color:black;
                text-decoration:none'>10-13</span></a><span style='font-size:10.0pt'>]</span>
                        </td>
          </tr>
          <tr>
                        <td>
                            esnp.blood
                        </td>
                        <td>
                            Blood eQTLs
                        </td>
                        <td>
              <span
                style='font-size:10.0pt'>[</span><a href="tutorial.php#_ENREF_10"
                title="Carithers, 2015"><span style='font-size:10.0pt;color:windowtext;
                text-decoration:none'>10</span></a><span style='font-size:10.0pt'>, </span><a
                href="tutorial.php#_ENREF_12" title="Emilsson, 2008"><span style='font-size:10.0pt;
                color:windowtext;text-decoration:none'>12</span><span style='font-size:10.0pt'>, </span><a
                href="tutorial.php#_ENREF_14" title="Fehrmann, 2011"><span style='font-size:10.0pt;
                color:windowtext;text-decoration:none'>14</span></a><span style='font-size:
                10.0pt'>]</span>
                        </td>
          </tr>
          <tr>
                        <td>
                            esnp.brain
                        </td>
                        <td>
                            Brain eQTLs
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_15"
                title="Derry, 2010"><span style='font-size:10.0pt;color:black;
                text-decoration:none'>15-18</span></a><span style='font-size:10.0pt'>]</span>
                        </td>
          </tr>
          <tr>
                        <td>
                            esnp.liver
                        </td>
                        <td>
                            Liver eQTLs
                        </td>
                        <td>
              <span
                style='font-size:10.0pt'>[</span><a href="tutorial.php#_ENREF_10"
                title="Carithers, 2015"><span style='font-size:10.0pt;color:windowtext;
                text-decoration:none'>10</span></a><span style='font-size:10.0pt'>, </span><a
                href="tutorial.php#_ENREF_13" title="Greenawalt, 2011"><span style='font-size:10.0pt;
                color:windowtext;text-decoration:none'>13</span><span style='font-size:10.0pt'>, </span><a
                href="tutorial.php#_ENREF_19" title="Schadt, 2008"><span style='font-size:10.0pt;
                color:windowtext;text-decoration:none'>19</span></a><span style='font-size:
                10.0pt'>]</span>
                        </td>
          </tr>
          <tr>
                        <td>
                            esnp.muscle_skeletal
                        </td>
                        <td>
                            Skeletal muscle eQTLs
                        </td>
                        <td>
              <span
                style='font-size:10.0pt'>[</span><a href="tutorial.php#_ENREF_10"
                title="Carithers, 2015"><span style='font-size:10.0pt;color:windowtext;
                text-decoration:none'>10</span></a><span style='font-size:10.0pt'>]</span>
                        </td>
          </tr>
          <tr>
                        <td>
                            gene2loci.010kb
                        </td>
                        <td>
                            Human SNP to gene mapping based on a chromosomal distance of 10kb
                        </td>
                        <td>
                          Null
                        </td>
          </tr>
          <tr>
                        <td>
                            gene2loci.020kb
                        </td>
                        <td>
                            Human SNP to gene mapping based on a chromosomal distance of 20kb
                        </td>
                        <td>
                          Null
                        </td>
          </tr>
          <tr>
                        <td>
                            gene2loci.050kb
                        </td>
                        <td>
                            Human SNP to gene mapping based on a chromosomal distance of 50kb
                        </td>
                        <td>
                            Null
                        </td>
          </tr>
          <tr>
                        <td>
                            gene2loci.regulome
                        </td>
                        <td>
                            Human SNP to gene mapping based on RegulomeDB (ENCODE)
                        </td>
                        <td>
              <span
                style='font-size:10.0pt'>[</span><a href="tutorial.php#_ENREF_20" title="Boyle, 2012"><span
                style='font-size:10.0pt;color:windowtext;text-decoration:none'>20</span></a><span
                style='font-size:10.0pt'>]</span>
                        </td>
          </tr>
          <tr>
                        <td>
                            all.mapping
                        </td>
                        <td>
                            Combined list of all the above mapping
                        </td>
                        <td>
                            Null
                        </td>
          </tr>
                    <tr>
                        <td rowspan="2">
                            Gene Sets
                        </td>
                        <td rowspan="2">
                            Collections of pre-defined sets of genes that are functionally related
                        </td>
                        <td rowspan="2">
              Gene symbol id,
                gene set id [<a
                href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Pathways/modules_canonical.txt">Example</a><span
                style='font-size:10.0pt'>]</span>
                        </td>
                        <td >
                            Canonical pathways
                        </td>
                        <td >
                            Pathways collected from KEGG, REACTOME and Biocarta
                        </td>
                        <td>
              <span
                style='font-size:10.0pt'>[</span><a href="tutorial.php#_ENREF_21"
                title="Joshi-Tope, 2005"><span style='font-size:10.0pt;color:windowtext;
                text-decoration:none'>21</span></a><span style='font-size:10.0pt'>, </span><a
                href="tutorial.php#_ENREF_22" title="Ogata, 1999"><span style='font-size:10.0pt;
                color:windowtext;text-decoration:none'>22</span></a><span style='font-size:
                10.0pt'>]</span>
                        </td>
          </tr>
          <tr>
                        <td >
                            Co-expression modules
                        </td>
                        <td >
                            Derived from coexpression networks by applying WGCNA on gene expression data
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_12"
                title="Emilsson, 2008"><span style='font-size:10.0pt;color:black;
                text-decoration:none'>12</span></a><span style='font-size:10.0pt;color:black'>,
                </span><a href="tutorial.php#_ENREF_13" title="Greenawalt, 2011"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>13</span></a><span style='font-size:10.0pt;color:black'>,
                </span><a href="tutorial.php#_ENREF_15" title="Derry, 2010"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>15-19</span></a><span style='font-size:10.0pt;color:black'>,
                </span><a href="tutorial.php#_ENREF_23" title="Erbilgin, 2013"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>23</span></a><span
                style='font-size:10.0pt;color:black'>]</span>
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2" bgcolor="#e5e5e5">
                            Gene Sets Description
                        </td>
                        <td rowspan="2" bgcolor="#e5e5e5">
                            Detailed descriptions of gene sets such as the full name of a biological pathway
                        </td>
                        <td rowspan="2" bgcolor="#e5e5e5">
              Gene set id, gene
                set description [<a
                href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Pathways/modules_canonical.info.txt">Example</a><span
                style='font-size:10.0pt'>]</span>
                        </td>
                        <td >
                            Canonical pathways
                        </td>
                        <td >
                            Description of the pathways including pathway name and database source
                        </td>
                        <td>
              <span
                style='font-size:10.0pt'>[</span><a href="tutorial.php#_ENREF_21"
                title="Joshi-Tope, 2005"><span style='font-size:10.0pt;color:windowtext;
                text-decoration:none'>21</span></a><span style='font-size:10.0pt'>, </span><a
                href="tutorial.php#_ENREF_22" title="Ogata, 1999"><span style='font-size:10.0pt;
                color:windowtext;text-decoration:none'>22</span></a><span style='font-size:
                10.0pt'>]</span>
                        </td>
                    </tr>
          <tr>
                        <td >
                            Co-expression modules
                        </td>
                        <td >
                            Description includes tissue type for the expression data
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_12"
                title="Emilsson, 2008"><span style='font-size:10.0pt;color:black;
                text-decoration:none'>12</span></a><span style='font-size:10.0pt;color:black'>,
                </span><a href="tutorial.php#_ENREF_13" title="Greenawalt, 2011"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>13</span></a><span style='font-size:10.0pt;color:black'>,
                </span><a href="tutorial.php#_ENREF_15" title="Derry, 2010"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>15-19</span></a><span style='font-size:10.0pt;color:black'>,
                </span><a href="tutorial.php#_ENREF_23" title="Erbilgin, 2013"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>23</span></a><span
                style='font-size:10.0pt;color:black'>]</span>
                        </td>
          </tr>
          <tr>
                        <td rowspan="6">
                            Gene Regulatory Netwrosk
                        </td>
                        <td rowspan="6">
                            Network edges from pre-defined gene networks
                        </td>
                        <td rowspan="6">
              Source gene id,
                target gene id, weight [<a
                href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Network/networks.hs.liver.txt">Example</a><span
                style='font-size:10.0pt'>]</span>
                        </td>
                        <td >
                            adipose
                        </td>
                        <td >
                            Adipose Bayesian networks
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_12"
                title="Emilsson, 2008"><span style='font-size:10.0pt;color:black;
                text-decoration:none'>12</span></a><span style='font-size:10.0pt;color:black'>,
                </span><a href="tutorial.php#_ENREF_15" title="Derry, 2010"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>15-19</span></a><span
                style='font-size:10.0pt;color:black'>]</span>
                        </td>
                        </tr>
                        <tr>
                        <td >
                            blood
                        </td>
                        <td >
                            Blood Bayesian networks
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_12"
                title="Emilsson, 2008"><span style='font-size:10.0pt;color:black;
                text-decoration:none'>12</span></a><span
                style='font-size:10.0pt;color:black'>]</span>
                        </td>
                        </tr>
                        <tr>
                        <td >
                            brain
                        </td>
                        <td >
                            Brain Bayesian networks
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_15" title="Derry, 2010"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>15-18</span></a><span
                style='font-size:10.0pt;color:black'>]</span>
                        </td>
                        </tr>
                        <tr>
                        <td >
                            liver
                        </td>
                        <td >
                            Liver Bayesian networks
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="#_ENREF_15" title="Derry, 2010"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>15-19</span></a><span
                style='font-size:10.0pt;color:black'>]</span>
                        </td>
                        </tr>
                        <tr>
                        <td >
                            muscle
                        </td>
                        <td >
                            Muscle Bayesian networks
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_15" title="Derry, 2010"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>15-19</span></a><span
                style='font-size:10.0pt;color:black'>]</span>
                        </td>
                        </tr>
                        <tr>
                        <td >
                            PPI
                        </td>
                        <td >
                            Protein-protein interaction network
                        </td>
                        <td>
              <span
                style='font-size:10.0pt;color:black'>[</span><a href="tutorial.php#_ENREF_24" title="Rossin, 2011"><span style='font-size:
                10.0pt;color:black;text-decoration:none'>24</span></a><span
                style='font-size:10.0pt;color:black'>]</span>
                        </td>
          </tr>
                </table>
            </div>

  
        <h1><a name="GWAS"></a>Association Dataset for MSEA</h1>
        <div class="boxed">
        <p>
            <ol>
            <li><h2>Select/Upload Association Data</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li>
                        The menu gives the user the option to select either a sample association dataset or upload their own dataset.
                    </li>
                    <b>IMPORTANT:</b> Press the submit button after selecting your option.

                    <ul style="text-align: center;">
                        <li class="product"><img src="Tutorial1_files/image002.jpg" border=1 style="border-color: grey" ></li>
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

                    <li>
                        If the user chooses to upload an association dataset, the user will be redirected to an upload page:
                    </li>
                    <ul style="text-align: center;">
                        <li class="product"><img src="Tutorial1_files/image003.jpg" border=1 style="border-color: grey" ></li>
                    </ul>

                    <li>
                        After selecting the appropriate dataset (<a href="http://mergeomics.research.idre.ucla.edu/Download/Sample_Files/Association/Sample_GWAS.txt">one</a> is provided in the downloads section), click <b>[Upload File]</b> and make sure you see the "Data Submitted" checkmark:
                    </li>
                    <ul style="text-align: center;">
                        <li class="product"><img src="Tutorial1_files/image029.jpg" width="450px" border=1 style="border-color: grey" ></li>
                    </ul>

                    <li>
                        Click <b>[Back to MSEA]</b> after uploading your input file.
                    </li>


                </ol>
                </ol>

        </p>
        </div>

        <h1><a name="Locus"></a>Marker Mapping File for MSEA</h1>  

        <div class="boxed">
        <p>

        <ol start=2>
            <li><h2>Select/Upload Marker Mapping File</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li>
                        Users can choose between uploading their own mapping files or using the sample mapping files.
                    </li>

                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image068.jpg" border=1 style="border-color: grey" ></li>
                    </ul>

                    <li>
                        If user chooses to upload a mapping file, the user will be redirected to an upload page:
                    </li>

                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image003.jpg" border=1 style="border-color: grey" ></li>
                    </ul>

                    <li>
                        <b>Select/Upload Marker Mapping</b> menu gives the user 14 mapping datasets. The datasets are described in <a href="#Table 1">Table 1</a>. The user can select any combination of the mapping files (if more than one is selected, the mapping files are combined).
                    </li>

                    <b>Note:</b> if using GWAS, the mapping file should have already corrected for LD (i.e. using <a href="#LDPrune">MDF</a>) to remove redundant SNPs that are in high LD for each gene. The preloaded mapping files have all been corrected for LD.</p>

                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image004.jpg" border=1 width="200px" style="border-color: grey" ></li>
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
        </ol>
        </p>
        </div>

        <h1><a name="SSEAParameters"></a>Parameters for MSEA</h1>  

        <div class="boxed">
        <p>

        <ol start=3>
            <li><h2>Enter MSEA Parameters</h2></li>
                <ol type="a" style="padding-left:20px">
                    <li><a name="Prune"></a>
                        Enter the following MSEA parameter values and then click the submit button.
                    </li>

                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image001.jpg" width="75%" border=1 style="border-color: grey" ></li>
                    </ul>

                    <li>
                        <b>Permutation type</b>
                        <br>
                        <b>Options:</b> Gene or Marker, indicating gene-based permutation or marker-based permutation to estimate statistical significance p-values. Gene-based permutation yields more conservative p-values than marker-based permutation.
                        <br>
                        <b>Default value:</b> Gene
                    </li>
                    <li>
                        <b>Max Genes in Gene Sets:</b> defines the maximum gene number that a gene set can have. 
                        <br>
                        <b>Options:</b> Number between 2 and 10,000; suggested between 200-800
                        <br>
                        <b>Default value:</b> 500
                    </li>
                    <li>
                        <b>Min Genes in Gene Sets:</b> defines the minimal gene number that a gene set can have. 
                        <br>
                        <b>Options:</b> Number between 2 and < Max Genes in Gene Sets
                        <br>
                        <b>Default value:</b> 20
                    </li>
                    <li>
                        <b><a name="Step 3E"></a>Min Overlap Allowed for Merging:</b> defines the minimum overlap ratio between gene sets if the user prefers to merge overlapping gene sets that are associated with the disease/trait as determined by MSEA into merged supersets.
                        <br>
                        <b>Options:</b> 0 to 1.0
                        <br>
                        <b>Default value:</b> 0.33 (33% overlap)
                    </li>
                    <li>
                        <b>Number of Permutations:</b> the number of gene or marker permutations conudcted in the MSEA analysis
                        <br>
                        <b>Options:</b> 1000 to 20,000 (for publication, recommend >= 10,000)
                        <br>
                        <b>Default value:</b> 2000
                    </li>
                    <li>
                        <b>MSEA FDR cutoff:</b> FDR should within the specified FDR cutoff.
                        <br>
                        <b>Options:</b> Between 25 to 0
                        <br>
                        <b>Default value:</b> 25
                    </li>
                    
                </ol>
        </ol>
        </p>
        </div>

        <h1><a name="Gene_Sets"></a>Gene Sets for MSEA</h1>  

        <div class="boxed">
        <p>

        <ol start=4>
            <li><h2>Select/Upload Gene Sets</h2></li>
                <ol type="a" style="padding-left:20px">        
                    <li>
                        <b>Select/Upload Gene Sets</b> menu gives the user three sample gene set datasets as described in <a href = "#Table 1">Table 1</a>. The first option in the menu is for uploading your own gene sets.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image005.jpg" border=1 width="400px" style="border-color: grey" ></li>
                    <li class="product"><table id="ver-minimalist">
                            <tr>
                                <th>MODULE</th>
                                <th>GENE</th>
                            </tr>
                            <tr>
                                <td>rctm001</td>
                                <td>CDSF4</td>
                            </tr>
                            <tr>
                                <td>rctm001</td>
                                <td>EIF2AK2</td>
                            </tr>
                            <tr>
                                <td>M10401</td>
                                <td>XRCC5</td>
                            </tr>
                            </table></li>
                    </ul>


                </ol>
        </ol>
        </p>
        </div>

        <h1><a name="Gene_Sets_Desc"></a>Gene Sets Description for MSEA</h1>  

        <div class="boxed">
        <p>

        <ol start=5>
            <li><h2>Select/Upload Gene Sets Description</h2></li>
                <ol type="a" style="padding-left:20px">        
                    <li>
                        <b>Select/Upload Gene Sets Description</b> menu gives two sample description files and an option for uploading your own gene set description file. Gene set description describes the gene sets in <a href="#Gene_Sets">Step 4</a>.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image006.jpg" width="275px" border=1 style="border-color: grey" ></li>
                    <li class="product"><table id="ver-minimalist" style="width:350px">
                            <tr>
                                <th>MODULE</th>
                                <th>SOURCE</th>
                                <th>DESCR</th>
                            </tr>
                            <tr>
                                <td>rctm001</td>
                                <td>reactome</td>
                                <td>NS1 Mediated Effects on Host Pathways</td>
                            </tr>
                            <tr>
                                <td>M10287</td>
                                <td>biocarta</td>
                                <td>fMLP induced chemokine gene expression</td>
                            </tr>
                            <tr>
                                <td>M10462</td>
                                <td>kegg</td>
                                <td>Adipocytokine signaling pathway</td>
                            </tr>
                            </table></li>
                    </ul>


                </ol>
        </ol>
        </p>
        </div>

        <h1><a name="Email/Run"></a>Enter Email and Run</h1>  

        <div class="boxed">
        <p>

        <ol start=6>
            <li><h2>Enter Email and Submit Job</h2></li>
                <ol type="a" style="padding-left:20px">        
                    <li>
                        <b>Enter your Email ID</b> in the text box and press submit <b>(Optional)</b> if you prefer to get notification emails regarding job start and job completion. The job completion alert will also give you a link for you to download your results and provide the results as attachments. We will delete your e-mail id after job completion and this e-mail id will not be used for any further communication.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image008.jpg" width="500px" border=1 style="border-color: grey" ></li>
                    </ul>
                </ol>
            <li><h2>Job Execution</h2></li>
                <ol type="a" style="padding-left:20px">        
                    <li>
                        <b>Wait for your results.</b> Your job may take 30 minutes or more due to the complexity of integration. This page will load your results after execution is done. If you want to close your browser then please copy the link in this page to see your results at a later time. If you have provided your e-mail id then we will send you this link in the job completion e-mail alert. 
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image010.jpg" width="500px" border=1 style="border-color: grey" ></li>
                    </ul>
                </ol>
            <li><h2>MSEA Pipeline Execution Email Notification</h2></li>
                <ol type="a" style="padding-left:20px">        
                    <li>
                        <b>If the user provided an email address,</b> then an email notification is sent to the provided email with a link to the results page which will be active when the MSEA analysis is completed.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image033.jpg" width="500px" border=1 style="border-color: grey" ></li>
                    </ul>
                </ol>
            <li><h2>MSEA Pipeline Completion Email Notification</h2></li>
                <ol type="a" style="padding-left:20px">        
                    <li>
                        <b>If the user provided an email address,</b> then an email notification is sent to the provided email with a link to the results page upon completion of the MSEA analysis. The results link will remain active for 24 hours. Additionally, the results files will be included in the email as attachments.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image034.jpg" width="500px" border=1 style="border-color: grey" ></li>
                    </ul>
                </ol>
        </ol>
        </p>
        </div>

        <h1><a name="SSEA Results"></a>Marker Set Enrichment Analysis Results</h1>  

        <div class="boxed">
        <p>

        <ol start=10>
            <li><h2>Display MSEA Results</h2></li>
                <ol type="a" style="padding-left:20px">        
                    <li>
                        <b>Marker Set Enrichment Analysis Table</b> lists the significant pathways/modules found to be enriched for your association data at your pre-defined FDR cutoff. The user can download the full result files containing all information from this page.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image056.jpg" width="650px" border=1 style="border-color: grey" ></li>
                    </ul>
                    <li>
                        <b>Interpretation of Results</b>
                    </li>
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
                            Module ID
                        </td>
                        <td >
                            Module id/gene set id from input gene set
                        </td>
                    </tr>
                    <tr>
                        <td>
                            MSEA:P-Value
                        </td>
                        <td >
                            Set enrichment p-value
                        </td>
                    </tr>
                    <tr>
                        <td>
                            MSEA:FDR
                        </td>
                        <td >
                            False discovery rate for set enrichment
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Description
                        </td>
                        <td >
                            Gene set description
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Module Top Genes
                        </td>
                        <td >
                            Top five genes in the gene set with the lowest p-values for the association study
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Module Top Marker
                        </td>
                        <td >
                            Top five markers in the gene set with the lowest p-values for the association study
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Module Top Association Score
                        </td>
                        <td >
                            Top five lowest p-values for the association study in -log<sub>10</sub>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Module Details
                        </td>
                        <td >
                            A web link that will load the gene set to DAVID for detailed functional annotations
                        </td>
                    </tr>
                </table>
                    </div>

                    <li>
                        <b>The Merged Supersets Table</b> lists the significant supersets after merging any overlapping gene sets among the significant pathways/modules. Merging is done as a part of our MSEA analyses based on the parameter value at <a href="#Step 3E">Step 3E</a>.
                    </li>
                    <ul style="text-align: center;">
                    <li class="product"><img src="Tutorial1_files/image069.jpg" width="650px" border=1 style="border-color: grey" ></li>
                    </ul>
                    <li>
                        <b>Interpretation of Merged Supersets</b>
                    </li>
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
                            Merge Module P-value
                        </td>
                        <td >
                            Merged set enrichment p-value
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Frequency
                        </td>
                        <td >
                            Equivalent to FDR
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Number of Genes
                        </td>
                        <td >
                            Number of genes in the gene set after merging
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Number of Markers
                        </td>
                        <td >
                            Number of association study markers in the merged module
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Density
                        </td>
                        <td >
                            Number of markers per gene
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Overlap
                        </td>
                        <td >
                            List of overlapping gene sets merged
                        </td>
                    </tr>
                    <tr>
                        <td>
                           Description
                        </td>
                        <td >
                            Functional description of the merge module
                        </td>
                    </tr>
                </table>
            </div>

            <li>
                <b>IMPORTANT:</b> You can choose to stop here if this is all you need. Only if you want to continue to run wKDA click on "Run wKDA", and continue to the <a href="tutorial_KDA#Tutorial 2">wKDA Tutorial</a>.
            </li>

                </ol>
        </ol>
        </p>
        </div>
      
</body>
</html>