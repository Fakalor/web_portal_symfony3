<?php
/**
 * Deal fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Deal;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class DealFixtures.
 */
class DealFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(300, 'deals', function ($i) {
            $deal = new Deal();
            $deal->setUser($this->getRandomReference('users'));
            $deal->setAuction($this->getRandomReference('auctions'));
            $deal->setDate(new \DateTime());
            $deal->setPrice($i + 2);

            return $deal;
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
            AuctionFixtures::class,
        );
    }
}
