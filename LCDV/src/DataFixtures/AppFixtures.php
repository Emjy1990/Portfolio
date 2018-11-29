<?php

namespace App\DataFixtures;

use App\Entity\Adress;
use App\entity\BankingCoordinate;
use App\entity\Category;
use App\entity\DeliveryMode;
use App\entity\Command;
use App\entity\OrderPaymentFarmer;
use App\entity\PaymentMode;
use App\entity\Product;
use App\entity\Role;
use App\entity\Statusorder;
use App\entity\StatusOrderPaymentFarmer;
use App\entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AppFixtures extends Fixture {
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager){

        $generator = Faker\Factory::create('fr_FR');
        $populator = new Faker\ORM\Doctrine\Populator($generator, $manager);

        $roleAdmin = New Role();
        $roleAdmin->setName('Administrateur');
        $roleAdmin->setCode('ROLE_ADMINISTRATOR');

        $roleUser = New Role();
        $roleUser->setCode('ROLE_USER');
        $roleUser->setName('Utilisateur');

        $roleFarmer = New Role();
        $roleFarmer->setCode('ROLE_FARMER');
        $roleFarmer->setName('Producteur');

        $manager->persist($roleAdmin);
        $manager->persist($roleUser);
        $manager->persist($roleFarmer);


        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setFirstname('admin');
        $userAdmin->setLastname('admin');
        $userAdmin->setPassword($this->encoder->encodePassword($userAdmin, 'admin'));
        $userAdmin->setEmail('admin@lcdv.fr');
        $userAdmin->setPhone('0297254578');
        $userAdmin->setImage('public/images/ADMIN.png');
        $userAdmin->setDate(new \DateTime());
        $userAdmin->setRole($roleAdmin);


        $userSimple =  new User();
        $userSimple->setUsername('user');
        $userSimple->setFirstname('user');
        $userSimple->setLastname('user');
        $userSimple->setPassword($this->encoder->encodePassword($userSimple, 'user'));
        $userSimple->setEmail('user@lcdv.fr');
        $userSimple->setPhone('0298456745');
        $userSimple->setImage('public/images/USER.png');
        $userSimple->setDate(new \DateTime());
        $userSimple->setRole($roleUser);


        $userFarmer =  new User();
        $userFarmer->setUsername('farmer');
        $userFarmer->setFirstname('farmer');
        $userFarmer->setLastname('farmer');
        $userFarmer->setPassword($this->encoder->encodePassword($userFarmer, 'farmer'));
        $userFarmer->setEmail('farmer@lcdv.fr');
        $userFarmer->setPhone('0567865645');
        $userFarmer->setImage('public/images/FARMER.png');
        $userFarmer->setSolde(1000);
        $userFarmer->setDate(new \DateTime());
        $userFarmer->setRole($roleFarmer);


        $manager->persist($userAdmin);
        $manager->persist($userSimple);
        $manager->persist($userFarmer);


        $userList = [];
        for ($i=0; $i < 15; $i++){
            $user= new User();
            $user
            ->setUsername($generator->username)
            ->setFirstname($generator->firstname)
            ->setLastname($generator->lastname)
            ->setPassword($this->encoder->encodePassword($userFarmer, 'hello'))
            ->setEmail($generator->email)
            ->setPhone($generator->PhoneNumber)
            ->setImage($generator->imageUrl($width = 640, $height = 480))
            ->setDate(new \DateTime())
            ->setRole($roleUser);
            array_push($userList, $user);
            $manager->persist($user);
        }

        $arraySolde = [75, 100, 150, 200, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000];
        $farmerList = [];
        for ($i=0; $i < 15; $i++){
            $farmer = new User();
            $farmer->setUsername($generator->username)
            ->setFirstname($generator->firstname)
            ->setLastname($generator->lastname)
            ->setPassword($this->encoder->encodePassword($userFarmer, 'hello'))
            ->setEmail($generator->email)
            ->setPhone($generator->PhoneNumber)
            ->setImage($generator->imageUrl($width = 640, $height = 480))
            ->setDate(new \DateTime())
            ->setRole($roleFarmer)
            ->setSolde($arraySolde[rand(0,14)]);
            array_push($farmerList, $farmer);
            $manager->persist($farmer);
        }


        $userAdminAdress = new Adress();
        $userAdminAdress->setNumber($generator->buildingNumber);
        $userAdminAdress->setStreet($generator->streetName);
        $userAdminAdress->setEtage(1);
        $userAdminAdress->setCp(75000);
        $userAdminAdress->setCity($generator->city);
        $userAdminAdress->setUser($userAdmin);
        $manager->persist($userAdminAdress);


        $userAdminBanking = new BankingCoordinate();
        $userAdminBanking->setName($generator->creditCardType);
        $userAdminBanking->setAccount($generator->swiftBicNumber);
        $userAdminBanking->setUser($userAdmin);
        $manager->persist($userAdminBanking);


        $arrayCp = [56300, 75000, 13000, 45000, 59650, 32344, 44000, 29200, 38200, 61250, 34125];
        $arrayEtage = [1, 2, 3, 4, 5];
        $arrayBuilding = ['A', 'B', 'C', 'D'];

        $arrayUserAlea = [];
        foreach($userList as $row){
            $userAlea = new Adress();
            $userAlea->setNumber($generator->buildingNumber);
            $userAlea->setStreet($generator->streetName);
            $userAlea->setBuilding($arrayBuilding[rand(0, 3)]);
            $userAlea->setEtage($arrayEtage[rand(0,4)]);
            $userAlea->setCp($arrayCp[rand(0,10)]);
            $userAlea->setCity($generator->city);
            $userAlea->setUser($row);
            array_push($arrayUserAlea, $userAlea);
            $manager->persist($userAlea);
        }


        foreach($farmerList as $row){
            $farmerAlea = new Adress();
            $farmerAlea->setNumber($generator->buildingNumber);
            $farmerAlea->setStreet($generator->streetName);
            $farmerAlea->setCp($arrayCp[rand(0,10)]);
            $farmerAlea->setCity($generator->city);
            $farmerAlea->setUser($row);
            $manager->persist($farmerAlea);
        }


        foreach($userList as $row){
            $userBankingAlea = new BankingCoordinate();
            $userBankingAlea->setName($generator->creditCardType);
            $userBankingAlea->setAccount($generator->swiftBicNumber);
            $userBankingAlea->setUser($row);
            $manager->persist($userBankingAlea);
        }

        $arrayFarmerBanking = [];
        foreach($farmerList as $row){
            $farmerBankingAlea = new BankingCoordinate();
            $farmerBankingAlea->setName($generator->creditCardType);
            $farmerBankingAlea->setAccount($generator->swiftBicNumber);
            $farmerBankingAlea->setUser($row);
            array_push($arrayFarmerBanking, $farmerBankingAlea);
            $manager->persist($farmerBankingAlea);
        }

        $arrayCategory = [];
        $arrayNameCategory = ['Légumes', 'Fruits', 'Boissons', 'Miels', 'Viandes'];
        foreach($arrayNameCategory as $row){
            $category = new Category();
            $category->setName($row);
            array_push($arrayCategory, $category);
            $manager->persist($category);
        }


        $arrayStatusFarmerOrder = [];
        $arrayStatusOrderPaymentFarmer = ['Autorisation en attente', 'Autorisation refusée', 'Annulé par client', 'Autorisé', 'Paiement OK', 'Paiement annulé'];
        foreach($arrayStatusOrderPaymentFarmer as $row){
            $statusPaymentFarmer = new StatusOrderPaymentFarmer();
            $statusPaymentFarmer->setName($row);
            array_push($arrayStatusFarmerOrder, $statusPaymentFarmer);
            $manager->persist($statusPaymentFarmer);
        }


        $arrayStatusUserOrder = [];
        $arrayStatusOrderPaymentUser = ['Autorisation en attente', 'Autorisation refusée', 'Annulé par client', 'Accepter', 'Paiement OK', 'Paiement annulé'];
        foreach($arrayStatusOrderPaymentUser as $row){
            $statusPaymentUser = new Statusorder();
            $statusPaymentUser->setName($row);
            array_push($arrayStatusUserOrder, $statusPaymentUser);
            $manager->persist($statusPaymentUser);
        }


        $arrayAmount = [50, 100, 250, 500, 1000 ];
        foreach($userList as $row){
            $farmerOrderPayment= new OrderPaymentFarmer();
            $farmerOrderPayment->setDate(new \DateTime());
            $farmerOrderPayment->setAmount($arrayAmount[rand(0, 4)]);
            $farmerOrderPayment->setUser($row);
            $farmerOrderPayment->setBankingCoordinate($arrayFarmerBanking[rand(0, 4)]);
            $farmerOrderPayment->setStatus($arrayStatusFarmerOrder[rand(0, 5)]);
            $manager->persist($farmerOrderPayment);
        }


        $product1 = new Product();
        $product1->setName('Aubergine');
        $product1->setPrice(1.50);
        $product1->setDescription('L\'aubergine est un des légumes (c\'est en réalité un fruit) frais les moins caloriques (18 kilocalories pour 100 grammes).');
        $product1->setQuantity(50);
        $product1->setUser($userList[rand(0,14)]);
        $product1->setCategory($arrayCategory[0]);
        $manager->persist($product1);


        $product2 = new Product();
        $product2->setName('Oignon');
        $product2->setPrice(4.11);
        $product2->setDescription(' L\'oignon est à la fois un légume et un condiment. Il peut se consommer cru ou cuit, ou également confit au vinaigre.');
        $product2->setQuantity(50);
        $product2->setUser($userList[rand(0,14)]);
        $product2->setCategory($arrayCategory[0]);
        $manager->persist($product2);


        $product3 = new Product();
        $product3->setName('Pomme');
        $product3->setPrice(2.3);
        $product3->setDescription(' La pomme est un fruit comestible à pépins d\'un goût sucré et acidulé et à la propriété plus ou moins astringente selon les variétés');
        $product3->setQuantity(50);
        $product3->setUser($userList[rand(0,14)]);
        $product3->setCategory($arrayCategory[1]);
        $manager->persist($product3);


        $product4 = new Product();
        $product4->setName('Cidre');
        $product4->setPrice(2.45);
        $product4->setDescription('Le cidre est une boisson alcoolisée titrant généralement entre 2 % vol. et 8 % vol. d\'alcool, obtenue à partir de la fermentation du jus de pomme.');
        $product4->setQuantity(50);
        $product4->setUser($userList[rand(0,14)]);
        $product4->setCategory($arrayCategory[2]);
        $manager->persist($product4);


        $product5 = new Product();
        $product5->setName('Miel d\'acacia');
        $product5->setPrice(27.90);
        $product5->setDescription('Le miel est une substance sucrée élaborée par les abeilles à miel à partir de nectar ou de miellat.');
        $product5->setQuantity(50);
        $product5->setUser($userList[rand(0,14)]);
        $product5->setCategory($arrayCategory[3]);
        $manager->persist($product5);


        $product6 = new Product();
        $product6->setName('Poulet Fermier');
        $product6->setPrice(7.74);
        $product6->setDescription('Un poulet est une jeune volaille, mâle ou femelle, de la sous-espèce Gallus gallus domesticus, élevée pour sa chair.');
        $product6->setQuantity(50);
        $product6->setUser($userList[rand(0,14)]);
        $product6->setCategory($arrayCategory[4]);
        $manager->persist($product6);


        $arrayProduct = [];
        array_push($arrayProduct, $product1, $product2, $product3, $product4, $product5, $product6);


        $arrayPaymentChoice = [];
        $arrayPaymentMode = ['Paypal', 'Carte Bancaire', 'Stripe'];
        foreach($arrayPaymentMode as $row){
            $paymentChoice = new PaymentMode();
            $paymentChoice->setName($row);
            array_push($arrayPaymentChoice, $paymentChoice);
            $manager->persist($paymentChoice);
        }

        $arrayDeliveryOrder = [];
        $arrayDeliveryMode = ['Chronopost', 'DHL', 'Colissmo', 'Relais_Colis'];
        $arrayDelay = [1, 2, 3, 4];
        foreach($arrayDeliveryMode as $row){
            $deliveryOrder = new DeliveryMode();
            $deliveryOrder->setName($row);
            $deliveryOrder->setDelay($arrayDelay[rand(0, 3)]);
            array_push($arrayDeliveryOrder, $deliveryOrder);
            $manager->persist($deliveryOrder);
        }


        $userCommand = new Command();
        $userCommand->setDateOpen(new \DateTime());
        $userCommand->setDateAccepted($generator->DateTime('2018-04-29 20:38:49', 'Europe/Paris'));
        $userCommand->setDateDelivery($generator->DateTime('2018-05-4 20:40:49', 'Europe/Paris'));
        $userCommand->setDateClosed($generator->DateTime('2018-05-4 20:38:49', 'Europe/Paris'));
        $userCommand->setPrice(7.74);
        $userCommand->setUser($userSimple);
        $userCommand->setPaymentMode($arrayPaymentChoice[0]);
        $userCommand->setDeliveryMode($arrayDeliveryOrder[rand(0, 3)]);
        $userCommand->setStatus($arrayStatusUserOrder[rand(0, 5)]);
        $userCommand->setDeliveryAdress($arrayUserAlea[rand(0, 14)]);
        $userCommand->setPaymentAdress($arrayUserAlea[rand(0, 14)]);
        $userCommand->setFarmer($farmerList[rand(0,4)]);
        $userCommand->addProduct($product6);
        $manager->persist($userCommand);


        $userAleaCommand = new Command();
        $userAleaCommand->setDateOpen(new \DateTime());
        $userAleaCommand->setDateAccepted($generator->DateTime('2018-04-29 20:38:49', 'Europe/Paris'));
        $userAleaCommand->setDateDelivery($generator->DateTime('2018-05-4 20:40:49', 'Europe/Paris'));
        $userAleaCommand->setDateClosed($generator->DateTime('2018-05-4 20:38:49', 'Europe/Paris'));
        $userAleaCommand->setPrice(2.45);
        $userAleaCommand->setUser($userList[rand(0, 14)]);
        $userAleaCommand->setPaymentMode($arrayPaymentChoice[0]);
        $userAleaCommand->setDeliveryMode($arrayDeliveryOrder[rand(0, 3)]);
        $userAleaCommand->setStatus($arrayStatusUserOrder[rand(0, 5)]);
        $userAleaCommand->setDeliveryAdress($arrayUserAlea[rand(0, 14)]);
        $userAleaCommand->setPaymentAdress($arrayUserAlea[rand(0, 14)]);
        $userAleaCommand->setFarmer($farmerList[rand(0,4)]);
        $userAleaCommand->addProduct($product4);
        $manager->persist($userAleaCommand);

        $userFarmerCommand = new Command();
        $userFarmerCommand->setDateOpen(new \DateTime());
        $userFarmerCommand->setDateAccepted($generator->DateTime('2018-04-29 20:38:49', 'Europe/Paris'));
        $userFarmerCommand->setDateDelivery($generator->DateTime('2018-05-4 20:40:49', 'Europe/Paris'));
        $userFarmerCommand->setDateClosed($generator->DateTime('2018-05-4 20:38:49', 'Europe/Paris'));
        $userFarmerCommand->setPrice(4.11);
        $userFarmerCommand->setUser($userFarmer);
        $userFarmerCommand->setPaymentMode($arrayPaymentChoice[0]);
        $userFarmerCommand->setDeliveryMode($arrayDeliveryOrder[rand(0, 3)]);
        $userFarmerCommand->setStatus($arrayStatusUserOrder[rand(0, 5)]);
        $userFarmerCommand->setDeliveryAdress($arrayUserAlea[rand(0, 14)]);
        $userFarmerCommand->setPaymentAdress($arrayUserAlea[rand(0, 14)]);
        $userFarmerCommand->setFarmer($farmerList[rand(0,4)]);
        $userFarmerCommand->addProduct($product2);
        $manager->persist($userFarmerCommand);



        $manager->flush();




    }
}
