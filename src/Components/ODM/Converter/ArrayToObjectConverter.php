<?php

namespace Pucene\Components\ODM\Converter;

use Pucene\Components\Metadata\ClassMetadataInterface;

class ArrayToObjectConverter
{
    /**
     * @return mixed
     */
    public function convert(array $data, ClassMetadataInterface $metadata)
    {
        $object = $metadata->createInstance();
        foreach ($metadata->getProperties() as $property) {
            if (!array_key_exists($property->getName(), $data)) {
                continue;
            }

            $property->setValue($object, $data[$property->getName()]);
        }

        return $object;
    }
}
