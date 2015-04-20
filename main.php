<?php
/**
 */

include_once('init.php');
include_once('session.php');
require_once('lib/adoDBPager.php');
global $db, $template;

$month = isset($_GET['month'])?addslashes(strip_tags($_GET['month'])):date("m/Y", mktime(0, 0, 0, date("m"), date("d")-1,   date("Y")));
$month = explode('/', $month);
$template->assign('month', $month[0].'/'.$month[1]);

$infoblocks = null;
foreach (glob("modules/*.php") as $filename)
{
  require_once($filename);
  $f = basename($filename, '.php').'_infoblock';
  if(function_exists($f))
  {
    $c = $f();
    if($c) $infoblocks[] = $c;
//echo '<br /><b>Call: '.$f.'</b><hr />'.print_r($c).' <hr />';
  }
}
$template->assign('infoblocks', $infoblocks);
$template->display('main.html');
?>
