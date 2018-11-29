<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\DeliveryModeRepository;
use App\Repository\CommandRepository;
use App\Entity\DeliveryMode;
use Doctrine\ORM\EntityManagerInterface;


class DeliveryModeController extends Controller
{
        /* DB Request.
        *  Return data.
        */
        /**
         * @Route("/list/deliverymode", name="list_deliverymode")
         */
        public function listDeliveryMode(DeliveryModeRepository $deliveryModeRepository)
        {
            /*****************request for all deliverymode ******************/
            $deliveryMode = $deliveryModeRepository->findAll();

            /******************convert object to json *********************/
            $deliveryModeJson = []; // json_encode on this array
            array_push($deliveryModeJson,  ["id" => "1"]); //message code ok
            foreach ($deliveryMode as $delivery){ //we're looking for pertinent data
                array_push($deliveryModeJson,["name" => $delivery->getName(), "delay" => $delivery->getDelay()]);
            }
            json_encode($deliveryModeJson); //encoding

            /*********** send JsonResponse ************************/
            return $this->reponse($deliveryModeJson);
        }

        /* Request Ajax from React with data (name, delay).
        *  DB Save.
        *  Return 1 if true or error if false.
        */

        /**
         * @Route("/create/deliverymode", name="create_deliverymode")
         */
        public function createDeliveryMode(EntityManagerInterface $em)
        {
            ///create/deliverymode?name=test&delay=0

            /************* testing parameters *****************/
            if ($_GET['name'] == null || $_GET['delay'] == null) {
                return $this->message(5);
            }

            /************* save in SF object **********************/
            $deliveryMode = new DeliveryMode();
            $deliveryMode->setName($_GET['name'])->setDelay($_GET['delay']);

            /******* create in DB **************/
            $em->persist($deliveryMode);
            if($this->tryCatch($em) != null) {
                return $this->message(3);
            };

            /*********** send JsonResponse ************************/
            return $this->message(6);
        }

        /* Request Ajax from React with deliverymode id.
        *  DB Update.
        *  Return 1 if true or error if false.
        */

        /**
         * @Route("/update/deliverymode/{id}", name="update_deliverymode")
         */
        public function updateDeliveryMode(DeliveryModeRepository $deliveryModeRepository, EntityManagerInterface $em,$id)
        {
            ///update/deliverymode/1?name=plop&delay=0

            /******** not use parameter converter cause its possible to have inexistent id ***********/
            $deliveryMode = $deliveryModeRepository->find($id);

            if ($deliveryMode == null) {
                return $this->message(1);
            }

            /**************testing parameters ************************/
            if ($deliveryMode->getName() == $_GET['name'] && $deliveryMode->getDelay() == $_GET['delay']) {
                return $this->message(4);
            }

            /**************save in SF object ****************************/
            if ($_GET['name'] != null) {
                $deliveryMode->setName($_GET['name']);
            }
            if ($_GET['delay'] != null) {
                $deliveryMode->setDelay($_GET['delay']);
            }

            /******* update in DB **************/
            $em->persist($deliveryMode);
            if($this->tryCatch($em) != null) {
                return $this->message(7);
            };

            /*********** send JsonResponse ************************/
            return $this->message(7);
        }

        /* Request Ajax from React with deliverymode id.
        *  DB Update.
        *  Return 1 if true or error if false.
        */

        /**
         * @Route("/delete/deliverymode/{id}", name="delete_deliverymode")
         */
        public function deleteDeliveryMode(DeliveryModeRepository $deliveryModeRepository, EntityManagerInterface $em, CommandRepository $commandRepository, $id)
        {

            /******** not use parameter converter cause its possible to have inexistent id ***********/
            $deliveryMode = $deliveryModeRepository->find($id);

            if ($deliveryMode == null) {
                return $this->message(1);
            }
            /********* test use in other entities ********************/
            $command = $commandRepository->findBy(['deliveryMode'=>$id]);
            if($command != null) {
                return $this->message(2);
            }
            /******* delete in DB **************/
            $em->remove($deliveryMode);
            if($this->tryCatch($em) != null) {
                return $this->message(4);
            };

            /*********** send JsonResponse ************************/
            return $this->message(8);
        }
}
