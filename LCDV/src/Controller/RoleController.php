<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/admin")
 */
class RoleController extends Controller
{
    /* DB Request.
    *  Return data.
    */

    /**
     * @Route("/list/role", name="list_role")
     */
    public function listRole(RoleRepository $roleRepository)
    {
        /***************** request for all users ***********************/
        $roles = $roleRepository->findAll();

        /******************convert object to json *********************/
        $rolesJson = []; // json_encode on this array
        array_push($rolesJson,  ["id" => "1"]); //message code ok
        foreach ($roles as $role){ //we're looking for pertinent data
            array_push($rolesJson,["name" => $role->getName()]);
        }
        json_encode($rolesJson); //encoding

        /*********** send JsonResponse ************************/
        return $this->reponse($rolesJson);
    }

    /* Request Ajax from React with data (name).
    *  DB Save.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/create/role", name="create_role")
     */
    public function createRole(EntityManagerInterface $em, RoleRepository $roleRepository)
    {
        ///create/role?name=plop&code=ROLE_PLOP

        /************* testing parameters *****************/
        if ($_GET['name'] == null || $_GET['code'] == null) {
            return $this->message(5);
        }
        $rolesExist = $roleRepository->findAll();
        foreach ($rolesExist as $roleExist) {
            if ($roleExist->getCode() == $_GET['code'])
            return $this->message(0);
        }

        /************** save in SF object********************/
        $role = new Role();
        $role->setName($_GET['name'])->setCode($_GET['code']);

        /******* create in DB **************/
        $em->persist($role);
        if($this->tryCatch($em) != null) {
            return $this->message(3);
        };

        /*********** send JsonResponse ************************/
        return $this->message(6);
    }

    /* Request Ajax from React with rol?e id.
    *  DB Update.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/update/role/{id}", name="update_role")
     */
    public function updateRole(RoleRepository $roleRepository, EntityManagerInterface $em, $id)
    {
        ///update/role/7?name=plop&code=ROLE_PLOP

        /******** not use parameter converter cause its possible to have inexistent id ***********/
        $role = $roleRepository->find($id);

        if ($role == null) {
            return $this->message(1);
        }

        /**************testing parameters ************************/
        if ($role->getName() == $_GET['name'] && $role->getCode() == $_GET['code']) {
            return $this->message(4);
        }

        /**************save in SF object ****************************/
        if ($_GET['name'] != null) {
            $role->setName($_GET['name']);
        }
        if ($_GET['code'] != null) {
            $role->setCode($_GET['code']);
        }

        /******* update in DB **************/
        $em->persist($role);
        if($this->tryCatch($em) != null) {
            return $this->message(7);
        };

        /*********** send JsonResponse ************************/
        return $this->message(7);
    }

    /* Request Ajax from React with role id.
    *  DB Update.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/delete/role/{id}", name="delete_role")
     */
    public function deleteRole(RoleRepository $roleRepository,EntityManagerInterface $em, UserRepository $userRepository, $id)
    {
        /******** not use parameter converter cause its possible to have inexistent id ***********/
        $role = $roleRepository->find($id);

        if ($role == null) {
            return $this->message(1);
        }

        /************** test use in other entities ********************/
        $user = $userRepository->findBy(['role'=>$id]);
        if($user != null) {
            return $this->message(2);
        }

        /******* delete user in DB **************/
        $em->remove($role);
        if($this->tryCatch($em) != null) {
            return $this->message(4);
        };

        /*********** send JsonResponse ************************/
            return $this->message(8);
    }
}
