<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021113324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_uploaded ADD blur_hash_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file_uploaded ADD CONSTRAINT FK_26EFF9AFAE180419 FOREIGN KEY (blur_hash_image_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_26EFF9AFAE180419 ON file_uploaded (blur_hash_image_id)');
        $this->addSql('ALTER TABLE post ADD header_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D8C782417 FOREIGN KEY (header_image_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D8C782417 ON post (header_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D8C782417');
        $this->addSql('DROP INDEX IDX_5A8A6C8D8C782417');
        $this->addSql('ALTER TABLE post DROP header_image_id');
        $this->addSql('ALTER TABLE file_uploaded DROP CONSTRAINT FK_26EFF9AFAE180419');
        $this->addSql('DROP INDEX IDX_26EFF9AFAE180419');
        $this->addSql('ALTER TABLE file_uploaded DROP blur_hash_image_id');
    }
}
