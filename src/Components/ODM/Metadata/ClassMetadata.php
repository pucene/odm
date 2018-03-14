<?php

namespace Pucene\Components\ODM\Metadata;

use Pucene\Components\Metadata\ClassMetadata as BaseMetadata;

class ClassMetadata extends BaseMetadata
{
    /**
     * @var string
     */
    private $type;

    public function __construct(string $type, string $name, array $properties = [], array $resources = [])
    {
        parent::__construct($name, $properties, $resources);

        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
