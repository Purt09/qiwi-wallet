<?php


namespace Purt09\Apirone\Services;

use Purt09\Apirone\Interfaces\NetworkFeeInterface;
use Purt09\Apirone\Traits\Api;

class NetworkFee implements NetworkFeeInterface
{
    use Api;

    const ENDPOINTS = [
        'fee' => '/networks/%s/fee'
    ];

    public function fee(string $currency = 'btc'): array
    {
        $url = $this->getURL(self::ENDPOINTS['fee'], [trim($currency)]);
        return $this->get($url);
    }

    public function getNormal(string $currency = 'btc'): float
    {
        $result = $this->fee($currency);
        return $result[0]['rate'];
    }

    public function getPriority(string $currency = 'btc'): float
    {
        $result = $this->fee($currency);
        return $result[1]['rate'];
    }
}