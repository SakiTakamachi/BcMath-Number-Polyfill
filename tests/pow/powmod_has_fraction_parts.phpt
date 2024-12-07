--TEST--
powmod() - has fraction parts
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$number = new Number('5');

try {
    (new Number('1.1'))->powmod('2', '3');
} catch (Error $e) {
    echo $e->getMessage() . "\n";
}

try {
    (new Number('1'))->powmod('2.1', '3');
} catch (Error $e) {
    echo $e->getMessage() . "\n";
}

try {
    (new Number('1'))->powmod('2', '3.1');
} catch (Error $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
Base number cannot have a fractional part
BcMath\Number::powmod(): Argument #1 ($exponent) cannot have a fractional part
BcMath\Number::powmod(): Argument #2 ($modulus) cannot have a fractional part
