<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends Controller
{
    /**
    * @Route("/login", name="login")
    */
   public function login(Request $request, AuthenticationUtils $authenticationUtils) {
       // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();
       // last username entered by the user
       $lastUsername = $authenticationUtils->getLastUsername();
       
       $this->addFlash('notice','Bienvenue!');
       return $this->render('security/login.html.twig', array(
           'last_username' => $lastUsername,
           'error'         => $error,
       ));
   }

    /**
    * @route("/logout", name="logout")
    */
    public function logout()
    {

    }
}
