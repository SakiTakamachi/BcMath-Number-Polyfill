<?php

require_once __DIR__ . '/../include.inc';

use BcMath\Number;

function run_round_test(RoundingMode $mode)
{
    $values = [
        new Number('0'),
        new Number('4'),
        new Number('-5'),
        new Number('6'),
        new Number('-0.04'),
        new Number('0.05'),
        new Number('-0.06'),
        new Number('0.0050001'),
        new Number('0.005'),
        new Number('-0.0050010'),
        new Number('0.0050001'),
        new Number('-0.0050000'),
        new Number('150'),
        new Number('-150'),
        new Number('150.000'),
        new Number('-150.001'),
    ];

    $precisions = [
        -3,
        -2,
        -1,
        0,
        1,
        2,
        3,
        5,
        10,
    ];

    foreach ($precisions as $precision) {
        echo "========== precision is {$precision} ==========\n";
        foreach ($values as $value) {
            $output = str_pad($value . ': ', 12, ' ', STR_PAD_LEFT);
            $ret = $value->round($precision, $mode);
            $output .= $ret->compare(0) === -1 ? '' : ' ';
            echo $output . $ret . "\n";
        }
        echo "\n";
    }
}
