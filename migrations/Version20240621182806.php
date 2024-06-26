<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240621182806 extends AbstractMigration
{
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE logs');
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS logs (
            id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            service_name VARCHAR(255) NOT NULL,
            service_url VARCHAR(255) NOT NULL,
            hash VARCHAR(255) NOT NULL,
            status_code INT(11) NOT NULL,
            method VARCHAR(50) NOT NULL,
            protocol VARCHAR(50) NOT NULL,
            request_date DATETIME NOT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE INDEX logs_service_name_index ON logs (service_name)');
        $this->addSql('CREATE INDEX logs_hash_index ON logs (hash)');
        $this->addSql('CREATE INDEX logs_status_code_index ON logs (status_code)');
        $this->addSql('CREATE INDEX logs_request_date_index ON logs (request_date)');
    }
}
