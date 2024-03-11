<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Service\CategoryService;

#[Route('/api', name: 'api_')]
class CategoryController extends AbstractController
{

    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[Route('/categories', name: 'category_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();

        return $this->json($categories);
    }

    #[Route('/categories', name: 'category_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
   
        $category = new Category();
        $name = $request->request->get('name');
        $description = $request->request->get('description');

        $category = $this->categoryService->createCategory($name, $description);

        $data =  [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];
           
        return $this->json($data);
    }

    #[Route('/categories/{id}', name: 'category_show', methods:['get'] )]
    public function show(int $id): JsonResponse
    {

        $category = $this->categoryService->getCategoryById($id);

        if (!$category) {
            return $this->json('No category found for id ' . $id, 404);
        }

        return $this->json($category);

    }

    #[Route('/categories/{id}', name: 'category_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->json('No Category found for id' . $id, 404);
        }

        $name = $request->request->get('name');
        $description = $request->request->get('description');

        $updatedCategory =  $this->categoryService->updateCategory($category, $name, $description);

        return $this->json([
            'id' => $updatedCategory->getId(),
            'name' => $updatedCategory->getName(),
            'description' => $updatedCategory->getDescription(),
        ]);
    }
 
    #[Route('/categories/{id}', name: 'category_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, CategoryService $categoryService, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
   
        if (!$category) {
            return $this->json('No Category found for id ' . $id, 404);
        }
   
        // Llamar al servicio para eliminar la categoría
        $categoryService->deleteCategory($category);
   
        return $this->json('Deleted a Category successfully with id ' . $id);
    }
}
