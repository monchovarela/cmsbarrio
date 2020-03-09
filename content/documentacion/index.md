Title: Documentación
Description: Funciones de Barrio CMS
Template: docs

----

Esta es la Documentación de **Barrio CMS** por defecto, luego depende de la plantilla que use puede haber mas funciones.


### htaccess

{Code}# previene que no se vean estos tipos de archivo 
<FilesMatch "\.(htaccess|htpasswd|ini|md|py|log|sh|inc|bak|db)$">
Order Allow,Deny
Deny from all
</FilesMatch>

AddDefaultCharset UTF-8

Options -Indexes

<IfModule mod_php5.c>
    php_flag magic_quotes_gpc                 off
    php_flag magic_quotes_sybase              off
    php_flag register_globals                 off
</IfModule>

<IfModule mod_rewrite.c>
    RewriteEngine on
    #RewriteBase  /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
{/Code}


{Divider}

### Empezemos

Este es el archivo config.php de la configuración del CMS.

{Code}// Theme color
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
'menu' => array(
    '/'             => 'Inicio',
    '/blog'         => 'Blog',
    '/documentacion'=> 'Documentacion',
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
{/Code}


{Divider}


### Constantes




|       Constante    |   Descripción   |
|         ---        |      ---        |
| BARRIO 			| Variable boleana para prevenir el acceso |
| ROOT 				| Define el directorio raiz de la web |
| CONTENT 			| Define el directorio raiz de el contenido de los archivos md |
| THEMES 			| Define el directorio raiz de las plantillas |
| EXTENSIONS 		| Define el directorio raiz de las extensiones |
| DEV_MODE 			| Variable boleana para ver errores |




{Divider}




### Estructura de paginas



|       Localización  Fisica    |   Url |
|               ---             |   --- |
|  content/index.md             | /     |
|  content/otra.md              | /otra |
|  content/proyectos/otra.md    | /proyectos/otra |
|  content/una/direccion/muy/larga/pagina.md    | /una/direccion/muy/larga/pagina |



{Divider}



### Variables del archivo .md

Por defecto existen unas variables en los archivos de texto, estas son:

{Code type='markdown'}&#10100;Url&#10101; = la direccíon de la pagina
&#10100;Email&#10101; = Email por defecto de config.php
&#10100;More&#10101; = funcíon para acortar los archivos de texto
&#10100;Php&#10101; echo 'hola mundo'; &#10100;/Php&#10101;{/Code}


{Divider}


### Funciones Php


**Acciones:**

{Code type='php'}// Crear una Accion
Barrio::addAction('demo',function($nombre = ''){
    echo $nombre
});

// llamar a la Accion
Barrio::runAction('demo',['nombre']);
{/Code}


**Shortcodes:**

{Code type='php'}// Crear una Shortcode
Barrio::addAction('Escribe',function($atributos){
    // extrae atributos
    extract($atributos);
    // valores por defecto
    $nombre = (isset($nombte)) ? $nombre = $nombre : 'Nombre por defecto';
    // retorna el nombre
    return $nombre
});

// llamar a el Shortcode
&#10100;Escribe nombre='Barrio CMS'&#10101;

{/Code}


{Divider}



**Otras funciones:**


|       Funcion    |   Descripción   |
|         ---      |      ---        |
| `Barrio::urlBase()`     |  Obtiene la dirección raíz   |
| `Barrio::urlCurrent()`  |  Obtiene la dirección en la que se encuentra en ese momento   |
| `Barrio::urlSegments()` |  Divide el hash en un array   |
| `Barrio::urlSegment(0)` |  Obtiene el primer hash       |
| `Barrio::shortArray($array,$clave,$orden)`  |  Ordena un array de elementos |
| `Barrio::scanFiles($carpeta,$tipo,$ruta)`   |  Busca archivos en una carpeta |
| `Barrio::pages($carpeta,$ord,$ord_por,$ignor,$limit)` | Busca las paginas en una carpeta |
| `Barrio::page('blog')` |  Obtiene el array de la pagina |


{Alert type='info' clase='mt-5'}
**Nota:** Puedes crear mas funciones en el archivo `func.php` de la plantilla.
{/Alert}
