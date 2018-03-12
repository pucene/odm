<?php

namespace Pucene\Components\Metadata\Cache;

use Psr\SimpleCache\CacheInterface as PsrCache;
use Pucene\Components\Metadata\ClassMetadata;
use Pucene\Components\Metadata\ClassMetadataInterface;

class Cache implements CacheInterface
{
    /**
     * @var PsrCache
     */
    private $cache;

    public function __construct(PsrCache $cache)
    {
        $this->cache = $cache;
    }

    public function fetch(string $className): ?ClassMetadataInterface
    {
        if (!$this->cache->has($className)) {
            return null;
        }

        /** @var ClassMetadata $metadata */
        $metadata = unserialize($this->cache->get($className));
        foreach ($metadata->getResources() as $resource) {
            if (!$resource->isFresh()) {
                return null;
            }
        }

        return $metadata;
    }

    public function save(string $className, ClassMetadataInterface $metadata): ClassMetadataInterface
    {
        $this->cache->set($className, serialize($metadata));

        return $metadata;
    }
}
