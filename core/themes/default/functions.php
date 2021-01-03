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

if (!function_exists('arrayOfMenu')) {
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
    function menu()
    {
        // array del menu
        $nav = Barrio::$config['menu'];
        $navigation = arrayOfMenu($nav);
        return $navigation;
    }
}

if (!function_exists('posts')) {
    function posts($name, $num = 0, $nav = true)
    {
        // get pages
        $posts = Barrio::run()->getHeaders($name, 'date', 'DESC', ['index', '404']);
        // limit
        $limit = ($num) ? $num : Barrio::$config['pagination'];
        // init
        $blogPosts = array();

        if ($posts) {
            foreach ($posts as $f) {
                // push on blogposts
                array_push($blogPosts, $f);
            }
            // fragment
            $articulos = array_chunk($blogPosts, $limit);
            // get number
            $pgkey = isset($_GET['page']) ? $_GET['page'] : 0;
            $items = $articulos[$pgkey];
            $html = '<div class="row">';
            foreach ($items as $articulo) {
                $date = date('d/m/Y', (int) $articulo['date']);
                $html .= '<div class="col-xl-6 col-md-6 mb-3">
                <div class="card shadow">';
                if (preg_match("/http/s", $articulo['image'])) {
                    if ($articulo['image']) {
                        $html .= '<img src="' . $articulo['image'] . '"/>';
                    } else {
                        $html .= '<div style="height: 12rem;background: #212529;color: #6c757d;display: flex;justify-content: center;align-items: center;font-family: ultra;letter-spacing: 1.8px;text-transform: uppercase;">' . $articulo['title'] . '</div>';
                    }
                } else {
                    if ($articulo['image']) {
                        $src = Barrio::urlBase() . '/' . $articulo['image'];
                        $html .= '<img src="' . $src . '" alt="' . $articulo['title'] . '"/>';
                    } else {
                        $html .= '<div style="height: 12rem;background: #212529;color: #6c757d;display: flex;justify-content: center;align-items: center;font-family: ultra;letter-spacing: 1.8px;text-transform: uppercase;">' . $articulo['title'] . '</div>';
                    }
                }
                $html .= '<div class="card-body">
                    <h4 class="card-title mb-3">' . $articulo['title'] . '</h4>
                    <p class="card-subtitle mb-3"><b>' . Barrio::$config['blogdate'] . ':</b> ' . $date . '</p>
                    <p class="card-text mb-2">' . Text::short($articulo['description'], 50) . '</p>
                    <a href="' . $articulo['url'] . '" class="btn btn-sm btn-dark my-3 float-end">' . Barrio::$config['readmore'] . '</a>
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
                    $src = Barrio::urlBase() . '/' . $articulo['image'];
                }
                $image = '<img class="img-thumbnail shadow" src="' . $src . '" />';
            }
            $html .= '<div class="row mb-3"><div class="col-md-6">';
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
            $html .= '      <p class="mb-3">' . Text::short($articulo['description'], 50) . '</p>';
            $html .= '      <p><a class="btn btn-dark" href="' . $articulo['url'] . '">Ver mas</a></p>';
            $html .= '  </div>';
            $html .= '</div>';
        }
        echo $html;
    }
}

if (!function_exists('last_posts')) {
    function last_posts(string $name = 'blog', int $cols = 3, int $num = 4)
    {
        $articulos = Barrio::run()->getHeaders($name, 'date', 'DESC', ['index', '404'], $num);
        $html = '<div class="row mb-3">';
        foreach ($articulos as $articulo) {
            $date = date('d/m/Y', $articulo['date']);
            $image = '';
            if ($articulo['image']) {
                $src = '';
                if (preg_match("/http/s", $articulo['image'])) {
                    $src = $articulo['image'];
                } else {
                    $src = Barrio::urlBase() . '/' . $articulo['image'];
                }
                $image = '<img class="card-img-top" src="' . $src . '" />';
            }
            $html .= '<div class="col-md-' . $cols . '"><div class="card shadow">';
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
                <a href="' . $articulo['url'] . '" class="btn btn-dark float-end">Ver mas</a>
              </div>
            </div></div>';
        }
        $html .= '</div>';
        echo $html;
    }
}

if (!function_exists('navFolder')) {
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
