<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709141457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE PLANNING (id_planning INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle_activity VARCHAR(100) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, idCamping INTEGER NOT NULL, CONSTRAINT FK_12AA23BA91106F16 FOREIGN KEY (idCamping) REFERENCES CAMPING (id_camping) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_12AA23BA91106F16 ON PLANNING (idCamping)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ACTIVITY AS SELECT id_activity, libelle, idCamping FROM ACTIVITY');
        $this->addSql('DROP TABLE ACTIVITY');
        $this->addSql('CREATE TABLE ACTIVITY (id_activity INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, idCamping INTEGER NOT NULL, CONSTRAINT FK_6A47951691106F16 FOREIGN KEY (idCamping) REFERENCES CAMPING (id_camping) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ACTIVITY (id_activity, libelle, idCamping) SELECT id_activity, libelle, idCamping FROM __temp__ACTIVITY');
        $this->addSql('DROP TABLE __temp__ACTIVITY');
        $this->addSql('CREATE INDEX IDX_6A47951691106F16 ON ACTIVITY (idCamping)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__STRUCTURE AS SELECT id_structure, libelle, nb_structure, etat_structure, idCamping FROM STRUCTURE');
        $this->addSql('DROP TABLE STRUCTURE');
        $this->addSql('CREATE TABLE STRUCTURE (id_structure INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, nb_structure INTEGER NOT NULL, etat_structure VARCHAR(20) DEFAULT NULL, idCamping INTEGER NOT NULL, CONSTRAINT FK_2BC3290591106F16 FOREIGN KEY (idCamping) REFERENCES CAMPING (id_camping) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO STRUCTURE (id_structure, libelle, nb_structure, etat_structure, idCamping) SELECT id_structure, libelle, nb_structure, etat_structure, idCamping FROM __temp__STRUCTURE');
        $this->addSql('DROP TABLE __temp__STRUCTURE');
        $this->addSql('CREATE INDEX IDX_2BC3290591106F16 ON STRUCTURE (idCamping)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE PLANNING');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ACTIVITY AS SELECT id_activity, libelle, idCamping FROM ACTIVITY');
        $this->addSql('DROP TABLE ACTIVITY');
        $this->addSql('CREATE TABLE ACTIVITY (id_activity INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, idCamping INTEGER NOT NULL, CONSTRAINT FK_6A47951691106F16 FOREIGN KEY (idCamping) REFERENCES CAMPING (idCamping) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ACTIVITY (id_activity, libelle, idCamping) SELECT id_activity, libelle, idCamping FROM __temp__ACTIVITY');
        $this->addSql('DROP TABLE __temp__ACTIVITY');
        $this->addSql('CREATE INDEX IDX_6A47951691106F16 ON ACTIVITY (idCamping)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__STRUCTURE AS SELECT id_structure, libelle, nb_structure, etat_structure, idCamping FROM STRUCTURE');
        $this->addSql('DROP TABLE STRUCTURE');
        $this->addSql('CREATE TABLE STRUCTURE (id_structure INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, nb_structure INTEGER NOT NULL, etat_structure VARCHAR(20) DEFAULT NULL, idCamping INTEGER NOT NULL, CONSTRAINT FK_2BC3290591106F16 FOREIGN KEY (idCamping) REFERENCES CAMPING (idCamping) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO STRUCTURE (id_structure, libelle, nb_structure, etat_structure, idCamping) SELECT id_structure, libelle, nb_structure, etat_structure, idCamping FROM __temp__STRUCTURE');
        $this->addSql('DROP TABLE __temp__STRUCTURE');
        $this->addSql('CREATE INDEX IDX_2BC3290591106F16 ON STRUCTURE (idCamping)');
    }
}
