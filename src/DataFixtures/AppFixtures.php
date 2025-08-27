<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->addUser($manager);
        $this->addCategories($manager);
        $this->addWished($manager);

        $manager->flush();
    }

    public function addCategories(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
        $categories = [
            "Travel & Adventure",
            "Sport",
            "Entertainment",
            "Human Relations",
            "Others"];
        foreach ($categories as $cat) {
            $category = new Category();
            $category->setName($cat);
            $manager->persist($category);
        }
        $manager->flush();

    }


    public function addWished(ObjectManager $manager): void {
        $categories = $manager->getRepository(Category::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 40; $i++) {
            $wish = new Wish();
            $wish
                ->setTitle($faker->realText(maxNbChars: 50))
                ->setDescription($faker->sentence($nbWords = 300, $variableNbWords = true))
                ->setUser($faker->randomElement($users))
                ->setIsPublished($faker->boolean())
                ->setDateCreated($faker->dateTimeBetween("-3 months"))
                ->setDateUpdated($faker->dateTime())
                ->setCategory($faker->randomElement($categories));

            $manager->persist($wish);


        }
        $manager->flush();

    }


    public function addUser(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 40; $i++) {
            $user = new User();
            $user->setRoles(['ROLE_USER']);
            $user->setPseudo($faker->userName);
            $user->setEmail($faker->email);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $faker->password));
            // Persist the created user
            $manager->persist($user);
        }
        // Add users created to the database
        $manager->flush();

    }

}
