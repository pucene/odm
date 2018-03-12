<?php

namespace Pucene\Components\ODM\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Property
{
    /**
     * @var string
     */
    public $type;

    public function __construct(array $values)
    {
        if (!\is_string($values['value'])) {
            throw new \RuntimeException('"value" must be a string.');
        }

        $this->type = $values['value'];
    }
}
