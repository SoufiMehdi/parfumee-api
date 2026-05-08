<?php

namespace App\Ecommerce\Infrastructure\Persistence\Doctrine\Catalog;

use App\Ecommerce\Domain\Exception\Catalog\CategorieNotFoundException;
use App\Ecommerce\Domain\Model\Catalog\Product;
use App\Ecommerce\Domain\Repository\Catalog\ProductRepositoryInterface;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineProduct;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineCategory;
use App\Ecommerce\Infrastructure\Persistence\Mapper\ProductMapper;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineProductRepository implements ProductRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private ProductMapper $mapper;

    public function __construct(EntityManagerInterface $entityManager, ProductMapper $mapper)
    {
        $this->entityManager = $entityManager;
        $this->mapper = $mapper;
    }

    public function save(Product $product): void
    {
        // On doit d'abord récupérer la catégorie Doctrine correspondante
        $categoryRepo = $this->entityManager->getRepository(DoctrineCategory::class);
        $doctrineCategory = $categoryRepo->find($product->getCategory()->getId());

        if (!$doctrineCategory) {
           throw new CategorieNotFoundException($product->getCategory()->getId());
        }

        // On mappe le produit Domaine vers une entité Doctrine
        $doctrineProduct = $this->mapper->toInfrastructure($product, $doctrineCategory);

        // On persiste l'entité Doctrine
        $this->entityManager->persist($doctrineProduct);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Product
    {
        $doctrineProduct = $this->entityManager->getRepository(DoctrineProduct::class)->find($id);
        return $doctrineProduct ? $this->mapper->toDomain($doctrineProduct) : null;
    }

    public function findAll(): array
    {
        $doctrineProducts = $this->entityManager->getRepository(DoctrineProduct::class)->findAll();
        return array_map([$this->mapper, 'toDomain'], $doctrineProducts);
    }
}