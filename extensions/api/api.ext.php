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
    function json(array $data = array())
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
     *
     *  @return string image
     */
    function resizeImg($url, $width, $height) 
    {

        @header('Content-type: image/jpeg');

        list($width_orig, $height_orig) = getimagesize($url);

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
          $width = $height*$ratio_orig;
        } else {
          $height = $width/$ratio_orig;
        }

        // This resamples the image
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($url);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        // Output the image
        imagejpeg($image_p, null, 100);
    }

    /**
     *  Pages
     *  
     *  @return array
     */
    function pages()
    {
        // check if exists name
        if (array_key_exists('name', $_GET))
        {
            // get name or null
            $name = ($_GET['name']) ? $_GET['name'] : 'blog';
            // check if is a dir
            if(is_dir(CONTENT.'/'.$name))
            {
                // filter data
                if (array_key_exists('filter', $_GET))
                {
                    // get filter
                    $filter = ($_GET['filter']) ? $_GET['filter'] : null;
                    // get pages
                    $pages = Barrio::run()->pages($name,'date','DESC',array('index','404'),null);
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
                        case 'keywords': // get only titles
                            foreach ($pages as $item) {
                               $arr = array("keywords" => $item['keywords']);
                               // push array
                               array_push($output, $arr);
                            }
                            break;

                        // index.php?api=file&data=pages&name=blog&filter=tags
                        case 'tags': // get only titles
                            foreach ($pages as $item) {
                               $arr = array("tags" => $item['tags']);
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
                // index.php?api=file&data=pages&name=blog&limit=3
                elseif (array_key_exists('limit', $_GET))
                {
                    // get filter
                    $limit = ($_GET['limit']) ? $_GET['limit'] : 3;
                    // get pages
                    $pages = Barrio::run()->pages($name,'date','DESC',array('index','404'),$limit);
                    // init array
                    $output = array();
                    foreach ($pages as $item) {
                       $arr = array(
                            "title" => $item['title'],
                            "description" => $item['description'],
                            "tags" => $item['tags'],
                            "author" => $item['author'],
                            "image" => $item['image'],
                            "date" => $item['date'],
                            "robots" => $item['robots'],
                            "keywords" => $item['keywords'],
                            "template" => $item['template'],
                            "published" => $item['published'],
                            "background" => $item['background'],
                            "video" => $item['video'],
                            "color" => $item['color'],
                            "url" => $item['url']
                       );
                       // push array
                       array_push($output, $arr);
                    }
                    // print json
                    $this->json($output);
                }
                else

                {   // index.php?api=file&data=pages&name=blog
                    // get pages
                    $pages = Barrio::run()->pages($name,'date','DESC',array('index','404'),null);
                    // init array
                    $output = array();
                    foreach ($pages as $item) {
                       $arr = array(
                            "title" => $item['title'],
                            "description" => $item['description'],
                            "tags" => $item['tags'],
                            "author" => $item['author'],
                            "image" => $item['image'],
                            "date" => $item['date'],
                            "robots" => $item['robots'],
                            "keywords" => $item['keywords'],
                            "template" => $item['template'],
                            "published" => $item['published'],
                            "background" => $item['background'],
                            "video" => $item['video'],
                            "color" => $item['color'],
                            "url" => $item['url']
                       );
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
    function page()
    {
        // index.php?api=file&data=page&name=blog
        // check if exists name
        if (array_key_exists('name', $_GET))
        {
            // get name or null
            $name = ($_GET['name']) ? $_GET['name'] : '';
            $page = Barrio::run()->page($name);

            // convert shortcodes and markdown
            include EXTENSIONS.'/markdown/Parsedown/Parsedown.php';
            include EXTENSIONS.'/markdown/Parsedown/ParsedownExtra.php';
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
            $arr = array(
                "title" => $page['title'],
                "description" => $page['description'],
                "tags" => $page['tags'],
                "author" => $page['author'],
                "image" => $page['image'],
                "date" => $page['date'],
                "robots" => $page['robots'],
                "keywords" => $page['keywords'],
                "template" => $page['template'],
                "published" => $page['published'],
                "background" => $page['background'],
                "video" => $page['video'],
                "color" => $page['color'],
                "content" => $content
            );

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
    function file()
    {
        if (array_key_exists('data', $_GET))
        {
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
    function image()
    {
        //index.php?api=image&url=public/notfound.jpg
        if(array_key_exists('url', $_GET)){
            //index.php?api=image&url=public/notfound.jpg&w=1024
            if(array_key_exists('w', $_GET)){
                //index.php?api=image&url=public/notfound.jpg&w=1024&h=768
                if(array_key_exists('h', $_GET)){
                    $this->resizeImg($_GET['url'], $_GET['w'], $_GET['h']);
                }else{
                    $this->resizeImg($_GET['url'], $_GET['w'], ($_GET['w']/2));
                }
            }else{
                $this->resizeImg($_GET['url'], 320, 180);
            }
        }
    }

    /**
     *  Manifest method
     *
     *  @return array
     */
    function manifest()
    {
        $iconUrl = Barrio::urlBase().'/themes/'.Barrio::$config['theme'].'/assets/img/icons';
        $json = array(
            "name" =>  Barrio::$config['title'],
            "short_name" =>  Barrio::$config['title'],
            "start_url" => Barrio::urlBase(),
            "display" => Barrio::$config['display'],
            "theme_color" => Barrio::$config['theme_color'],
            "background_color" => Barrio::$config['background_color'],
            "orientation" =>  Barrio::$config['orientation'],
            "icons" => array(
                array(
                    "src" => $iconUrl."/apple-icon-114x114.png",
                    "sizes" => "114x114",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/apple-icon-120x120.png",
                    "sizes" => "120x120",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/apple-icon-144x144.png",
                    "sizes" => "144x144",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/apple-icon-152x152.png",
                    "sizes" => "152x152",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/apple-icon-180x180.png",
                    "sizes" => "180x180",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/apple-icon-57x57.png",
                    "sizes" => "57x57",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/apple-icon-60x60.png",
                    "sizes" => "60x60",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/apple-icon-72x72.png",
                    "sizes" => "72x72",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/apple-icon-76x76.png",
                    "sizes" => "76x76",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/android-icon-36x36.png",
                    "sizes" => "36x36",
                    "type" => "image\/png",
                    "density" => "0.75"
                ),
                array(
                    "src" => $iconUrl."/android-icon-48x48.png",
                    "sizes" => "48x48",
                    "type" => "image\/png",
                    "density" => "1.0"
                ),
                array(
                    "src" => $iconUrl."/android-icon-72x72.png",
                    "sizes" => "72x72",
                    "type" => "image\/png",
                    "density" => "1.5"
                ),
                array(
                    "src" => $iconUrl."/android-icon-96x96.png",
                    "sizes" => "96x96",
                    "type" => "image\/png",
                    "density" => "2.0"
                ),
                array(
                    "src" => $iconUrl."/android-icon-144x144.png",
                    "sizes" => "144x144",
                    "type" => "image\/png",
                    "density" => "3.0"
                ),
                array(
                    "src" => $iconUrl."/android-icon-192x192.png",
                    "sizes" => "192x192",
                    "type" => "image\/png",
                    "density" => "4.0"
                )
            )
        );
        // render json
        $this->json($json);
    }

    /**
     *  Sitemap method
     *
     *  @return array
     */
    function sitemap()
    {
        $pages = Barrio::run()->pages('','date','DESC');
        $html = '<?xml version="1.0" encoding="UTF-8"?>';
        $html .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($pages as $page) {
            $url = trim(Barrio::urlBase().'/'.$page['slug']);
            $html .= '<url>
              <loc>'.$url.'</loc>
              <lastmod>'.date('c',$page['date']).'</lastmod>
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
    function help()
    {
        $file = EXTENSIONS.'/api/help/index.html';
        die(file_get_contents($file));
    }
}



if ( array_key_exists('api', $_GET)){
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

        //index.php?api=image&url=[public url]
        //index.php?api=image&url=[public url]&w=[size width]
        //index.php?api=image&url=[public url]&w=[size width]&h=[size height]
        case 'image': $api->image(); break;

        //index.php?api=manifest
        case 'manifest': $api->manifest(); break;
        
        //index.php?api=sitemap
        case 'sitemap': $api->sitemap(); break;
    }
}


