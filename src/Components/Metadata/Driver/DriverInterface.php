<?php

namespace Pucene\Components\Metadata\Driver;

use Pucene\Components\Metadata\ClassMetadataInterface;

interface DriverInterface
{
    public function loadMetadataForClass(\ReflectionClass $class): ?ClassMetadataInterface;

    /**
     * @return string[]
     */
    public function getAllClassNames(): array;
}
