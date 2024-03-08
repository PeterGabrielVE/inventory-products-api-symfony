<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Entity\Category;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $products = $doctrine
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

        return $this->json($data);
    }

    #[Route('/products', name: 'product_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
       
        $product = new Product();
        $name = $request->request->get('name');
        if ($name !== null) {
            $product->setName($name);
        }
        
        $description = $request->request->get('description');
        if ($description !== null) {
            $product->setDescription($description);
        }

        $price = $request->request->get('price');
        if ($price !== null) {
            $product->setPrice($price);
        }

        $quantity = $request->request->get('quantity');
        if ($quantity !== null) {
            $product->setQuantity($quantity);
        }

        $categoryId = $request->request->get('category_id');
        $category = $entityManager->getRepository(Category::class)->find($categoryId);
        if ($categoryId !== null && isset($category)) {
            $product->setCategoryId($categoryId);
        }

        $entityManager->persist($product);
        $entityManager->flush();
   
        $data =  [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity(),
            'category_id' => $product->getCategoryId(),
        ];
           
        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $product = $doctrine->getRepository(Product::class)->find($id);
   
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
           
        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
   
        if (!$product) {
            return $this->json('No Product found for id' . $id, 404);
        }

        $name = $request->request->get('name');
        if ($name !== null) {
            $product->setName($name);
        }
        
        $description = $request->request->get('description');
        if ($description !== null) {
            $product->setDescription($description);
        }

        $price = $request->request->get('price');
        if ($price !== null) {
            $product->setPrice($price);
        }

        $quantity = $request->request->get('quantity');
        if ($quantity !== null) {
            $product->setQuantity($quantity);
        }

        $categoryId = $request->request->get('category_id');
        $category = $entityManager->getRepository(Category::class)->find($categoryId);
        if ($categoryId !== null && isset($category)) {
            $product->setCategoryId($categoryId);
        }

        $entityManager->flush();
   
        $data =  [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity(),
            'category_id' => $product->getCategoryId(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/products/{id}', name: 'product_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
   
        if (!$product) {
            return $this->json('No Product found for id' . $id, 404);
        }
   
        $entityManager->remove($product);
        $entityManager->flush();
   
        return $this->json('Deleted a Product successfully with id ' . $id);
    }
}
