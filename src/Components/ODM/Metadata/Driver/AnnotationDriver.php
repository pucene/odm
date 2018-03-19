<?php

namespace Pucene\Components\ODM\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Pucene\Components\Metadata\ClassMetadataInterface;
use Pucene\Components\Metadata\Driver\FileDriver;
use Pucene\Components\Metadata\Driver\FileLocatorInterface;
use Pucene\Components\Metadata\PropertyMetadataInterface;
use Pucene\Components\ODM\Annotation\Document;
use Pucene\Components\ODM\Annotation\Id;
use Pucene\Components\ODM\Annotation\Property;
use Pucene\Components\ODM\Metadata\ClassMetadata;
use Pucene\Components\ODM\Metadata\IdPropertyMetadata;
use Pucene\Components\ODM\Metadata\PropertyMetadata;

class AnnotationDriver extends FileDriver
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader, FileLocatorInterface $locator)
    {
        parent::__construct($locator);

        $this->reader = $reader;
    }

    protected function loadMetadataFromFile(\ReflectionClass $class, string $file): ?ClassMetadataInterface
    {
        $name = $class->name;

        $type = null;
        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof Document) {
                $type = $annotation->type;
            }
        }

        $metadata = new ClassMetadata($type, $class->getName());

        foreach ($class->getProperties() as $property) {
            if ($property->class !== $name || (isset($property->info) && $property->info['class'] !== $name)) {
                continue;
            }

            $property = $this->loadPropertyMetadata($property, $metadata);
            if ($property instanceof PropertyMetadata) {
                $metadata->addProperty($property);

                continue;
            }

            $metadata->setIdProperty($property);
        }

        return $metadata;
    }

    protected function getExtension(): string
    {
        return 'php';
    }

    private function loadPropertyMetadata(
        \ReflectionProperty $property,
        ClassMetadata $metadata
    ): PropertyMetadataInterface {
        $type = null;
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof Property) {
                $type = $annotation->type;
            } elseif ($annotation instanceof Id) {
                return new IdPropertyMetadata($metadata, $property->getName());
            }
        }

        return new PropertyMetadata($type, $metadata, $property->getName());
    }
}
