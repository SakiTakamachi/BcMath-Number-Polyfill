# BcMath\Number Polyfill

[![Latest Stable Version](https://poser.pugx.org/saki/bcmath-number-polyfill/v)](https://packagist.org/packages/saki/bcmath-number-polyfill)
[![Push](https://github.com/SakiTakamachi/BcMath-Number-Polyfill/actions/workflows/push.yml/badge.svg)](https://github.com/SakiTakamachi/BcMath-Number-Polyfill/actions/workflows/push.yml)
[![codecov](https://codecov.io/gh/SakiTakamachi/BcMath-Number-Polyfill/graph/badge.svg?token=SEYCY57LRY)](https://codecov.io/gh/SakiTakamachi/BcMath-Number-Polyfill)
[![License](https://poser.pugx.org/saki/bcmath-number-polyfill/license)](https://packagist.org/packages/saki/bcmath-number-polyfill)
[![PHP Version Require](https://poser.pugx.org/saki/bcmath-number-polyfill/require/php)](https://packagist.org/packages/saki/bcmath-number-polyfill)

## Install

```
composer require saki/bcmath-number-polyfill
```

This library requires the BCMath extension.
Also, since this is a polyfill, it does **not support** PHP 8.4 or higher. For PHP 8.2 and 8.3 only.

## Description

This is a polyfill for `BcMath\Number` class added in PHP 8.4.
It achieves the same behavior as much as possible, but does **not support** operator overloading.

`RoundingMode` Enum is also included. However, if it has already been defined, it will not be defined twice.

New functions such as `bcround()` are not included in this library.

## How to use

You can treat it like anything added in PHP 8.4.

```
use BcMath\Number;

$number = new Number('1.23');
$rounded = $number->round(1, RoundingMode::AwayFromZero);

var_dump($rounded);
```

result:
```
object(BcMath\Number)#3 (2) {
  ["value"]=>
  string(3) "1.3"
  ["scale"]=>
  int(1)
}
```
