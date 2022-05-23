<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/posts')]
class AdminPostsController extends AbstractController
{
    private $sluger;

    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'app_admin_posts_index', methods: ['GET'])]
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $posts = $paginator->paginate(
            $postRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/new', name: 'app_admin_posts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $projet->setSlug($this->sluger->slug($projet->getName()));
            $projet->setUser($this->getUser());

            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès');

            $postRepository->add($post);
            return $this->redirectToRoute('app_admin_posts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_posts/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_posts_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('admin/admin_posts/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_posts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post);

            $this->addFlash('success', 'Le contenu a bien été modifié avec succès');
            return $this->redirectToRoute('app_admin_posts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_posts/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_posts_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post);
        }

        return $this->redirectToRoute('app_admin_posts_index', [], Response::HTTP_SEE_OTHER);
    }
}
