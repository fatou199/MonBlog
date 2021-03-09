<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 6; $i++) {
            $article = new Article();
            $article->setTitre($faker->sentence(6))
                    ->setContenu($faker->text)
                    ->setDateCreation($faker->dateTime());
            $manager->persist($article);
        }
        $manager->flush();
        // l'entité manager va nous permettre de faire des insertion, des maj, des suppresions

        // repository recuperation de donnée
    }
}
