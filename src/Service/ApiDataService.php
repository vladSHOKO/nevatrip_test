<?php

namespace App\Service;

use App\Model\ApiSite\ApiSiteApproveResponse;
use App\Model\ApiSite\ApiSiteOrderRequest;
use App\Model\ApiSite\ApiSiteOrderResponse;

class ApiDataService
{
    public function uploadOrder(ApiSiteOrderRequest $order): ApiSiteOrderResponse
    {
        $responses = [
            ['message' => 'order successfully booked'],
            ['error' => 'barcode already exists']
        ];

        return new ApiSiteOrderResponse($responses[array_rand($responses)]);
    }

    public function approveOrder(string $barcode): ApiSiteApproveResponse
    {
        $responses = [
            ['message' => 'order successfully approved'],
            ['error' => 'event cancelled'],
            ['error' => 'no tickets'],
            ['error' => 'no seats'],
            ['error' => 'fan removed']
        ];

        return new ApiSiteApproveResponse($responses[array_rand($responses)]);

    }

}
