<?php
include "functions.php";
$ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
if (isset($_GET['oldsession']) ? $_GET['oldsession'] : null) {
	$old_meta_session = $_GET["oldsession"];
}

if (isset($_GET['rollback'])) {
	$rollback = $_GET['rollback'];
}


if (isset($_GET['metasessionID']) ? $_GET['metasessionID'] : null) {
	$meta_sessionID = $_GET["metasessionID"];
} else {
	$meta_sessionID = generateRandomString(10);
}



$fsession = $ROOT_DIR."Data/Pipeline/Resources/session/$meta_sessionID" . "_session.txt";
$session_write = NULL;
//if session.txt file does not exist or rollback is called from Meta_Moduleprogress.php
if (!file_exists($fsession) || $rollback == 'T') {
	$sessionfile = fopen($fsession, "w");
	$session_write .= "Pipeline:" . "\t" . "META" . "\n";
	$session_write .= "Mergeomics_Path:" . "\t" . "1" . "\n";
	$session_write .= "Pharmomics_Path:" . "\n";
	fwrite($sessionfile, $session_write);
	fclose($sessionfile);
	chmod($fsession, 0755);
}





?>


<div id="METAheader">
	<!-- Description ===================================================== -->
	<h4 style="color: #00004d; text-align: center; padding: 40px;font-size:25px;">
		This part of the pipeline is for merging multiple association studies <br> (GWAS, EWAS, TWAS, PWAS, or MWAS) into a single Meta MSEA.
	</h4>
</div>

<div class="container clearfix" id="myMETAContainer">
	<h4 style="color: #00004d; text-align: center; font-size:25px; margin-bottom: 40px;">
		Select the type of enrichment for Meta-MSEA
	</h4>
	<div class="col_half" style="text-align: center;margin-bottom: 0px;">

		<!-- <a href="#" class="runm button-inner" id="GWAS_type">GWAS <br> Enrichment</a> -->

		<div class="button-wrapper">
			<div id="GWAS_typeoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
				<a href="#" class="runm button-inner" id="GWAS_type"> GWAS <br> Enrichment</a>
			</div>
		</div>

	</div>

	<div class="col_half col_last" style="text-align: center;margin-bottom: 0px;">


		<div class="button-wrapper">
			<div id="Other_typeoutline" class="button-container button-outer" style="display: table;height: 125px;width: 100%;">
				<a href="#" class="runm button-inner" id="Other_type"> EWAS/TWAS/PWAS/MWAS <br> Enrichment</a>
			</div>
		</div>

		<!-- <a href="#" class="runm button-inner" id="Other_type">TWAS/EWAS/MWAS <br> Enrichment</a> -->

	</div>
</div>
<script type="text/javascript">
	var meta_string = "<?php echo $meta_sessionID; ?>";
	var n = localStorage.getItem('on_load_session');
	localStorage.setItem("on_load_session", meta_string);

	$(document).ready(function() {

		$('#session_id').html("<p style='margin: 0px;font-size: 12px;padding: 0px;'>Session ID: </p>" + meta_string).attr('tooltip','Save your session ID! Click to copy.');
		$('#session_id').css("padding", "17px 30px");

	});
</script>



<?php
if (isset($_GET['oldsession']) ? $_GET['oldsession'] : null) {
?>
	<script type="text/javascript">
		var string = "<?php echo $old_meta_session; ?>";
		console.log("META_buttons 103 session ID: " + string);


		$("#GWAS_type").on('click', function() {
			$("#myMETA").empty();

			$("#myMETA").load("/METASSEA_parameters.php?oldsession=" + string + "&metasessionID=" + meta_string, function() {
				$("#myMETA").hide().slideDown('slow');
				$("#METAtogglet").html('<i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i><div class="capital">Step 1 - Meta-MSEA (GWAS)</div></div>');
			});





		});



		$("#Other_type").on('click', function() {
			$("#myMETA").empty();

			$("#myMETA").load("/METAMSEA_parameters.php?oldsession=" + string + "&metasessionID=" + meta_string, function() {
				$("#myMETA").hide().slideDown('slow');
				$("#METAtogglet").html('<i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i><div class="capital">Step 1 - Meta-MSEA (EWAS/TWAS/PWAS/MWAS)</div></div>');
			});

		});
	</script>

<?php
} else {
?>
	<script type="text/javascript">
		$("#GWAS_type").on('click', function() {
			$("#myMETA").empty();

			$("#myMETA").load("/METASSEA_parameters.php?metasessionID=" + meta_string, function() {
				$("#myMETA").hide().slideDown('slow');
				$("#METAtogglet").html(`<i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i><div class="capital">Step 1 - Meta-MSEA (GWAS)</div></div>`);
			});





		});



		$("#Other_type").on('click', function() {
			$("#myMETA").empty();

			$("#myMETA").load("/METAMSEA_parameters.php?metasessionID=" + meta_string, function() {
				$("#myMETA").hide().slideDown('slow');
				$("#METAtogglet").html(`<i class="toggle-closed icon-remove-circle"></i><i class="toggle-open icon-remove-circle"></i><div class="capital">Step 1 - Meta-MSEA (EWAS/TWAS/PWAS/MWAS)</div></div>`);
			});

		});
	</script>

<?php
}

?>