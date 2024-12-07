--TEST--
construct()
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
    var_dump((array) $number);
}
?>
--EXPECT--
array(2) {
  ["value"]=>
  string(1) "0"
  ["scale"]=>
  int(0)
}
array(2) {
  ["value"]=>
  string(1) "0"
  ["scale"]=>
  int(0)
}
array(2) {
  ["value"]=>
  string(1) "5"
  ["scale"]=>
  int(0)
}
array(2) {
  ["value"]=>
  string(2) "-8"
  ["scale"]=>
  int(0)
}
array(2) {
  ["value"]=>
  string(3) "1.2"
  ["scale"]=>
  int(1)
}
array(2) {
  ["value"]=>
  string(4) "-0.3"
  ["scale"]=>
  int(1)
}
array(2) {
  ["value"]=>
  string(6) "4.5000"
  ["scale"]=>
  int(4)
}
array(2) {
  ["value"]=>
  string(8) "-1.20020"
  ["scale"]=>
  int(5)
}
