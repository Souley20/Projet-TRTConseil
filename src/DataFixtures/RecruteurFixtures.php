<?php

namespace App\DataFixtures;

use App\Entity\Recruteur;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RecruteurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        for($usr = 1; $usr <= 5; $usr++){
             $recruteur = new Recruteur();
             
             $recruteur->setFirmName($faker->word());
             $recruteur->setAddressFirm($faker->streetAddress);
             $recruteur->setZipcode(str_replace(' ', '', $faker->postcode));
            $recruteur->setCity($faker->city);
              $recruteur->setIsValided(mt_rand(0, 1) == 1 ? true : false);
        $manager->persist($recruteur);
     
          }
        $manager->flush();
    }
}
