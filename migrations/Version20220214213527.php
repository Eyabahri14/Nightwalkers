<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214213527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation CHANGE mode mode VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE duree duree VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tutorial CHANGE category category VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
