<?php

namespace Pucene\Components\Metadata\Driver;

use Pucene\Components\Metadata\ClassMetadataInterface;

final class DriverChain implements DriverInterface
{
    /**
     * @var DriverInterface[]
     */
    private $drivers;

    /**
     * @param DriverInterface[] $drivers
     */
    public function __construct(array $drivers = [])
    {
        $this->drivers = $drivers;
    }

    public function loadMetadataForClass(\ReflectionClass $class): ?ClassMetadataInterface
    {
        foreach ($this->drivers as $driver) {
            if (null !== $metadata = $driver->loadMetadataForClass($class)) {
                return $metadata;
            }
        }

        return null;
    }

    public function getAllClassNames(): array
    {
        $classes = [];
        foreach ($this->drivers as $driver) {
            $driverClasses = $driver->getAllClassNames();
            if (!empty($driverClasses)) {
                $classes = array_merge($classes, $driverClasses);
            }
        }

        return $classes;
    }
}
