<?php  defined('BARRIO') or die('Sin accesso a este script.');


$theme = Barrio::$config['theme'];

define('THEME_NAME',$theme);
define('THEME_INC',THEMES.'/'.$theme.'/inc/');
define('THEME_BLOCK',THEMES.'/'.$theme.'/blocks/');
define('ICONS',Barrio::urlBase().'/themes/default/assets/img/icons/');


/**
 *  Function que genera un menu con submenu
 */
if (!function_exists('arrayOfMenu')) 
{
    /**
     * Transform array to menu
     *
     * @param array $nav the array of navigaiton
     * 
     * @return string
     */
    function arrayOfMenu($nav)
    {
        $html = '';
        foreach ($nav as $k => $v) {
            // key exists
            if (array_key_exists($k, $nav)) 
            {
                // not empty
                if ($k != '') 
                {
                    // external page
                    if (preg_match("/http/i", $k)) 
                    {
                        $html .= '<li class="nav-item">';
                        $html .= '<a class="nav-link" href="'.$k.'">'.ucfirst($v).'</a>';
                        $html .= '</li>';
                    } 
                    else if (preg_match("/#/i", $k))
                    {
                        $html .= '<li class="nav-item">';
                        $html .= '<a class="nav-link" data-href="'.$k.'" href="'.$k.'">'.ucfirst($v).'</a>';
                        $html .= '</li>';
                    }
                    else 
                    {
                        // is array
                        if (is_array($v)) 
                        {
                            // dropdown
                            $html .= '<li class="nav-item dropdown">';
                            $html .= '  <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">'.ucfirst($k).'</a>';
                            $html .= '<ul class="dropdown-menu" aria-labelledby="navbarDropdown"> '.arrayOfMenu($v).'</ul>';
                            $html .= '</li>';
                        } 
                        else 
                        {
                            // active page
                            $active = Barrio::urlCurrent();
                            $activeurl = str_replace('/','',$k);
                            if ($active == $activeurl) 
                            {
                                
                                $html .= '<li class="nav-item ">
                                            <a  class="nav-link active" href="'.trim(Barrio::urlBase().$k).'">
                                                '.ucfirst($v).'
                                            </a>
                                        </li>';
                            } 
                            else 
                            {
                                $html .= '<li class="nav-item">
                                            <a  class="nav-link"href="'.trim(Barrio::urlBase().$k).'">
                                                '.ucfirst($v).'
                                            </a>
                                        </li>';
                            }
                        }

                    }
                }
            }
        }
        // show html
        return $html;
    }
}



/**
 * ================================
 *      Incluimos el css
 * ================================
 */
Barrio::addAction('head', function()
{
    $url = Barrio::urlBase().'/themes/default/assets/css';

    $styles = array(
        'fontawesome' => 'https://use.fontawesome.com/releases/v5.0.10/css/all.css',
        'lux' => 'https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/lux/bootstrap.min.css',
        'plugins' => $url.'/plugins.css',
        'main' => $url.'/main.css'
    );

    $html = '';
    foreach ($styles as $key => $val) 
    {
        $html .= '<link rel="stylesheet" type="text/css" href="'.$val.'"/>';
    }

    echo $html;
});



/**
 * ================================
 *      incluimos javascript
 * ================================
 */
Barrio::addAction('footer', function()
{

    $url = Barrio::urlBase().'/themes/default/assets/js';
    $javascript = array(
        'jquery' => 'https://code.jquery.com/jquery-3.2.1.min.js',
        'popper' => 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js',
        'bootstrap' => 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js',
        'plugins' => Barrio::urlBase().'/themes/default/assets/js/plugins.js',
        'main' => $url.'/main.js'
    );

    $html = '';
    foreach ($javascript as $key => $val) 
    {
        $html .= '<script rel="javascript" type="text/javascript" src="'.$val.'"></script>';
    }

    echo $html;
});


/**
 * ====================================
 *      incluimos el plugin de cookie
 * ====================================
 */
