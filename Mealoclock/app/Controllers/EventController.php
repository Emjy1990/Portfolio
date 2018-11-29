<?php

namespace MealOclock\Controllers;

use MealOclock\Models\EventModel;

class EventController extends CoreController {
    public function list() {
        $this->show('event/list', [
            'eventsList' => EventModel::findAll()
        ]);
    }
    public function showEvent($urlParamsToto) {
        // Je récupère les données de l'URL qui m'intéresse
        //dump($urlParamsToto);exit;
        $idEvent = $urlParamsToto['idEvent'];
        
        // Exécution de la view 'event/showEvent' + passage de données ($eventId)
        $this->show('event/showEvent', [
            'eventModel' => EventModel::find($idEvent) // => $eventModel
        ]);
    }
}
