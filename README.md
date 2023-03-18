# wp-automated
This code automates the process of downloading, extracting, and setting up the latest version of WordPress in the current directory. 
The main steps it performs are:

1. Downloads the latest WordPress ZIP file from the official website.
2. Extracts the contents of the ZIP file into the current directory.
3. Moves the extracted WordPress files from the 'wordpress' folder to the current directory.
4. Deletes the 'wordpress' folder after moving the files.
5. Deletes the installer script (itself) after completing the setup process.
6. Redirects the user to the WordPress installation page.
