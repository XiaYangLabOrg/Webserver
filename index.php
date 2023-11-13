<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<?php include_once("analyticstracking.php") ?>

   <!-- Includes all the font/styling/js sheets -->

<?php include_once("head.inc") ?>


<body class="stretched">



<!-- Include the Frontpage Main Header-->
  <?php include_once("header.inc") ?>

 

      <!-- #flowchart begin -->
<!--<div style="text-align: center;padding: 20px 20px 0 20px;font-size: 16px;">
  <div class="alert alert-warning" style="margin: 0 auto; width: 50%;">
    <i class="icon-warning-sign" style="margin-right: 6px;font-size: 15px;"></i><strong>Note:</strong> It is highly recommended to use Chrome. We are currently modifying and streamlining the Mergeomics web server to make it easier to follow and use. For any bugs, issues, or suggestions for improvement, please use our contact form. Thank you!
  </div>
</div>-->

    <section id="slider" class="slider-element boxed-slider">


      <div class="container clearfix" align="center" style="margin-left:auto !important;">

        <img src="include/pictures/flowchart.png" alt="flowchart" data-animate="fadeIn">


      </div>

    </section>
    <!-- #flowchart ends -->

      <!-- Content
    ============================================= -->
    <section id="content">

      <div class="content-wrap" style="padding: 40px 0 0 0;">

        <div class="container clearfix" style="margin-left:auto !important;">

          <div class="row clearfix">


            <!-- MDF Column ===================================================-->
            <div class="col-lg-4 center bottommargin" data-animate="fadeIn">
             <!-- <i class="i-plain i-large icon-filter1 inline-block" style="margin-bottom: 20px; color:red;"></i>
              <div class="heading-block nobottomborder" style="margin-bottom: 15px;">
                <h4>Marker Dependency Filtering</h4>
              </div>
              <div class="fbox-media">
                <img src="include/pictures/LD.jpg" alt="MDF">
              </div>
              <p><b>MDF</b> prepares input files for <b>MSEA</b> by correcting for dependency between omics markers (e.g. linkage disequilibrium between SNPs in GWAS).</p>

              <div align="right">
              <a href="tutorial_MDF.php" class="more-link">Learn More</a>
              </div> -->

            </div>

            <!-- MSEA Column ===================================================-->
            <div class="col-lg-4 center bottommargin" data-animate="fadeIn">
             <!-- <b><i class="i-plain i-large icon-line2-magnifier inline-block" style="margin-bottom: 20px; color: blue; font-weight: bold;"></i></b>
              <div class="heading-block nobottomborder" style="margin-bottom: 15px;">
                <h4>Marker Set Enrichment Analysis</h4>
              </div>

              <div class="fbox-media">
                <img src="include/pictures/msea.jpg" alt="MSEA">
              </div>

              <p><b>MSEA</b> detects pathways and networks affected by multidimensional molecular markers (e.g., SNPs, differential methylation sites) associated with a pathological condition. The pipeline can be concluded after <b>MSEA</b> is run, or the results can be used directly in <b>wKDA.</b></p>
              <div align="right">
              <a href="Tutorial_MSEA.php" class="more-link">Learn More</a>
              </div> -->
            </div>


            <!-- wKDA Column ===================================================-->
            <div class="col-lg-4 center bottommargin" data-animate="fadeIn">
             <!-- <i class="i-plain i-large icon-dna inline-block" style="margin-bottom: 20px; color: gold;"></i>
              <div class="heading-block nobottomborder" style="margin-bottom: 15px;">
                <h4>Weighted Key Driver Analysis</h4>
              </div>

              <div class="fbox-media">
                <img src="include/pictures/wkda.jpg" alt="wKDA">
              </div>


              <p><b>wKDA</b> identifies essential regulators of disease-associated pathways and networks and produces the corresponding interactive network visualization. <b>wKDA</b> can be run as a follow-up to <b>MSEA</b> or <b>Meta MSEA</b>; or it can be run as an independent module.</p>

              <div align="right">
              <a href="tutorial_KDA.php" class="more-link">Learn More</a>
              </div>-->

            </div>

          </div>
        </div>
  </div><!-- #wrapper end -->
