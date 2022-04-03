<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersSpaceController extends AbstractController
{
    #[Route('/users/space', name: 'app_users_space')]
    public function index(): Response
    {
        return $this->render('users_space/index.html.twig', [
            'controller_name' => 'UsersSpaceController',
        ]);
    }
}
