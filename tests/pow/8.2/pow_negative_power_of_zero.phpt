--TEST--
pow() - negative power of zero for PHP-8.2
--SKIPIF--
<?php
if (PHP_MAJOR_VERSION !== 8 || PHP_MINOR_VERSION !== 2) {
    die('skip only PHP-8.2');
}
?>
--FILE--
<?php
require_once __DIR__ . '/../../include.inc';

use BcMath\Number;

$number = new Number('0');

$exponents = [
    -1,
    '-2',
    new Number('-3.00'),
];

foreach ($exponents as $exponent) {
    try {
        $number->pow($exponent);
    } catch (Error $e) {
        echo $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
Negative power of zero
Negative power of zero
Negative power of zero
