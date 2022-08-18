<?php
declare(strict_types=1);

namespace Purt09\QiwiWallet\Services;


use Purt09\QiwiWallet\Exceprtion\QiwiException;
use Purt09\QiwiWallet\Interfaces\HistoryInterface;
use Purt09\QiwiWallet\Traits\Api;

class History implements HistoryInterface
{
    use Api;

    const ENDPOINTS = [
        'history' => '/payment-history/v2/persons/%s/payments',
    ];

    public function getHistory(string $operation = "IN", int $rows = 50, string $startDate = "", string $endDate = ""): array
    {
        $result = $this->sendRequest(vsprintf(self::ENDPOINTS['history'], [$this->phone]), [
            'operation' => $operation,
            'rows' => $rows,
        ]);
        if(is_null($result))
            throw new QiwiException('Not valid phone');
        if(array_key_exists('errorCode', $result))
            throw new QiwiException('Token not access history payments');
        return $result;
    }

    public function checkByComment(string $comment, int $amount, int $currency = 643): bool
    {
        $data = $this->getHistory();

        foreach ($data['data'] as $item) {
            if($item['sum']['amount'] != $amount / 100)
                continue;
            if($item['sum']['currency'] != $currency)
                continue;
            if($item['status'] != 'SUCCESS')
                continue;
            if($item['comment'] == $comment)
                return true;
        }
        return false;
    }
}