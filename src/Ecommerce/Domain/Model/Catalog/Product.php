<?php

namespace App\Ecommerce\Domain\Model\Catalog;

use App\Ecommerce\Domain\Exception\Catalog\InvalidPriceException;
use App\Ecommerce\Domain\Model\Catalog\Category;

class Product
{
    private string $id;
    private string $name;
    private string $slug;
    private float $price;
    private Category $category;
    /** @var array<string, mixed> */
    private array $attributes; // Stockera tes données spécifiques (parfum, taille, etc.)

    public function __construct(
        string $id,
        string $name,
        string $slug,
        float $price,
        Category $category,
        array $attributes = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->setPrice($price); // Utilisation d'un setter pour valider le métier
        $this->category = $category;
        $this->attributes = $attributes;
    }

    public function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new InvalidPriceException($price);
        }
        $this->price = $price;
    }

    // Getters...
    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getAttributes(): array { return $this->attributes; }
    public function getSlug(): string { return $this->slug; }
    public function getPrice(): float { return $this->price; }
    public function getCategory(): Category { return $this->category; }
}