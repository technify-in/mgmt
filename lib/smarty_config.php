<?php
/** This is for the user interface
 */
#[SMARTY TEMPLATE ENGINE]
/** Configure the Smarty Class, describes where is smarty
    @var string
 */
$SmartyDir = 'lib/smarty/';

/** Configure the Smarty Class
    describes where are the smarty templates
 * @var string
 */
$SmartyTemplateDir = 'templates/';

/**
 * Configure the Smarty Class
 * describes where should smarty put the compiled templates
 * @var string
 */
$SmartyCompileDir = $SmartyTemplateDir.'compiled/';
/**
 * Configure the Smarty Class
 * describes which directory should smarty use for cache
 * @var string
 */
$SmartyCacheDir = $SmartyTemplateDir."cache/";

/**
 * Configure the Smarty Class
 * describe which template theme to use, see templates/*
 * @var string
 */
$SmartyTheme = 'themes/touch/';

/**
 * Configure the Smarty Class
 * Enables disables the template caching, set to true to enable smarty caching system
 * @var boolean
 */
$SmartyCache = false;

/**
 * Configure the Smarty Class
 * @var string
 */
$GlobalApplicationName = "ConManSys";
?>
