<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027035800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_uploaded DROP CONSTRAINT fk_26eff9afae180419');
        $this->addSql('DROP INDEX idx_26eff9afae180419');
        $this->addSql('ALTER TABLE file_uploaded DROP blur_hash_image_id');
        $this->addSql('ALTER TABLE post ADD hash VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post DROP hash');
        $this->addSql('ALTER TABLE file_uploaded ADD blur_hash_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file_uploaded ADD CONSTRAINT fk_26eff9afae180419 FOREIGN KEY (blur_hash_image_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_26eff9afae180419 ON file_uploaded (blur_hash_image_id)');
    }
}
