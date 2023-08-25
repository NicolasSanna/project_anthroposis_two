<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

#[Route(path: '/admin')]
class DashboardController extends AbstractController
{


    #[Route(path: '/tableau-de-bord', name: 'app_dashboard', methods:['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->find($this->getUser());

        $articles = $user->getArticles();
        $createdAt = $user->getCreatedAt();
        $countArticles = count($articles);

        return $this->render('admin/dashboard/index.html.twig', [
            'countArticles' => $countArticles,
            'createdAtFr' => $createdAt->format('d/m/Y à H:i')
        ]);
    }

    #[Route(path: '/informations-personnelles', name:'app_dashboard_personal_informations', methods:['GET', 'POST'])]
    public function edit_personal_informations(UserRepository $userRepository, Request $req): Response
    {
        $user = $userRepository->find($this->getUser());

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setFirstname($form->get('firstname')->getData());
            $user->setLastname($form->get('lastname')->getData());
            $user->setPseudo($form->get('pseudo')->getData());
            $user->setEmail($form->get('email')->getData());

            $userRepository->save($user, true);
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
