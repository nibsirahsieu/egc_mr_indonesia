<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105090714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE download_whitepaper_request DROP CONSTRAINT FK_74340E57A7353814');
        $this->addSql('ALTER TABLE download_whitepaper_request ADD CONSTRAINT FK_74340E57A7353814 FOREIGN KEY (whitepaper_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE download_whitepaper_request DROP CONSTRAINT fk_74340e57a7353814');
        $this->addSql('ALTER TABLE download_whitepaper_request ADD CONSTRAINT fk_74340e57a7353814 FOREIGN KEY (whitepaper_id) REFERENCES file_uploaded (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
