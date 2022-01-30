<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BookFixtures extends Fixture
{
    /** @var AuthorRepository */
    private AuthorRepository $authorRepo;

    /** @var Generator  */
    private Generator $faker;

    public function __construct(AuthorRepository $authorRepo)
    {
        $this->authorRepo = $authorRepo;
        $this->faker = Factory::create('ru_RU');
    }

    public function load(ObjectManager $manager): void
    {
        $authorCollection = $this->authorRepo->findAll();
        $count = count($authorCollection);

        // f.e. "1 | Война и мир"
        for ($i = 0; $i < 20; $i++) {
            $book = new Book();

            // @todo need russian text provider instead this...
            $bookName = $this->faker->city() . '. ' . $this->faker->city();
            $book->setName($bookName);

            $book->addAuthor($authorCollection[rand(0, $count-1)]);

            $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookFixtures::class,
        ];
    }

}
