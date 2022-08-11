<?php


namespace Test\unit\Services;


use PHPUnit\Framework\TestCase;
use Purt09\QiwiWallet\Services\History;

class HistoryTest extends TestCase
{
    public $phone = "";
    public $token = "";
    // Комментарий, который есть в истории (Последние 50 платежей)
    public $last_comment = '3';

    public function testHistory(): void
    {
        $walletService = new History($this->token, $this->phone);
        $wallet_result = $walletService->getHistory('IN', 1);
        $this->assertArrayHasKey('data', $wallet_result);
        $this->assertTrue(array_key_exists('data', $wallet_result));
    }

    public function testHistoryException(): void
    {
        $walletService = new History($this->token . 1, $this->phone);
        try {
            $wallet_result = $walletService->getHistory('IN', 1);
            self::assertEquals(1, 2);
        } catch (\Exception $e) {
            self::assertEquals($e->getMessage(), 'Not valid phone');
        }
    }

    public function testCheckPayComment(): void
    {
        $walletService = new History($this->token, $this->phone);
        $wallet_result = $walletService->checkByComment($this->last_comment);
        $this->assertTrue($wallet_result);
    }

    public function testCheckPayCommentNotFound(): void
    {
        $walletService = new History($this->token, $this->phone);
        $wallet_result = $walletService->checkByComment($this->last_comment . rand(10, 100000));
        $this->assertFalse($wallet_result);
    }
}