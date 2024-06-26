<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use App\Services\Slugger;
use App\Services\RegisterImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route(path: '/admin')]
class ArticleController extends AbstractController
{    
    private Slugger $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    #[Route(path: '/article/nouveau', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $req, ArticleRepository $articleRepository, RegisterImage $registerImage): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Gestion de l'image si différent de null
            if($form->get('image')->getData() != null)
            {
                $registerImage->setForm($form);
                $fileName = $registerImage->saveImage();

                $article->setImage($fileName);
            }
            else
            {
                $article->setImage('');
            }
            
            $article->setUser($this->getUser())
                    ->setIsVerified(false)
                    ->setSlug($this->slugger->slugify($form->get('title')->getData()))
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setUpdatedAt(new DateTimeImmutable());

            $articleRepository->save($article, true);

            $this->addFlash('success', sprintf('Article %s ajouté avec succès !', $article->getTitle()));
            return $this->redirectToRoute('app_article_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/article/verifier-articles', name: 'app_articles_verify_index', methods: ['GET'])]
    public function verify_index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['isVerified' => false]);

        return $this->render('admin/article/verify_index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route(path: '/article/verifier-article/{slug}', name: 'app_article_verify_check', methods: ['GET'])]
    public function verifiy_check(Article $article, ArticleRepository $articleRepository): RedirectResponse
    {

        $article->setIsVerified(true);
        $articleRepository->save($article, true);

        return $this->redirectToRoute('app_articles_verify_index');
    }

    #[Route(path: '/article/mes-articles', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['user' => $this->getUser()]);

        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route(path: '/article/editer/{slug}', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $req, Article $article, ArticleRepository $articleRepository, RegisterImage $registerImage, Filesystem $filesystem, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($this->getUser());

        $checkArticle = $articleRepository->findByAuthor($user, $article);
        
        if (!$checkArticle)
        {
            $this->addFlash('error', 'Vous ne pouvez pas modifier l\'article d\'un autre utilisateur');
            return $this->redirectToRoute('app_article_index');
        }

        $image = $checkArticle->getImage();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) 
        {

            $article->setUser($this->getUser())
                    ->setIsVerified(false)
                    ->setSlug($this->slugger->slugify($form->get('title')->getData()))
                    ->setUpdatedAt(new DateTimeImmutable());

            // Gestion de l'image si différent de null
            if ($form->get('image')->getData() != null) 
            {

                $registerImage->setForm($form);
                $fileName = $registerImage->saveImage();

                if($filesystem->exists('image_directory' . '/' . $image))
                {
                    $filesystem->remove('image_directory' . '/' . $image);
                }
                
                $article->setImage($fileName);
            }
            else
            {
                if($image)
                {
                    $article->setImage($image);
                }
            }
           
            $articleRepository->save($article, true);
            
            $this->addFlash('success', sprintf('Article %s modifié avec succès !', $article->getTitle()));
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    // Méthode asynchrone de suppression d'un article.
    #[Route(path: '/article/supprimer/{slug}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $req, Article $article, ArticleRepository $articleRepository, Filesystem $filesystem, UserRepository $userRepository): Response|JsonResponse
    {
        $user = $userRepository->find($this->getUser());
        
        $checkArticle = $articleRepository->findByAuthor($user, $article);
        
        if (!$checkArticle)
        {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer l\'article d\'un autre utilisateur');
            return $this->redirectToRoute('app_article_index');
        }

        $articleId = $checkArticle->getId();

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $req->request->get('_token'))) 
        {

            if($filesystem->exists('image_directory' . '/' . $article->getImage()))
            {
                $filesystem->remove('image_directory' . '/' . $article->getImage());
            }
            $articleRepository->remove($article, true);
        }

        // On rnevoie au front le numéro d'identifiant correspondant à la ligne du tableau du front pour donner une impression de suppression directe.
        if($req->isXmlHttpRequest())
        {
            return $this->json($articleId);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(path: '/article/{slug}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBy(['user' => $this->getUser(), 'slug' => $article->getSlug()]);

        return $this->render('admin/article/show.html.twig', [
            'article' => $article
        ]);
    }
}
