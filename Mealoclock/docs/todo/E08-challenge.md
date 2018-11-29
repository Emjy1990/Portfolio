# Challenge Nettoyage avec Ajax

## Objectifs

L'objectif est d'envoyer les formulaires de connexion et d'inscription en Ajax :muscle:

## Astuces

- le traitement à effectuer en POST n'est pas différent lorsqu'on passe par Ajax  
  il faut juste répondre par du JSON au lieu d'HTML
- la méthode `sendJSON($data)` du `CoreController` permet justement d'envoyer (afficher) des données au format JSON
- :warning: la version jQuery fournie avec Bootstrap est la version "slim" qui ne contient pas Ajax

## Ajax & JSON

- en JS :
  - intercepter la soumission du formulaire
  - récupérer toutes les données
  - envoyer les données en POST vers la bonne route (`->generate()` impossible dans un fichier JS)
  - si le retour est positif
    - rediriger vers la page d'accueil
  - sinon, afficher les erreurs à l'écran
- en PHP :
  - on peut laisser 1 seule route pour traiter GET & POST
  - mais le mieux est de séparer les 2 routes => 2 méthodes
  - déplacer le code de traitement du POST déjà écrit aujourd'hui dans la nouvelle méthode attaché à "la route en POST"
  - modifier les actions à faire selon la réussite du traitement
    - si tout ok, on renvoie un JSON avec une information de réussite + l'URL vers laquelle JS doit rediriger
    - sinon, retourner un JSON avec une information d'échec + une liste des erreurs

## Bonus

Modifier aussi le formulaire de la page d'inscription pour que tout soit traité en Ajax :joy:

## Bonus de la mort 💀

Certaines pages du site peuvent être mises en place pour la partie "publique" du site (non réservé aux membres ou admin) :  

- on a toutes les routes configurées
- on a le code récupérant les données de la DB
- il reste donc :
  - les méthodes de _Controller_
  - les _Views_
- Let's go ! :muscle:

### :bulb: Idée

Organises-toi avec tes collègues pour faire ce bonus à plusieurs (2 à 4 par équipe).  
Prenez l'un de vos dépôts GitHub, et ajoutez des _Collaborators_ à ce dépôt. Ils pourront ainsi faire des _commits_ et de _push_ :tada:  
Chacun une page et on va plus loin :muscle:
