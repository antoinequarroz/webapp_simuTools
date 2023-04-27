<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427150831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material ADD titre VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD badge VARCHAR(255) NOT NULL, ADD salle VARCHAR(255) NOT NULL, ADD localite VARCHAR(255) NOT NULL, ADD nombre INT NOT NULL, ADD identifiant VARCHAR(255) NOT NULL, ADD updated_at DATE DEFAULT NULL, ADD id_class VARCHAR(255) NOT NULL, ADD slugs VARCHAR(255) NOT NULL, ADD possibilite VARCHAR(255) NOT NULL, ADD mode_emploi VARCHAR(255) NOT NULL, ADD caracteristique VARCHAR(255) NOT NULL, ADD liens VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material DROP titre, DROP description, DROP badge, DROP salle, DROP localite, DROP nombre, DROP identifiant, DROP updated_at, DROP id_class, DROP slugs, DROP possibilite, DROP mode_emploi, DROP caracteristique, DROP liens');
    }
}
