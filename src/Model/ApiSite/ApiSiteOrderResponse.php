<?php

namespace App\Model\ApiSite;

readonly class ApiSiteOrderResponse
{
    public function __construct(private array $responseData)
    {
    }

    public function isSuccessful(): bool
    {
        return isset($this->responseData['message']) && $this->responseData['message'] === 'order successfully booked';
    }
}
