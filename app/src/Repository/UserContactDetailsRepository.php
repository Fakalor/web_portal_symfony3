<?php
/**
 * User contact details repository.
 */

namespace App\Repository;

use App\Entity\UserContactDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserContactDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserContactDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserContactDetails[]    findAll()
 * @method UserContactDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserContactDetailsRepository extends ServiceEntityRepository
{
    /**
     * UserContactDetailsRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserContactDetails::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\UserContactDetails $userContactDetails Deal entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(UserContactDetails $userContactDetails): void
    {
        $this->_em->persist($userContactDetails);
        $this->_em->flush($userContactDetails);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\UserContactDetails $userContactDetails Deal entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(UserContactDetails $userContactDetails): void
    {
        $this->_em->remove($userContactDetails);
        $this->_em->flush($userContactDetails);
    }
    // /**
    //  * @return UserContactDetails[] Returns an array of UserContactDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserContactDetails
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
