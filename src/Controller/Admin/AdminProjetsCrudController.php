<?php

namespace App\Controller\Admin;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use App\Entity\ProjetSearch;
use App\Form\ProjetSearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/projets')]
class AdminProjetsCrudController extends AbstractController
{
    private $sluger;

    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'app_admin_projets_crud_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository, PaginatorInterface $paginator, Request $request): Response
    {
        //$user = $this->getUser();

        $search = new ProjetSearch();
        $form = $this->createForm(ProjetSearchType::class, $search);
        $form->handleRequest($request);
        
        /*$projets = $paginator->paginate(
            //$projetRepository->findByDateDesc(),
            $user->getProjets(),
            $request->query->getInt('page', 1),
            100
        );*/

        $projets = $paginator->paginate(
            $projetRepository->findSearch($search),
            $request->query->getInt('page', 1),
            20
        );

        return $this->renderForm('admin/admin_projets_crud/index.html.twig', [
            'projets' => $projets,
            'form'  =>  $form,
        ]);
    }

    #[Route('/new', name: 'app_admin_projets_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjetRepository $projetRepository): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $projet->setSlug($this->sluger->slug($projet->getName()));
            $projet->setUser($this->getUser());

            $projetRepository->add($projet);
            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès');
            return $this->redirectToRoute('app_admin_projets_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_projets_crud/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_projets_crud_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('admin/admin_projets_crud/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_projets_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, ProjetRepository $projetRepository): Response
    {
        // Seul l'auteur du projet poura le modifier
        $this->denyAccessUnlessGranted('projet_edit', $projet);

        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $projet->setSlug($this->sluger->slug($projet->getName()));
            $projetRepository->add($projet);
            $this->addFlash('success', 'Le contenu a bien été modifié avec succès');
            return $this->redirectToRoute('app_admin_projets_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_projets_crud/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_projets_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, ProjetRepository $projetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $projetRepository->remove($projet);
        }
        $this->addFlash('success', 'Le contenu a bien été supprimer avec succès');

        return $this->redirectToRoute('app_admin_projets_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
