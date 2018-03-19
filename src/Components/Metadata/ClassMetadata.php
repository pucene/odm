<?php

namespace Pucene\Components\Metadata;

use Pucene\Components\Metadata\Cache\CacheResource;

class ClassMetadata implements ClassMetadataInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var PropertyMetadataInterface[]
     */
    private $properties;

    /**
     * @var CacheResource[]
     */
    private $resources;

    /**
     * @param PropertyMetadataInterface[] $properties
     * @param CacheResource[] $resources
     */
    public function __construct(string $name, array $properties = [], array $resources = [])
    {
        $this->name = $name;
        $this->properties = $properties;
        $this->resources = $resources;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return PropertyMetadataInterface[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getProperty(string $name): PropertyMetadataInterface
    {
        return $this->properties[$name];
    }

    public function hasProperty(string $name): bool
    {
        return array_key_exists($name, $this->properties);
    }

    public function addProperty(PropertyMetadataInterface $property): void
    {
        $this->properties[$property->getName()] = $property;
    }

    /**
     * @return CacheResource[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    public function addResource(CacheResource $resource): void
    {
        $this->resources[] = $resource;
    }

    public function createInstance()
    {
        return $this->getReflection()->newInstanceWithoutConstructor();
    }

    private function getReflection(): \ReflectionClass
    {
        return new \ReflectionClass($this->name);
    }
}
