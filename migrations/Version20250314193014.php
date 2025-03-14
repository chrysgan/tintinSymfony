<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314193014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE editeur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, logo VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, pays VARCHAR(50) DEFAULT NULL, commentaires LONGTEXT DEFAULT NULL, annee_creation INT DEFAULT NULL, annee_fermeture INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membres (id INT AUTO_INCREMENT NOT NULL, date_creation DATE NOT NULL, nom VARCHAR(15) NOT NULL, mail VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, validation_envoye VARCHAR(45) NOT NULL, validation_recue VARCHAR(45) DEFAULT NULL, newsletter TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objet (id INT AUTO_INCREMENT NOT NULL, editeur_id INT DEFAULT NULL, serie_id INT DEFAULT NULL, type_id INT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, reference VARCHAR(50) DEFAULT NULL, taille DOUBLE PRECISION DEFAULT NULL, poids INT DEFAULT NULL, annee INT DEFAULT NULL, mois INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, possede TINYINT(1) NOT NULL, lieu_rangement VARCHAR(255) DEFAULT NULL, detail_possession LONGTEXT DEFAULT NULL, prix_possede DOUBLE PRECISION DEFAULT NULL, actif TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME NOT NULL, INDEX IDX_46CD4C383375BD21 (editeur_id), INDEX IDX_46CD4C38D94388BD (serie_id), INDEX IDX_46CD4C38C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objet_image (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, fichier VARCHAR(255) NOT NULL, ordre INT NOT NULL, INDEX IDX_CA1F1FE2F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objet_perso (id INT AUTO_INCREMENT NOT NULL, objet_id INT DEFAULT NULL, relation_id INT DEFAULT NULL, INDEX IDX_B240E19CF520CF5A (objet_id), INDEX IDX_B240E19C3256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, nom_en VARCHAR(45) NOT NULL, nom_fr VARCHAR(45) NOT NULL, alpha2 VARCHAR(2) NOT NULL, alpha3 VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnage (id INT AUTO_INCREMENT NOT NULL, alias VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, nom VARCHAR(50) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, genre VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, annee INT DEFAULT NULL, mois INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_objet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(25) NOT NULL, code VARCHAR(5) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C383375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C38D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C38C54C8C93 FOREIGN KEY (type_id) REFERENCES type_objet (id)');
        $this->addSql('ALTER TABLE objet_image ADD CONSTRAINT FK_CA1F1FE2F520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id)');
        $this->addSql('ALTER TABLE objet_perso ADD CONSTRAINT FK_B240E19CF520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id)');
        $this->addSql('ALTER TABLE objet_perso ADD CONSTRAINT FK_B240E19C3256915B FOREIGN KEY (relation_id) REFERENCES personnage (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C383375BD21');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C38D94388BD');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C38C54C8C93');
        $this->addSql('ALTER TABLE objet_image DROP FOREIGN KEY FK_CA1F1FE2F520CF5A');
        $this->addSql('ALTER TABLE objet_perso DROP FOREIGN KEY FK_B240E19CF520CF5A');
        $this->addSql('ALTER TABLE objet_perso DROP FOREIGN KEY FK_B240E19C3256915B');
        $this->addSql('DROP TABLE editeur');
        $this->addSql('DROP TABLE membres');
        $this->addSql('DROP TABLE objet');
        $this->addSql('DROP TABLE objet_image');
        $this->addSql('DROP TABLE objet_perso');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE personnage');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE type_objet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
