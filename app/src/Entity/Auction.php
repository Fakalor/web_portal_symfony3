<?php
/**
 * Auction entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuctionRepository")
 * @ORM\Table(name="auctions")
 */
class Auction
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
     * Start date.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $startDate;

    /**
     * End date.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $endDate;

    /**
     * Start price.
     *
     * @var float
     *
     * @ORM\Column(
     *     type="float",
     *     nullable=true
     * )
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(
     *      value = 0.1
     * )
     */
    private $startPrice;

    /**
     * Description.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="text",
     *     length=500,
     *     nullable=true
     * )
     *
     * @Assert\Length(
     *     min = "3",
     *     max = "500",
     * )
     */
    private $description;

    /**
     * Product.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

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
     * Getter for Start date.
     *
     * @return \DateTimeInterface|null Start date
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * Setter for Start date.
     *
     * @param \DateTimeInterface $startDate Start date
     */
    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * Getter for End date.
     *
     * @return \DateTimeInterface|null End date
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * Setter for End date.
     *
     * @param \DateTimeInterface $endDate End date
     */
    public function setEndDate(\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * Getter for Start price.
     *
     * @return float|null Start price
     */
    public function getStartPrice(): ?float
    {
        return $this->startPrice;
    }

    /**
     * Setter for Start price.
     *
     * @param float|null $startPrice Start price
     */
    public function setStartPrice(?float $startPrice): void
    {
        $this->startPrice = $startPrice;
    }

    /**
     * Getter for Description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for Description.
     *
     * @param string|null $description Description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Getter for Product.
     *
     * @return Product|null Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * Setter for Product.
     *
     * @param Product|null $product Product
     */
    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }
}
