<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607072945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidacy DROP INDEX UNIQ_D930569D91BD8781, ADD INDEX IDX_D930569D91BD8781 (candidate_id)');
        $this->addSql('ALTER TABLE candidacy DROP INDEX UNIQ_D930569D3481D195, ADD INDEX IDX_D930569D3481D195 (job_offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidacy DROP INDEX IDX_D930569D91BD8781, ADD UNIQUE INDEX UNIQ_D930569D91BD8781 (candidate_id)');
        $this->addSql('ALTER TABLE candidacy DROP INDEX IDX_D930569D3481D195, ADD UNIQUE INDEX UNIQ_D930569D3481D195 (job_offer_id)');
    }
}
