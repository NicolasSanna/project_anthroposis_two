<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Services\Slugger;

#[Route(path: '/admin')]
class CategoryController extends AbstractController
{
    #[Route(path: '/categories', name:'app_category_index', methods:['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['label' => 'ASC']);

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route(path: '/categorie/nouveau', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $req, CategoryRepository $categoryRepository, Slugger $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $category->setSlug($slugger->slugify($form->get('label')->getData()));
            $categoryRepository->save($category, true);

            $this->addFlash('success', sprintf('Catégorie %s créée avec succès !', $category->getlabel()));
            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/categorie/editer/{slug}', name:'app_category_edit', methods:['GET', 'POST'])]
    public function edit(CategoryRepository $categoryRepository, Category $category, Request $req, Slugger $slugger): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $category->getSlug()]);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $category->setSlug($slugger->slugify($form->get('label')->getData()));
            $categoryRepository->save($category, true);

            $this->addFlash('success', sprintf('Catégorie %s modifiée avec succès !', $category->getLabel()));
            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/categorie/supprimer/{id}', name:'app_category_delete', methods:['POST'])]
    public function delete(Category $category, Request $req, CategoryRepository $categoryRepository): Response|JsonResponse
    {
        $categoryId = $category->getId();

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $req->request->get('_token'))) 
        {
            $categoryRepository->remove($category, true);
        }

        if($req->isXmlHttpRequest())
        {
            return $this->json($categoryId);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}