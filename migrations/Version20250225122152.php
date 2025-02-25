<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225122152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE header_footer_script ALTER header_script TYPE TEXT');
        $this->addSql('ALTER TABLE header_footer_script ALTER header_script TYPE TEXT');
        $this->addSql('ALTER TABLE header_footer_script ALTER footer_script TYPE TEXT');
        $this->addSql('ALTER TABLE header_footer_script ALTER footer_script TYPE TEXT');
        $this->addSql('ALTER TABLE post_type ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX post_type_slug_unique ON post_type (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE header_footer_script ALTER header_script TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE header_footer_script ALTER footer_script TYPE VARCHAR(1000)');
        $this->addSql('DROP INDEX post_type_slug_unique');
        $this->addSql('ALTER TABLE post_type DROP slug');
    }
}
