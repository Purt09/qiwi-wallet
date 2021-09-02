<?php


namespace Test\unit\Services;


use Purt09\Apirone\Exceprtion\ApironeException;
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

    public function testToBCH(): void
    {
        $course = new Course();
        $wallet_result = $course->toBch('USD', '100');
        $this->assertIsFloat($wallet_result);
    }

    public function testToDOGE(): void
    {
        $course = new Course();
        $wallet_result = $course->toDoge('USD', '100');
        $this->assertIsFloat($wallet_result);
    }

    public function testCheckNotValid()
    {
        $course = new Course();
        try {
            $course->toBtc('ASDASD', '100');
        } catch (ApironeException $e) {
            $this->assertEquals($e->getMessage(), 'not valid currency');
        }
    }
}