<?php

namespace App\Model;

use App\Enum\TicketTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class OrderDTO
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    private readonly int $event_id;

    #[Assert\NotBlank]
    #[Assert\Positive]
    private readonly int $user_id;

    #[Assert\NotBlank]
    private array $tickets;

    public function __construct(int $event_id, int $user_id, array $tickets)
    {
        $this->event_id = $event_id;
        $this->user_id = $user_id;

        foreach ($tickets as $type => $count) {
            TicketTypeEnum::tryFrom($type) ?? throw new \InvalidArgumentException("Invalid ticket type: $type");
            $this->tickets[$type] = $count;
        }
    }

    public function getEventId(): int
    {
        return $this->event_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getTickets(): array
    {
        return $this->tickets;
    }
}
