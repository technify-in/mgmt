<?php
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 */

require_once("init.php");
require_once("lib/chat.php");

if( isset($_SESSION['username']) && $_SESSION['username'] != '' )
{
  header("Location: menu.php \n\n");
  exit;
}

if(isset($_POST['username']) && isset($_POST['password']))
{
  //$db->debug = 1;
  $sql = "select id, username, lastlogin, local, enabled, role from users where username = '".addslashes(strip_tags($_POST['username']))."' and password=md5('".addslashes(trim($_POST['password']))."')";
//echo $sql;
//echo "<t=\"$sql\" />";
  $r = $db->getRow($sql);
  if($r)
  {
    if($r['enabled'])
    {
        $sql = "update users set lastlogin=NOW() where username like '".addslashes(strip_tags($_POST['username']))."' and password=md5('".addslashes($_POST['password'])."') and id='".$r['id']."'";
        $db->Execute($sql);
        $_SESSION['userid']    = $r['id'];
        $_SESSION['username']  = $r['username'];
        $_SESSION['lastlogin'] = $r['lastlogin'];
        $_SESSION['local']     = $r['local'];
        $_SESSION['role']      = $r['role'];

        addActionLog(_t('Login').': ('._t('IP').': '.$_SERVER['REMOTE_ADDR'].', '._t('User Agent').': '.$_SERVER['HTTP_USER_AGENT'].')', 'users', $r['id']);

        //will add slight delay
        sendChatMessage("User ".$r['username']." logged in");
        header("Location: menu.php \n\n");
        exit;
    }
    $template->assign( 'login_message', _t('Your account is suspended.'));
    addActionLog(_t("Log in attempt on suspended account ").$r['username'], 'users', $r['id']);
    $template->display('login.html');
  }
  else
  {
    $template->assign( 'login_message', _t('Your username or password is incorrect. Please contact your administrator if you are unable to login.'));
    $template->display('login.html');
  }
}
else
{
  $template->display('login.html');
}
?>
