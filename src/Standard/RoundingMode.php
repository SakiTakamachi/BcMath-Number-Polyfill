<?php

if (!class_exists('RoundingMode', false)) {
    enum RoundingMode {
        case HalfAwayFromZero;
        case HalfTowardsZero;
        case HalfEven;
        case HalfOdd;
        case TowardsZero;
        case AwayFromZero;
        case NegativeInfinity;
        case PositiveInfinity;
    }
}
