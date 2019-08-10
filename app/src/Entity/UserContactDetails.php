<?php
/**
 * User contact details entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserContactDetailsRepository")
 * @ORM\Table(name="contact_details_user")
 */
class UserContactDetails
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
     * Name.
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
     *     min="2",
     *     max="45",
     * )
     */
    private $name;

    /**
     * Surname.
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
     *     min="2",
     *     max="45",
     * )
     */
    private $surname;

    /**
     * Street.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=100
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="2",
     *     max="100",
     * )
     */
    private $street;

    /**
     * Postal code.
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
    private $postalCode;

    /**
     * City.
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
    private $city;

    /**
     * Country.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=90
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="90",
     * )
     */
    private $country;

    /**
     * Phone number.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=9
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="9",
     * )
     */
    private $phoneNumber;

    /**
     * User.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

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
     * Getter for Name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for Surname.
     *
     * @return string|null Surname
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Setter for Surname.
     *
     * @param string $surname Surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Getter for Street.
     *
     * @return string|null Street
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * Setter for Street.
     *
     * @param string $street Street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * Getter for Postal code.
     *
     * @return string|null Postal code
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Setter for Postal code.
     *
     * @param string $postalCode Postal code
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * Getter for City.
     *
     * @return string|null City
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Setter for City.
     *
     * @param string $city City
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * Getter for Country.
     *
     * @return string|null Country
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Setter for Country.
     *
     * @param string $country Country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * Getter for Phone number.
     *
     * @return string|null Phone number
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * Setter for Phone number.
     *
     * @param string $phoneNumber Phone number
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Getter for User.
     *
     * @return User|null User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for User.
     *
     * @param User $user User
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
