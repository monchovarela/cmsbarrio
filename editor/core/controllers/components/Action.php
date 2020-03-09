<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Action
{
    // actions array
    private static $actions = array();

    /**
     * Add action.
     *
     * @param <type> $name     The name
     * @param <type> $func     The function
     * @param int    $priority The priority
     * @param array  $args     The arguments
     */
    public static function add($name, $func, $priority = 10, array $args = null)
    {
        // Hooks a function on to a specific action.
        static::$actions[] = array(
                        'name' => (string) $name,
                        'func' => $func,
                        'priority' => (int) $priority,
                        'args' => $args,
        );
    }
    /**
     * Run action.
     *
     * @param <type> $name   The name
     * @param array  $args   The arguments
     * @param bool   $return The return
     *
     * @return <type> ( description_of_the_return_value )
     */
    public static function run($name, $args = array(), $return = false)
    {
        // Redefine arguments
        $name = (string) $name;
        $return = (bool) $return;
        // Run action
        if (count(static::$actions) > 0) {
            // Sort actions by priority
            $actions = Arr::short(static::$actions, 'priority');
            // Loop through $actions array
            foreach ($actions as $action) {
                // Execute specific action
                if ($action['name'] == $name) {
                    // isset arguments ?
                    if (isset($args)) {
                        // Return or Render specific action results ?
                        if ($return) {
                            return call_user_func_array($action['func'], $args);
                        } else {
                            call_user_func_array($action['func'], $args);
                        }
                    } else {
                        if ($return) {
                            return call_user_func_array($action['func'], $action['args']);
                        } else {
                            call_user_func_array($action['func'], $action['args']);
                        }
                    }
                }
            }
        }
    }
}
