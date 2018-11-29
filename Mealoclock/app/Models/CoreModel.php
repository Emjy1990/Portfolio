<?php

namespace MealOclock\Models;

use MealOclock\Utils\Database;
use PDO;

// abstract => interdiction de créer une instance de cette classe
abstract class CoreModel {
    /** @var int */
    protected $id;
    /** @var int */
    protected $status;
    /** @var string */
    protected $date_inserted;
    /** @var string */
    protected $date_updated;
    
    // self => la classe dans laquelle est écrit le mot clé "self"
    // static => la classe courante = la classe depuis laquelle on a appelé la méthode
    // parent => la classe parente (dont on a hérité)
    // $this => l'objet courant
    // &copy; Georges : self c'est la classe où est écrit le code, static c'est la classe qui est en train d'utiliser la méthode
    
    public static function find(int $id) {
        $sql = '
            SELECT *
            FROM '.static::TABLE_NAME.'
            WHERE id = :id
        ';
        $pdo = Database::getPDO();
        // J'utilise "prepare" !!
        $pdoStatement = $pdo->prepare($sql);
        // Je fais les bindValue
        $pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
        
        // J'exécute ma requete
        $pdoStatement->execute();
        
        // Je récupère le résultat sous forme d'objet
        // !Attention! Objet sous forme FQCN
        // self::class => a pour valeur le FQCN de la classe actuelle
        $result = $pdoStatement->fetchObject(static::class);
        
        return $result;
    }
    
    public static function findAll() : array {
        //echo self::class.'<br>';
        $sql = '
            SELECT *
            FROM '.static::TABLE_NAME.'
        ';
        $pdo = Database::getPDO();
        // J'utilise "prepare" !!
        $pdoStatement = $pdo->query($sql);
        
        // Je récupère le résultat sous forme d'array d'objets
        // !Attention! Objet sous forme FQCN
        // static::class => a pour valeur le FQCN de la classe actuelle
        $result = $pdoStatement->fetchAll(PDO::FETCH_CLASS, static::class);
        
        return $result;
    }
    
    // Méthode permettant de sauvegarder l'objet en DB
    public function save() : bool {
        // Si l'objet courant correspond à une ligne existante en DB
        if ($this->id > 0) {
            // Alors mise à jour
            return $this->update();
        }
        else {
            // Sinon, ajout (puis la propriété id récupère la nouvelle valeur)
            return $this->insert();
        }
    }
    
    public function disable() : bool {
        // On pense à modifier l'objet courant
        $this->status = 2;
        
        // On appelle la méthode factorisée
        return $this->setStatusInDb();
    }
    
    public function enable() : bool {
        // On pense à modifier l'objet courant
        $this->status = 1;
        
        // On appelle la méthode factorisée
        return $this->setStatusInDb();
    }
    
    // J'ai factorisé le code permettant de donner une nouvelle valeur à la colonne "status"
    private function setStatusInDb() {
        $sql = "
            UPDATE ".static::TABLE_NAME."
            SET status = :newStatus,
            date_updated = NOW()
            WHERE id = :currentId
        ";
        $pdo = Database::getPDO();
        // Je prépare ma requete
        $pdoStatement = $pdo->prepare($sql);
        // Je donne une valeur à chaque paramètre de la requete
        $pdoStatement->bindValue(':newStatus', $this->status, PDO::PARAM_INT);
        $pdoStatement->bindValue(':currentId', $this->id, PDO::PARAM_INT);
        // J'exécute le requete (PDO a remplacé tous les paramètres de la requete)
        $pdoStatement->execute();
        // Je récupère le nombre de lignes affectées
        $affectedRows = $pdoStatement->rowCount();
        // Je retourne true si au moins une ligne modifiée
        return ($affectedRows > 0);
    }
        
    // Pour être certain que les 2 méthodes insert et update soit implémentées parmi les enfants de CoreModel
    // Je déclare des méthodes abstraites
    abstract protected function insert() : bool;
    abstract protected function update() : bool;
    
    public function delete() : bool {
        $sql = '
            DELETE FROM '.static::TABLE_NAME.'
            WHERE id = :id
        ';
        $pdo = Database::getPDO();
        // J'utilise "prepare" !!
        $pdoStatement = $pdo->prepare($sql);
        // Je fais les bindValue
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);
        
        // J'exécute la requête
        $pdoStatement->execute();
        
        // Je récupère le nombre de lignes affectées
        $affectedRows = $pdoStatement->rowCount();
        
        // Retourne true si au moins une ligne supprimée
        return ($affectedRows > 0);
    }
    
    // Pour mieux comprendre static vs self
    public static function understandStaticAndSelf() {
        echo 'Bonjour, je suis codé dans la classe '.self::class.' (self::class) mais je suis appelé depuis la classe '.static::class.' (static::class)<br>';
    }
    
    // GETTERS (à factoriser aussi)
    public function getId() : int {
        return $this->id;
    }
    
    public function getStatus() : int {
        return $this->status;
    }

    public function getDateInserted() : string {
        return $this->date_inserted;
    }

    public function getDateUpdated() : string {
        return $this->date_updated;
    }
}
