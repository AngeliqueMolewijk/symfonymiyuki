<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserBead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBead>
 */
class UserBeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBead::class);
    }

    public function findSearchBeads(string|null $search, string|null $stock, User $user): array
    {

        $queryBuilder  = $this->createQueryBuilder('b')
            ->innerJoin('b.bead', 'c')
            ->andWhere('b.user = :userid')
            ->setParameter('userid', $user);
        if ($search) {
            $queryBuilder->andWhere('c.name LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $search . '%');
        }

        if ($stock) {
            $queryBuilder->andWhere('b.stock > 0');
        }
        return $queryBuilder
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBeads(User $user): array
    {

        $queryBuilder  = $this->createQueryBuilder('b')
            ->innerJoin('b.bead', 'c');

        if (!in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $queryBuilder->andWhere('b.user = :userid')
                ->setParameter('userid', $user);
        }
        return $queryBuilder
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
