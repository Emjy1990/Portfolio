<?php

namespace MealOclock\Utils;

use MealOclock\Models\UserModel;

// Classe servant de "libraire de fonctions"
// on encapsule les fonctions dans une classe => méthodes statiques
// ainsi, la classe et les méthodes ne sont chargées que si nécessaire
abstract class User {
    public static function isConnected() : bool {
        return (
            // Si l'utilisateur est connecté
            array_key_exists('connected-user', $_SESSION) &&
            // Si l'utilisateur en session est un objet
            is_object($_SESSION['connected-user']) && 
            // Si l'ip de l'utilisateur n'a pas changé
            $_SESSION['connected-user-ip'] == $_SERVER['REMOTE_ADDR']
        );
    }
  
    public static function getConnectedUser() {
        return $_SESSION['connected-user'];
    }
    
    public static function connect(UserModel $userModel) : bool {
        // Ajout le UserModel du user connecté en SESSION
        $_SESSION['connected-user'] = $userModel;
        // Je peux aussi stocker en session l'ip de l'utilisateur
        // afin d'éviter qu'un hacker usurpe sa session
        $_SESSION['connected-user-ip'] = $_SERVER['REMOTE_ADDR'];
        return true;
    }
    
    public static function disconnect() {
        // Je vérifié déjà qu'il y ait un user connecté
        if (self::isConnected()) {
            // On supprime le user en SESSION
            unset($_SESSION['connected-user']);
            // Et on supprime aussi l'ip du user
            unset($_SESSION['connected-user-ip']);
        }
    }
    
    public static function isAdmin() : bool {
        // $connectedUser = $_SESSION['user'];
        // return ($connectedUser->getRoleId() == 1);
        return (self::getConnectedUser()->getRoleId() == 1);
    }
    
    public static function isMember() : bool {
        return (self::getConnectedUser()->getRoleId() == 2);
    }
}
