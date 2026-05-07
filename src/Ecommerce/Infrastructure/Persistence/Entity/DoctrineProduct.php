<?php

namespace App\Ecommerce\Infrastructure\Persistence\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Ecommerce\Domain\Model\Category;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
class DoctrineProduct
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Category $category;

    /**
     * C'est ici que réside la magie pour le côté générique.
     * Le type 'json' sur PostgreSQL crée une colonne JSONB par défaut.
     */
    #[ORM\Column(type: 'json')]
    private array $attributes = [];

    public function __construct(string $id, string $name, float $price, Category $category, array $attributes = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->attributes = $attributes;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): void { $this->price = $price;}
    public function getCategory(): Category { return $this->category; }
    public function setCategory(Category $category): void { $this->category = $category; }
}