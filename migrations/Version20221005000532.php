<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221005000532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, contact INT NOT NULL, type VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, surname VARCHAR(255) DEFAULT NULL, is_entreprise TINYINT(1) NOT NULL, entreprise_name VARCHAR(255) DEFAULT NULL, other_field VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, date_peremp DATETIME NOT NULL, cible VARCHAR(255) NOT NULL, mode_conso LONGTEXT NOT NULL, note VARCHAR(255) DEFAULT NULL, stock INT NOT NULL, effet_secondaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, id_contact_id INT NOT NULL, date_transaction DATETIME NOT NULL, type VARCHAR(255) NOT NULL, price_total NUMERIC(5, 2) NOT NULL, status VARCHAR(255) NOT NULL, status_payment VARCHAR(255) NOT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_723705D1422BA59D (id_contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_medicament (transaction_id INT NOT NULL, medicament_id INT NOT NULL, INDEX IDX_750A458B2FC0CB0F (transaction_id), INDEX IDX_750A458BAB0D61F7 (medicament_id), PRIMARY KEY(transaction_id, medicament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_line (id INT AUTO_INCREMENT NOT NULL, id_transaction_id INT NOT NULL, id_medicament_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, qty INT NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_33578A5712A67609 (id_transaction_id), INDEX IDX_33578A571525B092 (id_medicament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1422BA59D FOREIGN KEY (id_contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE transaction_medicament ADD CONSTRAINT FK_750A458B2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_medicament ADD CONSTRAINT FK_750A458BAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_line ADD CONSTRAINT FK_33578A5712A67609 FOREIGN KEY (id_transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE transaction_line ADD CONSTRAINT FK_33578A571525B092 FOREIGN KEY (id_medicament_id) REFERENCES medicament (id)');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(180) DEFAULT NULL, ADD contact INT DEFAULT NULL, ADD address VARCHAR(180) DEFAULT NULL, ADD custom_field LONGTEXT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495E237E06 ON user (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494C62E638 ON user (contact)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1422BA59D');
        $this->addSql('ALTER TABLE transaction_medicament DROP FOREIGN KEY FK_750A458BAB0D61F7');
        $this->addSql('ALTER TABLE transaction_line DROP FOREIGN KEY FK_33578A571525B092');
        $this->addSql('ALTER TABLE transaction_medicament DROP FOREIGN KEY FK_750A458B2FC0CB0F');
        $this->addSql('ALTER TABLE transaction_line DROP FOREIGN KEY FK_33578A5712A67609');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_medicament');
        $this->addSql('DROP TABLE transaction_line');
        $this->addSql('DROP INDEX UNIQ_8D93D6495E237E06 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6494C62E638 ON user');
        $this->addSql('ALTER TABLE user DROP name, DROP contact, DROP address, DROP custom_field');
    }
}
