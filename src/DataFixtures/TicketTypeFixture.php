<?php

namespace App\DataFixtures;

use App\Entity\TicketType;
use App\Enum\TicketTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TicketTypeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ticketType = new TicketType(TicketTypeEnum::KID, 50);
        $manager->persist($ticketType);

        $ticketType = new TicketType(TicketTypeEnum::ADULT, 100);
        $manager->persist($ticketType);

        $manager->flush();
    }
}
