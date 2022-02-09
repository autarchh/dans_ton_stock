<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function productByStorage($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.storage = :val')
            ->setParameter('val', $value)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function expiredProduct()
    {
        return $this->createQueryBuilder('p')
            ->Where('DATE_DIFF(p.bbDate, CURRENT_DATE()) <= 0')
            ->orderBy('p.bbDate', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
     /**
     * @return Product[] Returns an array of Product objects
     */
    public function findAllWithTotalDays($user_id)
    {
        return $this->getEntityManager()
                    ->createQuery(
                    'SELECT p.name, p, DATE_DIFF(p.bbDate, CURRENT_DATE()) AS restTime, COUNT(p.id) AS totalProduct
                    FROM App\Entity\Product p, App\Entity\Storage s
                    WHERE p.storage = s.id
                    AND s.user = :id
                    GROUP BY p.id')
                    ->setParameter('id', $user_id)
                    ->getResult();
    }

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
