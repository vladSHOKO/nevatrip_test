<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private Order $order;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private TicketType $ticketType;

    #[ORM\Column(length: 120)]
    private string $barcode;

    #[ORM\Column]
    private int $price;

    public function __construct(Order $order, TicketType $ticketType)
    {
        $this->order = $order;
        $this->ticketType = $ticketType;
        $this->barcode = $this->generateBarcode();
        $this->price = $ticketType->getPrice();
    }

    private function generateBarcode(): string
    {
        return uniqid();
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getTicketType(): TicketType
    {
        return $this->ticketType;
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setBarcode(string $barcode): void
    {
        $this->barcode = $barcode;
    }
}
