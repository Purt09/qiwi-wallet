<?php


namespace Test\unit\Services;


use PHPUnit\Framework\TestCase;
use Purt09\Apirone\Services\Wallet;

class WalletTwoTest extends TestCase
{
    public function testCreateBTCWallet(): void
    {
        $walletService = new Wallet();
        $wallet_result = $walletService->create('saving', 'btc');
        $this->assertArrayHasKey('transfer_key', $wallet_result);
    }

    public function testCreateLTCWallet(): void
    {
        $walletService = new Wallet();
        $wallet_result = $walletService->create('saving', 'ltc');
        $this->assertArrayHasKey('transfer_key', $wallet_result);
    }

    public function testGenerateBTCAddress(): void
    {
        $wallet_id = 'btc-c260284a22281cf7abf5593705fd4afc';
        $walletService = new Wallet();
        $params = [
            'test' => 'test'
        ];
        $address_result = $walletService->generateAddress(
            $wallet_id,
            'https://bot-t.ru/',
            $params);
        $this->assertArrayHasKey('address', $address_result);
        $this->assertArrayHasKey('data', $address_result["callback"]);
    }

    public function testGenerateLTCAddress(): void
    {
        $wallet_id = 'ltc-dc00b7ded8ef9e1f7d322ae386254944';
        $walletService = new Wallet();
        $address_result = $walletService->generateAddress($wallet_id, 'https://bot-t.ru/');
        $this->assertArrayHasKey('address', $address_result);
    }

    public function testGetBTCBalance(): void
    {
        $wallet_id = 'btc-2e42ca863457247208f5f477fd4ebc4c';
        $walletService = new Wallet();
        $result = $walletService->getBalance($wallet_id);
        $this->assertArrayHasKey('total', $result);
    }

    public function testGetLTCBalance(): void
    {
        $wallet_id = 'ltc-8ef6fb7f562e4427406c194fb0ef9df1';
        $walletService = new Wallet();
        $result = $walletService->getBalance($wallet_id, 'ltc');
        $this->assertArrayHasKey('total', $result);
    }
}