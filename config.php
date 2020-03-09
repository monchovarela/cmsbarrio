<?php  defined('BARRIO') or die('Sin accesso a este script.');

    return array(
        // Theme color
        'theme_color' => '#fff',
        // background color
        'background_color' => '#fff',
        // orientation
        'orientation' => 'landscape',
        // display
        'display' => 'standalone',
        // shortname
        'short_name' => 'Moncho',
        // language
        'lang' => 'es',
        // charset
        'charset' => 'UTF-8',
        // timezone
        'timezone' => 'Europe/Brussels',
        // default theme
        'theme' => 'default',
        // title 
        'title' => 'CMS Barrio',
        // description
        'description' => 'Desarrollo web & Músico',
        // keywords
        'keywords' => 'desarrollo,web,musico',
        // author
        'author' => 'Moncho Varela',
        // email
        'email' => 'nakome@gmail.com',
        // default not found image
        'image' => 'public/notfound.jpg',
        // pagination per page
        'pagination' => 6,
        // blog
        'blog' => array(
            // blog image
            'image' => 'public/notfound.jpg',
            // Blog title
            'title' => 'Blog',
             // Blog description
            'description' => 'Articulos y actualizaciones.',
            // search title
            'search_title' => 'Buscar Pagina',
            // search button
            'search_btn' => 'Buscar',
            // recent posts
            'recent_posts' => 'Articulos recientes',
            // last posts 
            'last_posts' => 3
        ),
        // navigation
        'home_menu' => array(
            '/'             => 'Inicio',
            '#features'     => 'Que hacemos',
            '#services'     => 'Servicios',
            '/blog'         => 'Blog',
            '/documentacion'=> 'Documentacion',
            '/contacto'     => 'Contacto'
        ),
        'other_menu' => array(
            '/'             => 'Inicio',
            '/blog'         => 'Blog',
            '/editor'       => 'Editor',
            'Documentacion'=> array(
                '/documentacion'                        => 'Inicio',
                '/documentacion/archivos'               => 'Archivos',
                '/documentacion/extensiones'            => 'Extensiones',
                '/documentacion/plantillas'             => 'Temas',
                '/documentacion/que-es-markdown'        => 'Markdown',
                '/documentacion/shortcodes'             => 'Shortcodes',
                '/documentacion/shortcodes-plantilla'   => 'Demo'
            ),
            '/contacto'     => 'Contacto'
        ),
        // search
        'search' =>  array(
            'results_of' => 'Resultados de la busqueda',
            'no_results' => 'No hay resultados de:',
            'read'       => 'Ir a enlace'
        ),
        // copyright
        'copyright' => 'CMS Barrio',
        // not access
        'notaccess' => '!No tienes accesso aquí¡',

        // social
        'facebook' => 'https://facebook.com/nakome',
        'instagram' => 'https://instagram.com/monchovarela',
        'twitter' => 'https://twitter.com/nakome',
        'youtube' => 'https://youtube.com/nakome'

        // you can add more here...

    );
