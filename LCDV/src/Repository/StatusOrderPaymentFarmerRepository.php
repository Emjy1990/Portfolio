<?php

namespace App\Repository;

use App\Entity\StatusOrderPaymentFarmer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StatusOrderPaymentFarmer|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusOrderPaymentFarmer|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusOrderPaymentFarmer[]    findAll()
 * @method StatusOrderPaymentFarmer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusOrderPaymentFarmerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StatusOrderPaymentFarmer::class);
    }

//    /**
//     * @return StatusOrderPaymentFarmer[] Returns an array of StatusOrderPaymentFarmer objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatusOrderPaymentFarmer
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
