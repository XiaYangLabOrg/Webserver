<!DOCTYPE html>
<html dir="ltr" lang="en-US">


<?php include_once("analyticstracking.php") ?>
 
 <!-- Includes all the font/styling/js sheets -->
<?php include_once("head.inc") ?>


<!-- START body of pipeline ----------------------------------------------------------------------------->
<body class="stretched">

<!-- Include the Run Mergeomics header ------------------------------------------------------------------>
  <?php include_once("headersecondary_contact.inc") ?>



<!-- Page title block ---------------------------------------------------------------------------------->
  <section id="page-title">

      <div class="margin_rm" style="margin-left: 0;">
			<div class="container clearfix" style="text-align: center;">
				<h2>Contact us!</h2>
				
			</div>
    </div>

</section>

<section id="content" style="margin-bottom: 0px;">

			<div class="content-wrap">

				<div class="container clearfix">

					<!-- Postcontent
					============================================= -->
					<div class="postcontent nobottommargin">



						<h3 class="instructiontext" style="padding: 0px;">Please use this form to send us any feedback, issues, or concerns you have with Mergeomics Web Server. <br> Please provide your session ID if applicable so we can better address your issue. <br> You should expect a response within 48 hours. Thank you!</h3>

						<br>
						<br>
						<br>

						<div class="form">

					

							<form class="nobottommargin" id="contact" name="contactform" action="#" method="post">

								<div class="form-process"></div>

								<div class="col_half">
									<label for="name">Name <small>*</small></label>
									<input type="text" id="name" name="name" value="" class="sm-form-control required" style="text-align: left;">
								</div>

								<div class="col_half col_last">
									<label for="email">Email <small>*</small></label>
									<input type="text" id="email" name="email" value="" class="required email sm-form-control" style="text-align: left;"> <!--type="email"-->
								</div>

								<div class="clear"></div>

								<div class="col_full">
									<label for="subject">Subject <small>*</small></label>
									<select id="subject" name="subject" class="sm-form-control valid">
										<option value="">-- Select One --</option>
										<option value="Mergeomics Support: Mergeomics Pipeline">Mergeomics Pipeline</option>
										<option value="Mergeomics Support: Pharmomics Pipeline">PharmOmics Pipeline</option>
										<option value="Mergeomics Support: Report a bug">Report a bug</option>
										<option value="Mergeomics Support: Suggestion/Feedback">Suggestion/Feedback</option>
										<option value="Mergeomics Support: Other">Other</option>
									</select>
								</div>


								<div class="clear"></div>

								<div class="col_full">
									<label for="message">Message <small>*</small></label>
									<textarea class="required sm-form-control" id="message" name="message" rows="6" cols="30"></textarea>
								</div>

								<div class="col_full">
									<button class="button button-3d nomargin" type="submit" id="submit" name="submit" value="submit" onClick="sendContact(); return false;">Send Message</button>
									<a href="#" id="successbutton" class="btn btn-success" data-notify-type="success" data-notify-msg="<i class=icon-ok-sign></i> Message Sent Successfully!" onclick="SEMICOLON.widget.notifications(this); return false;" style="visibility: hidden;"></a>
								</div>

							</form>
						</div>

					</div><!-- .postcontent end -->

					<!-- Sidebar
					============================================= -->
					<div class="sidebar col_last nobottommargin">

						<address style="font-size: 25px;">
							<strong>Lab Location:</strong><br>
							Terasaki Life Science Building<br>
							Los Angeles, CA 90095<br>
						</address>
					

						<br>
						<br>

						      
          <div id="top-social">
            <ul>
              <li><a href="https://groups.google.com/forum/#!forum/mergeomics-support" class="si-googlegroup"><span class="ts-icon"><i class="icon-group"></i></span><span class="ts-text">Google Group</span></a></li>
              <li><a href="https://yanglab.ibp.ucla.edu/" class="si-yanglab"><span class="ts-icon"><i class="icon-lab"></i></span><span class="ts-text">Lab of Xia Yang</span></a></li>
              <li><a href="https://www.bioconductor.org/packages/3.3/bioc/html/Mergeomics.html" class="si-bioconductor"><span class="ts-icon"><i class="icon-line2-music-tone"></i></span><span class="ts-text">Bioconductor Package</span></a></li>
              <li><a href="#" class="si-github"><span class="ts-icon"><i class="icon-github-circled"></i></span><span class="ts-text">Github</span></a></li>
            </ul>
          </div><!-- #social end -->


          
              </div>


              

					

					</div><!-- .sidebar end -->

				</div>

			</div>

		</section>




</body>
</html>

<script type="text/javascript">

	function sendContact(){
		
		var form_data = new FormData(document.getElementById('contact'));

			$.ajax({
							'url': 'contactformemail.php',
							'type': 'POST',
							'data': form_data,
							processData: false,
							contentType: false,
							'success': function(data){
								console.log("done")
								$("#successbutton").trigger('click');
								$('#contact').trigger("reset");
							},error: function(xhr, status, error) {
								console.log(error);
								var err = eval(xhr.responseText);
								alert(err.Message);
							}	
						});
			
				return false;
        }

</script>

  <!-- External JavaScripts IMPORTANT!
  ============================================= -->
  <script src="include/js/jquery.js"></script>
  <script src="include/js/plugins.js"></script>

  <!-- Footer Scripts IMPORTANT!
  ============================================= -->
  <script src="include/js/functions.js"></script>