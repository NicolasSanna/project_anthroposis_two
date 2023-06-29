<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin')]
class ArticleController extends AbstractController
{
    #[Route('/article/nouveau.html', name: 'app_article_new')]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // @TODO Enregistrement d'un article.
        }

        return $this->render('article/index.html.twig', [
            'form' => $form,
        ]);
    }
}
