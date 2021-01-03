<?php
/*
 * Declara al principio del archivo, las llamadas a las funciones respetarán
 * estrictamente los indicios de tipo (no se lanzarán a otro tipo).
 */
declare (strict_types = 1);

namespace File;

/**
 * Acceso restringido
 */
defined("ACCESS") or die("No tiene acceso a este archivo");

/**
 * Class File
 *
 * @author    Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright 2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version 0.0.1
 *
 */
class File
{
    /**
     * Escanea los archivos recursivamente.
     *
     * <code>
     *  File::scan(CONTENT,'md',false);
     * </code>
     *
     * @param string $folder    the folder
     * @param string $type      extension
     * @param bool   $file_path boolean
     *
     * @return array
     */
    public static function scan(string $folder = "", string $type = null, bool $file_path = true)
    {
        $data = array();
        if (is_dir($folder)) {
            $iterator = new \RecursiveDirectoryIterator($folder);
            foreach (new \RecursiveIteratorIterator($iterator) as $file) {
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

}
