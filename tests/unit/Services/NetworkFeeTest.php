<?php
namespace Test\unit\Services;

use PHPUnit\Framework\TestCase;
use Purt09\Apirone\Services\NetworkFee;

class NetworkFeeTest extends TestCase
{
    public function testBTCFee(): void
    {
        $network_fee = new NetworkFee();
        $result = $network_fee->fee();
        $this->assertArrayHasKey('strategy', $result[0]);
        $this->assertArrayHasKey('strategy', $result[1]);
        $this->assertEquals('normal', $result[0]['strategy']);
        $this->assertEquals('priority', $result[1]['strategy']);
    }

    public function testGetNormal()
    {
        $network_fee = new NetworkFee();
        $normal = $network_fee->getNormal();
        $this->assertIsFloat($normal);
    }

    public function testGetPriority()
    {
        $network_fee = new NetworkFee();
        $normal = $network_fee->getPriority();
        $this->assertIsFloat($normal);
    }
}