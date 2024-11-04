<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031084308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_api_service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, base_url VARCHAR(255) NOT NULL, path_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_api_service_user (job_api_service_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_945C5B8EB88F8532 (job_api_service_id), INDEX IDX_945C5B8EA76ED395 (user_id), PRIMARY KEY(job_api_service_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_api_service_user ADD CONSTRAINT FK_945C5B8EB88F8532 FOREIGN KEY (job_api_service_id) REFERENCES job_api_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_api_service_user ADD CONSTRAINT FK_945C5B8EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_api_service_user DROP FOREIGN KEY FK_945C5B8EB88F8532');
        $this->addSql('ALTER TABLE job_api_service_user DROP FOREIGN KEY FK_945C5B8EA76ED395');
        $this->addSql('DROP TABLE job_api_service');
        $this->addSql('DROP TABLE job_api_service_user');
    }
}
