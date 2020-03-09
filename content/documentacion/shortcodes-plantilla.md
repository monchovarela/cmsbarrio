Title: Shortcodes plantilla
Description:  Shortcodes que se pueden usar en la plantilla
Background: #f55,blue
Color: white
Template: docs

----


Una de las mejores formas a mi a parecer de junto a **Markdown** hacen que sea mas facil editar una pagina.



## Divider

{Code type='php'}{Esc}{Divider num=3}{/Esc}
{Esc}{Divider type='br' num=3}{/Esc}
{/Code}



{Divider type='br'}



## Iframes

{Alert type=info}
**Nota:** Solo acepta contenido seguro `https`
{/Alert}


{Code type='php'}{Esc}{Iframe cls='web' src='example.com'}{/Esc}
{Esc}{Iframe src='example.com'}{/Esc}
{/Code}

{Iframe  src='example.com'}



{Divider type='br'}



## Youtube & Vimeo

{Code type='php'}{Esc}{Youtube cls='video' id='aVS4W7GZSq0'}{/Esc}
{Esc}{Youtube id='aVS4W7GZSq0'}{/Esc}
{/Code}


{Youtube id='aVS4W7GZSq0'}




{Code type='php'}{Esc}{Vimeo cls='video' id='149129821'}{/Esc}
{Esc}{Vimeo id='149129821'}{/Esc}
{/Code}

{Vimeo id='149129821'}




{Divider type='br'}



### Texto

{Code type='html'}{Esc}{Text bg='blue' Color='white'}Text aqui{/Texto}{/Esc}
{Esc}{Text Color='white'}Text aqui{/Texto}{/Esc}
{/Code}

{Text bg='blue' Color='white'}
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo necessitatibus, provident maiores vero dolorum saepe architecto quam rerum possimus assumenda, incidunt sit quis, qui at dolor minima dicta aperiam fuga.
{/Text}


{Divider type='br'}


### Alerts

{Code type='php'}// type [primary|secondary|success|info|warning|danger|light|dark]
{Esc}{Alert type='primary'} **Primary!** This is a alert  {/Alert}{/Esc}
{/Code}

{Alert type='primary'}**Primary!** This is a primary alert-check it out!{/Alert}
{Alert type='secondary'}**Secondary!** This is a primary alert-check it out!{/Alert}
{Alert type='success'}**Success!** This is a primary alert-check it out!{/Alert}
{Alert type='info'}**Info!** This is a primary alert-check it out!{/Alert}
{Alert type='warning'}**Warning!** This is a primary alert-check it out!{/Alert}
{Alert type='danger'}**Danger!** This is a primary alert-check it out!{/Alert}
{Alert type='light'}**Light!** This is a primary alert-check it out!{/Alert}
{Alert type='dark'}**Dark!** This is a primary alert-check it out!{/Alert}


{Divider type='br'}


### Botones


**Color**

{Code type='php'}// Color = [primary|secondary|success|info|warning|danger|light|dark|link]
// text = texto del boton
// id =  id del boton (opcional)
// link = dirección  (opcional)
{Esc}{Btn Color='dark' text='Link' link='//example.com'}{/Esc}
{/Code}


{Btn Color='primary' text='Primary' link='http://example.com'}
{Btn Color='secondary' text='Secondary' link='http://example.com'}
{Btn Color='success' text='Success' link='http://example.com'}
{Btn Color='info' text='Info' link='http://example.com'}
{Btn Color='warning' text='Warning' link='http://example.com'}
{Btn Color='danger' text='Danger' link='http://example.com'}
{Btn Color='light' text='Light' link='http://example.com'}
{Btn Color='dark' text='Dark' link='http://example.com'}
{Btn Color='link' text='Link' link='http://example.com'}



{Divider type='br'}



### Columnas

