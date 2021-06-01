<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531150027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidacy ADD candidate_id VARCHAR(255) NOT NULL, ADD job_offer_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE candidacy ADD CONSTRAINT FK_D930569D91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE candidacy ADD CONSTRAINT FK_D930569D3481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D930569D91BD8781 ON candidacy (candidate_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D930569D3481D195 ON candidacy (job_offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidacy DROP FOREIGN KEY FK_D930569D91BD8781');
        $this->addSql('ALTER TABLE candidacy DROP FOREIGN KEY FK_D930569D3481D195');
        $this->addSql('DROP INDEX UNIQ_D930569D91BD8781 ON candidacy');
        $this->addSql('DROP INDEX UNIQ_D930569D3481D195 ON candidacy');
        $this->addSql('ALTER TABLE candidacy DROP candidate_id, DROP job_offer_id');
    }
}
