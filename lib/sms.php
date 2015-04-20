<?php

function sendSMS($mobile, $sms, $module)
{
  global $db, $config;

  //do nothing on test server
  if(!$config['productionserver']) return;

  $mobile = urlencode($mobile);
  $sms = urlencode(addslashes(strip_tags(trim($sms))));
  $db->Execute("insert into sms set mobile='$mobile', sms='$sms', scheduletime=NOW(), module='$module'");
  $id = $db->Insert_ID();
  $url = "http://59.162.167.52/api/MessageCompose?admin=".urlencode('admin@gmail.com')."&user=".urlencode('22bankim@gmail.com:Password')."&senderID=".urlencode('TEST SMS')."&receipientno=$mobile&msgtxt=$sms&state=4";
//error_reporting(~0);
//ini_set('display_errors', 1);
  $buffer = file_get_contents($url);
//echo $url;
  if($buffer == false)
  {
    $db->Execute("update sms set response='G/W URL Open Failed, N/W Error?', sendtime=NOW() where id='$id'");
  }
  else if($buffer == null)
  {
    //echo 'allow_url_fopen:'.ini_get('allow_url_fopen');
    $db->Execute("update sms set response='G/W URL Open Access Denied, server config error', sendtime=NOW() where id='$id'");
  }
  else if(empty ($buffer))
  {
    $db->Execute("update sms set response='Failed to get G/W Response', sendtime=NOW() where id='$id'");
  }
  else
  {
    $buffer = addslashes($buffer);
    $buffer = explode(",", $buffer);
    $db->Execute("update sms set trackid='".$buffer[0]."', response='".$buffer[1]."', sendtime=NOW() where id='$id'");
  }
}

?>
