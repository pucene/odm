<?php

namespace Pucene\Components\Metadata\Cache;

class FileCacheResource extends CacheResource
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path, \DateTimeImmutable $createdAt = null)
    {
        parent::__construct($createdAt);

        $this->path = $path;
    }

    public function isFresh(): bool
    {
        if (!file_exists($this->path)) {
            return false;
        }

        return $this->createdAt->getTimestamp() < filemtime($this->path);
    }
}
