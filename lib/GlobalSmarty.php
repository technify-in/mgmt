<?php

//The global config
/*equire_once(_ABS_APPLICATION_PATH.'config/config.php');
//a little kludgy
require_once(_ABS_APPLICATION_PATH.'config/smarty_config.php');
// load Smarty library*/
require_once(_ABS_APPLICATION_PATH.$SmartyDir.'Smarty.class.php');

/**
 * We configure the smarty template engine here in this class 
 * by extending the base class Smarty.
 * Use this class to initlize the smarty template engine
 * @author Ajay Pal Singh Atwal <ajaypal@ajaypal.com>
 */
class GlobalSmarty extends Smarty
{
   /**
    * This constructor sets the smarty specific vars
    * @access public
    */
    function GlobalSmarty()
    {
        //The global config
        global $SmartyTemplateDir;
        global $SmartyTheme;
        global $SmartyCompileDir;
        global $SmartyCacheDir;
        global $SmartyCache;
        global $GlobalApplicationName;

        //Class Constructor.
        $this->Smarty();

        //Determined by the current theme
        $this->template_dir = _ABS_APPLICATION_PATH.$SmartyTemplateDir.$SmartyTheme;
        $this->config_dir = _ABS_APPLICATION_PATH.$SmartyTemplateDir.$SmartyTheme;

        //Common for all
        $this->compile_dir = _ABS_APPLICATION_PATH.$SmartyCompileDir;
        $this->cache_dir = _ABS_APPLICATION_PATH.$SmartyCacheDir;
        $this->caching = $SmartyCache;

        //$this->debugging = true;
        //$this->compile_check = true;
        $this->assign("application_name", $GlobalApplicationName );
    }
}
?>