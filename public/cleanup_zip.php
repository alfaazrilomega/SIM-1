<?php
function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
        return;
    }
    $files = scandir($dirPath);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            if (is_dir("$dirPath/$file")) {
                deleteDir("$dirPath/$file");
            } else {
                unlink("$dirPath/$file");
            }
        }
    }
    rmdir($dirPath);
}

deleteDir('d:/xamp1/htdocs/SIM/temp_zip_content');
echo "CLEANUP_SUCCESS";
?>
