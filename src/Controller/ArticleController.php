<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

#[Route('/articles')]
class ArticleController extends AbstractController
{
    #[Route('.html', name: 'app_articles_index', methods:['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' =>  $articles
        ]);
    }

    #[Route('/article/{slug}.html', name: 'app_articles_article_show', methods:['GET'])]
    public function show(Article $article, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->findOneBy(['slug' => $article->getSlug()]);

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }
}
