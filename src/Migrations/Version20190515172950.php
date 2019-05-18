<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190515172950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE boite_postale boite_postale VARCHAR(255) DEFAULT NULL, CHANGE site site VARCHAR(255) DEFAULT NULL, CHANGE logo logo INT DEFAULT NULL, CHANGE secteur_activiter secteur_activiter VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE station CHANGE nombre_etudiant nombre_etudiant INT DEFAULT NULL, CHANGE fichier fichier VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE station CHANGE nombre_etudiant nombre_etudiant INT DEFAULT NULL, CHANGE fichier fichier VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE boite_postale boite_postale VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE site site VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE logo logo INT DEFAULT NULL, CHANGE secteur_activiter secteur_activiter VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
