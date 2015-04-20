<?php 
/* Modified to support user display
 */
/*
  V4.66 28 Sept 2005  (c) 2000-2005 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence.
  
  Some pretty-printing by Chris Oxenreider <oxenreid@state.net>
*/ 
  
// specific code for tohtml
GLOBAL $gSQLMaxRows,$gSQLBlockRows,$ADODB_ROUND;

$ADODB_ROUND=4; // rounding
$gSQLMaxRows = 1000; // max no of rows to download
$gSQLBlockRows=20; // max no of rows per table block

// RecordSet to HTML Table
//------------------------------------------------------------
// Convert a recordset to a html table. Multiple tables are generated
// if the number of rows is > $gSQLBlockRows. This is because
// web browsers normally require the whole table to be downloaded
// before it can be rendered, so we break the output into several
// smaller faster rendering tables.
//
// $rs: the recordset
// $ztabhtml: the table tag attributes (optional)
// $zheaderarray: contains the replacement strings for the headers (optional)
//
//  USAGE:
//      include('adodb.inc.php');
//      $db = ADONewConnection('mysql');
//      $db->Connect('mysql','userid','password','database');
//      $rs = $db->Execute('select col1,col2,col3 from table');
//      rs2html($rs, 'BORDER=2', array('Title1', 'Title2', 'Title3'));
//      $rs->Close();
//
// RETURNS: number of rows displayed


