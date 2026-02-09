<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209141928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detalle_planificacion (id INT AUTO_INCREMENT NOT NULL, estado INT NOT NULL, planificacion_id INT NOT NULL, discipulo_id INT NOT NULL, INDEX IDX_E8A7B9D24428E082 (planificacion_id), INDEX IDX_E8A7B9D2DD2C2CAD (discipulo_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE detalle_planificacion ADD CONSTRAINT FK_E8A7B9D24428E082 FOREIGN KEY (planificacion_id) REFERENCES planificacion (id)');
        $this->addSql('ALTER TABLE detalle_planificacion ADD CONSTRAINT FK_E8A7B9D2DD2C2CAD FOREIGN KEY (discipulo_id) REFERENCES discipulo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_planificacion DROP FOREIGN KEY FK_E8A7B9D24428E082');
        $this->addSql('ALTER TABLE detalle_planificacion DROP FOREIGN KEY FK_E8A7B9D2DD2C2CAD');
        $this->addSql('DROP TABLE detalle_planificacion');
    }
}
