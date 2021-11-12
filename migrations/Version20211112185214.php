<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211112185214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE goal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "users_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE visit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, full_address VARCHAR(55) NOT NULL, city VARCHAR(55) NOT NULL, street VARCHAR(55) NOT NULL, zip VARCHAR(55) NOT NULL, country VARCHAR(55) DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, user_id INT DEFAULT NULL, code_uniq VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, tax_registration_num VARCHAR(255) DEFAULT NULL, deadline INT DEFAULT NULL, turnover DOUBLE PRECISION DEFAULT NULL, is_prospect BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C7440455A76ED395 ON client (user_id)');
        $this->addSql('CREATE TABLE goal (id INT NOT NULL, client_id INT NOT NULL, nb_visits INT DEFAULT NULL, period INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E19EB6921 ON goal (client_id)');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE "users" (id INT NOT NULL, address_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, nb_visits_day INT DEFAULT NULL, turnover_target DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON "users" (email)');
        $this->addSql('CREATE INDEX IDX_1483A5E9F5B7AF75 ON "users" (address_id)');
        $this->addSql('CREATE TABLE visit (id INT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, address_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, background_color VARCHAR(255) DEFAULT NULL, border_color VARCHAR(255) DEFAULT NULL, text_color VARCHAR(255) DEFAULT NULL, all_day BOOLEAN DEFAULT NULL, active BOOLEAN DEFAULT NULL, comment TEXT DEFAULT NULL, is_done BOOLEAN DEFAULT \'false\', created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, types TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_437EE93919EB6921 ON visit (client_id)');
        $this->addSql('CREATE INDEX IDX_437EE939A76ED395 ON visit (user_id)');
        $this->addSql('CREATE INDEX IDX_437EE939F5B7AF75 ON visit (address_id)');
        $this->addSql('COMMENT ON COLUMN visit.types IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "users" ADD CONSTRAINT FK_1483A5E9F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE93919EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "users" DROP CONSTRAINT FK_1483A5E9F5B7AF75');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE939F5B7AF75');
        $this->addSql('ALTER TABLE goal DROP CONSTRAINT FK_FCDCEB2E19EB6921');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE93919EB6921');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE939A76ED395');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE goal_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "users_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE visit_id_seq CASCADE');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE "users"');
        $this->addSql('DROP TABLE visit');
    }
}
