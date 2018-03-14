<?php

namespace Pucene\Components\Metadata;

use Pucene\Components\Metadata\Cache\CacheResource;

interface ClassMetadataInterface
{
    public function getName(): string;

    /**
     * @return PropertyMetadataInterface[]
     */
    public function getProperties(): array;

    public function getProperty(string $name): PropertyMetadataInterface;

    public function hasProperty(string $name): bool;

    public function addProperty(PropertyMetadataInterface $property): void;

    /**
     * @return CacheResource[]
     */
    public function getResources(): array;

    public function addResource(CacheResource $resource): void;

    /**
     * @return mixed
     */
    public function createInstance();
}
