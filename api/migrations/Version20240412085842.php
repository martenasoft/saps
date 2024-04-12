<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412085842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS feedback_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS page_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feedback (id INT NOT NULL, from_email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, text TEXT NOT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN feedback.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN feedback.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE menu (id INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) DEFAULT NULL, is_bottom_menu BOOLEAN NOT NULL, is_left_menu BOOLEAN NOT NULL, is_top_menu BOOLEAN NOT NULL, status SMALLINT NOT NULL, type SMALLINT NOT NULL, slug VARCHAR(255) NOT NULL, lft INT NOT NULL, rgt INT NOT NULL, lvl INT NOT NULL, tree INT NOT NULL, parent_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX lft ON menu (lft)');
        $this->addSql('CREATE INDEX lft_rgt ON menu (lft, rgt)');
        $this->addSql('CREATE INDEX id_lft_rgt ON menu (lft, rgt)');
        $this->addSql('CREATE INDEX is_bottom_menu ON menu (is_bottom_menu)');
        $this->addSql('CREATE INDEX is_left_menu ON menu (is_left_menu)');
        $this->addSql('CREATE INDEX is_top_menu ON menu (is_top_menu)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D053A93989D9B62B73E5EDC ON menu (slug, tree)');
        $this->addSql('COMMENT ON COLUMN menu.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN menu.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE page (id INT NOT NULL, menu_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, preview TEXT DEFAULT NULL, body TEXT DEFAULT NULL, position INT DEFAULT NULL, public_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, is_preview_on_main BOOLEAN NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, og_title VARCHAR(255) DEFAULT NULL, og_description VARCHAR(255) DEFAULT NULL, og_url VARCHAR(255) DEFAULT NULL, og_image VARCHAR(255) DEFAULT NULL, og_type VARCHAR(255) DEFAULT NULL, status SMALLINT NOT NULL, slug VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_140AB620CCD7E912 ON page (menu_id)');
        $this->addSql('COMMENT ON COLUMN page.public_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN page.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN page.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, status SMALLINT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON users (email)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB620CCD7E912');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE users');
    }
}
