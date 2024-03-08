<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;

#[Route('/api', name: 'api_')]
class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'category_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $categories = $doctrine
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

        return $this->json($data);
    }

    #[Route('/categories', name: 'category_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
   
        $category = new Category();
        $name = $request->request->get('name');
        if ($name !== null) {
            $category->setName($name);
        }
        
        $description = $request->request->get('description');
        if ($description !== null) {
            $category->setDescription($description);
        }

        $entityManager->persist($category);
        $entityManager->flush();
   
        $data =  [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];
           
        return $this->json($data);
    }

    #[Route('/categories/{id}', name: 'category_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $category = $doctrine->getRepository(Category::class)->find($id);
   
        if (!$category) {
   
            return $this->json('No category found for id ' . $id, 404);
        }
   
        $data =  [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];
           
        return $this->json($data);
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
        if ($name !== null) {
            $category->setName($name);
        }

        $description = $request->request->get('description');
        if ($description !== null) {
            $category->setDescription($description);
        }

        $entityManager->flush();
   
        $data =  [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/categories/{id}', name: 'category_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
   
        if (!$category) {
            return $this->json('No Category found for id' . $id, 404);
        }
   
        $entityManager->remove($category);
        $entityManager->flush();
   
        return $this->json('Deleted a Category successfully with id ' . $id);
    }
}
