<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE agency (id INT AUTO_INCREMENT NOT NULL, city INT DEFAULT NULL, agency_code VARCHAR(255) NOT NULL, phone_number VARCHAR(15) NOT NULL, email VARCHAR(100) NOT NULL, address VARCHAR(300) NOT NULL, INDEX IDX_70C0C6E62D5B0234 (city), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name_ VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name_ VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, wilaya INT DEFAULT NULL, name_ VARCHAR(200) NOT NULL, INDEX IDX_2D5B0234CF6AF33B (wilaya), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, city INT DEFAULT NULL, fullname_ VARCHAR(200) NOT NULL, email VARCHAR(200) NOT NULL, password VARCHAR(200) NOT NULL, address VARCHAR(300) NOT NULL, phone_number VARCHAR(200) NOT NULL, license_number VARCHAR(200) NOT NULL, api_token LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_C7440455E7927C74 (email), INDEX IDX_C74404552D5B0234 (city), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract_ (id INT AUTO_INCREMENT NOT NULL, client INT NOT NULL, vehicle INT NOT NULL, date_ INT NOT NULL, arrival TIME NOT NULL, departure TIME NOT NULL, INDEX IDX_3B88C590C7440455 (client), INDEX IDX_3B88C5901B80E486 (vehicle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, contract_ INT DEFAULT NULL, date_ TIME NOT NULL, amount DOUBLE PRECISION NOT NULL, paid TINYINT(1) NOT NULL, INDEX IDX_906517443B88C590 (contract_), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, category INT DEFAULT NULL, brand INT DEFAULT NULL, name_ VARCHAR(200) NOT NULL, INDEX IDX_D79572D964C19C1 (category), INDEX IDX_D79572D91C52F958 (brand), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, model INT NOT NULL, agency INT NOT NULL, registration_number VARCHAR(255) NOT NULL, rental_price DOUBLE PRECISION NOT NULL, inssurance_price DOUBLE PRECISION NOT NULL, deposit DOUBLE PRECISION NOT NULL, passenger_number INT NOT NULL, image_ VARCHAR(255) NOT NULL, suitcase_number INT NOT NULL, state_ VARCHAR(200) NOT NULL, gearbox VARCHAR(100) NOT NULL, status_ VARCHAR(300) NOT NULL, INDEX IDX_1B80E486D79572D9 (model), INDEX IDX_1B80E48670C0C6E6 (agency), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wilaya (id INT AUTO_INCREMENT NOT NULL, name_ VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agency ADD CONSTRAINT FK_70C0C6E62D5B0234 FOREIGN KEY (city) REFERENCES city (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234CF6AF33B FOREIGN KEY (wilaya) REFERENCES wilaya (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404552D5B0234 FOREIGN KEY (city) REFERENCES city (id)');
        $this->addSql('ALTER TABLE contract_ ADD CONSTRAINT FK_3B88C590C7440455 FOREIGN KEY (client) REFERENCES client (id)');
        $this->addSql('ALTER TABLE contract_ ADD CONSTRAINT FK_3B88C5901B80E486 FOREIGN KEY (vehicle) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517443B88C590 FOREIGN KEY (contract_) REFERENCES contract_ (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D964C19C1 FOREIGN KEY (category) REFERENCES category (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D91C52F958 FOREIGN KEY (brand) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486D79572D9 FOREIGN KEY (model) REFERENCES model (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48670C0C6E6 FOREIGN KEY (agency) REFERENCES agency (id)');
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E48670C0C6E6');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D91C52F958');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D964C19C1');
        $this->addSql('ALTER TABLE agency DROP FOREIGN KEY FK_70C0C6E62D5B0234');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404552D5B0234');
        $this->addSql('ALTER TABLE contract_ DROP FOREIGN KEY FK_3B88C590C7440455');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517443B88C590');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486D79572D9');
        $this->addSql('ALTER TABLE contract_ DROP FOREIGN KEY FK_3B88C5901B80E486');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234CF6AF33B');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE contract_');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE wilaya');
    }
}
