<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211012183903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goal ADD client_id INT NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E19EB6921 ON goal (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2E19EB6921');
        $this->addSql('DROP INDEX IDX_FCDCEB2E19EB6921 ON goal');
        $this->addSql('ALTER TABLE goal DROP client_id, DROP created_at');
    }
}
