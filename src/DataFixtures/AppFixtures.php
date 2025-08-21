<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->addWished($manager);

        $manager->flush();
    }

    public function addWished(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 20; $i++) {
            $wish = new Wish();
            $wish
                ->setTitle($faker->realText(maxNbChars: 50))
                ->setDescription($faker->sentence($nbWords = 300, $variableNbWords = true))
                ->setAuthor($faker->firstName())
                ->setIsPublished($faker->boolean())
                ->setDateCreated($faker->dateTimeBetween("-3 months"))
                ->setDateUpdated($faker->dateTime());

            $manager->persist($wish);


        }
        $manager->flush();

    }
}
