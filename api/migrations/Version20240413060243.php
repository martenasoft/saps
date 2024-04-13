<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413060243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page DROP CONSTRAINT fk_140ab620ccd7e912');
        $this->addSql('DROP INDEX idx_140ab620ccd7e912');
        $this->addSql('ALTER TABLE page DROP menu_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_NAME ON page (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_NAME');
        $this->addSql('ALTER TABLE page ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT fk_140ab620ccd7e912 FOREIGN KEY (menu_id) REFERENCES menu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_140ab620ccd7e912 ON page (menu_id)');
    }
}
