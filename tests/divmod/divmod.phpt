--TEST--
divmod()
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
            $ret = "{$left} divmod {$right}: ";
            $ret = str_pad($ret, 22, ' ', STR_PAD_LEFT);
            [$quot, $rem] = $left->divmod($right, $scale);
            $quot = str_pad($quot, 4, ' ', STR_PAD_LEFT);
            $rem = str_pad($rem, $scale + 3, ' ', STR_PAD_LEFT);
            $ret .= "{$quot}, {$rem}\n";
            echo $ret;
        }
    }
    echo "\n";
}
?>
--EXPECT--
========== scale is null ==========
          0 divmod 3:    0,   0
        0 divmod 1.0:    0, 0.0
       0 divmod -0.5:    0, 0.0
    0 divmod 0.00800:    0, 0.00000
          1 divmod 3:    0,   1
        1 divmod 1.0:    1, 0.0
       1 divmod -0.5:   -2, 0.0
    1 divmod 0.00800:  125, 0.00000
        1.1 divmod 3:    0, 1.1
      1.1 divmod 1.0:    1, 0.1
     1.1 divmod -0.5:   -2, 0.1
  1.1 divmod 0.00800:  137, 0.00400
      -2.20 divmod 3:    0, -2.20
    -2.20 divmod 1.0:   -2, -0.20
   -2.20 divmod -0.5:    4, -0.20
-2.20 divmod 0.00800: -275, 0.00000

========== scale is 1 ==========
          0 divmod 3:    0,  0.0
        0 divmod 1.0:    0,  0.0
       0 divmod -0.5:    0,  0.0
    0 divmod 0.00800:    0,  0.0
          1 divmod 3:    0,  1.0
        1 divmod 1.0:    1,  0.0
       1 divmod -0.5:   -2,  0.0
    1 divmod 0.00800:  125,  0.0
        1.1 divmod 3:    0,  1.1
      1.1 divmod 1.0:    1,  0.1
     1.1 divmod -0.5:   -2,  0.1
  1.1 divmod 0.00800:  137,  0.0
      -2.20 divmod 3:    0, -2.2
    -2.20 divmod 1.0:   -2, -0.2
   -2.20 divmod -0.5:    4, -0.2
-2.20 divmod 0.00800: -275,  0.0

========== scale is 4 ==========
          0 divmod 3:    0,  0.0000
        0 divmod 1.0:    0,  0.0000
       0 divmod -0.5:    0,  0.0000
    0 divmod 0.00800:    0,  0.0000
          1 divmod 3:    0,  1.0000
        1 divmod 1.0:    1,  0.0000
       1 divmod -0.5:   -2,  0.0000
    1 divmod 0.00800:  125,  0.0000
        1.1 divmod 3:    0,  1.1000
      1.1 divmod 1.0:    1,  0.1000
     1.1 divmod -0.5:   -2,  0.1000
  1.1 divmod 0.00800:  137,  0.0040
      -2.20 divmod 3:    0, -2.2000
    -2.20 divmod 1.0:   -2, -0.2000
   -2.20 divmod -0.5:    4, -0.2000
-2.20 divmod 0.00800: -275,  0.0000

========== scale is 10 ==========
          0 divmod 3:    0,  0.0000000000
        0 divmod 1.0:    0,  0.0000000000
       0 divmod -0.5:    0,  0.0000000000
    0 divmod 0.00800:    0,  0.0000000000
          1 divmod 3:    0,  1.0000000000
        1 divmod 1.0:    1,  0.0000000000
       1 divmod -0.5:   -2,  0.0000000000
    1 divmod 0.00800:  125,  0.0000000000
        1.1 divmod 3:    0,  1.1000000000
      1.1 divmod 1.0:    1,  0.1000000000
     1.1 divmod -0.5:   -2,  0.1000000000
  1.1 divmod 0.00800:  137,  0.0040000000
      -2.20 divmod 3:    0, -2.2000000000
    -2.20 divmod 1.0:   -2, -0.2000000000
   -2.20 divmod -0.5:    4, -0.2000000000
-2.20 divmod 0.00800: -275,  0.0000000000
