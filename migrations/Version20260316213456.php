<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316213456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C387C3FFF2D FOREIGN KEY (idserie) REFERENCES serie (idserie)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C38DAA31581 FOREIGN KEY (idediteur) REFERENCES editeur (idediteur)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C3837667FC1 FOREIGN KEY (idcategorie) REFERENCES categorie (idcategorie)');
        $this->addSql('ALTER TABLE objet RENAME INDEX fk_series_idx TO IDX_46CD4C387C3FFF2D');
        $this->addSql('ALTER TABLE objet RENAME INDEX fk_editeurs_idx TO IDX_46CD4C38DAA31581');
        $this->addSql('ALTER TABLE objet RENAME INDEX fk_type_idx TO IDX_46CD4C3837667FC1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C387C3FFF2D');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C38DAA31581');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C3837667FC1');
        $this->addSql('ALTER TABLE objet RENAME INDEX idx_46cd4c387c3fff2d TO fk_series_idx');
        $this->addSql('ALTER TABLE objet RENAME INDEX idx_46cd4c38daa31581 TO fk_editeurs_idx');
        $this->addSql('ALTER TABLE objet RENAME INDEX idx_46cd4c3837667fc1 TO fk_type_idx');
    }
}
