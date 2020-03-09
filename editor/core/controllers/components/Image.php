<?php

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Image
{
    /**
     * resize thumbnail.
     *
     * @param <type> $picture       The picture
     * @param <type> $picture_thumb The picture thumb
     * @param int    $w             { parameter_description }
     *
     * @return bool ( description_of_the_return_value )
     */
    public static function resize($picture, $picture_thumb, $w)
    {
        // get src image
        if (pathinfo($picture, PATHINFO_EXTENSION) == 'jpg') {
            $source_image = imagecreatefromjpeg($picture);
        } elseif (pathinfo($picture, PATHINFO_EXTENSION) == 'png') {
            $source_image = imagecreatefrompng($picture);
        } else {
            return false;
        }

        $width = imagesx($source_image);
        $height = imagesy($source_image);

        // calc height according to given width
        $h = floor($height * ($w / $width));

        // create virtual
        $virtual_image = imagecreatetruecolor($w, $h);

        // copy src image
        imagecopyresized($virtual_image, $source_image, 0, 0, 0, 0, $w, $h, $width, $height);

        // create thumbnail
        if (pathinfo($picture, PATHINFO_EXTENSION) == 'jpg') {
            imagejpeg($virtual_image, $picture_thumb, 83);
        } elseif (pathinfo($picture, PATHINFO_EXTENSION) == 'png') {
            imagepng($virtual_image, $picture_thumb);
        }

        return true;
    }
}
