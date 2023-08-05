<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_home', methods:['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([], ['created_at' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route(path: '/rechercher', name: 'app_search', methods:['GET', 'POST'])]
    public function search(ArticleRepository $articleRepository, Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $results = [];

        if ($form->isSubmitted() && $form->isValid())
        {
            $criteria = $form->get('search')->getData();
            $results = $articleRepository->searchEngine($criteria);
        }

        return $this->render('home/search.html.twig', [
            'articles' => $results,
            'form' => $form->createView()
        ]);
    }
}
