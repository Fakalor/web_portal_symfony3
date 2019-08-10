<?php
/**
 * Product fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProductFixtures.
 */
class ProductFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(200, 'products', function ($i) {
            $product = new Product();
            $product->setUser($this->getRandomReference('users'));
            $product->setProductCategory($this->getRandomReference('categories_products'));
            $product->setProductName($this->faker->colorName);
            $product->setDescription($this->faker->sentence);

            return $product;
        });

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return array(
            UserFixtures::class,
            ProductCategoryFixtures::class,
        );
    }
}
