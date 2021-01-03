<?php defined('BARRIO') or die('Sin accesso a este script.');

function showError($txt)
{
    $content = "<span style=\"display: inline-block; background: #f55; color: white; padding: 4px 10px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b style=\"color:#FFEB3B;\">Error</b>: {$txt}</span>";
    return $content;
}

/*
 * ================================
 * Site url
 * ================================
 */
Shortcode::add('Site_url', function ($attrs) {
    extract($attrs);
    return Url::base();
});

/*
 * ================================
 * Site current
 * ================================
 */
Shortcode::add('Site_current', function ($attrs) {
    extract($attrs);
    return Url::current();
});

/*
 * ================================
 * Details
 * {Details title='example'}Markdown Hidden content {/Details}
 * ================================
 */
Shortcode::add('Details', function ($attrs, $content) {
    extract($attrs);

    $title = (isset($title)) ? $title : 'Info';

    $content = Parsedown::instance()->text($content);
    $output = Filter::apply('content', '<details><summary>' . $title . '</summary>' . $content . '</details>');
    $output = preg_replace('/\s+/', ' ', $output);

    if ($content) {
        return $output;
    } else {
        return showError('Error [ content ] no encontrado');
    }
});

/*
 * ================================
 * Iframe
 * {iframe src='monchovarela.es'}
 * ================================
 */
Shortcode::add('Iframe', function ($attrs) {

    // extrac attributes
    extract($attrs);

    // src url
    $src = (isset($src)) ? $src : '';
    $cls = (isset($cls)) ? $cls : 'iframe';

    // check src
    if ($src) {
        $html = '<section class="' . $cls . '">';
        $html .= '<iframe src="https://' . $src . '" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
        // show error if not exists src
    } else {
        return showError('Error [ src ] no encontrado');
    }
});

/*
 * =============================================
 *   Youtube
 *   {Youtube id='GxEc46k46gg'}
 *   {Youtube cls='well' id='GxEc46k46gg'}
 * =============================================
 */
Shortcode::add('Youtube', function ($attrs) {
    extract($attrs);

    $id = (isset($id)) ? $id : '';
    $cls = (isset($cls)) ? $cls : 'iframe';

    if ($id) {
        $html = '<section class="' . $cls . '">';
        $html .= '<iframe src="//www.youtube.com/embed/' . $id . '" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return showError('Error [ id ] no encontrado');
    }
});

/*
 * =============================================
 *   Vimeo
 *   {Vimeo id='149129821'}
 *   {Vimeo cls='iframe' id='149129821'}
 * =============================================
 */
Shortcode::add('Vimeo', function ($attrs) {
    extract($attrs);

    $id = (isset($id)) ? $id : '';
    $cls = (isset($cls)) ? $cls : 'iframe';

    if ($id) {
        $html = '<section class="' . $cls . '">';
        $html .= '<iframe src="https://player.vimeo.com/video/' . $id . '" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return showError('Error [ id ] no encontrado');
    }
});

/*
 * =============================================
 *   Video
 *   {Video src='public/videos/movie.mp4'}
 *   {Video cls='iframe' src='public/videos/movie.mp4'}
 *   {Video cls='iframe' autoplay='' src='public/videos/movie.mp4'}
 *   {Video cls='iframe' autoplay='' autobuffer='' src='public/videos/movie.mp4'}
 *   {Video cls='iframe' autoplay='' autobuffer='' muted='' src='public/videos/movie.mp4'}
 *   {Video cls='iframe' autoplay='' autobuffer='' muted='' loop='' src='public/videos/movie.mp4'}
 * =============================================
 */
Shortcode::add('Video', function ($attrs) {
    extract($attrs);

    $src = (isset($src)) ? $src : '';
    $ext = (isset($ext)) ? $ext : false;
    $cls = (isset($cls)) ? $cls : 'video';

    $autoplay = (isset($autoplay)) ? 'autoplay="true"' : '';
    $autobuffer = (isset($autobuffer)) ? 'autobuffer="true"' : '';
    $muted = (isset($muted)) ? 'muted="true"' : '';
    $loop = (isset($loop)) ? 'loop="true"' : '';
    $controls = (isset($controls)) ? 'controls="true"' : '';

    if ($src) {
        $url = Url::base();
        $src = ($ext) ? $src : $url . '/' . $src;
        $html = '<section class="' . $cls . '">';
        $html .= '<video src="' . $src . '" ' . $controls . ' ' . $autoplay . ' ' . $autobuffer . '  ' . $muted . ' ' . $loop . '> </video>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return showError('Error [ src ] no encontrado');
    }
});

/*
 * ====================================================
 *   Texto
 *   {Text}Color texto{/Text}
 *   {Text color='white'}Color texto{/Text}
 *   {Text bg='blue' color='white'}Color texto{/Text}
 * ====================================================
 */
Shortcode::add('Text', function ($attrs, $content) {
    extract($attrs);

    $cls = (isset($cls)) ? 'class="' . $cls . '"' : 'class="txt"';
    $color = (isset($color)) ? 'color:' . $color . ';' : '';
    $bg = (isset($bg)) ? 'background-color:' . $bg . ';' : '';

    $content = Parsedown::instance()->text($content);
    $output = Filter::apply('content', '<div ' . $cls . ' style="' . $color . ' ' . $bg . '">' . $content . '</div>');
    $output = preg_replace('/\s+/', ' ', $output);

    if ($content) {
        return $output;
    } else {
        return showError('Error [ content ] no encontrado');
    }
});

