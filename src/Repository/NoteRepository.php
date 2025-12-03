<?php

namespace App\Repository;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function findNotesByAuthor(User $user): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.author = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

}
