<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SubjectFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
         $subject = new Subject();
              $subject->setName('Uvod u američki film')
              ->setECTS(4)
              ->setCode('SIT143')
              ->setDescription('Uvod u američki film')
              ->setSemRedovni(6)
              ->setSemIzvanredni(6)
              ->setIzborni(1);


            $manager->persist($subject);
            $manager->flush();
    }
}
