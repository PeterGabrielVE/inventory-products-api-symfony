<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Entity\Category;
use App\Service\ProductService;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{

    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('/products', name: 'product_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        return $this->json($products);
    }

    #[Route('/products', name: 'product_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $category = new Category();
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $quantity = $request->request->get('quantity');
        $categoryId = $request->request->get('category_id');

        $product = $this->productService->createProduct($name, $description, $price, $quantity, $categoryId);

        $data =  [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getprice(),
            'quantity' => $product->getQuantity(),
            'category_id' => $product->getCategoryId(),
        ];
           
        return $this->json($data);
    }

    #[Route('/products/{id}', name: 'product_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return $this->json('No category found for id ' . $id, 404);
        }

        return $this->json($product);
    }

    #[Route('/products/{id}', name: 'product_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No Product found for id ' . $id, 404);
        }

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $quantity = $request->request->get('quantity');
        $categoryId = $request->request->get('category_id');

        $updatedProduct =  $this->productService->updateProduct($product, $name, $description, $price, $quantity, $categoryId);

        return $this->json([
            'id' => $updatedProduct->getId(),
            'name' => $updatedProduct->getName(),
            'description' => $updatedProduct->getDescription(),
            'price' => $updatedProduct->getPrice(),
            'quantity' => $updatedProduct->getQuantity(),
            'category_id' => $updatedProduct->getCategoryId(),
        ]);

      
        
    }
 
    #[Route('/products/{id}', name: 'product_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
   
        if (!$product) {
            return $this->json('No Product found for id ' . $id, 404);
        }
   
        $this->productService->deleteProduct($product);
   
        return $this->json('Deleted a product successfully with id ' . $id);
    }
}
