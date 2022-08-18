<?php

namespace Purt09\QiwiWallet\Interfaces;

interface HistoryInterface
{
    /**
     * Получить историю платежей
     * @param string $operation
     * @param int $rows
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getHistory(string $operation = "IN", int $rows = 50, string $startDate = "", string $endDate = ""): array;

    /**
     * Находит платеж по комментарию
     * true - найдено
     * false - не найдено
     * @param string $comment
     * @param int $currency
     * @return bool
     */
    public function checkByComment(string $comment, int $amount, int $currency): bool;

}