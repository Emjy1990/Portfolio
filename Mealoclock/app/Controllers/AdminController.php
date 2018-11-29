<?php

namespace MealOclock\Controllers;

use MealOclock\Models\UserModel;
use MealOclock\Utils\User;

class AdminController extends CoreController {
    public function membersList() {
        // Je restreins cette page au role Id 1
        $this->restrictToRoleId(1);
        
        // Je veux tous les users
        $usersList = UserModel::findAll();
        
        // J'affiche la template
        $this->show('admin/members_list', [
            'usersList' => $usersList // => $usersList dans la view
        ]);
    }
}
