<?php
/**
 * Deal entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DealRepository")
 * @ORM\Table(name="deals")
 */
class Deal
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
     * Price.
     *
     * @var float
     *
     * @ORM\Column(type="float")
     *
     * @Assert\NotNull
     * @Assert\GreaterThan(propertyPath="auction.startPrice")
     */
    private $price;

    /**
     * Auction.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Auction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auction;

    /**
     * User.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Date.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $date;

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
     * Getter for Price.
     *
     * @return float|null Price
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Setter for Price.
     *
     * @param float $price Price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * Getter for Auction.
     *
     * @return Auction|null Auction
     */
    public function getAuction(): ?Auction
    {
        return $this->auction;
    }

    /**
     * Setter for Auction.
     *
     * @param Auction|null $auction Auction
     */
    public function setAuction(?Auction $auction): void
    {
        $this->auction = $auction;
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
     * @param User|null $user User
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * Getter for Date.
     *
     * @return \DateTimeInterface|null Date
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Setter for Date.
     *
     * @param \DateTimeInterface $date Start date
     */
    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }
}
