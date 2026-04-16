<?php
$zipArchive = new ZipArchive();
$zipFilePath = 'd:/xamp1/htdocs/SIM/SIM.zip';
$extractToPath = 'd:/xamp1/htdocs/SIM/temp_zip_content';

if ($zipArchive->open($zipFilePath) === TRUE) {
    if (!is_dir($extractToPath)) {
        mkdir($extractToPath, 0755, true);
    }
    $zipArchive->extractTo($extractToPath);
    $zipArchive->close();
    echo "SUCCESS_UNZIP";
} else {
    echo "FAILED_UNZIP";
}
?>
