<?php

namespace Saki\Number;

use Saki\Number\ScaleCheckerTraits\HasFractionalPart;

if (PHP_INT_SIZE === 8) {
    // for 64-bit
    class ScaleChecker
    {
        use HasFractionalPart;

        private const INT_MAX = 2147483647;
        public static function isOverflow(int $scale, int $originScale): bool
        {
            return $scale > self::INT_MAX;
        }
    }
} else {
    // for 32-bit
    class ScaleChecker
    {
        use HasFractionalPart;

        public static function isOverflow(int $scale, int $originScale): bool
        {
            return $scale > PHP_INT_MAX || $scale < $originScale;
        }
    }
}