/*
 * ====================================================
 *   Image
 *   {Img src='{url}/public/image.jpg'}
 *   {Img cls='well' src='{url}/public/image.jpg'}
 *   {Img url='//google.es' cls='well' src='{url}/public/image.jpg'}
 *   {Img url='//google.es' title='Hello' cls='well' src='{url}/public/image.jpg'}
 *   {Img url='//google.es' title='Hello' cls='well' ext='' src='//otraurl.com/public/image.jpg'}
 * ====================================================
 */
Shortcode::add('Img', function ($attrs) {
    extract($attrs);

    $src = (isset($src)) ? $src : '';
    $url = (isset($url)) ? $url : '';
    $cls = (isset($cls)) ? 'class="' . $cls . '"' : '';
    $ext = (isset($ext)) ? ($ext = ($ext == 'true') ? true : false) : false;
    $title = (isset($title)) ? $title : '';
    $site = Url::base();
    $src = rtrim($src, '/');

    $html = '';
    // exits $src
    if ($src) {
        if ($ext == true || $ext == 'true') {
            $src = '//' . $src;
        } else {
            $src = Url::base() . '/' . $src;
        }

        if ($title) {
            if ($url) {
                $html = '<a href="' . $url . '" title="' . $title . '"><figure><img ' . $cls . ' src="' . $src . '" alt="' . $title . '"/><figcaption>' . $title . '</figcaption></figure></a>';
            } else {
                $html = '<figure><img ' . $cls . ' src="' . $src . '" alt="' . $title . '"/><figcaption>' . $title . '</figcaption></figure>';
            }
        } else {
            if ($url) {
                $html = '<a  href="' . $url . '" title="' . $title . '"><img ' . $cls . ' src="' . $src . '" /></a>';
            } else {
                $html = '<img ' . $cls . ' src="' . $src . '" alt="' . $title . '"/>';
            }
        }

        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return showError('Error [ src ] no encontrado');
    }
});

/*
 * ====================================================
 *  Row
 * - cls = css class
 *   {Row}
 *       bloques que sumen 12 en total
 *   {/Row}
 * ====================================================
 */
Shortcode::add('Row', function ($attrs, $content) {
    extract($attrs);
    $cls = (isset($cls)) ? $cls : '';
    $output = Filter::apply('content', '<div class="row ' . $cls . '">' . $content . '</div>');
    $output = preg_replace('/\s+/', ' ', $output);
    return $output;
});

/**
 * ====================================================
 * num = col number
 * cls = class
 *
 * {Col num='8'}
 *      texto en markdown
 * {/Col}
 * ====================================================
 */
Shortcode::add('Col', function ($attrs, $content) {
    extract($attrs);
    $num = (isset($num)) ? $num : '6';
    $cls = (isset($cls)) ? $cls : '';
    $content = Parsedown::instance()->text($content);
    $content = Filter::apply('content', '<div class="col-' . $num . ' ' . $cls . '">' . $content . '</div>');
    $content = preg_replace('/\s+/', ' ', $content);
    return $content;
});

/*
 * ================================
 * Escape or convert html tags
 * {Esc}echo 'holas';{/Esc}
 * ================================
 */
Shortcode::add('Esc', function ($attr, $content) {
    $output = htmlspecialchars("$content", ENT_QUOTES);
    $output = str_replace('&#039;', "'", $output);
    return $output;
});

/**
 * ====================================================
 *  Code
 *   {Code type='php'}
 *       bloques que sumen 12 en total
 *   {/Code}
 * ====================================================
 */
Shortcode::add('Code', function ($attrs, $content) {
    extract($attrs);
    $type = (isset($type)) ? $type : 'php';
    if ($content) {
        $content = htmlentities(html_entity_decode($content));
        $output = Filter::apply('content', '<pre class="line-numbers language-' . $type . '"><code class="language-' . $type . '">' . $content . '</code></pre>');
        return $output;
    } else {
        return showError('Error [ contenido ] no encontrado');
    }
});

/**
 * ====================================================
 *   {Divider}
 *   {Divider num='2'}
 *   {Divider type='br' num='2'}
 * ====================================================
 */
Shortcode::add('Divider', function ($attrs) {
    extract($attrs);
    $type = (isset($type)) ? $type : 'hr';
    $num = (isset($num)) ? $num : '2';
    $cls = (isset($cls)) ? $cls : '';
    $color = (isset($color)) ? 'style="border-color:' . $color . '"' : '';
    if ($type !== 'br') {
        return '<hr class="' . $cls . ' mt-' . $num . ' mb-' . $num . '" ' . $color . '/>';
    } else {
        return '<br class="' . $cls . ' mt-' . $num . ' mb-' . $num . '" ' . $color . '/>';
    }
});

/**
 * ====================================================
 *   {Space}
 *   {Space num='2'}
 * ====================================================
 */
Shortcode::add('Space', function ($attrs) {
    extract($attrs);
    $num = (isset($num)) ? $num : '2';
    return str_repeat('&nbsp;', $num);
});

/*
 * ====================================================
 * Btn
 * text = texto del boton
 * id =  id del boton (opcional)
 * href = dirección  (opcional)
 * {Btn  text='hola' id='btn' href='//example.com'}
 * ====================================================
 */
Shortcode::add('Btn', function ($attrs) {
    extract($attrs);

    $text = (isset($text)) ? $text : '';
    $cls = (isset($cls)) ? 'class="' . $cls . '"' : '';
    $id = (isset($id)) ? 'id="' . $id . '"' : '';
    $href = (isset($href)) ? $href : '#';

    if ($text) {
        $html = '<a ' . $cls . ' ' . $id . ' href="' . $href . '" title="' . $text . '"><button>' . $text . '</button></a>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return showError('Error [ text ] no encontrado');
    }
});
