<?php
$output = shell_exec('git status 2>&1');
echo "<pre>$output</pre>";
?>
