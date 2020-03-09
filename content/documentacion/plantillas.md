Title: Crear Temas
Description:  Cómo crear tus propios temas.
Template: docs

----


En **Barrio CMS** tu puedes crear todo tipo de plantillas, desde un blog una pagina tipo _landing page_  etc...

{more}


{Alert type=info}
**Nota:** la mejor forma de aprender a crear temas es modificando otros.
{/Alert}



La estructura de los temas se compone de:

1. nombre de la carpeta que contiene el tema.
    - archivo **func.php** ( si no esta nos daría error ).
    - carpeta **assets** donde meteremos todos los scripts y estilos.
    - carpeta **inc** , aquí se encuentran los **includes** ( head.html, footer.html )
    - archivo **index.html**, que se puede usar para paginas estáticas.
    - archivo **post.html**, es el encargado de buscar en la carpeta artículos todos los archivos que haya.
    - archivo **group.html** , es el que enseña los articulos o otras carpetas con contenido.
    - archivo **404.html** , es el que enseña en caso de error.


{Alert type=info}
**Nota:** _siempre puedes cambiar todo esto y ponerlo como tu quieras es solo para comenzar_.
{/Alert}


### Crear plantilla

Vamos a crear una plantilla a la que llamaremos **home.html**:




{Code type='php'}<?php include THEMES.'/default/inc/head.inc.html';?>
<?php include THEMES.'/default/inc/header.inc.html';?>
<main class="container">
    <div class="row">
        <div class="col-md-12">
            <?php Barrio::runAction('theme_before');?>
            <?php echo $page['content'];?>
            <?php Barrio::runAction('theme_after');?>
        </div>
    </div>
</main>
<?php include THEMES.'/default/inc/prefooter.inc.html';?>
<?php include THEMES.'/default/inc/footer.inc.html';?>
{/Code}


En primer lugar incluimos **head.inc.html** que es donde se encuentra los **metatags** y **estilos**.

Luego incluimos **header.inc.html** que es donde se encuentra la navegación y el titulo de la pagina.

Las acciones  `theme_before` y `theme_after` se ponen por que a la hora de crear una extensión puedes hacer que se cargen ahi.

Ahora añadimos `$page['content']` que es el encargado de enseñar el contenido del archivo, si por ejemplo queremos enseñar el titulo usamos `$page['title']` o si queremos poner la descripción usamos `$page['description']`.

Ahora solo quedaría incluir **footer.inc.html** que es donde su nombre indica el pie de la página con los scripts y el cierre del body.

También podemos crear una plantilla sin incluir el **head** o el **footer** pero asi es mas ordenado.

**Nota:** _Estas son todas las variables que acepta `$page` si están incluidas en el archivo.


{Code type='markdown'}Title = El titulo de la página
Description = Descripción de la página
Tags = Etiquetas de la página
Author = Author de la página
Image = Imagen de la página
Date = Fecha
Robots, = Si quieres que indexe o no google
Keywords = keywords de la página
Background = color de fondo de cabecera
Color = color de texto de cabecera
Template = plantilla html que usara (index.html,articulo.html etc...)
Url = Un link para enseñar o enlazar.
Category = categoría
Published = publicado o no.
{/Code}

Las etiquetas **title**, **description**, **keywords** y **author** si no se escriben se usarán las de el archivo **config.php**.












