<?php


namespace Test\unit\Services;


use Purt09\Apirone\Services\Course;
use PHPUnit\Framework\TestCase;

class CourseTest extends TestCase
{
    public function testGetCourseBTC(): void
    {
        $course = new Course();
        $wallet_result = $course->getCourse();
        $this->assertArrayHasKey('GBP', $wallet_result);
        $this->assertArrayHasKey('USD', $wallet_result);
        $this->assertArrayHasKey('RUB', $wallet_result);
        $this->assertArrayHasKey('EUR', $wallet_result);
    }

    public function testGetCourseLTC(): void
    {
        $course = new Course();
        $wallet_result = $course->getCourse('ltc');
        $this->assertArrayHasKey('GBP', $wallet_result);
        $this->assertArrayHasKey('USD', $wallet_result);
        $this->assertArrayHasKey('RUB', $wallet_result);
        $this->assertArrayHasKey('EUR', $wallet_result);
    }

    public function testGetRate(): void
    {
        $course = new Course();
        $wallet_result = $course->getRate('USD', '1562409674', 'btc');
        $this->assertEquals($wallet_result, 11447.66);
    }

    public function testToBTC(): void
    {
        $course = new Course();
        $wallet_result = $course->toBtc('USD', '100');
        $this->assertIsFloat($wallet_result);
    }

    public function testToLTC(): void
    {
        $course = new Course();
        $wallet_result = $course->toLtc('USD', '100');
        $this->assertIsFloat($wallet_result);
    }
}