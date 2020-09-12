<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class GlobalController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ContactNotification $notification, MailerInterface $mailer) 
    {
        //  = new Contact();
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new TemplatedEmail())
                    ->from($contact->get('email')->getData())
                    ->to("syl.pillet@hotmail.fr")
                    ->subject("New Mail from Website")
                    // ->htmlTemplate("global/index.html.twig")
                    ->text($contact->get('message')->getData())
                    ->context([
                        "form" => $form->createView()
                    ]);

                    $mailer->send($email);

            // $notification->notify($contact);

            $this->addFlash('success', "Your email has been send !!");
            return $this->redirectToRoute("home");
        }

        return $this->render('global/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
