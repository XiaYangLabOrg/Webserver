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
    <a name="Visualization"></a>Weighted Key Driver Analysis Network Visualization
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

        <h1>Network Graphs</h1>
        <div class="boxed">
        <p>
            <ol>
            <li>
                Displays key drivers in diamond shaped nodes and their local network neighborhood genes which are colored based their membership in the disease-associated merged supersets from MSEA. Member genes in the same superset are the same color (those that are members of multiple supersets have multiple colors).
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image058.jpg" width="650x" border=1 style="border-color: grey" ></li>
            </ul>
            <li>
                 The network visualization feature is <b>fully interactive</b> and allows individual nodes, or groups of nodes (by shift+clicking or selecting with a box) to be selected and then can be manipulated on the display.
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image059.jpg" width="200px" border=1 style="border-color: grey" ></li>
              <li class="product"><img src="Tutorial1_files/image060.jpg" width="225px" border=1 style="border-color: grey" ></li>
            </ul>
            <li>
                 The tools on the left can be used to pan, <b>select, zoom in & zoom out</b>, and size the window to <b>fit all currently visible nodes</b>. The textbox on the right can be used to <b>search for any gene (node) of interest</b>. 
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image061.jpg" width="650x" border=1 style="border-color: grey" ></li>
            </ul>
            <li>
                  Clicking <b>[Execute]</b> will <b>hide</b> all other nodes that are not in the same superset as the node of interest and <b>highlight</b> your node of interest.  
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image062.jpg" width="650x" border=1 style="border-color: grey" ></li>
            </ul>
            <li>
                  Clicking the <b>[Resize]</b> button will automatically scale the network to fit the screen size. Clicking <b>[Clear]</b> will reset the network and reveal all the hidden nodes. Clicking the <b>[Resize]</b> button will zoom the screen back out to capture all of the now visible nodes.  
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/edge_weight.jpg" width="650x" border=1 style="border-color: grey" ></li>
            </ul>
            <li>
                  Users can also filter the network based on edge weight (in this case edge weight >= 4.0). 
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image064.jpg" width="650x" border=1 style="border-color: grey" ></li>
            </ul>
            <li>
                   Right-clicking will give the user the option to <b>"EXPORT: as pdf"</b>. This allows the user to automatically save exactly what appears on the screen as an image. 
            </li>
            <ul style="text-align: center;">
              <li class="product"><img src="Tutorial1_files/image063.jpg" width="650x" border=1 style="border-color: grey" ></li>
            </ul>
            <li>
                   At the bottom of the page is a <b>legend</b> that indicates what the corresponding colors for each module are. 
            </li>
            </ol>
        </p>
        </div>


  </div>

<div class ="clear">
</div>

<div class="footer">
    <div class="footer_right">
    <a href="https://yanglab.ibp.ucla.edu/">Yang Lab - UCLA</a>
    </div>
    <div class="clear"></div>
</div>

</body>
</html>