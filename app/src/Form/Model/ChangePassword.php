<?php
/**
 * Change password model.
 */

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangePassword.
 */
class ChangePassword
{
    /**
     * Old password.
     *
     * @var string
     *
     * @SecurityAssert\UserPassword(
     *     message = "Podane hasło nie zgadza się z twoim obecnym hasłem"
     * )
     */
    protected $oldPassword;

    /**
     * New password.
     *
     * @var string
     *
     * @Assert\Length(
     *     min = 6,
     *     max = 128
     * )
     */
    protected $newPassword;

    /**
     * Getter for old password.
     *
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Getter for new password.
     *
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * Setter for new password.
     *
     * @param string $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * Setter for old password.
     *
     * @param string $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }
}
