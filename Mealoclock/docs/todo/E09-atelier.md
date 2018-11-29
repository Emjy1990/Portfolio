# Atelier connexion User

Le but sera de stocker les données utilisateur en SESSION permettant de savoir qui est connecté.

## Connexion

Une fois le mot de passe validé pour l'email fourni :

- récupérer le UserModel complet
- stocker en SESSION ce UserModel
- rediriger vers la home (fonction `header`)

## Home

- tester que les données sont bien en SESSION (`dump()`)
- si la variable en SESSION n'existe pas, ou est vide, me contacter :wink:
- une fois vérifié, afficher le nom de l'utilisateur connecté :
  - retirer le `h1` "A table"
  - afficher "Bienvenue chez MealOclock" dans le `h1`
  - si il y a un utilisateur connecté, afficher "Bonjour {EMAIL}" où {EMAIL} est l'adresse email du user connecté

## Améliorations

- ne pas accéder directement à `$_SESSION` dans les _Views_
- passer l'utilisateur connecté aux _Views_ dans une variable "$connectedUser"
- utiliser uniquement cette variable `$connectedUser` dans les _Views_

## Admin

On va se créer une page pour la partie "Administration" : "Admin members - Liste des membres"

- créer la route, méthode de _Controller_ et _View_ pour 
- récupérer la liste de tous les Users
- passer les données à la _View_
- parcourir le array dans la _View_ et afficher les données

### Option Rôles

En option, afficher également le rôle de chaque User :

- récupérer la liste de tous les Roles
- passer les données à la _View_
- ajouter le role de chaque user (admin/member)
