<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createCategory(string $name, string $description): Category
    {
        $category = new Category();
        $category->setName($name);
        $category->setDescription($description);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    // Otros métodos para actualizar, eliminar y obtener categorías...
}