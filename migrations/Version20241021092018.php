<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021092018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, summary VARCHAR(255) DEFAULT NULL, content TEXT NOT NULL, published_at DATE DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN post.published_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE post_sector (post_id INT NOT NULL, sector_id INT NOT NULL, PRIMARY KEY(post_id, sector_id))');
        $this->addSql('CREATE INDEX IDX_7B813D8D4B89032C ON post_sector (post_id)');
        $this->addSql('CREATE INDEX IDX_7B813D8DDE95C867 ON post_sector (sector_id)');
        $this->addSql('CREATE TABLE sector (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE post_sector ADD CONSTRAINT FK_7B813D8D4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_sector ADD CONSTRAINT FK_7B813D8DDE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post_sector DROP CONSTRAINT FK_7B813D8D4B89032C');
        $this->addSql('ALTER TABLE post_sector DROP CONSTRAINT FK_7B813D8DDE95C867');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_sector');
        $this->addSql('DROP TABLE sector');
    }
}
