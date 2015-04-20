<?php
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 */

require("init.php");
require_once("lib/chat.php");

//fix for if not logged in
if(isset($_SESSION['userid']) || isset($_SESSION['username']))
{
  addActionLog(_t('Logout').': ('._t('IP').': '.$_SERVER['REMOTE_ADDR'].', '._t('User Agent').': '.$_SERVER['HTTP_USER_AGENT'].')', 'users', $_SESSION['userid']);

  //will add slight delay
  sendChatMessage("User ".$_SESSION['username']." logged out");

  unset($_SESSION['userid']);
  unset($_SESSION['username']);
  unset($_SESSION['lastlogin']);
  unset($_SESSION['local']);
  unset($_SESSION['role']);
  unset($_SESSION['write']);
}
header("Location:login.php \n\n");
exit();
?>
