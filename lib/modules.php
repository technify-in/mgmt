<?php
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 */

function AddMenuforModule($module, &$menufields)
{
  require_once("modules/$module.php");

  $getMainMenu = $module."_getMainMenu";
  $getMainMenuItems = $module."_getMainMenuItems";
  $menufields[$getMainMenu()][] = $getMainMenuItems();
}

?>