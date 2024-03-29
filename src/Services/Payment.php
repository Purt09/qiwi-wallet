<?php

namespace Purt09\QiwiWallet\Services;

use Purt09\QiwiWallet\Exceprtion\QiwiException;
use Purt09\QiwiWallet\Interfaces\PaymentInterface;
use Purt09\QiwiWallet\Traits\Api;

class Payment implements PaymentInterface
{
    use Api;

    const PATH_WALLET = '/runtime/wallets/';
    const PATH_AWAITING =  '/runtime/awaiting/';
    /**
     * Разделитель между AMOUNT и CURRENCY_CODE и phone
     */
    const SEPARATOR = ':';

    // Лимит хранения в файле runtime/wallets/phone.txt
    private $limit = 200;

    public static function getWalletPath()
    {
        return dirname(__DIR__, 2) . self::PATH_WALLET;
    }

    public static function getAwaitingPath()
    {
        return dirname(__DIR__, 2) . self::PATH_AWAITING;
    }

    public function create(string $proxy = ''): void
    {
        $history = new History($this->token, $this->phone);
        $data = $history->getHistory('IN', 50, $proxy);
        $path = self::getWalletPath() . $this->phone . ".txt";
        $result = '';
        foreach ($data['data'] as $item) {
            $result .= $item['txnId'] . PHP_EOL;
        }

        file_put_contents($path, $result);
    }

    public function billCreate(int $amount, int $currency_code, ?string $phone = null): int
    {
        $path = self::getAwaitingPath() . $this->phone . ".txt";
        if (file_exists($path)) {
            $data = file_get_contents($path);
            $dataArray = explode(PHP_EOL, $data);
            $amount = $this->recursiveSearch($dataArray, $amount, $currency_code, $phone);
        } else {
            $data = '';
        }
        if(isset($phone)) {
            $data .= $amount . self::SEPARATOR . $currency_code . self::SEPARATOR . $phone . PHP_EOL;
        } else {
            $data .= $amount . self::SEPARATOR . $currency_code . PHP_EOL;
        }
        file_put_contents($path, $data);
        return $amount;
    }

    /**
     * Подбираем сумму, увеличивая на копейку каждый раз!
     * @param array $dataArray
     * @param int $amount
     * @param int $currency_code
     * @param string|null $phone
     * @return int
     */
    private function recursiveSearch(array $dataArray, int $amount, int $currency_code, ?string $phone = null): int
    {
        // Перебирает строки
        foreach ($dataArray as $item) {
            if(isset($phone)) {
                $data = $amount . self::SEPARATOR . $currency_code . self::SEPARATOR . $phone;
            } else {
                $data = $amount . self::SEPARATOR . $currency_code ;
            }
            if($item == $data) {
                $amount++;
                return $this->recursiveSearch($dataArray, $amount, $currency_code, $phone);
            }
        }
        return $amount;
    }

    public function billCancel(int $amount, int $currency_code, ?string $phone = null): void
    {
        $path = self::getAwaitingPath() . $this->phone . ".txt";
        if (file_exists($path)) {
            if(isset($phone)) {
                $data = $amount . self::SEPARATOR . $currency_code . self::SEPARATOR . $phone;
            } else {
                $data = $amount . self::SEPARATOR . $currency_code ;
            }
            $this->deleteAwaiting($data);
        } else {
            throw new QiwiException('not found bills');
        }
    }

    public function billCheck(int $amount, int $currency_code, ?string $phone = null, string $proxy = ''): bool
    {
        $pathAwaiting = self::getAwaitingPath() . $this->phone . ".txt";
        if (file_exists($pathAwaiting)) {
            $data = file_get_contents($pathAwaiting);
            $dataArray = explode(PHP_EOL, $data);
            $isFound = false;
            foreach ($dataArray as $key => $item) {
                if(isset($phone)) {
                    $data = $amount . self::SEPARATOR . $currency_code . self::SEPARATOR . $phone;
                } else {
                    $data = $amount . self::SEPARATOR . $currency_code ;
                }
                if(intval($item) == $data) {
                    $isFound = true;
                }
            }
            if(!$isFound)
                throw new QiwiException('not found bills, because you not use method billCreate()');


            $pathWallets = self::getWalletPath() . $this->phone . ".txt";
            $data = file_get_contents($pathWallets);
            // Тут номера заказов
            $dataArray = explode(PHP_EOL, $data);
            $history = new History($this->token, $this->phone);
            // Тут новая история транзакций
            $dataTransaction = $history->getHistory('IN', 50, $proxy);
            // Проверяем есть ли совпадения в истории
            $isPay = false;
            $amountQiwi = $amount / 100;
            foreach ($dataTransaction['data'] as $item) {
                if($item['sum']['amount'] == $amountQiwi && $item['sum']['currency'] == $currency_code) {
                    if(isset($phone)) {
                        if($item['account'] != $phone)
                            continue;
                    }
                    $isPay = true;
                    foreach ($dataArray as $value) {
                        if($item['txnId'] == $value) {
                            $isPay = false;
                            break;
                        }
                    }
                    // Нашли платеж, добавляем его в историю и удаляем и awaiting
                    if($isPay) {
                        if(isset($phone)) {
                            $data = $amount . self::SEPARATOR . $currency_code . self::SEPARATOR . $phone;
                        } else {
                            $data = $amount . self::SEPARATOR . $currency_code ;
                        }
                        $this->deleteAwaiting($data);
                        $this->addId($item['txnId']);
                        break;
                    }
                }
            }
            return $isPay;
        } else {
            throw new QiwiException('not found bills');
        }
    }

    /**
     * Удаляет ожидающий платеж
     * @param string $data
     */
    public function deleteAwaiting(string $data): void
    {
        $path = self::getAwaitingPath() . $this->phone . ".txt";
        $dataNew = file_get_contents($path);
        $dataArray = explode(PHP_EOL, $dataNew);
        foreach ($dataArray as $key => $item) {
            if(intval($item) == $data) {
                unset($dataArray[$key]);
            }
        }
        // Если сохранять нечего, удаляем файл
        $data = implode(PHP_EOL, $dataArray);
        if(empty($data)) {
            unlink($path);
        } else {
            file_put_contents($path, $data);
        }
    }

    /**
     * Добавляет в кошелек новую транзакцию
     * Контролирует, чтобы их было не больше лимита
     * @param int $id
     */
    public function addId(int $id): void
    {
        $path = self::getWalletPath() . $this->phone . ".txt";
        $data = file_get_contents($path);
        $dataArray = explode(PHP_EOL, $data);
        array_push($dataArray, $id);

        if(count($dataArray) > $this->limit)
            unset($dataArray[0]);

        $result = implode(PHP_EOL, $dataArray);
        file_put_contents($path, $result);
    }

    public function delete(): void
    {
        $path = self::getWalletPath() . $this->phone . ".txt";
        if (file_exists($path))
            unlink($path);
        $path = self::getAwaitingPath() . $this->phone . ".txt";
        if (file_exists($path))
            unlink($path);
    }
}