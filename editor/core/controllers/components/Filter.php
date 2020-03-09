<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Filter
{
    // filters array
    protected static $filters = array();

    /**
     * Apply filter.
     *
     * @param      <type>  $filter_name  The filter name
     * @param      <type>  $value        The value
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public static function apply($filter_name, $value)
    {
        // Redefine arguments
        $filter_name = (string) $filter_name;
        $args = array_slice(func_get_args(), 2);
        if (!isset(static::$filters[$filter_name])) {
            return $value;
        }
        foreach (static::$filters[$filter_name] as $priority => $functions) {
            if (!is_null($functions)) {
                foreach ($functions as $function) {
                    $all_args = array_merge(array($value), $args);
                    $function_name = $function['function'];
                    $accepted_args = $function['accepted_args'];
                    if ($accepted_args == 1) {
                        $the_args = array($value);
                    } elseif ($accepted_args > 1) {
                        $the_args = array_slice($all_args, 0, $accepted_args);
                    } elseif ($accepted_args == 0) {
                        $the_args = null;
                    } else {
                        $the_args = $all_args;
                    }
                    $value = call_user_func_array($function_name, $the_args);
                }
            }
        }

        return $value;
    }

    /**
     * Add filter.
     *
     * @param      <type>   $filter_name      The filter name
     * @param      <type>   $function_to_add  The function to add
     * @param      int  $priority         The priority
     * @param      int  $accepted_args    The accepted arguments
     *
     * @return     bool  ( description_of_the_return_value )
     */
    public static function add($filter_name, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        // Redefine arguments
        $filter_name = (string) $filter_name;
        $function_to_add = $function_to_add;
        $priority = (int) $priority;
        $accepted_args = (int) $accepted_args;
        // Check that we don't already have the same filter at the same priority. Thanks to WP :)
        if (isset(static::$filters[$filter_name]["$priority"])) {
            foreach (static::$filters[$filter_name]["$priority"] as $filter) {
                if ($filter['function'] == $function_to_add) {
                    return true;
                }
            }
        }
        static::$filters[$filter_name]["$priority"][] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
        // Sort
        ksort(static::$filters[$filter_name]["$priority"]);

        return true;
    }
}
