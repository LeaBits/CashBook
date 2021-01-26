<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126103434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD repayment_transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1235AE3D9 FOREIGN KEY (repayment_transaction_id) REFERENCES transaction (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D1235AE3D9 ON transaction (repayment_transaction_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1235AE3D9');
        $this->addSql('DROP INDEX UNIQ_723705D1235AE3D9 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP repayment_transaction_id');
    }
}
