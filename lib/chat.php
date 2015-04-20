<?php
/** Function for hipchat
 */
include_once('lib/config.php');

function sendChatMessage($message, $user = 'Server', $room = 'Server Says')
{
  global $config;

  if(!$config['productionserver']) return;
  //Testing, send message on each user login
  //may delay login by a few ms
  require 'lib/HipChat/HipChat.php';
  try
  {
    $token = 'a6d25c6f5c7a8766ab439c2810dfbc';
    $target = 'https://api.hipchat.com';
    $hc = new HipChat\HipChat($token, $target);

    $hc->message_room($room, $user, $message);
  }
  catch (HipChat\HipChat_Exception $e)
  {
    //echo "Oops! Error: ".$e->getMessage();
  }
}
?>
