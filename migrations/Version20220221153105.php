<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221153105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE panier_tutorial');
        $this->addSql('ALTER TABLE panier ADD formation_id INT DEFAULT NULL, ADD tutorial_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD qte INT NOT NULL, DROP total');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF25200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF289366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF25200282E ON panier (formation_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF289366B7B ON panier (tutorial_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2A76ED395 ON panier (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier_tutorial (panier_id INT NOT NULL, tutorial_id INT NOT NULL, INDEX IDX_5C2EF69BF77D927C (panier_id), INDEX IDX_5C2EF69B89366B7B (tutorial_id), PRIMARY KEY(panier_id, tutorial_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE panier_tutorial ADD CONSTRAINT FK_5C2EF69B89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_tutorial ADD CONSTRAINT FK_5C2EF69BF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF25200282E');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF289366B7B');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('DROP INDEX IDX_24CC0DF25200282E ON panier');
        $this->addSql('DROP INDEX IDX_24CC0DF289366B7B ON panier');
        $this->addSql('DROP INDEX IDX_24CC0DF2A76ED395 ON panier');
        $this->addSql('ALTER TABLE panier ADD total DOUBLE PRECISION NOT NULL, DROP formation_id, DROP tutorial_id, DROP user_id, DROP qte');
    }
}
