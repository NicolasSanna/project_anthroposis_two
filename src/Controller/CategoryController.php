<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Repository\CategoryRepository;

#[Route(path: '/categories')]
class CategoryController extends AbstractController
{
    #[Route(path: '', name: 'app_categories_index', methods:['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' =>  $categories
        ]);
    }

    #[Route(path: '/categorie/{slug}', name: 'app_categories_category_show', methods:['GET'])]
    public function show(Category $category, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $category->getSlug()]);

        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }
}