<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221008142325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, carton INT DEFAULT NULL, boite INT DEFAULT NULL, plaquette INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medicament ADD price_unit_boite NUMERIC(10, 2) NOT NULL, ADD price_unit_carton NUMERIC(10, 2) NOT NULL, ADD price_unit_plaquette NUMERIC(10, 2) NOT NULL, CHANGE date_peremp date_peremp DATE NOT NULL');
        $this->addSql('ALTER TABLE transaction_line ADD qty_catton INT NOT NULL, ADD qty_boite INT NOT NULL, ADD plaquette INT NOT NULL, CHANGE price price NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE test');
        $this->addSql('ALTER TABLE medicament DROP price_unit_boite, DROP price_unit_carton, DROP price_unit_plaquette, CHANGE date_peremp date_peremp DATETIME NOT NULL');
        $this->addSql('ALTER TABLE transaction_line DROP qty_catton, DROP qty_boite, DROP plaquette, CHANGE price price NUMERIC(10, 2) NOT NULL');
    }
}
