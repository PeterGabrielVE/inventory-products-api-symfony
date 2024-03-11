<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllProducts(): array
    {
        $products = $this->entityManager
            ->getRepository(Product::class)
            ->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'category_id' => $product->getCategoryId(),
            ];
        }

        return $data;
    }

    public function createProduct(string $name, string $description,?int $price, ?int $quantity, ?int $categoryId): Product
    {
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setQuantity($quantity);
        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        if ($categoryId !== null && isset($category)) {
            $product->setCategoryId($categoryId);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function getProductById(int $id): Array
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
   
        if (!$product) {
   
            return $this->json('No Product found for id ' . $id, 404);
        }
   
        $data =  [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity(),
            'category_id' => $product->getCategoryId(),
        ];

        return $data;
    }

    public function updateProduct(Product $product, ?string $name, ?string $description, ?int $price, ?int $quantity, ?int $categoryId): Product
    {

        if ($name !== null) {
            $product->setName($name);
        }

        if ($description !== null) {
            $product->setDescription($description);
        }

        if ($price !== null) {
            $product->setPrice($price);
        }

        if ($quantity !== null) {
            $product->setQuantity($quantity);
        }

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        
        if ($categoryId !== null && isset($category)) {
            $product->setCategoryId($categoryId);
        }

        $this->entityManager->flush();

        return $product;
    }

    public function deleteProduct(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

}