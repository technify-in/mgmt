<?php
// +----------------------------------------------------------------------+
// | $Id: functions.php,v 1.4 2004/02/04 07:40:49 wolf Exp $
// +----------------------------------------------------------------------+
// | Project: Restkultur WebSite
// | Page/Section: Backup MySQL Database Tables
// | Modul: functions
// | $Author: wolf $
// | $Revision: 1.4 $
// | Last Modified $Date: 2004/02/04 07:40:49 $
// | $State: Exp $
// +----------------------------------------------------------------------+
// | Copyright (c) 2003, 2004 by Alain Wolf - Zurich, Switzerland.
// | Some parts Copyright (c) 2007 APS Atwal
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or
// | modify it under the terms of the GNU General Public License
// | as published by the Free Software Foundation; either version 2
// | of the License, or (at your option) any later version.
// |
// | This program is distributed in the hope that it will be useful,
// | but WITHOUT ANY WARRANTY; without even the implied warranty of
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// | GNU General Public License for more details.
// | You should have received a copy of the GNU General Public License
// | along with this program in the file LICENSE.TXT;
// | if not, write to the Free Software Foundation, Inc.,
// | 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// +----------------------------------------------------------------------+

function backupdb($database)
{

  addActionLog("Dumping database '$database'", 'system', 0);

  $statements = "#\n".
                "# Dumping database $database\n".
                "# ".date('Y-m-d H:i:s');
  $sql = "show tables";

  $result = mysql_query($sql);
  if ($result == FALSE) {
    $statements .= "## No tables ";
  }
  else
  {
    while ($row = mysql_fetch_row($result)) {
      $statements .= make_sql($row[0]);
    }
  }

  return $statements;
}

function sql_addslashes($a_string = '', $is_like = FALSE)
{
  /*
    Better addslashes for SQL queries.
    Taken from phpMyAdmin.
  */
    if ($is_like) {
        $a_string = str_replace('\\', '\\\\\\\\', $a_string);
    } else {
        $a_string = str_replace('\\', '\\\\', $a_string);
    }
    $a_string = str_replace('\'', '\\\'', $a_string);

    return $a_string;
} // function sql_addslashes($a_string = '', $is_like = FALSE)

function backquote($a_name)
{
  /*
    Add backqouotes to tables and db-names in
    SQL queries. Taken from phpMyAdmin.
  */
    if (!empty($a_name) && $a_name != '*') {
        if (is_array($a_name)) {
             $result = array();
             reset($a_name);
             while(list($key, $val) = each($a_name)) {
                 $result[$key] = '`' . $val . '`';
             }
             return $result;
        } else {
            return '`' . $a_name . '`';
        }
    } else {
        return $a_name;
    }
} // function backquote($a_name, $do_it = TRUE)


function make_sql($table)
{
  /*
    Reads the Database table in $table and creates
    SQL Statements for recreating structure and data
  */

    $sql_statements  = "";

    // Add SQL statement to drop existing table
    $sql_statements .= "\n";
    $sql_statements .= "\n";
    $sql_statements .= "#\n";
    $sql_statements .= "# Delete any existing table " . backquote($table) . "\n";
    $sql_statements .= "#\n";
    $sql_statements .= "\n";
    $sql_statements .= "DROP TABLE IF EXISTS " . backquote($table) . ";\n";

    // Table structure

    // Comment in SQL-file
    $sql_statements .= "\n";
    $sql_statements .= "\n";
    $sql_statements .= "#\n";
    $sql_statements .= "# Table structure of table " . backquote($table) . "\n";
    $sql_statements .= "#\n";
    $sql_statements .= "\n";

    // Get table structure
    $query = "SHOW CREATE TABLE " . backquote($table);

    $result = mysql_query($query);
    if ($result == FALSE) {
      addActionLog("Error (". mysql_errno() .") getting table structure of $table!", 'system', 0, _ALOG_WARN);
    } else {
      if (mysql_num_rows($result) > 0) {
        $sql_create_arr = mysql_fetch_array($result);
        $sql_statements .= $sql_create_arr[1];
      }
      mysql_free_result($result);
      $sql_statements .= " ;";
    } // ($result == FALSE)

    // Table data contents

    // Get table contents
    $query = "SELECT * FROM " . backquote($table);
    $result = mysql_query($query);
    if ($result == FALSE) {
      addActionLog("Error (". mysql_errno() .") getting records of $table!", 'system', 0, _ALOG_WARN);
    } else {
      $fields_cnt = mysql_num_fields($result);
      $rows_cnt   = mysql_num_rows($result);
    } // if ($result == FALSE)

    // Comment in SQL-file
    $sql_statements .= "\n";
    $sql_statements .= "\n";
    $sql_statements .= "#\n";
    $sql_statements .= "# Data contents of table " . $table . " (" . $rows_cnt . " records)\n";
    $sql_statements .= "#\n";

    // Checks whether the field is an integer or not
    for ($j = 0; $j < $fields_cnt; $j++) {
      $field_set[$j] = backquote(mysql_field_name($result, $j));
      $type          = mysql_field_type($result, $j);
      if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
        $type == 'bigint'  ||$type == 'timestamp') {
        $field_num[$j] = TRUE;
      } else {
        $field_num[$j] = FALSE;
      }
    } // end for

    // Sets the scheme
    $entries = 'INSERT INTO ' . backquote($table) . ' VALUES (';
    $search     = array("\x00", "\x0a", "\x0d", "\x1a");  //\x08\\x09, not required
    $replace    = array('\0', '\n', '\r', '\Z');
    $current_row  = 0;
    while ($row = mysql_fetch_row($result)) {
      $current_row++;
      for ($j = 0; $j < $fields_cnt; $j++) {
        if (!isset($row[$j])) {
          $values[]     = 'NULL';
        } else if ($row[$j] == '0' || $row[$j] != '') {
          // a number
          if ($field_num[$j]) {
            $values[] = $row[$j];
          }
          else {
            $values[] = "'" . str_replace($search, $replace, sql_addslashes($row[$j])) . "'";
          } //if ($field_num[$j])
      } else {
          $values[]     = "''";
        } // if (!isset($row[$j]))
      } // for ($j = 0; $j < $fields_cnt; $j++)
      $sql_statements .= " \n" . $entries . implode(', ', $values) . ') ;';
      unset($values);
    } // while ($row = mysql_fetch_row($result))
    mysql_free_result($result);

    // Create footer/closing comment in SQL-file
    $sql_statements .= "\n";
    $sql_statements .= "#\n";
    $sql_statements .= "# End of data contents of table " . $table . "\n";
    $sql_statements .= "# --------------------------------------------------------\n";
    $sql_statements .= "\n";
    return $sql_statements;
} //function make_sql($table)
?>