## installing the package
PHP Library for working with qiwi.com/api
### From CLI
```$xslt
$ composer config repositories.apirone vcs https://github.com/purt09/qiwi-wallet.git
$ composer require purt09/qiwi-wallet:dev-master
```
### Install in your local
```$xslt
$ composer install
```
## How use?

### Services:
Wallet - To view balance and account information

History - To view the history of payments and find a payment with a comment

Payment - For payment to work without comment! 


### Инструкция(RU):
Библиотека создана для удобной работы с qiwi/api согласно https://developer.qiwi.com/ru/qiwi-wallet-personal/#auth_api

Добавлены не все методы, только те, в которых автор библиотеки видит практический смысл.
Состоит из трех классов.

1) Wallet - для работы с кошельком, основные методы
2) History - для работы и историей кошелька
3) Payment - основной класс для работы с пополнениями кошелька.

Каждый метод имеет описание в интерфейсе. 

#### Как работать с Payment?
Payment - поддерживает работу с несколькими кошельками. Первое, необходимо инициализировать кошелек методом create(), 
важно, что это делается один раз, а не каждый платеж! 

Цикл работы для платежа:
billCreate() - создает заявку на платеж, затем проверка платежа billCheck(). Если платеж найдется, то библиотека сама удалит данные
Если платежа не будет, необходимо самостоятельно отменить платеж billCancel().
Если вы не будете отменять, то система не будет освобождать суммы.

Если вы закончили работать полностью с кошельком, то необходимо удалить его payment->delete()

Готовые примеры использования есть в тестах!

## Unit testing

### Run Tests
```$xslt
$ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/unit/Services/WalletTest.php
$ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/unit/Services/HistoryTest.php
$ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/unit/Services/PaymentTest.php
```
or
```$xslt
$ "vendor/bin/phpunit" --bootstrap vendor/autoload.php tests/unit/Services/WalletTest.php
$ "vendor/bin/phpunit" --bootstrap vendor/autoload.php tests/unit/Services/HistoryTest.php
$ "vendor/bin/phpunit" --bootstrap vendor/autoload.php tests/unit/Services/PaymentTest.php
```
