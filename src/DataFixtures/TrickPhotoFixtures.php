<?php

namespace App\DataFixtures;

use App\Entity\TrickPhoto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickPhotoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
         $trickPhoto = new TrickPhoto();
         $trickPhoto->setName('Figure1');
         $trickPhoto->setUrl('img/banniere1.png');
         $trickPhoto->setTrick($this->getReference('trick-1'));


         $manager->persist($trickPhoto);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [TrickFixtures::class];
    }
}
