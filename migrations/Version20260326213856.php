<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260326213856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editeur CHANGE description description LONGTEXT DEFAULT NULL, CHANGE commentaire commentaire LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE editeur ADD CONSTRAINT FK_5A747EFA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('CREATE INDEX IDX_5A747EFA6E44244 ON editeur (pays_id)');
        $this->addSql('ALTER TABLE objet CHANGE taille taille NUMERIC(6, 2) DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE prix prix NUMERIC(6, 2) DEFAULT \'-1.00\', CHANGE editeur_id editeur_id INT DEFAULT NULL, CHANGE categorie_id categorie_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE montant_achat montant_achat NUMERIC(6, 2) DEFAULT NULL, CHANGE description_possession description_possession LONGTEXT DEFAULT NULL, CHANGE actif actif TINYINT DEFAULT false');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C38D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C383375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C38BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_46CD4C38D94388BD ON objet (serie_id)');
        $this->addSql('CREATE INDEX IDX_46CD4C383375BD21 ON objet (editeur_id)');
        $this->addSql('CREATE INDEX IDX_46CD4C38BCF5E72D ON objet (categorie_id)');
        $this->addSql('ALTER TABLE personnage CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE serie CHANGE description description LONGTEXT DEFAULT NULL, CHANGE commentaire commentaire LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editeur DROP FOREIGN KEY FK_5A747EFA6E44244');
        $this->addSql('DROP INDEX IDX_5A747EFA6E44244 ON editeur');
        $this->addSql('ALTER TABLE editeur CHANGE description description TEXT DEFAULT NULL, CHANGE commentaire commentaire TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C38D94388BD');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C383375BD21');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C38BCF5E72D');
        $this->addSql('DROP INDEX IDX_46CD4C38D94388BD ON objet');
        $this->addSql('DROP INDEX IDX_46CD4C383375BD21 ON objet');
        $this->addSql('DROP INDEX IDX_46CD4C38BCF5E72D ON objet');
        $this->addSql('ALTER TABLE objet CHANGE taille taille DOUBLE PRECISION DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE prix prix DOUBLE PRECISION DEFAULT \'-1.00\', CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'
        \', CHANGE montant_achat montant_achat FLOAT DEFAULT NULL, CHANGE actif actif TINYINT DEFAULT 0 NOT NULL, CHANGE description_possession description_possession TEXT DEFAULT NULL, CHANGE editeur_id editeur_id INT NOT NULL, CHANGE categorie_id categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE personnage CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE serie CHANGE description description TEXT DEFAULT NULL, CHANGE commentaire commentaire TEXT DEFAULT NULL');
    }
}
