<?php

// MealOclock => app/
// Controllers => Controllers/
// au final => app/Controllers/
namespace MealOclock\Controllers;

// Import des classes externes
use MealOclock\Models\CommunityModel;

class CommunityController extends CoreController {
    public function community($urlParams) {
        // Je récupère l'id se trouvant dans l'URL
        $id = $urlParams['id'];
        // echo "Vous souhaitez consulter la communauté n°{$id}<br>";
        
        // Je veux récuperer l'objet CommunityModel correspondant à l'id de la communauté fourni dans l'URL
        $communityModel = CommunityModel::find($id);
        // dump($communityModel);exit; // debug
        
        // Exécute la view '/community/community' avec passage de données jusqu'à la view 
        $this->show('/community/community', [
            'communityModel' => $communityModel
        ]);
    }
}
