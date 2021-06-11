<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210611102353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_admin_candidate DROP FOREIGN KEY FK_CB693D0F91BD8781');
        $this->addSql('DROP INDEX IDX_CB693D0F91BD8781 ON info_admin_candidate');
        $this->addSql('ALTER TABLE info_admin_candidate DROP candidate_id');
        $this->addSql('ALTER TABLE info_admin_client DROP FOREIGN KEY FK_B751E20B19EB6921');
        $this->addSql('DROP INDEX IDX_B751E20B19EB6921 ON info_admin_client');
        $this->addSql('ALTER TABLE info_admin_client DROP client_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_admin_candidate ADD candidate_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE info_admin_candidate ADD CONSTRAINT FK_CB693D0F91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('CREATE INDEX IDX_CB693D0F91BD8781 ON info_admin_candidate (candidate_id)');
        $this->addSql('ALTER TABLE info_admin_client ADD client_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE info_admin_client ADD CONSTRAINT FK_B751E20B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_B751E20B19EB6921 ON info_admin_client (client_id)');
    }
}
