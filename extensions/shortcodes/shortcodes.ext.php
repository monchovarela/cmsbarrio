<?php  defined('BARRIO') or die('Sin accesso a este script.');


// minify css
if (!function_exists('minify_css')) {
    function minify_css($input)
    {
        if (trim($input) === "") {
            return $input;
        }
        return preg_replace(
            array(
                // Remove comment(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                // Remove unused white-space(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
                '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                // Replace `:0 0 0 0` with `:0`
                '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                // Replace `background-position:0` with `background-position:0 0`
                '#(background-position):0(?=[;\}])#si',
                // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
                '#(?<=[\s:,\-])0+\.(\d+)#s',
                // Minify string value
                '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
                '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                // Minify HEX color code
                '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                // Replace `(border|outline):none` with `(border|outline):0`
                '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                // Remove empty selector(s)
                '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
            ),
            array(
                '$1',
                '$1$2$3$4$5$6$7',
                '$1',
                ':0',
                '$1:0 0',
                '.$1',
                '$1$3',
                '$1$2$4$5',
                '$1$2$3',
                '$1:0',
                '$1$2'
            ),
            $input
        );
    }
}
// minify javascript
if (!function_exists('minify_js')) {
    // JavaScript Minifier
    function minify_js($input)
    {
        if (trim($input) === "") {
            return $input;
        }
        return preg_replace(
            array(
                // Remove comment(s)
                '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
                // Remove white-space(s) outside the string and regex
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
                // Remove the last semicolon
                '#;+\}#',
                // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
                '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
                // --ibid. From `foo['bar']` to `foo.bar`
                '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
            ),
            array(
                '$1',
                '$1$2',
                '}',
                '$1$3',
                '$1.$3'
            ),
            $input
        );
    }
}



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

    $cls = (isset($cls)) ? 'class="'.$cls.'"' : 'class="txt"';
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
    $ext = (isset($ext)) ? ($ext = ($ext == 'true') ? true : false) : false;
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
 * {Styles}body{};{/Styles}
 * {Styles minify=true}body{};{/Styles}
 * ================================
 */
Barrio::addShortcode('Styles', function ($attrs, $content = '') {
    extract($attrs);
    $minify = (isset($minify)) ? $minify : false;
    if ($content) {
        // convert string to bool
        $minify = ($minify == 'true') ? true : false;
        // minify or not
        $content = ($minify == true) ? minify_css($content) : $content;
        return Barrio::addAction('head', function () use ($content) {
            $html = "\n\n\t";
            $html .= '<!-- Shortcode css -->';
            $html .= "\n\t";
            $html .= '<style rel="stylesheet">'.$content.'</style>';
            $html .= "\n\n";
            echo $html;
        });
    } else {
        return Barrio::error('Error [ contenido ] no encontrado en Style Shortcode');
    }
});
/*
 * ================================
 * Style file
 * {Style href='//example.css'}
 * ================================
 */
Barrio::addShortcode('Style', function ($attrs) {
    extract($attrs);
    $href = (isset($href)) ? $href : '';
    if ($href) {
        return Barrio::addAction('head', function () use ($href) {
            echo '<link rel="stylesheet" href="https://'.$href.'"/>';
        });
    } else {
        return Barrio::error('Error [ href ] no encontrado');
    }
});
/*
 * ================================
 * Scripts
 * {Scripts}console.log("test");{/Scripts}
 * {Scripts minify=true}console.log("test");{/Scripts}
 * ================================
 */
Barrio::addShortcode('Scripts', function ($attrs, $content = '') {
    extract($attrs);
    $minify = (isset($minify)) ? $minify : false;
    if ($content) {
        // convert string to bool
        $minify = ($minify == 'true') ? true : false;
        // minify or not
        $content = ($minify == true) ? minify_js($content) : $content;
        return Barrio::addAction('footer', function () use ($content) {
            $html = "\n\n\t";
            $html .= '<!-- Shortcode scripts -->';
            $html .= "\n\t";
            $html .= '<script rel="javascript">'.$content.'</script>';
            $html .= "\n\n";
            echo $html;
        });
    } else {
        return Barrio::error('Error [ contenido ] no encontrado');
    }
});
/*
 * ================================
 * Script file
 * {Script src='//example.js'}
 * ================================
 */
Barrio::addShortcode('Script', function ($attrs) {
    extract($attrs);
    $src = (isset($src)) ? $src : '';
    if ($src) {
        return Barrio::addAction('footer', function () use ($src) {
            echo '<script rel="javascript" src="https:'.$src.'"></script>';
        });
    } else {
        return Barrio::error('Error [ src ] no encontrado');
    }
});


/*
 * ================================
 * Escape or convert html tags
 * {Esc}echo 'holas';{/Esc}
 * ================================
 */
Barrio::addShortcode('Esc', function ($attr, $content) {
    $output = htmlspecialchars("$content", ENT_QUOTES);
    $output = str_replace('&#039;',"'",$output);
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
Barrio::addShortcode('Code', function ($attrs, $content) {
    extract($attrs);
    $type = (isset($type)) ? $type : 'php';
    if ($content) {
        $content = htmlentities(html_entity_decode($content));
        $output = Barrio::applyFilter('content', '<pre class="line-numbers language-'.$type.'"><code class="language-'.$type.'">'.$content.'</code></pre>');
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
    $cls = (isset($cls)) ? $cls : '';
    $color = (isset($color)) ? 'style="border-color:'.$color.'"' : '';
    if ($type !== 'br') {
        return '<hr class="'.$cls.' mt-'.$num.' mb-'.$num.'" '.$color.'/>';
    } else {
        return '<br class="'.$cls.' mt-'.$num.' mb-'.$num.'" '.$color.'/>';
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
