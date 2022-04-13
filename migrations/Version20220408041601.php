<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220408041601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE financement_projet');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE financement_projet (financement_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_1E088F4EA737ED74 (financement_id), INDEX IDX_1E088F4EC18272 (projet_id), PRIMARY KEY(financement_id, projet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE financement_projet ADD CONSTRAINT FK_1E088F4EC18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE financement_projet ADD CONSTRAINT FK_1E088F4EA737ED74 FOREIGN KEY (financement_id) REFERENCES financement (id) ON DELETE CASCADE');
    }
}
