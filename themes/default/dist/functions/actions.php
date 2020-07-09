<?php  defined('BARRIO') or die('Sin accesso a este script.');

include THEME_FUNCTIONS.'/theme.php';

/**
 * ====================================
 *  Actions
 * ====================================
 */
Barrio::addAction('head', "Theme::head");
Barrio::addAction('footer', "Theme::footer");
Barrio::addAction('navigation', "Theme::navigation");
Barrio::addAction('pagination', "Theme::posts");
Barrio::addAction('navFolder', "Theme::navFolder");
Barrio::addAction('theme_after', "Theme::search");
