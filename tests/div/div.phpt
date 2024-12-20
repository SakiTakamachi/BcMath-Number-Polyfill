--TEST--
div()
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$lefts = [
    new Number(0),
    new Number(1),
    new Number('1.1'),
    new Number('-2.20'),
];

$rights = [
    3,
    '1.0',
    '-0.5',
    new Number('0.00800'),
];

$scales = [
    ['scale is null', null],
    ['scale is 1', 1],
    ['scale is 4', 4],
    ['scale is 10', 10],
];

foreach ($scales as [$label, $scale]) {
    echo "========== {$label} ==========\n";
    foreach ($lefts as $left) {
        foreach ($rights as $right) {
            $ret = "{$left} / {$right}: ";
            $ret = str_pad($ret, 17, ' ', STR_PAD_LEFT);
            $calcRet = $left->div($right, $scale);
            $leadingPad = substr($calcRet->value, 0, 1) !== '-' ? ' ' : '';
            $ret .= $leadingPad . $calcRet . "\n";
            echo $ret;
        }
    }
    echo "\n";
}
?>
--EXPECT--
========== scale is null ==========
          0 / 3:  0
        0 / 1.0:  0
       0 / -0.5:  0
    0 / 0.00800:  0
          1 / 3:  0.3333333333
        1 / 1.0:  1
       1 / -0.5: -2
    1 / 0.00800:  125
        1.1 / 3:  0.36666666666
      1.1 / 1.0:  1.1
     1.1 / -0.5: -2.2
  1.1 / 0.00800:  137.5
      -2.20 / 3: -0.733333333333
    -2.20 / 1.0: -2.20
   -2.20 / -0.5:  4.40
-2.20 / 0.00800: -275.00

========== scale is 1 ==========
          0 / 3:  0.0
        0 / 1.0:  0.0
       0 / -0.5:  0.0
    0 / 0.00800:  0.0
          1 / 3:  0.3
        1 / 1.0:  1.0
       1 / -0.5: -2.0
    1 / 0.00800:  125.0
        1.1 / 3:  0.3
      1.1 / 1.0:  1.1
     1.1 / -0.5: -2.2
  1.1 / 0.00800:  137.5
      -2.20 / 3: -0.7
    -2.20 / 1.0: -2.2
   -2.20 / -0.5:  4.4
-2.20 / 0.00800: -275.0

========== scale is 4 ==========
          0 / 3:  0.0000
        0 / 1.0:  0.0000
       0 / -0.5:  0.0000
    0 / 0.00800:  0.0000
          1 / 3:  0.3333
        1 / 1.0:  1.0000
       1 / -0.5: -2.0000
    1 / 0.00800:  125.0000
        1.1 / 3:  0.3666
      1.1 / 1.0:  1.1000
     1.1 / -0.5: -2.2000
  1.1 / 0.00800:  137.5000
      -2.20 / 3: -0.7333
    -2.20 / 1.0: -2.2000
   -2.20 / -0.5:  4.4000
-2.20 / 0.00800: -275.0000

========== scale is 10 ==========
          0 / 3:  0.0000000000
        0 / 1.0:  0.0000000000
       0 / -0.5:  0.0000000000
    0 / 0.00800:  0.0000000000
          1 / 3:  0.3333333333
        1 / 1.0:  1.0000000000
       1 / -0.5: -2.0000000000
    1 / 0.00800:  125.0000000000
        1.1 / 3:  0.3666666666
      1.1 / 1.0:  1.1000000000
     1.1 / -0.5: -2.2000000000
  1.1 / 0.00800:  137.5000000000
      -2.20 / 3: -0.7333333333
    -2.20 / 1.0: -2.2000000000
   -2.20 / -0.5:  4.4000000000
-2.20 / 0.00800: -275.0000000000
