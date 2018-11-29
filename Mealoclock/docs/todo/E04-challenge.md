# Challenge Routes, Controllers & Views

## Objectif

L'objectif est de créer toutes les pages de toutes les routes prévues au projet :scream:  
Non, en fait, seulement quelques pages du projet pour l'instant ^^ :

- mentions légales (`/legal-notice`)
- liste des events (`/events`)
- inscription (`/signup`)
- connexion (`/login`)
- déconnexion (`/logout`)
- espace membre / profil (`/profile`)
- page d'un évènement (`/communities/[i:idCommunity]/[a:slugCommunity]/events/[i:idEvent]/[a:slugEvent]`)


### Astuces

- Le challenge (hors bonus) est déjà assez conséquent.  
  Ne faites les bonus que si le challenge a été facile et rapide à faire :wink:
- Commencez par configurer 1 page de A à Z (du mappage de la route jusqu'à la vue).  
  Une fois fonctionnelle, "dupliquer" le fonctionnement aux autres pages

## Challenge

### Routes

A partir du document listant toutes les routes,  
écrire le code PHP (dans la classe `Application`) permettant de mapper chacune des routes dans l'objet `AltoRouter`.

<details><summary>la route est bien définie, mais elle n'est pas "matchée" ?</summary>

1. regarder s'il n'y a pas un `/` en trop à la fin de l'URL (barre d'adresse)
2. vérifier que le _basePath_ est correctement défini (au moins une route doit fonctionner)
3. vérifier le type de(s) partie(s) dynamique(s) du pattern d'URL (`[i:id]` => `i` = integer)
4. vérifier la typo côté pattern d'URL (map de la route)
5. vérifier la typo côté URL (barre d'adresse)
6. vérifier que le dossier `public` fait toujours partie de l'URL (si pas de vhost.local configuré)

</details>

### Navigation

Pour pouvoir tester toutes nos routes, on va commencer par créer une mini navigation (`<nav>`) avec les liens nécessaires.  
Pour l'instant, nous ne nous soucions pas de l'aspect graphique. On s'occupera de l'intégration en E05.

<details><summary>Un souci au niveau des liens de la navigation ?</summary>

:warning: n'oubliez pas que chaque `/` dans l'URL représente un dossier.

Donc, si on se trouve dans l'URL `http://localhost/mealOclock/public/community/42`  
et qu'on veut faire un lien vers `http://localhost/mealOclock/public/cgu`  
- le lien relatif doit être :`<a href="../cgu">lien</a>`
- le lien absolu doit être :`<a href="/mealOclock/public/cgu">lien</a>`

Quelle est la meilleure solution ? Qui ne dépendra pas de la page/URL sur laquelle on se trouve ?

</details>

### Controllers

- créer tous les Controllers définis dans notre document listant les routes.  
- définir aussi 1 méthode pour chaque route.  
- :warning: parfois, le nom de la méthode n'est pas définie dans le Google Sheet, à vous de choisir le nom.

### Views

Pour chaque page,
- créer le fichier de template dans le répertoire _Views_
- mettre un petit code HTML simple représentant la page
- :warning: ne pas oublier de bien structurer ses fichiers :wink:
- dans la méthode de Controller, appeler la méthode `show` pour afficher la template/view

## Bonus Navigation

- On veut générer une URL à partir du nom d'une route, avec AltoRouter
- Cependant, la doc d'AltoRouter n'est pas complète
- Seule solution, lire le code source d'AltoRouter : https://github.com/dannyvankooten/AltoRouter/blob/master/AltoRouter.php
- Modifier la navigation pour utiliser cette méthode générant l'URL à partir du nom d'une route

## Bonus de la mort 💀

Finalement, je ne déconnais pas, il faut bien créer toutes les pages de toutes les routes prévues au projet :scream:
