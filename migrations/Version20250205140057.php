<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205140057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sector ADD header_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sector ADD meta_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sector ADD meta_description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sector ADD CONSTRAINT FK_4BA3D9E88C782417 FOREIGN KEY (header_image_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4BA3D9E88C782417 ON sector (header_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sector DROP CONSTRAINT FK_4BA3D9E88C782417');
        $this->addSql('DROP INDEX IDX_4BA3D9E88C782417');
        $this->addSql('ALTER TABLE sector DROP header_image_id');
        $this->addSql('ALTER TABLE sector DROP meta_title');
        $this->addSql('ALTER TABLE sector DROP meta_description');
    }
}
