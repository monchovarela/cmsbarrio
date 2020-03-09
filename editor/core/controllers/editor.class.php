<?php



class Editor{

    /**
     * Parse content
     *
     * @param string $content the content
     *
     * @return $content (array)
     */
    public function parseContent($data)
    {
        $tags = '<div><h1><h2><h3><h4><h5><h6><table><td><tr><th><hr><img><br><ul><ol><li><a><button><i><em><bloqcuote><iframe><video><b><strong><i><pre><code><style></style>';

        // nivel 1
        $content = Filter::apply('content',$data,1);
        $content = Parsedown::instance()->text($content);
        $content = strip_tags($content, $tags);
        $content = Shortcode::parse($content);
        $content = preg_replace('/\s+/', ' ', $content);

        // nivel 2
        $content = Filter::apply('content',$content,1);
        $content = Parsedown::instance()->text($content);
        $content = Shortcode::parse($content);
        $content = preg_replace('/\s+/', ' ', $content);
        // nivel 3
        $content = Filter::apply('content',$content,1);
        $content = Parsedown::instance()->text($content);
        $content = Shortcode::parse($content);
        $content = preg_replace('/\s+/', ' ', $content);
        // nivel 4
        $content = Filter::apply('content',$content,1);
        $content = Parsedown::instance()->text($content);
        $content = Shortcode::parse($content);
        $content = preg_replace('/\s+/', ' ', $content);

        // salida
        $content = base64_encode(Shortcode::parse(Parsedown::instance()->text($content)));
        $content = static::evalPHP($content);
        return $content;
    }

    /**
     * Eval Content.
     *
     * @param string $data the data
     *
     * @return $data
     */
    protected static function obEval($data)
    {
        ob_start();
        eval($data[1]);
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }

    /**
     * Eval Php
     *
     * @param string $str the string to eval
     *
     * @return callback
     */
    protected static function evalPHP($str)
    {
        return preg_replace_callback('/\\{php\\}(.*?)\\{\\/php\\}/ms', 'Editor::obEval', $str);
    }

    /**
     * Record logs
     *
     * @param string $desc the data
     *
     */
    public static function log($desc = '')
    {
        $file = ROOT.'/logs.txt';
        if (File::exists($file)) {
            $data = json_decode(File::getContent($file), true);
            $data[] = array(
                'date' => date('d/m/Y H:i:s'),
                'desc' => $desc
            );
            File::setContent($file, json_encode($data));
        }
    }

    /**
     * Get Log
     *
     * @return array
     */
    public static function getLog()
    {
        $file = ROOT.'/logs.txt';
        if (File::exists($file)) {
            $data = json_decode(File::getContent($file), true);
            if (is_array($data)) {
                return $data;
            }
        }
    }

    /**
     * Clean Log
     *
     * @return  boolean
     */
    public static function cleanLog()
    {
        $file = ROOT.'/logs.txt';
        if (File::exists($file)) {
            File::setContent($file, '[]', true, false, '0755');
            Message::set('Bien !', 'El archivo ha sido borrado');
            Url::redirect(Url::base());
        }
    }
}