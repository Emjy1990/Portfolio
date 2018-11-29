<?php

namespace MealOclock\Controllers;

use MealOclock\Models\UserModel;
use MealOclock\Utils\User;

class UserController extends CoreController {
    public function signup() {
        // Tableau contenant toutes les erreurs
        $errorList = [];
        
        // formulaire soumis
        if (!empty($_POST)) {
            //dump($_POST);exit;
            // Je récupère les données
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $passwordConfirm = isset($_POST['passwordConfirm']) ? $_POST['passwordConfirm'] : '';
            
            // Je traite les données si besoin
            $email = trim($email);
            $password = trim($password);
            $passwordConfirm = trim($passwordConfirm);
            
            // Je valide les données => je cherche les erreurs
            if (empty($email)) {
                $errorList[] = 'Email vide';
            }
            else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errorList[] = 'Email incorrect';
            }
            if (empty($password)) {
                $errorList[] = 'Mot de passe vide';
            }
            if (empty($passwordConfirm)) {
                $errorList[] = 'Confirmation de mot de passe vide';
            }
            if ($password != $passwordConfirm) {
                $errorList[] = 'Les 2 mots de passe sont différents';
            }
            if (strlen($password) < 8) {
                $errorList[] = 'Le mot de passe doit faire au moins 8 caractères';
            }
            
            // Si tout est ok (aucune erreur)
            if (empty($errorList)) {
                // On vérifie la présence de l'adresse email en DB
                $userModel = UserModel::findByEmail($email);
                
                // Si l'email existe
                if ($userModel !== false) {
                    // Ajouter un message d'erreur
                    $errorList[] = 'Un compte avec cette adresse email existe déjà';
                }
                // Sinon
                else {
                    // Je hash/encode le mot de passe
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    // On insert en DB
                    $newUserModel = new UserModel();
                    $newUserModel->setEmail($email);
                    $newUserModel->setPassword($hashedPassword);
                    $newUserModel->setRoleId(2);
                    $newUserModel->save();
                    
                    // On redirige sur la page profile
                    $this->redirectToRoute('user_profile');
                }
            }
        }
        
        // Exécute la view
        $this->show('user/signup', [
            'errorList' => $errorList
        ]);
    }
    public function login() {
        // Exécute la view
        $this->show('user/login');
    }
    
    public function ajaxLoginPost() {
        // Tableau contenant toutes les erreurs
        $errorList = [];
        
        //dump($_POST);exit;
        // Je récupère les données
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        // Je traite les données si besoin
        $email = trim($email);
        $password = trim($password);
        
        // Je valide les données => je cherche les erreurs
        if (empty($email)) {
            $errorList[] = 'Email vide';
        }
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errorList[] = 'Email incorrect';
        }
        if (empty($password)) {
            $errorList[] = 'Mot de passe vide';
        }
        if (strlen($password) < 8) {
            $errorList[] = 'Le mot de passe doit faire au moins 8 caractères';
        }
        
        // Si tout est ok (aucune erreur)
        if (empty($errorList)) {
            // On va cherche le user pour l'email fourni
            $userModel = UserModel::findByEmail($email);
            // dump($userModel);exit;
            
            // Si l'email existe
            if ($userModel !== false) {
                // Alors, on va tester le password
                if (password_verify($password, $userModel->getPassword())) {
                    // Je connecte l'utilisateur, peut importe comme cela est fait
                    User::connect($userModel);
                    
                    // Affichage d'un JSON "ok"
                    self::sendJson([
                        'code' => 1,
                        'redirect' => $this->router->generate('main_home'),
                        'errorList' => $errorList
                    ]);
                }
                else {
                    $errorList[] = 'Identifiants/mot de passe non reconnus';
                }
            }
            // Sinon
            else {
                $errorList[] = 'Email non reconnu';
            }
        }
        
        // Si on arrive ici, c'est que c'est pas "ok"
        // Donc, on affiche un JSON avec les erreurs
        // Affichage d'un JSON "ok"
        self::sendJson([
            'code' => 2,
            'errorList' => $errorList
        ]);
    }
    
    public function logout() {
        // Je déconnecte le user
        User::disconnect();
        
        // Je redirige vers la home
        $this->redirectToRoute('main_home');
    }
    public function profile() {
        // Page restreinte aux Membres
        $this->restrictToRoleId(2);
        
        // Exécute la view
        $this->show('user/profile');
    }
    
    public function communities() {
        // Page restreinte aux Membres
        $this->restrictToRoleId(2);
        
        // Je vais chercher les communautés du user connecté
        $listOfCommunities = User::getConnectedUser()->getCommunities();
        
        // Exécute la view
        $this->show('user/communities', [
            'communitiesList' => $listOfCommunities
        ]);
    }
}
