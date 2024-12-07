--TEST--
__serialize()
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
    echo serialize($number) . "\n";
}
?>
--EXPECT--
O:13:"BcMath\Number":1:{s:5:"value";s:1:"0";}
O:13:"BcMath\Number":1:{s:5:"value";s:1:"0";}
O:13:"BcMath\Number":1:{s:5:"value";s:1:"5";}
O:13:"BcMath\Number":1:{s:5:"value";s:2:"-8";}
O:13:"BcMath\Number":1:{s:5:"value";s:3:"1.2";}
O:13:"BcMath\Number":1:{s:5:"value";s:4:"-0.3";}
O:13:"BcMath\Number":1:{s:5:"value";s:6:"4.5000";}
O:13:"BcMath\Number":1:{s:5:"value";s:8:"-1.20020";}
