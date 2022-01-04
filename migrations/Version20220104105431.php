<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220104105431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création Entity : Storage et StorageCategory';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE storage (id INT AUTO_INCREMENT NOT NULL, storage_category_id INT NOT NULL, name VARCHAR(100) NOT NULL, location VARCHAR(100) NOT NULL, INDEX IDX_547A1B3418D664C3 (storage_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE storage_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE storage ADD CONSTRAINT FK_547A1B3418D664C3 FOREIGN KEY (storage_category_id) REFERENCES storage_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storage DROP FOREIGN KEY FK_547A1B3418D664C3');
        $this->addSql('DROP TABLE storage');
        $this->addSql('DROP TABLE storage_category');
    }
}
