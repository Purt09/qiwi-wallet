## installing the package
SDK for working with qiwi.com/api
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
