<?php  defined('BARRIO') or die('Sin accesso a este script.');


class Api
{

    /**
     *  Render json
     *
     *  @param array $data
     *
     *  @return array
     */
    public function json(array $data = array())
    {
        @header('Content-Type: application/json');
        die(json_encode($data));
    }

    /**
     *  Resize image
     *
     *  @param string $url
     *  @param string $width
     *  @param string $height
     *  @param integer $quality
     *
     *  @return string image
     */
    public function resizeImg($url, $width, $height, $quality=70)
    {
        @header('Content-type: image/jpeg');
        $filePath = ROOT.'/'.$url;

        list($width_orig, $height_orig) = getimagesize($url);
        $ratio_orig = $width_orig/$height_orig;
        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }

        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $image = '';
        if ($ext == 'png') {
            $image = imagecreatefrompng($filePath);
            $bg = imagecreatetruecolor($width, $height);
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagealphablending($bg, true);
            imagecopyresampled($bg, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            imagejpeg($bg, null, $quality);
        } else {
            // This resamples the image
            $image_p = imagecreatetruecolor($width, $height);
            $image = imagecreatefromjpeg($url);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            imagejpeg($image_p, null, 100);
            //imagedestroy($bg);
        }
    }

    /**
     * Headers array
     * 
     * @return array $args
     */
    public function getHeaders($args = array())
    {
        return array(
            "title" => $args['title'],
            "description" => $args['description'],
            "tags" => $args['tags'],
            "author" => $args['author'],
            "image" => $args['image'],
            "date" => $args['date'],
            "robots" => $args['robots'],
            "keywords" => $args['keywords'],
            "template" => $args['template'],
            "published" => $args['published'],
            "background" => $args['background'],
            "video" => $args['video'],
            "color" => $args['color'],
            "attrs" => $args['attrs']
        );
    }

    public function filterPages($filter = null,$pages = array())
    {
        // init output
        $output = array();
        // switch
        switch ($filter) {

            // index.php?api=file&data=pages&name=blog&filter=title
            case 'title': // get only titles
                foreach ($pages as $item) {
                    $arr = array("title" => $item['title']);
                    // push array
                    array_push($output, $arr);
                }
                break;

            // index.php?api=file&data=pages&name=blog&filter=keywords
            case 'keywords': // get only keywords
                foreach ($pages as $item) {
                    $arr = array("keywords" => $item['keywords']);
                    // push array
                    array_push($output, $arr);
                }
                break;

            // index.php?api=file&data=pages&name=blog&filter=images
            case 'images': // get only images
                foreach ($pages as $item) {
                    $arr = array("image" => $item['image']);
                    // push array
                    array_push($output, $arr);
                }
                break;

            // index.php?api=file&data=pages&name=blog&filter=videos
            case 'videos': // get only videos
                foreach ($pages as $item) {
                    $arr = array("video" => $item['video']);
                    // push array
                    array_push($output, $arr);
                }
                break;

            // index.php?api=file&data=pages&name=blog&filter=attrs
            case 'attrs': // get only attrs
                foreach ($pages as $item) {
                    $arr = array("attrs" => $item['attrs']);
                    // push array
                    array_push($output, $arr);
                }
                break;

            // index.php?api=file&data=pages&name=blog&filter=count
            case 'count': // count
                $arr = array("total" => count($pages));
                // push array
                array_push($output, $arr);
                break;
        }
        // print json
        $this->json($output);
    }
    /**
     *  Pages
     *
     *  @return array
     */
    public function pages()
    {
        // check if exists name
        if (array_key_exists('name', $_GET)) {
            // get name or null
            $name = ($_GET['name']) ? $_GET['name'] : 'blog';
            // check if is a dir
            if (is_dir(CONTENT.'/'.$name)) {
                // filter data
                if (array_key_exists('filter', $_GET)) {
                    // get filter
                    $filter = ($_GET['filter']) ? $_GET['filter'] : null;
                    // get pages
                    $pages = Barrio::run()->getHeaders($name, 'date', 'DESC', array('index','404'), null);
                    $this->filterPages($filter, $pages);
                }
                // index.php?api=file&data=pages&name=blog&limit=3
                elseif (array_key_exists('limit', $_GET)) {
                    // get filter
                    $limit = ($_GET['limit']) ? $_GET['limit'] : 3;
                    // get pages
                    $pages = Barrio::run()->pages($name, 'date', 'DESC', array('index','404'), $limit);
                    // init array
                    $output = array();
                    foreach ($pages as $item) {
                        $arr = $this->getHeaders($item);
                        $arr['url'] = $item['url'];
                        // push array
                        array_push($output, $arr);
                    }
                    // print json
                    $this->json($output);
                } else {   // index.php?api=file&data=pages&name=blog
                    // get pages
                    $pages = Barrio::run()->getHeaders($name, 'date', 'DESC', array('index','404'), null);
                    // init array
                    $output = array();
                    foreach ($pages as $item) {
                        $arr = $this->getHeaders($item);;
                        // push array
                        array_push($output, $arr);
                    }
                    // print json
                    $this->json($output);
                }
            }
        }
        $this->json(array('status' => false));
    }

