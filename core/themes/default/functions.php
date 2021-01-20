<?php
/*
 * Declara al principio del archivo, las llamadas a las funciones respetarán
 * estrictamente los indicios de tipo (no se lanzarán a otro tipo).
 */
declare (strict_types = 1);

/**
 * Acceso restringido
 */
defined("ACCESS") or die("No tiene acceso a este archivo");

use Action\Action as Action;
use Barrio\Barrio as Barrio;
use Text\Text as Text;

if (!function_exists('assets')) {
    /**
     * Assets
     *
     * @param string $source
     *
     * @return string
     */
    function assets(string $source = "")
    {
        $themeName = config('theme');
        $folder = url() . '/core/themes/' . $themeName . '/assets/' . $source;
        return $folder;
    }
}
if (!function_exists('action')) {
    /**
     * Action
     *
     * @param string $name
     *
     * @return Action
     */
    function action(string $name = "")
    {
        return Action::run($name);
    }
}
if (!function_exists('config')) {
    /**
     * Config
     *
     * @param string $name
     *
     * @return string
     */
    function config(string $name = "")
    {
        return (Barrio::$config[$name]) ? Barrio::$config[$name] : "";
    }
}
if (!function_exists('url')) {
    /**
     * Site url
     *
     * @return string
     */
    function url()
    {
        return Barrio::urlBase();
    }
}
if (!function_exists('urlCurrent')) {
    /**
     * Current url
     *
     * @return string
     */
    function urlCurrent()
    {
        return Barrio::urlCurrent();
    }
}
if (!function_exists('imageToDataUri')) {
    /**
     * Convertir imagen a Data uri
     *
     * @param string $source
     *
     * @return string
     */
    function imageToDataUri(string $source)
    {
        $img = str_replace(Barrio::urlBase(), ROOT_DIR, $source);
        // Read image path, convert to base64 encoding
        $imageData = base64_encode((string) file_get_contents($img));
        // Format the image SRC:  data:{mime};base64,{data};
        $src = 'data: ' . mime_content_type($img) . ';base64,' . $imageData;
        return $src;
    }
}
if (!function_exists('arrayOfMenu')) {
    /**
     * Generar un menu
     *
     * @param array $nav
     *
     * @return string
     */
    function arrayOfMenu($nav)
    {
        $html = '';
        foreach ($nav as $k => $v) {
            // key exists
            if (array_key_exists($k, $nav)) {
                // not empty
                if ($k != '') {
                    // external page
                    if (preg_match("/http/i", $k)) {
                        $html .= '<li class="nav-item"><a class="nav-link fw-ultra" href="' . $k . '">' . ucfirst($v) . '</a></li>';
                    } else {
                        // is array
                        if (is_array($v)) {
                            // dropdown
                            $id = uniqid();
                            $html .= '<li class="nav-item dropdown">';
                            $html .= '  <a class="nav-link  fw-ultra dropdown-toggle" data-bs-toggle="dropdown"  id="' . $id . '" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="#">' . ucfirst($k) . '</a>';
                            $html .= '<ul class="dropdown-menu" aria-labelledby="' . $id . '">
                            ' . arrayOfMenu($v) . '</ul>';
                            $html .= '</li>';
                        } else {
                            // active page
                            $active = Barrio::urlSegment(0);
                            $activeurl = str_replace('/', '', $k);
                            if ($active == $activeurl) {
                                $html .= '<li class="nav-item "><a  class="nav-link fw-ultra active"href="' . trim(Barrio::urlBase() . $k) . '">
                                    ' . ucfirst($v) . '
                                </a></li>';
                            } else {
                                $html .= '<li class="nav-item"><a  class="nav-link fw-ultra" href="' . trim(Barrio::urlBase() . $k) . '">
                                    ' . ucfirst($v) . '
                                </a></li>';
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
if (!function_exists('menu')) {
    /**
     * Crear menu
     */
    function menu()
    {
        // array del menu
        $nav = Barrio::$config['menu'];
        $navigation = arrayOfMenu($nav);
        return $navigation;
    }
}
if (!function_exists('posts')) {
    /**
     * Obtener un lista articulos
     *
     * @param string $name
     * @param int $num
     * @param string $nav
     */
    function posts($name, $num = 0, $nav = true)
    {
        // obtenemos las cabeceras
        $posts = Barrio::run()->getHeaders($name, 'date', 'DESC', ['index', '404']);
        // limit de la paginacion
        $limit = ($num) ? $num : Barrio::$config['pagination'];
        // init
        $blogPosts = array();
        // si hay articulos y si es array
        if ($posts && is_array($posts)) {
            foreach ($posts as $f) {
                // push on blogposts
                array_push($blogPosts, $f);
            }
            // dividimos en fragmentos para la paginacion
            $articulos = array_chunk($blogPosts, $limit);
            // obtenemos el numero
            $pgkey = isset($_GET['page']) ? $_GET['page'] : 0;
            $items = $articulos[$pgkey];
            $html = '<div class="row">';
            foreach ($items as $articulo) {
                $date = (date('d/m/Y', (int) $articulo['date'])) ? date('d/m/Y', (int) $articulo['date']) : date('d/m/Y');
                $html .= '<div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow mb-3">';
                if (preg_match("/http/s", $articulo['image'])) {
                    if ($articulo['image']) {
                        $html .= '<img src="' . $articulo['image'] . '"/>';
                    } else {
                        $html .= '<div style="height: 12rem;background: #212529;color: #6c757d;display: flex;justify-content: center;align-items: center;font-family: ultra;letter-spacing: 1.8px;text-transform: uppercase;">' . $articulo['title'] . '</div>';
                    }
                } else {
                    if ($articulo['image']) {
                        $src = Barrio::urlBase() . '/' . $articulo['image'];
                        $html .= '<img src="' . imageToDataUri($src) . '" alt="' . $articulo['title'] . '"/>';
                    } else {
                        $html .= '<div style="height: 12rem;background: #212529;color: #6c757d;display: flex;justify-content: center;align-items: center;font-family: ultra;letter-spacing: 1.8px;text-transform: uppercase;">' . $articulo['title'] . '</div>';
                    }
                }
                $html .= '<div class="card-body">
                    <h4 class="card-title mb-3">' . $articulo['title'] . '</h4>
                    <p class="card-subtitle mb-3"><b>' . Barrio::$config['blogdate'] . ':</b> ' . $date . '</p>
                    <p class="card-text mb-2">' . Text::short($articulo['description'], 50) . '</p>
                    <a href="' . $articulo['url'] . '" class="btn btn-sm btn-dark my-3 float-end"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-symlink" viewBox="0 0 16 16"><path d="M11.798 8.271l-3.182 1.97c-.27.166-.616-.036-.616-.372V9.1s-2.571-.3-4 2.4c.571-4.8 3.143-4.8 4-4.8v-.769c0-.336.346-.538.616-.371l3.182 1.969c.27.166.27.576 0 .742z"/><path d="M.5 3l.04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm.694 2.09A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09l-.636 7a1 1 0 0 1-.996.91H2.826a1 1 0 0 1-.995-.91l-.637-7zM6.172 2a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.684.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"/></svg><span class="ms-2">' . Barrio::$config['readmore'] . '</span></a>
                  </div>
                </div></div><!-- / post -->';
            }
            $html .= '</div><!-- / posts -->';

            // print
            echo $html;

            // total = post / limit - 1
            $total = ceil(count($posts) / $limit);

            // if empty start in 0
            $p = 0;
            if (empty($_GET['page'])) {
                $p = 0;
            } else {
                $p = isset($_GET['page']) ? $_GET['page'] : 0;
            }

            if (count($posts) > $limit) {
                // pagination
                $pagination = '<nav class="pt-3 overflow-hidden">';
                if ($p > 0) {
                    $pagination .= '<a class="btn btn-dark float-start me-2" href="?page=' . ($p - 1) . '"><span aria-hidden="true">&laquo;</span> Anteriores</a>';
                }

                $disabled = ($p == ($total - 1)) ? 'class="btn-dark btn disabled"' : 'class="btn float-end btn-dark" href="?page=' . ($p + 1) . '"';

                $pagination .= '<a  ' . $disabled . '> Siguientes <span aria-hidden="true">&raquo;</span></a>';
                $pagination .= '</nav>';
                // print
                if ($nav) {
                    echo $pagination;
                }
            }
        }
    }
}
if (!function_exists('primary_post')) {
    /**
     * Obtener el primer articulo
     *
     * @param string $name
     * @param int $num
     */
    function primary_post(string $name = 'blog', int $num = 1)
    {
        $articulos = Barrio::run()->getHeaders($name, 'date', 'DESC', ['index', '404'], $num);
        $html = '';
        foreach ($articulos as $articulo) {
            $date = date('d/m/Y', $articulo['date']);
            $image = '';
            if ($articulo['image']) {
                $src = '';
                if (preg_match("/http/s", $articulo['image'])) {
                    $src = $articulo['image'];
                } else {
                    $src = imageToDataUri($articulo['image']);
                }
                $image = '<img class="img-thumbnail shadow" src="' . $src . '" />';
            }
            $html .= '<div class="row mb-5"><div class="col-md-6">';
            if ($articulo['image']) {
                $html .= $image;
            } elseif ($articulo['video']) {
                $html .= '<div class="video" style="height: 20em">
                            <video
                                src="' . $articulo['video'] . '"
                                autoplay=""
                                autobuffer=""
                                muted=""
                                loop=""> </video>';
                $html .= '</div>';
            } else {
                $html .= '<div style="height: 12rem;background: #212529;color: #6c757d;display: flex;justify-content: center;align-items: center;font-family:ultra;letter-spacing: 1.8px;text-transform: uppercase;">' . $articulo['title'] . '</div>';
            }
            $html .= '  </div>';
            $html .= '  <div class="col-md-6">';
            $html .= '      <h2 class="text-ultra mb-3">' . $articulo['title'] . '</h2>';
            $html .= '      <p class="mb-3">' . $articulo['description'] . '</p>';

            // atributos
            if ($articulo['attrs']) {
                $attrs = $articulo['attrs'];
                $html .= generateAttrs($attrs);
            }
            $html .= '      <p><a class="btn btn-dark" href="' . $articulo['url'] . '"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-symlink" viewBox="0 0 16 16"><path d="M11.798 8.271l-3.182 1.97c-.27.166-.616-.036-.616-.372V9.1s-2.571-.3-4 2.4c.571-4.8 3.143-4.8 4-4.8v-.769c0-.336.346-.538.616-.371l3.182 1.969c.27.166.27.576 0 .742z"/><path d="M.5 3l.04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm.694 2.09A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09l-.636 7a1 1 0 0 1-.996.91H2.826a1 1 0 0 1-.995-.91l-.637-7zM6.172 2a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.684.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"/></svg><span class="ms-2">Ver mas</span></a></p>';
            $html .= '  </div>';
            $html .= '</div>';
        }
        echo $html;
    }
}
if (!function_exists('generateAttrs')) {
    /**
     * Generar atributos
     *
     * @param array $attrs
     */
    function generateAttrs($attrs)
    {
        $html = '<div class="my-4">';
        $toJson = json_decode((string) $attrs, true);
        if (is_array($toJson)) {
            // si hay colores
            if ($toJson['colores']) {
                $colores = explode(',', $toJson['colores']);
                $html .= '<p class="m-0"><b>Colores usados: </b></p>';
                $html .= '<div class="colors">';
                foreach ($colores as $color) {
                    $html .= '<div class="color float-start" style="background:' . $color . ';"><span>' . $color . '</span></div>';
                }
                $html .= '</div>';
            }
            // si hay codigo
            if ($toJson['code']) {
                $code = explode(',', $toJson['code']);
                $html .= '<p class="mb-1"><b class="me-2">Codigo: </b> ';
                foreach ($code as $cod) {
                    $html .= '<span class="me-1 badge bg-dark text-light">' . ucfirst($cod) . '</span>';
                }
                $html .= '</p>';
            }
            // si hay cms
            if ($toJson['cms']) {
                $html .= '<p class="mb-1"><b class="me-2">CMS: </b> ' . $toJson['cms'] . '</p>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}
if (!function_exists('last_posts')) {
    /**
     * Obtener los ultimos articulos
     *
     * @param string $name
     * @param int $cols
     * @param int $num
     */
    function last_posts(string $name = 'blog', int $cols = 3, int $num = 4)
    {
        $articulos = Barrio::run()->getHeaders($name, 'date', 'DESC', ['index', '404'], $num);
        $html = '<div class="row mb-5">';
        foreach ($articulos as $articulo) {
            $date = date('d/m/Y', $articulo['date']);
            $image = '';
            if ($articulo['image']) {
                $src = '';
                if (preg_match("/http/s", $articulo['image'])) {
                    $src = $articulo['image'];
                } else {
                    $src = imageToDataUri($articulo['image']);
                }
                $image = '<img class="card-img-top" src="' . $src . '" />';
            }
            $html .= '<div class="col-md-' . $cols . '"><div class="card shadow mb-3">';
            if ($articulo['image']) {
                $html .= '  <div class="thumbnail top">' . $image . '</div>';
            } elseif ($articulo['video']) {
                $html .= '<div class="video">';
                $html .= '<video src="' . $articulo['video'] . '" autoplay="" autobuffer="" muted="" loop=""> </video>';
                $html .= '</div>';
            } else {
                $html .= '<div style="height: 12rem;background: #212529;color: #6c757d;display: flex;justify-content: center;align-items: center;font-family: ultra;letter-spacing: 1.8px;text-transform: uppercase;">' . $articulo['title'] . '</div>';
            }
            $html .= '<div class="card-body">
                <h5 class="card-title">' . $articulo['title'] . '</h5>
                <p class="card-text">' . Text::short($articulo['description'], 50) . '</p>
                <a href="' . $articulo['url'] . '" class="btn btn-dark float-end"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-symlink" viewBox="0 0 16 16"><path d="M11.798 8.271l-3.182 1.97c-.27.166-.616-.036-.616-.372V9.1s-2.571-.3-4 2.4c.571-4.8 3.143-4.8 4-4.8v-.769c0-.336.346-.538.616-.371l3.182 1.969c.27.166.27.576 0 .742z"/><path d="M.5 3l.04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm.694 2.09A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09l-.636 7a1 1 0 0 1-.996.91H2.826a1 1 0 0 1-.995-.91l-.637-7zM6.172 2a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.684.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"/></svg><span class="ms-2">Ver mas</span></a>
              </div>
            </div></div>';
        }
        $html .= '</div>';
        echo $html;
    }
}
if (!function_exists('navFolder')) {
    /**
     * Navegacion de carpetas
     */
    function navFolder()
    {
        $source = CONTENT . '/' . trim(Barrio::urlSegment(0));
        if (is_dir($source)) {
            $pages = Barrio::run()->getHeaders(trim(Barrio::urlSegment(0)), 'date', 'DESC', array('index', '404'));

            if (count($pages) > 1) {
                $url = trim(Barrio::urlCurrent());
                $site_url = trim(Barrio::urlBase() . '/' . Barrio::urlSegment(0));
                $html = '<nav class="my-4 overflow-hidden">';
                if ($pages) {
                    foreach ($pages as $k => $v) {
                        $slug = Barrio::urlSegment(0) . '/' . $pages[$k]['slug'];
                        if ($url == $slug) {
                            $separator = '<span style="margin: 0 3em"></span>';

                            if (isset($pages[$k - 1]) != null) {
                                $html .= '<a class="btn btn-dark float-start" href="' . $site_url . '/' . $pages[$k - 1]['slug'] . '">
                                    <span aria-hidden="true">&laquo;</span>
                                    ' . $pages[$k - 1]['title'] . '
                                </a>' . $separator;
                            } else {
                                $html .= '<a class="btn btn-dark float-start" href="' . $site_url . '">
                                    <span aria-hidden="true">&laquo;</span>
                                    ' . ucfirst(Barrio::urlSegment(0)) . '
                                </a>' . $separator;
                            }

                            if (isset($pages[$k + 1]) != null) {
                                $html .= '<a class="btn btn-dark float-end" href="' . $site_url . '/' . $pages[$k + 1]['slug'] . '">
                                    ' . $pages[$k + 1]['title'] . '
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                            } else {
                                $html .= '<a class="btn btn-dark float-end" href="' . $site_url . '">
                                    ' . ucfirst(Barrio::urlSegment(0)) . '
                                    <span aria-hidden="true">&raquo;</span>
                                </a>';
                            }
                        }
                    }
                }
                $html .= '</nav>';
                echo $html;
            }
        }
    }
}
if (!function_exists('search')) {
    /**
     * Accion de buscar
     */
    Action::add('theme_after', "search");
    function search()
    {
        $language = Barrio::$config['search'];
        // demo http://localhost/cmsbarrio/?buscar=
        if (isset($_POST['buscar'])) {
            // http://localhost/cmsbarrio/?buscar=Hola
            // $query = hola
            $query = $_POST['buscar'];
            // check query
            if ($query) {
                $name = '/';
                $num = 0;
                // get pages
                $data = Barrio::run()->getHeaders($name, 'date', 'DESC', ['index', '404'], $num);
                // get 5 words
                $name = urlencode(substr(trim($query), 0, 5));
                // init results and total
                $results = array();
                $total = 0;
                // loop data
                foreach ($data as $item) {
                    // raplace url with data url
                    $root = str_replace(Barrio::urlBase(), CONTENT, $item['url']);
                    // decode
                    $name = urldecode($name);
                    // check title description and slug
                    if (preg_match("/$name/i", $item['title']) ||
                        preg_match("/$name/i", $item['description']) ||
                        preg_match("/$name/i", $item['slug'])) {
                        // if obtain something show
                        $results[] = array(
                            'title' => (string) $item['title'],
                            'description' => (string) $item['description'],
                            'url' => (string) $item['url'],
                        );
                        // count results
                        $total++;
                    }
                }

                // template
                $output = '';
                foreach ($results as $page) {
                    $output .= '<article>';
                    $output .= '<h3>' . $page['title'] . '</h3>';
                    $output .= '<p>' . $page['description'] . '</p>';
                    $output .= '<a href="' . $page['url'] . '">' . $language['read'] . '</a>';
                    $output .= '</article>';
                }

                $html = '<section class="form-results">';
                // if results show
                if ($results) {
                    $html .= $output;
                } else {
                    $html .= $language['no_results'] . ' ' . $query;
                }
                $html .= '</section>';
                echo $html;
            }
        }
    }
}

/**
 * ====================================
 *      incluimos el plugin de cookie
 * ====================================
 */
Action::add('head', function () {

    $src = array(
        'style' => 'https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css',
        'javascript' => 'https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js',
    );

    $html = '<link rel="stylesheet" href="' . $src['style'] . '"/>';
    $html .= '<script rel="javascript" src="' . $src['javascript'] . '"></script>';
    $html .= '<script>
        window.addEventListener("load", function(){
        window.cookieconsent.initialise({
          "position": "bottom-right",
          "content": {
            "message": "Utilizamos cookies propias y de terceros. Si continúa navegando acepta su uso.",
            "dismiss": "Aceptar",
            "link": "Leer mas",
            "href": "' . Barrio::urlBase() . '/politica-de-cookies"
          }
        })});
        </script>';

    echo $html;
});
