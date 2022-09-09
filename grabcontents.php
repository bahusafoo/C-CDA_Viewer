<?php
$FileToGrab = $_GET['FileToGrab'];
$FileContents = file_get_contents("./CCDAs/$FileToGrab");
echo $FileContents;
?>