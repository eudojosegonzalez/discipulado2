<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260302135328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asistencia (id INT AUTO_INCREMENT NOT NULL, fecha_reg DATETIME NOT NULL, planificacion_id INT NOT NULL, discipulo_id INT NOT NULL, clase_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_D8264A8D4428E082 (planificacion_id), INDEX IDX_D8264A8DDD2C2CAD (discipulo_id), INDEX IDX_D8264A8D9F720353 (clase_id), INDEX IDX_D8264A8DDB38439E (usuario_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE asistencia ADD CONSTRAINT FK_D8264A8D4428E082 FOREIGN KEY (planificacion_id) REFERENCES planificacion (id)');
        $this->addSql('ALTER TABLE asistencia ADD CONSTRAINT FK_D8264A8DDD2C2CAD FOREIGN KEY (discipulo_id) REFERENCES discipulo (id)');
        $this->addSql('ALTER TABLE asistencia ADD CONSTRAINT FK_D8264A8D9F720353 FOREIGN KEY (clase_id) REFERENCES clases (id)');
        $this->addSql('ALTER TABLE asistencia ADD CONSTRAINT FK_D8264A8DDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE planificacion ADD CONSTRAINT FK_320E413D7A5A413A FOREIGN KEY (seccion_id) REFERENCES seccion (id)');
        $this->addSql('CREATE INDEX IDX_320E413D7A5A413A ON planificacion (seccion_id)');
        $this->addSql('ALTER TABLE planificacion RENAME INDEX fk_320e413dfb30efa4 TO IDX_320E413DFB30EFA4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asistencia DROP FOREIGN KEY FK_D8264A8D4428E082');
        $this->addSql('ALTER TABLE asistencia DROP FOREIGN KEY FK_D8264A8DDD2C2CAD');
        $this->addSql('ALTER TABLE asistencia DROP FOREIGN KEY FK_D8264A8D9F720353');
        $this->addSql('ALTER TABLE asistencia DROP FOREIGN KEY FK_D8264A8DDB38439E');
        $this->addSql('DROP TABLE asistencia');
        $this->addSql('ALTER TABLE planificacion DROP FOREIGN KEY FK_320E413D7A5A413A');
        $this->addSql('DROP INDEX IDX_320E413D7A5A413A ON planificacion');
        $this->addSql('ALTER TABLE planificacion RENAME INDEX idx_320e413dfb30efa4 TO FK_320E413DFB30EFA4');
    }
}
