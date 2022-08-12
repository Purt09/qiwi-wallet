<?php


namespace Test\unit\Services;


use PHPUnit\Framework\TestCase;
use Purt09\QiwiWallet\Services\Wallet;

/**
 * Перед тестами у Вас должны быть разрешены просмотры профиля и баланса
 */
class WalletTest extends TestCase
{
    public $phone = "";
    public $token = "";

    public function testProfile(): void
    {
        $walletService = new Wallet($this->token, $this->phone);
        $wallet_result = $walletService->getProfile();
        self::assertEquals($wallet_result['contractInfo']['contractId'], $this->phone);
    }

    public function testProfileException(): void
    {
        $walletService = new Wallet($this->token . '1', $this->phone);
        try {
            $wallet_result = $walletService->getProfile();
            self::assertEquals(1, 2);
        } catch (\Exception $e) {
            self::assertEquals($e->getMessage(), 'Not valid phone');
        }
    }

    public function testBalance(): void
    {
        $walletService = new Wallet($this->token, $this->phone);
        $wallet_result = $walletService->getBalance();
        self::assertArrayHasKey('accounts', $wallet_result);
    }

    public function testBalanceException(): void
    {
        $walletService = new Wallet($this->token . '1', $this->phone);
        try {
            $wallet_result = $walletService->getBalance();
            self::assertEquals(1, 2);
        } catch (\Exception $e) {
            self::assertEquals($e->getMessage(), 'Not valid phone');
        }
    }

    public function testRestrictions()
    {
        $walletService = new Wallet($this->token, $this->phone);
        $wallet_result = $walletService->getBalance();
        self::assertTrue($wallet_result['accounts'][0]['hasBalance']);
    }
}