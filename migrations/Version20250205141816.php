<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205141816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meta_page ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE meta_page ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE meta_page DROP type');
        $this->addSql('CREATE UNIQUE INDEX meta_page_slug_unique ON meta_page (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX meta_page_slug_unique');
        $this->addSql('ALTER TABLE meta_page ADD type INT NOT NULL');
        $this->addSql('ALTER TABLE meta_page DROP name');
        $this->addSql('ALTER TABLE meta_page DROP slug');
    }
}
