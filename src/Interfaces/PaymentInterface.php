<?php

namespace Purt09\QiwiWallet\Interfaces;

/**
 * Для получения истории платежей без комментария, необходимо
 * 1) Хранить уже отработанные платежи
 * 2) Следить, чтобы не совпали суммы активных заявок
 *
 * Поэтому:
 * При добавлении кошелька, мы заполняем уже отработанные платежи
 * При создании заявки на платеж, мы бронируем сумму
 * При отмене или успехе заявки, мы отменяем сумму
 *
 * Отработанные платежи хранятся в папке runtime/wallets
 * Хранится в формате номер телефона - это файл
 * Каждая строка - уже засчитанный платеж.
 * В файле не больше 200-х строк.
 * Новые снизу
 *
 * Ожидающие в папке runtime/awaiting
 * Хранится в формате номер телефона - это файл
 * Каждая строка - "ЭТО_СУММА_В_КОПЕЙКАХ:КОД_ВАЛЮТЫ"
 * Без ограничения по кол-ву строк
 */
interface PaymentInterface
{
    /**
     * Заполняет уже отработанные платежи, проверяет есть ли возможность смотреть историю.
     * Вызывать при добавлении кошелька
     */
    public function create(): void;

    /**
     * Создает заявку на платеж.
     * Проверяет надо разрешена ли сумма (в копейках)
     * Возвращает разрешенную сумму
     * @param int $amount
     * @param int $currency_code - В какой валюте был перевод?
     * @param string|null $phone
     * @return int
     */
    public function billCreate(int $amount, int $currency_code, ?string $phone): int;

    /**
     * Отменяет заявку на платеж
     * @param int $amount
     * @param int $currency_code - В какой валюте был перевод?
     * @param string|null $phone
     */
    public function billCancel(int $amount, int $currency_code, ?string $phone): void;

    /**
     * Проверяет есть ли платеж в истории с указанной суммой
     * true - Платеж найден
     * false - Не найден
     *
     * Примечание:
     * Игнорирует все платежи, которые уже засчитаны
     *
     * @param int $amount
     * @param int $currency_code - В какой валюте был перевод?
     * @param string|null $phone
     * @param string $proxy
     * @return bool
     */
    public function billCheck(int $amount, int $currency_code, ?string $phone = null, string $proxy = ''): bool;

    /**
     * Удаляет все данные о кошельке
     */
    public function delete(): void;
}