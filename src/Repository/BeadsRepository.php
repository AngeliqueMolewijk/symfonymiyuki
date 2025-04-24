<?php

namespace App\Repository;

use App\Entity\Bead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Beads>
 */
class BeadsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bead::class);
    }


    public function findSearchBeads(string|null $search, string|null $stock, $userid): array
    {
        $queryBuilder  = $this->createQueryBuilder('beads')
            ->select('s')
            ->from(Bead::class, 's')
            ->andWhere('s.userid = :userid')
            ->setParameter('userid',  $userid);
        if ($search) {
            $queryBuilder->andWhere('s.name LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $search . '%');
        }

        if ($stock) {
            $queryBuilder->andWhere('s.stock > 0');
        }
        return $queryBuilder
            ->orderBy('s.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
