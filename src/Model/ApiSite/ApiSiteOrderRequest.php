<?php

namespace App\Model\ApiSite;

use App\Entity\Order;
use App\Entity\Ticket;
use DateTime;

class ApiSiteOrderRequest implements \JsonSerializable
{
    private int $eventId;
    private DateTime $eventDate;
    private int $ticketAdultPrice;
    private int $ticketAdultQuantity;
    private int $ticketKidPrice;
    private int $ticketKidQuantity;
    private string $barcode;

    public function __construct(
        int $eventId,
        DateTime $eventDate,
        int $ticketAdultPrice,
        int $ticketAdultQuantity,
        int $ticketKidPrice,
        int $ticketKidQuantity,
        string $barcode
    ) {
        $this->eventId = $eventId;
        $this->eventDate = $eventDate;
        $this->ticketAdultPrice = $ticketAdultPrice;
        $this->ticketAdultQuantity = $ticketAdultQuantity;
        $this->ticketKidPrice = $ticketKidPrice;
        $this->ticketKidQuantity = $ticketKidQuantity;
        $this->barcode = $barcode;
    }

    public static function fromOrder(Order $order, string $barcode): self
    {
        return new self(
            $order->getEvent()->getId(),
            $order->getEvent()->getEventDate(),
            $order->getAdultTicketPrice(),
            $order->getAdultTicketQuantity(),
            $order->getKidTicketPrice(),
            $order->getKidTicketQuantity(),
            $barcode
        );
    }
    public static function adultTicket(Ticket $ticket): self
    {
        return new self(
            $ticket->getOrder()->getEvent()->getId(),
            $ticket->getOrder()->getEvent()->getEventDate(),
            $ticket->getTicketType()->getPrice(),
            1,
            0,
            0,
            $ticket->getBarcode()
        );
    }

    #[\Override] public function jsonSerialize(): array
    {
        return [
            'eventId' => $this->eventId,
            'eventDate' => $this->eventDate->format('Y-m-d'),
            'ticketAdultPrice' => $this->ticketAdultPrice,
            'ticketAdultQuantity' => $this->ticketAdultQuantity,
            'ticketKidPrice' => $this->ticketKidPrice,
            'ticketKidQuantity' => $this->ticketKidQuantity,
            'barcode' => $this->barcode
        ];
    }
}
