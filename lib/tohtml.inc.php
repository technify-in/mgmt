<?php 
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
//	include('adodb.inc.php');
//	$db = ADONewConnection('mysql');
//	$db->Connect('mysql','userid','password','database');
//	$rs = $db->Execute('select col1,col2,col3 from table');
//	rs2html($rs, 'BORDER=2', array('Title1', 'Title2', 'Title3'));
//	$rs->Close();
//
// RETURNS: number of rows displayed


function rs2html(&$rs,$ztabhtml=false,$zheaderarray=false,$htmlspecialchars=true,$echo = true)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows,$ADODB_ROUND;

	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	
	if (! $ztabhtml) $ztabhtml = " border=\"1\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\"";
	//else $docnt = true;
	$typearr = array();
	$ncols = $rs->FieldCount();
	$hdr = "<TABLE $ztabhtml><tbody><tr>";
	for ($i=1; $i < $ncols; $i++) {	
		$field = $rs->FetchField($i);
		if ($field) {
			if ($zheaderarray) $fname = $zheaderarray[$i];
			else $fname = htmlspecialchars($field->name);	
			$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 			//print " $field->name $field->type $typearr[$i] ";
		} else {
			$fname = 'Field '.($i+1);
			$typearr[$i] = 'C';
		}
		if (strlen($fname)==0) $fname = '';
		$hdr .= "<TD>$fname</TD>";
	}
	$hdr .= "</tr>";
	if ($echo) print $hdr."";
	else $html = $hdr;
	
	// smart algorithm - handles ADODB_FETCH_MODE's correctly by probing...
	$numoffset = isset($rs->fields[0]) ||isset($rs->fields[1]) || isset($rs->fields[2]);
	while (!$rs->EOF) {
		
		$s .= "<TR>";
		
		for ($i=1; $i < $ncols; $i++) {
			if ($i===0) $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
			else $v = ($numoffset) ? $rs->fields[$i] : next($rs->fields);
			
			$type = $typearr[$i];
			switch($type) {
			case 'D':
				if (empty($v)) $s .= "<TD></TD>";
				else if (!strpos($v,':')) {
					$s .= "	<TD>".$rs->UserDate($v,"D d, M Y") ."</TD>";
				}
				break;
			case 'T':
				if (empty($v)) $s .= "<TD></TD>";
				else $s .= "<TD>".$rs->UserTimeStamp($v,"D d, M Y, h:i:s") ."</TD>";
			break;
			
			case 'N':
				if (abs($v) - round($v,0) < 0.00000001)
					$v = round($v);
				else
					$v = round($v,$ADODB_ROUND);
			case 'I':
				$s .= "<TD>".stripslashes((trim($v))) ."</TD>";
			   	
			break;
			/*
			case 'B':
				if (substr($v,8,2)=="BM" ) $v = substr($v,8);
				$mtime = substr(str_replace(' ','_',microtime()),2);
				$tmpname = "tmp/".uniqid($mtime).getmypid();
				$fd = @fopen($tmpname,'a');
				@ftruncate($fd,0);
				@fwrite($fd,$v);
				@fclose($fd);
				if (!function_exists ("mime_content_type")) {
				  function mime_content_type ($file) {
				    return exec("file -bi ".escapeshellarg($file));
				  }
				}
				$t = mime_content_type($tmpname);
				$s .= (substr($t,0,5)=="image") ? " <td><img src='$tmpname' alt='$t'></td>\" : " <td><a
				href='$tmpname'>$t</a></td>\";
				break;
			*/

			default:
				if ($htmlspecialchars) $v = htmlspecialchars(trim($v));
				$v = trim($v);
				if (strlen($v) == 0) $v = '';
				$s .= "<TD>". str_replace("",'<br>',stripslashes($v)) ."</TD>";
			  
			}
		} // for
		$s .= "</TR>";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
	
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
	
		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
///no repeat headers
// 			if ($echo) print $s . "</TABLE>";
// 			else $html .= $s ."</TABLE>";
// 			$s = $hdr;
		}
	} // while

	if ($echo) print $s."</tbody></TABLE>";
	else $html .= $s."</tbody></TABLE>";
	
	if ($docnt) if ($echo) print "<H2>".$rows." Rows</H2>";
	
	return ($echo) ? $rows : $html;
}
 
// pass in 2 dimensional array
function arr2html(&$arr,$ztabhtml='',$zheaderarray='')
{
	if (!$ztabhtml) $ztabhtml = 'BORDER="1"';
	
	$s = "<TABLE $ztabhtml><tbody>";//';print_r($arr);

	if ($zheaderarray) {
		$s .= '<TR>';
		for ($i=1; $i<sizeof($zheaderarray); $i++) {
			$s .= "<TD>{$zheaderarray[$i]}</TD>";
		}
		$s .= "</TR>";
	}
	
	for ($i=1; $i<sizeof($arr); $i++) {
		$s .= '<TR>';
		$a = &$arr[$i];
		if (is_array($a)) 
			for ($j=0; $j<sizeof($a); $j++) {
				$val = $a[$j];
				if (empty($val)) $val = '';
				$s .= "<TD>$val</TD>";
			}
		else if ($a) {
			$s .=  '<TD>'.$a."</TD>";
		} else $s .= "<TD></TD>";
		$s .= "</TR>";
	}
	$s .= '</tbody></TABLE>';
	print $s;
}

?>