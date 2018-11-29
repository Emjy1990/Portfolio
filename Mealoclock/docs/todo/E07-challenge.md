# Challenge Factoriser et Aller au delà du CRUD

**PairProgramming**

## Objectifs

Les objectifs sont :

- de factoriser les méthodes ou propriétés qui sont communes à nos _Models_
- de créer les dernières méthodes nécessaires à notre projet
- de travailler en PairProgramming (2 cerveaux valent mieux qu'un ^^)

## Challenge _CoreModel_

### Astuces

- factoriser : _centraliser un code similaire dans un seul endroit pour ne pas le répéter_
- lorsqu'une classe _A_ hérite d'une class _B_, la classe _A_ hérite de toutes les méthodes et de toutes les propriétés de _B_

### Challenge

- qui dit factoriser, dit classe "parent" (et souvent abstraite)
- trouver dans le code des _Models_ les méthodes et/ou propriétés qui peuvent être factorisée

## Challenge _Last Methods_

### Astuces

- commencez d'abord par écrire la requête SQL (dans phpMyAdmin par exemple),  
  puis, une fois fonctionnelle, petit copier-coller des familles dans la méthode du _Model_
- utilisez la méthode `->prepare()` de PDO dès que la requête SQL contient une "variable"
  (ou tout le temps, avec ou sans variable, comme vous voulez :wink:)

### 5 derniers events

Pour la home, nous avons besoin des 5 derniers évènements

### Communautés d'un utilisateur

Dans le bon _Model_, créer la méthode récupérant une liste des communautés dont l'utilisateur est "membre".

## Bonus _Last Methods_

On va avoir besoin pour la partie sécurité de récupérer un _UserModel_ à partir d'un email (page de login).  
Dans le bon _Model_, créer la méthode `findByEmail($email)` retournant un _UserModel_ à partir d'un email.

## Bonus de la mort 💀

- coder tous les CRUD de tous les _Models_
- tester les méthodes avec notre page `/test`
- si c'était déjà fait :clap:

## Bonus de la mort 💀, le retour

Actuellement, la méthode `findAll` du _CoreModel_ me retourne toutes les lignes de la table, quelque soit la valeur du champ `status`.  
Je souhaite que pour `EventModel` et `CommunityModel`, il y ait un filtre sur le `status` (=1).  
Pour cela, on doit **surcharger** (**overrider** en anglais) la méthode du parent afin de filtrer le tableau de résultats.    
:warning: interdiction de modifier la requête SQL d'origine ou de faire une seconde requête :warning:

<details><summary>un indice</summary>

Google est ton ami :joy:  
Tu dois apprendre à rechercher des notions sur le net, lire plusieurs sources afin de vérifier leur exactitude.

</details>
