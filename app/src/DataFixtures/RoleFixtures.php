<?php
/**
 * Role fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class RoleFixtures.
 */
class RoleFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setRoleName('ROLE_ADMIN');
        $this->addReference('roles_1', $role);
        $manager->persist($role);

        $role = new Role();
        $role->setRoleName('ROLE_USER');
        $this->addReference('roles_2', $role);
        $manager->persist($role);

        $manager->flush();
    }
}
