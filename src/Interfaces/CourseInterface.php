<?php


namespace Purt09\Apirone\Interfaces;


interface CourseInterface
{
    /**
     * Текущий курс
     *
     * @param string $currency
     * @return array
     */
    public function getCourse(string $currency = 'btc'): array;

    /**
     * Перевод в лтк курсу
     *
     * @param string $currency
     * @param $value
     * @return float
     */
    public function toLtc(string $currency, $value): float;

    /**
     * Перевод в бтк по курсу
     *
     * @param string $currency
     * @param $value
     * @return float
     */
    public function toBtc(string $currency, $value): float;

    /**
     * Перевод в doge по курсу
     *
     * @param string $currency
     * @param $value
     * @return float
     */
    public function toDoge(string $currency, $value): float;

    /**
     * Перевод в бch по курсу
     *
     * @param string $currency
     * @param $value
     * @return float
     */
    public function toBch(string $currency, $value): float;
}