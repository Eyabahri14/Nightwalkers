<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214211432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_tutorial (panier_id INT NOT NULL, tutorial_id INT NOT NULL, INDEX IDX_5C2EF69BF77D927C (panier_id), INDEX IDX_5C2EF69B89366B7B (tutorial_id), PRIMARY KEY(panier_id, tutorial_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier_tutorial ADD CONSTRAINT FK_5C2EF69BF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_tutorial ADD CONSTRAINT FK_5C2EF69B89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial ADD panier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tutorial ADD CONSTRAINT FK_C66BFFE9F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE INDEX IDX_C66BFFE9F77D927C ON tutorial (panier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_tutorial DROP FOREIGN KEY FK_5C2EF69BF77D927C');
        $this->addSql('ALTER TABLE tutorial DROP FOREIGN KEY FK_C66BFFE9F77D927C');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_tutorial');
        $this->addSql('ALTER TABLE formation CHANGE mode mode VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE duree duree VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_C66BFFE9F77D927C ON tutorial');
        $this->addSql('ALTER TABLE tutorial DROP panier_id, CHANGE category category VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
