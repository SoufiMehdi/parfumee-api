<?php

namespace App\Ecommerce\Infrastructure\Persistence\Doctrine\User;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Ecommerce\Domain\Model\User\User;
use Symfony\Polyfill\Uuid\Uuid;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    public function findById(Uuid $id): User
    {
        return $this->entityManager->find(User::class, $id);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(Uuid $id): void
    {
        $user = $this->findById($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}