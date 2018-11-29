<?php

// MealOclock => app/
// Controllers => Controllers/
// au final => app/Controllers/
namespace MealOclock\Controllers;

// Import des classes venant d'un autre namespace
use MealOclock\Models\CommunityModel;
use MealOclock\Models\EventModel;
use MealOclock\Models\UserModel;

class MainController extends CoreController {
    public function home() {
        // dump($_SESSION);exit;
        // Je veux toutes les communautés de la DB
        $communitiesList = CommunityModel::findAll();
        // dump($communitiesList);exit; // debug
        
        $dataToViews = [
            'toto' => '<script>alert("TATAAAAAA")</script>', // => $toto dans les views
            'titi' => 'tutu', // => $titi dans les views
            'listOfCommunities' => $communitiesList // => $listOfCommunities
        ];

        $this->show('main/home', $dataToViews);
    }
    
    public function cgu() {
        // Exécute la view
        $this->show('main/cgu');
    }
    
    public function legalNotice() {
        // Exécute la view
        $this->show('main/legalNotice');
    }
    
    public function test() {
        // Tests sur static vs self
        \MealOclock\Models\CoreModel::understandStaticAndSelf();
        EventModel::understandStaticAndSelf();
        CommunityModel::understandStaticAndSelf();
        
        // Je teste la méthode findAll sur le UserModel
        $allUsers = UserModel::findAll();
        dump($allUsers);
        
        // Je souhaite tester la méthode insert()
        // Je commence par créer mon objet EventModel
        $eventModel = new EventModel();
        dump($eventModel);
        // J'utilise les SETTERS pour définir des valeurs aux propriétés
        $eventModel->setTitle('Poissons pour tous');
        $eventModel->setSlug('poissons-tous');
        $eventModel->setDescription('Du poisson à gogo');
        $eventModel->setPrice(2.99);
        $eventModel->setDateEvent('2018-06-26 14:00:00'); // au format MySQL : YYYY-MM-DD
        $eventModel->setAddress('4 place de la Madeleine');
        $eventModel->setZipcode('75008');
        $eventModel->setCity('PARIS');
        $eventModel->setNbGuests(40);
        $eventModel->setIsVirtual(2);
        $eventModel->setCommunityId(2);
        $eventModel->setUserId(1);
        dump($eventModel);
        
        // J'effectue l'insertion en DB
        $eventModel->save();
        dump($eventModel);
        
        // Je teste la ligne créée en DB, en la récupérant via find
        $eventModelFound = EventModel::find($eventModel->getId());
        dump($eventModelFound);
        
        // Je fais une mise à jour de mon second model
        // Pµour cela, je modifie une propriété
        $eventModelFound->setSlug('poissons-au-top');
        // Puis j'appelle la méthode update()
        $eventModelFound->save();
        dump($eventModelFound);
        
        // Je teste la suppression
        if ($eventModelFound->delete()) {
            echo 'suppression ok<br>';
        }
        else {
            echo 'erreur dans la suppression<br>';
        }
        dump($eventModelFound);
        
        // On vérifie la suppression
        $deletedEventModel = EventModel::find($eventModelFound->getId());
        dump($deletedEventModel);
        
        if ($deletedEventModel === false) {
            echo 'event supprimé<br>';
        }
        
        // Je vérifie la méthode findAll sur EventModel
        $allEvents = EventModel::findAll();
        dump($allEvents);
    }
}
