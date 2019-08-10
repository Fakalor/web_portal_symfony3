<?php
/**
 * User entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="email_idx",
 *              columns={"email"},
 *          )
 *     }
 * )
 */
class User implements UserInterface
{
    /**
     * Number of items per page.
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 10;

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
     * Login.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=45
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $login;

    /**
     * Email.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=45
     * )
     *
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "Email '{{ value }}' nie jest poprawny.",
     *     checkMX = true
     * )
     * @Assert\Length(
     *     max="45",
     * )
     */
    private $email;

    /**
     * Password.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="6",
     *     max="255",
     * )
     */
    private $password;

    /**
     * Warning.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $warning;

    /**
     * Ban.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $ban;

    /**
     * Role.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

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
     * Getter for Login.
     *
     * @return string|null Login
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * Setter for Login.
     *
     * @param string $login Login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * Getter for Email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for Email.
     *
     * @param string $email Email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for Password.
     *
     * @return string|null Password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter for Password.
     *
     * @param string $password Password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Getter for Warning.
     *
     * @return int|null Warning
     */
    public function getWarning(): ?int
    {
        return $this->warning;
    }

    /**
     * Setter for Warning.
     *
     * @param int $warning Warning
     */
    public function setWarning(int $warning): void
    {
        $this->warning = $warning;
    }

    /**
     * Getter for Ban.
     *
     * @return int|null Ban
     */
    public function getBan(): ?int
    {
        return $this->ban;
    }

    /**
     * Setter for Ban.
     *
     * @param int $ban Ban
     */
    public function setBan(int $ban): void
    {
        $this->ban = $ban;
    }

    /**
     * Getter for Role.
     *
     * @return Role|null Role
     */
    public function getRole(): ?Role
    {
        return $this->role;
    }

    /**
     * Setter for Role.
     *
     * @param Role|null $role Role
     */
    public function setRole(?Role $role): void
    {
        $this->role = $role;
    }

    /**
     * Getter for the Roles.
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles[] = $this->role->getRoleName();
        // guarantee every user at least has user
        $roles[] = 'user';

        return array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using bcrypt or argon
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     *
     * @see UserInterface
     *
     * @return string User name
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }
}
