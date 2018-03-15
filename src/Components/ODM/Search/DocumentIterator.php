<?php

namespace Pucene\Components\ODM\Search;

use Pucene\Components\ODM\Converter\ArrayToObjectConverter;
use Pucene\Components\ODM\Metadata\ClassMetadata;

class DocumentIterator extends \IteratorIterator implements \Countable
{
    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @var ArrayToObjectConverter
     */
    private $converter;

    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $total;

    public function __construct(array $data, ClassMetadata $metadata, ArrayToObjectConverter $converter)
    {
        parent::__construct(new \ArrayObject($data['hits']['hits']));

        $this->metadata = $metadata;
        $this->converter = $converter;

        $this->count = count($data['hits']['hits']);
        $this->total = $data['hits']['total'];
    }

    public function current()
    {
        $data = parent::current();

        $document = $this->converter->convert($data['_source'], $this->metadata);
        $this->metadata->getIdProperty()->setValue($document, $data['_id']);

        return $document;
    }

    public function count()
    {
        return $this->count;
    }

    public function total()
    {
        return $this->total;
    }
}
