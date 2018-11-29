<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;


final class MainController extends Controller
{
    /**
    * @Route("/", name="home")
    */
    public function acceuil(Session $session, ProductRepository $productRepository): Response
    {
        $session->set('price', null);
        return $this->render('main/home.html.twig');
    }

    //a verifier mais cette route est normalement gérer par Symfony


    /* Request Ajax from React with data (title,body,mail).
    *  Send Email.
    *  Return 1 if true or error if false.
    */

    /**
    * @Route("/contact", name="contact")
    */
    public function contact(): Response
    {

        return $this->render('main/contact.html.twig');
    }

    /**
    * @Route("/contact/mail", name="send_mail")
    */
    public function SendMail(\Swift_Mailer $mailer): Response
    {

        $message = (new \Swift_Message($_GET['objet']))
      ->setFrom($_GET['email'])
      ->setTo('lcdvtest2018@gmail.com')
      ->setBody(
          $this->renderView(
              // templates/emails/registration.html.twig
              'emails/mailUnknow.html.twig',
              array('body' => $_GET['body'])
          ),
          'text/html'
      )
      /*
       * If you also want to include a plaintext version of the message
      ->addPart(
          $this->renderView(
              'emails/registration.txt.twig',
              array('name' => $name)
          ),
          'text/plain'
      )
      */
  ;

        $mailer->send($message);
        $this->addFlash('success', 'Votre message a bien été envoyé!');
        return $this->redirectToRoute('contact');
    }

    /* DB Request.
    *  Return data.
    */

    /**
    * @Route("/cgu", name="CGU")
    */
    public function cgu(): Response
    {
        return $this->reponse('cgu');
    }

    //idem que cgu

    /**
    * @Route("/mention-legale", name="mentions_legales")
    */
    public function mentionsLegale(): Response
    {
        return $this->reponse('Mentions légales');
    }
}
