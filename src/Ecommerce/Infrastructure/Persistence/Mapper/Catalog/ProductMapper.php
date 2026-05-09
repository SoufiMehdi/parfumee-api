<?php

namespace App\Ecommerce\Infrastructure\Persistence\Mapper\Catalog;

use App\Ecommerce\Domain\Model\Catalog\Product;
use App\Ecommerce\Domain\Model\Catalog\Category;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineProduct;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineCategory;
use App\Ecommerce\Infrastructure\Persistence\Mapper\Catalog\CategoryMapper;

class ProductMapper
{
    public function __construct(
        private CategoryMapper $categoryMapper
    )
    {
        // Si tu as besoin d'injecter d'autres mappers ou services, tu peux le faire ici
    }
    /**
     * Transforme un modèle Domaine en entité Doctrine (Infrastructure)
     */
    public function toInfrastructure(Product $product, DoctrineCategory $category): DoctrineProduct
    {
        return new DoctrineProduct(
            $product->getId(),
            $product->getName(),
            $product->getPrice(),
            $category,
            $product->getAttributes()
        );
    }

    /**
     * Transforme une entité Doctrine en modèle Domaine
     */
    public function toDomain(DoctrineProduct $doctrineProduct): Product
    {

        return new Product(
            $doctrineProduct->getId(),
            $doctrineProduct->getName(),
            "slug-placeholder", // Tu peux ajouter le slug dans DoctrineProduct si besoin
            $doctrineProduct->getPrice(),
            $this->categoryMapper->toDomain($doctrineProduct->getCategory()),
            $doctrineProduct->getAttributes()
        );
    }
}