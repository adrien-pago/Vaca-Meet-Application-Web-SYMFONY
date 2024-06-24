<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240624120845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__CAMPING AS SELECT id, nom_camping, email, siret, map, password, mdp_vacancier, is_verified, roles FROM CAMPING');
        $this->addSql('DROP TABLE CAMPING');
        $this->addSql('CREATE TABLE CAMPING (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_camping VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, siret INTEGER NOT NULL, map BLOB DEFAULT NULL, password VARCHAR(1000) NOT NULL, mdp_vacancier VARCHAR(1000) NOT NULL, is_verified BOOLEAN NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO CAMPING (id, nom_camping, email, siret, map, password, mdp_vacancier, is_verified, roles) SELECT id, nom_camping, email, siret, map, password, mdp_vacancier, is_verified, roles FROM __temp__CAMPING');
        $this->addSql('DROP TABLE __temp__CAMPING');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEECFAFEE7927C74 ON CAMPING (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__CAMPING AS SELECT id, nom_camping, email, siret, map, password, mdp_vacancier, is_verified, roles FROM CAMPING');
        $this->addSql('DROP TABLE CAMPING');
        $this->addSql('CREATE TABLE CAMPING (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_camping VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, siret INTEGER NOT NULL, map BLOB DEFAULT NULL, password VARCHAR(1000) NOT NULL, mdp_vacancier VARCHAR(1000) DEFAULT NULL, is_verified BOOLEAN NOT NULL, roles CLOB NOT NULL, token_confirm VARCHAR(250) DEFAULT NULL)');
        $this->addSql('INSERT INTO CAMPING (id, nom_camping, email, siret, map, password, mdp_vacancier, is_verified, roles) SELECT id, nom_camping, email, siret, map, password, mdp_vacancier, is_verified, roles FROM __temp__CAMPING');
        $this->addSql('DROP TABLE __temp__CAMPING');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEECFAFEE7927C74 ON CAMPING (email)');
    }
}
