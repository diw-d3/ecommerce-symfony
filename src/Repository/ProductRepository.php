<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    public function findLastProducts($number, $category = null)
    {
        $query = $this->createQueryBuilder('p')
            ->setMaxResults($number)
            ->orderBy('p.createdAt', 'DESC');

        if (null !== $category) {
            $query->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        $qb = $query->getQuery();

        // echo $qb->getSQL(); // Renvoie la requête SQL "brut"

        if ($number > 1) {
            return $qb->getResult();
        }

        return $qb->getOneOrNullResult();
    }

    public function findBestProducts()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.reviews', 'r')
            ->addSelect('r')
            ->groupBy('p.id')
            ->orderBy('SUM(r.note)', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    public function fingPaginator($page, $category = null)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->orderBy('p.id')
            ->setFirstResult(($page - 1) * 3)
            ->setMaxResults(3);

        if (null !== $category) {
            $queryBuilder->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        return new Paginator($queryBuilder->getQuery());
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
