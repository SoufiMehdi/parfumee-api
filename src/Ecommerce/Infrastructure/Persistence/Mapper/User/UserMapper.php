<?php

namespace App\Ecommerce\Infrastructure\Persistence\Mapper\User;

use App\Ecommerce\Domain\Model\User\User;
use App\Ecommerce\Infrastructure\Persistence\Entity\User\DoctrineUser;

class UserMapper {
    public function toInfrastructure(User $domainUser): DoctrineUser {
        $entity = new DoctrineUser(
            $domainUser->getId(),
            $domainUser->getEmail()->getValue(),
            $domainUser->getPasswordHash()
        );
        $entity->setRoles($domainUser->getRoles());
        return $entity;
    }
    
    /*public function toDomain(DoctrineUser $entity): User {
        return new User(
            $entity->id,
            $entity->email,
            $entity->password,
            'mehdi',
            'soufi',
            $entity->roles
        );
    }*/
}