    /**
     *  Pages
     *
     *  @return array
     */
    public function page()
    {
        // index.php?api=file&data=page&name=blog
        // check if exists name
        if (array_key_exists('name', $_GET)) {
            // get name or null
            $name = ($_GET['name']) ? $_GET['name'] : '';
            $page = Barrio::run()->page($name);

            // convert shortcodes and markdown
            include EXTENSIONS.'/markdown/Parsedown.php';
            include EXTENSIONS.'/shortcodes/shortcodes.ext.php';
            // parse content
            $content = Barrio::parseShortcode($page['content']);
            $content = Parsedown::instance()->text(Barrio::parseShortcode($content));
            // parse content
            $content = Barrio::parseShortcode($content);
            $content = Parsedown::instance()->text(Barrio::parseShortcode($content));
            // parse content
            $content = Barrio::parseShortcode($content);
            $content = Parsedown::instance()->text(Barrio::parseShortcode($content));
            // array
            $arr = $this->getHeaders($page);
            $arr['content'] = base64_encode($content);
            // print json
            $this->json($arr);
        }
        $this->json(array('status' => false));
    }

    /**
     *  File method
     *
     *  @return array
     */
    public function file()
    {
        if (array_key_exists('data', $_GET)) {
            $data = ($_GET['data']) ? $_GET['data'] : null;

            switch ($data) {
                // index.php?api=file&data=pages
                case 'pages': $this->pages(); break;
                // index.php?api=file&data=page
                case 'page': $this->page(); break;
            }
        }
        $this->json(array('status' => false));
    }

    /**
     *  Images method
     *
     *  @return array
     */
    public function image()
    {
        //index.php?api=image&url=public/notfound.jpg
        if (array_key_exists('url', $_GET)) {
            //index.php?api=image&url=public/notfound.jpg&w=1024
            if (array_key_exists('w', $_GET)) {
                //index.php?api=image&url=public/notfound.jpg&w=1024&h=768
                if (array_key_exists('h', $_GET)) {
                    if (array_key_exists('q', $_GET)) {
                        $this->resizeImg($_GET['url'], $_GET['w'], $_GET['h'], $_GET['q']);
                    } else {
                        $this->resizeImg($_GET['url'], $_GET['w'], $_GET['h']);
                    }
                } else {
                    if (array_key_exists('q', $_GET)) {
                        $this->resizeImg($_GET['url'], $_GET['w'], ($_GET['w']/2), $_GET['q']);
                    } else {
                        $this->resizeImg($_GET['url'], $_GET['w'], ($_GET['w']/2));
                    }
                }
            } elseif (array_key_exists('q', $_GET)) {
                $this->resizeImg($_GET['url'], 320, 180, $_GET['q']);
            } else {
                $this->resizeImg($_GET['url'], 320, 180, 50);
            }
        }
    }

    /**
     *  Manifest method
     *
     *  @return array
     */
    public function manifest()
    {
        $iconUrl = Barrio::urlBase().'/themes/'.Barrio::$config['theme'].'/assets/img/icons';
        
        $sizes = array(
            '192','144','114','120',
            '144','152','180','96',
            '72','76','60','57','36','48',
        );     

        $icons = array();
        foreach ($sizes as $size) {
            $density = '0.75';
            switch($size){
                case '192':
                    $density = '4.0';
                    break;
                case '144':
                    $density = '3.0';
                    break;
                case '96':
                    $density = '2.0';
                    break;
                case '72':
                    $density = '1.5';
                    break;
                case '48':
                    $density = '1.5';
                    break;
            }

            $icons[] =  array(
                "src" => $iconUrl."/apple-icon-{$size}x{$size}.png",
                "sizes" => "{$size}x{$size}",
                "type" => "image\/png",
                "density" => $density
            );

        };

        $json = array(
            "name" =>  Barrio::$config['title'],
            "short_name" =>  Barrio::$config['title'],
            "start_url" => Barrio::urlBase(),
            "display" => Barrio::$config['display'],
            "theme_color" => Barrio::$config['theme_color'],
            "background_color" => Barrio::$config['background_color'],
            "orientation" =>  Barrio::$config['orientation'],
            "icons" => $icons
        );
        // render json
        $this->json($json);
    }

    /**
     *  Sitemap method
     *
     *  @return array
     */
    public function sitemap()
    {
        $pages = Barrio::run()->pages('', 'date', 'DESC');
        $html = '<?xml version="1.0" encoding="UTF-8"?>';
        $html .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($pages as $page) {
            $url = trim(Barrio::urlBase().'/'.$page['slug']);
            $html .= '<url>
              <loc>'.$url.'</loc>
              <lastmod>'.date('c', $page['date']).'</lastmod>
           </url>';
        }
        $html .= '</urlset>';
        die($html);
    }


    /**
     *  help method
     *
     *  @return array
     */
    public function help()
    {
        $file = EXTENSIONS.'/api/help/index.php';
        $site_url = Barrio::urlBase();
        die(file_get_contents($file));
    }
}



if (array_key_exists('api', $_GET)) {
    // enable Cors
    Barrio::cors();
    // method
    $method = ($_GET['api']) ? $_GET['api'] : null;
    // init api
    $api = new Api();
    // Switch method
    switch ($method) {

        //index.php?api=help
        case 'help': $api->help(); break;

        //index.php?api=file&data=pages&name=blog
        //index.php?api=file&data=pages&name=blog&limit=2
        //index.php?api=file&data=pages&name=blog&filter=title
        //index.php?api=file&data=pages&name=blog&filter=images
        //index.php?api=file&data=pages&name=blog&filter=videos
        case 'file': $api->file(); break;

        //index.phpv[public url]
        //index.php?api=image&url=[public url]&w=[size width]
        //index.php?api=image&url=[public url]&w=[size width]&h=[size height]
        case 'image': $api->image(); break;

        //index.php?api=manifest
        case 'manifest': $api->manifest(); break;
        
        //index.php?api=sitemap
        case 'sitemap': $api->sitemap(); break;
    }
}
