<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    private $uploadDir;

    public function __construct(HttpClientInterface $client, $uploadDir)
    {
        $this->client = $client;
        $this->uploadDir = $uploadDir;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $fs = new Filesystem();

        $categories = [];
        for ($i = 0; $i < 5; ++$i) {
            $category = new Category();
            $category->setName('Catégorie '.$i);
            $category->setSlug('categorie-'.$i);
            $manager->persist($category);
            $categories[] = $category;
        }

        $colors = ['Bleu', 'Rouge', 'Vert'];
        for ($i = 0; $i < 10; ++$i) {
            $product = new Product();
            $product->setName('Produit '.$i);
            $product->setSlug('produit-'.$i);
            $product->setDescription($faker->text);
            $product->setPrice($faker->numberBetween(9900, 999900));
            $product->setCreatedAt($faker->dateTimeBetween('-30 days'));
            $product->setIsHeartStroke($faker->boolean);
            $product->setColors($faker->randomElements($colors, rand(0, count($colors))));
            $response = $this->client->request('GET', 'https://picsum.photos/400/400');
            $fs->dumpFile($this->uploadDir.'/'.$product->getSlug().'.jpg', $response->getContent());
            $product->setImage($product->getSlug().'.jpg');
            $product->setDiscount($faker->numberBetween(0, 90));
            $product->setCategory($faker->randomElement($categories));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