function userrs2html(&$rs, $links, $ztabhtml=false, $zheaderarray=false, $htmlspecialchars=true, $echo = true, $tableid, $srnoenabled, $rowsperpage, $currentpageno)
{
  $srno = 0;
  if($srnoenabled) //show sr no, find first sr no
    $srno = ($currentpageno-1)*$rowsperpage;
  
  $s ='';$rows=0;$docnt = false;
  GLOBAL $gSQLMaxRows,$gSQLBlockRows,$ADODB_ROUND;

  if (!$rs)
  {
    printf(ADODB_BAD_RS,'userrs2html');
    return false;
  }

  /*if (! $ztabhtml)*/ $ztabhtml = " data-mode=\"columntoggle\" data-role=\"table\" data-filter=\"true\" data-input=\"#filterTable$tableid-input\" class=\"ui-responsive\" BORDER='0' WIDTH='100%' id=\"$tableid\"";
  //else $docnt = true;
  $typearr = array();
  
  $ncols = $rs->FieldCount();
  //two columns for editing
  //$displcols = $ncols+2;
  $hdr = "<form><input id=\"filterTable$tableid-input\" data-type=\"search\" data-filter-placeholder=\"Find...\"></form>";
  $hdr .= "<div class=\"PagerTable\"><TABLE $ztabhtml><thead>\n<tr>\n\n";
  if($srnoenabled) //show sr no
    $hdr .= "<TH class='aodb_table_th'>Sr No</TH>";

  //skip first
  for ($i=1; $i < $ncols; $i++)
  {
    //do not process those cols where we have an associated special column; need optimisation
    $skip = false;
    if($links)
    {
        foreach($links as $l)
        {
            if(isset($l['dbcolumn']) && $l['dbcolumn'] == $i)
            {
                $skip = true;
                break;
            }
        }
    }

    if(!$skip)
    {
      $field = $rs->FetchField($i);
      if ($field)
      {
              if ($zheaderarray) $fname = $zheaderarray[$i];
              else $fname = htmlspecialchars($field->name);   
              $typearr[$i] = $rs->MetaType($field->type,$field->max_length);
              //print " $field->name $field->type $typearr[$i] ";
      }
      else
      {
              $fname = 'Field '.($i+1);
              $typearr[$i] = 'C';
      }
      if (strlen($fname)==0) $fname = '&nbsp;';
      $hdr .= "<TH class='aodb_table_th' data-priority=\"$i\">$fname</TH>";
    }
  }
  //add special columns
  if($links)
  {
    foreach($links as $l)
        $hdr .= "<th class='aodb_table_th' width='10'>".$l['label']."</th>\n";
  }
  $hdr .= "\n</tr></thead><tbody>";
  if ($echo) print $hdr."\n\n";
  else $html = $hdr;

  // smart algorithm - handles ADODB_FETCH_MODE's correctly by probing...
  $numoffset = isset($rs->fields[0]) ||isset($rs->fields[1]) || isset($rs->fields[2]);
//for row calss evenrow, oddrow
$rowcnt = 0;
while (!$rs->EOF)
{
  $rowcnt++;
  $s .= "<TR valign='top'";
  $s .= ($rowcnt%2==0?" class='aodb_evenrow'":" class='aodb_oddrow'");
  $s .= ">\n";
  if($srnoenabled) //show sr no
  {
    $srno++;
    $s .= "<TD class='aodb_table_td'>$srno</TD>";
  }
  //skip first as it is id
  for ($i=1; $i < $ncols; $i++)
  {
      //do not process those cols where we have an associated special column
      $skip = false;
    if($links)
    {
      foreach($links as $l)
      {
        if(isset($l['dbcolumn']) && $l['dbcolumn'] == $i)
        {
          $skip = true;
          break;
        }
      }
    }
      if(!$skip)
      {
        if ($i===0) $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
        else $v = ($numoffset) ? $rs->fields[$i] : next($rs->fields);

        $type = $typearr[$i];
        switch($type)
        {
            case 'D':
              if (empty($v)) $s .= "<TD class='aodb_table_td'> &nbsp; </TD>\n";
              else if (!strpos($v,':'))
              {
                $s .= " <TD class='aodb_table_td'>".$rs->UserDate($v,"D d, M Y") ."&nbsp;</TD>\n";
              }
              break;
            case 'T':
              if (empty($v)) $s .= "<TD class='aodb_table_td'> &nbsp; </TD>\n";
              else $s .= "    <TD class='aodb_table_td'>".$rs->UserTimeStamp($v,"D d, M Y, h:i:s") ."&nbsp;</TD>\n";
            break;
            
            case 'N':
//               if (abs($v) - round($v,0) < 0.00000001)
//                       $v = round($v);
//               else
//                       $v = round($v,$ADODB_ROUND);
            case 'I':
                $s .= " <TD class='aodb_table_td' align='right'>".stripslashes((trim($v))) ."</TD>\n";              
            break;

            default:
              if ($htmlspecialchars) $v = htmlspecialchars(trim(_t($v)));
              $v = trim($v);
              if (strlen($v) == 0) $v = '&nbsp;';
              $s .= " <TD class='aodb_table_td'>". str_replace("\n",'<br />',stripslashes($v)) ."</TD>\n";
            }
          }
        } // for

        //add special additional columns
        if($links)
        {
            foreach($links as $l)
            {
            $s .= "<td class='aodb_table_td'><a class=\"ui-btn ui-mini\"";
            if(!is_array($l['name']))
            {
                if(isset($l['class']))
                   $s .= " class='".$l['class']."'";
                if(isset($l['target']))
                   $s .= " target='".$l['target']."'";
                if(isset($l['rawjs']) && isset($l['onclick']))
                {
                    $thestr = sprintf($l['onclick'], $rs->fields['id']);
                    $s .= " onclick=\"".$thestr."\"";
                }
                else if(isset($l['onclick']))
                    $s .= " onclick=\"return confirm('".$l['onclick']."')\"";
                $s .=" href='".$l['link']."id=".$rs->fields['id']."'>".$l['name'];
            }
            else
            {
                if($l['onclick'][$rs->fields[$l['dbcolumn']]])
                $s .= " onclick=\"return confirm('".$l['onclick'][$rs->fields[$l['dbcolumn']]]."')\"";
                $s .=" href='".$l['link']."id=".$rs->fields['id']."'>".$l['name'][$rs->fields[$l['dbcolumn']]];
            }
            $s .= "</a></td>\n";
            }
        }

        $s .= "</TR>\n\n";
                  
        $rows += 1;
        if ($rows >= $gSQLMaxRows)
        {
          $rows = "<p>Truncated at $gSQLMaxRows</p>";
          break;
        } // switch

        $rs->MoveNext();

// additional EOF check to prevent a widow header
        if (!$rs->EOF && $rows % $gSQLBlockRows == 0)
        {
        //if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
          if ($echo) print $s . "</tbody></TABLE>\n\n";
          else $html .= $s ."</tbody></TABLE>\n\n";
          $s = $hdr;
        }
    } // while

    if ($echo) print $s."</tbody></TABLE>\n\n";
    else $html .= $s."</tbody></TABLE>\n\n";
    
    if ($docnt) if ($echo) print "<H2>".$rows.' '._t('Rows')."</H2>";
    
    return ($echo) ? $rows : $html;
 }
 
// pass in 2 dimensional array
function userarr2html(&$arr,$ztabhtml='',$zheaderarray='')
{
        if (!$ztabhtml) $ztabhtml = "BORDER='1' class='aodb_table'";
        
        $s = "<TABLE $ztabhtml>";//';print_r($arr);

        if ($zheaderarray) {
                $s .= '<TR>';
                for ($i=0; $i<sizeof($zheaderarray); $i++) {
                        $s .= " <TH class='aodb_table_th'>{$zheaderarray[$i]}</TH>\n";
                }
                $s .= "\n</TR>";
        }
        
        for ($i=0; $i<sizeof($arr); $i++) {
                $s .= '<TR>';
                $a = &$arr[$i];
                if (is_array($a)) 
                        for ($j=0; $j<sizeof($a); $j++) {
                                $val = $a[$j];
                                if (empty($val)) $val = '&nbsp;';
                                $s .= " <TD class='aodb_table_td'>$val</TD>\n";
                        }
                else if ($a) {
                        $s .=  "        <TD class='aodb_table_td'>".$a."</TD>\n";
                } else $s .= "  <TD class='aodb_table_td'>&nbsp;</TD>\n";
                $s .= "\n</TR>\n";
        }
        $s .= '</TABLE></div>';
        print $s;
}

?>
