<?php

namespace Pucene\Components\ODM\Metadata;

use Pucene\Components\Metadata\ClassMetadataInterface;
use Pucene\Components\Metadata\PropertyMetadata as BaseMetadata;

class PropertyMetadata extends BaseMetadata
{
    /**
     * @var string
     */
    private $type;

    public function __construct(string $type, ClassMetadataInterface $class, string $name)
    {
        parent::__construct($class, $name);

        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
