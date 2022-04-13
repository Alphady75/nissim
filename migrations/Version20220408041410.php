<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220408041410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE financement (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, projet_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, created DATETIME NOT NULL, INDEX IDX_59895F56A76ED395 (user_id), INDEX IDX_59895F56C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE financement_projet (financement_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_1E088F4EA737ED74 (financement_id), INDEX IDX_1E088F4EC18272 (projet_id), PRIMARY KEY(financement_id, projet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, f_descriptive LONGTEXT NOT NULL, a_fiabilite LONGTEXT NOT NULL, d_inf_reglementaire LONGTEXT NOT NULL, end_date DATETIME NOT NULL, m_collecte DOUBLE PRECISION DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_50159CA9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE financement ADD CONSTRAINT FK_59895F56A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE financement ADD CONSTRAINT FK_59895F56C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE financement_projet ADD CONSTRAINT FK_1E088F4EA737ED74 FOREIGN KEY (financement_id) REFERENCES financement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE financement_projet ADD CONSTRAINT FK_1E088F4EC18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE financement_projet DROP FOREIGN KEY FK_1E088F4EA737ED74');
        $this->addSql('ALTER TABLE financement DROP FOREIGN KEY FK_59895F56C18272');
        $this->addSql('ALTER TABLE financement_projet DROP FOREIGN KEY FK_1E088F4EC18272');
        $this->addSql('ALTER TABLE financement DROP FOREIGN KEY FK_59895F56A76ED395');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE financement');
        $this->addSql('DROP TABLE financement_projet');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
    }
}
