<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 12/8/2015
 * Time: 3:27 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_product")
 * @Vich\Uploadable
 */
Class Product{
    /**
     * @ORM\Column(name="row_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $code;

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

    /**     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="img")
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
    public $desc_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $desc_vi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public  $deliver_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public  $deliver_vi;

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
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="row_id")
     */
    public $category;


    /**
     * @ORM\ManyToMany(targetEntity="Color")
     * @ORM\JoinTable(name="tbl_product_color",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="row_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="color_id", referencedColumnName="row_id")}
     *      )
     */
    public $color;

    /**
     * @ORM\ManyToMany(targetEntity="Glaze")
     * @ORM\JoinTable(name="tbl_product_glaze",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="row_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="glaze_id", referencedColumnName="row_id")}
     *      )
     */
    public $glaze;

    /**
     * @ORM\ManyToMany(targetEntity="Pattern")
     * @ORM\JoinTable(name="tbl_product_pattern",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="row_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pattern_id", referencedColumnName="row_id")}
     *      )
     */
    public $pattern;


    /**
     * @ORM\ManyToMany(targetEntity="Size")
     * @ORM\JoinTable(name="tbl_product_size",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="row_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="size_id", referencedColumnName="row_id")}
     *      )
     */
    public $size;


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
    public $desc;
    public $seo_meta;
    public $deliver;

    public $url;

    function setLocale($locale)
    {
        if ($locale == 'vi')
        {
            $this->name = $this->name_vi;
            $this->seo_title = $this->seo_title_vi;
            $this->seo_meta = $this->seo_meta_vi;
            $this->desc = $this->desc_vi;
            $this->deliver = $this->deliver_vi;
        }
        else
        {
            $this->name = $this->name_en;
            $this->seo_title = $this->seo_title_en;
            $this->seo_meta = $this->seo_meta_en;
            $this->desc = $this->desc_en;
            $this->deliver = $this->deliver_en;
        }

        $this->url = str_replace(' ','-',preg_replace("/[^A-Za-z0-9 ]/", '', $this->name_en));
    }






}