Barrio::addAction('head', function()
{

    $src = array(
        'style' => 'https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css',
        'javascript' => 'https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js'
    );

    $html = '<link rel="stylesheet" href="'.$src['style'].'"/>';
    $html .= '<script rel="javascript" src="'.$src['javascript'].'"></script>';
    $html .= '<script>
        window.addEventListener("load", function(){
        window.cookieconsent.initialise({
          "palette": {
            "popup": {
              "background": "#f8f9fa",
              "text": "#373a3c"
            },
            "button": {
              "background": "transparent",
              "text": "#373a3c",
              "border": "#373a3c"
            }
          },
          "position": "bottom-right",
          "content": {
            "message": "Utilizamos cookies propias y de terceros. Si continúa navegando acepta su uso.",
            "dismiss": "Aceptar",
            "link": "Leer mas",
            "href": "'.Barrio::urlBase().'/politica-de-cookies"
          }
        })});
        </script>';

    echo $html;
});


/**
 * ====================================
 *          Formulario de busqueda
 * ====================================
 */
Barrio::addAction('theme_before', function () 
{

    $language = Barrio::$config['search'];

    // demo http://localhost/cmsbarrio/?buscar=
    if (isset($_POST['buscar'])) 
    {
        // http://localhost/cmsbarrio/?buscar=Hola
        // $query = hola
        $query = $_POST['buscar'];
        // check query
        if ($query) 
        {
            // get pages
            $data = Barrio::pages('/', 'date', 'DESC', ['404'], '');
            // get 5 words
            $name = urlencode(substr(trim($query), 0, 5));
            // init results and total
            $results = array();
            $total = 0;
            // loop data
            foreach ($data as $item) 
            {
                // raplace url with data url
                $root = str_replace(Barrio::urlBase(), CONTENT, $item['url']);
                // decode
                $name = urldecode($name);
                // check title description and slug
                if (preg_match("/$name/i", $item['title']) || 
                    preg_match("/$name/i", $item['description']) || 
                    preg_match("/$name/i", $item['slug'])) 
                {
                    // if obtain something show
                    $results[]= array(
                      'title' => (string) $item['title'],
                      'description' => (string) $item['description'],
                      'url' => (string) $item['url']
                    );
                    // count resutls
                    $total++;
                }
            }
            // template
            $html = '<p class="text-primary">'.$language['results_of'].' <span class="text-danger ml-2">'.$total.'</span></p>';

            foreach ($results as $page) 
            {
                $html .= '<!-- card -->';
                $html .= '<div class="card p-3 mb-2">';
                $html .= '  <h3>'.$page['title'].'</h3>';
                $html .= '  <p>'.$page['description'].'</p>';
                $html .= '  <a class="btn btn-link" href="'.$page['url'].'">'.$language['read'].'</a>';
                $html .= '</div><!-- / card -->';
            }

            // if results show
            if ($results) 
            {
                echo $html;
            } 
            else 
            {
                $html .= '<!-- card -->';
                $html .= '<div class="card p-3 mb-2">';
                $html .= '  <h3>'.$language['no_results'].' '.$query.'</h3>';
                $html .= '</div><!-- / card -->';
                echo $html;
            }
        }
    }
});


/**
 * =====================================
 * Paginacion de carpetas
 * Barrio::runAction('pagination');
 * Barrio::runAction('pagination',[$name, $num,true]);
 * =====================================
 */
