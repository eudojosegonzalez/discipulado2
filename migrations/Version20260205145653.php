<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205145653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planificacion (id INT AUTO_INCREMENT NOT NULL, fecha DATE NOT NULL, estado INT NOT NULL, observacion LONGTEXT DEFAULT NULL, leccion_id INT NOT NULL, aula_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_320E413DACFF9B5F (leccion_id), INDEX IDX_320E413DAD1A1255 (aula_id), INDEX IDX_320E413DDB38439E (usuario_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE planificacion ADD CONSTRAINT FK_320E413DACFF9B5F FOREIGN KEY (leccion_id) REFERENCES clases (id)');
        $this->addSql('ALTER TABLE planificacion ADD CONSTRAINT FK_320E413DAD1A1255 FOREIGN KEY (aula_id) REFERENCES aula (id)');
        $this->addSql('ALTER TABLE planificacion ADD CONSTRAINT FK_320E413DDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planificacion DROP FOREIGN KEY FK_320E413DACFF9B5F');
        $this->addSql('ALTER TABLE planificacion DROP FOREIGN KEY FK_320E413DAD1A1255');
        $this->addSql('ALTER TABLE planificacion DROP FOREIGN KEY FK_320E413DDB38439E');
        $this->addSql('DROP TABLE planificacion');
    }
}
