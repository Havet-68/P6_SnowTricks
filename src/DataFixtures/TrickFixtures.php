<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tricksData=[
            ['id'=>1, 'name'=>'stalefish', 'difficulty'=>1, 'trickGroup'=>'grabs'],
            ['id'=>2, 'name'=>'indy', 'difficulty'=>2, 'trickGroup'=>'grabs'],
        ];
        foreach($tricksData as $trickData) {
            $trick = new Trick();
            $trick->setName($trickData['name']);
            $trick->setDifficulty($trickData['difficulty']);
            $trick->setTrickGroup($trickData['trickGroup']);
            $manager->persist($trick);
        }
        $manager->flush();
    }
}
