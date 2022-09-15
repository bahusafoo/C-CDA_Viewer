<?php
    $FileName = $_GET['FileName'];
    $SolutionVersion = $_GET['SolutionVersion'];
    $AccessLog = "../../Access Logs/AccessLog.log";
    $AccessUser = get_current_user();
    date_default_timezone_set("America/Chicago");
    global $AccessLog, $AccessUser;
    $AccessTimeStamp = date("Y-m-d h:i:sa");
    file_put_contents("../Access Logs/$AccessLog", "[ $AccessTimeStamp ] [ $SolutionVersion ] Username \"$AccessUser\" opened the file \"$FileName\".\r\n",  FILE_APPEND | LOCK_EX);
?>