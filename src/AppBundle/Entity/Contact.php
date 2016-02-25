<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 12/14/2015
 * Time: 3:33 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="tbl_message_contact")
 */
class Contact
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
    public $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $subject;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $body;

}