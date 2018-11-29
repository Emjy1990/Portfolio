<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PaymentModeRepository;
use App\Repository\CommandRepository;
use App\Entity\PaymentMode;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/admin")
 */
class PaymentModeController extends Controller
{
    /* DB Request.
    *  Return data.
    */

    /**
     * @Route("/list/paymentmode", name="list_paymentmode")
     */
    public function listPaymentMode(PaymentModeRepository $paymentModeRepository)
    {

        /***************** request for all paymentMode ***********************/
        $paymentModes = $paymentModeRepository->findAll();

        /******************convert object to json *********************/
        $paymentModeJson = []; // json_encode on this array
        array_push($paymentModeJson,  ["id" => "1"]); //message code ok
        foreach ($paymentModes as $paymentMode){ //we're looking for pertinent data
            array_push($paymentModeJson,["name" => $paymentMode->getName()]);
        }
        json_encode($paymentModeJson); //encoding

        /*********** send JsonResponse ************************/
        return $this->reponse($paymentModeJson);
    }

    /* Request Ajax from React with data (name).
    *  DB Save.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/create/paymentmode", name="create_paymentmode")
     */
    public function createPaymentMode(EntityManagerInterface $em, PaymentModeRepository $paymentModeRepository)
    {
        ///create/paymentmode?name=testPaiment

        /***********  parameters test ************************/
        if ($_GET['name'] == null) {
            $this->message(5);
        }
        $allPaymentMode = $paymentModeRepository->findAll();
        foreach ($allPaymentMode as $payment) {
            if ($payment->getName() == $_GET['name']){
                return $this->message(0);
            }
        }

        /************* save in SF object **********************/
        $paymentMode = new PaymentMode();
        $paymentMode->setName($_GET['name']);

        /******* create in DB **************/
        $em->persist($paymentMode);
        if($this->tryCatch($em) != null) {
            return $this->message(3);
        };

        /*********** send JsonResponse ************************/
        return $this->message(6);
    }

    /* Request Ajax from React with paymentmode id.
    *  DB Update.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/update/paymentmode/{id}", name="update_paymentmode")
     */
    public function updatePaymentMode(PaymentModeRepository $paymentModeRepository,EntityManagerInterface $em,$id)
    {
        ///update/paymentmode/1?name=testupdate

        /******** not use parameter converter cause its possible to have inexistent id ***********/
        $paymentMode = $paymentModeRepository->find($id);

        if ($paymentMode == null) {
            return $this->message(1);
        }

        /**************testing parameters ************************/
        if ($paymentMode->getName() == $_GET['name']) {
            return $this->message(4);
        }

        /**************save in SF object ****************************/
        if ($_GET['name'] != null) {
            $paymentMode->setName($_GET['name']);
        }

        /******* update in DB **************/
        $em->persist($paymentMode);
        if($this->tryCatch($em) != null) {
            return $this->message(3);
        };

        /*********** send JsonResponse ************************/
        return $this->message(7);
    }

    /* Request Ajax from React with paymentmode id.
    *  DB Update.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/delete/paymentmode/{id}", name="delete_paymentmode")
     */
    public function deletePaymentMode(PaymentModeRepository $paymentModeRepository,EntityManagerInterface $em, CommandRepository $commandRepository, $id)
    {

        /******** not use parameter converter cause its possible to have inexistent id ***********/
        $paymentMode = $paymentModeRepository->find($id);

        if ($paymentMode == null) {
            return $this->message(1);
        }

        /************** verify non-using paymentMode ********************/
        $command = $commandRepository->findBy(['paymentMode'=>$id]);

        if($command != null) {
            $this->message(2);
        }

        /******* delete in DB **************/
        $em->remove($paymentMode);
        if($this->tryCatch($em) != null) {
            return $this->message(4);
        };

        /*********** send JsonResponse ************************/
        return $this->message(8);
    }
}
