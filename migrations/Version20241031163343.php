<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031163343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_api_services_job_search_settings DROP FOREIGN KEY FK_580E24E12F1E6320');
        $this->addSql('ALTER TABLE job_api_services_job_search_settings DROP FOREIGN KEY FK_580E24E1EF2E0545');
        $this->addSql('DROP TABLE job_api_services_job_search_settings');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_api_services_job_search_settings (job_api_services_id INT NOT NULL, job_search_settings_id INT NOT NULL, INDEX IDX_580E24E12F1E6320 (job_api_services_id), INDEX IDX_580E24E1EF2E0545 (job_search_settings_id), PRIMARY KEY(job_api_services_id, job_search_settings_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE job_api_services_job_search_settings ADD CONSTRAINT FK_580E24E12F1E6320 FOREIGN KEY (job_api_services_id) REFERENCES job_api_services (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_api_services_job_search_settings ADD CONSTRAINT FK_580E24E1EF2E0545 FOREIGN KEY (job_search_settings_id) REFERENCES job_search_settings (id) ON DELETE CASCADE');
    }
}
