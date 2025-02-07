<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108091932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE case_study (id SERIAL NOT NULL, image_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, client VARCHAR(500) DEFAULT NULL, issue TEXT DEFAULT NULL, solution TEXT DEFAULT NULL, approach TEXT DEFAULT NULL, recommendation TEXT DEFAULT NULL, engagement_roi TEXT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B4966DA53DA5256D ON case_study (image_id)');
        $this->addSql('CREATE TABLE cookieconsent_log (id SERIAL NOT NULL, ip_address VARCHAR(255) NOT NULL, consent_key VARCHAR(255) NOT NULL, cookie_name VARCHAR(255) NOT NULL, cookie_value VARCHAR(1024) NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE case_study ADD CONSTRAINT FK_B4966DA53DA5256D FOREIGN KEY (image_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE case_study DROP CONSTRAINT FK_B4966DA53DA5256D');
        $this->addSql('DROP TABLE case_study');
        $this->addSql('DROP TABLE cookieconsent_log');
    }
}
