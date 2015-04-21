<?php
/**
   The global configuration file
   @author        Ajay Pal Singh Atwal
 */
/** If web app is installed in a sub directory
 */
//$config['app_subdir'] = '~aps/bankim';


/** Default Local Settings
    We will read the local for a user from database and change this (after login)
 */
$config['lang']     = "en_US"; //pa_IN.utf8

/** Database URI
    Format is
    mysql://username:password@localhost/database
 */

$config['dsn'] = 'mysql://mgmt:store123@localhost/mgmt';

/** Session name: Set it to something unique
 */
$config['sessionname'] = "Bankim";
$config['default_title'] = "Bankim";

//SMS
//see lib/sms.php

//Analytics
//see templates/analytics.html

//Set to True if running on a production server
//will enable chat, analytics
$config['productionserver'] = false;
?>
