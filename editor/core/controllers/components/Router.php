<?php

/**
 * Functions to make routes.
 *
 *  <pre>
 *    <code>
 *    $Router =  new Router();
 *    // go to http://localhost/foo
 *    $Router->Route('/foo',function(){
 *      echo 'I am in foo';
 *    });
 *
 *    // go to http://localhost/foo/1
 *    $Router->Route('/foo/(:num)',function($num){
 *      echo 'I am in foo '.$num;
 *    });
 *
 *    // go to http://localhost/foo/bar
 *    $Router->Route('/foo/(:any)',function($any){
 *      echo 'I am in foo '.$any;
 *    });
 *    $Router->lauch();
 *    </code>
 *  </pre>
 *
 *
 * @author Moncho Varela <nakome@gmail.com>
 * @version 1.0.0
 */
class Router
{
    private $routes = array();
    public static $config = array();

    /**
     * Load config.
     */
    public static function loadConfig()
    {
        $path = ROOT.'/config.php';
        if (file_exists($path)) {
            static::$config = (require $path);
        } else {
            die('ERROR, Where is Config file ?!');
        }
    }
    /**
     * Load components.
     */
    public static function loadComponent($file)
    {
        $filename = COMPONENTS.'/'.str_replace('\\', '/', $file).'.php';
        if (file_exists($filename)) {
            include $filename;
            if (class_exists($file)) {
                return true;
            }
        }

        return false;
    }

    /**
     *  Render Assets.
     *
     *  @param array $patterns  array
     *  @param array $callback  function
     */
    public function route($patterns, $callback)
    {
        // if not in array
        if (!is_array($patterns)) {
            $patterns = array($patterns);
        }
        foreach ($patterns as $pattern) {
            $pattern = trim($pattern, '/');
            // get any num all
            $pattern = str_replace(
                array('\(', '\)', '\|', '\:any', '\:num', '\:all', '#'),
                array('(', ')', '|', '[^/]+', '\d+', '.*?', '\#'),
                preg_quote($pattern, '/')
            );
            // this pattern
            $this->routes['#^'.$pattern.'$#'] = $callback;
        }
    }

    /**
     *  launch routes.
     */
    public function launch()
    {
        self::loadComponent('Action');
        self::loadComponent('Arr');
        self::loadComponent('Dir');
        self::loadComponent('File');
        self::loadComponent('Filter');
        self::loadComponent('Session');
        self::loadComponent('Token');
        self::loadComponent('Message');
        self::loadComponent('Image');
        self::loadComponent('Url');
        self::loadComponent('Parsedown');
        self::loadComponent('ParsedownExtra');
        self::loadComponent('Shortcode');


        // Turn on output buffering
        ob_start();
        // Sanitize url
        Url::runSanitize();
        // load config
        $this->loadConfig();
        // zona horaria por defecto
        date_default_timezone_set(static::$config['timezone']);
        // Send default header and set internal encoding
        header('Content-Type: text/html; charset='.static::$config['charset']);
        function_exists('mb_language') and mb_language('uni');
        function_exists('mb_regex_encoding') and mb_regex_encoding(static::$config['charset']);
        function_exists('mb_internal_encoding') and mb_internal_encoding(static::$config['charset']);
        // Gets the current configuration setting of magic_quotes_gpc and kill magic quotes
        if (get_magic_quotes_gpc()) {
            function stripslashesGPC(&$value)
            {
                $value = stripslashes($value);
            }
            array_walk_recursive($_GET, 'stripslashesGPC');
            array_walk_recursive($_POST, 'stripslashesGPC');
            array_walk_recursive($_COOKIE, 'stripslashesGPC');
            array_walk_recursive($_REQUEST, 'stripslashesGPC');
        }
        // Start the session
        Session::start();
        // launch
        $url = $_SERVER['REQUEST_URI'];
        $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (strpos($url, $base) === 0) {
            $url = substr($url, strlen($base));
        }
        $url = trim($url, '/');

        foreach ($this->routes as $pattern => $callback) {
            if (preg_match($pattern, $url, $params)) {
                array_shift($params);

                //return function
                return call_user_func_array($callback, array_values($params));
            }
        }
        // Page not found
        if ($this->is_404(Url::base())) {
            die('404. That’s an error. The requested URL was not found on this server.');
        }
        // end flush
        ob_end_flush();
        exit;
    }

    /**
     * Determines if 404.
     *
     * @param      <type>   $url    The url
     *
     * @return     bool  True if 404, False otherwise
     */
    public function is_404($url)
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        /* If the document has loaded successfully without any redirection or error */
        if ($httpCode >= 200 && $httpCode < 300) {
            return false;
        } else {
            return true;
        }
    }
}
