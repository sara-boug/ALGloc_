<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200612191442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE agency (id INT AUTO_INCREMENT NOT NULL, city INT DEFAULT NULL, agency_code VARCHAR(255) NOT NULL, phone_number INT NOT NULL, email VARCHAR(100) NOT NULL, address VARCHAR(300) NOT NULL, INDEX IDX_70C0C6E62D5B0234 (city), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, wilaya INT DEFAULT NULL, name VARCHAR(200) NOT NULL, INDEX IDX_2D5B0234CF6AF33B (wilaya), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, client INT DEFAULT NULL, number INT NOT NULL, departure TIME NOT NULL, arrival TIME NOT NULL, date TIME NOT NULL, INDEX IDX_E98F2859C7440455 (client), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, contract INT DEFAULT NULL, date TIME NOT NULL, amount DOUBLE PRECISION NOT NULL, paid TINYINT(1) NOT NULL, INDEX IDX_90651744E98F2859 (contract), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, category INT DEFAULT NULL, brand INT DEFAULT NULL, name VARCHAR(200) NOT NULL, INDEX IDX_D79572D964C19C1 (category), INDEX IDX_D79572D91C52F958 (brand), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, model INT DEFAULT NULL, registration_nummer VARCHAR(200) NOT NULL, rental_price DOUBLE PRECISION NOT NULL, inssurance_price DOUBLE PRECISION NOT NULL, passenger_number INT NOT NULL, image TINYBLOB NOT NULL, suitcase_number INT NOT NULL, state VARCHAR(200) NOT NULL, gearbox VARCHAR(200) NOT NULL, status VARCHAR(200) NOT NULL, INDEX IDX_1B80E486D79572D9 (model), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wilaya (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agency ADD CONSTRAINT FK_70C0C6E62D5B0234 FOREIGN KEY (city) REFERENCES city (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234CF6AF33B FOREIGN KEY (wilaya) REFERENCES wilaya (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859C7440455 FOREIGN KEY (client) REFERENCES client (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744E98F2859 FOREIGN KEY (contract) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D964C19C1 FOREIGN KEY (category) REFERENCES category (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D91C52F958 FOREIGN KEY (brand) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486D79572D9 FOREIGN KEY (model) REFERENCES model (id)');
        $this->addSql('ALTER TABLE client ADD city INT DEFAULT  NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404552D5B0234 FOREIGN KEY (city) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_C74404552D5B0234 ON client (city)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D91C52F958');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D964C19C1');
        $this->addSql('ALTER TABLE agency DROP FOREIGN KEY FK_70C0C6E62D5B0234');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404552D5B0234');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744E98F2859');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486D79572D9');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234CF6AF33B');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE wilaya');
        $this->addSql('DROP INDEX IDX_C74404552D5B0234 ON client');
        $this->addSql('ALTER TABLE client DROP city');
    }
}
