<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022112627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_uploaded ADD extension VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE file_uploaded ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE file_uploaded ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE post ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D93CB796C FOREIGN KEY (file_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D93CB796C ON post (file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE file_uploaded DROP extension');
        $this->addSql('ALTER TABLE file_uploaded DROP created_at');
        $this->addSql('ALTER TABLE file_uploaded DROP updated_at');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D93CB796C');
        $this->addSql('DROP INDEX IDX_5A8A6C8D93CB796C');
        $this->addSql('ALTER TABLE post DROP file_id');
    }
}
