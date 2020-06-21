<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Purt09\Apirone\Services\NetworkFee;

class NetworkFeeTest extends TestCase
{
    public function testBTCFee(): void
    {
        $network_fee = new NetworkFee();
        $result = $network_fee->fee();
        $this->assertArrayHasKey('economical', $result);
    }
}