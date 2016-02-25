<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 12/2/2015
 * Time: 4:33 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Utility\Util;


/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_project_img")
 */
class ProjectImg
{
    /**
     * @ORM\Column(name="row_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $project_row_id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    public $img;


    /**
     * @ORM\Column(type="string", length=255)
     */
    public $img_thumb;


    private $file;


    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }



    public function upload($rootDir, $webPathDir, $thumbWidth, $thumbHeight)
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return false;
        }

        $util = new Util();

        // Get extension & Create file name
        $extension = $this->getFile()-> guessExtension();
        if ($util->isImage($extension))
        {
            $fileName = uniqid();

            // Upload file
            $this->getFile()->move($rootDir, $fileName.'.'.$extension);

            // Save file path
            $this->img = $webPathDir.'/'.$fileName.'.'.$extension;

            // Create thumb
            $util->makeThumbnails($rootDir, $fileName.'.'.$extension, $rootDir, $fileName.'_tb.'.$extension, $thumbWidth, $thumbHeight);
            $this->img_thumb = $webPathDir.'/'.$fileName.'_tb.'.$extension;

            // clean up the file property as you won't need it anymore
            $this->file = null;

            return true;
        }
        else
            return false;

    }
}