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
        $address_result = $walletService->generateAddress($wallet_id, 'https://bot-t.ru/');
        $this->assertArrayHasKey('address', $address_result);
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
        $wallet_id = 'btc-c260284a22281cf7abf5593705fd4afc';
        $walletService = new Wallet();
        $result = $walletService->getBalance($wallet_id);
        print_r($result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals($result['currency'], 'btc');
    }
}