<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 12/10/2015
 * Time: 2:13 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_size")
 */
class Size
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
    public $size_value;
}