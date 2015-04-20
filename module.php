<?php
/**
 * Copyright (C) 2008  Ajay Pal Singh Atwal
 *
 */

require_once('init.php');
//require_once('session.php');

///NOTE: Check for sec issues
$to = isset($_GET['to'])?addslashes(trim(strip_tags($_GET['to']))):exit(1);
$to = explode('.',$to);
if(!is_file("modules/".$to[0].".php"))
{
  $template->assign('to', ".$to[0].");
  $template->display('404.html');
  //echo 'OOPS: The Requested Page was not found.';
  exit(1);
}
require_once("modules/".$to[0].".php");
$call = $to[0].(isset($to[1])?'_'.$to[1]:'_default');
if(is_callable($call))
  return $call();
else
{
  $template->assign('to', ".$to[0].");
  $template->display('404.html');
  //echo 'OOPS: The Requested Page was not found.';
  exit(1);
}
?>
