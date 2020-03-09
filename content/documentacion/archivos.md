Title: Archivos 
Description: Como crear archivos o paginas.
Template: docs
----

Los archivos que hacen de base de datos están escritos en **Markdown** un formato muy fácil y que una vez se acostumbra uno no vuelve a usar otra cosa.


Esta es la **estructura** básica de un archivo:

{Code type='markdown'}Title = El título de la página
Description = Descripción de la página
Template = plantilla html que usará (index,articulo etc...) sin .html la extensión

---


Aqui ira el contenido de la página
{/Code}

Esta es la **estructura** completa de un archivo:

{Code type='markdown'}Title = El titulo de la página
Description = Descripción de la página
Tags = Etiquetas de la página
Author = Author de la página
Image = Imagen de la página
Background: color de fondo de la cabecera
Color: color de la cabecera
Date = Fecha en formato d/m/Y
Robots, = Si quieres que indexe o no en google por defecto index,follow
Keywords = keywords de la página
Template = plantilla html que usara (index,articulo etc...)
Category = categoría

---


Aqui ira el contenido de la página
{/Code}


Por defecto podemos usar varios tipos de **Shortcodes** que son:

{Code type='markdown'}&#10100;Url&#10101;= Es la dirección de nuestra web{/Code}

Por ejemplo &#10100;Url&#10101;/nosotros se convierte en:

{Url}/nosotros

Luego hay otros como son:

{Code type='markdown'}&#10100;More&#10101; que recorta el articulo como si fuera un read more.

&#10100;Email&#10101; enseña el email que tenemo en config.php
{/Code}

El Shortcode **&#10100;Php&#10101;**

Podemos usarla para añadir **Php** simple en nuestros archivos como por ejemplo:

{Code type='markdown'}&#10100;Php&#10101; echo 'Hola Mundo'; &#10100;/Php&#10101;{/Code}

El resultado sería:


{Php} echo 'Hola Mundo'; {/Php}





