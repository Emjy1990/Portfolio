<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use App\Entity\User;
use App\Entity\Role;



class AdminController extends Controller
{

    /**
    * @Route("/admin", name="admin")
    */
    public function admin(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $role = "ROLE_ADMINISTRATOR";
        $adminRole = $roleRepository->findBy(["code" => $role]);
        $adminProfil = $userRepository->findBy(["role" => 4]);

        $adminJson = [];

        foreach($adminProfil as $row){

            array_push($adminJson, $row->getUsername(), $row->getFirstname(), $row->getLastname(), $row->getEmail(), $row->getImage(), $row->getPhone(), $row->getDate());
        }


        json_encode($adminJson);

        return $this->reponse($adminJson);
    }
}
