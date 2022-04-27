<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/pages', name: 'app_pages')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    #[Route('/historique', name: 'app_pages_historique')]
    public function historique(): Response
    {
        return $this->render('pages/historique.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    #[Route('/a-propos', name: 'app_pages_apropos')]
    public function aPropos(): Response
    {
        return $this->render('pages/apropos.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    #[Route('/contact', name: 'app_pages_contact')]
    public function contact(Request $request, MailerInterface $mailerInterface): Response
    {
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Envoie de mail
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to('vous@domain.com')
                ->subject('Contact depuis le site (Nom du mon site)')
                ->htmlTemplate('emails/_contact.html.twig')
                ->context([
                    'useremail'  =>  $contact->get('email')->getData(),
                    'sujet' =>  $contact->get('sujet')->getData(),
                    'content'   =>  $contact->get('content')->getData()
                ])
            ;

            $mailerInterface->send($email);

            $this->addFlash('success', 'Mail de contact envoyer');
            return $this->redirectToRoute('app_pages_contact');
        }

        return $this->renderForm('pages/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
