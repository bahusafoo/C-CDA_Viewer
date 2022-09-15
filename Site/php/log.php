<?php
    $FileName = $_GET['FileName'];
    $AccessLog = "../../Access Logs/AccessLog.log";
    $AccessUser = get_current_user();
    date_default_timezone_set("America/Chicago");
    global $AccessLog, $AccessUser;
    $AccessTimeStamp = date("Y-m-d h:i:sa");
    file_put_contents("../Access Logs/$AccessLog", "[ $AccessTimeStamp ] Username \"$AccessUser\" opened the file \"$FileName\".",  FILE_APPEND | LOCK_EX);
?>