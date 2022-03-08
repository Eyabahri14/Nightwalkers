<?php

namespace App\Repository;

use App\Entity\Thematique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Discussion;
/**
 * @method Thematique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thematique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thematique[]    findAll()
 * @method Thematique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThematiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thematique::class);
    }

    public function findActiveThematique()
    {
        return $this->createQueryBuilder('d')
            ->addSelect('COUNT(c.id) AS HIDDEN counter')
            ->innerJoin('App\Entity\Discussion',
                'c',
                'WITH',
                'c.thematique = d.id' )
            ->orderBy('counter','DESC')
            ->groupBy('d.id')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function rechercheAvance($str) {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT  t
                FROM App\Entity\Thematique t
                WHERE t.nom LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();

    }

























    // /**
    //  * @return Thematique[] Returns an array of Thematique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Thematique
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
