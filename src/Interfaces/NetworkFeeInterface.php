<?php
declare(strict_types=1);

namespace Purt09\Apirone\Interfaces;


interface NetworkFeeInterface
{
    /**
     * Возвращает ставку комиссии
     *
     * @param string $currency - currency
     * @return array
     */
    public function fee(string $currency): array;

    /**
     * Текущая минимальная ставка комиссии.
     *
     * @param string $currency
     * @return float
     */
    public function getNormal(string $currency = 'btc'): float;

    /**
     * Приоритетная оценка потенциально дает более высокую комиссию и, скорее всего,
     * будет достаточной для желаемой цели, но не так чувствительна к краткосрочным
     * падениям на преобладающем рынке комиссионных.
     *
     * @param string $currency
     * @return float
     */
    public function getPriority(string $currency = 'btc'): float;
}