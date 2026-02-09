<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205125449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discipulo (id INT AUTO_INCREMENT NOT NULL, cedula VARCHAR(50) NOT NULL, nombre VARCHAR(150) NOT NULL, fecha_nac DATE DEFAULT NULL, sexo INT NOT NULL, email VARCHAR(255) DEFAULT NULL, telefono VARCHAR(50) DEFAULT NULL, estado INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        //$this->addSql('ALTER TABLE clases ADD CONSTRAINT FK_67CBBF10C07F55F5 FOREIGN KEY (modulo_id) REFERENCES modulo (id)');
        //$this->addSql('CREATE INDEX IDX_67CBBF10C07F55F5 ON clases (modulo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE discipulo');
        //$this->addSql('ALTER TABLE clases DROP FOREIGN KEY FK_67CBBF10C07F55F5');
        //$this->addSql('DROP INDEX IDX_67CBBF10C07F55F5 ON clases');
    }
}
