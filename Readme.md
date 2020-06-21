## installing the package
SDK for working with apirone.com
### From CLI
```$xslt
$ composer require purt09/apirone:dev-master
```
## Unit testing

### Install in your local
```$xslt
$ composer install
```
### Run Tests
```$xslt
$ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/unit/Services/WalletTest.php
$ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/unit/Services/NetworkFeeTest.php
$ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/unit/Services/CourseTest.php
```
or
```$xslt
$ "vendor/bin/phpunit" --bootstrap vendor/autoload.php tests/unit/Services/WalletTest.php
$ "vendor/bin/phpunit" --bootstrap vendor/autoload.php tests/unit/Services/NetworkFeeTest.php
$ "vendor/bin/phpunit" --bootstrap vendor/autoload.php tests/unit/Services/CourseTest.php
```
