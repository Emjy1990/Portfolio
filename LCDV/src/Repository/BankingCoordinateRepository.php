<?php

namespace App\Repository;

use App\Entity\BankingCoordinate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BankingCoordinate|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankingCoordinate|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankingCoordinate[]    findAll()
 * @method BankingCoordinate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankingCoordinateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BankingCoordinate::class);
    }

//    /**
//     * @return BankingCoordinate[] Returns an array of BankingCoordinate objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BankingCoordinate
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
