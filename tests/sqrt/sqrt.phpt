--TEST--
sqrt()
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$values = [
    new Number(0),
    new Number(2),
    new Number('4.00'),
    new Number('0.04000'),
];

$scales = [
    ['scale is null', null],
    ['scale is 1', 1],
    ['scale is 4', 4],
    ['scale is 10', 10],
];

foreach ($scales as [$label, $scale]) {
    echo "========== {$label} ==========\n";
    foreach ($values as $value) {
        $ret = "sqrt of {$value}: ";
        $ret = str_pad($ret, 17, ' ', STR_PAD_LEFT);
        $ret .= $value->sqrt($scale) . "\n";
        echo $ret;
    }
    echo "\n";
}
?>
--EXPECT--
========== scale is null ==========
      sqrt of 0: 0
      sqrt of 2: 1.4142135623
   sqrt of 4.00: 2.00
sqrt of 0.04000: 0.20000

========== scale is 1 ==========
      sqrt of 0: 0.0
      sqrt of 2: 1.4
   sqrt of 4.00: 2.0
sqrt of 0.04000: 0.2

========== scale is 4 ==========
      sqrt of 0: 0.0000
      sqrt of 2: 1.4142
   sqrt of 4.00: 2.0000
sqrt of 0.04000: 0.2000

========== scale is 10 ==========
      sqrt of 0: 0.0000000000
      sqrt of 2: 1.4142135623
   sqrt of 4.00: 2.0000000000
sqrt of 0.04000: 0.2000000000
