<?php

namespace App\Repository;

use App\Entity\Candidats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidats>
 *
 * @method Candidats|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidats|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidats[]    findAll()
 * @method Candidats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidats::class);
    }

    public function add(Candidats $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Candidats $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
    * @return Candidats [] Returns an array of Candidate objects
    */
   public function findByRecruteur($recruteur): array
   {
       return $this->createQueryBuilder('c')
           ->join('c.user', 'u') 
           ->where('u = :val')           
           ->setParameter('val', $recruteur)
           ->orderBy('c.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }
//    /**
//     * @return Candidats[] Returns an array of Candidats objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Candidats
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
