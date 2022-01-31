<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131191428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'extract Book and BookTranslation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE book_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE book_translation (id INT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX book_tr_trable_id ON book_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX book_translation_unique_translation ON book_translation (translatable_id, locale)');
        $this->addSql('ALTER TABLE book_translation ADD CONSTRAINT FK_book_tr_trable_id FOREIGN KEY (translatable_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book DROP name');
        //$this->addSql('ALTER INDEX index_book_author_bookid RENAME TO IDX_9478D34516A2B381');
        //$this->addSql('ALTER INDEX index_book_author_authorid RENAME TO IDX_9478D345F675F31B');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE SCHEMA public'); // @todo fix it
        $this->addSql('DROP SEQUENCE book_translation_id_seq CASCADE');
        $this->addSql('DROP TABLE book_translation');
        $this->addSql('ALTER TABLE book ADD name VARCHAR(255) NOT NULL');
        //$this->addSql('ALTER INDEX idx_9478d345f675f31b RENAME TO index_book_author_authorid');
        //$this->addSql('ALTER INDEX idx_9478d34516a2b381 RENAME TO index_book_author_bookid');
    }
}
