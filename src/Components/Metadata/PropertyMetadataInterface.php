<?php

namespace Pucene\Components\Metadata;

interface PropertyMetadataInterface
{
    public function getClass(): ClassMetadataInterface;

    public function getName(): string;

    /**
     * @param mixed $object
     *
     * @return mixed
     */
    public function getValue($object);

    /**
     * @param mixed $object
     * @param mixed $value
     */
    public function setValue($object, $value): void;
}
