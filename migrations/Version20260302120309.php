<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260302120309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planificacion ADD seccion_id INT NOT NULL');
        $this->addSql('ALTER TABLE planificacion ADD CONSTRAINT FK_320E413DFB30EFA4 FOREIGN KEY (cohorte_id) REFERENCES cohorte (id)');
        $this->addSql('ALTER TABLE planificacion ADD CONSTRAINT FK_320E413D7A5A413A FOREIGN KEY (seccion_id) REFERENCES seccion (id)');
        $this->addSql('CREATE INDEX IDX_320E413DFB30EFA4 ON planificacion (cohorte_id)');
        $this->addSql('CREATE INDEX IDX_320E413D7A5A413A ON planificacion (seccion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planificacion DROP FOREIGN KEY FK_320E413DFB30EFA4');
        $this->addSql('ALTER TABLE planificacion DROP FOREIGN KEY FK_320E413D7A5A413A');
        $this->addSql('DROP INDEX IDX_320E413DFB30EFA4 ON planificacion');
        $this->addSql('DROP INDEX IDX_320E413D7A5A413A ON planificacion');
        $this->addSql('ALTER TABLE planificacion DROP seccion_id');
    }
}
