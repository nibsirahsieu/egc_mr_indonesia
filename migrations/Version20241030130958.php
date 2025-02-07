<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030130958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP CONSTRAINT fk_5a8a6c8d922726e9');
        $this->addSql('DROP INDEX idx_5a8a6c8d922726e9');
        $this->addSql('ALTER TABLE post RENAME COLUMN cover_id TO thumbnail_id');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DFDFF2E92 ON post (thumbnail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DFDFF2E92');
        $this->addSql('DROP INDEX IDX_5A8A6C8DFDFF2E92');
        $this->addSql('ALTER TABLE post RENAME COLUMN thumbnail_id TO cover_id');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT fk_5a8a6c8d922726e9 FOREIGN KEY (cover_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5a8a6c8d922726e9 ON post (cover_id)');
    }
}
