<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Enum\TicketTypeEnum;
use App\Model\ApiSite\ApiSiteOrderRequest;
use App\Model\OrderDTO;
use App\Repository\EventRepository;
use App\Repository\TicketTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class BookService
{
    public function __construct(
        private ApiDataService $apiDataService,
        private EntityManagerInterface $entityManager,
        private EventRepository $eventRepository,
        private TicketTypeRepository $ticketTypeRepository
    ) {
    }

    public function process(OrderDTO $orderDTO): void
    {
        $event = $this->eventRepository->find($orderDTO->getEventId());
        $order = new Order($event, $orderDTO->getUserId());

        foreach ($orderDTO->getTickets() as $type => $count) {
            $ticketToBuy = $count;
            while ($ticketToBuy--) {
                $ticketType = $this->ticketTypeRepository->findOneByName(TicketTypeEnum::from($type));

                $ticket = new Ticket($order, $ticketType);
                $order->addTicket($ticket);
            }
        }

        $bookedBarcode = $this->bookOrder($order);

        $result = $this->apiDataService->approveOrder($bookedBarcode);

        if(!$result->isSuccessful()) {
           throw new \Exception($result->getErrorMessage());
        }

        foreach ($order->getTickets() as $ticket) {
            $ticket->setBarcode($bookedBarcode);
            $this->entityManager->persist($ticket);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    private function bookOrder(Order $order): string
    {
        $barcode = uniqid();
        $orderRequest = ApiSiteOrderRequest::fromOrder($order, $barcode);

        $response = $this->apiDataService->uploadOrder($orderRequest);

        if ($response->isSuccessful()) {
            return $barcode;
        }

        return $this->bookOrder($order);
    }
}
