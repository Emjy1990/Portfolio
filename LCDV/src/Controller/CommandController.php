<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PaymentModeRepository;
use App\Repository\UserRepository;
use App\Repository\AdressRepository;
use App\Repository\DeliveryModeRepository;
use App\Repository\CommandRepository;
use App\Repository\StatusorderRepository;
use App\Repository\ProductRepository;
use App\Repository\RoleRepository;
use App\Entity\Product;
use App\Entity\Command;
use App\Entity\Adress;
use App\Entity\DeliveryMode;
use App\Entity\Statusorder;
use App\Entity\User;
use App\Entity\Role;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommandTypeNew;
use Symfony\Component\HttpFoundation\Response;

class CommandController extends Controller
{

        /**
        * @Route("/list/command/farmer/{id}", name="list_command_by_farmer")
        */
        public function listCommandByFarmer(CommandRepository $commandRepository, $id, RoleRepository $roleRepository, StatusOrderRepository $statusOrderRepository)
        {
                $commands = $commandRepository->findBy(["farmer"=>$id]);
                $status = $statusOrderRepository->findAll();
                return $this->render('list/commandByFarmer.html.twig', ['commands' => $commands, "statusies" => $status]);
        }

        /**
        * @Route("/list/command/user/{id}", name="list_command_by_user")
        */
        public function listCommandByUser(CommandRepository $commandRepository, $id, RoleRepository $roleRepository, StatusOrderRepository $statusOrderRepository)
        {
                $commands = $commandRepository->findBy(["user"=>$id]);
                $status = $statusOrderRepository->findAll();
                return $this->render('list/commandByUser.html.twig', ['commands' => $commands, "statusies" => $status]);
        }


        /**
        * @Route("/accept/command/farmer/{id}", name="accept_command_by_farmer")
        */
        public function acceptCommandByFarmer(CommandRepository $commandRepository, $id, StatusOrderRepository $statusOrderRepository, EntityManagerInterface $em)
        {

                $status = $statusOrderRepository->findBy(['name'=>'Accepter']);
                $command = $commandRepository->find($_GET['id']);
                $command->setStatus($status[0]);
                $em->persist($command);
                $em->flush();
                $this->addFlash('success', 'Commande acceptée!');
                $commands = $commandRepository->findBy(["farmer"=>$id]);
                return $this->render('list/commandByFarmer.html.twig', ['commands' => $commands, "statusies" => $status]);
        }

        /**
        * @Route("/reject/command/farmer/{id}", name="reject_command_by_farmer")
        */
        public function rejectCommandByFarmer(CommandRepository $commandRepository, $id, StatusOrderRepository $statusOrderRepository, EntityManagerInterface $em)
        {

                $status = $statusOrderRepository->findBy(['name'=>'Autorisation refusée']);
                $command = $commandRepository->find($_GET['id']);
                $command->setStatus($status[0]);
                $em->persist($command);
                $em->flush();
                $this->addFlash('success', 'Commande refusée!');
                $commands = $commandRepository->findBy(["farmer"=>$id]);
                return $this->render('list/commandByFarmer.html.twig', ['commands' => $commands, "statusies" => $status]);
        }


