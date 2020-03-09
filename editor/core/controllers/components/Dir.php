<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Dir
{
    /**
     * Protected constructor since this is a static class.
     */
    protected function __construct()
    {
        // Nothing here
    }

    /**
     * Creates a directory.
     *
     * @param string $dir   Name of directory to create
     * @param int    $chmod Chmod
     *
     * @return bool
     */
    public static function create($dir, $chmod = 0775)
    {
        // Redefine vars
        $dir = (string) $dir;

        // Create new dir if $dir !exists
        return (!self::exists($dir)) ? @mkdir($dir, $chmod, true) : true;
    }

    /**
     * Checks if this directory exists.
     *
     * @param string $dir Full path of the directory to check
     *
     * @return bool
     */
    public static function exists($dir)
    {
        // Redefine vars
        $dir = (string) $dir;

        // Directory exists
        if (file_exists($dir) && is_dir($dir)) {
            return true;
        }

        // Doesn't exist
        return false;
    }

    /**
     * Check dir permission.
     *
     * @param string $dir Directory to check
     *
     * @return string
     */
    public static function checkPerm($dir)
    {
        // Redefine vars
        $dir = (string) $dir;

        // Clear stat cache
        clearstatcache();

        // Return perm
        return substr(sprintf('%o', fileperms($dir)), -4);
    }

    /**
     * Delete directory.
     *
     * @param string $dir Name of directory to delete
     */
    public static function delete($dir)
    {
        // Redefine vars
        $dir = (string) $dir;

        // Delete dir
        if (is_dir($dir)) {
            $ob = scandir($dir);
            foreach ($ob as $o) {
                if ($o != '.' && $o != '..') {
                    if (filetype($dir.'/'.$o) == 'dir') {
                        self::delete($dir.'/'.$o);
                    } else {
                        unlink($dir.'/'.$o);
                    }
                }
            }
        }
        reset($ob);
        rmdir($dir);
    }

    /**
     * Get list of directories.
     *
     * @param string $dir Directory
     */
    public static function scan($dir)
    {
        // Redefine vars
        $dir = (string) $dir;

        // Scan dir
        if (is_dir($dir) && $dh = opendir($dir)) {
            $f = array();
            while ($fn = readdir($dh)) {
                if ($fn != '.' && $fn != '..' && is_dir($dir.'/'.$fn)) {
                    $f[] = $fn;
                }
            }

            return$f;
        }
    }

    /**
     * Check if a directory is writable.
     *
     * @param string $path The path to check
     *
     * @return booleans
     */
    public static function writable($path)
    {
        // Redefine vars
        $path = (string) $path;

        // Create temporary file
        $file = tempnam($path, 'writable');

        // File has been created
        if ($file !== false) {

            // Remove temporary file
            File::delete($file);

            //  Writable
            return true;
        }

        // Else not writable
        return false;
    }

    /**
     * Get directory size.
     *
     * @param string $path The path to directory
     *
     * @return int
     */
    public static function size($path)
    {
        // Redefine vars
        $path = (string) $path;

        $total_size = 0;
        $files = scandir($path);
        $clean_path = rtrim($path, '/').'/';

        foreach ($files as $t) {
            if ($t != '.' && $t != '..') {
                $current_file = $clean_path.$t;
                if (is_dir($current_file)) {
                    $total_size += self::size($current_file);
                } else {
                    $total_size += filesize($current_file);
                }
            }
        }

        // Return total size
        return $total_size;
    }
}
