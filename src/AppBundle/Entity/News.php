<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 12/4/2015
 * Time: 12:27 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_news")
 * @Vich\Uploadable
 */
Class News{

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
     * @ORM\Column(type="boolean")
     */
    public $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $img;

    /**     *
     * @Vich\UploadableField(mapping="news_image", fileNameProperty="img")
     * @var File
     */
    private $img_file;

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

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImgCoverFile(File $image = null)
    {
        $this->img_cover_file = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost

            $this->update_datetime = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImgCoverFile()
    {
        return $this->img_cover_file;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $img_cover;

    /**     *
     * @Vich\UploadableField(mapping="news_image", fileNameProperty="img_cover")
     * @var File
     */
    private $img_cover_file;


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
    public $seo_desc_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $seo_desc_vi;

    /**
     * @ORM\Column(type="string")
     */
    public $short_desc_en;

    /**
     * @ORM\Column(type="string")
     */
    public $short_desc_vi;

    /**
     * @ORM\Column(type="string")
     */
    public $content_en;

    /**
     * @ORM\Column(type="string")
     */
    public $content_vi;


    /**
     * @ORM\Column(type="datetime")
     */
    public $update_datetime;



    public $name;
    public $seo_title;
    public $seo_meta;
    public $short_desc;
    public $content;
    public $url;

    function setLocale($locale)
    {
        if ($locale == 'vi')
        {
            $this->name = $this->name_vi;
            $this->seo_title = $this->seo_title_vi;
            $this->seo_meta = $this->seo_desc_vi;
            $this->short_desc = $this->short_desc_vi;
            $this->content = $this->content_vi;
        }
        else
        {
            $this->name = $this->name_en;
            $this->seo_title = $this->seo_title_en;
            $this->seo_meta = $this->seo_desc_en;
            $this->short_desc = $this->short_desc_en;
            $this->content = $this->content_en;
        }

        $this->url = str_replace(' ','-',preg_replace("/[^A-Za-z0-9 ]/", '', $this->name_en));
    }

}