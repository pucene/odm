<?php

namespace Pucene\Components\Metadata\Driver;

use Pucene\Components\Metadata\Cache\FileCacheResource;
use Pucene\Components\Metadata\ClassMetadataInterface;

abstract class FileDriver implements DriverInterface
{
    /**
     * @var FileLocatorInterface
     */
    private $locator;

    public function __construct(FileLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    public function loadMetadataForClass(\ReflectionClass $class): ?ClassMetadataInterface
    {
        $file = $this->locator->findFileForClass($class, $this->getExtension());
        if (!$file) {
            return null;
        }

        $metadata = $this->loadMetadataFromFile($class, $file);
        $metadata->addResource(new FileCacheResource($file));

        return $metadata;
    }

    public function getAllClassNames(): array
    {
        return $this->locator->findAllClasses($this->getExtension());
    }

    /**
     * Parses the content of the file, and converts it to the desired metadata.
     */
    abstract protected function loadMetadataFromFile(\ReflectionClass $class, string $file): ?ClassMetadataInterface;

    /**
     * Returns the extension of the file.
     */
    abstract protected function getExtension(): string;
}
