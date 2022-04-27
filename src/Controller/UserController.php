<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditeProfilFormType;
use App\Repository\ProjetRepository;
use App\Repository\FinancementRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/projets', name: 'app_user_projet', methods: ['GET'])]
    public function userProjet(): Response
    {
        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/espace-utilisateur', name: 'app_userspace', methods: ['GET'])]
    public function profile(FinancementRepository $financementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $financements = $paginator->paginate(
            $financementRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('user/userspace.html.twig', [
            'user'  =>  $user,
            'financements' => $financements,
        ]);
    }

    #[Route('/edition-profile', name: 'app_user_edit_profile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditeProfilFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->add($user);

            $this->addFlash('success', 'Votre profile a bien été mis à jour');

            return $this->redirectToRoute('app_userspace', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/editProfile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
