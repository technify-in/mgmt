<?php
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 */

define( '_ABS_APPLICATION_PATH', dirname(__FILE__) . '/' );

#Do not change anything here
require_once(_ABS_APPLICATION_PATH."lib/config.php");

//First check if country is allowed
if($config['productionserver'])
{
  switch($country_name = apache_note("GEOIP_COUNTRY_NAME"))
  {
    case 'India':
      break;
    case '': //allow localhost as it returns blank,and cronjobs depend on it
      break;
    default:
      echo "<html><body><h1>Sorry your country $country_name is not in the list of allowed countries</h1><ul><li>It may be possible you are accessing this application through a proxy. Please update your browser settings and try accessing this application directly.</li><li>Some mobile browsers like Chrome, Opera, Silk and UC browser use web site acceleration/ compression proxies, that route connections through countries not allowed to access this application, please disable the proxy access by going into your browser settings.</li><li>Please contact HQ in case you believe this is in error</li></ul></body></html>";
      exit(1);
  }
}

define( '_APPLICATION_WEB_PATH', dirname($_SERVER['QUERY_STRING']).'/' );
define( '_ABS_APPLICATION_WEB_PATH', _APPLICATION_WEB_PATH.$config['app_subdir']);

session_name($config['sessionname']);
session_start();

//@todo: should be from config file or on per user basis
date_default_timezone_set('Asia/Calcutta');
require_once(_ABS_APPLICATION_PATH."lib/smarty_config.php");
require_once(_ABS_APPLICATION_PATH."lib/GlobalSmarty.php");
require_once(_ABS_APPLICATION_PATH.'lib/adodb/adodb.inc.php');
//require_once(_ABS_APPLICATION_PATH.'lang/'.$config['lang'].'.php');
require_once(_ABS_APPLICATION_PATH.'lib/translate.php');
require_once(_ABS_APPLICATION_PATH.'lib/logger.php');
require_once(_ABS_APPLICATION_PATH.'lib/permissions.php');

$template = new GlobalSmarty;
require_once('lib/smarty-gettext.php');
$template->register_block('t', 'smarty_translate');
$template->assign("skinpath", $SmartyTemplateDir.$SmartyTheme);
$template->assign("homepagepath", _APPLICATION_WEB_PATH);
$template->assign("app_default_title", _t($config['default_title']));
$template->assign("lang", $lang);
$template->assign("app_productionserver", $config['productionserver']);

if(isset($config['dsn']))
{
  $db = @NewADOConnection($config['dsn']);
  //No Database so die
  if (!$db)
  {
    ECHO MYSQL_ERROR();
    $template->assign("title", _t('Error'));
    $template->assign("error_message", _t('Connection_with_database_server_failed'));
    $template->display('error.html');
    exit;
  }
  //Get ready for some UTF action
  $db->Execute("SET NAMES UTF8");
}
//else no DB support

/** NOTE: This may cause problems if the content type is something else
 */
if($lang['Content-Type-charset'])
  header("Content-Type: text/html; charset=".$lang['Content-Type-charset']."\n\n");
//localisation
putenv("LANG=".$config['lang']);
setlocale(LC_ALL, $config['lang']);
bindtextdomain('SFW_v1', './lang');
textdomain('SFW_v1');

?>
