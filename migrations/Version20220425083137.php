<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425083137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE financement CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE stripe_token stripe_token VARCHAR(255) DEFAULT NULL, CHANGE brand_stripe brand_stripe VARCHAR(255) DEFAULT NULL, CHANGE last4_stripe last4_stripe VARCHAR(255) DEFAULT NULL, CHANGE id_charge_stripe id_charge_stripe VARCHAR(255) DEFAULT NULL, CHANGE status_stripe status_stripe VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE financement CHANGE reference reference VARCHAR(255) NOT NULL, CHANGE stripe_token stripe_token VARCHAR(255) NOT NULL, CHANGE brand_stripe brand_stripe VARCHAR(255) NOT NULL, CHANGE last4_stripe last4_stripe VARCHAR(255) NOT NULL, CHANGE id_charge_stripe id_charge_stripe VARCHAR(255) NOT NULL, CHANGE status_stripe status_stripe VARCHAR(255) NOT NULL');
    }
}
