<?php
/**
 * Product entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
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
     * Product name.
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
    private $productName;

    /**
     * Description.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="text",
     *     length=500
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="500",
     * )
     */
    private $description;

    /**
     * Product Category.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCategory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productCategory;

    /**
     * User.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
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
     * Getter for Product name.
     *
     * @return string|null Product name
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * Setter for Product name.
     *
     * @param string $productName Product name
     */
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
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
     * @param string $description Description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Getter for Product category.
     *
     * @return ProductCategory|null Product category
     */
    public function getProductCategory(): ?ProductCategory
    {
        return $this->productCategory;
    }

    /**
     * Setter for Product category.
     *
     * @param ProductCategory|null $productCategory Product category
     */
    public function setProductCategory(?ProductCategory $productCategory): void
    {
        $this->productCategory = $productCategory;
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
}
