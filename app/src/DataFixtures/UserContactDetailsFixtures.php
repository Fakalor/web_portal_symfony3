<?php
/**
 * User contact details fixtures.
 */

namespace App\DataFixtures;

use App\Entity\UserContactDetails;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserContactDetailsFixtures.
 */
class UserContactDetailsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'contact_details_user', function ($i) {
            $detail = new UserContactDetails();
            $detail->setName($this->faker->firstName);
            $detail->setSurname($this->faker->lastName);
            $detail->setStreet($this->faker->streetAddress);
            $detail->setPostalCode($this->faker->postcode);
            $detail->setCity($this->faker->city);
            $detail->setCountry($this->faker->country);
            $detail->setPhoneNumber($this->faker->numberBetween(100000000, 999999999));
            $detail->setUser($this->getReference('users_'.$i));

            return $detail;
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
        return [UserFixtures::class];
    }
}
