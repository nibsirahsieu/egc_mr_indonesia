<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112111425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE case_study_sector (case_study_id INT NOT NULL, sector_id INT NOT NULL, PRIMARY KEY(case_study_id, sector_id))');
        $this->addSql('CREATE INDEX IDX_DF3FD55B70CD7994 ON case_study_sector (case_study_id)');
        $this->addSql('CREATE INDEX IDX_DF3FD55BDE95C867 ON case_study_sector (sector_id)');
        $this->addSql('ALTER TABLE case_study_sector ADD CONSTRAINT FK_DF3FD55B70CD7994 FOREIGN KEY (case_study_id) REFERENCES case_study (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE case_study_sector ADD CONSTRAINT FK_DF3FD55BDE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE case_study_sector DROP CONSTRAINT FK_DF3FD55B70CD7994');
        $this->addSql('ALTER TABLE case_study_sector DROP CONSTRAINT FK_DF3FD55BDE95C867');
        $this->addSql('DROP TABLE case_study_sector');
    }
}