{Code type='php'}// cls = se le puede añadir cualquer classe
{Esc}{Row}
	{Col num='2'}6 columnas{/Col}
	{Col num='2'}6 columnas{/Col}
{/Row}
{Row}
	{Col num='4'}3 columnas{/Col}
	{Col num='4'}3 columnas{/Col}
	{Col num='4'}3 columnas{/Col}
{/Row}
{Row}
	{Col num='3'}4 columnas{/Col}
	{Col num='3'}4 columnas{/Col}
	{Col num='3'}4 columnas{/Col}
	{Col num='3'}4 columnas{/Col}
{/Row}
{/Esc}
{/Code}


{Row}

{Col num='4'}
**Col 4**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}

{Col num='4'}
**Col 4**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}

{Col num='4'}
**Col 4**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}

{/Row}


{Row}

{Col num='3'}
**Col 3**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}
{Col num='3'}
**Col 3**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}
{Col num='3'}
**Col 3**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}
{Col num='3'}
**Col 3**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}
{/Row}


{Row}
{Col num='6'}
**Col 6**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}
{Col num='6'}
**Col 6**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}
{/Row}

{Row}
{Col num='8'}
**Col 8**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}
{Col num='4'}
**Col 4**

Labore ipsum ea dolor labore deserunt magna magna.
{/Col}
{/Row}

{Row}
{Col num='4'}
**Col 4**

Labore ipsum ea dolor labore deserunt magna magna.
{/Col}
{Col num='8'}
**Col 8**

Labore ipsum ea dolor labore deserunt magna magna sit consequat magna eiusmod consequat.
{/Col}
{/Row}


{Divider type='br'}


### Barra de progreso

{Code type='php'}// size = Tamaño de la barra
// Color = [success | info | warning | danger ]
// cls = otra clase
{Esc}{Progress  size='25' Color='success'}{/Esc}
{/Code}

{Progress  size='25' Color='success'}
{Progress  size='30' Color='info'}
{Progress  size='40' Color='warning'}
{Progress  size='60' Color='danger' cls='mb-5'}




{Divider type='br'}



### Cards

{Code type='php'}// Row para agrupar
// Col = numero de Columna que sume 12 en total en el Bloque
// title =  titulo
// img = imagen
// cls = css class
{Esc}
{Row}
	{Card num='4' title='heart' img='imagen-1.jpg'}
		Row que sumen 12 en total
	{/Card}
	{Card num='4' title='heart' img='imagen-2.jpg'}
		Row que sumen 12 en total
	{/Card}
{/Row}
{/Esc}
{/Code}


{Row}
	{Card col='6' title='heart' img='{Url}/public/background.jpg'}
		Row que sumen 12 en total
	{/Card}
	{Card col='6' title='heart' img='{Url}/public/background.jpg'}
		Row que sumen 12 en total
	{/Card}
{/Row}


{Divider type='br'}


### Accordeones ( se necesita jQuery )

{Code type='php'}// id = id del acordeon
{Esc}{Accordions id='acordeon'}
	// title = el titulo
	// cls = la clase ( si es active el acordeon se expande)
	{Accordion cls='active' title='Acordeon uno'}
	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus sit similique quidem, sint veniam amet nostrum facilis eius consectetur. Doloremque fuga, libero veritatis itaque nisi numquam earum. Ipsum explicabo, quasi.
	{/Accordion}

	{Accordion  title='Acordeon dos'}
	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic quam quasi, officia nulla est possimus fugit nesciunt, dolores dolore eaque. Consequatur, ipsa. Voluptas, laborum voluptatum aliquid doloribus quos praesentium quod.
	{/Accordion}

	{Acordeon  title='Acordeon tres'}
	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero aperiam nemo adipisci cumque qui vitae nihil. Consequatur quo explicabo dolore quas, autem, temporibus repellendus nostrum qui in necessitatibus optio, non.
	{/Accordion}

{/Accordions}{/Esc}
{/Code}

{Divider type='br'}


{Row}
{Col num=6}
{Accordions id='acordeon'}

