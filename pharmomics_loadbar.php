	<?php
	error_reporting(E_ERROR | E_PARSE);
	if (isset($_GET['sessionID'])) {
		$sessionID = $_GET['sessionID'];
	}
	$networkfile = "./Data/Pipeline/Results/shinyapp2/" . $sessionID . "out.txt";
	$jaccardfile = "./Data/Pipeline/Results/shinyapp3/" . $sessionID . "out.txt";
	if (file_exists($ROOT_DIR . "Data/Pipeline/Resources/shinyapp2_temp/$sessionID" . "_is_done")) {
        $outfile = $ROOT_DIR . "Data/Pipeline/Results/shinyapp2/" . $sessionID . "out.txt";
        $outfile_f = fopen($outfile, "w");
        fwrite($outfile_f, "100%");
        fclose($outfile_f);
    }
	if (file_exists($networkfile)) {
		$file = $networkfile;
			$line = '';

		$f = fopen($file, "r");
		$cursor = -1;

		fseek($f, $cursor, SEEK_END);
		$char = fgetc($f);

		/**
		 * Trim trailing newline chars of the file
		 */
		while ($char === "\n" || $char === "\r") {
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}

		/**
		 * Read until the start of file or first newline char
		 */
		while ($char !== false && $char !== "\n" && $char !== "\r") {
			/**
			 * Prepend the new char
			 */
			$line = $char . $line;
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}
		$line = preg_replace('/\s+/', '', $line);
		print $line;
	}

	if (file_exists($jaccardfile)) {
		$file = $jaccardfile;
	

		$line = '';

		$f = fopen($file, "r");
		$cursor = -1;

		fseek($f, $cursor, SEEK_END);
		$char = fgetc($f);

		/**
		 * Trim trailing newline chars of the file
		 */
		while ($char === "\n" || $char === "\r") {
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}

		/**
		 * Read until the start of file or first newline char
		 */
		while ($char !== false && $char !== "\n" && $char !== "\r") {
			/**
			 * Prepend the new char
			 */
			$line = $char . $line;
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}
		$line = preg_replace('/\s+/', '', $line);
		print $line;

	}
	
	?>