<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 12/2/2015
 * Time: 5:52 PM
 */

namespace AppBundle\Utility;


class Util
{
    function isImage($ext)
    {
        return in_array(strtolower($ext),
            array("jpg", "jpeg", "gif", "png"));
    }

    function makeThumbnails($src_dir, $src_file, $dst_dir, $dst_file, $new_width, $new_height)
    {
        $path = $src_dir . "/" . $src_file;
        list($width, $height) = getimagesize($path);

        if($width/$new_width > $height/$new_height && $width/$new_width > 1) {
            $new_height = $new_width * $height / $width;
        }
        else if($height/$new_height >= $width/$new_width && $height/$new_height > 1) {
            $new_width = $new_height * $width / $height;
        }
        else
        {
            $new_width = $width;
            $new_height = $height;
        }

        preg_match("/\.[^\.]+$/i", $src_file, $ext);
        $type = strtolower($ext[0]);
        $new_file = $dst_dir . "/" . $dst_file;

        switch($type)
        {
            case '.gif':

                $image_p = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromgif($path);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagegif($image_p, $new_file);
                break;

            case '.jpg':
            case '.jpeg':
                $image_p = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($path);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_p, $new_file, 100);
                break;

            case '.png':
                $image_p = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($path);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagepng($image_p, $new_file);
                break;
        }
    }
}