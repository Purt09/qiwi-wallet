<?php

namespace Purt09\QiwiWallet\Interfaces;

interface HistoryInterface
{
    /**
     * Получить историю платежей
     * @param string $operation
     * @param int $rows
     * @param string $proxy
     * @return array
     */
    public function getHistory(string $operation = "IN", int $rows = 50, string $proxy = ''): array;

    /**
     * Найти платеж по комментарию
     * @param string $comment
     * @param int $amount
     * @param int $currency
     * @param string $proxy
     * @return bool
     */
    public function checkByComment(string $comment, int $amount, int $currency = 643, string $proxy = ''): bool;

}