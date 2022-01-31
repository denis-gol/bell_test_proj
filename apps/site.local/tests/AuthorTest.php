<?php

namespace App\Tests;

use App\Entity\Author;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    private Author $author;

    public function setUp(): void
    {
        $this->author = new Author();
    }

    /**
     * @return void
     */
    public function testGetEmptyCollectionFromNewAuthor()
    {
        $this->assertEmpty($this->author->getBooks());
    }

    public function testAddBookToAuthor()
    {
        $book = new Book();
        $book->translate('ru')->setName('Лев Толстой');
        $this->author->addBook($book);

        $this->assertEquals(
            $this->author->getBooks()->first()->translate('ru')->getName(),
            'Лев Толстой'
        );
    }

    public function testDeleteBookBelongsAuthor()
    {
        $book = new Book();
        $book->translate('ru')->setName('testname');
        $this->author->addBook($book);

        $this->author->removeBook($book);

        $this->assertEmpty($this->author->getBooks());
    }

}
