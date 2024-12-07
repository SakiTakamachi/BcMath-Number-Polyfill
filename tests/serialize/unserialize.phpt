--TEST--
__unserialize()
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

$values = [
    'O:13:"BcMath\Number":1:{s:5:"value";s:1:"0";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:1:"0";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:1:"5";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:2:"-8";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:3:"1.2";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:4:"-0.3";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:6:"4.5000";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:8:"-1.20020";}',
];

foreach ($values as $value) {
    $number = unserialize($value);
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
