<?php

namespace App\Controller\Admin;

use App\Form\EditeProfilFormType;
use App\Form\ChangePasswordFormType;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/espace')]
class AdminEspaceController extends AbstractController
{
    #[Route('/profil-administrateur', name: 'app_admin_profil')]
    public function profil(ProjetRepository $projetRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $projets = $paginator->paginate(
            $user->getProjets(),
            $request->query->getInt('page', 1),
            5
        );

        $users = $paginator->paginate(
            $user->getUsers(),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('admin/admin_espace/profil.html.twig', [
            'projets'   =>  $projets,
            'users'     =>  $users
        ]);
    }

    #[Route('/edition-profil-administrateur', name: 'app_admin_edit_profile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditeProfilFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->add($user);

            $this->addFlash('success', 'Votre profile a bien été mise à jour');

            return $this->redirectToRoute('app_admin_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_espace/editionProfil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/edition-mot-de-passe-administrateur', name: 'app_admin_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $userRepository->add($user);
            //$this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            //$this->cleanSessionAfterReset();

            $this->addFlash('success', 'Votre mot de passe a bien été mise à jour avec succès');

            return $this->redirectToRoute('app_admin_profil');
        }

        return $this->render('admin/admin_espace/changePassword.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
