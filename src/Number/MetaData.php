<?php

namespace Saki\Number;

use BcMath\Number;
use LogicException;

class MetaData
{
    private static array $metaData = [];

    public static function deleteMetaData(Number $object): void
    {
        $hash = spl_object_hash($object);

        if (!isset(self::$metaData[$hash])) {
            throw new LogicException('Metadata not set');
        }

        unset(self::$metaData[$hash]);
    }

    public static function getMetaData(Number $object): array
    {
        $hash = spl_object_hash($object);

        if (!isset(self::$metaData[$hash])) {
            throw new LogicException('Metadata not set');
        }

        return self::$metaData[$hash];
    }

    public static function setMetaData(Number $object, string $value, int $scale): void
    {
        $hash = spl_object_hash($object);

        if (isset(self::$metaData[$hash])) {
            throw new LogicException('Metadata already set');
        }

        self::$metaData[$hash] = [$value, $scale];
    }
}
