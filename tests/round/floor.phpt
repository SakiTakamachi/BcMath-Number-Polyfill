--TEST--
floor()
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$values = [
    new Number('0'),
    new Number('1'),
    new Number('-1'),
    new Number('1.1'),
    new Number('-1.1'),
    new Number('4.00001'),
    new Number('-4.00001'),
];

foreach ($values as $value) {
    echo $value->floor() . "\n";
}
?>
--EXPECT--
0
1
-1
1
-2
4
-5
