<?php

namespace Template;

/**
 * Class Template
 *
 * @author    Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright 2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version 0.0.1
 *
 */
class Template
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // tags
        $this->tags = array();
        $tempFolder = ROOT_DIR . '/tmp/';
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder);
        }
        $this->tmp = $tempFolder;
    }

    /**
     * Callback
     *
     * @param mixed $variable the var
     *
     * @return mixed
     */
    public function callback($variable)
    {
        if (!is_string($variable) && is_callable($variable)) {
            return $variable();
        }
        return $variable;
    }

    /**
     *  Set var
     *
     * @param string $name  the key
     * @param string $value the value
     *
     * @return mixed
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * Append data in array
     *
     * @param string $name  the key
     * @param string $value the value
     *
     * @return null
     */
    public function append($name, $value)
    {
        $this->data[$name][] = $value;
    }
    /**
     * Parse content
     *
     * @param string $content the content
     *
     * @return string
     */
    private function _parse($content)
    {
        // replace tags with PHP
        foreach ($this->tags as $regexp => $replace) {
            if (strpos($replace, 'self') !== false) {
                $content = preg_replace_callback('#' . $regexp . '#s', $replace, $content);
            } else {
                $content = preg_replace('#' . $regexp . '#', $replace, $content);
            }
        }
        // replace variables
        if (preg_match_all('/(\$(?:[a-zA-Z0-9_-]+)(?:\.(?:(?:[a-zA-Z0-9_-][^\s]+)))*)/', $content, $matches)) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                // $a.b to $a["b"]
                $rep = $this->_replaceVariable($matches[1][$i]);
                $content = str_replace($matches[0][$i], $rep, $content);
            }
        }
        // remove spaces betweend %% and $
        $content = preg_replace('/\%\%\s+/', '%%', $content);
        // call cv() for signed variables
        if (preg_match_all('/\%\%(.)([a-zA-Z0-9_-]+)/', $content, $matches)) {
            for ($i = 0; $i < count($matches[2]); $i++) {
                if ($matches[1][$i] == '$') {
                    $content = str_replace($matches[0][$i], 'self::callback($' . $matches[2][$i] . ')', $content);
                } else {
                    $content = str_replace($matches[0][$i], $matches[1][$i] . $matches[2][$i], $content);
                }
            }
        }
        return $content;
    }
    /**
     * Run file
     *
     * @param string $file    the file
     * @param int    $counter the counter
     *
     * @return string
     */
    private function _run($file, $counter = 0)
    {
        $pathInfo = pathinfo($file);
        $tmpFile = $this->tmp . $pathInfo['basename'];
        if (!is_file($file)) {
            echo "Template '$file' not found.";
        } else {
            $content = file_get_contents($file);
            if ($this->_searchTags($content) && ($counter < 3)) {
                file_put_contents($tmpFile, $content);
                $content = $this->_run($tmpFile, ++$counter);
            }
            file_put_contents($tmpFile, $this->_parse($content));
            extract($this->data, EXTR_SKIP);
            ob_start();
            include $tmpFile;
            if (!DEV_MODE) {
                unlink($tmpFile);
            }
            return ob_get_clean();
        }
    }
    /**
     * Draw file
     *
     * @param string $file the file
     *
     * @return string
     */
    public function draw($file)
    {
        $result = $this->_run($file);
        return $result;
    }
    /**
     *  Comment
     *
     * @param string $content the content
     *
     * @return null
     */
    public function comment($content)
    {
        return null;
    }
    /**
     *  Search Tags
     *
     * @param string $content the content
     *
     * @return boolean
     */
    private function _searchTags($content)
    {
        foreach ($this->tags as $regexp => $replace) {
            if (preg_match('#' . $regexp . '#sU', $content, $matches)) {
                return true;
            }

        }
        return false;
    }
    /**
     * Dot notation
     *
     * @param string $var the var
     *
     * @return string
     */
    private function _replaceVariable($var)
    {
        if (strpos($var, '.') === false) {
            return $var;
        }
        return preg_replace('/\.([a-zA-Z\-_0-9]*(?![a-zA-Z\-_0-9]*(\'|\")))/', "['$1']", $var);
    }
}
