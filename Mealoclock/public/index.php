<?php

// Chargement de l'autoload de Composer (pour avoir toutes les dépendances + le PSR-4)
require (__DIR__.'/../vendor/autoload.php');

// J'active le système de sessions de PHP
// à placer au début, du début, du début, etc., du code
// ou presque, on laisse l'autoload avant
session_start();

// Uniquement pour tester la méthode run de la classe Application
// Composer gère l'autoload => on commente la ligne
// require (__DIR__.'/../app/Application.php');

// Au lieu d'inclure chaque classe, je les importe ici
use MealOclock\Application;

// créer un fichier index.php (dans public si existant, ou à la racine) qui instancie et appelle la méthode "run" de votre classe Application
$app = new Application();
$app->run();

// vérififaction des fichiers inclus
//dump(get_included_files());