        /**
        * @Route("/recap/command", name="recap_command")
        */
        public function recapCommand(Request $request, CommandRepository $commandRepository, ProductRepository $productRepository, Session $session,PaymentModeRepository $paymentModeRepository,DeliveryModeRepository $deliveryModeRepository, AdressRepository $adressRepository, StatusorderRepository $statusorderRepository, UserRepository $userRepository): Response
        {
            if(!$session->has('cart'))
        {
            $session->set('cart' ,[]);
        }
        /********** create var command for view *************/
        $products = $session->get('cart');

        $session->set('arrayProduct', $products);

        /*********** product in SF object *****************/
        $productSF = [];
        $idfarmer = [];
        $arrayPrice = [];
        foreach ($products as $key=>$value) {
            $prod = $productRepository->find($key);
            $prod->setQuantity($value);
            array_push($productSF,$prod);
            $id = $prod->getUser()->getId();
            $cpt = 0;
            for ($i=0;$i<sizeof($idfarmer);$i++) {
                if ($id == $idfarmer[$i]) {$cpt++;}
            }
            if ($cpt == 0) {array_push($idfarmer,$id);}
        }
        // il faut créer une commande par fermier et afficher sous total / total
        // oublier de gerer la livraison ou drive
        $commandtotal = [];
        //dump($idfarmer);die;
        foreach ($idfarmer as $key=>$value) {
            $price = 0;
            $order = new Command();
            foreach ($productSF as $product) {
                if ($product->getUser()->getId() == $value) {
                    $order->addProduct($product);
                    $price += $product->getPrice()* $products[$product->getId()];
                     array_push($arrayPrice, $price);
                    $totalPrice = array_sum($arrayPrice);
                    $session->set('price', $totalPrice);
                }
                $order->setPrice($price);
            }
            array_push($commandtotal,$order);
        }
        //dump($commandtotal);die;
        return $this->render('command/recap.html.twig', ["commands" => $commandtotal,"deliveries" => $deliveryModeRepository->findAll(), "adresses" => $adressRepository->findBy(['user'=>$this->getUser()]), "paiments" => $paymentModeRepository->findAll()]);

                // $user = $this->getUser();
                //
                // $statusorder = $statusorderRepository->findBy(["name" =>"Autorisation en attente"]);
                // // dump($statusorder);die();
                //
                // if(!$session->has('cart'))
                // {
                //         $session->set('cart' ,[]);
                // }
                // /********** create var command for view *************/
                // $products = $session->get('cart');
                // // dump($products);die;
                //
                // /*********** product in SF object *****************/
                // $productSF = [];
                // $idfarmer = [];
                // $quantityArray = [];
                // foreach ($products as $key=>$value) {
                //         array_push($quantityArray, $value);
                //         $prod = $productRepository->find($key);
                //         $prod->setQuantity($value);
                //         array_push($productSF,$prod);
                //         $id = $prod->getUser()->getId();
                //         $cpt = 0;
                //         for ($i=0;$i<sizeof($idfarmer);$i++) {
                //                 if ($id == $idfarmer[$i]) {$cpt++;}
                //         }
                //         if ($cpt == 0) {array_push($idfarmer,$id);}
                // }
                // // dump($quantityArray);die();
                // // il faut créer une commande par fermier et afficher sous total / total
                // // oublier de gerer la livraison ou drive
                // $commandtotal = [];
                //
                // $arrayPrice = [];
                //
                //
                // // dump($productSF);die();
                // $farmer = $this->getUser()->getId();
                // $command = new Command();
                //
                //
                // foreach ($productSF as $product) {
                //         array_push($commandtotal, $product);
                //         $session = $this->get('session');
                //         $price = 0;
                //         $price = $product->getPrice()* $products[$product->getId()];
                //         array_push($arrayPrice, $price);
                //         $totalPrice = array_sum($arrayPrice);
                //         $session->set('price', $totalPrice);
                //         $session->set('product', $product);
                //
                // }
                //
                //
                // $price = $session->get('price');
                //
                // $product = $session->get('product');
                //
                // $form = $this->createForm(CommandTypeNew::class, $command, ['id' => $farmer]);
                //
                // $form->handleRequest($request);
                //
                //
                //         if ($form->isSubmitted() && $form->isValid()){
                //                 $em = $this->getDoctrine()->getManager();
                //                         $command->setUser($user)
                //                         ->setFarmer($user)
                //                         ->setStatus($statusorder[0])
                //                         ->setPrice($price);
                //                         // ->addProduct($product);
                //                 $em->persist($command);
                //                 // dump($command);die();
                //                 $em->flush();
                //
                //                 $session->set('cart', []);
                //                 return $this->redirectToRoute('home');
                //                 }
                //
                //
                //
                //
                //
                // $session->set('cart', []);
                // return $this->render('command/recap.html.twig', [
                //         "commands" => $commandtotal,
                //         'form' => $form->createView()]);
                }

                /**
                * @Route("/create/command", name="create_command")
                */
                public function createCommand(Request $request, Session $session, EntityManagerInterface $em, PaymentModeRepository $paymentModeRepository,
                UserRepository $userRepository, StatusorderRepository $statusorderRepository, DeliveryModeRepository $deliveryModeRepository, AdressRepository $adressRepository, ProductRepository $productRepository)
                {

                        $user = $this->getUser();
                        $paymentMode = $paymentModeRepository->find($_GET['paymentMode']);
                        $deliveryMode = $deliveryModeRepository->find($_GET['deliveryMode']);
                        $statusorder = $statusorderRepository->findBy(["name" =>"Autorisation en attente"]);
                        $paymentAdress = $adressRepository->find($_GET['paymentAdress']);
                        $adress = $adressRepository->find($_GET['deliveryAdress']);
                        $products = $session->get('cart');
                        //dump($product);die;

                        /*********** product in SF object *****************/
                        $productSF = [];
                        $idfarmer = [];
                        foreach ($products as $key=>$value) {
                                $prod = $productRepository->find($key);
                                $prod->setQuantity($value);
                                array_push($productSF,$prod);
                                $id = $prod->getUser()->getId();
                                $cpt = 0;
                                for ($i=0;$i<sizeof($idfarmer);$i++) {
                                        if ($id == $idfarmer[$i]) {$cpt++;}
                                }
                                if ($cpt == 0) {array_push($idfarmer,$id);}
                        }

                        // il faut créer une commande par fermier et afficher sous total / total
                        // oublier de gerer la livraison ou drive
                        $commandtotal = [];
                        //dump($idfarmer);die;

                        foreach ($idfarmer as $key=>$value) {
                                $price = 0;
                                $farmer = $userRepository->find($value);
                                $order = new Command();
                                $order->setFarmer($farmer)->setUser($user)->setPaymentMode($paymentMode)->setDeliveryMode($deliveryMode)->setDeliveryAdress($adress)->setStatus($statusorder[0])->setPaymentAdress($paymentAdress);
                                foreach ($productSF as $product) {
                                        if ($product->getUser()->getId() == $value) {
                                                $order->addProduct($product);
                                                $price += $product->getPrice()* $products[$product->getId()];

                                        }
                                        $order->setPrice($price);
                                        $em->persist($order);
                                        $em->flush();
                                }
                                array_push($commandtotal,$order);
                        }
                        $session->set('cart', []);
                        $session->set('basketCount', null);


                        return $this->render('command/create.html.twig',["commands" => $commandtotal,"deliveryMode"=>$deliveryMode, "paymentMode"=>$paymentMode, "deliveryAdress"=>$adress]);
                }
                /* Request Ajax from React with command id.
                *  DB Request.
                *  Return data or error if false.
                */

