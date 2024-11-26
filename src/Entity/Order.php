<?php

namespace App\Entity;

use App\Enum\TicketTypeEnum;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $user_id;

    #[ORM\Column]
    private int $equal_price;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $created;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private Event $event;


    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'order', orphanRemoval: true)]
    private Collection $tickets;

    public function __construct(Event $event, int $user_id)
    {
        $this->event = $event;
        $this->user_id = $user_id;
        $this->equal_price = 0;
        $this->created = new \DateTimeImmutable();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getEqualPrice(): int
    {
        return $this->equal_price;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): void
    {
        $this->equal_price += $ticket->getPrice();
        $this->tickets[] = $ticket;
    }

    public function getAdultTicketQuantity(): int
    {
        return
            $this->tickets->filter(fn(Ticket $ticket) => $ticket->getTicketType()->getName() === TicketTypeEnum::ADULT)->count();
    }

    public function getAdultTicketPrice(): int
    {
        $tickets = $this->tickets->filter(fn(Ticket $ticket) => $ticket->getTicketType()->getName() === TicketTypeEnum::ADULT);
        if ($tickets->count()) {
            return $tickets->first()->getPrice();
        }

        throw new \InvalidArgumentException('No adult tickets found');
    }

    public function getKidTicketQuantity(): int
    {
        return
            $this->tickets->filter(fn(Ticket $ticket) => $ticket->getTicketType()->getName() === TicketTypeEnum::KID)->count();
    }

    public function getKidTicketPrice(): int
    {
        $tickets = $this->tickets->filter(fn(Ticket $ticket) => $ticket->getTicketType()->getName() === TicketTypeEnum::KID);
        if ($tickets->count()) {
            return $tickets->first()->getPrice();
        }

        throw new \InvalidArgumentException('No kid tickets found');
    }
}
