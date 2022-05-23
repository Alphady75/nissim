<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog')]
    public function blog(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $posts = $paginator->paginate(
            $postRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('blog/blog.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/{slug}', name: 'app_blog_detail')]
    public function postdetail(PostRepository $postRepository, $slug, PaginatorInterface $paginator, Request $request): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug]);

        if (!$post) {
            return $this->redirectToRoute('app_blog');
        }

        return $this->render('blog/post_detail.html.twig', [
            'post' => $post,
        ]);
    }
}
