--TEST--
construct() - error
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$values = [
    'a',
    '.1.',
    '1.1.1',
    '1.00A',
];

foreach ($values as $value) {
    try {
        new Number($value);
    } catch (Error $e) {
        echo $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
BcMath\Number::__construct(): Argument #1 ($num) is not well-formed
BcMath\Number::__construct(): Argument #1 ($num) is not well-formed
BcMath\Number::__construct(): Argument #1 ($num) is not well-formed
BcMath\Number::__construct(): Argument #1 ($num) is not well-formed
