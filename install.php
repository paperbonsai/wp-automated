<?php
echo '<span style="color:green">Extracting WordPress...</span>' . PHP_EOL;

// Download the latest WordPress ZIP file
download_wordpress_zip();

// Extract and move WordPress files
if (extract_and_move_files()) {
    // Remove 'wordpress' folder and installer
    remove_directory("wordpress");
    unlink("./install.php");

    // Redirect to the WordPress installation page
    echo '<meta http-equiv="refresh" content="1;url=index.php" />';
} else {
    echo 'Oops, something went wrong.';
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
        $zip->extractTo("./");
        $zip->close();
        unlink("wp.zip");

        // Move files from 'wordpress' folder to the current folder
        move_files("wordpress", ".");

        return true;
    }

    return false;
}

function move_files($source, $destination)
{
    $dir = opendir($source);
    @mkdir($destination);

    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($source . '/' . $file)) {
                move_files($source . '/' . $file, $destination . '/' . $file);
            } else {
                echo "[FILE] " . $source . '/' . $file . " -> " . $destination . '/' . $file . PHP_EOL;
                rename($source . '/' . $file, $destination . '/' . $file);
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
