<?php

namespace App\Repository;

use App\Entity\Bead;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Beads>
 */
class BeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bead::class);
    }


    public function findSearchBeads(string|null $search, User $user): array
    {
        $queryBuilder  = $this->createQueryBuilder('b');
        if ($search && $search !== 'own') {
            $queryBuilder->andWhere('b.name LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $search . '%');
        }

        if ($search === 'own') {
            $queryBuilder
                ->innerJoin('b.userBeads', 'ub')
                ->andWhere('ub.user = :user')
                ->setParameter('user', $user);
        }

        return $queryBuilder
            ->orderBy('b.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
