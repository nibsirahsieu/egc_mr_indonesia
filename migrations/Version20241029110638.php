<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029110638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_uploaded ADD used_by BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE file_uploaded DROP removeable');
        $this->addSql('CREATE INDEX file_uploaded_used_by_idx ON file_uploaded (used_by)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX file_uploaded_used_by_idx');
        $this->addSql('ALTER TABLE file_uploaded ADD removeable BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE file_uploaded DROP used_by');
    }
}
