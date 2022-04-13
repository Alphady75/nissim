<?php

namespace App\DataFixtures;

use App\Entity\Financement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FinancementsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($financements = 1; $financements <= 100; $financements++){

            $user = $this->getReference('user_'. $faker->numberBetween(1, 200));
            
            $financement = new Financement();
            $financement->setMontant($faker->numberBetween(100, 1000));
            $financement->setUser($user);
            $manager->persist($financement);

            // Enregistre la catégorie dans une référence
            $this->addReference('financement_' . $financements, $financement);
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
