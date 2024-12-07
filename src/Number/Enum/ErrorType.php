<?php

namespace Saki\Number\Enum;

enum ErrorType
{
    case NotWellFormed;
    case TooLargeScale;
    case PowmodHasFractionParts;
    case PowmodExponentIsNegative;
    case InvalidSerializationData;
}
