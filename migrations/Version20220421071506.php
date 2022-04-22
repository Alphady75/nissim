<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421071506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE financement ADD reference VARCHAR(255) NOT NULL, ADD stripe_token VARCHAR(255) NOT NULL, ADD brand_stripe VARCHAR(255) NOT NULL, ADD last4_stripe VARCHAR(255) NOT NULL, ADD id_charge_stripe VARCHAR(255) NOT NULL, ADD status_stripe VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE financement DROP reference, DROP stripe_token, DROP brand_stripe, DROP last4_stripe, DROP id_charge_stripe, DROP status_stripe');
    }
}
