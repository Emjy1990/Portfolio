<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BankingCoordinate;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BankingCoordinateTypeNew;
use App\Repository\UserRepository;
use App\Repository\BankingCoordinateRepository;
use Symfony\Component\HttpFoundation\Request;

class BankingCoordinateController extends AbstractController
{
    /**
     * @Route("new/banking/coordinate", name="new_banking_coordinate")
     */
    public function newBankingCoordinate(EntityManagerInterface $em,UserRepository $userRepository, Request $request )
    {
        $bc = new BankingCoordinate();
        $user = $userRepository->find($_GET['user']);

        $form = $this->createForm(BankingCoordinateTypeNew::class, $bc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bc->setUser($user);
            $em->persist($bc);
            $em->flush();
            $this->addFlash('success', 'Coordonée bancaire enregistré!');
            return $this->render('profil/user.html.twig', ["user" =>$user]);
        }

        return $this->render('bankingCoordinate/newBankingCoordinate.html.twig', [

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete/banking/coordinate/{id}", name="delete_banking_coordinate")
     */
    public function deleteBankingCoordinate($id,UserRepository $userRepository, BankingCoordinateRepository $bankingCoordinateRepository, Request $request, EntityManagerInterface $em)
    {
        $bc = $bankingCoordinateRepository->find($id);

        $em->remove($bc);
        $em->flush();
        $this->addFlash('success', 'Coordonée bancaire supprimé!');
        return $this->render('profil/user.html.twig', ["user" =>$userRepository->find($_GET['user'])]);
    }
}
