<?php

namespace Saki\Number\ScaleCheckerTraits;

use Exception;

if (PHP_MAJOR_VERSION === 8) {
    switch (PHP_MINOR_VERSION) {
        case 2:;
            trait HasFractionalPart
            {
                public static function hasFractionalPart(int $scale, int $realScale): bool
                {
                    return $scale > 0;
                }
            }
            break;
    
        case 3:;
            trait HasFractionalPart
            {
                public static function hasFractionalPart(int $scale, int $realScale): bool
                {
                    return $realScale > 0;
                }
            }
            break;

        default:
            throw new Exception('Unsupported PHP version');
    }
} else {
    throw new Exception('Unsupported PHP version');
}
