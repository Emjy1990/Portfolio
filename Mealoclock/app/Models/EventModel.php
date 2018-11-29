<?php

namespace MealOclock\Models;

// Import des classes externes au namespace
use MealOclock\Utils\Database;
use PDO;

class EventModel extends CoreModel {
    /** @var string */
    protected $title;
    /** @var string */
    protected $slug;
    /** @var string */
    protected $description;
    /** @var float  */
    protected $price;
    /** @var string */
    protected $date_event;
    /** @var string */
    protected $address;
    /** @var string */
    protected $zipcode;
    /** @var string */
    protected $city;
    /** @var int */
    protected $nb_guests;
    /** @var int */
    protected $is_virtual;
    /** @var string */
    protected $virtual_address;
    /** @var int */
    protected $community_id;
    /** @var int */
    protected $user_id;
    
    // Ajout d'une constante
    // - valeur ne peut pas être modifiée
    // - elle est obligatoirement public
    const TABLE_NAME = 'event';
    
    /**
     * Méthode permettant de récupérer les 5 derniers évènements dans la table
     * @return array
     */
    public function findLastFive() : array {
        return $this->findLast(5);
    }
    
    /**
     * Méthode permettant de renvoyer le UserModel de l'organisateur de l'event
     * @return UserModel|bool
     */
    public function getHost() {
        return UserModel::find($this->user_id);
    }
    
    /**
     * Méthode permettant de renvoyer le CommunityModel de la communauté de l'event
     * @return CommunityModel|bool
     */
    public function getCommunity() {
        return CommunityModel::find($this->community_id);
    }
    
    public function findLast(int $limit=10) {
        $sql = '
            SELECT *
            FROM '.self::TABLE_NAME.'
            ORDER BY id DESC
            LIMIT :limit
        ';
        $pdoStatement = Database::getPDO()->prepare($sql);
        $pdoStatement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $pdoStatement->execute();
        
        return $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
    }
    
    /**
     * Méthode permettant de récupérer les 5 prochains évènements
     * @return array
     */
    public function findNextFive() : array {
        $sql = '
            SELECT *
            FROM '.self::TABLE_NAME.'
            WHERE date_event >= NOW()
            ORDER BY date_event ASC
            LIMIT 5
        ';
        $pdoStatement = Database::getPDO()->query($sql);
        
        return $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
    }
    
