<?php

namespace App\Ecommerce\Infrastructure\Persistence\Doctrine\Catalog;

use App\Ecommerce\Domain\Model\Catalog\Category;

interface CategoryRepositoryInterface
{
    public function save(Category $category): void;
    public function findById(string $id): ?Category;
    public function findBySlug(string $slug): ?Category;
    public function findAll(): array;
}