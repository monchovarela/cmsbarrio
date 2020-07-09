<?php  defined('BARRIO') or die('Sin accesso a este script.');

/**
 * Extension Markdown.
 *
 *  @author Moncho Varela / Nakome
 *  @copyright 2016 Moncho Varela / Nakome
 *
 *  @version 1.0.0
 */

include EXTENSIONS.'/markdown/Parsedown.php';

Barrio::addFilter('content', 'markdown', 1);

function markdown($content)
{
    return Parsedown::instance()->text(Barrio::parseShortcode($content));
}
