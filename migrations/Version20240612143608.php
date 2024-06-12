<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612141506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Création de la table camping
        $this->addSql('CREATE TABLE camping (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom_camping VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            siret VARCHAR(14) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            rgpd_accepted BOOLEAN NOT NULL,
            is_active BOOLEAN NOT NULL
        )');

    }

    public function down(Schema $schema): void
    {
        // Suppression de la table camping
        $this->addSql('DROP TABLE camping');
        
        // Suppression de la table vacancier
        $this->addSql('DROP TABLE vacancier');
    }
}
