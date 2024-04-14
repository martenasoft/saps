<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414110101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu RENAME INDEX uniq_7d053a93989d9b62b73e5edc TO UNIQ_IDENTIFIER_MENU_SLUG_TREEE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu RENAME INDEX uniq_identifier_menu_slug_treee TO UNIQ_7D053A93989D9B62B73E5EDC');
    }
}
