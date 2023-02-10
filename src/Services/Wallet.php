<?php
declare(strict_types=1);

namespace Purt09\QiwiWallet\Services;


use GuzzleHttp\Exception\GuzzleException;
use Purt09\QiwiWallet\Exceprtion\QiwiException;
use Purt09\QiwiWallet\Interfaces\WalletInterface;
use Purt09\QiwiWallet\Traits\Api;

class Wallet implements WalletInterface
{
    use Api;

    const ENDPOINTS = [
        'profile' => '/person-profile/v1/profile/current',
        'balance' => '/funding-sources/v2/persons/%s/accounts',
        'restrictions' => '/person-profile/v1/persons/%s/status/restrictions',
    ];

    public function getProfile(string $proxy = ''): ?array
    {
        $result = null;
        try {
            $result = $this->sendRequest(self::ENDPOINTS['profile'], [],  $proxy, false);
        } catch (GuzzleException $e) {
            if(!empty($proxy))
                throw new QiwiException($e->getMessage());
        }
        if(is_null($result))
            throw new QiwiException('Not valid token');
        if(array_key_exists('errorCode', $result))
            throw new QiwiException('Token not access profile');
        return $result;
    }

    public function getBalance(string $proxy = ''): ?array
    {
        $result = null;
        try {
            $result = $this->sendRequest(vsprintf(self::ENDPOINTS['balance'], [$this->phone]), [],  $proxy, false);
        } catch (GuzzleException $e) {
        }
        if(is_null($result))
            throw new QiwiException('Not valid token');
        if(array_key_exists('errorCode', $result))
            throw new QiwiException('Token not access balance');
        return $result;
    }

    public function checkRestrictions(string $proxy = ''): array
    {
        $result = null;
        try {
            $result = $this->sendRequest(vsprintf(self::ENDPOINTS['restrictions'], [$this->phone]), [],  $proxy, false);
        } catch (GuzzleException $e) {
        }
        if(is_null($result))
            throw new QiwiException('Not valid token');
        if(array_key_exists('errorCode', $result))
            throw new QiwiException('Token not access balance');
        return $result;
    }
}