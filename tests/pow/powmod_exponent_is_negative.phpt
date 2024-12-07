--TEST--
powmod() - exponent is negative
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$number = new Number('5');

try {
    (new Number('1'))->powmod('-2', '3');
} catch (Error $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
BcMath\Number::powmod(): Argument #1 ($exponent) must be greater than or equal to 0
