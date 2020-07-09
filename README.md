# Barrio CMS

El CMS que se adapta a cualquier proyecto

-----------

- [Primeros pasos.](#paso1)
- [Instalación.](#paso2)
- [Estructura.](#paso3)
- [Configuración.](#paso4)
- [Plantillas.](#paso5)
- [Aciones.](#paso6)
- [Shortcodes.](#paso7)

-----------

<span id="paso1"></span>
### Primeros pasos.

#### Requisitos de Apache
Aunque la mayoría de las distribuciones de Apache vienen con todo lo necesario, en aras de la exhaustividad, aquí hay una lista de los módulos de Apache necesarios:

	mod_rewrite

También debe asegurarse de tener **AllowOverride All** configurado en los bloques **<Directory>** y / o **<VirtualHost>** para que el archivo **.htaccess** se procese correctamente y las reglas de reescritura surtan efecto.

#### Requerimientos PHP

La mayoría de los proveedores de alojamiento e incluso las configuraciones locales de **LAMP** tienen **PHP** preconfigurado con todo lo que necesita para que Barrio CMS se ejecute. 

<span id="paso2"></span>
### Instalación.

Si descargó el archivo ZIP y luego planea moverlo a su raíz web, mueva **TODA LA CARPETA** porque contiene varios archivos ocultos (como .htaccess) que no se seleccionarán de manera predeterminada. La omisión de estos archivos ocultos puede causar problemas al ejecutar Barrio CMS.


<span id="paso3"></span>
### Estructura.


	└── Raíz/
	    ├── content/
	    │   ├── blog/ Carpeta del blog
	    │   ├── documentacion/ Carpeta de la documentación
	    │   ├── index.md (Pagina inicio)
	    │   └── 404.md (Pagina error)
	    ├── extensions (Plugins)/
	    │   ├── api/ 
	    │   ├── markdown/
	    │   └── shortcodes/
	    ├── public (Fotos, videos etc..)/
	    │   └── notfound.jpg
	    ├── themes/
	    │   └── default/
	    │       └── dist/
	    │           ├── assets/ (Estylos de la plantilla)
	    │           ├── includes/ (Rrozos de html)
	    │           ├── functions/ (Carpeta funciones)
	    │           ├── func.php (Archivo funciones)
	    │           └── index.html (Plntilla html)
	    ├── .htaccess 
	    ├── Barrio.php 
	    ├── config.php (Archivo configuración)
	    └── index.php


<span id="paso4"></span>
### Configuración.

En el archivo <mark>config.php</mark> encontraras la configuración de la web, desde el titulo, descripción hasta la configuración del menu de navegación.

Puedes crear tu propia configuración pero <mark>no borres las variables que hay por defecto</mark>.

Puedes llamar cualquier variable con el comando `<?php Barrio::$config['nombre'];?>` o si estas en el archivo _.md_ puedes usar `{Config name=nombre}`. 


<span id="paso5"></span>
### Plantillas.

#### Los estilos

La plantilla por defecto usa **classless** es un pequeño archivo CSS, que define pocos pero excelentes estilos para etiquetas HTML5 básicas más muy pocas clases para cuadrícula y espaciado. Nada mas. Nada menos.

#### La plantilla


	// incluimos el archivo head.html
	<?php include THEME_INC.'/head.html'; ?>
	<main>
		// la cabecera
		<header>
			// titulo y descripción archivo config.php
			<h1><?php echo Barrio::$config['title'];?></h1>
			<p><?php echo Barrio::$config['description'];?></p>
			// Navegacion
			<?php Barrio::runAction('navigation',[Barrio::$config['menu']]); ?>
		</header>
		// contenido
		<section>
		<?php 
			// Acción theme_before 
			Barrio::runAction('theme_before');
			// Titulo y descripción archivo md 
			echo '<h2>'.$page['title'].'</h2>'; 
			echo '<p>'.$page['description'].'</p>';
			// si estamos en la carpeta blog
			if(Barrio::urlCurrent() == 'blog') { 
				// Acción que scanea la carpeta blog
				Barrio::runAction('pagination',[Barrio::urlCurrent()]);
			}else{
				// pagina normal
				echo $page['content'];
				// si la pagina esta dentro de una carpeta
				if(Barrio::urlSegment(1)) {
					// navegar dentro de la carpeta
					Barrio::runAction('navFolder');
				}
			}
			// Acción theme_after
			Barrio::runAction('theme_after');
		?>
		</section>
		// footer 
		<footer>
			<p>© <?php echo date('Y'); ?> 💓 <strong><?php echo Barrio::$config['title'];?></strong></p>
		</footer>
	</main>
	// incluimos el javascript y demas
	<?php include THEME_INC.'/footer.html'; ?>



<span id="paso6"></span>
### Acciones.

Las Acciones son funciones que podemos integrar en la plantilla para hacerla mas dináminca. Tenemos unas cuantas por defecto que son:

- **head:** usada para incluir los estilos.
- **theme_before:** comentarios tipo discus
- **theme_after:** resolución de formularios
- **footer:** Analytics y javascript

#### Creando Extensiones

Vamos a crear una extensión que automáticamente genere un enlace al final de cada pagina usando una acción que ya esta en la plantilla que es  `<?php Barrio::runAction('theme_after); ?>`.

	<?php
		// llamamos a la acción theme_after
		Barrio::addAction('theme_after',function(){
		    // y ahora que enseñe esto
		    echo '<a href="'.Barrio::urlBase().'/articulos">Ver articulos.</a>';
		});



Y ahora en todas las páginas al final se verá ese enlace, asi de facil.

Ahora vamos añadir algo más, le vamos a decir que si está en la sección artículos y  la página extensiones enseñe el texto y si no no enseñe nada.

	<?php
		// llamamos a la acción theme_after
		Barrio::addAction('theme_after',function(){
		    // urlSegment sirve para señalar un segmento del enlace
		    // si pones var_dump(Barrio::urlSegments()) veras todos los segmentos del enlace
		    if(Barrio::urlSegment(0) == 'articulos' && Barrio::urlSegment(1) == 'extensiones'){
		         // y ahora que enseñe esto
		        echo '<a href="'.Barrio::urlBase().'/articulos">Ver articulos.</a>';
		    }
		});



Ahora haremos una acción que cambie el fondo solo en esta página, para ello usaremos  el  `Barrio::runAction('head')` que hay en el archivo _head.inc.html_.

	<?php
		// llamamos a la accion head
		Barrio::addAction('head',function(){
		     // urlSegment sirve para señalar un segmento del enlace
		    if(Barrio::urlSegment(0) == 'articulos' && Barrio::urlSegment(1) == 'extensiones'){
		         // y ahora incrustamos esto
		        echo '<style rel="stylesheet">
		                body{
		                    background:blue;
		                    color:white;
		                }
		                pre,code{
		                    background: #0000bb;
		                    border-color: #00008e;
		                    box-shadow: 0px 3px 6px -2px #02026f;
		                    color: white;
		                }
		        </style>';
		    }
		});


<span id="paso7"></span>
### Shortcodes.

Es muy fácil crear Shortcodes en **Barrio CMS** por ejemplo, vamos a crear un Shortcode que cambie el color del texto con el color que queramos.

	<?php
		// llamamos la funcion mejor capitalizada (letra mayúscula)
		Barrio::shortcodeAdd('Texto',function($atributos,$contenido){

		    // extraemos los atributos (en este caso $color)
		    extract($atributos);

		    // definimos el color, por defecto sera blue (mejor en ingles)
		    $color = (isset($color)) ? $color : 'blue';

		    // parseamos para poder usar markdown
		    $contenido = Parsedown::instance()->text($contenido);

		    // aplicamos un filtro para escribir dentro del shortcode
		    $resultado = Barrio::applyFilter('content','<div style="color:'.$color.'">'.$contenido.'</div>');

		    // quitamos espacios
		    $resultado = preg_replace('/\s+/', ' ', $resultado);

		    // enseñamos la plantilla
		    return $resultado;
		});


Ahora si escribimos `{Text color=green}` y dentro de este el texto y cerramos  con **corchetes** `{/Text}` obtenemos esto:

	<p style="color:green">Este es un texto dentro de un Shortcode en el que puedo usar **Markdown**</p>

También puedes usar **código de color**

	{Text color='#f00'} // con comillas simples
	    Hola soy **Rojo**
	{/Text}

	<p style="color:#f00">Este es un texto dentro de un Shortcode en el que puedo usar 


Ahora vamos hacer un Shortcode para incrustar videos de Youtube.
En este caso **no necesitamos escribir dentro** así que es mas facil aun.

	<?php
		Barrio::shortcodeAdd('Youtube', function ($atributos) {

		    // extraemos los atributos (en este caso $src)
		    extract($atributos);

		    // el codigo del enlace
		    $id = (isset($id)) ? $id : '';

		    $clase = (isset($clase)) ? $clase : '';

		    // comprobamos que exista el $id
		    if($id){

		        // el html
		        $html = '<section class="'.$clase.'">';
		        $html .= '<iframe src="//www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';
		        $html .= '</section>';
		        $html = preg_replace('/\s+/', ' ', $html);
		        return $html;

		    // si no se pone el atributo id que avise
		    }else{
		        return Barrio::error('Error [ id ] no encontrado');
		    }
		});


El código seria este:

	{Youtube id='GxEc46k46gg'}


Con los Shortcodes podemos crear desde **galerías**, **formularios** , **incrustar videos**, **Musica**, **Cambiar el Css** y todo un largo etcétera.


**Nota:** Si tienes instalado Barrio CMS en local puedes probar el editor para ver como funcionan los Shortcodes sino mejor que lo borres por si acaso.

