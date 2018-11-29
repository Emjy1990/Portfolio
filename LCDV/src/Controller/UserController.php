<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Adress;
use App\Form\AdressTypeNew;
use App\Form\UserTypeNew;
use App\Form\UserTypeEdit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\File;



class UserController extends Controller
{

    /* DB Request.
    *  Return data.
    */

    /**
     * @Route("/admin/user", name="list_user")
     */
    public function listUser(UserRepository $userRepository)
    {

        /***************** request for all users ***********************/
        $users = $userRepository->findAll();

        /******************convert object to json *********************/
        $usersJson = []; // json_encode on this array
        array_push($usersJson,  ["id" => "1"]); //message code ok
        foreach ($users as $user){ //we're looking for pertinent data
            array_push($usersJson,["username" => $user->getUsername(), "firstname" => $user->getFirstname(),"lastname" => $user->getLastname(),"email" => $user->getEmail(),"role" => $user->getRole()->getName(), "date" => $user->getDate()->format('Y-m-d')]);
        }
        json_encode($usersJson); //encoding

        /*********** send JsonResponse ************************/
        return $this->reponse($usersJson);
    }

    /* DB Request.
    *  Return data.
    */

    /**
     * @Route("/list/farmer", name="list_farmer")
     */
    public function listFarmer(Request $request, UserPasswordEncoderInterface $encoder,UserRepository $userRepository, RoleRepository $roleRepository)
    {

        /************* request for role_id ***************************/
        $role = $roleRepository->findBy(["code"=>"ROLE_FARMER"]);
        $roleId = $role[0]->getId();

        return $this->render('list/farmer.html.twig', ["farmers" => $userRepository->findBy(["role"=>$roleId])]);
    }

    /* Request Ajax from React with data ().
    *  Don't forget constraint.
    *  DB Save.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/inscription", name="inscription_user")
     */
    public function inscriptionUser(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder, RoleRepository $roleRepository)
    {
        /************* request for role_id ***************************/
        $role = $roleRepository->findBy(["code"=>"ROLE_USER"]);
        $roleId = $role[0];


      $user = new User();

      $form = $this->createForm(UserTypeNew::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
          $file = $form->get('image')->getData();

           $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();


           $file->move(
               $this->getParameter('images_directory'),
               $fileName
           );



          $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
          $user->setPassword($encodedPassword);
          $user->setRole($roleId);
          $user->setImage($fileName);
          $em->persist($user);
          $em->flush();
          $this->addFlash('success', 'Vous vous êtes bien inscrit!');
          return $this->redirectToRoute('inscription_user_complete',['user'=>$user->getId()]);
      }

      return $this->render('user/inscription_user.html.twig', [
          'client' => $user,
          'form' => $form->createView(),
      ]);

    }

    /**
     * @Route("/inscription/complete", name="inscription_user_complete")
     */
    public function inscriptionUserComplete(EntityManagerInterface $em, Request $request, UserRepository $userRepository)
    {

      $adress = new Adress();
      $user = $userRepository->find($_GET['user']);

      $form = $this->createForm(AdressTypeNew::class, $adress);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

          $adress->setUser($user);
          $em->persist($adress);
          $em->flush();
          $this->addFlash('success', 'Bienvenue chez nous cher consommateur!');
          return $this->redirectToRoute('home');
      }

      return $this->render('user/inscription_user_complete.html.twig', [

          'form' => $form->createView(),
      ]);

    }

    /**
     * @Route("/inscription/farmer", name="inscription_farmer")
     */
    public function inscriptionFarmer(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder, RoleRepository $roleRepository)
    {
        /************* request for role_id ***************************/
        $role = $roleRepository->findBy(["code"=>"ROLE_FARMER"]);
        $roleId = $role[0];


      $user = new User();

      $form = $this->createForm(UserTypeNew::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
          $file = $form->get('image')->getData();

           $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();


           $file->move(
               $this->getParameter('images_directory'),
               $fileName
           );
          $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
          $user->setPassword($encodedPassword);
          $user->setImage($fileName);
          $user->setRole($roleId);
          $em->persist($user);
          $em->flush();
          $this->addFlash('success', 'Bienvenue chez nous cher producteur!');
            return $this->redirectToRoute('inscription_user_complete',['user'=>$user->getId()]);
      }

      return $this->render('user/inscription_farmer.html.twig', [
          'client' => $user,
          'form' => $form->createView(),
      ]);

    }

    /* Request Ajax from React with user id.
    *  DB Request.
    *  Return data if true or error if false.
    */

    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function profil(UserRepository $userRepository, $id)
    {
        return $this->render('profil/user.html.twig', ["user" =>$userRepository->find($id)]);
    }

    /**
     * @Route("/show/farmer/{id}", name="profil_farmer")
     */
    public function showFarmer(UserRepository $userRepository, $id, ProductRepository $productRepository)
    {
        return $this->render('profil/farmer.html.twig', ["user" => $userRepository->find($id), "products" => $productRepository->findBy(["user"=>$id])]);
    }

    /* Request Ajax from React with user id and data to change.
    *  DB Save.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/update/user/{id}", name="update_user")
     */
    public function updateUser(User $user,EntityManagerInterface $em, $id, Request $request,UserRepository $userRepository)
    {
        if($_GET["username"] != "") {
            $user->setUsername($_GET["username"]);
        }
        if($_GET["firstname"] != "") {
            $user->setFirstname($_GET["firstname"]);
        }
        if($_GET["lastname"] != "") {
            $user->setLastname($_GET["lastname"]);
        }
        if($_GET["description"] != "") {
            $user->setDescription($_GET["description"]);
        }

        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'Profil mis à jour!');
        return $this->render('profil/user.html.twig', ["user" =>$userRepository->find($id)]);

    }

    /* Request Ajax from React with user id.
    *  DB Save.
    *  Return 1 if true or error if false.
    */

    /**
     * @Route("/delete/user/{id}", name="delete_user")
     */
    public function deleteUser(UserRepository $userRepository,EntityManagerInterface $em)
    {

        $user = $userRepository->find($id);

        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Profil supprimé!');
        return $this->redirectToRoute('home');

    }

    /**
    * @return string
    */
   private function generateUniqueFileName()
   {
       // md5() reduces the similarity of the file names generated by
       // uniqid(), which is based on timestamps
       return md5(uniqid());
   }
}
