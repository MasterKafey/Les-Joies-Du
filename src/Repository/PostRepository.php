<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function findAllByPage($page = 1, $limit = 10): array
    {
        $queryBuilder = $this->createQueryBuilder('post');

        $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('post.id', 'DESC');;

        return $queryBuilder->getQuery()->getResult();
    }
}
