<?php

namespace App\Repository;

use App\Entity\TicketType;
use App\Enum\TicketTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TicketTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketType::class);
    }

    public function findOneByName(TicketTypeEnum $name): Tickettype
    {
        $ticketType = $this->createQueryBuilder('t')
            ->andWhere('t.name = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult();
        if ($ticketType === null) {
            throw new \Exception('Ticket type not found');
        }

        return $ticketType;
    }
}
