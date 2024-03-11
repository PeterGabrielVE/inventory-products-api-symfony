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

    public function getAllCategories(): array
    {
        $categories = $this->entityManager
            ->getRepository(Category::class)
            ->findAll();

        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'description' => $category->getDescription(),
            ];
        }

        return $data;
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

    public function getCategoryById(int $id): Array
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
   
        if (!$category) {
   
            return $this->json('No category found for id ' . $id, 404);
        }
   
        $data =  [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];

        return $data;
    }

    public function updateCategory(Category $category, ?string $name, ?string $description): Category
    {

        if ($name !== null) {
            $category->setName($name);
        }

        if ($description !== null) {
            $category->setDescription($description);
        }

        $this->entityManager->flush();

        return $category;
    }

    public function deleteCategory(Category $category): void
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

}