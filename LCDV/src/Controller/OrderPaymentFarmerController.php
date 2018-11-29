<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\OrderPaymentFarmerRepository;
use App\Entity\OrderPaymentFarmer;
use App\Entity\StatusOrderPaymentFarmer;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\StatusOrderPaymentFarmerRepository;
use App\Repository\BankingCoordinateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OrderPaymentFarmerType;
use Symfony\Component\HttpFoundation\Session\Session;


class OrderPaymentFarmerController extends Controller
{
    /* DB Request.
    *  Return data.
    */

    /**
     * @Route("admin/list/orderpaymentfarmer", name="list_orderPaymentFarmer")
     */
    public function listOrderPaymentFarmer(OrderPaymentFarmerRepository $orderPaymentFarmerRepository)
    {

        /***************** request for all ***********************/
        $orderPaymentFarmers = $orderPaymentFarmerRepository->findAll();

        /******************convert object to json *********************/
        $orderPaymentFarmerJson = []; // json_encode on this array
        array_push($orderPaymentFarmerJson,  ["id" => "1"]); //message code ok
        foreach ($orderPaymentFarmers as $orderPaymentFarmer){ //we're looking for pertinent data
            array_push($orderPaymentFarmerJson,["user" => $orderPaymentFarmer->getUser()->getUsername(),"status" => $orderPaymentFarmer->getStatus()->getName(),"bankingCoordinate" => $orderPaymentFarmer->getBankingCoordinate()->getName(),"date" => $orderPaymentFarmer->getDate()->format('Y-m-d'),"amount" => $orderPaymentFarmer->getAmount()]);
        }
        json_encode($orderPaymentFarmerJson); //encoding

        /*********** send JsonResponse ************************/
        return $this->reponse($orderPaymentFarmerJson);
    }

    /* Request Ajax from React with farmer id.
    *  DB Request.
    *  Return data or error if false.
    */

    /**
     * @Route("admin/list/orderpaymentfarmer/farmer/{id}", name="list_orderPaymentFarmer_by_farmer")
     */
    public function listOrderPaymentFarmerByFarmer(OrderPaymentFarmerRepository $orderPaymentFarmerRepository, $id)
    {
        /***************** request for all ***********************/
        $orderPaymentFarmers = $orderPaymentFarmerRepository->findBy(["user"=>$id]);

        /****************** testing parameters ***************************/
        if ($orderPaymentFarmers == null) {
            $this->message(2);
        }

        /******************convert object to json *********************/
        $orderPaymentFarmersJson = []; // json_encode on this array
        foreach ($orderPaymentFarmers as $orderPaymentFarmer){ //we're looking for pertinent data
            array_push($orderPaymentFarmersJson,["user" => $orderPaymentFarmer->getUser()->getUsername(),"status" => $orderPaymentFarmer->getStatus()->getName(),"bankingCoordinate" => $orderPaymentFarmer->getBankingCoordinate()->getName(),"date" => $orderPaymentFarmer->getDate()->format('Y-m-d'),"amount" => $orderPaymentFarmer->getAmount()]);
        }
        json_encode($orderPaymentFarmersJson); //encoding

        /*********** send JsonResponse ************************/
        return $this->reponse($orderPaymentFarmersJson);
    }



    /* Request Ajax from React with data (amount).
    *  Don't forget constraint.
    *  DB Save.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/create/orderpaymentfarmer", name="create_orderPaymentFarmer")
     */
    public function createOrderPaymentFarmer(Request $request,Session $session, StatusOrderPaymentFarmerRepository $statusOrderPaymentFarmerRepository, BankingCoordinateRepository $bankingCoordinateRepository, OrderPaymentFarmerRepository $orderPaymentFarmerRepository): Response
    {
        $farmer = $this->getUser();
        $status = $statusOrderPaymentFarmerRepository->findBy(["name" =>"Autorisation en attente"]);
        $bankingCoordinate = $bankingCoordinateRepository->findAll();
        $orderPaymentFarmers = $orderPaymentFarmerRepository->findBy(['user'=>$farmer->getId()]);
        // dump($orderPaymentFarmers);die();
        $statusFarmer = [];
        foreach($status as $row){
            array_push($statusFarmer, $row);
        }

        $money = $this->getUser()->getSolde();



        $orderPaymentFarmer = new OrderPaymentFarmer();

        $form = $this->createForm(OrderPaymentFarmerType::class, $orderPaymentFarmer, ['money' => $money]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $orderPaymentFarmer->setUser($farmer);
            foreach($status as $row){
            $orderPaymentFarmer->setStatus($statusFarmer[0]);
            $em->persist($orderPaymentFarmer);
            $em->flush();
            $this->addFlash('success', 'Votre demande de virement à bien été enregistré !');
            $amount = $orderPaymentFarmer->getAmount();
            $totalMo = ($money - $amount);
            $session->set('totalMo', $totalMo);

        }
            $money = $session->get('totalMo');

            return $this->redirectToRoute('recap_orderPaymentFarmer', ['id' => $farmer->getId(), 'orderPaymentFarmers' => $orderPaymentFarmers, 'money' => $money]);
        }

        return $this->render('orderPayment/create.html.twig', [
            'orderPaymentFarmer' => $orderPaymentFarmer,
            'form' => $form->createView(),
            'orderPaymentFarmers' => $orderPaymentFarmers,
        ]);
    }


