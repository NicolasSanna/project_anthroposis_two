<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;

#[Route('/admin')]
class CommentController extends AbstractController
{
    #[Route(path: '/commentaires', name: 'app_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(['isVerified' => false]);

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route(path: '/commentaires/{id}', name: 'app_comment_check', methods: ['GET'])]
    public function check(Comment $comment, CommentRepository $commentRepository): RedirectResponse
    {
        $comment->setIsVerified(true);
        $commentRepository->save($comment, true);

        return $this->redirectToRoute('app_comment_index');
    }
}
