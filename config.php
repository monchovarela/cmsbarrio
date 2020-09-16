<?php  defined('BARRIO') or die('Sin accesso a este script.');

    return array(
        "base_url" => "http://localhost/cmsbarrio",
        // Theme color
        'theme_color' => '#fff',
        // background color
        'background_color' => '#fff',
        // orientation
        'orientation' => 'landscape',
        // display
        'display' => 'minimal-ui',
        // shortname
        'short_name' => 'BarrioCMS',
        // language
        'lang' => 'es',
        // charset
        'charset' => 'UTF-8',
        // timezone
        'timezone' => 'Europe/Brussels',
        // default theme
        'theme' => 'default',
        // title
        'title' => 'Barrio CMS',
        // description
        'description' => 'El CMS que se adapta a cualquier proyecto',
        // keywords
        'keywords' => 'desarrollo,web,cms',
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
        'menu' => array(
            '/'             => 'Inicio',
            '/blog'         => 'Blog',
            '/editor'       => 'Pruébalo',
            '/documentacion'=> 'Docs'
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
        'facebook' => 'https://facebook.com',
        'instagram' => 'https://instagram.com',
        'twitter' => 'https://twitter.com',

    );
