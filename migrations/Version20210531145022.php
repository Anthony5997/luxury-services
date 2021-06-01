<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531145022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_admin_client ADD client_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE info_admin_client ADD CONSTRAINT FK_B751E20B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B751E20B19EB6921 ON info_admin_client (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_admin_client DROP FOREIGN KEY FK_B751E20B19EB6921');
        $this->addSql('DROP INDEX UNIQ_B751E20B19EB6921 ON info_admin_client');
        $this->addSql('ALTER TABLE info_admin_client DROP client_id');
    }
}
