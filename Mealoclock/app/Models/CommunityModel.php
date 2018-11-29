<?php

namespace MealOclock\Models;

// On Importe les classes d'un autre namespace
use MealOclock\Utils\Database;
use PDO;

// 1 table en DB = 1 model
// 1 model = 1 classe PHP
// 1 colonne en DB  = 1 propriété
class CommunityModel extends CoreModel {
    // Les propriétés (private ou protected, au choix)
    /** @var string */
    protected $name;
    /** @var string */
    protected $slug;
    /** @var string */
    protected $description;
    /** @var string */
    protected $image;
    
    // Ajout d'une constante
    // - valeur ne peut pas être modifiée
    // - elle est obligatoirement public
    const TABLE_NAME = 'community';
    
    /**
     * Méthode findAll dont la classe a hérité
     * Mais on préfère la remplacer (surcharger / override) afin de modifier son comportement (status = 1)
     * @return array [description]
     */
    public static function findAll() : array {
        //echo self::class.'<br>';
        // J'utilise la méthode qui a été héritée (celle du parent)
        $communitiesList = parent::findAll();
        //dump($communitiesList);
        // Je parcours la liste pour supprimer les lignes dont le statut n'est pas à 1
        foreach ($communitiesList as $index=>$currentCommunityModel) {
            // Si statut différent de 1
            if ($currentCommunityModel->getStatus() != 1) {
                // Alors, je supprime l'élément dans le tableau
                // !attention! toujours préciser la variable contenant le tableau complet + le ou les index
                unset($communitiesList[$index]);
            }
        }
        //dump($communitiesList);exit;
        
        return $communitiesList;
    }
    
    /**
     * Méthode statique permettant de renvoyer un tableu de CommunityModel pour un membre donné en paramètre
     * @param  int   $userId
     * @return array
     */
    public static function findCommunitiesByUserId(int $userId) : array {
        $sql = '
            SELECT *
            FROM '.self::TABLE_NAME.'
            INNER JOIN community_has_user ON community_has_user.community_id = '.self::TABLE_NAME.'.id
            WHERE community_has_user.user_id = :userId
        ';
        $pdoStatement = Database::getPDO()->prepare($sql);
        $pdoStatement->bindValue(':userId', $userId, PDO::PARAM_INT);
        $pdoStatement->execute();
        
        return $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
    }
    
    // Private car utilisée que par la classe courante (voir save())
    protected function insert() : bool {
        // status = 1 => pour activer par défaut
        // date_inserted = "NOW()" => fonction retournant la Datetime de "maintenant"
        $sql = "
            INSERT INTO community (`name`, `slug`, `description`, `image`, `status`, `date_inserted`)
            VALUES (:name, :slug:, :description:, :image:, 1, NOW())
        ";
        $pdoStatement = Database::getPDO()->prepare($sql);
        $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindValue(':slug', $this->slug, PDO::PARAM_STR);
        $pdoStatement->bindValue(':description', $this->description, PDO::PARAM_STR);
        $pdoStatement->bindValue(':image', $this->image, PDO::PARAM_STR);
        $pdoStatement->execute();
        
        $insertedRows = $pdoStatement->rowCount();
        
        // Si au moins une ligne ajoutée
        if ($insertedRows > 0) {
            // Je récupère l'id de la ligne ajoutée
            $this->id = $pdo->lastInsertId();
            
            return true;
        }
        // else implicite
        return false;
    }
    // Private car utilisée que par la classe courante (voir save())
    protected function update() : bool {
        $sql = "
            UPDATE community
            SET name = :name,
            slug = :slug,
            description = :description,
            image = :image,
            date_updated = NOW()
            WHERE id = :id
        ";
        $pdoStatement = Database::getPDO()->prepare($sql);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindValue(':slug', $this->slug, PDO::PARAM_STR);
        $pdoStatement->bindValue(':description', $this->description, PDO::PARAM_STR);
        $pdoStatement->bindValue(':image', $this->image, PDO::PARAM_STR);
        $pdoStatement->execute();
        
        $insertedRows = $pdoStatement->rowCount();
        
        return ($affectedRows > 0);
    }
    
    // GETTERS
    public function getName() : string {
        return $this->name;
    }

    public function getSlug() : string {
        return $this->slug;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function getImage() : string {
        return (!empty($this->image) ? $this->image : 'assets/images/community.jpg');
    }


    // SETTERS
    public function setName(string $name) {
        $this->name = $name;
    }
 
    public function setSlug(string $slug) {
        $this->slug = $slug;
    }
 
    public function setDescription(string $description) {
        $this->description = $description;
    }
 
    public function setImage(string $image) {
        $this->image = $image;
    }

}
