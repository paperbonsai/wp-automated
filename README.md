# Automatic download and extraction of WordPress
This code automates the process of downloading, extracting, and setting up the latest version of WordPress in the current directory. 

## The main steps it performs are
1. Downloads the latest WordPress ZIP file from the official website.
2. Extracts the contents of the ZIP file into the current directory.
3. Moves the extracted WordPress files from the 'wordpress' folder to the current directory.
4. Deletes the 'wordpress' folder after moving the files.
5. Deletes the installer script (itself) after completing the setup process.
6. Redirects the user to the WordPress installation page.

## How to install and run this file
1. Upload the install.php file to the directory where you want your WordPress installation. For example, the root directory on the domain https://example.com
2. Run the file by typing https://example.com/install.php in your browser's address bar.
3. Now wait for the script to finish downloading and extracting the files, then you will be automatically redirected to the beginning of the actual WordPress installation
