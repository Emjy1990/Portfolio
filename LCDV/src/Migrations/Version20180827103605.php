<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180827103605 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(64) NOT NULL, price DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, quantity INT NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04ADA76ED395 (user_id), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_payment_farmer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, status_id INT NOT NULL, banking_coordinate_id INT NOT NULL, date DATETIME NOT NULL, amount INT NOT NULL, INDEX IDX_A1CDE7A9A76ED395 (user_id), INDEX IDX_A1CDE7A96BF700BD (status_id), INDEX IDX_A1CDE7A95D9862AC (banking_coordinate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_mode (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, number INT NOT NULL, street VARCHAR(255) NOT NULL, building VARCHAR(255) DEFAULT NULL, etage INT DEFAULT NULL, cp INT NOT NULL, city VARCHAR(64) NOT NULL, INDEX IDX_5CECC7BEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, code VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, username VARCHAR(25) NOT NULL, firstname VARCHAR(64) NOT NULL, lastname VARCHAR(64) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, image VARCHAR(255) NOT NULL, phone VARCHAR(35) NOT NULL, date DATETIME NOT NULL, money DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, solde INT DEFAULT NULL, UNIQUE INDEX UNIQ_C2502824F85E0677 (username), INDEX IDX_C2502824D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, payment_mode_id INT NOT NULL, delivery_mode_id INT NOT NULL, status_id INT NOT NULL, delivery_adress_id INT NOT NULL, payment_adress_id INT NOT NULL, farmer_id INT NOT NULL, date_open DATETIME NOT NULL, date_accepted DATETIME NOT NULL, date_delivery DATETIME NOT NULL, date_closed DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_8ECAEAD4A76ED395 (user_id), INDEX IDX_8ECAEAD46EAC8BA0 (payment_mode_id), INDEX IDX_8ECAEAD47DFB3A94 (delivery_mode_id), INDEX IDX_8ECAEAD46BF700BD (status_id), INDEX IDX_8ECAEAD4C0E3B53E (delivery_adress_id), INDEX IDX_8ECAEAD4C7AFD684 (payment_adress_id), INDEX IDX_8ECAEAD413481D2B (farmer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command_product (command_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_3C20574E33E1689A (command_id), INDEX IDX_3C20574E4584665A (product_id), PRIMARY KEY(command_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_mode (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, delay INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statusorder (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banking_coordinate (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, account VARCHAR(64) NOT NULL, INDEX IDX_BD4AB061A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status_order_payment_farmer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE order_payment_farmer ADD CONSTRAINT FK_A1CDE7A9A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE order_payment_farmer ADD CONSTRAINT FK_A1CDE7A96BF700BD FOREIGN KEY (status_id) REFERENCES status_order_payment_farmer (id)');
        $this->addSql('ALTER TABLE order_payment_farmer ADD CONSTRAINT FK_A1CDE7A95D9862AC FOREIGN KEY (banking_coordinate_id) REFERENCES banking_coordinate (id)');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BEA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE app_users ADD CONSTRAINT FK_C2502824D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD46EAC8BA0 FOREIGN KEY (payment_mode_id) REFERENCES payment_mode (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD47DFB3A94 FOREIGN KEY (delivery_mode_id) REFERENCES delivery_mode (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD46BF700BD FOREIGN KEY (status_id) REFERENCES statusorder (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4C0E3B53E FOREIGN KEY (delivery_adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4C7AFD684 FOREIGN KEY (payment_adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD413481D2B FOREIGN KEY (farmer_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE command_product ADD CONSTRAINT FK_3C20574E33E1689A FOREIGN KEY (command_id) REFERENCES command (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE command_product ADD CONSTRAINT FK_3C20574E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE banking_coordinate ADD CONSTRAINT FK_BD4AB061A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE command_product DROP FOREIGN KEY FK_3C20574E4584665A');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD46EAC8BA0');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4C0E3B53E');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4C7AFD684');
        $this->addSql('ALTER TABLE app_users DROP FOREIGN KEY FK_C2502824D60322AC');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE order_payment_farmer DROP FOREIGN KEY FK_A1CDE7A9A76ED395');
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BEA76ED395');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4A76ED395');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD413481D2B');
        $this->addSql('ALTER TABLE banking_coordinate DROP FOREIGN KEY FK_BD4AB061A76ED395');
        $this->addSql('ALTER TABLE command_product DROP FOREIGN KEY FK_3C20574E33E1689A');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD47DFB3A94');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD46BF700BD');
        $this->addSql('ALTER TABLE order_payment_farmer DROP FOREIGN KEY FK_A1CDE7A95D9862AC');
        $this->addSql('ALTER TABLE order_payment_farmer DROP FOREIGN KEY FK_A1CDE7A96BF700BD');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE order_payment_farmer');
        $this->addSql('DROP TABLE payment_mode');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE command_product');
        $this->addSql('DROP TABLE delivery_mode');
        $this->addSql('DROP TABLE statusorder');
        $this->addSql('DROP TABLE banking_coordinate');
        $this->addSql('DROP TABLE status_order_payment_farmer');
    }
}
