<?php

namespace Pucene\Components\ODM\Metadata;

use Pucene\Components\Metadata\ClassMetadata as BaseMetadata;

class ClassMetadata extends BaseMetadata
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var IdPropertyMetadata
     */
    private $idProperty;

    public function __construct(string $type, string $name, array $properties = [], array $resources = [])
    {
        parent::__construct($name, $properties, $resources);

        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setIdProperty(IdPropertyMetadata $property): void
    {
        $this->idProperty = $property;
    }

    public function getIdProperty(): IdPropertyMetadata
    {
        return $this->idProperty;
    }

    public function getMapping(): array
    {
        $result = [];
        foreach ($this->getProperties() as $property) {
            $result[$property->getName()] = ['type' => $property->getType()];
        }

        return $result;
    }
}
