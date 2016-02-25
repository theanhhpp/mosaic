<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/16/2015
 * Time: 12:10 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_project_group")
 * @Vich\Uploadable
 */

class ProjectGroup
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
     * @Vich\UploadableField(mapping="project_group_image", fileNameProperty="img")
     * @var File
     */
    private $img_file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $img_cover;

    /**     *
     * @Vich\UploadableField(mapping="project_group_image", fileNameProperty="img_cover")
     * @var File
     */
    private $img_cover_file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public$desc_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $desc_vi;

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
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\JoinTable(name="tbl_project_in_group",
     *      joinColumns={@ORM\JoinColumn(name="project_group_id", referencedColumnName="row_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="row_id")}
     *      )
     */
    public $projects;


    public $desc;
    public $seo_title;
    public $seo_meta;
    public $name;
    public $url;

    public function __construct() {
        $this->projects = new ArrayCollection();
    }


    public function setLocale($locale)
    {
        if ($locale == 'vi') {
            $this->name = $this->name_vi;
            $this->desc = $this->desc_vi;
            $this->seo_title = $this->seo_title_vi;
            $this->seo_meta = $this->seo_meta_vi;
        } else {
            $this->name = $this->name_en;
            $this->desc = $this->desc_en;
            $this->seo_title = $this->seo_title_en;
            $this->seo_meta = $this->seo_meta_en;
        }
		
		$this->url = str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $this->name_en));
    }
}