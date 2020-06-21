<?php
declare(strict_types=1);

namespace Purt09\Apirone\Interfaces;


interface WalletInterface
{
    public function create(string $type, string $currency, string $callback_url, array $callback_data, array $destinations): array;

    public function generateAddress(string $wallet_id, string $callback_url, array $callback_data): array;

    public function getBalance(string $wallet_id): array;

    public function transfer(string $wallet_id, string $transfer_key, array $destinations): array;
}