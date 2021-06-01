<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531144834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_admin_candidate ADD candidate_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE info_admin_candidate ADD CONSTRAINT FK_CB693D0F91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CB693D0F91BD8781 ON info_admin_candidate (candidate_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_admin_candidate DROP FOREIGN KEY FK_CB693D0F91BD8781');
        $this->addSql('DROP INDEX UNIQ_CB693D0F91BD8781 ON info_admin_candidate');
        $this->addSql('ALTER TABLE info_admin_candidate DROP candidate_id');
    }
}
