<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803231524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE id id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE invoice CHANGE customer_id customer_id VARCHAR(36) NOT NULL');
        $this->addSql('CREATE INDEX created_at ON invoice (created_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX created_at ON invoice');
        $this->addSql('ALTER TABLE invoice CHANGE customer_id customer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE customer CHANGE id id CHAR(36) NOT NULL');
    }
}
