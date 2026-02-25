<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218150352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seccion_alumno (id INT AUTO_INCREMENT NOT NULL, fecha_creacion DATE NOT NULL, estado INT NOT NULL, seccion_id INT NOT NULL, discipulo_id INT NOT NULL, INDEX IDX_D9B0F6437A5A413A (seccion_id), INDEX IDX_D9B0F643DD2C2CAD (discipulo_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE seccion_alumno ADD CONSTRAINT FK_D9B0F6437A5A413A FOREIGN KEY (seccion_id) REFERENCES seccion (id)');
        $this->addSql('ALTER TABLE seccion_alumno ADD CONSTRAINT FK_D9B0F643DD2C2CAD FOREIGN KEY (discipulo_id) REFERENCES discipulo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seccion_alumno DROP FOREIGN KEY FK_D9B0F6437A5A413A');
        $this->addSql('ALTER TABLE seccion_alumno DROP FOREIGN KEY FK_D9B0F643DD2C2CAD');
        $this->addSql('DROP TABLE seccion_alumno');
    }
}
