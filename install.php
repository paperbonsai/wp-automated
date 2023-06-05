<!DOCTYPE html>
<html>
<head>
	<title>WordPress Installer</title>
	<style>
		#popup {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			z-index: 9999;
			text-align: center;
		}
		#popup-inner {
			display: inline-block;
			margin-top: 10%;
			background-color: white;
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
		}
		#progress {
			display: none;
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			z-index: 9999;
			text-align: center;
		}
		#progress-bar {
			display: inline-block;
			width: 50%;
			height: 20px;
			background-color: #ddd;
			border-radius: 10px;
			overflow: hidden;
		}
		#progress-bar-fill {
			display: block;
			height: 100%;
			background-color: #4CAF50;
			transition: width 0.5s;
		}
	</style>
</head>
<body>

<?php
if (isset($_GET['install'])) {
	echo '<span style="color:green">Extracting WordPress...</span>' . PHP_EOL;

	// Download the latest WordPress ZIP file
	download_wordpress_zip();

	// Extract and move WordPress files
	if (extract_and_move_files()) {
		// Remove 'wordpress' folder and installer
		remove_directory("wordpress");
		unlink("./install.php");

		// Show the progress bar
		echo '<div id="progress"><div id="progress-bar"><div id="progress-bar-fill"></div></div></div>' . PHP_EOL;

		// Refresh the page after 5 seconds
		echo '<script>setTimeout(function() { location.reload(); }, 5000);</script>' . PHP_EOL;
	} else {
		echo 'Oops, something went wrong.';
	}
} else {
	// Show the popup
	echo '<div id="popup">
			<div id="popup-inner">
				<h2>Install WordPress?</h2>
				<p>This will download and install the latest version of WordPress.</p>
				<button id="yes">Yes</button>
				<button id="no">No</button>
			</div>
		</div>';

	echo '<script>
			// Show the popup when the page loads
			window.onload = function() {
				document.getElementById("popup").style.display = "block";
			};

			// Handle user input
			document.getElementById("yes").onclick = function() {
				document.getElementById("popup").style.display = "none";
				// Run the PHP script
				window.location.href = "index.php?install=true";
			};
			document.getElementById("no").onclick = function() {
				document.getElementById("popup").style.display = "none";
				// Refresh the page
				location.reload();
			};
		</script>';
}

function download_wordpress_zip()
{
	file_put_contents("wp.zip", file_get_contents("https://wordpress.org/latest.zip"));
}

function extract_and_move_files()
{
	$zip = new ZipArchive();
	$res = $zip->open("wp.zip");
	if ($res === true) {
		// Extract ZIP file
		$total_files = $zip->numFiles;
		$extracted_files = 0;
		$zip->extractTo("./");
		$zip->close();
		unlink("wp.zip");

		// Move files from 'wordpress' folder to the current folder
		move_files("wordpress", ".", $total_files, $extracted_files);

		return true;
	}

	return false;
}

function move_files($source, $destination, $total_files, &$extracted_files)
{
	$dir = opendir($source);
	@mkdir($destination);

	while (false !== ($file = readdir($dir))) {
		if (($file != '.') && ($file != '..')) {
			if (is_dir($source . '/' . $file)) {
				move_files($source . '/' . $file, $destination . '/' . $file, $total_files, $extracted_files);
			} else {
				// Move file
				rename($source . '/' . $file, $destination . '/' . $file);
				$extracted_files++;

				// Update progress bar
				$percent = floor(($extracted_files / $total_files) * 100);
				echo '<script>document.getElementById("progress-bar-fill").style.width = "' . $percent . '%";</script>' . PHP_EOL;
			}
		}
	}
	closedir($dir);
}

function remove_directory($dir)
{
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (is_dir($dir . "/" . $object)) {
					remove_directory($dir . "/" . $object);
				} else {
					unlink($dir . "/" . $object);
				}
			}
		}
		rmdir($dir);
	}
}
?>

</body>
</html>
