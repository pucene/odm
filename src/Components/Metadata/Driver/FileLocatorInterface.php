<?php

namespace Pucene\Components\Metadata\Driver;

interface FileLocatorInterface
{
    public function findFileForClass(\ReflectionClass $class, string $extension): ?string;

    /**
     * @return string[]
     */
    public function findAllClasses(string $extension): array;
}