Barrio::addAction('pagination', function ($name,$num=6,$nav = true) 
{
    // get pages
    $posts = Barrio::pages($name, 'date', 'DESC', ['index','404']);
    // limit
    $limit = ($num) ? $num :  Barrio::$config['pagination'];
    // init
    $blogPosts = array();
    if ($posts) 
    {
        foreach ($posts as $f) 
        {
            // push on blogposts
            array_push($blogPosts, $f);
        }

        // fragment
        $articulos = array_chunk($blogPosts, $limit);
        // get number
        $pgkey = isset($_GET['page']) ? $_GET['page'] : 0;

        $items = $articulos[$pgkey];

        $html = '<!-- section -->';
        $html .= '<section class="posts">';

        foreach ($items as $articulo) 
        {

            $date =  date('d/m/Y', $articulo['date']);
            $html .= '<!-- article -->';
            $html .= '<article class="post float-xl-left">';
            $html .= '<!-- card -->';
            $html .= '<div class="card mb-3">';

            if (!empty($articulo['image'])) 
            {
                $html .= '<div class="top"><img class="card-img-top" src="'.$articulo['image'].'" /></div>';
            }
            elseif ($articulo['video'])
            {
                $html .= '<!-- video -->';
                $html .= '<div class="video" style="height: 20em">';
                $html .= '<video src="'.$articulo['video'].'" autoplay="" autobuffer="" muted="" loop=""> </video>';
                $html .= '</div><!-- / video -->';
            }
            elseif (!empty($articulo['background'])) 
            {
                // check colors
                if(preg_match('/,/s',$articulo['background']))
                {
                    // explode colors
                    $b = explode(",", $articulo['background']);
                    $html .= '<!-- flex -->';
                    $html .= '<div class="d-flex align-items-center" style="user-select:none;height:20em;background: linear-gradient(-45deg, '.$b[0].','.$b[1].');background: -webkit-linear-gradient(-45deg, '.$b[0].','.$b[1].')">';
                    $html .= '<h3 class="text-center m-auto" style="color:'.$articulo['color'].';">'.$articulo['title'].'</h3>';
                    $html .= '</div><!-- / flex -->';
                }
                else
                {
                    $html .= '<!-- flex -->';
                    $html .= '<div class="d-flex align-items-center" style="user-select:none;height:20em;background: '.$articulo['background'].'">';
                    $html .= '<h3 class="text-center m-auto" style="color:'.$articulo['color'].';">'.$articulo['title'].'</h3>';
                    $html .= '</div><!-- / flex -->';
                }
            }

            $html .= '<!-- card -->';
            $html .= '<div class="card-body">';
            $html .= '  <h3 class="card-title">';
            $html .= '    <a class="text-dark" href="'.$articulo['url'].'">'.$articulo['title'].'</a>';
            $html .= '  </h3>';
            $html .= '  <p>'.$articulo['description'].'</p>';
            $html .= '<a class="btn btn-light text-danger float-right" href="'.$articulo['url'].'">Leer mas &rarr;</a>';
            $html .= '</div>';
            $html .= '</div><!-- / card -->';
            $html .= '</article><!-- / article -->';
        }
        $html .= '</section><!-- / section -->';

        // print
        echo $html;
        

        // total = post / limit - 1
        $total = ceil(count($posts)/$limit);

        // if empty start in 0
        $p = 0;
        if (empty($_GET['page']))  $p = 0;
        else  $p = isset($_GET['page']) ? $_GET['page'] : 0;

        if(count($posts) > $limit)
        {

            // pagination
            $pagination = '<ul class="pagination">';
            // first
            $class = ($p == 0) ? "disabled" : "";
            $pagination .= '<li class="page-item '.$class.'"><a class="page-link" href="?page='.($p - 1).'">&laquo;</a></li>';
            if ($p > 0) 
            {
                $pagination .= '<li class="page-item"><a class="page-link" href="?page=0">Primera</a></li>';
                $pagination .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }

            // loop numbers
            $s = max(1, $p - 5);
            for (; $s < min($p+ 6, ($total - 1)); $s++) 
            {
                if ($s==$p) 
                {
                    $class = ($p == $s) ? "active" : "";
                    $pagination .= '<li class="hide-mobile page-item '.$class.'"><a class="page-link" href="?page='.$s.'">'.$s.'</a></li>';
                } 
                else 
                {
                    $class = ($p == $s) ? "active" : "";
                    $pagination .= '<li class="hide-mobile page-item '.$class.'"><a class="page-link" href="?page='.$s.'">'.$s.'</a></li>';
                }
            }

            // last
            if ($p < ($total - 1)) 
            {
                $pagination .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                $pagination .= '<li class="page-item"><a class="page-link" href="?page='.($total - 1).'">Ultima</a></li>';
            }
            // arrow right
            $class = ($p == ($total - 1)) ? "disabled" : "";
            $pagination .= '<li class="page-item '.$class.'"><a class="page-link" href="?page=' . ($p + 1) . '">&raquo;</a></li>';
            $pagination .= '</ul>';

            // print
            if($nav) echo $pagination;
        }
    }

});

/**
 * =====================================
 * Last posts
 * Barrio::runAction('lastPosts');
 * Barrio::runAction('lastPosts',[$name, $num]);
 * =====================================
 */
