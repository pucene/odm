<?php

namespace Pucene\Components\Metadata;

use Pucene\Components\Metadata\Driver\DriverInterface;

class MetadataFactory implements MetadataFactoryInterface
{
    /**
     * @var DriverInterface
     */
    private $driver;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function getAllClassNames(): array
    {
        return $this->driver->getAllClassNames();
    }

    public function getMetadataForClass(string $className): ?ClassMetadataInterface
    {
        $metadata = null;
        foreach ($this->getClassHierarchy($className) as $class) {
            $metadata = $this->merge($metadata, $this->loadClassMetadata($class));
        }

        return $metadata;
    }

    protected function loadClassMetadata(\ReflectionClass $class): ?ClassMetadataInterface
    {
        return $this->driver->loadMetadataForClass($class);
    }

    /**
     * @return \ReflectionClass[]
     */
    private function getClassHierarchy(string $className): array
    {
        $classes = [];
        $reflection = new \ReflectionClass($className);

        do {
            $classes[] = $reflection;
            $reflection = $reflection->getParentClass();
        } while (false !== $reflection);

        return array_reverse($classes, false);
    }

    protected function merge(?ClassMetadataInterface $a, ?ClassMetadataInterface $b): ?ClassMetadataInterface
    {
        if (!$a && !$b) {
            return null;
        } elseif (!$a) {
            return $b;
        } elseif (!$b) {
            return $a;
        }

        foreach ($b->getProperties() as $name => $propertyMetadata) {
            if (!$a->hasProperty($name)) {
                $a->addProperty($propertyMetadata);
            }
        }

        foreach ($b->getResources() as $resource) {
            $a->addResource($resource);
        }

        return $a;
    }
}
