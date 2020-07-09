<?php  defined('BARRIO') or die('Sin accesso a este script.');

$theme = Barrio::$config['theme'];

// Constants to short source urls
define('THEME_NAME', $theme);
define('THEME_ROOT', THEMES.'/'.$theme.'/dist');
define('THEME_FUNCTIONS', THEME_ROOT.'/functions');
define('THEME_INC', THEME_ROOT.'/includes');
define('THEME_ASSETS', Barrio::urlBase().'/themes/'.$theme.'/dist/assets');


include_once(THEME_FUNCTIONS.'/shortcodes.php');
include_once(THEME_FUNCTIONS.'/actions.php');
