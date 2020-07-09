<?php  defined('BARRIO') or die('Sin accesso a este script.');

/*
 * ================================
 * Site url
 * ================================
 */
Barrio::addShortcode('Site_url', function ($attrs) {
    extract($attrs);
    return Barrio::urlBase();
});

/*
 * ================================
 * Site current
 * ================================
 */
Barrio::addShortcode('Site_current', function ($attrs) {
    extract($attrs);
    return Barrio::urlCurrent();
});


/*
 * ================================
 * Details
 * {Details title='example'}Markdown Hidden content {/Details}
 * ================================
 */
Barrio::addShortcode('Details', function ($attrs, $content) {
    extract($attrs);

    $title = (isset($title)) ? $title : 'Info';
    
    $content = Parsedown::instance()->text($content);
    $output = Barrio::applyFilter('content', '<details><summary>'.$title.'</summary>'.$content.'</details>');
    $output = preg_replace('/\s+/', ' ', $output);

    if ($content) {
        return $output;
    } else {
        return Barrio::error('Error [ content ] no encontrado');
    }
});


/*
 * ================================
 * Iframe
 * {iframe src='monchovarela.es'}
 * ================================
 */
Barrio::addShortcode('Iframe', function ($attrs) {

    // extrac attributes
    extract($attrs);

    // src url
    $src = (isset($src)) ? $src : '';
    $cls = (isset($cls)) ? $cls : 'iframe';

    // check src
    if ($src) {
        $html = '<section class="'.$cls.'">';
        $html .= '<iframe src="https://'.$src.'" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    // show error if not exists src
    } else {
        return Barrio::error('Error [ src ] no encontrado');
    }
});

/*
 * =============================================
 *   Youtube
 *   {Youtube id='GxEc46k46gg'}
 *   {Youtube cls='well' id='GxEc46k46gg'}
 * =============================================
 */
Barrio::addShortcode('Youtube', function ($attrs) {
    extract($attrs);

    $id = (isset($id)) ? $id : '';
    $cls = (isset($cls)) ? $cls : 'iframe';

    if ($id) {
        $html = '<section class="'.$cls.'">';
        $html .= '<iframe src="//www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return Barrio::error('Error [ id ] no encontrado');
    }
});


/*
 * =============================================
 *   Vimeo
 *   {Vimeo id='149129821'}
 *   {Vimeo cls='iframe' id='149129821'}
 * =============================================
 */
