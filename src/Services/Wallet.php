<?php
declare(strict_types=1);

namespace Purt09\Apirone\Services;

use Purt09\Apirone\Interfaces\WalletInterface;
use Purt09\Apirone\Traits\Api;

class Wallet implements WalletInterface
{

    use Api;

    const ENDPOINTS = [
        'create' => '/wallet',
        'generateAddress' => '/wallets/%s/addresses',
        'getBalance' => '/wallets/%s/balance',
        'getEstimation' => '/wallets/%s/transfer?destinations=%s&fee=%s&subtract_fee_from_amount=%s',
        'transfer' => '/wallet/%s/transfer',
        'getHistory' => 'wallets//%s/history?limit=/%s&offset=/%s&q=/%s'
    ];

    /**
     * @param string $type : saving|forwarding
     * @param string $currency : btc|ltc
     * @param string $callback_url
     * @param array $callback_data
     * @param array $destinations
     * @return array
     */
    public function create(string $type, string $currency, string $callback_url = '', array $callback_data = [], array $destinations = []): array
    {
        $payload = [
            "type" => $type,
            "currency" => $currency
        ];

        if (!empty($callback_url)) {
            $payload["callback"] = [
                "url" => $callback_url
            ];

            if (empty($callback_data)) {
                $payload["callback"]["data"] = $callback_data;
            }
        }
        if ($type == 'forwarding') {
            $payload['destinations'] = $destinations;
        }

        $url = $this->getURL(self::ENDPOINTS['create']);

        return $this->post($url, $payload);
    }

    /**
     * @param string $wallet_id
     * @param string $callback_url
     * @param array $callback_data
     * @return array
     */
    public function generateAddress(string $wallet_id, string $callback_url = ' ', array $callback_data = []): array
    {
        $payload = [];
        if (!empty($callback_url)) {
            $payload = [
                "callback" => [
                    "url" => $callback_url
                ]
            ];

            if (count($callback_data) > 0) {
                $payload["callback"]["data"] = $callback_data;
            }
        }
        $url = $this->getURL(self::ENDPOINTS['generateAddress'], [trim($wallet_id)]);
        return $this->post($url, $payload);
    }

    /**
     * @param string $wallet_id
     * @return array
     */
    public function getBalance(string $wallet_id): array
    {
        $wallet_id = explode('-', $wallet_id);
        $url = $this->getURL(self::ENDPOINTS['getBalance'], [trim($wallet_id[1])]);
        return $this->get($url);
    }

    /**
     * @param string $wallet_id
     * @param string $transfer_key
     * @param array $destinations
     * @return array
     */
    public function transfer(string $wallet_id, string $transfer_key, array $destinations): array
    {
        $payload = [
            "transfer_key" => $transfer_key,
            "destinations" => $destinations
        ];
        $url = $this->getURL(self::ENDPOINTS['transfer'], [trim($wallet_id)]);
        return $this->post($url, $payload);
    }

    public function getHistory(string $wallet_id, string $limit, string $offset, string $q)
    {
        $url = $this->getURL(self::ENDPOINTS['getHistory'], [trim($wallet_id), $limit, $offset, $q]);
    }
}