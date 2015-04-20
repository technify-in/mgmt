<?php
/** Export_Query is used by exportcsv.php to export the SQL to CSV.
    Not too elegant but will fix later
 */
function registerExportQuery($sql)
{
  global $_SESSION;
  $_SESSION['export_query'] = $sql;
}

function registerExportQueryPDF($sql, $title = null)
{
  global $_SESSION;
  $_SESSION['export_query_pdf'] = $sql;
  $_SESSION['export_pageheader_pdf'] = $title;
}
?>