<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531082030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note_commentaire (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, video_id INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, note INT DEFAULT NULL, INDEX IDX_4149FF39FB88E14F (utilisateur_id), INDEX IDX_4149FF3929C1004E (video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_commentaire ADD CONSTRAINT FK_4149FF39FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE note_commentaire ADD CONSTRAINT FK_4149FF3929C1004E FOREIGN KEY (video_id) REFERENCES formation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_commentaire DROP FOREIGN KEY FK_4149FF39FB88E14F');
        $this->addSql('ALTER TABLE note_commentaire DROP FOREIGN KEY FK_4149FF3929C1004E');
        $this->addSql('DROP TABLE note_commentaire');
        $this->addSql('DROP TABLE utilisateurs');
    }
}
