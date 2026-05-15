<?php

namespace App\Ecommerce\Infrastructure\Persistence\Mapper\Catalog;

use App\Ecommerce\Domain\Model\Catalog\Picture;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrinePicture;
use App\Ecommerce\Infrastructure\Persistence\Entity\Catalog\DoctrineProduct;
use Doctrine\ORM\EntityManagerInterface;

class PictureMapper
{
   public function __construct(
    private EntityManagerInterface $entityManager,
    ) {
        
    }

    public function toInfrastructure(Picture $picture): DoctrinePicture
    {
        $productEntity = null;
        if ($picture->getProduct()) {
            $productEntity = $this->entityManager->getRepository(DoctrineProduct::class)
            ->find($picture->getProduct()->getId());
        }   

        return new DoctrinePicture(
            $picture->getId(),
            $picture->url(),
            $picture->getAlt(),
            $picture->getSortOrder(),
            $productEntity,
            null
        );
    }
}