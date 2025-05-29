<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 5; $i++) {
            $category = new Category();
            $category->setName($faker->word());
            $category->setSlug(strtolower($this->slugger->slug($category->getName())));
            $manager->persist($category);

            for ($j = 1; $j <= 10; $j++) {
                $product = new Product();
                $title = $faker->sentence(3);
                $product->setTitle($title);
                $product->setSlug(strtolower($this->slugger->slug($title)));
                $product->setDescription($faker->paragraph());
                $product->setPrice($faker->randomFloat(2, 10, 200));
                $product->setStock($faker->numberBetween(1, 50));
                $product->setImage($faker->imageUrl(640, 480, 'books', true));
                $product->setCategory($category);
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
