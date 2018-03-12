<?php

namespace Pucene\Components;

use Pucene\Components\Metadata\ClassMetadataInterface;
use Pucene\Components\Metadata\PropertyMetadataInterface;

class PropertyMetadata implements PropertyMetadataInterface
{
    /**
     * @var ClassMetadataInterface
     */
    private $class;

    /**
     * @var string
     */
    private $name;

    public function __construct(ClassMetadataInterface $class, string $name)
    {
        $this->class = $class;
        $this->name = $name;
    }

    public function getClass(): ClassMetadataInterface
    {
        return $this->class;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $object
     *
     * @return mixed
     */
    public function getValue($object)
    {
        return $this->getReflection()->getValue($object);
    }

    /**
     * @param mixed $object
     * @param mixed $value
     */
    public function setValue($object, $value): void
    {
        $this->getReflection()->setValue($object, $value);
    }

    private function getReflection(): \ReflectionProperty
    {
        $reflection = new \ReflectionProperty($this->class->getName(), $this->name);
        $reflection->setAccessible(true);

        return $reflection;
    }
}
