<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 10/29/2015
 * Time: 4:37 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_category")
 * @Vich\Uploadable
 */
class Category
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
     * @ORM\Column(type="integer")
     */
    public $category_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $img;

    /**
     * @Vich\UploadableField(mapping="category_image", fileNameProperty="img")
     * @var File
     */
    private $img_file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $seo_title_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $seo_title_vi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $seo_meta_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $seo_meta_vi;

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
    public $seo_title;
    public $seo_meta;
    public $url;

    function setLocale($locale)
    {
        if ($locale == 'vi')
        {
            $this->name = $this->name_vi;
            $this->seo_title = $this->seo_title_vi;
            $this->seo_meta = $this->seo_meta_vi;
        }
        else
        {
            $this->name = $this->name_en;
            $this->seo_title = $this->seo_title_en;
            $this->seo_meta = $this->seo_meta_en;
        }

        $this->url = str_replace(' ','-',preg_replace("/[^A-Za-z0-9 ]/", '', $this->name_en));
    }
}