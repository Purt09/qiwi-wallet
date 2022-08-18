<?php


namespace Test\unit\Services;


use PHPUnit\Framework\TestCase;
use Purt09\QiwiWallet\Exceprtion\QiwiException;
use Purt09\QiwiWallet\Services\Payment;

class PaymentTest extends TestCase
{
    public $phone = "";
    public $token = "";
    // Сумма, которая есть в истории
    public $old_amount = 3;
    // Сумма, которая есть в истории для перевода с номером
    public $old_amount_phone = 15;
    // номер с которого перевод
    public $leftPhone = "";

    public function testCreate(): void
    {
        $payment = new Payment($this->token, $this->phone);
        $payment->create();

        $this->assertFileExists(Payment::getWalletPath() . $this->phone . '.txt');
    }

    public function testDeleteAwaiting()
    {
        $payment = new Payment($this->token, $this->phone);
        $amount = $payment->billCreate($amount = 100, $currency = 643);
        $this->assertEquals($amount, 100);
        $path = Payment::getAwaitingPath() . $this->phone . '.txt';
        $this->assertFileExists($path);
        $payment->deleteAwaiting($amount . Payment::SEPARATOR . $currency);
        $this->assertFileNotExists($path);

        $amount = $payment->billCreate($amount = 100, $currency = 643);
        $this->assertEquals($amount, 100);
        $amount = $payment->billCreate($amount = 100, $currency = 643);
        $this->assertEquals($amount, 101);
        $payment->billCancel(100, $currency = 643);
        $data = file_get_contents($path);
        $this->assertEquals($data, $amount . Payment::SEPARATOR . $currency . PHP_EOL);
        $payment->billCancel($amount, $currency = 643);
        $this->assertFileNotExists($path);


        $amount = $payment->billCreate($amount = 100, $currency = 643, '+213123');
        $this->assertEquals($amount, 100);
        $amount = $payment->billCreate($amount = 100, $currency = 643, $phone = '+213123');
        $this->assertEquals($amount, 101);
        $this->assertFileExists($path);
        $payment->billCancel(100, $currency = 643, $phone);
        $data = file_get_contents($path);
        $this->assertEquals($data, $amount . Payment::SEPARATOR . $currency . Payment::SEPARATOR . $phone . PHP_EOL);
        $payment->billCancel($amount, $currency = 643, $phone);
        $this->assertFileNotExists($path);
    }

    public function testBillCreate()
    {
        $payment = new Payment($this->token, $this->phone);
        // Первое добавление заявки на оплату
        $amount = $payment->billCreate($amount = 100, $currency = 643);
        $this->assertEquals($amount, 100);
        $path = Payment::getAwaitingPath() . $this->phone . '.txt';
        $this->assertFileExists($path);
        $data = file_get_contents($path);
        $this->assertEquals($amount . Payment::SEPARATOR . $currency . PHP_EOL, $data);

        // Вторая заявка на оплату
        $amount = $payment->billCreate($amount = 101, $currency = 643);
        $this->assertEquals($amount, 101);
        $path = Payment::getAwaitingPath() . $this->phone . '.txt';
        $this->assertFileExists($path);
        $data = file_get_contents($path);
        $this->assertEquals(100 . Payment::SEPARATOR . $currency . PHP_EOL . $amount . Payment::SEPARATOR . $currency . PHP_EOL, $data);

        // Третья заявка на оплату, с занятой суммой
        $amount = $payment->billCreate($amount = 101, $currency = 643);
        $this->assertEquals($amount, 102);
        $path = Payment::getAwaitingPath() . $this->phone . '.txt';
        $this->assertFileExists($path);
        $data = file_get_contents($path);
        $this->assertEquals(100 . Payment::SEPARATOR . $currency . PHP_EOL . 101 . Payment::SEPARATOR . $currency . PHP_EOL .
            102 . Payment::SEPARATOR . $currency . PHP_EOL, $data);

        // Третья заявка на оплату, с занятой суммой
        $amount = $payment->billCreate($amount = 101, $currency = 643);
        $this->assertEquals($amount, 103);
        $path = Payment::getAwaitingPath() . $this->phone . '.txt';
        $this->assertFileExists($path);
        $data = file_get_contents($path);
        $this->assertEquals(100 . Payment::SEPARATOR . $currency . PHP_EOL . 101 . Payment::SEPARATOR . $currency . PHP_EOL .
            102 . Payment::SEPARATOR . $currency . PHP_EOL . 103 . Payment::SEPARATOR . $currency . PHP_EOL, $data);

        // Новый номер с новым файлом
        $payment = new Payment($this->token, $this->phone . '1');
        $amount = $payment->billCreate($amount = 101, $currency = 643);
        $this->assertEquals($amount, 101);
        $path = Payment::getAwaitingPath() . $this->phone . '1' . '.txt';
        $this->assertFileExists($path);
        $data = file_get_contents($path);
        $this->assertEquals(101 . Payment::SEPARATOR . $currency . PHP_EOL, $data);
    }

