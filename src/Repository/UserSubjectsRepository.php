<?php

namespace App\Repository;

use App\Entity\UserSubjects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserSubjects|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSubjects|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSubjects[]    findAll()
 * @method UserSubjects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSubjectsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserSubjects::class);
    }

    // /**
    //  * @return UserSubjects[] Returns an array of UserSubjects objects
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
    public function findSubjectsRelatedToStudent($student)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user_email = :student')
            ->setParameter('student', $student)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?UserSubjects
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
