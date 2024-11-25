<?php

namespace App\Controller;

use App\Model\OrderDTO;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/order", name: "Order booking controller", methods: ["POST"])]
class OrderController extends AbstractController
{
    public function __construct(private BookService $bookService)
    {
    }

    public function __invoke(#[MapRequestPayload] OrderDTO $orderDTO): \Symfony\Component\HttpFoundation\JsonResponse
    {
        try {
            $this->bookService->process($orderDTO);
        } catch (\Exception $e) {
            return $this->json($e->getMessage());
        }

        return $this->json('Ok');
    }
}
