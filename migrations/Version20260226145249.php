<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226145249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planificacion ADD cohorte_id INT NOT NULL');
        $this->addSql('ALTER TABLE planificacion ADD CONSTRAINT FK_320E413DFB30EFA4 FOREIGN KEY (cohorte_id) REFERENCES cohorte (id)');
        $this->addSql('CREATE INDEX IDX_320E413DFB30EFA4 ON planificacion (cohorte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planificacion DROP FOREIGN KEY FK_320E413DFB30EFA4');
        $this->addSql('DROP INDEX IDX_320E413DFB30EFA4 ON planificacion');
        $this->addSql('ALTER TABLE planificacion DROP cohorte_id');
    }
}
