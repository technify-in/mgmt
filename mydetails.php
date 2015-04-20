<?php
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 */

include_once('init.php');
include_once('session.php');
require_once('lib/adoDBPager.php');

$userselected = (int)$_SESSION['userid'];

//request for changing password?
if(checkPermission('changeownpassword') && isset($_POST['cpass']) && isset($_POST['npass1']) && isset($_POST['npass2']))
{
  if(strcmp($_POST['npass1'], $_POST['npass2']) == 0)
  {
    $npass = addslashes(trim($_POST['npass1']));
    $sql = "select enabled from users where username='".addslashes($_SESSION['username'])."' and id='".addslashes($_SESSION['userid'])."' and password=md5('".addslashes($_POST['cpass'])."')";
    $r = $db->getRow($sql);
    if($r)
    {
      if($r['enabled'])
      {
          $sql = "update users set mod_datetime=NOW(), password=md5('$npass') where username='".addslashes(trim($_SESSION['username']))."' and id='".addslashes($_SESSION['userid'])."'";
          $db->Execute($sql);
  
          addActionLog(_t("Password Changed"), 'users', addslashes($_SESSION['userid']));
          $template->assign( 'mydetails_message', _t('Password Updated.'));
      }
      else
      {
        $template->assign( 'mydetails_message', _t('Your account is suspended.'));
        addActionLog(_t("Password Change attempt on suspended account ").$r['username'], 'users', addslashes($_SESSION['userid']));
      }
    }
    else
    {
      $template->assign( 'mydetails_message', _t('Your username or password is incorrect. Please contact your administrator if you are unable to change your password.'));
    }
  }
  else
    $template->assign( 'mydetails_message', _t('Your new password and re-entered password do not match, please tyr again. Please contact your administrator if you are unable to change your password.'));
}
if(checkPermission('viewallactionlog'))
{
    //create selection for users
    $sql = "SELECT id, username  from users order by username ASC";
    $userdetails = $db->getAssoc($sql);
    ///HACK: id or user, same thing
    if(isset($_GET['user']))
        $userselected = $db->getOne("select id from users where id='".(int)$_GET['user']."'");
    else if(isset($_GET['id']))
        $userselected = $db->getOne("select id from users where id='".(int)$_GET['id']."'");

    $template->assign('useroptions', $userdetails);
    $template->assign('userselected', $userselected);

    $sql = "SELECT roles.role FROM `roles`, `users` where users.id='".$userselected."' and roles.id=users.role";

    $template->assign("userrole", $db->getOne($sql));
    $sql = "SELECT count(*) FROM `actionlog` where userid='".$userselected."'";
    $template->assign("useractionlogcount", $db->getOne($sql));
}

//create selection for type
$sql = "SELECT DISTINCT `table`, `table` FROM `actionlog` where `table` != ''";
$logtypedetails = $db->getAssoc($sql);
$template->assign('logtypeoptions', $logtypedetails);
$logtype = isset($_GET['logtype'])?addslashes($_GET['logtype']):null;
$template->assign('logtypeselected', $logtype);

$sql = "SELECT role FROM `roles` where id='".$_SESSION['role']."'";
$template->assign("myrole", $db->getOne($sql));

$sql = "SELECT count(*) FROM `actionlog` where userid='".$_SESSION['userid']."'";
$template->assign("myactionlogcount", $db->getOne($sql));

$sql = "SELECT `id`, `datetime` as '"._t('When')."', `comment` as '"._t('Comment')."', `table` as '"._t('Type')."' FROM `actionlog` where `userid`='".$userselected."'";
if($logtype)
    $sql .= " and `table`='$logtype'";
$sql .= " order by `datetime` DESC";

$pager = new adoDBPager($db,$sql);
$links = null;
$pager->setTableID('actionlogdetails');
$pager = "<table width=\"98%\" align=\"center\"><tr><td>".$pager->Render(10, $links)."</td></tr></table>";
$template->assign("resultpager",$pager);

$template->display('mydetails.html');
?>
