--TEST--
construct()
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$origins = [
    new Number('1'),
    new Number('2.1'),
    new Number('3.100'),
    new Number('-1'),
    new Number('-2.1'),
    new Number('-3.100'),
];

foreach ($origins as $origin) {
    echo "========== {$origin} ==========\n";
    $clone = clone $origin;
    var_dump(
        (array) $clone,
        (array) $clone->add(1),
    );
    echo "\n";
}
?>
--EXPECT--
========== 1 ==========
array(2) {
  ["value"]=>
  string(1) "1"
  ["scale"]=>
  int(0)
}
array(2) {
  ["value"]=>
  string(1) "2"
  ["scale"]=>
  int(0)
}

========== 2.1 ==========
array(2) {
  ["value"]=>
  string(3) "2.1"
  ["scale"]=>
  int(1)
}
array(2) {
  ["value"]=>
  string(3) "3.1"
  ["scale"]=>
  int(1)
}

========== 3.100 ==========
array(2) {
  ["value"]=>
  string(5) "3.100"
  ["scale"]=>
  int(3)
}
array(2) {
  ["value"]=>
  string(5) "4.100"
  ["scale"]=>
  int(3)
}

========== -1 ==========
array(2) {
  ["value"]=>
  string(2) "-1"
  ["scale"]=>
  int(0)
}
array(2) {
  ["value"]=>
  string(1) "0"
  ["scale"]=>
  int(0)
}

========== -2.1 ==========
array(2) {
  ["value"]=>
  string(4) "-2.1"
  ["scale"]=>
  int(1)
}
array(2) {
  ["value"]=>
  string(4) "-1.1"
  ["scale"]=>
  int(1)
}

========== -3.100 ==========
array(2) {
  ["value"]=>
  string(6) "-3.100"
  ["scale"]=>
  int(3)
}
array(2) {
  ["value"]=>
  string(6) "-2.100"
  ["scale"]=>
  int(3)
}