Barrio::addShortcode('Vimeo', function ($attrs) {
    extract($attrs);

    $id = (isset($id)) ? $id : '';
    $cls = (isset($cls)) ? $cls : 'iframe';

    if ($id) {
        $html = '<section class="'.$cls.'">';
        $html .= '<iframe src="https://player.vimeo.com/video/'.$id.'" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return Barrio::error('Error [ id ] no encontrado');
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
Barrio::addShortcode('Video', function ($attrs) {
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
        $url = Barrio::urlBase();
        $src = ($ext) ? $src : $url.'/'.$src;
        $html = '<section class="'.$cls.'">';
        $html .= '<video src="'.$src.'" '.$controls.' '.$autoplay.' '.$autobuffer.'  '.$muted.' '.$loop.'> </video>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return Barrio::error('Error [ src ] no encontrado');
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
Barrio::addShortcode('Text', function ($attrs, $content) {
    extract($attrs);

    $cls = (isset($cls)) ? 'class="'.$cls.'"' : '';
    $color = (isset($color)) ? 'color:'.$color.';' : '';
    $bg = (isset($bg)) ? 'background-color:'.$bg.';' : '';

    $content = Parsedown::instance()->text($content);
    $output = Barrio::applyFilter('content', '<div '.$cls.' style="'.$color.' '.$bg.'">'.$content.'</div>');
    $output = preg_replace('/\s+/', ' ', $output);

    if ($content) {
        return $output;
    } else {
        return Barrio::error('Error [ content ] no encontrado');
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
Barrio::addShortcode('Img', function ($attrs) {
    extract($attrs);

    $src = (isset($src)) ? $src : '';
    $url = (isset($url)) ? $url : '';
    $cls = (isset($cls)) ? 'class="'.$cls.'"' : '';
    $ext = (isset($ext)) ? $ext : false;
    $title = (isset($title)) ? $title : '';
    $site = Barrio::urlBase();
    $src = rtrim($src, '/');

    $html = '';
    // exits $src
    if ($src) {
        if ($ext == true || $ext == 'true') {
            $src = '//'.$src;
        } else {
            $src = Barrio::urlBase().'/'.$src;
        }

        if ($title) {
            if ($url) {
                $html = '<a href="'.$url.'" title="'.$title.'"><figure><img '.$cls.' src="'.$src.'" alt="'.$title.'"/><figcaption>'.$title.'</figcaption></figure></a>';
            } else {
                $html = '<figure><img '.$cls.' src="'.$src.'" alt="'.$title.'"/><figcaption>'.$title.'</figcaption></figure>';
            }
        } else {
            if ($url) {
                $html = '<a  href="'.$url.'" title="'.$title.'"><img '.$cls.' src="'.$src.'" /></a>';
            } else {
                $html = '<img '.$cls.' src="'.$src.'" alt="'.$title.'"/>';
            }
        }

        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return Barrio::error('Error [ src ] no encontrado');
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
Barrio::addShortcode('Row', function ($attrs, $content) {
    extract($attrs);
    $cls = (isset($cls)) ? $cls : '';
    $output = Barrio::applyFilter('content', '<div class="row '.$cls.'">'.$content.'</div>');
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
Barrio::addShortcode('Col', function ($attrs, $content) {
    extract($attrs);
    $num = (isset($num)) ? $num : '6';
    $cls = (isset($cls)) ? $cls : '';
    $content = Parsedown::instance()->text($content);
    $content = Barrio::applyFilter('content', '<div class="col-'.$num.' '.$cls.'">'.$content.'</div>');
    $content = preg_replace('/\s+/', ' ', $content);
    return $content;
});


/*
 * ================================
 * Style
 * {Style}body{};{/Style}
 * ================================
 */
Barrio::addShortcode('Style', function ($attrs, $content = '') {
    extract($attrs);
    if ($content) {
        return Barrio::addAction('head', function () use ($content) {
            echo'<style rel="stylesheet">'.$content.'</style>';
        });
    } else {
        return Barrio::error('Error [ contenido ] no encontrado');
    }
});

/*
 * ================================
 * Script
 * {Script}console.log("test");{/Script}
 * ================================
 */
Barrio::addShortcode('Script', function ($attrs, $content = '') {
    extract($attrs);
    if ($content) {
        return Barrio::addAction('footer', function () use ($content) {
            echo '<script rel="javascript">'.$content.'</script>';
        });
    } else {
        return Barrio::error('Error [ contenido ] no encontrado');
    }
});

/*
 * ================================
 * Escape or convert html tags
 * {Esc}echo 'holas';{/Esc}
 * ================================
 */
Barrio::addShortcode('Esc', function ($attr, $content) {
    return htmlspecialchars("$content", ENT_QUOTES);
});

/**
 * ====================================================
 *  Code
 *   {Code type='php'}
 *       bloques que sumen 12 en total
 *   {/Code}
 * ====================================================
 */
Barrio::addShortcode('Code', function ($attrs, $content) {
    extract($attrs);
    $code = (isset($code)) ? $code : 'php';
    if ($content) {
        $content = htmlentities(html_entity_decode($content));
        $output = Barrio::applyFilter('content', '<pre class="line-numbers language-'.$code.'"><code class="language-'.$code.'">'.$content.'</code></pre>');
        return $output;
    } else {
        return Barrio::error('Error [ contenido ] no encontrado');
    }
});



/*
 * ================================
 * Config
 * Get config {Config name='title'}
 * ================================
 */
Barrio::addShortcode('Config', function ($attrs) {
    extract($attrs);
    if ($name) {
        return Barrio::$config[$name];
    } else {
        return Barrio::error('Error [ name ] no encontrado');
    }
});


/**
 * ====================================================
 *   {Divider}
 *   {Divider num='2'}
 *   {Divider type='br' num='2'}
 * ====================================================
 */
Barrio::addShortcode('Divider', function ($attrs) {
    extract($attrs);
    $type = (isset($type)) ? $type : 'hr';
    $num = (isset($num)) ? $num : '2';
    if ($type !== 'br') {
        return '<hr class="mt-'.$num.' mb-'.$num.'" />';
    } else {
        return '<br class="mt-'.$num.' mb-'.$num.'" />';
    }
});


/**
 * ====================================================
 *   {Space}
 *   {Space num='2'}
 * ====================================================
 */
Barrio::addShortcode('Space', function ($attrs) {
    extract($attrs);
    $num = (isset($num)) ? $num : '2';
    return str_repeat('&nbsp;', $num);
});


/*
 * ====================================================
 * Btn
 * type = Tipo de boton [ouline] ( opcinal )
 * text = texto del boton
 * id =  id del boton (opcional)
 * href = dirección  (opcional)
 * {Btn  text='hola' id='btn' href='//example.com'}
 * ====================================================
 */
Barrio::addShortcode('Btn', function ($attrs) {
    extract($attrs);

    $text = (isset($text)) ? $text : '';
    $cls = (isset($cls)) ? 'class="'.$cls.'"' : '';
    $id = (isset($id)) ? 'id="'.$id.'"' : '';
    $href = (isset($href)) ? $href : '#';

    if ($text) {
        $html = '<a '.$cls.' '.$id.' href="'.$href.'" title="'.$text.'"><button>'.$text.'</button></a>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return Barrio::error('Error [ text ] no encontrado');
    }
});


/**
 * ====================================================
 * Php
 * {Php}echo 'holas';{/Php}
 * ====================================================
 */
Barrio::addShortcode('Php', function ($attr, $content) {
    ob_start();
    eval("$content");
    return ob_get_clean();
});
