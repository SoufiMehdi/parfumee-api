<?php

namespace App\Ecommerce\Infrastructure\Persistence\Doctrine\User;

use App\Ecommerce\Domain\Repository\User\UserRepositoryInterface;
use App\Ecommerce\Domain\Model\User\User;
use Symfony\Polyfill\Uuid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use App\Ecommerce\Infrastructure\Persistence\Mapper\User\UserMapper; 

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserMapper $mapper
        )
    {
    }
    public function findById(Uuid $id): User
    {
        return $this->entityManager->find(User::class, $id);
    }

    public function save(User $user): void
    {
        $doctrineUser = $this->mapper->toInfrastructure($user);
        $this->entityManager->persist($doctrineUser);
        $this->entityManager->flush();
    }

    public function delete(Uuid $id): void
    {
        $user = $this->findById($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}