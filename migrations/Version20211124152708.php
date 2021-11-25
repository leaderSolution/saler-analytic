<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211124152708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `leave` ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE `leave` ADD CONSTRAINT FK_9BB080D0A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('CREATE INDEX IDX_9BB080D0A76ED395 ON `leave` (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `leave` DROP FOREIGN KEY FK_9BB080D0A76ED395');
        $this->addSql('DROP INDEX IDX_9BB080D0A76ED395 ON `leave`');
        $this->addSql('ALTER TABLE `leave` DROP user_id');
    }
}
