<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use App\Entity\Category;

/**
* @Route("/admin")
*/
class CategoryController extends Controller
{

    /* DB Request.
    *  Return data.
    */

    /**
    * @Route("/list/category", name="category")
    */
    public function listCategory(CategoryRepository $categoryRepository)
    {
        /***************** request for all category ***********************/
        $categories = $categoryRepository->findAll();

        /******************convert object to json *********************/
        $categoriesJson = []; // json_encode on this array
        array_push($categoriesJson,  ["id" => "1"]); //message code ok

        foreach ($categories as $category){ //we're looking for pertinent data
            array_push($categoriesJson,[$category->getName()]);
        }

        json_encode($categoriesJson); //encoding
        // dump($categoriesJson);die();

        return $this->reponse($categoriesJson);
    }

    /**
    * @Route("/show/category/{id}", name="show_category")
    */
    public function showCategory(Category $category)
    {
        $categoryJson = []; // json_encode on this array

        /**************** convert object to json *************************/

        array_push($categoryJson,["name" => $category->getName()]);

        json_encode($categoryJson); //encoding

        return $this->reponse($categoryJson);
    }


    /* Request Ajax from React with data (name).
    *  DB Save.
    *  Return 1 if true or error if false.
    */

    /**
    * @Route("/create/category", name="create_category")
    */
    public function createCategory(EntityManagerInterface $em)
    {
        $category = new Category();
        $category->setName($_GET['name']);
        $categoryJson = [];

        if ($_GET['name'] == null) {
            array_push($categoryJson,  ["id" => "0"],["message" => "Champ incomplet"]); //message code
            return $this->reponse($categoryJson);
        }

        /******* create in DB **************/
        $em->persist($category);
        if($this->tryCatch($em) != null) {
            return $this->message(3);
        };

        /*********** send JsonResponse ************************/
        return $this->message(6);
    }

    /* Request Ajax from React with category id.
    *  DB Update.
    *  Return 1 if true or error if false.
    */

    /**
    * @Route("/update/category/{id}", name="update_category")
    */
    public function updateCategory(Category $category, EntityManagerInterface $em)
    {

        $categoryJson = [];

        if ($category->getName() == $_GET['name']) {
            array_push($categoryJson,  ["id" => "0"],["message" => "Pas de changement"]); //message code
            $reponse = new JsonResponse();
            $reponse->setData($categoryJson);
            return $reponse;
        }

        if ($_GET['name'] != null) {
            $category->setName($_GET['name']);
        }


        /******* create in DB **************/
        $em->persist($command);
        if($this->tryCatch($em) != null) {
            return $this->message(3);
        };

        /*********** send JsonResponse ************************/
        return $this->message(7);

    }

    /* Request Ajax from React with category id.
    *  DB Update.
    *  Return 1 if true or error if false.
    */

    /**
    * @Route("/delete/category/{id}", name="delete_category")
    */
    public function deleteCategory(Category $category, EntityManagerInterface $em)
    {
        /******* delete category in DB **************/

        $em->remove($category);
        if($this->tryCatch($em) != null) {
            return $this->message(3);
        }

        /*********** send JsonResponse ************************/
        return $this->message(8);
    }
}
