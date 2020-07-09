<?php  defined('BARRIO') or die('Sin accesso a este script.');


/**
 * Simple theme class
 */
class Theme
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->theme = Barrio::$config['theme'];
        $this->assets = Barrio::urlBase().'/themes/'.$this->theme.'/dist/assets';
    }

    /**
     * Run non static
     *
     * self::run()-nonStaticMethod();
     */
    private static function run()
    {
        return new static();
    }

    /**
     * Styles css
     *
     * @return string link
     */
    public static function head()
    {
        $url = self::run()->assets.'/css';
        echo '<link rel="stylesheet" type="text/css" href="'.$url.'/main.min.css"/>';
    }

    /**
     * Styles javascript
     *
     * @return string script
     */
    public static function footer()
    {
        $script = self::run()->assets.'/js';
        echo '<script rel="javascript" src="'.$script.'/main.min.js"></script>';
    }

    /**
     * Convert Array to menu
     *
     * @param array $nav array on menu
     *
     * @return string menu
     */
    public static function arrayOfMenu($nav)
    {
        $html = '';
        foreach ($nav as $k => $v) {
            // key exists
            if (array_key_exists($k, $nav)) {
                // not empty
                if ($k != '') {
                    // external page
                    if (preg_match("/http/i", $k)) {
                        $html .= '<a href="'.$k.'">'.ucfirst($v).'</a>';
                    } elseif (preg_match("/#/i", $k)) {
                        $html .= '<a data-href="'.$k.'" href="'.$k.'">'.ucfirst($v).'</a> / ';
                    } else {
                        // is array
                        if (is_array($v)) {
                            // dropdown
                            $html .= '<a href="#">'.ucfirst($k).'</a> / ';
                        } else {
                            // active page
                            $active = Barrio::urlCurrent();
                            $activeurl = str_replace('/', '', $k);
                            if ($active == $activeurl) {
                                $html .= '<a style="color:var(--nc-lk-2);" href="'.trim(Barrio::urlBase().$k).'">'.ucfirst($v).'</a> / ';
                            } else {
                                $html .= '<a href="'.trim(Barrio::urlBase().$k).'">'.ucfirst($v).'</a> / ';
                            }
                        }
                    }
                }
            }
        }
        // show html
        $html = substr($html, 0, -2);
        return $html;
    }

    /**
     * Create action navigation
     *
     * @return string html
     */
    public static function navigation($menu = array())
    {
        // array del menu
        $nav = ($menu) ? $menu : array();
        $navigation = self::arrayOfMenu($nav);
        $html = '<nav>'.$navigation.'</nav><!-- nav -->';
        echo $html;
    }

    /**
     *  Get Posts
     *
     * @param string $name
     * @param integer $num
     * @param boolean $nav
     *
     * @return string
     */
    public static function posts($name, $num=0, $nav = true)
    {

        // get pages
        $posts = Barrio::run()->getHeaders($name, 'date', 'DESC', ['index','404']);
        // limit
        $limit = ($num) ? $num :  Barrio::$config['pagination'];
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
            $html = '<section>';
            foreach ($items as $articulo) {
                $date =  date('d/m/Y', $articulo['date']);
                $html .= '<article>';
                $html .= '<h3><a  href="'.$articulo['url'].'">'.$articulo['title'].'</a></h3>';
                $html .= '<p>'.$articulo['description'].'</p>';
                $html .= '</article><!-- / article -->';
            }
            $html .= '</section><!-- / section -->';

            // print
            echo $html;

            // total = post / limit - 1
            $total = ceil(count($posts)/$limit);

            // if empty start in 0
            $p = 0;
            if (empty($_GET['page'])) {
                $p = 0;
            } else {
                $p = isset($_GET['page']) ? $_GET['page'] : 0;
            }

            if (count($posts) > $limit) {
                // pagination
                $pagination = '<nav>';
                if ($p > 0) {
                    $pagination .= '<a href="?page='.($p - 1).'">Anterior</a> / ';
                }

                $disabled = ($p == ($total - 1)) ? "disabled" : 'href="?page=' . ($p + 1) . '"';
                $pagination .= '<a '.$disabled.'> Siguientes</a>';
                $pagination .= '</nav>';
                // print
                if ($nav) {
                    echo $pagination;
                }
            }
        }
    }


    /**
     * Create folder navigation
     *
     * @return string
     */
    public static function navFolder()
    {
        $source = CONTENT.'/'.trim(Barrio::urlSegment(0));
        if (is_dir($source)) {
            $pages = Barrio::run()->getHeaders(trim(Barrio::urlSegment(0)), 'date', 'DESC', array('index','404'));

            if (count($pages) > 1) {
                $url = trim(Barrio::urlCurrent());
                $site_url = trim(Barrio::urlBase().'/'.Barrio::urlSegment(0));
                $html = '<nav style="text-align:center;">';
                if ($pages) {
                    foreach ($pages as $k => $v) {
                        $slug = Barrio::urlSegment(0).'/'.$pages[$k]['slug'];
                        if ($url == $slug) {
                            $separator = '<span style="margin: 0 3em"></span>';

                            if (isset($pages[$k-1]) != null) {
                                $html .= '<a href="'.$site_url.'/'.$pages[$k-1]['slug'].'">
                                        <span aria-hidden="true">&laquo;</span> 
                                        '.$pages[$k-1]['title'].'
                                    </a>'.$separator;
                            } else {
                                $html .= '<a href="'.$site_url.'">
                                        <span aria-hidden="true">&laquo;</span> 
                                        '.ucfirst(Barrio::urlSegment(0)).'
                                    </a>'.$separator;
                            }
            
                            if (isset($pages[$k+1]) != null) {
                                $html .= '<a href="'.$site_url.'/'.$pages[$k+1]['slug'].'">
                                        '.$pages[$k+1]['title'].'
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>';
                            } else {
                                $html .= '<a href="'.$site_url.'">
                                        '.ucfirst(Barrio::urlSegment(0)).'
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


    /**
     * Search function
     *
     * @return string
     */
    public static function search()
    {
        $language = Barrio::$config['search'];
        // demo http://localhost/cmsbarrio/?buscar=
        if (isset($_POST['buscar'])) {
            // http://localhost/cmsbarrio/?buscar=Hola
            // $query = hola
            $query = $_POST['buscar'];
            // check query
            if ($query) {
                // get pages
                $data = Barrio::run()->getHeaders('/', 'date', 'DESC', ['404'], '');
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
                        $results[]= array(
                          'title' => (string) $item['title'],
                          'description' => (string) $item['description'],
                          'url' => (string) $item['url']
                        );
                        // count results
                        $total++;
                    }
                }

                // template
                $output = '';
                foreach ($results as $page) {
                    $output .= '<article>';
                    $output .= '<h3>'.$page['title'].'</h3>';
                    $output .= '<p>'.$page['description'].'</p>';
                    $output .= '<a href="'.$page['url'].'">'.$language['read'].'</a>';
                    $output .= '</article>';
                }

                $html = '<section class="form-results">';
                // if results show
                if ($results) {
                    $html .= $output;
                } else {
                    $html .= $language['no_results'].' '.$query;
                }
                $html .= '</section>';
                echo $html;
            }
        }
    }
}
