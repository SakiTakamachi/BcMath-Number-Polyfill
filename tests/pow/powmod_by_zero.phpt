--TEST--
powmod() - modulus by zero for PHP-8.3
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$number = new Number('5');

$mods = [
    0,
    '-0',
    '-0.0',
    new Number('0.00'),
];

foreach ($mods as $mod) {
    try {
        $number->powmod('2', $mod);
    } catch (Error $e) {
        echo $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
Modulo by zero
Modulo by zero
Modulo by zero
Modulo by zero
