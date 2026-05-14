<?php

namespace App\Ecommerce\Domain\Model\Catalog;

final readonly class Picture
{

    public function __construct(
        private string $id,
        private string $url,
        private ?string $alt = null,
        private ?int $sortOrder = null
    ){
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function url(): string
    {
        return $this->url;
    }
    public function getAlt(): ?string
    {
        return $this->alt;
    }
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }
}   