<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findRandom($number)
    {
        $count = (int) $this->count([]);

        $result = $this->createQueryBuilder('p')
            ->setMaxResults(4)
            ->setFirstResult(rand(0, $count - $number))
            ->getQuery()
            ->getResult();

        shuffle($result);

        return $result;
    }

    public function findOneHeartStroke()
    {
        $count = (int) $this->count([]);

        return $this->createQueryBuilder('p')
            ->setMaxResults(1)
            ->setFirstResult(rand(0, $count - 1))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLastProducts()
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults(4)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
