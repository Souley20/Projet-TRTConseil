<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707140453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidats ADD cv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE candidats ADD CONSTRAINT FK_C8B28E44CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8B28E44CFE419E2 ON candidats (cv_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidats DROP FOREIGN KEY FK_C8B28E44CFE419E2');
        $this->addSql('DROP INDEX UNIQ_C8B28E44CFE419E2 ON candidats');
        $this->addSql('ALTER TABLE candidats DROP cv_id');
    }
}
