<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $event = new Event(new \DateTime());
        $manager->persist($event);
        $manager->flush();
    }
}
