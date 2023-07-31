<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;
use App\Services\Slugger;
use App\Services\RegisterImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTimeImmutable;

#[Route('/admin')]
class ArticleController extends AbstractController
{
    #[Route('/article/nouveau.html', name: 'app_article_new')]
    public function new(Request $request, ArticleRepository $articleRepository, RegisterImage $registerImage, Slugger $slugger): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
                    ->setSlug($slugger->slugify($form->get('title')->getData()))
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setUpdatedAt(new DateTimeImmutable());

            $articleRepository->save($article, true);

            $this->addFlash('success', 'Article ajouté avec succès !');
            return $this->redirectToRoute('app_article_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/article/mes-articles.html', name: 'app_article_index', methods:['GET'])]
    public function index(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy(['user' => $this->getUser()]);

        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/{slug}.html', name: 'app_article_show', methods:['GET'])]
    public function show(Article $article, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->findOneBy(['user' => $this->getUser(), 'slug' => $article->getSlug()]);

        return $this->render('admin/article/show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/article/editer/{slug}.html', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository, RegisterImage $registerImage, Filesystem $filesystem, Slugger $slugger): Response
    {
        $checkArticle = $articleRepository->findByAuthor($this->getUser(), $article);
        
        if (!$checkArticle)
        {
            $this->addFlash('error', 'Vous ne pouvez pas modifier l\'article d\'un autre utilisateur');
            return $this->redirectToRoute('app_article_index');
        }

        $image = $checkArticle->getImage();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setUser($this->getUser());
            $article->setSlug($slugger->slugify($form->get('title')->getData()));
            $article->setUpdatedAt(new DateTimeImmutable());

            if ($form->get('image')->getData() != null) {

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

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/article/supprimer/{slug}.html', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository, Filesystem $filesystem): Response|JsonResponse
    {

        $checkArticle = $articleRepository->findByAuthor($this->getUser(), $article);
        
        if (!$checkArticle)
        {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer l\'article d\'un autre utilisateur');
            return $this->redirectToRoute('app_article_index');
        }

        $articleId = $checkArticle->getId();

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {

            if($filesystem->exists('image_directory' . '/' . $article->getImage()))
            {
                $filesystem->remove('image_directory' . '/' . $article->getImage());
            }
            $articleRepository->remove($article, true);
        }

        if($request->isXmlHttpRequest())
        {
            return $this->json($articleId);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
