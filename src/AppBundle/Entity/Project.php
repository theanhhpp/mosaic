<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/6/2015
 * Time: 3:21 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_project")
 * @Vich\Uploadable
 */
class Project
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
     * @Vich\UploadableField(mapping="project_image", fileNameProperty="img")
     * @var File
     */
    private $img_file;

    /**
     * @ORM\Column(type="string")
     */
    public $desc_en;

    /**
     * @ORM\Column(type="string")
     */
    public $desc_vi;

    /**
     * @ORM\Column(type="datetime")
     */
    public $update_datetime;


    /**
     * @ORM\ManyToMany(targetEntity="ProjectGroup")
     * @ORM\JoinTable(name="tbl_project_in_group",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="row_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="project_group_id", referencedColumnName="row_id")}
     *      )
     */
    public $projectGroups;

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


    public $images;


    public $desc;
    public $name;


    public function __construct() {
        $this->projectGroups = new ArrayCollection();
    }


    function setLocale($locale)
    {
        if ($locale == 'vi') {
            $this->name = $this->name_vi;
            $this->desc = $this->desc_vi;
        } else {
            $this->name = $this->name_en;
            $this->desc = $this->desc_en;
        }
    }
}