<?php
declare(strict_types=1);

namespace Purt09\QiwiWallet\Interfaces;


interface WalletInterface
{
    /**
     * Получение данных об аккаунте
     * @return array
     */
    public function getProfile(): ?array;

    /**
     * Получение данных о балансе кошелька
     * @return array
     */
    public function getBalance(): ?array;

    /**
     * Проверяет заблокирован ли кошелёк на исходящие платежи
     * @return array
     */
    public function checkRestrictions(): array;
}