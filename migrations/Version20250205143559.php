<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205143559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE our_service DROP CONSTRAINT fk_9669d25b8c782417');
        $this->addSql('DROP INDEX idx_9669d25b8c782417');
        $this->addSql('ALTER TABLE our_service DROP header_image_id');
        $this->addSql('ALTER TABLE our_service DROP description');
        $this->addSql('ALTER TABLE our_service DROP meta_title');
        $this->addSql('ALTER TABLE our_service DROP meta_description');
        $this->addSql('ALTER TABLE sector DROP CONSTRAINT fk_4ba3d9e88c782417');
        $this->addSql('DROP INDEX idx_4ba3d9e88c782417');
        $this->addSql('ALTER TABLE sector DROP header_image_id');
        $this->addSql('ALTER TABLE sector DROP meta_title');
        $this->addSql('ALTER TABLE sector DROP meta_description');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sector ADD header_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sector ADD meta_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sector ADD meta_description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sector ADD CONSTRAINT fk_4ba3d9e88c782417 FOREIGN KEY (header_image_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_4ba3d9e88c782417 ON sector (header_image_id)');
        $this->addSql('ALTER TABLE our_service ADD header_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE our_service ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE our_service ADD meta_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE our_service ADD meta_description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE our_service ADD CONSTRAINT fk_9669d25b8c782417 FOREIGN KEY (header_image_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9669d25b8c782417 ON our_service (header_image_id)');
    }
}
