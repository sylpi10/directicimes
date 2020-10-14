<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class GlobalController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ContactNotification $notification, MailerInterface $mailer, TranslatorInterface $translator) 
    {
        //  = new Contact();
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new TemplatedEmail())
                    ->from($contact->get('email')->getData())
                    ->to("georgesyn@gmail.com")
                    // ->to("syl.pillet@hotmail.fr")
                    ->subject("Nouveau Message depuis DirectiCimes")
                    // ->htmlTemplate("global/index.html.twig")
                    ->text($contact->get('message')->getData())
                    ->context([
                        "form" => $form->createView()
                    ]);

                    $mailer->send($email);

            // $notification->notify($contact);
            $message = $translator->trans("Your email has been send");

            $this->addFlash('success', $message);
            return $this->redirectToRoute("home");
        }

        return $this->render('global/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //   /**
    //  * @Route("/register", name="register")
    //  */
    // public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) 
    // {
    //     $user = new User();

    //     $form = $this->createForm(RegisterType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $passWrdCrypt = $encoder->encodePassword($user, $user->getPassword());
    //         $user->setPassword($passWrdCrypt);
    //         $user->setRoles("ROLE_ADMIN");
    //        $manager->persist($user);
    //        $manager->flush();
    //     }

    //     return $this->render('global/register.html.twig', [
    //         'user' => $user,
    //         'form' => $form->createView()
    //     ]);
    // }

     /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $util)
    {

        return $this->render('global/login.html.twig', [
            "lastUserName" => $util->getLastUsername(),
            "error" => $util->getLastAuthenticationError()
        ]);
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
      
    }

    /**
     * @Route("/change-locale/{locale}", name="change-locale")
     */
    public function changeLocale($locale, Request $request)
    {
        //stock lang in session
        $request->getSession()->set('_locale', $locale);

        //back to previous page
        return $this->redirect($request->headers->get('referer'));
        
    }
}
