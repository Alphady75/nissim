<?php

namespace App\Repository;

use App\Entity\Financement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Financement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Financement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Financement[]    findAll()
 * @method Financement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinancementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Financement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Financement $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Financement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
      * @return Financement[] Returns an array of Financement objects
    */
    public function findByDateDesc()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Financement[]
     */
    public function findLasted($limit = null)
    {
        if(!$limit){
            $limit = 10;
        }

        return $this->createQueryBuilder('f')
            //->andWhere('u.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy('f.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUser($user, $limit = null)
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.created', 'DESC')
            ->andWhere('f.user = :user')
            ->setMaxResults($limit ? $limit : 99999999)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTotalSum()
    {
        return $this->createQueryBuilder('f')
            ->select("SUM(f.montant) as montantTotal")
            ->andWhere('f.statut = :statut')
            ->setParameter('statut', 1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
