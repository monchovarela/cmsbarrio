<?php

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    exit('Barrio necesita PHP 5.3.0 en adelante.');
}

define('BARRIO', true);
define('ROOT', rtrim(dirname(__FILE__), '\\/'));
define('CONTENT', ROOT.'/content');
define('THEMES', ROOT.'/themes');
define('EXTENSIONS', ROOT.'/extensions');
define('DEV_MODE', true);

if (DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
}

require ROOT.'/Barrio.php';
Barrio::Run()->init('config.php');
