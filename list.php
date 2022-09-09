<?php
$path    = '/CCDAs';
if ($handle = opendir('./CCDAs')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            echo "<a href='./grabcontents.php?FileToGrab=$entry'>$entry</a>\n<br />";
        }
    }
    closedir($handle);
}
?>


