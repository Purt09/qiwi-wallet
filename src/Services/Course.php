<?php


namespace Purt09\Apirone\Services;


use Purt09\Apirone\Exceprtion\ApironeException;
use Purt09\Apirone\Traits\Api;
use Purt09\Apirone\Interfaces\CourseInterface;

class Course implements CourseInterface
{

    use Api;

    const ENDPOINTS = [
        'ticker' => '/ticker',
        'toBtc' => '/tobtc',
        'toLtc' => '/toltc',
        'toBch' => '/tobch',
        'toDoge' => '/todoge'
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
     * @param $value
     * @return float
     */
    public function toBtc(string $currency, $value): float
    {
        $this->checkCurrency($currency);
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
        $this->checkCurrency($currency);
        $params = [
            'currency' => $currency,
            'value' => $value
        ];
        $url = $this->getURL(self::ENDPOINTS['toLtc'], [], 'v1');
        return $this->get($url, $params);
    }

    /**
     * @param string $currency
     * @param $value
     * @return float
     */
    public function toBch(string $currency, $value): float
    {
        $this->checkCurrency($currency);
        $params = [
            'currency' => $currency,
            'value' => $value
        ];
        $url = $this->getURL(self::ENDPOINTS['toBch'], [], 'v1');
        return $this->get($url, $params);
    }

    /**
     * @param string $currency
     * @param $value
     * @return float
     */
    public function toDoge(string $currency, $value): float
    {
        $this->checkCurrency($currency);
        $params = [
            'currency' => $currency,
            'value' => $value
        ];
        $url = $this->getURL(self::ENDPOINTS['toDoge'], [], 'v1');
        return $this->get($url, $params);
    }

    private function checkCurrency(string $currency): void
    {
        $currency = strtolower($currency);
        $validCurrencies = [
            'usd', 'eur', 'gbp', 'rub'
        ];
        if(!in_array($currency, $validCurrencies))
            throw new ApironeException('not valid currency');
    }
}