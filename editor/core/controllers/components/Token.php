<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Token
{
    /**
     * token.
     *
     * @var array
     */
    protected static $security_token_name = 'sqlcms_security_token';
    /**
     *   generate apikey.
     *
     *   @return $key,
     */
    public static function apikey()
    {
        $key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));

        return $key;
    }
    /**
     * Generate hash.
     *
     * @param   <type>  $pass   The pass
     * @param   <type>  $salt   The salt
     *
     * @return  hash
     */
    public static function hash($pass, $salt)
    {
        return hash('sha256', $pass.$salt);
    }

    /**
     * generate salt.
     *
     * @return  <type>  ( description_of_the_return_value )
     */
    public static function salt()
    {
        return uniqid(mt_rand(), true);
    }

    /**
     *  Generate token.
     *
     *  @param bolean $new false
     *
     *  @return bolean
     */
    public static function generate($new = false)
    {
        // Get the current token
        if (isset($_SESSION[(string) self::$security_token_name])) {
            $token = $_SESSION[(string) self::$security_token_name];
        } else {
            $token = null;
        }
        // Create a new unique token
        if ($new === true or !$token) {
            // Generate a new unique token
            $token = sha1(uniqid(mt_rand(), true));
            // Store the new token
            $_SESSION[(string) self::$security_token_name] = $token;
        }
        // Return token
        return $token;
    }
    /**
     *  Check token.
     *
     *  @param string $r  token
     *
     *  @return bolean
     */
    public static function check($t)
    {
        return self::generate() === $t;
    }
}
