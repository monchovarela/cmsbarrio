<?php  defined('BARRIO') or die('Sin accesso a este script.');


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
    $cls = (isset($cls)) ? $cls : 'iframe mt-2 mb-4';
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    // check src
    if ($src) {
        $html = '<section '.$fx.' class="'.$cls.'">';
        $html .= '<iframe src="https://'.$src.'" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
        // show error if not exists src
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [ src ] no encontrado</span>";
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
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    if ($id) {
        $html = '<section '.$fx.' class="'.$cls.' mt-2 mb-4">';
        $html .= '<iframe src="//www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;

    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [ id ] no encontrado.</span>";
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
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    if ($id) {
        $html = '<section '.$fx.' class="'.$cls.'">';
        $html .= '<iframe src="https://player.vimeo.com/video/'.$id.'" frameborder="0" allowfullscreen></iframe>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;

    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error  [id] no encontrado.</span>";
    }
});


/*
 * =============================================
 *   Video
 *   {Video src='public/videos/movie.mp4'}
 *   {Video cls='iframe' src='public/videos/movie.mp4'}
 * =============================================
 */
Shortcode::add('Video', function ($attrs) {
    extract($attrs);

    $src = (isset($src)) ? $src : '';
    $cls = (isset($cls)) ? $cls : 'mb-3 mt-3';
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    if ($src) {
        $html = '<section '.$fx.' class="'.$cls.'">';
        $html .= '<video class="h-100 w-100 d-flex" src="'.$src.'" autoplay="" autobuffer="" muted="" loop=""> </video>';
        $html .= '</section>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;

    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error  [src] no encontrado.</span>";
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

    $cls = (isset($cls)) ? $cls : 'p-2';
    $color = (isset($color)) ? $color : '';
    $bg = (isset($bg)) ? $bg : '';
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    $content = Parsedown::instance()->text($content);

    $output = Filter::apply(
        'content',
        '<div '.$fx.' class="'.$cls.'" style="color:'.$color.';background-color:'.$bg.'">
        '.$content.'
        </div>'
    );

    $output = preg_replace('/\s+/', ' ', $output);
    if ($content) {
        return $output;
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [ content ] no encontrado</span>";
    }

});



/*
 * ====================================================
 *   Image
 *   {Img src='{url}/public/image.jpg'}
 *   {Img cls='well' src='{url}/public/image.jpg'}
 *   {Img url='//google.es' cls='well' src='{url}/public/image.jpg'}
 *   {Img url='//google.es' title='Hello' cls='well' src='{url}/public/image.jpg'}
 * ====================================================
 */
Shortcode::add('Img', function ($attrs) {
    extract($attrs);

    $src = (isset($src)) ? $src : '';
    $url = (isset($url)) ? $url : '';
    $cls = (isset($cls)) ? $cls : '';
    $ext = (isset($ext)) ? $ext : false;
    $title = (isset($title)) ? $title : '';
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    $site = str_replace('editor','',Url::base());
    $src = rtrim($src, '/');

    $html = '';
    // exits $src
    if ($src) {
        
        if($ext == true || $ext == 'true') $src = '//'.$src;
        else $src = $site.$src;

        if($title){
            if($url) {
                $html = '<a  '.$fx.' href="'.$url.'" title="'.$title.'">
                    <figure>
                        <img class="'.$cls.' top" src="'.$src.'" alt="'.$title.'"/>
                        <figcaption>'.$title.'</figcaption>
                    </figure>
                </a>';
            }else {
                $html = '<figure '.$fx.' >
                        <img class="'.$cls.' top" src="'.$src.'" alt="'.$title.'"/>
                        <figcaption>'.$title.'</figcaption>
                    </figure>';
            }
        }else{
            if($url) {
                $html = '<a  '.$fx.' href="'.$url.'" title="'.$title.'">
                            <img class="'.$cls.' top" src="'.$src.'" />
                        </a>';
            }else {
                $html = '<img '.$fx.' class="'.$cls.' top" src="'.$src.'" alt="'.$title.'"/>';
            }   
        }

        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [ src ] no encontrado.</span>";
    }
});


/*
 * ====================================================
 *   Alert
 *   type = [primary|secondary|success|info|warning|danger|light|dark|link]
 *   {Alert type='primary'} **Primary!** alert-check it out! {/Alert}
 *   {Alert type='primary' cls='text-light'} **Primary!** alert-check it out! {/Alert}
 * ====================================================
 */
Shortcode::add('Alert', function ($attrs, $content) {
    extract($attrs);

    $type = (isset($type)) ? $type : '';
    $cls = (isset($cls)) ? $cls : 'mt-3 mb-3';
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    $content = Parsedown::instance()->text($content);
    $content = Filter::apply(
        'content',
        '<div '.$fx.' class="alert alert-'.$type.' '.$cls.'">
        '.$content.
        '</div>'
    );
    $content = preg_replace('/\s+/', ' ', $content);

    if ($type) {
        return $content;
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [type] no encontrado</span>";
    }
});



/*
 * ====================================================
 * Btn 
 * type = Tipo de boton [ouline] ( opcinal )
 * color = [primary|secondary|success|info|warning|danger|light|dark|href]
 * text = texto del boton
 * id =  id del boton (opcional)
 * href = direcciÃ³n  (opcional)
 * {Btn color='primary' text='Primary' id='btn' href='//example.com'}
 * ====================================================
 */
Shortcode::add('Btn', function ($attrs) {
    extract($attrs);

    $text = (isset($text)) ? $text : '';
    $color = (isset($color)) ? $color : 'primary';
    $id = (isset($id)) ? $id : uniqid();
    $href = (isset($href)) ? $href : '';
    $size = (isset($size)) ? 'btn-'.$size : '';
    $type = (isset($type) == 'outline') ?  'btn-outline-'.$color : 'btn-'.$color;
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    if ($text) {
        $html = '<a '.$fx.' class="mt-3 mb-3 btn '.$size.' '.$type.'" id="'.$id.'" href="'.$href.'" title="'.$text.'">'.$text.'</a>';
        $html = preg_replace('/\s+/', ' ', $html);
        return $html;
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [text] no encontrado</span>";
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
    $type = (isset($type)) ? $type : 'fixed';
    $style = (isset($style)) ? $style : '';
    $bg = (isset($bg)) ? $bg : '';
    $fx = (isset($fx)) ? 'data-aos="'.$fx.'"' : '';

    $imageStyle = '';
    if($bg){
        if(preg_match_all("/\/\//im", $bg)){
            // imagen
            $imageStyle = 'background:url('.$bg.') no-repeat center center '.$type.' transparent;background-size:cover;';
        }else{
            // color
            $imageStyle = 'background:'.$bg.';';
        }
    }

    $output = Filter::apply('content', '<div class="row '.$cls.'" '.$fx.' style="'.$imageStyle.' '.$style.'">'.$content.'</div>');
    $output = preg_replace('/\s+/', ' ', $output);
    return $output;
});




/**
 * ====================================================
 * num = col number
 * cls = class
 *
 * {Col col='8'}
 *      texto en markdown
 * {/Col}
 * ====================================================
 */
Shortcode::add('Col', function ($attrs, $content) {
    extract($attrs);

    $num = (isset($num)) ? $num : '6';
    $cls = (isset($cls)) ? $cls : '';
    $style = (isset($style)) ? $style : '';
    $bg = (isset($bg)) ? $bg : '';
    $fx = (isset($fx)) ? 'data-aos="'.$fx.'"' : '';
    $imageStyle = '';

    if($bg){
        if(preg_match_all("/\/\//im", $bg)){
            $imageStyle = 'background:url('.$bg.') no-repeat center center '.$type.' transparent;background-size:cover;';
        }else{
            $imageStyle = 'background:'.$bg.';';
        }
    }
    $content = Parsedown::instance()->text($content);
    $content = Filter::apply('content', '<div class="col-xl-'.$num.' '.$cls.'" '.$fx.'  style="'.$imageStyle.' '.$style.'">'.$content.'</div>');
    $content = preg_replace('/\s+/', ' ', $content);
    return $content;
});


/**
 * ====================================================
 * size = Tamaño de la barra
 * color = [success | info | warning | danger ]
 * clase = otra clase
 * {Progress  size='25' color='primary'}
 * ====================================================
 */
Shortcode::add('Progress', function ($attrs) {
    extract($attrs);
    // atributos
    $size = (isset($size)) ? $size : '25';
    $color = (isset($color)) ? $color : 'primary';
    $cls = (isset($cls)) ? $cls : 'mt-2 mb-2';
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    // enseñamos
    $html = '<div '.$fx.' class="progress '.$cls.'">';
    $html .='   <div class="progress-bar bg-'.$color.'" role="progressbar" style="width:'.$size.'%" aria-valuenow="'.$size.'" aria-valuemin="0" aria-valuemax="100"></div>';
    $html .='</div>';
    $html = preg_replace('/\s+/', ' ', $html);
    return $html;
});




/**
 * ====================================================
 *  Card
 *  - col = Numero bloques que sumen 12 en total
 *  - title = titulo
 *  - cls = css class
 *  - img = imagen
 *   {Card col='4? title='heart' img='{url}/content/imagenes/sin-imagen.svg'}
 *       bloques que sumen 12 en total
 *   {/Card}
 * ====================================================
 */
Shortcode::add('Card', function ($attrs, $content) {
    extract($attrs);

    $title = (isset($title)) ? $title : '';
    $img = (isset($img)) ? $img : '';
    $col = (isset($col)) ? $col : '4';
    $cls = (isset($cls)) ? $cls : '';
    $fx = (isset($fx)) ? 'data-aos="'.$fx.'"' : '';

    $site = str_replace('editor','',Url::base());
    $img = rtrim($img, '/');
    $img = $site.$img;

    $content = Parsedown::instance()->text($content);
    $output = Filter::apply('content', '<div class="card-text">'.$content.'</div>');
    $html = '<div class="col-md-'.$col.' mb-3" '.$fx.'>';
    $html .= '<div class="card '.$cls.'">';

    if($img){

        $html .= '<div class="card-thumb">
                    <img class="card-img-top" src="'.$img.'" alt="'.$title.'">
                </div>';
    }

    $html .= '  <div class="card-body p-3">';
    $html .= '    <h3 class="card-title">'.$title.'</h3>';
    $html .=      $output;
    $html .= '  </div>';
    $html .= '</div>';
    $html .= '</div>';
    $html = preg_replace('/\s+/', ' ', $html);

    if ($content) {
        return $html;
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [content] no encontrado</span>";
    }
});



/**
 * ====================================================
 * id = unique id
 * {Accordions id='acordeon'}Texto tarjeta{/Accordions}
 * ====================================================
 */
Shortcode::add('Accordions', function ($attrs, $content) {
    extract($attrs);
    $id = (isset($id)) ? $id : 'acordeon';
    $cls = (isset($cls)) ? $cls : 'mt-2 mb-2';
    $content = Filter::apply('content', $content);
    $content = Parsedown::instance()->text($content);
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    $html = '<div '.$fx.' id="'.$id.'"  class="accordion '.$cls.' ">'.$content.'</div>';
    $html = preg_replace('/\s+/', ' ', $html);

    if ($content) {
        return $html;
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [content] no encontrado</span>";
    }
});

/**
 * ====================================================
 * title = el titulo
 * cls = extra classes
 * {Acordion  cñase="active" title='Titulo'}Texto oculto{/Acordion}
 * ====================================================
 */
Shortcode::add('Accordion', function ($attrs, $content) {
    extract($attrs);

    $parent = (isset($parent)) ? $parent : 'acordeon';
    $title = (isset($title)) ? $title : 'Titulo vacio';
    $id = (isset($id)) ? $id : 'acordeon1';
    $cls = (isset($cls)) ? $cls : '';
    $show = ($cls == 'active') ? 'd-block' : 'd-none';
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    $content = Parsedown::instance()->text($content);
    $content = Filter::apply('content', '<div class="accordion-content '.$show.'">'.$content.'</div>');

    $html = '<div '.$fx.' class="accordion-title text-success">';
    $html .= '  <a class="'.$cls.'">'.$title.'</a>';
    $html .= '</div>';
    $html .= $content;
    $html = preg_replace('/\s+/', ' ', $html);

    if ($content) {
        return $html;
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [content] no encontrado</span>";
    }
});



/**
 * ====================================================
 * href = href to
 * cls = custom class
 * title = for SEO
 * {Icon cls='fa fa-phone'}
 * {Icon cls='fa fa-phone' href='624584452'}
 * {Icon cls='fa fa-phone' title='Go to' href='624584452'}
 * ====================================================
 */
Shortcode::add('Icon', function ($atributos) {
    extract($atributos);

    $href = (isset($href)) ? $href : '';
    $cls = (isset($cls)) ? $cls : 'mr-2';
    $title = (isset($title)) ? $title : '';
    $fx = (isset($fx)) ? ' data-aos="'.$fx.'" ': '';

    if ($cls) {
        if($href){
            if($title){
                $html = '<a '.$fx.' class="hasIcon" href="'.$href.'" title="'.$title.'"><i class="'.$cls.'"></i> &nbsp; &nbsp; '.$title.'</a>';
                $html = preg_replace('/\s+/', ' ', $html);
                return $html;
            }else{
                $html = '<a '.$fx.' class="hasIcon" href="'.$href.'"><i class="'.$cls.'"></i></a>';
                $html = preg_replace('/\s+/', ' ', $html);
                return $html;
            }
        }else{
            $html = '<i class="'.$cls.'"></i> ';
            $html = preg_replace('/\s+/', ' ', $html);
            return $html;
        }
    } else {
        return "<span style=\"display: inline-block; background: #f55; color: white; padding: 2px 8px; border-radius: 4px; font-family: 'Lucida Console', Monaco, monospace, sans-serif; font-size: 80%\"><b>Ups :(</b>: Error [cls] no encontrado/span>";
    }
});


/**
 * ====================================================
 * Php
 * {Php}echo 'holas';{/Php}
 * ====================================================
 */
Shortcode::add('Php', function ($attr, $content) {
    ob_start();
    eval("$content");
    return ob_get_clean();
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
    $num = (isset($num)) ? $num : '5';
    if($type !== 'br') return '<hr class="mt-'.$num.' mb-'.$num.'" />';
    else return '<br class="mt-'.$num.' mb-'.$num.'" />';
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
