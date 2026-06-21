<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260327173921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet_image CHANGE objet_id objet_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE objet_image ADD CONSTRAINT FK_CA1F1FE2F520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id)');
        $this->addSql('ALTER TABLE objet_image RENAME INDEX fk_id_fig_idx TO IDX_CA1F1FE2F520CF5A');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet_image DROP FOREIGN KEY FK_CA1F1FE2F520CF5A');
        $this->addSql('ALTER TABLE objet_image CHANGE objet_id objet_id INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id, objet_id)');
        $this->addSql('ALTER TABLE objet_image RENAME INDEX idx_ca1f1fe2f520cf5a TO fk_id_fig_idx');
    }
}
