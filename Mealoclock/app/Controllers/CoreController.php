<?php

// MealOclock => app/
// Controllers => Controllers/
// au final => app/Controllers/
namespace MealOclock\Controllers;

// Import Classe erterne + alias
use League\Plates\Engine as Plates;
use MealOclock\Application;
use MealOclock\Utils\User;

// classe héritée par tous les controllers
// abstract = cette classe ne peut avoir d'instance ou d'objet
abstract class CoreController {
    
    // Je passe le moteur de template en propriété
    // afin que le moteur soit dispo dans toutes les méthodes
    protected $templateEngine;
    
    // Je passe le routeur AltoRouter en propriété
    // afin que le routeur soit dispo dans toutes les méthodes
    protected $router;
    
    // Lors de la construction des Controllers
    // on instancie le moteur de templates "Plates"
    // On récupère en argument l'objet Application qui a instancié ce Controller
    // En mettant "(Application $app)", je force le paramètre à être du type "Application" (c'est à dire un objet de la classe Application)
    public function __construct(Application $app) {
        // Create new Plates instance
        $this->templateEngine = new Plates(__DIR__.'/../Views');
        
        // Je stocke l'objet AlotRouter
        $this->router = $app->getRouter();
        
        // On transmet des données à toutes les views
        $this->templateEngine->addData([
            'router' => $this->router, // => $router dans toutes les views
            'basePath' => $app->getConfig('BASE_PATH'), // => $basePath
            'connectedUser' => User::isConnected() ? User::getConnectedUser() : false // $connectedUser => user connecté
        ]);
    }
    
    // Méthode permettant d'afficher la partie vue pour une page
    protected function show(string $templateName, array $dataToViews=[]) {
        $viewFolder = '';
        // TODO récupérer dynamiquement le répertoire de la view
        // Render a template
        echo $this->templateEngine->render($viewFolder.$templateName, $dataToViews);
    }
    
    // Méthode permettant d'afficher une réponse JSON (souvent suite à une requête HTTP en Ajax)
    // static car aucun $this dans le corps de la méthode
    // => static = méthode liée à la Classe
    protected static function sendJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    // Méthode renvoyant une entete HTTP d'erreur (404, 403, 500, etc.)
    // static car aucun $this dans le corps de la méthode
    // => static = méthode liée à la Classe
    public static function sendHttpError($errorCode, $htmlContent='') {
        // Erreur 404 not found
        if ($errorCode == 404) {
            header("HTTP/1.0 404 Not Found");
            echo $htmlContent;
            exit;
        }
        // Erreur 403 Forbidden = vousn'avez pas les droits pour accéder à cette ressource
        else if ($errorCode == 403) {
            header('HTTP/1.0 403 Forbidden');
            echo $htmlContent;
            exit;
        }
    }
    
    /**
     * Méthode permettant de rediriger en PHP vers une URL
     * @param  string $url
     */
    public function redirect(string $url) {
        header('Location: '.$url);
        exit;
    }
    
    /**
     * Méthode permettant de rediriger vers l'URL de la route fournie en argument
     * @param  string $routeName
     */
    public function redirectToRoute(string $routeName) {
        $this->redirect($this->router->generate($routeName));
    }
    
    /**
     * Méthode permettant de gérer les restrictions d'accès à certains roles
     * @param  int $roleId
     */
    public function restrictToRoleId(int $roleId) {
        // Si l'utilisateur n'est pas connecté
        if (!User::isConnected()) {
            // Je redirige vers la page de connexion
            $this->redirect($this->router->generate('user_login'));
        }
        // Sinon, si l'utilisateur connecté n'a pas les droits  
        else if (User::getConnectedUser()->getRoleId() != $roleId) {
            // 403 forbidden
            $this->sendHttpError(403, 'Vous n\'avez pas accès à cette page');
        }
    }
}
