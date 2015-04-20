<?php
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 *
 */

require_once('init.php');
require_once('session.php');
require_once('lib/modules.php');

$menufields = null;

foreach (glob("modules/*.php") as $filename)
{
  require_once($filename);
  $m = basename($filename, '.php');
  $f1 = $m.'_getMainMenu';
  $f2 = $m.'_getMainMenuItems';

  if(function_exists($f1) and function_exists($f2))
  AddMenuForModule($m, $menufields);
}

$template->assign("menufields", $menufields);
$template->assign('username', $_SESSION['username']);

$template->display('menu.html');
?>
