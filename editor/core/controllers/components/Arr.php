<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Arr
{
    /**
   *  Set array.
   *
   *  @param array $array   array
   *  @param string $path   path to array
   *  @param string $value  value to array
   */
    public static function set(&$array, $path, $value)
    {
        // Get segments from path
        $segments = explode('.', $path);
        // Loop through segments
        while (count($segments) > 1) {
            $segment = array_shift($segments);
            if (!isset($array[$segment]) || !is_array($array[$segment])) {
                $array[$segment] = [];
            }
            $array = &$array[$segment];
        }
        $array[array_shift($segments)] = $value;
    }
    /**
     *  Short array values.
     *
     *  @param array $a  array
     *  @param array $subkey   array
     *  @param array $order  null
     *
     *  @return value
     */
    public static function short($a, $subkey, $order = null)
    {
        if (count($a) != 0 || (!empty($a))) {
            foreach ($a as $k => $v) {
                $b[$k] = function_exists('mb_strtolower') ? mb_strtolower($v[$subkey]) : strtolower($v[$subkey]);
            }
            if ($order == null || $order == 'ASC') {
                asort($b);
            } elseif ($order == 'DESC') {
                arsort($b);
            }
            foreach ($b as $key => $val) {
                $c[] = $a[$key];
            }

            return $c;
        }
    }
    /**
     *  Get array data.
     *
     *  @param array $array   array
     *  @param array $path  path to array
     *  @param string $default  null
     *
     *  @return array
     */
    public static function get($array, $path, $default = null)
    {
        // Get segments from path
        $segments = explode('.', $path);
        // Loop through segments
        foreach ($segments as $segment) {
            // Check
            if (!is_array($array) || !isset($array[$segment])) {
                return $default;
            }
            // Write
            $array = $array[$segment];
        }
        // Return
        return $array;
    }
}
