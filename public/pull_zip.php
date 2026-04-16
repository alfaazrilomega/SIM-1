<?php
function recursive_copy($src, $dst) {
    if (is_dir($src)) {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                recursive_copy("$src/$file", "$dst/$file");
            }
        }
    } else if (file_exists($src)) {
        copy($src, $dst);
    }
}

$srcDir = 'd:/xamp1/htdocs/SIM/temp_zip_content/SIM';
$dstDir = 'd:/xamp1/htdocs/SIM';

// Hapus folder temp_zip_content dari iterasi biar tidak loop ke dirinya sendiri
recursive_copy($srcDir, $dstDir);
echo "MERGE_SUCCESS";
?>
