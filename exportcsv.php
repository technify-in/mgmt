<?php
/** Export an sql query to CSV.
 *  Copyright (c) Ajay Pal Singh Atwal
 */
require_once('init.php');
//require_once('session.php');
require_once('lib/csv.lib.php');

global $_SESSION, $db, $template;

$sql = $_SESSION['export_query'];

$rows = $db->getAll($sql);
if($rows)
{
  $db2csv = new export2CSV(",","\n");
  $csv = $db2csv->create_csv_file($rows);

  header("Content-type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=export.csv");
  header("Pragma: no-cache");
  header("Expires: 0");

  echo $csv;
}
else
{
  global $_SERVER;
  $ref = $_SERVER['HTTP_REFERER'];
  error_log("Export from $ref for $sql failed");
  $template->display('404.html');
}
?>
