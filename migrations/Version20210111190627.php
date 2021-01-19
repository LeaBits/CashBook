<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111190627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, bank_account_id INT NOT NULL, transaction_category_id INT NOT NULL, transaction_subcategory_id INT DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, date DATE NOT NULL, is_off TINYINT(1) NOT NULL, comments LONGTEXT DEFAULT NULL, INDEX IDX_723705D112CB990C (bank_account_id), INDEX IDX_723705D1AECF88CF (transaction_category_id), INDEX IDX_723705D1CE82F6C8 (transaction_subcategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_subcategory (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D112CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1AECF88CF FOREIGN KEY (transaction_category_id) REFERENCES transaction_category (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1CE82F6C8 FOREIGN KEY (transaction_subcategory_id) REFERENCES transaction_subcategory (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D112CB990C');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1AECF88CF');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1CE82F6C8');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_category');
        $this->addSql('DROP TABLE transaction_subcategory');
        $this->addSql('DROP TABLE user');
    }
}
