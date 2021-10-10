<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210727170503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455F5B7AF75');
        $this->addSql('DROP INDEX UNIQ_C7440455F5B7AF75 ON client');
        $this->addSql('ALTER TABLE client DROP address_id');
        $this->addSql('ALTER TABLE visit ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_437EE939F5B7AF75 ON visit (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455F5B7AF75 ON client (address_id)');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE939F5B7AF75');
        $this->addSql('DROP INDEX IDX_437EE939F5B7AF75 ON visit');
        $this->addSql('ALTER TABLE visit DROP address_id');
    }
}
