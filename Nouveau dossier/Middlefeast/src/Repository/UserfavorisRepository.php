<?php

namespace App\Repository;

use App\Entity\Userfavoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Userfavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userfavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userfavoris[]    findAll()
 * @method Userfavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserfavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Userfavoris::class);
    }



    public function GetAllarticle(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM `article` INNER JOIN userfavoris_article ON article.id = userfavoris_article.article_id
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    // /**
    //  * @return Userfavoris[] Returns an array of Userfavoris objects
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
    public function findOneBySomeField($value): ?Userfavoris
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
