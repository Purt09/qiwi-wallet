<?php
declare(strict_types=1);

namespace Purt09\QiwiWallet\Services;


use Purt09\QiwiWallet\Exceprtion\QiwiException;
use Purt09\QiwiWallet\Interfaces\WalletInterface;
use Purt09\QiwiWallet\Traits\Api;

class Wallet implements WalletInterface
{
    use Api;

    const ENDPOINTS = [
        'profile' => '/person-profile/v1/profile/current',
        'balance' => '/funding-sources/v2/persons/%s/accounts',
    ];

    public function getProfile(): ?array
    {
        $result = $this->sendRequest(self::ENDPOINTS['profile']);
        if(is_null($result))
            throw new QiwiException('Not valid phone');
        if(array_key_exists('errorCode', $result))
            throw new QiwiException('Token not access profile');
        return $result;
    }

    public function getBalance(): ?array
    {
        $result = $this->sendRequest(vsprintf(self::ENDPOINTS['balance'], [$this->phone]));
        if(is_null($result))
            throw new QiwiException('Not valid phone');
        if(array_key_exists('errorCode', $result))
            throw new QiwiException('Token not access balance');
        return $result;
    }
}