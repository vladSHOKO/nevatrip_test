<?php

namespace App\Model\ApiSite;

readonly class ApiSiteApproveResponse
{
    public function __construct(private array $responseData)
    {
    }

    public function isSuccessful(): bool
    {
        return isset($this->responseData['message']) && $this->responseData['message'] === 'order successfully approved';
    }

    public function getErrorMessage(): string
    {
        return $this->responseData['error'];
    }
}
