<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\ru_RU\Text;

class BookFixtures extends Fixture
{
    /** @var AuthorRepository */
    private AuthorRepository $authorRepo;

    /** @var Generator  */
    private Generator $fakerRu;

    /** @var Generator  */
    private Generator $fakerEn;

    public function __construct(AuthorRepository $authorRepo)
    {
        $this->authorRepo = $authorRepo;
        $this->fakerRu = Factory::create('ru_RU');
        $this->fakerEn = Factory::create('en_US');

        $this->fakerRu->addProvider(new Text($this->fakerRu));
    }

    public function load(ObjectManager $manager): void
    {
        $authorCollection = $this->authorRepo->findAll();
        $count = count($authorCollection);

        for ($i = 0; $i < 10000; $i++) {
            $book = new Book();

            $bookName = $this->fakerRu->realText(rand(10, 20));
            $book->translate('ru')->setName($bookName);
            $bookName = $this->fakerEn->realText(rand(10, 20));
            $book->translate('en')->setName($bookName);
            $book->mergeNewTranslations();

            $book->addAuthor($authorCollection[rand(0, $count - 1)]);

            $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
        ];
    }

}