    public function testBillCancel()
    {
        // Проверяем что удалился платеж
        $payment = new Payment($this->token, $this->phone);
        $currency = 643;
        $path = Payment::getAwaitingPath() . $this->phone . '.txt';
        $this->assertFileExists($path);
        $data = file_get_contents($path);
        $this->assertEquals(100 . Payment::SEPARATOR . $currency . PHP_EOL . 101 . Payment::SEPARATOR . $currency . PHP_EOL . 102 . Payment::SEPARATOR . $currency . PHP_EOL . 103 . Payment::SEPARATOR . $currency . PHP_EOL, $data);
        $payment->billCancel(101, $currency);

        $data = file_get_contents($path);
        $this->assertEquals(100 . Payment::SEPARATOR . $currency . PHP_EOL . 102 . Payment::SEPARATOR . $currency . PHP_EOL . 103 . Payment::SEPARATOR . $currency . PHP_EOL, $data);

        // Проверяем, что удалился файл. Так как в нем всего один платеж
        $payment = new Payment($this->token, $this->phone . '1');
        $payment->billCancel($amount = 101, $currency = 643);
        $path = Payment::getAwaitingPath() . $this->phone . '1' . '.txt';
        $this->assertFileNotExists($path);
    }

    public function testBillCheck()
    {
        // Проверяем что ошибка, если не вызывали метод создания платежа
        $payment = new Payment($this->token, $this->phone);
        try {
            $payment->billCheck($amount = 101, $currency = 643);
            $this->assertEquals(1, 2);
        } catch (QiwiException $e) {
            $this->assertEquals($e->getMessage(), 'not found bills, because you not use method billCreate()');
        }

        // Не находит платеж
        $payment->billCreate($amount = 101231321, $currency = 643);
        $result = $payment->billCheck($amount = 101231321, $currency = 643);
        $this->assertFalse($result);


        // Проверяем, если платеж есть, но он был до нашей проверки уже (Т.е. инициализировался при методе create)
        $payment->billCreate($this->old_amount, $currency = 643);
        $result = $payment->billCheck($this->old_amount, $currency = 643);
        $this->assertFalse($result);


        // Проверить на успешный платеж
        $path = Payment::getWalletPath() . $this->phone . ".txt";
        if (file_exists($path))
            unlink($path);
        $result = '';
        file_put_contents($path, $result);
        $payment->billCreate($this->old_amount, $currency = 643);
        $result = $payment->billCheck($this->old_amount, $currency = 643);
        $this->assertTrue($result);
        // Неверная валюта, проверка
        $payment->billCreate($this->old_amount, $currency = 644);
        $result = $payment->billCheck($this->old_amount, $currency = 644);
        $this->assertFalse($result);
    }

    public function testAddId()
    {
        // Проверяем на добавление
        $payment = new Payment($this->token, $this->phone);
        $payment->create();
        $payment->addId(1);
        $path = Payment::getWalletPath() . $this->phone . ".txt";
        $this->assertFileExists($path);
        $data = file_get_contents($path);
        $dataArray = explode(PHP_EOL, $data);
        $this->assertEquals(count($dataArray), 52);

        // Проверяем на лимит хранения
        for ($i = 0; $i < 500; $i++) {
            $payment->addId($i);
        }
        $data = file_get_contents($path);
        $dataArray = explode(PHP_EOL, $data);
        $this->assertEquals(count($dataArray), 200);
        $this->assertEquals($dataArray[199], 499);
    }


    public function testBillPhone(): void
    {
        $payment = new Payment($this->token, $this->phone);
        $payment->delete();
        $payment->create();
        $amount = $payment->billCreate(100, $currency = 643);
        $amount = $payment->billCreate(100, $currency = 643, $this->leftPhone);
        $this->assertEquals($amount, 100);
        $path = Payment::getAwaitingPath() . $this->phone . '.txt';
        $this->assertFileExists($path);
        $data = file_get_contents($path);
        $this->assertEquals($amount . Payment::SEPARATOR . $currency . PHP_EOL . $amount . Payment::SEPARATOR . $currency . Payment::SEPARATOR . $this->leftPhone . PHP_EOL, $data);
        $amount = $payment->billCreate($amount = 100, $currency = 643, $this->leftPhone);
        $this->assertEquals($amount, 101);

        $this->assertFalse($payment->billCheck($amount = 100, $currency = 643, $this->leftPhone));
        $payment->billCancel($amount = 100, $currency = 643, $this->leftPhone);
        $payment->billCancel($amount = 101, $currency = 643, $this->leftPhone);
        $path = Payment::getAwaitingPath() . $this->phone . '.txt';
        $this->assertFileNotExists($path);

        $path = Payment::getWalletPath() . $this->phone . ".txt";
        if (file_exists($path))
            unlink($path);
        $result = '';
        file_put_contents($path, $result);
        $amount = $payment->billCreate($this->old_amount_phone, $currency = 643, $this->leftPhone);
        $this->assertTrue($payment->billCheck($this->old_amount_phone, $currency = 643, $this->leftPhone));
    }

    public function testDelete(): void
    {
        $payment = new Payment($this->token, $this->phone);
        $payment->delete();
        $this->assertFileNotExists(Payment::getWalletPath() . $this->phone . '.txt');
        $payment = new Payment($this->token, $this->phone . '1');
        $payment->delete();

    }
}