                /**
                * @Route("/show/command/{id}", name="show_command")
                */
                public function showCommand(Command $command, $id)
                {


                        $commandJson = []; // json_encode on this array

                        /**************** convert object to json *************************/

                        array_push($commandJson,["dateOpen" => $command->getDateOpen(), "dateAccepted" => $command->getDateAccepted(),"dateDelivery" => $command->getDateDelivery(),
                        "dateClosed" => $command->getDateClosed(),"price" => $command->getPrice(), "paymentMode" => $command->getPaymentMode()->getId(), "user" => $command->getUser()->getId(),
                        "deliveryMode" => $command->getDeliveryMode()->getId(), "statusorder" => $command->getStatus()->getId(), "Deliveryadress" => $command->getDeliveryAdress()->getId(),
                        "paymentAdress" => $command->getPaymentAdress()->getId(), "farmerId" => $command->getFarmer()->getId()]);

                        json_encode($commandJson); //encoding

                        return $this->reponse($commandJson);
                }

                /* Request Ajax from React with command id.
                *  DB Update.
                *  Return 1 if true or error if false.
                */

                /**
                * @Route("/update/command/{id}", name="update_command")
                */
                public function updateCommand(Command $command, EntityManagerInterface $em, PaymentModeRepository $paymentModeRepository, DeliveryModeRepository $deliveryModeRepository, StatusorderRepository $statusorderRepository,
                AdressRepository $adressRepository)
                {
                        $commandJson = [];
                        $paymentMode = $paymentModeRepository->find($_GET['paymentMode']);
                        $statusorder = $statusorderRepository->find($_GET['statusorder']);
                        $deliveryMode = $deliveryModeRepository->find($_GET['deliveryMode']);
                        $adress = $adressRepository->find($_GET['adress']);


                        if ($command->getPaymentMode() == $paymentMode && $command->getStatus() == $statusorder && $command->getDeliveryAdress() == $adress && $command->getDeliveryMode() == $deliveryMode && $command->getPrice() == $_GET['price'] ) {
                                array_push($commandJson,  ["id" => "0"],["message" => "Pas de changement"]); //message code
                                return $this->reponse($commandJson);
                        }

                        if ($_GET['paymentMode'] != null) {
                                $command->setPaymentMode($paymentMode);
                        }
                        if ($_GET['statusorder'] != null) {
                                $command->setStatus($statusorder);
                        }
                        if ($_GET['deliveryMode'] != null) {
                                $command->setDeliveryMode($deliveryMode);
                        }
                        if ($_GET['adress'] != null) {
                                $command->setDeliveryAdress($adress);
                        }
                        if ($_GET['price'] != null) {
                                $command->setPrice($_GET['price']);
                        }


                        /******* create in DB **************/
                        $em->persist($command);
                        if($this->tryCatch($em) != null) {
                                return $this->message(3);
                        };

                        /*********** send JsonResponse ************************/
                        return $this->message(7);
                }

                /* Request Ajax from React with command id.
                *  DB Update.
                *  Return 1 if true or error if false.
                */

                /**
                * @Route("/delete/command/{id}", name="delete_command")
                */
                public function deleteCommand(Command $command, EntityManagerInterface $em)
                {

                        /******* delete in DB **************/
                        $em->remove($command);
                        if($this->tryCatch($em) != null) {
                                return $this->message(3);
                        };

                        /*********** send JsonResponse ************************/
                        return $this->message(8); //message code

                        // return $this->reponse($commandJson);
                }

        }