{Accordion cls='active' title='Acordeon 1'}
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus sit similique quidem, sint veniam amet nostrum facilis eius consectetur. Doloremque fuga, libero veritatis itaque nisi numquam earum. Ipsum explicabo, quasi.
{/Accordion}

{Accordion  title='Acordeon 2'}
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic quam quasi, officia nulla est possimus fugit nesciunt, dolores dolore eaque. Consequatur, ipsa. Voluptas, laborum voluptatum aliquid doloribus quos praesentium quod.
{/Accordion}

{Accordion  title='Acordeon 3'}
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero aperiam nemo adipisci cumque qui vitae nihil. Consequatur quo explicabo dolore quas, autem, temporibus repellendus nostrum qui in necessitatibus optio, non.
{/Accordion}

{/Accordions}
{/Col}

{Col num=6}
{Accordions id='acordeon2'}

{Accordion title='Acordeon 4'}
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus sit similique quidem, sint veniam amet nostrum facilis eius consectetur. Doloremque fuga, libero veritatis itaque nisi numquam earum. Ipsum explicabo, quasi.
{/Accordion}

{Accordion  title='Acordeon 5'}
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic quam quasi, officia nulla est possimus fugit nesciunt, dolores dolore eaque. Consequatur, ipsa. Voluptas, laborum voluptatum aliquid doloribus quos praesentium quod.
{/Accordion}

{Accordion  title='Acordeon 6'}
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero aperiam nemo adipisci cumque qui vitae nihil. Consequatur quo explicabo dolore quas, autem, temporibus repellendus nostrum qui in necessitatibus optio, non.
{/Accordion}

{/Accordions}
{/Col}
{/Row}



{Divider type='br'}



## Iconos

{Code type='php'}{Esc}{Icon cls='facebook'}{/Esc}
{Esc}{Icon cls='fab fa-facebook text-dark' href='//facebook.com' title='Facebook'} {Space}{/Esc}
{Esc}{Icon cls='fab fa-twitter text-primary' href='//twitter.com' title='Twitter'} {Space}{/Esc}
{Esc}{Icon cls='fab fa-youtube text-danger' href='//youtube.com' title='Youtube'}{/Esc} 
{/Code}


{Divider type='br'}


{Icon cls='fab fa-facebook text-dark' href='//facebook.com' title='Facebook'} {Space}
{Icon cls='fab fa-twitter text-primary' href='//twitter.com' title='Twitter'} {Space}
{Icon cls='fab fa-youtube text-danger' href='//youtube.com' title='Youtube'} 


{Divider type='br'}


### Imagenes

{Code type='php'}{Esc}{Img src='public/background.jpg'}{/Esc}
{Esc}{Img cls='iframe' src='public/background.jpg'}{/Esc}
{Esc}{Img title='image-1' cls='iframe' url='//google.es' src='public/background.jpg'}{/Esc}
{/Code}


{Row}
	{Col num=6}
		{Img title='image-1'cls='iframe' src='public/background.jpg'}
	{/Col}
	{Col num=6}
		{Img title='image-2' url='//google.es' cls='iframe' src='public/background.jpg'}
	{/Col}
{/Row}


### Animaciones fx

  * Fade animations:
    * fx='fade'
    * fx='fade-up'
    * fx='fade-down'
    * fx='fade-left'
    * fx='fade-right'
    * fx='fade-up-right'
    * fx='fade-up-left'
    * fx='fade-down-right'
    * fx='fade-down-left'

  * Flip animations:
    * fx='flip-up'
    * fx='flip-down'
    * fx='flip-left'
    * fx='flip-right'

  * Slide animations:
    * fx='slide-up'
    * fx='slide-down'
    * fx='slide-left'
    * fx='slide-right'

  * Zoom animations:
    * fx='zoom-in'
    * fx='zoom-in-up'
    * fx='zoom-in-down'
    * fx='zoom-in-left'
    * fx='zoom-in-right'
    * fx='zoom-out'
    * fx='zoom-out-up'
    * fx='zoom-out-down'
    * fx='zoom-out-left'
    * fx='zoom-out-right'