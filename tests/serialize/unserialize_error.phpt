--TEST--
__unserialize() - error
--FILE--
<?php
require_once __DIR__ . '/../include.inc';

use BcMath\Number;

$values = [
    'O:13:"BcMath\Number":1:{s:5:"value";i:0;}',
    'O:13:"BcMath\Number":1:{s:5:"scale";s:1:"0";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:2:"5a";}',
    'O:13:"BcMath\Number":1:{s:5:"value";s:5:"1.1.1";}',
];

foreach ($values as $value) {
    try {
        unserialize($value);
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
Invalid serialization data for BcMath\Number object
Invalid serialization data for BcMath\Number object
Invalid serialization data for BcMath\Number object
Invalid serialization data for BcMath\Number object
