<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226122253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seccion_planificacion (id INT AUTO_INCREMENT NOT NULL, fecha_creacion DATE NOT NULL, estado INT NOT NULL, seccion_id INT NOT NULL, planificacion_id INT NOT NULL, clase_id INT NOT NULL, INDEX IDX_959E08247A5A413A (seccion_id), INDEX IDX_959E08244428E082 (planificacion_id), INDEX IDX_959E08249F720353 (clase_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE seccion_planificacion ADD CONSTRAINT FK_959E08247A5A413A FOREIGN KEY (seccion_id) REFERENCES seccion (id)');
        $this->addSql('ALTER TABLE seccion_planificacion ADD CONSTRAINT FK_959E08244428E082 FOREIGN KEY (planificacion_id) REFERENCES planificacion (id)');
        $this->addSql('ALTER TABLE seccion_planificacion ADD CONSTRAINT FK_959E08249F720353 FOREIGN KEY (clase_id) REFERENCES clases (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seccion_planificacion DROP FOREIGN KEY FK_959E08247A5A413A');
        $this->addSql('ALTER TABLE seccion_planificacion DROP FOREIGN KEY FK_959E08244428E082');
        $this->addSql('ALTER TABLE seccion_planificacion DROP FOREIGN KEY FK_959E08249F720353');
        $this->addSql('DROP TABLE seccion_planificacion');
    }
}
