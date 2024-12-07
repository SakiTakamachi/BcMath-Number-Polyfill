--TEST--
divmod() - division by zero
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$number = new Number('5.1');

$rights = [
    0,
    '-0',
    '-0.0',
    new Number('0.00'),
];

foreach ($rights as $right) {
    try {
        $number->divmod($right);
    } catch (Error $e) {
        echo $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
Division by zero
Division by zero
Division by zero
Division by zero
