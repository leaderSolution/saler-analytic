<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115134121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, full_address VARCHAR(55) NOT NULL, city VARCHAR(55) NOT NULL, street VARCHAR(55) NOT NULL, zip VARCHAR(55) NOT NULL, country VARCHAR(55) DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, code_uniq VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, tax_registration_num VARCHAR(255) DEFAULT NULL, deadline INT DEFAULT NULL, turnover DOUBLE PRECISION DEFAULT NULL, is_prospect TINYINT(1) DEFAULT NULL, INDEX IDX_C7440455A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE goal (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, nb_visits INT DEFAULT NULL, period INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_FCDCEB2E19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, nb_visits_day INT DEFAULT NULL, turnover_target DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visit (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, address_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, background_color VARCHAR(255) DEFAULT NULL, border_color VARCHAR(255) DEFAULT NULL, text_color VARCHAR(255) DEFAULT NULL, all_day TINYINT(1) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, is_done TINYINT(1) DEFAULT \'0\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, types LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_437EE93919EB6921 (client_id), INDEX IDX_437EE939A76ED395 (user_id), INDEX IDX_437EE939F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE `users` ADD CONSTRAINT FK_1483A5E9F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE93919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `users` DROP FOREIGN KEY FK_1483A5E9F5B7AF75');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE939F5B7AF75');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2E19EB6921');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE93919EB6921');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE939A76ED395');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE `users`');
        $this->addSql('DROP TABLE visit');
    }
}
