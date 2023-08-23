<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230814141920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency (
                            id INT NOT NULL,
                            numeric_code VARCHAR(3) DEFAULT NULL,
                            character_code VARCHAR(3) DEFAULT NULL,
                            nominal INT DEFAULT NULL,
                            name VARCHAR(128) DEFAULT NULL,
                            value INT DEFAULT NULL,
                            date DATE NOT NULL,
                            PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX currency_date_character_code_uniq ON currency (date, character_code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE currency_id_seq CASCADE');
        $this->addSql('DROP TABLE currency');
    }
}
