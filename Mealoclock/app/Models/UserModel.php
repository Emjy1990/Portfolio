<?php

namespace MealOclock\Models;

use MealOclock\Utils\Database;
use PDO;

class UserModel extends CoreModel {
    /** @var string */
    protected $username;
    /** @var string */
    protected $presentation;
    /** @var string */
    protected $email;
    /** @var string */
    protected $password;
    /** @var string */
    protected $picture;
    /** @var int */
    protected $role_id;
    
    const TABLE_NAME = 'user';
    
    /**
     * Méthode permettant de récupérer la liste des communautés desquelles le user est membre
     * @return array
     */
    public function getCommunities() : array {
        return CommunityModel::findCommunitiesByUserId($this->id);
    }
    
    /**
     * Méthode permettant de retourner un objet RoleModel associé à ce UserModel
     * @return RoleModel|false
     */
    public function getRole() {
        // TODO retourner un objet RoleModel à partir de l'id $this->role_id
    }
    
    /**
     * Méthode permettant de récupérer un objet UserModel à partir d'un email
     * @param  string $email
     * @return UserModel|bool
     */
    public static function findByEmail(string $email) {
        $sql = '
            SELECT *
            FROM '.self::TABLE_NAME.'
            WHERE email = :email
        ';
        $pdoStatement = Database::getPDO()->prepare($sql);
        $pdoStatement->bindValue(':email', $email, PDO::PARAM_STR);
        $pdoStatement->execute();
        
        return $pdoStatement->fetchObject(self::class);
    }
    
    // Obligation de déclarer/implémenter les 3 méthodes abstraites de CoreModel
    protected function insert() : bool {
        $sql = '
            INSERT INTO '.self::TABLE_NAME.' (`username`, `presentation`, `email`, `password`, `picture`, `status`, `date_inserted`, `role_id`)
            VALUES (:username, :presentation, :email, :password, :picture, 1, NOW(), :role_id)
        ';
        $pdoStatement = Database::getPDO()->prepare($sql);
        $pdoStatement->bindValue(':username', $this->username, PDO::PARAM_STR);
        $pdoStatement->bindValue(':presentation', $this->presentation, PDO::PARAM_STR);
        $pdoStatement->bindValue(':email', $this->email, PDO::PARAM_STR);
        $pdoStatement->bindValue(':password', $this->password, PDO::PARAM_STR);
        $pdoStatement->bindValue(':picture', $this->picture, PDO::PARAM_STR);
        $pdoStatement->bindValue(':role_id', $this->role_id, PDO::PARAM_INT);
        $pdoStatement->execute();
        
        $affectedRows = $pdoStatement->rowCount();
        
        if ($affectedRows > 0) {
            $this->id = Database::getPDO()->lastInsertId();
            return true;
        }
        return false;
    }
    protected function update() : bool {
        $sql = '
            UPDATE event
            SET username = :username,
            presentation = :presentation,
            email = :email,
            password = :password,
            picture = :picture,
            role_id = :role_id,
            date_updated = NOW()
            WHERE id = :id
        ';

        $pdo = Database::getPDO();
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':username', $this->username, PDO::PARAM_STR);
        $statement->bindValue(':presentation', $this->presentation, PDO::PARAM_STR);
        $statement->bindValue(':email', $this->email, PDO::PARAM_STR);
        $statement->bindValue(':password', $this->password, PDO::PARAM_STR);
        $statement->bindValue(':picture', $this->picture, PDO::PARAM_STR);
        $statement->bindValue(':role_id', $this->role_id, PDO::PARAM_INT);
        $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
        $statement->execute();
        
        $affectedRows = $statement->rowCount();

        return ($affectedRows > 0);
    }
    public function delete() : bool {
        
    }
    
    // GETTERS (&copy; Damien)

    public function getUsername() : string {
        return $this->username;
    }

    public function getPresentation() : string {
        return $this->presentation;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getPicture() : string {
        return $this->picture;
    }

    public function getRoleId() : int {
        return $this->role_id;
    }

    // SETTERS (&copy; Damien)

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function setPresentation(string $presentation) {
        $this->presentation = $presentation;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setPicture(string $picture) {
        $this->picture = $picture;
    }

    public function setRoleId(int $role_id) {
        $this->role_id = $role_id;
    }
}
