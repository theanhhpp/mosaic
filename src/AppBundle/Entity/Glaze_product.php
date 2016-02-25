<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/27/2015
 * Time: 4:02 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbl__product_glaze")
 */
class GlazeProduct
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
    public $product_row_id;

    /**
     * @ORM\Column(type="integer")
     */
    public $glaze_row_id;

}