<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 12/11/2015
 * Time: 10:24 PM
 */

namespace AppBundle\UserBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Mật khẩu cũ không đúng"
     * )
     */
    public $current1Password;

    /**
     * @Assert\Length(
     *     min = 1,
     *     minMessage = "Password should by at least 6 chars long"
     * )
     */
    protected $newPassword;

    public function setnewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function getnewPassword()
    {
        return $this->newPassword;
    }
}