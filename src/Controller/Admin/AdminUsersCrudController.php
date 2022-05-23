<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserEditFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users')]
class AdminUsersCrudController extends AbstractController
{
    #[Route('/', name: 'app_admin_users_crud_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $userRepository->findByNomAsc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_users_crud/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/nouvel-utilisateur', name: 'app_admin_users_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // User parent
            if($this->getUser()){
                $user->setUser($this->getUser());
            }

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    //$form->get('plainPassword')->getData()
                    '123456'
                )
            );

            $userRepository->add($user);

            $this->addFlash('success', 'Utilisateur enregistré avec succès');

            return $this->redirectToRoute('app_admin_users_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_users_crud/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_users_crud_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/admin_users_crud/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_users_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        // Seul l'administrateur auteur peut editer un utilisateur
        //$this->denyAccessUnlessGranted('user_edit', $user);
        
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);

            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('app_admin_users_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_users_crud/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_users_crud_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        // Seul l'administrateur auteur peut editer un utilisateur
        $this->denyAccessUnlessGranted('user_edit', $user);

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        $this->addFlash('success', 'Utilisateur supprimer avec succès');

        return $this->redirectToRoute('app_admin_users_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
