<?php
/*
 * Declara al principio del archivo, las llamadas a las funciones respetarán
 * estrictamente los indicios de tipo (no se lanzarán a otro tipo).
 */
declare (strict_types = 1);

namespace traits;

/**
 * Acceso restringido
 */
defined("ACCESS") or die("No tiene acceso a este archivo");

/**
 * Trait action
 *
 * @author    Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright 2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version 0.0.1
 *
 */
trait Template
{
    /**
     * Etiquetas de plantilla
     *
     * @return array
     */
    public function tags()
    {
        return array(
            // Fecha
            '{date}' => '<?php echo date("d-m-Y");?>',
            // Año
            '{Year}' => '<?php echo date("Y");?>',
            // url
            '{Site_url}' => '<?php echo Barrio\Barrio::urlBase();?>',
            // ruta
            '{Site_current}' => '<?php echo Barrio\Barrio::urlCurrent();?>',
            //{* Comentario *}
            '{\*(.*?)\*}' => '<?php echo "\n";?>',
            // Condicional
            // {:If $num == 2} es 2 {Elseif: $num == 3} es 3 {Else} es 0{/If}
            '{If: ([^}]*)}' => '<?php if ($1): ?>',
            '{Else}' => '<?php else: ?>',
            '{Elseif: ([^}]*)}' => '<?php elseif ($1): ?>',
            '{\/If}' => '<?php endif; ?>',
            // {loop: $array} {/loop}
            '{Loop: ([^}]*) as ([^}]*)=>([^}]*)}' => '<?php $counter = 0; foreach (%%$1 as $2=>$3): ?>',
            '{Loop: ([^}]*) as ([^}]*)}' => '<?php $counter = 0; foreach (%%$1 as $key => $2): ?>',
            '{Loop: ([^}]*)}' => '<?php $counter = 0; foreach (%%$1 as $key => $value): ?>',
            '{\/Loop}' => '<?php $counter++; endforeach; ?>',
            // {?= 'hello world' ?}
            '{\?(\=){0,1}([^}]*)\?}' => '<?php if(strlen("$1")) echo $2; else $2; ?>',
            // {? 'hello world' ?}
            '{(\$[a-zA-Z\-\._\[\]\'"0-9]+)}' => '<?php echo %%$1; ?>',
            // capitalizar
            '{(\$[a-zA-Z\-\._\[\]\'"0-9]+)\|cap}' => '<?php echo ucfirst(%%$1); ?>',
            // minúsculas
            '{(\$[a-zA-Z\-\._\[\]\'"0-9]+)\|lower}' => '<?php echo strtolower(%%$1); ?>',
            // acortar texto
            '{(\$[a-zA-Z\-\._\[\]\'"0-9]+)\|short}' => '<?php echo Vendor\Barrio\Barrio::shortText(%%$1,30); ?>',
            // {$page.content|e}
            '{(\$[a-zA-Z\-\._\[\]\'"0-9]+)\|e}' => '<?php echo htmlspecialchars(%%$1, ENT_QUOTES | ENT_HTML5, "UTF-8"); ?>',
            // {$page.content|parse}
            '{(\$[a-zA-Z\-\._\[\]\'"0-9]+)\|parse}' => '<?php echo html_entity_decode(%%$1, ENT_QUOTES); ?>',
            // Incluimos trozo del tema de la carpeta includes
            '{Include: (.+?\.[a-z]{2,4})}' => '<?php include_once(THEMES."/' . self::$config['theme'] . '/includes/$1"); ?>',
            // Agregamos una acción
            '{Action: ([a-zA-Z\-\._\[\]\'"0-9]+)}' => '<?php Action\Action::run(\'$1\'); ?>',
            // Comprobamos la si es la misma ruta
            '{Segment: ([^}]*)}' => '<?php if (Barrio\Barrio::urlSegment(0) == "$1"): ?>',
            '{\/Segment}' => '<?php endif; ?>',
            // Assets del tema
            '{Assets: (.+?\.[a-z]{2,4})}' => '<?php echo Barrio\Barrio::urlBase()."/core/themes/' . self::$config['theme'] . '/assets/$1" ?>',
        );
    }
}
