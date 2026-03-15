<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260315091000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editeur CHANGE description description LONGTEXT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE commentaire commentaire LONGTEXT DEFAULT NULL, CHANGE idpays idpays INT NOT NULL');
        $this->addSql('ALTER TABLE editeur ADD CONSTRAINT FK_5A747EFE750CD0E FOREIGN KEY (idpays) REFERENCES pays (idpays)');
        $this->addSql('CREATE INDEX IDX_5A747EFE750CD0E ON editeur (idpays)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editeur DROP FOREIGN KEY FK_5A747EFE750CD0E');
        $this->addSql('DROP INDEX IDX_5A747EFE750CD0E ON editeur');
        $this->addSql('ALTER TABLE editeur CHANGE description description TEXT DEFAULT NULL, CHANGE image image VARCHAR(256) DEFAULT NULL, CHANGE commentaire commentaire TEXT DEFAULT NULL, CHANGE idpays idpays VARCHAR(45) DEFAULT NULL');
    }
}