Barrio::addAction('lastPosts', function ($name = '',$num = 4) 
{
    $articulos = Barrio::pages($name, 'date', 'DESC', ['index','404'], $num);

    $html = '';
    foreach ($articulos as $articulo) 
    {

        $date =  date('d/m/Y', $articulo['date']);

        $image = '';
        if($articulo['image'])
        {
            $src = '';
            if(preg_match("/http/s", $articulo['image']))
            {
                $src = $articulo['image'];
            }
            else
            {
                $src = Barrio::urlBase().'/index.php?api=image&url='.$articulo['image'].'&w=65&h=65';
            }
            $image = '<img class="d-flex mr-3 float-left" src="'.$src.'" />';
        }
        else 
        {
            $image = '<div class="d-flex mr-3 float-left" style="background:#eee;width:65px;height:65px"></div>';
        }

        $html .= '<!-- media -->';
        $html .= '<div class="media mb-3">';
        $html .= '<!-- media-body -->';
        $html .= '<div class="media-body">';
        $html .=  $image;
        $html .= '<h5 class="mt-0 ml-2">';
        $html .= '  <a href="'.$articulo['url'].'" title="'.$articulo['title'].'">'.Barrio::shortText($articulo['title'],20).'</a>';
        $html .= '</h5>';
        $html .= '<p>'.Barrio::shortText($articulo['description'],25).'</p>';
        $html .= '</div><!-- / media-body -->';
        $html .= '</div><!-- / media -->';
    }

    echo $html;
});




/**
 * ==============================================================
 *  Background
 *  On file.md use Background: blue,red or Background: red
 *  Barrio::runAction('background',[$page, false,'extraclass']);
 * ==============================================================
 */
Barrio::addAction('background', function(array $page = array(), bool $full = false, string $extraClass = '')
{
    $image = ($page['image']) ? Barrio::urlBase().'/'.$page['image'] : '';
    $color = ($page['color']) ? 'color:'.$page['color'] : '';
    $fullWidth = ($full) ? 'h-100 '.$extraClass : ' '.$extraClass;

    $html = '';
    if ($page['image'])
    {   
        $html .= 'class="header '.$fullWidth.'" style="'.$color.';background-image:url('.$image.')" ';
    }
    elseif ($page['background']) 
    {
        $background = $page['background'];

        $color = ($page['color']) ? 'color:'.$page['color'] : '';
        if(preg_match('/,/s',$background))
        {   
            $b = explode(',',$background);
            $html .= 'class="'.$fullWidth.'" style="'.$color.';background: linear-gradient(-45deg '.$b[0].','.$b[1].');background: -webkit-linear-gradient(-45deg, '.$b[0].','.$b[1].')"';
        }
        else
        {
            $html .= 'class="'.$fullWidth.'" style="'.$color.';background: '.$background.' !important"';
        }
    }else{
        $html .= 'class="header '.$fullWidth.' text-light"';
    }

    echo $html;
});





/**
 * ====================================================================
 *  Navbar
 *  Barrio::runAction('navbar');
 *  Barrio::runAction('navbar',['dark','transparent',false]);
 * =====================================================================
 */
Barrio::addAction('navbar', function($menu = array(), $type = 'dark', $bg = 'transparent', $fixed = false)
{
    // url base
    $site_url = Barrio::urlBase();

    // array del menu
    $nav = ($menu) ? $menu : array();
    $navigation = arrayOfMenu($nav);
    // tipo, 
    $type = ($type) ? 'navbar-'.$type : 'navbar-dark';
    $bg = ($bg) ? 'bg-'.$bg : 'bg-primary';
    $fixed = ($fixed) ? 'mb-4 fixed-top' : '';

    $logo = '';
    if(isset(Barrio::$config['logo']))
    {
        $logo .='<img src="'.Barrio::$config['logo'].'" alt="'.Barrio::$config['title'].'"/>';
    }
    else
    {
        $logo .= Barrio::$config['title'];
    }

    $html = '<!-- nav -->
        <nav class="navbar navbar-expand-lg '.$type.' '.$bg.' '.$fixed.'">
        <a class="navbar-brand ml-3" href="'.$site_url.'">'.$logo.'</a>
        <button class="menu-burger navbar-toggler" type="button" data-toggle="collapse" data-target="#header-nav" aria-controls="header-nav" aria-expanded="false" aria-label="Toggle navigation"></button>
        <div class="collapse navbar-collapse" id="header-nav">
            <ul class="navbar-nav ml-auto">'.$navigation.'</ul>
        </div>
    </nav><!-- nav -->';

    echo $html;
});



/**
 * ======================================================
 *  Sidebar navigation
 *  Barrio::runAction('asideNav');
 *  Barrio::runAction('asideNav',['name','extraclass']);
 * ======================================================
 */
