<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Message
{
    /**
     * Get message.
     *
     * @param string $callback The callback
     *
     * @return callback
     */
    public static function get()
    {
        //Top of file
        if (Session::get('msg')) {
            $msg = Session::get('msg');
            Session::delete('msg');
        }
        if (isset($msg)) {
            echo '<script type="text/javascript">message("'.$msg['title'].'","'.$msg['msg'].'");</script>';
        }
    }

    /**
     * Set message.
     *
     * @param array $title The title
     * @param array $msg   The message
     * @param array $type  The type
     */
    public static function set($title, $msg)
    {
        $data = array(
          'title' => $title,
          'msg' => $msg,
        );
        Session::set('msg', $data);
    }
}
