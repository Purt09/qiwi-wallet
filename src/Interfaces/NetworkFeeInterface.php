<?php
declare(strict_types=1);

namespace Purt09\Apirone\Interfaces;


interface NetworkFeeInterface
{
    public function fee(string $coin, int $blocks): array;

}