<?php

namespace App\Repository;

use App\Entity\Projet;
use App\Entity\ProjetSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

use function PHPUnit\Framework\isNull;

/**
 * @method Projet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projet[]    findAll()
 * @method Projet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Projet $entity, bool $flush = true): void
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
    public function remove(Projet $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Permet de recupérer les projets par date, ordre décroissante
    * @return Projet[] Returns an array of Projet objects
    */
    public function findByDateDesc($visible = null)
    {
        if(isNull($visible)){
            $visible = 1;
        }

        return $this->createQueryBuilder('p')
            ->andWhere('p.visible = :visible')
            ->setParameter('visible', $visible)
            ->orderBy('p.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findOneBySomeField($value): ?Projet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Undocumented function
     *
     * @param ProjetSearch $search
     * @return Projet[]
     */
    public function findSearch(ProjetSearch $search): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.created', 'DESC')
        ;

        if(!empty($search->q)){
            $query = $query
            ->andWhere('p.name LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        }

        return $query->getQuery()->getResult();
    }
}
