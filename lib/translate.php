<?php
/** Will search a global array by the name lang and return translation
 */

function _t($str)
{
  global $lang;
  $key =  str_replace(' ', '_', $str);
  if(isset($lang[$key]))
    return $lang[$key];
  else
    return str_replace('_', ' ', $str);
}
?>