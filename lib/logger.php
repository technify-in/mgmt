<?php
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 */

define('_ALOG_INFO', 0);
define('_ALOG_WARN', 1);
define('_ALOG_URG' , 2);

function addActionLog($comment, $table = '', $fieldid = 0,  $type = _ALOG_INFO)
{
    global $db;

    $userid = isset($_SESSION['userid'])?$_SESSION['userid']:0;

    $sql = "INSERT INTO `actionlog` ( `id` , `userid` , `datetime` , `comment` , `table` , `fieldid`, `type` ) VALUES ( NULL , '$userid', NOW( ) , '".addslashes($comment)."', '".addslashes($table)."', '".(int)$fieldid."', '".(int)$type."')";
    $db->Execute($sql);
}
?>