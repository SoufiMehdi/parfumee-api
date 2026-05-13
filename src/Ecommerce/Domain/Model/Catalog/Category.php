<?php

namespace App\Ecommerce\Domain\Model\Catalog;

class Category
{
    private string $id;
    private string $name;
    private string $slug;

    public function __construct(string $id, string $name, string $slug)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getSlug(): string { return $this->slug; }
}