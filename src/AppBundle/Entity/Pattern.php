<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/3/2015
 * Time: 2:54 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_pattern")
 * @Vich\Uploadable
 */
class Pattern
{
    /**
     * @ORM\Column(name="row_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name_vi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $img;

    /**
     * @Vich\UploadableField(mapping="pattern_image", fileNameProperty="img")
     * @var File
     */
    private $img_file;

    /**
     * @ORM\Column(type="datetime")
     */
    public $update_datetime;

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImgFile(File $image = null)
    {
        $this->img_file = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost

            $this->update_datetime = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImgFile()
    {
        return $this->img_file;
    }

    public $name;

    function setLocale($locale)
    {
        if ($locale == 'vi') {
            $this->name = $this->name_vi;
        } else {
            $this->name = $this->name_en;
        }
    }
}