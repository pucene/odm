<?php

namespace Pucene\Components\Metadata;

use Pucene\Components\Metadata\Cache\CacheInterface;
use Pucene\Components\Metadata\Driver\DriverInterface;

class CachableMetadataFactory extends MetadataFactory
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var array
     */
    private $loadedMetadata = [];

    public function __construct(CacheInterface $cache, DriverInterface $driver)
    {
        parent::__construct($driver);

        $this->cache = $cache;
    }

    public function getMetadataForClass(string $className): ?ClassMetadataInterface
    {
        if (array_key_exists($className, $this->loadedMetadata)) {
            return $this->loadedMetadata[$className];
        }

        return $this->loadedMetadata[$className] = parent::getMetadataForClass($className);
    }

    protected function loadClassMetadata(\ReflectionClass $class): ?ClassMetadataInterface
    {
        $metadata = $this->cache->fetch($class->getName());
        if ($metadata) {
            return $metadata;
        }

        $metadata = parent::loadClassMetadata($class);
        if ($metadata) {
            $this->cache->save($class->getName(), $metadata);
        }

        return $metadata;
    }
}
