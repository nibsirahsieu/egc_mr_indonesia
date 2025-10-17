<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251017063856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inquiry_file_uploaded (inquiry_id INT NOT NULL, file_uploaded_id INT NOT NULL, PRIMARY KEY(inquiry_id, file_uploaded_id))');
        $this->addSql('CREATE INDEX IDX_4EF800DDA7AD6D71 ON inquiry_file_uploaded (inquiry_id)');
        $this->addSql('CREATE INDEX IDX_4EF800DD41AE7A56 ON inquiry_file_uploaded (file_uploaded_id)');
        $this->addSql('ALTER TABLE inquiry_file_uploaded ADD CONSTRAINT FK_4EF800DDA7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inquiry_file_uploaded ADD CONSTRAINT FK_4EF800DD41AE7A56 FOREIGN KEY (file_uploaded_id) REFERENCES file_uploaded (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE download_whitepaper_request ALTER phone_number SET NOT NULL');
        $this->addSql('ALTER TABLE download_whitepaper_request ALTER message SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE inquiry_file_uploaded DROP CONSTRAINT FK_4EF800DDA7AD6D71');
        $this->addSql('ALTER TABLE inquiry_file_uploaded DROP CONSTRAINT FK_4EF800DD41AE7A56');
        $this->addSql('DROP TABLE inquiry_file_uploaded');
        $this->addSql('ALTER TABLE download_whitepaper_request ALTER phone_number DROP NOT NULL');
        $this->addSql('ALTER TABLE download_whitepaper_request ALTER message DROP NOT NULL');
    }
}
