<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Purt09\Apirone\Services\Wallet;

class WalletTest extends TestCase
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
        $wallet_id = 'btc-35792d71cd48b19dc155ce7e25f221aa';
        $walletService = new Wallet();
        $address_result = $walletService->generateAddress($wallet_id, 'http://requestbin.net/r/v6uefsv6');
        $this->assertArrayHasKey('address', $address_result);
    }

    public function testGenerateLTCAddress(): void
    {
        $wallet_id = 'ltc-dc00b7ded8ef9e1f7d322ae386254944';
        $walletService = new Wallet();
        $address_result = $walletService->generateAddress($wallet_id, 'http://requestbin.net/r/v6uefsv6');
        $this->assertArrayHasKey('address', $address_result);
    }

    public function testTransferBTC(): void
    {
        $wallet_id = 'btc-35792d71cd48b19dc155ce7e25f221aa';
        $transfer_id = '8cjMi1jU0cD4o12Vuyvs8jB9bOBpkC4u';
        $destination = [
            [
                'address' => '1G3S2jaWaRJXUsrPhQ18sYiz3P6iMpAmPb',
                'amount' => '50%'
            ],
            [
                'address' => '19BCLLFB7CBbnbjuHZw8TugYh5qFBkJ5iz',
                'amount' => '50%'
            ]
        ];
        $walletService = new Wallet();
        $result = $walletService->transfer($wallet_id, $transfer_id, $destination);
        $this->assertArrayHasKey('txs', $result);
        /*
        [txs] => Array
        (
            [0] => a43486d73b88ccee761ecfd44405d50cfe5d7fe52025b5a38600d0d821fc8b52
        )

    [processing-fee] => 20000
    [network-fee] => 233
    [change-amount] => 0
    [change-address] => 39BpvvQBrHJzyECS4yupn2wMZgaGYA9sUF
    [currency] => btc
        */

    }

    //TODO: wriete LTC testing
    public function testTransferLTC(): void
    {
        $wallet_id = 'ltc-dc00b7ded8ef9e1f7d322ae386254944';
        $transfer_id = 'bfiT1aP2EtKApYkhAEZhajyoL191aFKS';
    }

    public function testGetBTCBalance(): void
    {
        $wallet_id = '5484e54ec0bb35c95b79d7338399900f';
        $walletService = new Wallet();
        $result = $walletService->getBalance($wallet_id);
        print_r($result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals($result['currency'], 'btc');
    }

    public function testGetLTCBalance(): void
    {
        $wallet_id = 'ltc-dc00b7ded8ef9e1f7d322ae386254944';
        $walletService = new Wallet();
        $result = $walletService->getBalance($wallet_id);
        print_r($result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals($result['currency'], 'ltc');
    }
}