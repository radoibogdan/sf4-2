<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

// Le système de fixture de Doctrine
class ProductFixtures extends Fixture implements DependentFixtureInterface
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

            // Récupération aléatoire d'une catégorie par une référence
            $categoryReference = 'category_' . $faker->numberBetween(0, 2);
            /** @var Category $category */
            $category = $this->getReference($categoryReference); // getReference -> renvoie une entité Category
            $product->setCategory($category);

            $manager->persist($product);
        }
        // exécute réelement les injections
        $manager->flush();
    }

    /**
     * Liste des classes de fixtures qui doivent être chargés avant celle-ci
     */

     public function getDependencies()
     {
        return [
            CategoryFixtures::class
        ];
     }
}
