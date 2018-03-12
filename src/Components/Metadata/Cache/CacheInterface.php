<?php

namespace Pucene\Components\Metadata\Cache;

use Pucene\Components\Metadata\ClassMetadataInterface;

interface CacheInterface
{
    public function fetch(string $className): ?ClassMetadataInterface;

    public function save(string $className, ClassMetadataInterface $metadata): ClassMetadataInterface;
}
