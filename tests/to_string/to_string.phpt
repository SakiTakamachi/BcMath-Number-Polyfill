--TEST--
__toString()
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$values = [
    0,
    -0,
    5,
    -8,
    '1.2',
    '-0.3',
    '4.5000',
    '-1.20020',
];

foreach ($values as $value) {
    $number = new Number($value);
    echo $number . "\n";
}
?>
--EXPECT--
0
0
5
-8
1.2
-0.3
4.5000
-1.20020
