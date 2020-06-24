<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

// Le système de fixture de Doctrine
class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Instantiation de faker pour la création de fausses données
        // https://github.com/fzaninotto/Faker
        $faker = Factory::create('fr_FR');

        // Générer 50 produits
        for($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product
                ->setName($faker->sentence(3))
                ->setDescription($faker->optional()->realText())
                ->setPrice($faker->numberBetween(1000,3500))
                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
            ;
            $manager->persist($product);
        }
        // exécute réelement les injections
        $manager->flush();
    }
}
