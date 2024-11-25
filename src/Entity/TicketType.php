<?php

namespace App\Entity;

use App\Enum\TicketTypeEnum;
use App\Repository\TicketTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketTypeRepository::class)]
class TicketType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(enumType: TicketTypeEnum::class)]
    private TicketTypeEnum $name;

    #[ORM\Column]
    private int $price;

    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'ticketType')]
    private Collection $tickets;

    public function __construct(TicketTypeEnum $name, int $price)
    {
        $this->name = $name;
        $this->price = $price;
        $this->tickets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getName(): TicketTypeEnum
    {
        return $this->name;
    }
}
