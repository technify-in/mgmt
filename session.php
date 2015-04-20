<?php
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 *
 */

if( !isset($_SESSION['username']) || $_SESSION['username'] == '' )
{
  header("Location: login.php \n\n");
  exit();
}
?>