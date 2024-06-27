<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627081253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ACTIVITY (id_activity INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, idCamping INTEGER NOT NULL, CONSTRAINT FK_6A47951691106F16 FOREIGN KEY (idCamping) REFERENCES CAMPING (idCamping) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6A47951691106F16 ON ACTIVITY (idCamping)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ACTIVITY');
    }
}
