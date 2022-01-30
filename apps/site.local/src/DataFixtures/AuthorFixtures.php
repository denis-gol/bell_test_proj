<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');

        // f.e. "1 | Лев Толстой"
        for ($i = 0; $i < 20; $i++) {
            $author = new Author();
            $authorName = $faker->firstName(). ' '. $faker->lastName();

            $author->setName($authorName);
            $manager->persist($author);
        }

        $manager->flush();
    }
}
