<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ProductTypeNew;

class ProductController extends Controller
{
    /* DB Request.
    *  Return data.
    */

    /**
    * @Route("/list/product", name="list_product")
    */
    public function listProduct(Session $session, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        if(!$session->has('cart'))
        {
            $session->set('cart' ,[]);
        }
        $products = $session->get('cart');
        // dump($product);die;

        $basketCount = array_sum($products);

        $session->set('basketCount', $basketCount );

        return $this->render('list/product.html.twig', ["products" =>  $productRepository->findAll(), "categories" => $categoryRepository->findAll() ]);
    }

    /* Request Ajax from React with farmer id.
    *  DB Request.
    *  Return data or error if false.
    */

    /**
    * @Route("/product/farmer/{id}", name="list_product_by_farmer")
    */
    public function listProductByFarmer(ProductRepository $productRepository, $id, CategoryRepository $categoryRepository)
    {

        $products = $productRepository->findBy(["user"=>$id ]);



        return $this->render('product/productForFarmer.html.twig', ["products" =>  $products, "categories" => $categoryRepository->findAll() ]);
    }

    /* Request Ajax from React with category id.
    *  DB Request.
    *  Return data or error if false.
    */

    /**
    * @Route("/product/category/{id}", name="list_product_by_category")
    */
    public function listProductByCategory(ProductRepository $productRepository, CategoryRepository $categoryRepository, $id)
    {
        return $this->render('list/product.html.twig', ["products" =>  $productRepository->findBy(["category"=>$id]), "categories" => $categoryRepository->findAll() ]);
    }

    /* Request Ajax from React with data (name,price,description,quantity,category_id).
    *  DB Save.
    *  Return 1 if true or error if false.
    */

    /**
    * @Route("/create/product", name="create_product")
    */
    public function createProduct(Request $request, CategoryRepository $categoryRepository, UserRepository $userRepository, ProductRepository $productRepository)
    {

        $farmer = $this->getUser();
        $product = new Product();

        $form = $this->createForm(ProductTypeNew::class, $product);

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
            $product->setUser($farmer);
            $product->setImage($fileName);
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Votre produit à bien été enregistré !');


            return $this->redirectToRoute('list_product_by_farmer', ['id' => $farmer->getId()]);
        }

        return $this->render('product/create.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /* Request Ajax from React with product id.
    *  DB Request.
    *  Return data or error if false.
    */

    /**
    * @Route("/show/product/{id}", name="show_product")
    */
    public function showProduct(Product $product): Response
    {
        return $this->render('profil/product.html.twig', ["product" =>  $product]);
    }

    /* Request Ajax from React with category id.
    *  DB Update.
    *  Return 1 if true or error if false.
    */

    /**
    * @Route("/update/product/{id}", name="update_product")
    */
    public function updateProduct(Request $request, Product $product, User $user): Response
    {
        $user = $product->getUser()->getId();

        $form = $this->createForm(ProductTypeNew::class, $product);
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
            $product->setImage($fileName);
            $em->flush();
            $this->addFlash('success', 'Votre produit a bien été mis a jour!');
            return $this->redirectToRoute('list_product_by_farmer', ['id' => $user]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);

    }


    /**
    * @Route("/delete/product/{id}", name="delete_product", methods="DELETE")
    */
    public function deleteProduct(Request $request, Product $product): Response
    {
        $user = $product->getUser()->getId();

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Votre produit a bien été supprimé!');
        }

        return $this->redirectToRoute('list_product_by_farmer', ['id' => $user]);


    }


    /**
    * @Route("/addCart", name="addCart")
    */
    public function addCart(Session $session, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        /********* test cart existent ************/
        if(!$session->has('cart'))
        {
            $session->set('cart' ,[]);
        }

        /******** set cart session ***************/
        $cart = $session->get('cart');
        //dump($cart);die;

        /*********** update product quantity ***********/
        $product = $productRepository->find($_GET["product"]);
        if($product->getQuantity()-$_GET["qt"] >= 0)
        {

            $product->setQuantity($product->getQuantity()-$_GET["qt"]);

            if ( array_key_exists($_GET["product"] , $cart) )
            {

                $cart[$_GET["product"]] = $cart[$_GET["product"]] + $_GET["qt"];
            } else {
                $cart[$_GET["product"]] = $_GET["qt"];
            }
        }
        $em->persist($product);
        $em->flush();
        $this->addFlash('success', 'Produit ajouté!');
        /********** save cart session ******************/
        $session->set("cart", $cart);

        //dump($session->get('cart'));die;


        return $this->redirectToRoute("list_product");
    }

    /**
    * @Route("/suppCart", name="suppCart")
    */
    public function suppCart(Session $session, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        /********* test cart existent ************/
        if(!$session->has('cart'))
        {
            $session->set('cart' ,[]);
        }

        $cart = $session->get('cart');

        $cpt = 0;
        foreach ($cart as $key => $value)
        {
            $product = $productRepository->find($key);
            $product->setQuantity($product->getQuantity()+$value);
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Produit supprimé!');

        }
        $session->set('cart' ,[]);
        $session->set('basketCount', null);
        return $this->redirectToRoute("list_product");
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
