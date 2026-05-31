<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Ecommerce\Domain\Model\User\User;
use Symfony\Polyfill\Uuid\Uuid;

/**
 * Interface respectant le principe d'Interface Segregation (ISP).
 */
interface UserRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function findById(Uuid $id): User;

    public function save(User $user): void;

    public function delete(Uuid $id): void;
}