</section>


    <!-- End Content
    ============================================= -->

      <!-- Footer begins
    ============================================= -->
    <footer id="footer" class="light notopborder" style="background: #F9F9F9 url(include/pictures/pattern-dark.png) repeat center center;">

    <div style="text-align: center;padding-top: 2%;font-size: 20px;">The Mergeomics web tool is freely accessible to all users. Test<br> We recommend using Chrome or Firefox for the best viewing experience.</div>

      <div class="footercontainer" style="margin-left:auto !important;">

       
        <div class="footer-widgets-wrap clearfix" style="padding-top: 30px;">

           <!-- Footer -- About
        ============================================= -->

        <div class="col_one_fourth">

            <div class="widget clearfix">

              <h5 class="mfront_h5">About</h5>
              <div class="footerdivider"></div>

              <p class="mfront_p">Mergeomics is being actively developed by the <a href="https://yanglab.ibp.ucla.edu/">Yang Lab</a> in the <a href="https://www.ibp.ucla.edu/">Department of Integrative Biology and Physiology</a> at UCLA. The Yang Lab at UCLA uses integrative genomics and systems biology approaches to better understand the molecular mechanisms of complex disease.</p>

            

            </div>

          </div>


           <!-- Footer -- Citation
        ============================================= -->

        <div class="col_one_fourth">

            <div class="widget clearfix">

              <h5 class="mfront_h5">Citation</h5>
              <div class="footerdivider"></div>

              <p class="mfront_p">If you use the Mergeomics or PharmOmics web server or R package in published research, please be sure to cite appropriately.</p>

              <p class="mfront_p"> Ding, J., Blencowe, M., Nghiem, T., Ha, S., Chen, Y., Li, G., &amp; Yang, X. (2021). Mergeomics 2.0: a web server for multi-omics data integration to elucidate disease networks and predict therapeutics. <a href="https://academic.oup.com/nar/advance-article/doi/10.1093/nar/gkab405/6287846"> Nucleic Acids Research, 49(W1):W375-W387. doi: 10.1093/nar/gkab405.</a></p>
              
              <p class="mfront_p"> Arneson, D., Bhattacharya, A., Shu, L., Mäkinen, V., &amp; Yang, X. (2016). Mergeomics: A web server for identifying pathological pathways, networks, and key regulators via multidimensional data integration. <a href="http://www.ncbi.nlm.nih.gov/pubmed/27612452"> BMC Genomics, 17(1). doi:10.1186/s12864-016-3057-8</a></p>

	          <p class="mfront_p"> Chen Y., Diamante G., Ding J., Nghiem T., Yang J., Ha S., Cohn P., Arneson D., Blencowe M., Garcia J., Zaghari N., Patel P., Yang X. (2022). PharmOmics: A species- and tissue-specific drug signature database and gene-network-based drug repositioning tool. <a href="https://doi.org/10.1016/j.isci.2022.104052"> iScience, 25(4):104052. doi: 10.1016/j.isci.2022.104052.</a></p>
            

            </div>

          </div>

               <!-- Footer -- Acknowledgement
        ============================================= -->

          <div class="col_one_fourth">

            <div class="widget clearfix">

              <h5 class="mfront_h5">Acknowledgement</h5>
              <div class="footerdivider"></div>

              <p class="mfront_p"> We would like to thank Jessica Ding, Thien Nghiem, Montgomery Blencowe, Daniel Ha, Gaoyan Li, and Yen-Wei Chen for their contributions to the improved Mergeomics Web Server.</p>

            

            </div>

          </div>

           <!-- Footer -- Get in touch
        ============================================= -->

          <div class="col_one_fourth col_last">

            <div class="widget clearfix">

              <h5 class="mfront_h5">Get in Touch</h5>
              <div class="footerdivider"></div>

              <p class="mfront_p"> Please <a href="contact.php">contact us</a> if you have any comments, suggestions, or would like to request a resource to be added on the web server. </p>

            

            </div>

          </div>







    <!-- Bottom of footer
          ============================================= -->

          <div class="line"></div>

          <div class="col_one_half">
            <div class="widget clearfix">
            <h5 class="mfront_h5">Updates</h5>
            <div class="footerdivider" style="width: 14%;"></div>
            <p style="margin-bottom: 0.5%;font-size: 20px;">We will make monthly screens for updated public resources and make functionality updates as necessary, which will be reported here.</p>
            <p style="margin-bottom: 0.5%;font-size: 20px;">Updates for large sample data releases will be made yearly.</p>
            <h4 style="font-size: 22px;">June 2020</h4>
            <p style="font-size: 20px;margin-bottom: 1%;">Mergeomics Web Server 2.0 is released</p>
            <h4 style="font-size: 22px;margin-bottom: 4%;">Next major update for sample resources: December 2021</h4>
            </div>
          </div>

          <div class="line"></div>

          <div class="row clearfix">

            <div class="col-lg-7 col-md-6">
              <div class="widget clearfix">
                <div class="clear-bottommargin-sm">
                  <div class="row clearfix">

                    <div class="col-lg-6" style="padding-top: 10px;">
                      <div class="footer-big-contacts">
                        <span>Yang Lab - UCLA</span>
                        
                      </div>
                      <div class="d-block d-md-block d-lg-none bottommargin-sm"></div>
                    </div>

                    

                  </div>
                </div>
              </div>
              <div class="d-block d-md-block d-lg-none bottommargin-sm"></div>
            </div>

            <div class="col-lg-5 col-md-6">

              <div class="clearfix fright" data-class-xl="fright" data-class-lg="fright" data-class-md="fright" data-class-sm="" data-class-xs="">
                
                <!-- Social
          ============================================= -->
          
          <div id="top-social">
            <ul>
              <li><a href="https://groups.google.com/forum/#!forum/mergeomics-support" class="si-googlegroup"><span class="ts-icon"><i class="icon-group"></i></span><span class="ts-text">Google Group</span></a></li>
              <li><a href="https://yanglab.ibp.ucla.edu/" class="si-yanglab"><span class="ts-icon"><i class="icon-lab"></i></span><span class="ts-text">Lab of Xia Yang</span></a></li>
              <li><a href="https://www.bioconductor.org/packages/3.3/bioc/html/Mergeomics.html" class="si-bioconductor"><span class="ts-icon"><i class="icon-line2-music-tone"></i></span><span class="ts-text">Bioconductor Package</span></a></li>
              <li><a href="https://github.com/jessicading/mergeomics" class="si-github"><span class="ts-icon"><i class="icon-github-circled"></i></span><span class="ts-text">Github</span></a></li>
            </ul>
          </div><!-- #social end -->
              </div>

            </div>

          </div><!-- end of bottom of footer -->

        </div><!-- .footer-widgets-wrap end -->

      </div>
  </footer>

  
 <!-- Footer ends
    ============================================= -->

    



    

  <!-- Go To Top button
  ============================================= -->
  <div id="gotoTop" class="icon-angle-up"></div>

  <!-- External JavaScripts IMPORTANT!
  ============================================= -->
  <script src="include/js/jquery.js"></script>
  <script src="include/js/plugins.js"></script>

  <!-- Footer Scripts IMPORTANT!
  ============================================= -->
  <script src="include/js/functions.js"></script>


</body>

</html>