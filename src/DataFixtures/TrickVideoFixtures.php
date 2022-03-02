<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickVideo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickVideoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $trickVideo = new TrickVideo();
        $trickVideo->setName('exemple1');
        $trickVideo->setUrl('https://www.youtube.com/watch?v=t705_V-RDcQ');
        $trickVideo->setTrick($this->getReference('trick-1'));

        

        $manager->persist($trickVideo);


        $manager->flush();
    }

    public function getDependencies()
    {
        return [TrickFixtures::class];
    }
}
