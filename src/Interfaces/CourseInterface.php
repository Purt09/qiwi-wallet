<?php


namespace Purt09\Apirone\Interfaces;


interface CourseInterface
{
    public function getCourse(string $currency = 'btc'): array;

    public function getRate(string $currency, int $timestamp, string $crypto): float;

    public function toLtc(string $currency, $value): float;

    public function toBtc(string $currency, $value): float;
}