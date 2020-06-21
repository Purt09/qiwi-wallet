<?php


namespace Purt09\Apirone\Services;


use Purt09\Apirone\Traits\Api;
use Purt09\Apirone\Interfaces\CourseInterface;

class Course implements CourseInterface
{

    use Api;

    const ENDPOINTS = [
        'ticker' => '/ticker',
        'rate' => '/rate',
        'toBtc' => '/tobtc',
        'toLtc' => '/toltc'
    ];

    /**
     * @param string $currency
     * @return array
     */
    public function getCourse(string $currency = 'btc'): array
    {
        $url = $this->getURL(self::ENDPOINTS['ticker'], [], 'v1');
        return $this->get($url, ['currency' => $currency]);
    }

    /**
     * @param string $currency
     * @param int $timestamp
     * @param string $crypto
     * @return float
     */
    public function getRate(string $currency, int $timestamp, string $crypto): float
    {
        $params = [
            'currency' => $currency,
            'timestamp' => $timestamp,
            'crypto' => $crypto
        ];
        $url = $this->getURL(self::ENDPOINTS['rate'], [], 'v1');
        return $this->get($url, $params);
    }

    /**
     * @param string $currency
     * @param $value
     * @return float
     */
    public function toBtc(string $currency, $value): float
    {
        $params = [
            'currency' => $currency,
            'value' => $value
        ];
        $url = $this->getURL(self::ENDPOINTS['toBtc'], [], 'v1');
        return $this->get($url, $params);

    }

    /**
     * @param string $currency
     * @param $value
     * @return float
     */
    public function toLtc(string $currency, $value): float
    {
        $params = [
            'currency' => $currency,
            'value' => $value
        ];
        $url = $this->getURL(self::ENDPOINTS['toLtc'], [], 'v1');
        return $this->get($url, $params);
    }
}