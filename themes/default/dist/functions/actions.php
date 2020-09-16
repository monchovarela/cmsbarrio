<?php  defined('BARRIO') or die('Sin accesso a este script.');

include THEME_FUNCTIONS.'/theme.php';

/**
 * ====================================
 *  Actions
 * ====================================
 */
Barrio::addAction('theme_head', "Theme::head");
Barrio::addAction('theme_footer', "Theme::footer");
Barrio::addAction('theme_navigation', "Theme::navigation");
Barrio::addAction('theme_pagination', "Theme::posts");
Barrio::addAction('theme_navFolder', "Theme::navFolder");
Barrio::addAction('theme_content_after', "Theme::search");