Barrio::addAction('asideNav', function(string $name = '',string $extraClass = '')
{

	$pages = Barrio::pages('/'.$name, 'date', 'DESC',['index']);

    $html = '';
    foreach ($pages as $item) 
    {
        $active = Barrio::urlCurrent();
        if ('/'.$active == '/'.$name.'/'.$item['slug'])
        {
            $html .= '<li class="pb-3">
                <a  class="text-info text-capitalize'.$extraClass.'" href="'.trim(Barrio::urlBase().'/'.$name.'/'.$item['slug']).'">
                    '.ucfirst($item['title']).'
                </a>
            </li>';
        }
        else
        {
            $html .= '<li class="pb-3">
                <a  class="text-capitalize'.$extraClass.'"  href="'.trim(Barrio::urlBase().'/'.$name.'/'.$item['slug']).'">
                    '.ucfirst($item['title']).'
                </a>
            </li>';
        }
    }

    $html = '<ul class="list-unstyled">'.$html.'</ul>';

    echo $html;
});

/**
 * ================================
 *          breadcrumbs
 * ================================
 */
Barrio::addAction('breadcrumb',function()
{
    
    $pages = Barrio::pages(trim(Barrio::urlSegment(0)), 'date', 'DESC',['index']);
    
    $url = trim(Barrio::urlCurrent());
    
    $site_url = trim(Barrio::urlBase().'/'.Barrio::urlSegment(0));
    
    $html = '<nav aria-label="Navegacion">';

    foreach ($pages as $k => $v) 
    {
        $slug = trim(Barrio::urlSegment(0).'/'.$pages[$k]['slug']);
        
        if($url == $slug)
        {    
            $html .= '<nav aria-label="breadcrumb">';
            $html .= '  <ol class="breadcrumb bg-transparent">';
            $html .= '      <li class="breadcrumb-item">';
            $html .= '          <a href="'.Barrio::urlBase().'">Inicio</a>';
            $html .= '      </li>';
            $html .= '      <li class="breadcrumb-item">';
            $html .= '          <a href="'.$site_url.'">';
            $html .=                ucfirst(Barrio::urlSegment(0));
            $html .= '          </a>';
            $html .= '      </li>';
            $html .= '      <li class="breadcrumb-item active" aria-current="page">';
            $html .=            $pages[$k]['title'];
            $html .= '      </li>';
            $html .= '  </ol>';
            $html .= '</nav>';
        }
    }

    $html .= '</ul>';

    $html .= '</nav>';

    echo $html;
});


/**
 * ================================
 *          Nav in folder
 * ================================
 */
Barrio::addAction('navFolder',function()
{
    // get all pages order by date desc
    $pages = Barrio::pages(trim(Barrio::urlSegment(0)), 'date', 'DESC',['index']);
    // trim url
    $url = trim(Barrio::urlCurrent());
    // trim site url
    $site_url = trim(Barrio::urlBase().'/'.Barrio::urlSegment(0));
    
    // output
    $html = '<!-- Navegacion -->';
    $html .= '<nav aria-label="Navegacion">';
    
    $html.= '<ul class="pagination justify-content-between">';
    
    // loop pages
    foreach ($pages as $k => $v)
    {
        // slug
        $slug = Barrio::urlSegment(0).'/'.$pages[$k]['slug'];
        
        // active url
        if($url == $slug)
        {
            
            if(isset($pages[$k-1]) != null)
            {
                $html .= '<li class="page-item">
                        <a class="page-link" href="'.$site_url.'/'.$pages[$k-1]['slug'].'">
                            <span aria-hidden="true">&laquo;</span> 
                            '.$pages[$k-1]['title'].'
                        </a>
                    </li>';
            }
            else
            {
                $html .= '<li class="page-item">
                        <a class="page-link" href="'.$site_url.'">
                            <span aria-hidden="true">&laquo;</span> 
                            '.ucfirst(Barrio::urlSegment(0)).'
                        </a>
                    </li>';
            }

            if(isset($pages[$k+1]) != null)
            {
                $html .= '<li class="page-item">
                        <a class="page-link" href="'.$site_url.'/'.$pages[$k+1]['slug'].'">
                            '.$pages[$k+1]['title'].'
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>';
            }
            else
            {
                $html .= '<li class="page-item">
                        <a class="page-link" href="'.$site_url.'">
                            '.ucfirst(Barrio::urlSegment(0)).'
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>';
            }
        }
    }
    
    $html .= '</ul>';
    $html .= '<!-- / ul -->';

    $html .= '</nav>';
    $html .= '<!-- / nav -->';

    echo $html;
});

