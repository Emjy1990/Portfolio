<?php

namespace App\Repository;

use App\Entity\OrderPaymentFarmer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderPaymentFarmer|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderPaymentFarmer|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderPaymentFarmer[]    findAll()
 * @method OrderPaymentFarmer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderPaymentFarmerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderPaymentFarmer::class);
    }

//    /**
//     * @return OrderPaymentFarmer[] Returns an array of OrderPaymentFarmer objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderPaymentFarmer
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
