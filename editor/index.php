<?php
// required version
if (version_compare(PHP_VERSION, "5.3.0", "<")) {
    exit("Panel requires PHP 5.3.0 or greater.");
}

// Root directory
define('ROOT', rtrim(dirname(__FILE__), '\\/'));
define('BARRIO', true);
define('CORE', ROOT.'/core');
define('CONTROLLERS', ROOT.'/core/controllers');
define('EXTENSIONS', ROOT.'/core/extensions');
define('COMPONENTS', CONTROLLERS.'/components');
define('PARTIALS', ROOT.'/core/partials');
define('VIEWS', ROOT.'/core/views');
define('ROOTBASE', rtrim(str_replace(array('admin'), array(''), dirname(__FILE__)), '\\/'));

define('DEV', true);
if (DEV) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('track_errors', 1);
    ini_set('html_errors', 1);
    error_reporting(E_ALL | E_STRICT | E_NOTICE);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('track_errors', 0);
    ini_set('html_errors', 0);
    error_reporting(0);
}

require COMPONENTS.'/Router.php';
require CONTROLLERS.'/routes.php';
