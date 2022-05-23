<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class PostsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($posts = 1; $posts <= 100; $posts++){
            $user = $this->getReference('user_'. $faker->numberBetween(1, 200));
            $post = new Post();

            $post->setName($faker->realText(150));
            $post->setSlug('lorem-ipsum-dolor-sit-amet-consectetur-' . 'slug-post-' . $posts);
            $post->setUser($user);
            $post->setOnline($faker->numberBetween(0, 1));
            $post->setContent($faker->realText(1000));

            $manager->persist($post);
        }
        

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
        ];
    }
}