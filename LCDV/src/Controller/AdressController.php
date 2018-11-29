<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Adress;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AdressTypeNew;
use App\Repository\UserRepository;
use App\Repository\AdressRepository;
use Symfony\Component\HttpFoundation\Request;


class AdressController extends AbstractController
{
    /**
     * @Route("new/adress", name="new_adress")
     */
    public function newAdress(UserRepository $userRepository, Request $request, EntityManagerInterface $em)
    {
        $adress = new Adress();
        $user = $userRepository->find($_GET['user']);

        $form = $this->createForm(AdressTypeNew::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adress->setUser($user);
            $em->persist($adress);
            $em->flush();
            $this->addFlash('success', 'Adresse enregistrée!');
            return $this->render('profil/user.html.twig', ["user" =>$user]);
        }

        return $this->render('user/inscription_user_complete.html.twig', [

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete/adress/{id}", name="delete_adress")
     */
    public function deleteAdress($id,UserRepository $userRepository,AdressRepository $adressRepository, Request $request, EntityManagerInterface $em)
    {
        $adress = $adressRepository->find($id);

        $em->remove($adress);
        $em->flush();
        $this->addFlash('success', 'Adresse supprimée!');
        return $this->render('profil/user.html.twig', ["user" =>$userRepository->find($_GET['user'])]);
    }
}
