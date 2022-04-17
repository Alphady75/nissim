<?php

namespace App\Controller\Admin;

use App\Entity\Financement;
use App\Form\FinancementType;
use App\Repository\FinancementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/financement')]
class AdminFinancementCrudController extends AbstractController
{
    #[Route('/', name: 'app_admin_financement_crud_index', methods: ['GET'])]
    public function index(FinancementRepository $financementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $financements = $paginator->paginate(
            $financementRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_financement_crud/index.html.twig', [
            'financements' => $financements,
        ]);
    }

    #[Route('/new', name: 'app_admin_financement_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FinancementRepository $financementRepository): Response
    {
        $financement = new Financement();
        $form = $this->createForm(FinancementType::class, $financement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $financementRepository->add($financement);
            return $this->redirectToRoute('app_admin_financement_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_financement_crud/new.html.twig', [
            'financement' => $financement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_financement_crud_show', methods: ['GET'])]
    public function show(Financement $financement): Response
    {
        return $this->render('admin/admin_financement_crud/show.html.twig', [
            'financement' => $financement,
            'projet'    =>  $financement->getProjet(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_financement_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Financement $financement, FinancementRepository $financementRepository): Response
    {
        $form = $this->createForm(FinancementType::class, $financement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $financementRepository->add($financement);
            return $this->redirectToRoute('app_admin_financement_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_financement_crud/edit.html.twig', [
            'financement' => $financement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_financement_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Financement $financement, FinancementRepository $financementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$financement->getId(), $request->request->get('_token'))) {
            $financementRepository->remove($financement);
        }

        return $this->redirectToRoute('app_admin_financement_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