    protected function insert() : bool {
        $sql = '
            INSERT INTO '.self::TABLE_NAME.' (title, slug, description, price, date_event, address, zipcode, city, nb_guests, is_virtual, virtual_address, status, date_inserted, community_id, user_id)
            VALUES (
                :title,
                :slug,
                :description,
                :price,
                :date_event,
                :address,
                :zipcode,
                :city,
                :nb_guests,
                :is_virtual,
                :virtual_address,
                1,
                NOW(),
                :community_id,
                :user_id
            )
        ';
        $pdo = Database::getPDO();
        // J'utilise "prepare" !!
        $pdoStatement = $pdo->prepare($sql);
        // Je fais les bindValue
        $pdoStatement->bindValue(':title', $this->title, PDO::PARAM_STR);
        $pdoStatement->bindValue(':slug', $this->slug, PDO::PARAM_STR);
        $pdoStatement->bindValue(':description', $this->description, PDO::PARAM_STR);
        $pdoStatement->bindValue(':price', $this->price, PDO::PARAM_INT);
        $pdoStatement->bindValue(':date_event', $this->date_event, PDO::PARAM_STR);
        $pdoStatement->bindValue(':address', $this->address, PDO::PARAM_STR);
        $pdoStatement->bindValue(':zipcode', $this->zipcode, PDO::PARAM_STR);
        $pdoStatement->bindValue(':city', $this->city, PDO::PARAM_STR);
        $pdoStatement->bindValue(':nb_guests', $this->nb_guests, PDO::PARAM_INT);
        $pdoStatement->bindValue(':is_virtual', $this->is_virtual, PDO::PARAM_INT);
        $pdoStatement->bindValue(':virtual_address', $this->virtual_address, PDO::PARAM_STR);
        $pdoStatement->bindValue(':community_id', $this->community_id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        
        // J'exécute la requête
        $pdoStatement->execute();
        
        // Je récupère le nombre de lignes affectées
        $affectedRows = $pdoStatement->rowCount();
        
        // Si au moins une ligne ajoutée
        if ($affectedRows > 0) {
            // Je récupère l'id de la ligne ajoutée
            $this->id = $pdo->lastInsertId();
            
            return true;
        }
        // else implicite
        return false;
    }
    
    protected function update() : bool {
        $sql = '
            UPDATE '.self::TABLE_NAME.'
            SET title = :title,
            slug = :slug,
            description = :description,
            price = :price,
            date_event = :date_event,
            address = :address,
            zipcode = :zipcode,
            city = :city,
            nb_guests = :nb_guests,
            is_virtual = :is_virtual,
            virtual_address = :virtual_address,
            date_updated = NOW(),
            community_id = :community_id,
            user_id = :user_id
            WHERE id = :id
        ';
        $pdo = Database::getPDO();
        // J'utilise "prepare" !!
        $pdoStatement = $pdo->prepare($sql);
        // Je fais les bindValue
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':title', $this->title, PDO::PARAM_STR);
        $pdoStatement->bindValue(':slug', $this->slug, PDO::PARAM_STR);
        $pdoStatement->bindValue(':description', $this->description, PDO::PARAM_STR);
        $pdoStatement->bindValue(':price', $this->price, PDO::PARAM_INT);
        $pdoStatement->bindValue(':date_event', $this->date_event, PDO::PARAM_STR);
        $pdoStatement->bindValue(':address', $this->address, PDO::PARAM_STR);
        $pdoStatement->bindValue(':zipcode', $this->zipcode, PDO::PARAM_STR);
        $pdoStatement->bindValue(':city', $this->city, PDO::PARAM_STR);
        $pdoStatement->bindValue(':nb_guests', $this->nb_guests, PDO::PARAM_INT);
        $pdoStatement->bindValue(':is_virtual', $this->is_virtual, PDO::PARAM_INT);
        $pdoStatement->bindValue(':virtual_address', $this->virtual_address, PDO::PARAM_STR);
        $pdoStatement->bindValue(':community_id', $this->community_id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        
        // J'exécute la requête
        $pdoStatement->execute();
        
        // Je récupère le nombre de lignes affectées
        $affectedRows = $pdoStatement->rowCount();
        
        // Retourne true si au moins une ligne modifiée
        return ($affectedRows > 0);
    }
    
    // Getters
    public function getTitle() : string {
        return $this->title;
    }
    public function getSlug() : string {
        return $this->slug;
    }
    public function getDescription() : string {
        return $this->description;
    } 
    public function getPrice() : float {
        return $this->price;
    } 
    public function getDateEvent() : string {
        return $this->date_event;
    } 
    public function getAddress() : string {
        return $this->address;
    } 
    public function getZipcode() : string {
        return $this->zipcode;
    } 
    public function getCity() : string {
        return $this->city;
    }
    public function getNbGuests() : int {
        return $this->nb_guests;
    }
    public function getIsVirtual() : int {
        return $this->is_virtual;
    }
    public function getVirtualAddress() : string  {
        return $this->virtual_address;
    }
    public function getCommunityId() : int {
        return $this->community_id;
    }
    public function getUserId() : int {
        return $this->user_id;
    }
    
    // SETTERS
    public function setTitle(string $title) {
        $this->title = $title;
    }
    public function setSlug(string $slug) {
        $this->slug = $slug;
    }
    public function setDescription(string $description) {
        $this->description = $description;
    }
    public function setPrice(float $price) {
        $this->price = $price;
    }
    public function setDateEvent(string $date_event) {
        $this->date_event = $date_event;
    }
    public function setAddress(string $address) {
        $this->address = $address;
    }
    public function setZipcode(string $zipcode) {
        $this->zipcode = $zipcode;
    }
    public function setCity(string $city) {
        $this->city = $city;
    }
    public function setNbGuests(int $nb_guests) {
        $this->nb_guests = $nb_guests;
    }
    public function setIsVirtual(int $is_virtual) {
        $this->is_virtual = $is_virtual;
    }
    public function setVirtualAddress(string $virtual_address) {
        $this->virtual_address = $virtual_address;
    }
    public function setCommunityId(int $community_id) {
        $this->community_id = $community_id;
    }
    public function setUserId(int $user_id) {
        $this->user_id = $user_id;
    }
}
