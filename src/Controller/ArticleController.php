<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Services\Slugger;
use App\Services\RegisterImage;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin')]
class ArticleController extends AbstractController
{
    #[Route('/article/nouveau.html', name: 'app_article_new')]
    public function index(Request $request, ArticleRepository $articleRepository, RegisterImage $registerImage, Slugger $slugger): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // @TODO Enregistrement d'un article.

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

        return $this->render('article/index.html.twig', [
            'form' => $form,
        ]);
    }
}
