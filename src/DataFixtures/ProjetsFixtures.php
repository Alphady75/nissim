<?php

namespace App\DataFixtures;

use App\Entity\Projet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ProjetsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbProjet = 1; $nbProjet <= 1000; $nbProjet++){
            $user = $this->getReference('user_'. $faker->numberBetween(1, 200));
            $financements = $this->getReference('financement_'. $faker->numberBetween(1, 100));

            $projet = new Projet();

            $projet->setName($faker->realText(150));
            $projet->setSlug('lorem-ipsum-dolor-sit-amet-consectetur-' . 'slug-projet-' . $nbProjet);
            $projet->setUser($user);
            $projet->setVisible($faker->numberBetween(0, 1));
            $projet->setFDescriptive($faker->realText(400));
            $projet->setAFiabilite($faker->realText(1000));
            $projet->setDInfReglementaire($faker->realText(1000));
            $projet->setMCollecte($faker->numberBetween(10000, 100000));
            $projet->setEndDate($faker->dateTimeBetween('2021-04-01 00:00:00', '2021-12-31 00:00:00'));
            for($financement = 1; $financement < 100; $financement++){
                $projet->addFinancement($financements);
            }

            $manager->persist($projet);
        }
        

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
            FinancementsFixtures::class,
        ];
    }
}
