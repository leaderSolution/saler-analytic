<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210716000441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8119EB6921');
        $this->addSql('DROP INDEX IDX_D4E6F8119EB6921 ON address');
        $this->addSql('ALTER TABLE address DROP client_id, CHANGE city city VARCHAR(55) NOT NULL, CHANGE street street VARCHAR(55) NOT NULL, CHANGE zip zip VARCHAR(55) NOT NULL, CHANGE country country VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE client ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455F5B7AF75 ON client (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD client_id INT DEFAULT NULL, CHANGE city city VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE street street VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE zip zip VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8119EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D4E6F8119EB6921 ON address (client_id)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455F5B7AF75');
        $this->addSql('DROP INDEX UNIQ_C7440455F5B7AF75 ON client');
        $this->addSql('ALTER TABLE client DROP address_id');
    }
}
