<?php

namespace BcMath;

use Exception;
use RoundingMode;
use Saki\Number\Enum\ErrorType;
use Saki\Number\Enum\Sign;
use Saki\Number\MetaData;
use Saki\Number\ScaleChecker;
use Stringable;
use ValueError;

final readonly class Number implements Stringable
{
    public string $value;
    public int $scale;

    public function __construct(string|int $num)
    {
        if (is_int($num)) {
            $this->value = (string) $num;
            $this->scale = 0;
            MetaData::setMetaData($this, $this->value, 0);
            return;
        }

        $parsed = $this->parseStringNumber($num);
        if ($parsed === false) {
            $this->throwError(ErrorType::NotWellFormed, __METHOD__, 'num', 1);
        }

        [
            $this->value,
            $this->scale,
            $realValue,
            $realScale,
        ] = $parsed;
        MetaData::setMetaData($this, $realValue, $realScale);
    }

    public function __destruct()
    {
        MetaData::deleteMetaData($this);
    }

    private function getMetaData(): array
    {
        return MetaData::getMetaData($this);
    }

    private function getRealValue(): string
    {
        $metaData = $this->getMetaData();
        return $metaData[0];
    }

    private function getRealScale(): int
    {
        $metaData = $this->getMetaData();
        return $metaData[1];
    }

    private function quickThreeWayComparisonWithZero(): int
    {
        $realValue = $this->getRealValue();

        if ($realValue === '0') {
            return 0;
        }

        $firstChar = substr($realValue, 0, 1);
        if ($firstChar === '-') {
            return -1;
        }
        return 1;
    }

    /** @return array{string, int, string, int} */
    private function parseStringNumber(string $num): array|false
    {
        $numParts = explode('.', $num);
        if (count($numParts) > 2) {
            return false;
        }

        if (strlen($numParts[0]) === 0) {
            $numParts[0] = '0';
        }

        $sign = substr($numParts[0], 0, 1) === '-' ? '-' : '';
        if (in_array(substr($numParts[0], 0, 1), ['-', '+'], true)) {
            $numParts[0] = substr($numParts[0], 1);
        }

        if (!preg_match('/^[0-9]+$/', $numParts[0])) {
            return false;
        }

        $leadingZeroes = strspn($numParts[0], '0');
        if ($leadingZeroes > 0) {
            $numParts[0] = substr($numParts[0], $leadingZeroes);
        }

        if (strlen($numParts[0]) === 0) {
            $numParts[0] = '0';
        }

        if (count($numParts) === 1 || strlen($numParts[1]) === 0) {
            $value = "{$sign}{$numParts[0]}";
            return [$value, 0, $value, 0];
        }

        if (!preg_match('/^[0-9]+$/', $numParts[1])) {
            return false;
        }

        $scale = strlen($numParts[1]);
        $realScale = $scale;
        while ($realScale > 0) {
            if (str_ends_with($numParts[1], '0')) {
                $numParts[1] = substr($numParts[1], 0, -1);
                $realScale--;
            } else {
                break;
            }
        }

        $realValue = $realScale > 0 ? "{$sign}{$numParts[0]}.{$numParts[1]}" : "{$sign}{$numParts[0]}";

        $scaleDiff = $scale - $realScale;
        $decimalPoint = $realScale === 0 ? '.' : '';

        $value = $scaleDiff > 0
            ? $realValue  . $decimalPoint . str_repeat('0', $scaleDiff)
            : $realValue;

        return [
            $value,
            $scale,
            $realValue,
            $realScale,
        ];
    }

    private function throwError(ErrorType $errorType, string $methodName = '', string $argName = '', int $argNum = 0): void
    {
        switch ($errorType) {
            case ErrorType::NotWellFormed:
                throw new ValueError("{$methodName}(): Argument #{$argNum} (\${$argName}) is not well-formed");

            case ErrorType::TooLargeScale:
                throw new ValueError('scale of the result is too large');

            case ErrorType::PowmodHasFractionParts:
                throw new ValueError("{$methodName}(): Argument #{$argNum} (\${$argName}) cannot have a fractional part");

            case ErrorType::PowmodExponentIsNegative:
                throw new ValueError("{$methodName}(): Argument #{$argNum} (\${$argName}) must be greater than or equal to 0");

            case ErrorType::InvalidSerializationData:
                $className = Number::class;
                throw new Exception("Invalid serialization data for {$className} object");
        }
    }

    public function add(Number|string|int $num, ?int $scale = null): Number
    {
        return $this->doAddSub(true, $num, $scale);
    }

    public function sub(Number|string|int $num, ?int $scale = null): Number
    {
        return $this->doAddSub(false, $num, $scale);
    }

    private function doAddSub(bool $isAdd, Number|string|int $num, ?int $scale = null): Number
    {
        $num = $num instanceof Number ? $num : new Number($num);
        $scale = $scale ?? max($this->scale, $num->scale);
        $result = $isAdd
            ? bcadd($this->getRealValue(), $num->getRealValue(), $scale)
            : bcsub($this->getRealValue(), $num->getRealValue(), $scale);
        return new Number($result);
    }

    public function mul(Number|string|int $num, ?int $scale = null): Number
    {
        $num = $num instanceof Number ? $num : new Number($num);
        if (!isset($scale)) {
            $scale = $this->scale + $num->scale;
            if (ScaleChecker::isOverflow($scale, $this->scale)) {
                $this->throwError(ErrorType::TooLargeScale);
            }
        }
        $scale = $scale ?? $this->scale + $num->scale;
        $result = bcmul($this->getRealValue(), $num->getRealValue(), $scale);
        return new Number($result);
    }

    private function extendScale(int $scale): int
    {
        return $scale + 10;
    }

    private function removeUnnecessaryExtendedScale(string $num): string
    {
        for ($i = 0; $i < 10; $i++) {
            if (str_ends_with($num, '0')) {
                $num = substr($num, 0, -1);
            } else {
                break;
            }
        }
        return $num;
    }

    public function div(Number|string|int $num, ?int $scale = null): Number
    {
        $scaleIsNull = is_null($scale);
        $num = $num instanceof Number ? $num : new Number($num);
        if (!isset($scale)) {
            $scale = $this->extendScale($this->scale);
            if (ScaleChecker::isOverflow($scale, $this->scale)) {
                $this->throwError(ErrorType::TooLargeScale);
            }
        }
        $result = bcdiv($this->getRealValue(), $num->getRealValue(), $scale);
        return new Number(
            $scaleIsNull
                ? $this->removeUnnecessaryExtendedScale($result)
                : $result
        );
    }

    public function mod(Number|string|int $num, ?int $scale = null): Number
    {
        $num = $num instanceof Number ? $num : new Number($num);
        $scale = $scale ?? max($this->scale, $num->scale);
        $result = bcmod($this->getRealValue(), $num->getRealValue(), $scale);
        return new Number($result);
    }

    /** @return Number[] */
    public function divmod(Number|string|int $num, ?int $scale = null): array
    {
        $num = $num instanceof Number ? $num : new Number($num);
        $scale = $scale ?? max($this->scale, $num->scale);
        $realValue = $this->getRealValue();
        $quot = bcdiv($realValue, $num->getRealValue(), 0);
        $rem = bcmod($realValue, $num->getRealValue(), $scale);
        return [
            new Number($quot),
            new Number($rem),
        ];
    }

    public function powmod(Number|string|int $exponent, Number|string|int $modulus, ?int $scale = null): Number
    {
        [$realValue, $realScale] = $this->getMetaData();
        if ($realScale > 0) {
            throw new ValueError('Base number cannot have a fractional part');
        }

        $exponent = $exponent instanceof Number ? $exponent : new Number($exponent);
        [$exponentRealValue, $exponentRealScale] = $exponent->getMetaData();
        if ($exponentRealScale > 0) {
            $this->throwError(ErrorType::PowmodHasFractionParts, __METHOD__, 'exponent', 1);
        }
        if ($exponent->quickThreeWayComparisonWithZero() === -1) {
            $this->throwError(ErrorType::PowmodExponentIsNegative, __METHOD__, 'exponent', 1);
        }

        $modulus = $modulus instanceof Number ? $modulus : new Number($modulus);
        [$modulusRealValue, $modulusRealScale] = $modulus->getMetaData();
        if ($modulusRealScale > 0) {
            $this->throwError(ErrorType::PowmodHasFractionParts, __METHOD__, 'modulus', 2);
        }

        $scale = $scale ?? 0;
        $result = bcpowmod($realValue, $exponentRealValue, $modulusRealValue, $scale);
        return new Number($result);
    }

    public function pow(Number|string|int $exponent, ?int $scale = null): Number
    {
        $scaleIdNull = is_null($scale);
        $exponent = $exponent instanceof Number ? $exponent : new Number($exponent);

        switch ($exponent->quickThreeWayComparisonWithZero()) {
            case -1:
                if ($this->quickThreeWayComparisonWithZero() === 0) {
                    throw new ValueError('Negative power of zero');
                }
                if (!isset($scale)) {
                    $scale = $this->extendScale($this->scale);
                    if (ScaleChecker::isOverflow($scale, $this->scale)) {
                        $this->throwError(ErrorType::TooLargeScale);
                    }
                }
                $result = bcpow($this->getRealValue(), $exponent->getRealValue(), $scale);
                return new Number(
                    $scaleIdNull
                        ? $this->removeUnnecessaryExtendedScale($result)
                        : $result
                );
            case 0:
                $scale = $scale ?? 0;
                $fraction = $scale > 0 ? '.' . str_repeat('0', $scale) : '';
                return new Number('1' . $fraction);
            case 1:
                if (!isset($scale)) {
                    $scaleStr = bcmul((string) $this->scale, $exponent->getRealValue());
                    $scale = (int) $scaleStr;
                    if ((string) $scale !== $scaleStr || ScaleChecker::isOverflow($scale, $this->scale)) {
                        $this->throwError(ErrorType::TooLargeScale);
                    }
                }
                return new Number(bcpow($this->getRealValue(), $exponent->getRealValue(), $scale));
        }
    }

    public function sqrt(?int $scale = null): Number
    {
        $scaleIdNull = is_null($scale);
        $scale = $scale ?? $this->extendScale($this->scale);
        $result = bcsqrt($this->getRealValue(), $scale);
        return new Number(
            $scaleIdNull
                ? $this->removeUnnecessaryExtendedScale($result)
                : $result
        );
    }

    public function floor(): Number
    {
        $realValue = $this->getRealValue();
        if ($this->quickThreeWayComparisonWithZero() >= 0) {
            [$result] = explode('.', $realValue);
        } else {
            if ($this->getRealScale() > 0) {
                $result = bcadd($realValue, '-1', 0);
            } else {
                [$result] = explode('.', $realValue);
            }
        }
        return new Number($result);
    }

    public function ceil(): Number
    {
        $realValue = $this->getRealValue();
        if ($this->quickThreeWayComparisonWithZero() >= 0) {
            if ($this->getRealScale() > 0) {
                $result = bcadd($realValue, '1', 0);
            } else {
                [$result] = explode('.', $realValue);
            }
        } else {
            [$result] = explode('.', $realValue);
        }
        return new Number($result);
    }

    public function round(int $precision = 0, RoundingMode $mode = RoundingMode::HalfAwayFromZero): Number
    {
        [$realValue, $realScale] = $this->getMetaData();
        if ($realScale <= $precision) {
            $trailingZeroes = ($realScale === 0 ? '.' : '') . str_repeat('0', $precision - $realScale);
            return new Number($realValue . $trailingZeroes);
        }

        $sign = substr($realValue, 0, 1) === '-' ? Sign::Minus : Sign::Plus;

        $numParts = explode('.', $realValue);
        if ($sign === Sign::Minus) {
            $numParts[0] = substr($numParts[0], 1);
        }
        $integerPart = $numParts[0];
        $integerPartLen = strlen($numParts[0]);

        if ($integerPartLen < -$precision) {
            switch ($mode) {
                case RoundingMode::HalfAwayFromZero:
                case RoundingMode::HalfTowardsZero:
                case RoundingMode::HalfEven:
                case RoundingMode::HalfOdd:
                case RoundingMode::TowardsZero:
                    return new Number(0);

                case RoundingMode::NegativeInfinity:
                    if ($sign === Sign::Plus) {
                        return new Number(0);
                    }
                    break;

                case RoundingMode::PositiveInfinity:
                    if ($sign === Sign::Minus) {
                        return new Number(0);
                    }
                    break;
            }

            if ($realValue === '0') {
                return new Number(0);
            }

            return new Number(($sign === Sign::Minus ? '-' : '') . '1' . str_repeat('0', -$precision));
        }

        $fractionPartLen = isset($numParts[1]) ? strlen($numParts[1]) : 0;
        $adjustedAbsValue = $numParts[0] . (isset($numParts[1]) ? $numParts[1] : '');

        $roundTargetIndex = $integerPartLen + $precision - 1; // for $adjustedAbsValue's char index
        if ($precision >= 0) {
            /**
             * e.g. precision is 0, 0.5 or 0.51 ?
             * e.g. precision is 2, 0.005 or 0.0051 ?
             */
            $hasMoreDigits = $fractionPartLen > $precision + 1;
        } else {
            if ($fractionPartLen > 0) {
                $hasMoreDigits = true;
            } else {
                /**
                 * e.g.
                 * the origin value is 1234.56 (adjusted value is 123456)
                 * precision is -2
                 * $roundTargetIndex is 1 (mean "2")
                 * the next digit is "3"
                 * get "456" for $hasMoreDigits
                 */
                $check = substr($integerPart, $roundTargetIndex + 2);
                $check = str_replace('0', '', $check);
                $hasMoreDigits = strlen($check) > 0; // all digits are 0 ?
            }
        }

        /**
         * if the value is 123 and precision is -3, the $roundTargetIndex is -1
         * else, the value is 12345 and $roundTargetIndex is 2, $tmpRoundedAbsValue is 123
         */
        $tmpRoundedAbsValue = $roundTargetIndex === -1
            ? '0'
            : substr($adjustedAbsValue, 0, $roundTargetIndex + 1);
        $checkTargetChar = substr($adjustedAbsValue, $roundTargetIndex + 1, 1);

        switch ($mode) {
            case RoundingMode::HalfAwayFromZero:
            case RoundingMode::HalfTowardsZero:
            case RoundingMode::HalfEven:
            case RoundingMode::HalfOdd:
                if (in_array($checkTargetChar, ['6', '7', '8', '9'], true)) {
                    goto away_from_zero;
                } elseif ($checkTargetChar !== '5') {
                    goto towards_zero;
                }
                break;

            case RoundingMode::TowardsZero:
                goto towards_zero;

            case RoundingMode::AwayFromZero:
                if ($checkTargetChar !== '0' || $hasMoreDigits) {
                    goto away_from_zero;
                } else {
                    goto towards_zero;
                }

            case RoundingMode::NegativeInfinity:
            case RoundingMode::PositiveInfinity:
                if ($checkTargetChar === '0' && !$hasMoreDigits) {
                    goto towards_zero;
                }
                break;
        }

        switch ($mode) {
            case RoundingMode::HalfAwayFromZero:
                goto away_from_zero;

            case RoundingMode::HalfTowardsZero:
                if ($hasMoreDigits) {
                    goto away_from_zero;
                }
                break;

            case RoundingMode::HalfEven:
                if (bcmod($tmpRoundedAbsValue, '2') !== '0') {
                    goto away_from_zero;
                }
                break;

            case RoundingMode::HalfOdd:
                if (bcmod($tmpRoundedAbsValue, '2') === '0') {
                    goto away_from_zero;
                }
                break;

            case RoundingMode::NegativeInfinity:
                if ($sign === Sign::Minus) {
                    goto away_from_zero;
                }
                break;

            case RoundingMode::PositiveInfinity:
                if ($sign === Sign::Plus) {
                    goto away_from_zero;
                }
                break;
        }

    towards_zero:
        $roundedAbsValue = $tmpRoundedAbsValue;
        goto do_return;

    away_from_zero:
        $roundedAbsValue = bcadd($tmpRoundedAbsValue, '1', 0);
        if ($roundTargetIndex === -1) {
            $roundedAbsValue .= '0';
            $integerPartLen++;
        } else {
            $leadingZeroes = strspn($tmpRoundedAbsValue, '0');
            if ($leadingZeroes > 0) {
                $roundedAbsValue = str_repeat('0', $leadingZeroes) . $roundedAbsValue;
            }
        }

    do_return:
        if ($precision < 0) {
            $roundedAbsValue = $roundedAbsValue . str_repeat('0', $integerPartLen - strlen($roundedAbsValue));
        }
        if (str_replace('0', '', $roundedAbsValue) === '') {
            $sign = Sign::Plus;
        }
        $integerPart = substr($roundedAbsValue, 0, $integerPartLen);
        $fractionPart = $integerPart === $roundedAbsValue ?  '' : substr($roundedAbsValue, $integerPartLen);
        $fractionPartLen = strlen($fractionPart);

        if ($precision > 0) {
            $fractionPart = '.' . $fractionPart;
        }

        $ret = ($sign === Sign::Minus ? '-' : '') . $integerPart . $fractionPart;
        return new Number($ret);
    }

    public function compare(Number|string|int $num, ?int $scale = null): int
    {
        $num = $num instanceof Number ? $num : new Number($num);
        [$realValue, $realScale] = $this->getMetaData();
        [$numRealValue, $numRealScale] = $num->getMetaData();
        $scale = $scale ?? max($realScale, $numRealScale);
        return bccomp($realValue, $numRealValue, $scale);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function __serialize(): array
    {
        return ['value' => (string) $this];
    }

    public function __unserialize(array $data): void
    {
        if (!isset($data['value']) || !is_string($data['value'])) {
            $this->throwError(ErrorType::InvalidSerializationData);
        }

        $parsed = $this->parseStringNumber($data['value']);
        if ($parsed === false) {
            $this->throwError(ErrorType::InvalidSerializationData);
        }

        [
            $this->value,
            $this->scale,
            $realValue,
            $realScale,
        ] = $parsed;
        MetaData::setMetaData($this, $realValue, $realScale);
    }
    
    public function __clone()
    {
        $parsed = $this->parseStringNumber($this->value);

        [
            2 => $realValue,
            3 => $realScale,
        ] = $parsed;
        MetaData::setMetaData($this, $realValue, $realScale);
    }
}
