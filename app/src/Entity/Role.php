<?php
/**
 * Role entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 * @ORM\Table(name="roles")
 */
class Role
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Role name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $roleName;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Role name.
     *
     * @return string|null Role name
     */
    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    /**
     * Setter for Role name.
     *
     * @param string $roleName Role name
     */
    public function setRoleName(string $roleName): void
    {
        $this->roleName = $roleName;
    }
}
