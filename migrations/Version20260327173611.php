<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260327173611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet_personnage ADD CONSTRAINT FK_B9F4DA17F520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objet_personnage ADD CONSTRAINT FK_B9F4DA175E315342 FOREIGN KEY (personnage_id) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B9F4DA17F520CF5A ON objet_personnage (objet_id)');
        $this->addSql('ALTER TABLE objet_personnage RENAME INDEX fk_perso_id_idx TO IDX_B9F4DA175E315342');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet_personnage DROP FOREIGN KEY FK_B9F4DA17F520CF5A');
        $this->addSql('ALTER TABLE objet_personnage DROP FOREIGN KEY FK_B9F4DA175E315342');
        $this->addSql('DROP INDEX IDX_B9F4DA17F520CF5A ON objet_personnage');
        $this->addSql('ALTER TABLE objet_personnage RENAME INDEX idx_b9f4da175e315342 TO fk_perso_id_idx');
    }
}
