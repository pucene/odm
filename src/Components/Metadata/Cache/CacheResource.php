<?php

namespace Pucene\Components\Metadata\Cache;

abstract class CacheResource
{
    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    public function __construct(\DateTimeImmutable $createdAt = null)
    {
        $this->createdAt = $createdAt ?: new \DateTimeImmutable();
    }

    abstract public function isFresh(): bool;
}
