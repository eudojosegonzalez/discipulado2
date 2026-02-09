<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260130114138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clases ADD modulo_id INT NOT NULL');
        $this->addSql('ALTER TABLE clases ADD CONSTRAINT FK_67CBBF10C07F55F5 FOREIGN KEY (modulo_id) REFERENCES modulo (id)');
        $this->addSql('CREATE INDEX IDX_67CBBF10C07F55F5 ON clases (modulo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clases DROP FOREIGN KEY FK_67CBBF10C07F55F5');
        $this->addSql('DROP INDEX IDX_67CBBF10C07F55F5 ON clases');
        $this->addSql('ALTER TABLE clases DROP modulo_id');
    }
}