    /**
     * @Route("/recap/orderpaymentfarmer", name="recap_orderPaymentFarmer")
     */
    public function recapOrderPaymentFarmer(Request $request,Session $session, StatusOrderPaymentFarmerRepository $statusOrderPaymentFarmerRepository, BankingCoordinateRepository $bankingCoordinateRepository, OrderPaymentFarmerRepository $orderPaymentFarmerRepository): Response
    {
        $farmer = $this->getUser();
        $status = $statusOrderPaymentFarmerRepository->findBy(["name" =>"Autorisation en attente"]);
        $bankingCoordinate = $bankingCoordinateRepository->findAll();
        $orderPaymentFarmers = $orderPaymentFarmerRepository->findBy(['user'=>$farmer->getId()]);
        // dump($orderPaymentFarmers);die();
        $statusFarmer = [];
        foreach($status as $row){
            array_push($statusFarmer, $row);
        }

        $money = $session->get('totalMo');



        $orderPaymentFarmer = new OrderPaymentFarmer();

        $form = $this->createForm(OrderPaymentFarmerType::class, $orderPaymentFarmer, ['money' => $money]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $orderPaymentFarmer->setUser($farmer);
            foreach($status as $row){
            $orderPaymentFarmer->setStatus($statusFarmer[0]);
            $em->persist($orderPaymentFarmer);
            $em->flush();
            $this->addFlash('success', 'Votre demande de virement à bien été enregistré !');
            $amount = $orderPaymentFarmer->getAmount();
            $totalMo = ($money - $amount);
            $session->set('totalMo', $totalMo);

        }


            return $this->redirectToRoute('recap_orderPaymentFarmer', ['id' => $farmer->getId(), 'orderPaymentFarmers' => $orderPaymentFarmers, 'money' => $money]);
        }

        return $this->render('orderPayment/recap.html.twig', [
            'orderPaymentFarmer' => $orderPaymentFarmer,
            'form' => $form->createView(),
            'orderPaymentFarmers' => $orderPaymentFarmers,
        ]);
    }
    /* Request Ajax from React with orderPaymentFarmer id.
    *  DB Request.
    *  Return data or error if false.
    */


    /**
     * @Route("/show/orderpaymentfarmer/{id}", name="show_orderPaymentFarmer")
     */
    public function showOrderPaymentFarmer(OrderPaymentFarmer $orderPaymentFarmer): Response
    {

        return $this->render('orderPayment/show.html.twig', ['orderPaymentFarmer' => $orderPaymentFarmer]);
    }

    /**
     * @Route("/list/orderpaymentfarmer/{id}", name="list_orderPaymentFarmer")
     */
    public function myListOrderPaymentFarmer(OrderPaymentFarmerRepository $orderPaymentFarmerRepository, OrderPaymentFarmer $orderPaymentFarmer): Response
    {
        $farmer = $this->getUser()->getId();
        // dump($farmer);die();
        $orderPaymentFarmers = $orderPaymentFarmerRepository->findBy(["user" => $farmer], ["id" => 'DESC']);

        return $this->render('orderPayment/list.html.twig', ['orderPaymentFarmer' => $orderPaymentFarmers]);
    }


    /* Request Ajax from React with orderPaymentFarmer id.
    *  DB Update.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/delete/orderpaymentfarmer/{id}", name="delete_orderPaymentFarmer", methods="DELETE")
     */
    public function deleteOrderPaymentFarmer(Request $request, OrderPaymentFarmer $orderPaymentFarmer) : Response
    {
        $farmer = $this->getUser()->getId();
        /******** not use parameter converter cause its possible to have inexistent id ***********/
        if ($this->isCsrfTokenValid('delete'.$orderPaymentFarmer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($orderPaymentFarmer);
            $em->flush();
            $this->addFlash('success', 'Votre ordre de paiement a bien été supprimé');
        }

        return $this->redirectToRoute('create_orderPaymentFarmer', ['id' => $farmer]);
    }

}

    /* They aren't update for avoid abuses.*/
