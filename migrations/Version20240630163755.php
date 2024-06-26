<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630163755 extends AbstractMigration
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
        $this->addSql('CREATE TABLE CAMPING (id_camping INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_camping VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, siret INTEGER NOT NULL, map BLOB DEFAULT NULL, password VARCHAR(1000) NOT NULL, mdp_vacancier VARCHAR(1000) DEFAULT NULL, is_verified BOOLEAN NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEECFAFEE7927C74 ON CAMPING (email)');
        $this->addSql('CREATE TABLE STRUCTURE (id_structure INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, nb_structure INTEGER NOT NULL, etat_structure VARCHAR(20) DEFAULT NULL, idCamping INTEGER NOT NULL, CONSTRAINT FK_2BC3290591106F16 FOREIGN KEY (idCamping) REFERENCES CAMPING (idCamping) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2BC3290591106F16 ON STRUCTURE (idCamping)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ACTIVITY');
        $this->addSql('DROP TABLE CAMPING');
        $this->addSql('DROP TABLE STRUCTURE');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
