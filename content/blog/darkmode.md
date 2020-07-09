Title: Dark mode
Description: Que es y cómo funciona.
Keywords: dark,mode
Author: Moncho Varela
Date: 05/05/2020
Template: index

----

{Style}
  :root {
    --nc-tx-1: #fff;
    --nc-tx-2: #dedede;
    --nc-bg-1: #1d1d1d;
    --nc-bg-2: #121212;
    --nc-bg-3: #000;
    --nc-lk-1: #fffd6f;
    --nc-lk-2: #c8cd1c;
    --nc-lk-tx: #000;
    --nc-ac-1: #03A9F4;
    --nc-ac-tx: #000000;
  }
{/Style}

Desde hace un tiempo me encanta navegar en sitios con opción a diseños obscuros o dark mode, no por su estética sino por la comodidad para mis ojos. Afortunadamente para mis desafortunados ojos, cada vez más sitios web están implementando esta opción y así lo hice yo también para BTemplates. Agregar una opción de diseño obscuro o dark es más sencillo de lo que parece y es posible realizarlo usando CSS y quizá un poco de Javascript.

### Beneficios de un dark mode

Los beneficios de agregar esta opción para tu sitio web son:

- Ofreces una mejor experiencia a los usuarios que nos agrega navegar en este modo.

- Una mejor integración con la experiencia del navegador o sistema operativo. Explicado abajo.

- Los diseños obscuros consumen **menor energía eléctrica,** así que indirectamente tiene un beneficio ecológico.


{Code}:root {
	--background: white;
}
@media (prefers-color-scheme: dark) {
    :root {
		--background: black;
    }
}{/Code}