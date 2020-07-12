<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200708134152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode CHANGE season_id season_id INT NOT NULL');
        $this->addSql('ALTER TABLE program RENAME INDEX fk_64c19c13eb8070a TO IDX_92ED778412469DE2');
        $this->addSql('ALTER TABLE season CHANGE program_id program_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode CHANGE season_id season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE program RENAME INDEX idx_92ed778412469de2 TO FK_64C19C13EB8070A');
        $this->addSql('ALTER TABLE season CHANGE program_id program_id INT DEFAULT NULL');
    }
}
