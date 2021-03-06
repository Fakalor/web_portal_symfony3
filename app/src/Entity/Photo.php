<?php
/**
 * Photo entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(name="photos")
 */
class Photo
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
     * Product photo.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255
     * )
     */
    private $productPhoto;

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
     * Getter for Product photo.
     *
     * @return string|null Product photo
     */
    public function getProductPhoto(): ?string
    {
        return $this->productPhoto;
    }

    /**
     * Setter for Product photo.
     *
     * @param string $productPhoto Product photo
     */
    public function setProductPhoto(string $productPhoto): void
    {
        $this->productPhoto = $productPhoto;
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
