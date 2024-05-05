<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Consultants;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ConsultantsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
          for($usr = 1; $usr <= 5; $usr++){
             $consultant = new Consultants();
             
             $consultant->setTel('0753870879');
              
        $manager->persist($consultants);
     
          }
        $manager->flush();
    }
}