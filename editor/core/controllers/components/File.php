<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class File
{
    /**
   * File Exists.
   *
   * @param  <type>  $filename  The filename
   *
   * @return <type>  bolean
   */
    public static function exists($filename)
    {
        // Redefine vars
        $filename = (string) $filename;
        // Return
        return file_exists($filename) && is_file($filename);
    }

    /**
     * info.
     *
     * @param   <type>  $file             The file
     * @param   array   $returned_values  The returned values
     *
     * @return  info
     */
    public static function info($file, $returned_values = array('name', 'server_path', 'size', 'date','fileperms'))
    {
        if (!file_exists($file)) {
            return false;
        }

        if (is_string($returned_values)) {
            $returned_values = explode(',', $returned_values);
        }

        foreach ($returned_values as $key) {
            switch ($key) {
            case 'name':
                $fileinfo['name'] = substr(strrchr($file, DIRECTORY_SEPARATOR), 1);
                break;
            case 'server_path':
                $fileinfo['server_path'] = $file;
                break;
            case 'size':
                $fileinfo['size'] = self::size(filesize($file));
                break;
            case 'date':
                $fileinfo['date'] = date('d/m/Y', filemtime($file));
                break;
            case 'readable':
                $fileinfo['readable'] = is_readable($file);
                break;
            case 'writable':
                // There are known problems using is_weritable on IIS.  It may not be reliable - consider fileperms()
                $fileinfo['writable'] = is_writable($file);
                break;
            case 'executable':
                $fileinfo['executable'] = is_executable($file);
                break;
            case 'fileperms':
                $fileinfo['fileperms'] = fileperms($file);
                break;
        }
        }

        return $fileinfo;
    }

    /**
     * Size.
     *
     * @param   int  $bytes     The bytes
     * @param   int  $decimals  The decimals
     *
     * @return  size
     */
    public static function size($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).@$sz[$factor];
    }
    /**
     * Delete file.
     *
     * @param   $filename  The filename
     *
     * @return  unlink file
     */
    public static function delete($filename)
    {
        // Is array
        if (is_array($filename)) {
            // Delete each file in $filename array
            foreach ($filename as $file) {
                @unlink((string) $file);
            }
        } else {
            // Is string
            return @unlink((string) $filename);
        }
    }
    /**
     * Filname.
     *
     * @param   $filename  The filename
     *
     * @return  name
     */
    public static function name($filename)
    {
        // Redefine vars
        $filename = (string) $filename;
        // Return filename
        return basename($filename, '.'.self::ext($filename));
    }
    /**
     * Filext.
     *
     * @param  $filename  The filename
     *
     * @return Extension
     */
    public static function ext($filename)
    {
        // Redefine vars
        $filename = (string) $filename;
        // Return file extension
        return substr(strrchr($filename, '.'), 1);
    }

    /**
     * Scan files.
     */
    public static function scan($folder, $type = null, $file_path = true)
    {
        $data = array();
        if (is_dir($folder)) {
            $iterator = new RecursiveDirectoryIterator($folder);
            foreach (new RecursiveIteratorIterator($iterator) as $file) {
                if ($type !== null) {
                    if (is_array($type)) {
                        $file_ext = substr(strrchr($file->getFilename(), '.'), 1);
                        if (in_array($file_ext, $type)) {
                            if (strpos($file->getFilename(), $file_ext, 1)) {
                                if ($file_path) {
                                    $data[] = $file->getPathName();
                                } else {
                                    $data[] = $file->getFilename();
                                }
                            }
                        }
                    } else {
                        if (strpos($file->getFilename(), $type, 1)) {
                            if ($file_path) {
                                $data[] = $file->getPathName();
                            } else {
                                $data[] = $file->getFilename();
                            }
                        }
                    }
                } else {
                    if ($file->getFilename() !== '.' && $file->getFilename() !== '..') {
                        if ($file_path) {
                            $data[] = $file->getPathName();
                        } else {
                            $data[] = $file->getFilename();
                        }
                    }
                }
            }

            return $data;
        } else {
            return false;
        }
    }
    /**
     *  Format bytes.
     *
     * @param      int|string  $bytes  The bytes
     *
     * @return     int|string  ( description_of_the_return_value )
     */
    public static function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2).' kB';
        } elseif ($bytes > 1) {
            $bytes = $bytes.' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes.' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    /**
     * Rename file.
     *
     * @param <type> $from The from
     * @param <type> $to   { parameter_description }
     *
     * @return <type> ( description_of_the_return_value )
     */
    public static function rename($from, $to)
    {
        // Redefine vars
        $from = (string) $from;
        $to = (string) $to;
        // If file exists $to than rename it
        if (!self::exists($to)) {
            return rename($from, $to);
        }
        // Else return false
        return false;
    }
    /**
     * Fetch the content from a file or URL.
     *
     *  <code>
     *      echo File::getContent('filename.txt');
     *  </code>
     *
     * @param  string  $filename The file name
     * @return boolean
     */
    public static function getContent($filename)
    {
        // Redefine vars
        $filename = (string) $filename;
        // If file exists load it
        if (File::exists($filename)) {
            return file_get_contents($filename);
        }
    }
    /**
     * Writes a string to a file.
     *
     * @param  string  $filename    The path of the file.
     * @param  string  $content     The content that should be written.
     * @param  boolean $create_file Should the file be created if it doesn't exists?
     * @param  boolean $append      Should the content be appended if the file already exists?
     * @param  integer $chmod       Mode that should be applied on the file.
     * @return boolean
     */
    public static function setContent($filename, $content, $create_file = true, $append = false, $chmod = 0666)
    {
        // Redefine vars
        $filename    = (string) $filename;
        $content     = (string) $content;
        $create_file = (bool) $create_file;
        $append      = (bool) $append;
        // File may not be created, but it doesn't exist either
        if (! $create_file && File::exists($filename)) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' doesn't exist", array(__METHOD__)));
        }
        // Create directory recursively if needed
        Dir::create(dirname($filename));
        // Create file & open for writing
        $handler = ($append) ? @fopen($filename, 'a') : @fopen($filename, 'w');
        // Something went wrong
        if ($handler === false) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' could not be created. Check if PHP has enough permissions.", array(__METHOD__)));
        }
        // Store error reporting level
        $level = error_reporting();
        // Disable errors
        error_reporting(0);
        // Write to file
        $write = fwrite($handler, $content);
        // Validate write
        if ($write === false) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' could not be created. Check if PHP has enough permissions.", array(__METHOD__)));
        }
        // Close the file
        fclose($handler);
        // Chmod file
        chmod($filename, $chmod);
        // Restore error reporting level
        error_reporting($level);
        // Return
        return true;
    }
    /**
     * Copy file.
     *
     * @param <type> $from The from
     * @param <type> $to   { parameter_description }
     *
     * @return bool ( description_of_the_return_value )
     */
    public static function copy($from, $to)
    {
        // Redefine vars
        $from = (string) $from;
        $to = (string) $to;
        // If file !exists $from and exists $to then return false
        if (!self::exists($from) || self::exists($to)) {
            return false;
        }
        // Else copy file
        return copy($from, $to);
    }
}
