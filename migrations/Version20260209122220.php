<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209122220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipulo ADD direccion LONGTEXT DEFAULT NULL, ADD instruccion VARCHAR(150) DEFAULT NULL, ADD ocupacion VARCHAR(150) DEFAULT NULL, ADD discipulado VARCHAR(50) DEFAULT NULL, ADD area_servicio VARCHAR(150) DEFAULT NULL, ADD tiempo_asistencia VARCHAR(100) DEFAULT NULL, ADD fecha_registro DATE DEFAULT NULL, ADD observacion LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipulo DROP direccion, DROP instruccion, DROP ocupacion, DROP discipulado, DROP area_servicio, DROP tiempo_asistencia, DROP fecha_registro, DROP observacion');
    }
}
