<?php


class Shortcode
{
    protected static $shortcode_tags = array();

    /**
     * Shortcode function.
     *
     *  <code>
     *      Shortcode::add('demo',function($args){
     *              // shortcode
     *      });
     *  </code>
     *
     * @param string $shortcode         the name
     * @param array  $callback_function The arguments
     *
     * @return <type> ( description_of_the_return_value )
     */
    public static function add($shortcode, $callback_function)
    {
        $shortcode = (string) $shortcode;
        if (is_callable($callback_function)) {
            self::$shortcode_tags[$shortcode] = $callback_function;
        }
    }


    /**
     * Shortcode parse.
     *
     * @param string $content the shortcode content
     *
     * @return <type> ( description_of_the_return_value )
     */
    public static function parse($content)
    {
        if (!self::$shortcode_tags) {
            return $content;
        }
        $shortcodes = implode('|', array_map('preg_quote', array_keys(self::$shortcode_tags)));
        $pattern = "/(.?)\\{([{$shortcodes}]+)(.*?)(\\/)?\\}(?(4)|(?:(.+?)\\{\\/\\s*\\2\\s*\\}))?(.?)/s";

        return preg_replace_callback($pattern, 'self::handle', $content);
    }


    /**
     * Handle Shortcode
     *
     * @param array $matches the matches
     *
     * @return <type> ( description_of_the_return_value
     */
    protected static function handle($matches)
    {
        $prefix = $matches[1];
        $suffix = $matches[6];
        $shortcode = $matches[2];
        if ($prefix == '{' && $suffix == '}') {
            return substr($matches[0], 1, -1);
        }
        $attributes = array();
        if (preg_match_all('/(\\w+) *= *(?:([\'"])(.*?)\\2|([^ "\'>]+))/', $matches[3], $match, PREG_SET_ORDER)) {
            foreach ($match as $attribute) {
                if (!empty($attribute[4])) {
                    $attributes[strtolower($attribute[1])] = $attribute[4];
                } elseif (!empty($attribute[3])) {
                    $attributes[strtolower($attribute[1])] = $attribute[3];
                }
            }
        }

        return isset(self::$shortcode_tags[$shortcode]) ? $prefix.call_user_func(self::$shortcode_tags[$shortcode], $attributes, $matches[5], $shortcode).$suffix : '';
    }
}
