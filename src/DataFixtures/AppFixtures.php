<?php

namespace App\DataFixtures;

use App\Entity\Bead;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $bead = new Bead();
        $bead->setName('Fixture Bead');
        $manager->persist($bead);
        $this->addReference('test-bead', $bead); // useful in tests

        $manager->flush();
    }
}
