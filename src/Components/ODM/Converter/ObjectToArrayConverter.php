<?php

namespace Pucene\Components\ODM\Converter;

use Pucene\Components\Metadata\ClassMetadataInterface;

class ObjectToArrayConverter
{
    /**
     * @param mixed $object
     */
    public function convert($object, ClassMetadataInterface $metadata): array
    {
        $data = [];
        foreach ($metadata->getProperties() as $property) {
            $data[$property->getName()] = $property->getValue($object);
        }

        return $data;
    }
}
