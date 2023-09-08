<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;

#[Route(path: '/articles')]
class ArticleController extends AbstractController
{
    #[Route(path: '', name: 'app_articles_index', methods:['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['isVerified' => true]);

        return $this->render('article/index.html.twig', [
            'articles' =>  $articles
        ]);
    }

    #[Route(path: '/article/{slug}', name: 'app_articles_article_show', methods:['GET', 'POST'])]
    public function show(Article $article, ArticleRepository $articleRepository, CommentRepository $commentRepository, Request $req): Response
    {
        $article = $articleRepository->findOneBy(['slug' => $article->getSlug()]);

        $commentForm = $this->createForm(CommentType::class);

        $commentForm->handleRequest($req);

        if($commentForm->isSubmitted() && $commentForm->isValid())    
        {
            $comment = new Comment();

            $comment->setContent($commentForm->get('content')->getData())
                    ->setArticle($article)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setUser($this->getUser());

            $commentRepository->save($comment, true);
            $this->addFlash('success', 'Votre commentaire a bien été ajouté');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm->createView()
        ]);
    }
}
