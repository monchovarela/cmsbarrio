<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Session
{
    /**
   *  Start session.
   */
    public static function start()
    {
        // Is session already started?
        if (!session_id()) {
            // Start the session
            return @session_start();
        }
        // If already started
        return true;
    }

    /**
     *  Remove session.
     */
    public static function delete()
    {
        // Loop all arguments
        foreach (func_get_args() as $argument) {
            // Array element
            if (is_array($argument)) {
                // Loop the keys
                foreach ($argument as $key) {
                    // Unset session key
                    unset($_SESSION[(string) $key]);
                }
            } else {
                // Remove from array
                unset($_SESSION[(string) $argument]);
            }
        }
    }

    /**
     *  Destroy session.
     */
    public static function destroy()
    {
        // Destroy
        if (session_id()) {
            session_unset();
            session_destroy();
            $_SESSION = array();
        }
    }
    /**
     *  Check session.
     */
    public static function exists()
    {
        // Start session if needed
        if (!session_id()) {
            self::start();
        }
        // Loop all arguments
        foreach (func_get_args() as $argument) {
            // Array element
            if (is_array($argument)) {
                // Loop the keys
                foreach ($argument as $key) {
                    // Does NOT exist
                    if (!isset($_SESSION[(string) $key])) {
                        return false;
                    }
                }
            } else {
                // Does NOT exist
                if (!isset($_SESSION[(string) $argument])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     *  Get session.
     */
    public static function get($key)
    {
        // Start session if needed
        if (!session_id()) {
            self::start();
        }
        // Redefine key
        $key = (string) $key;
        // Fetch key
        if (self::exists((string) $key)) {
            return $_SESSION[(string) $key];
        }
        // Key doesn't exist
        return;
    }
    /**
     *  Set session.
     *
     *  @param  string $key   key
     *  @param  string $value   value
     */
    public static function set($key, $value)
    {
        // Start session if needed
        if (!session_id()) {
            self::start();
        }
        // Set key
        $_SESSION[(string) $key] = $value;
    }
}
